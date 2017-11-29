<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mensas;

class CrawlerController extends Controller
{

	/**
	 * Fetches the menu from a mensa from the STW Website and returnes them in JSON
	 *
	 * @return array of menu or empty array
	 */
	public function getMenu(Request $request, $id) {
		$lang = $request->query('lang', 'de');
		$date = $request->query('date', null);
		if ($date!=null) $date = date_create($date);

		$mensa = Mensas::where('_id', strval($id))->first();
		if (!is_null($mensa)) {
			$result = $mensa->origin;

			switch ($result["provider"]) {
				case "5a1402220de85702c47c632d":
					$res = $this->getStwBerlinMenu($result["mensa_id"], $lang, $date);
					return response()->json($res);
					break;
				case "5a14027e0de85702c47c632e":
					$res = $this->getPersonalkantinetuberlinMenu($result["nr"], $lang, $date);
					return response()->json($res);
					break;
			}
		}
		return (new Response("Does exist but does not have a handler", 404));

	}

	/**
	 * Fetches the menu from a mensa from the STW Website and returnes them in JSON
	 *
	 * @return void
	 */
	public function getStwBerlinMenu($mensaID, $lang, $date) {

		$guzzleClient = new GuzzleClient();
		$link = "https://www.stw.berlin/xhr/speiseplan-und-standortdaten.html";

		$param = ['resources_id' => $mensaID];
		if (null != $date) {
			$param['date'] = date_format($date, "Y-m-d");
		}


		$crawler = $guzzleClient->request('POST', $link,
			['form_params' => $param,
			'headers' => [ 'Referer' => "https://www.stw.berlin/{$lang}/"]]);


		$crawler = new Crawler((string) $crawler->getBody(true));
		$mensas = [];
		$crawler
			->filter('.splGroupWrapper')
			->reduce(function (Crawler $onode, $j) use (&$mensas) {
				
				if ($onode->filter('.splMeal')->count()>0) {

					$category = [];
					$onode->filter('.splMeal')
					->reduce(function (Crawler $node, $k) use (&$category) {

						$additives = explode(',', $node->attr('lang'));
						if (strlen($additives[0]) < 1) $additives = [];

						$name = $this->convertCrawlerString($node->filter('span.bold')->first()->text(), true);

						$prices = explode('/', substr($this->convertCrawlerString($node->filter('.col-xs-6.col-md-3.text-right')->first()->text(), true),2));

						if (strlen($prices[0]) < 1) {
							$prices[0] = 0.0;
						}
						if (count($prices) < 2) {
							$prices[1] = $prices[0];
							$prices[2] = $prices[0];
						}
						foreach ($prices as $p => $price) {
							$prices[$p] = floatval(str_replace(',', '.', str_replace('.', '', $price)));
						}

						$ampel = -1;
						$labels = [];

						$node->filter('.col-xs-12.col-md-3 > .splIcon')
						->reduce(function (Crawler $node2, $i) use (&$ampel, &$labels) {

							$curr = explode('/', rtrim($node2->attr('src'), '/'));
							$curr = preg_replace('/\\.[^.\\s]{2,4}$/', '', end($curr));


							$pos = strpos($curr, 'ampel_');
							if ($pos !== false) {
								switch ($curr[$pos+7]) {
									case "r": //green
										$ampel = 0;
										break;
									case "e": //yellow
										$ampel = 1;
										break;
									case "o": //red
										$ampel = 2;
										break;
								}
							} else {
								switch ($curr) {
									case "1":
										$labels[] = "vegetarian";
										break;
									case "15":
										$labels[] = "vegan";
										break;
									case "43":
										$labels[] = "eco";
										break;
									case "18":
										$labels[] = "bio";
										break;
									case "38":
										$labels[] = "msc";
										break;
								}
							}
							
						});


						$val = [
							'name' => $name,
							'ampel' => $ampel,
							'prices' => $prices,
							'labels' => $labels,
							'additives' => $additives];
						$category[] = $val;
					});
					$qword = $this->convertCrawlerString($onode->filter('.splGroup')->first()->text(), true);
					$qres = DB::collection('menu_sections')->where('translations', ['$in' => [$qword]])->project(["tag"=>1])->get();
					if (count($qres)>0) $qword = $qres[0]["tag"];
					$mensas[$j]['name'] = $qword;
					$mensas[$j]['items'] = $category; 
					}
				});
		return $mensas;
	}

