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
	 * @return void
	 */
	public function getMenu(Request $request, $id) {
		//$lang = $request->query('lang', 'de');
		$lang = 'de';

		$mensa = Mensas::where('_id', strval($id))->first();
		if (is_null($mensa)) return [];

		$mensaID = $mensa->mensa_id;
		//if (strcmp($lang,"en") == 0) $mensaID = $mensa->mensa_id_en;

		$guzzleClient = new GuzzleClient();
		$link = "https://www.stw.berlin/xhr/speiseplan-und-standortdaten.html";

		$param = ['resources_id' => $mensaID];
		if (null !== ($request->input('date'))) {
			$param['date'] = $request->input('date');
		}


		$crawler = $guzzleClient->request('POST', $link,
			['form_params' => $param,
			'headers' => [ 'Referer' => "https://www.stw.berlin/{$lang}/"]]);


		$crawler = new Crawler((string) $crawler->getBody(true));
		$mensas = [];
		$crawler
			->filter('.splGroupWrapper')
			->reduce(function (Crawler $onode, $j) use (&$mensas) {
				$category = [];
				$onode->filter('.splMeal')
				->reduce(function (Crawler $node, $k) use (&$category) {

					$additives = explode(',', $node->attr('lang'));
					if (strlen($additives[0]) < 1) $additives = [];

					$name = $this->convertCrawlerString($node->filter('span.bold')->first()->text());

					$prices = explode('/', substr($this->convertCrawlerString($node->filter('.col-xs-6.col-md-3.text-right')->first()->text()),2));

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
				$category_name = $this->convertCrawlerString($onode->filter('.splGroup')->first()->text());
				$mensas[$category_name] = $category;
			});
		return (new Response(json_encode($mensas), 200))
						->header('Content-Type', "json")
						->header('charset', "utf-8")
						->header('Access-Control-Allow-Origin', '*');
	}

	/**
	 * Converts DOMcrawler mumbo-jumbo into clean strings
	 *
	 * @return String
	 */
	private function convertCrawlerString($string) {
			$string = trim(utf8_decode($string));
			$string = preg_replace("/\n/", '', $string);
			$string = preg_replace("/\"|'/", '"', $string);
			$string = preg_replace('!\s+!', ' ', $string);
			return $string;
	}
}