	/**
	 * Fetches the menu from a mensa from the Personalkantinen Website and returnes them in JSON
	 *
	 * @return array of menu or empty array
	 */
	public function getPersonalkantinetuberlinMenu($id, $lang, $date) {

		$guzzleClient = new GuzzleClient();
		$link = "http://personalkantine.personalabteilung.tu-berlin.de/";

		if (null == $date) return (new Response("personalkantine TU needs date", 400));

		$dateString = date_format($date, "j.n.Y");

		$crawler = $guzzleClient->request('GET', $link);


		$crawler = new Crawler((string) $crawler->getBody(true));
		$crawler = $crawler->filter('.Menu__accordion')->eq($id);
		$mensas = [];

		$crawler
			->filter('.Menu__accordion > li')
			->reduce(function (Crawler $onode, $j) use (&$dateString, &$lang, &$mensas) {
			if (strpos($this->convertCrawlerString($onode->filter('h2')->first()->text()), $dateString) !== false) {
				$food = [];
				$onode->filter('ul > li')
					->reduce(function (Crawler $node, $k) use (&$category, &$food, &$mensas) {

						$prices[0] = preg_replace("[^0-9,]", "", $this->convertCrawlerString($node->filter('.price')->first()->text()));
						$prices[0] = floatval(str_replace(',', '.', $prices[0]));
						$prices[1] = $prices[0];
						$prices[2] = $prices[0];

						foreach ($node as $inNode) {
							$inNode->parentNode->removeChild($inNode);
						}

						$labels = [];
						$additives = [];
						$additivesArr = array("m"=>"30", "1"=>"21", "2"=>"6", "3"=>"7", "4"=>"8", "5"=>"3", "6"=>"37", "7"=>"22", "8"=>"26", "9"=>"27", "10"=>"28", "11"=>"29", "12"=>"32", "13"=>"36", "14"=>"23", "sch"=>"2", "r"=>"2a", "g"=>"2b", "f"=>"2c", "l"=>"2d", "w"=>"2e");
						$labelsArr = array("vs" => "lightfood",
										   "s" => "lightfood",
										   "v" => "vegetarian",
										   "p" => "corned");

						$subtext = explode(" (", $this->convertCrawlerString($node->text()));
						$name = $subtext[0];

						if (sizeof($subtext)>1) {
							$foreignAdditives = preg_split("/(\W)+/", $subtext[1]);
							foreach ($foreignAdditives as $fa) {
								$fa = strtolower($fa);
								if ($fa=="") continue;
								if (array_key_exists($fa, $additivesArr)) {
									$additives[] = $additivesArr[$fa];
									continue;
								} else if (array_key_exists($fa, $labelsArr)) {
									$labels[] = $labelsArr[$fa];
									continue;
								}
							}
						}

						$val = [
							'name' => $name,
							'ampel' => null,
							'prices' => $prices,
							'labels' => $labels,
							'additives' => $additives];
						$food[] = $val;
				});
				$mensas[0]['name'] = 'maindishes';
				$mensas[0]['items'] = $food; 
				return;
			}
			});
		return $mensas;
	}

	/**
	 * Converts DOMcrawler mumbo-jumbo into clean strings
	 *
	 * @return String
	 */
	private function convertCrawlerString($string, $decode=false) {
			if ($decode) $string = trim(utf8_decode($string));
			$string = preg_replace("/\n/", '', $string);
			$string = preg_replace("/\"|'/", '"', $string);
			$string = preg_replace('!\s+!', ' ', $string);
			return $string;
	}
}
