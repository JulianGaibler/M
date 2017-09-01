<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;

class MensaController extends Controller
{
	/**
	 * Fetches all Mensa Names from STW Website and returnes them in JSON
	 *
	 * @return void
	 */
	public function allMensas($lang) {
		$guzzleClient = new GuzzleClient();
		$link = "https://www.stw.berlin/xhr/speiseplan-und-standortdaten.html";
		$crawler = $guzzleClient->request('POST', $link,
			['form_params' => ['resources_id' => '323'],
			'headers' => [ 'Referer' => "https://www.stw.berlin/de/"]]);
		// Language has to be fixed because english and german mensas have diffrent IDs -_-


		$crawler = new Crawler((string) $crawler->getBody(true));
		$mensas = [];
		$crawler = $crawler
			->filter('#listboxEinrichtungen > option')
			->reduce(function (Crawler $node, $i) use (&$mensas) {
				$val = [
					'id' => (int) $node->attr('value'),
					'name' => $this->convertCrawlerString($node->text())];
				$mensas[] = $val;
			}
		);
		return (new Response(json_encode($mensas), 200))
						->header('Content-Type', "json")
						->header('charset', "utf-8")
						->header('Access-Control-Allow-Origin', '*');
	}

	/**
	 * Fetches all allergies from STW Website and returnes them in JSON
	 *
	 * @return void
	 */
	public function allAdditives($lang) {
		$guzzleClient = new GuzzleClient();
		$link = "https://www.stw.berlin/xhr/speiseplan-und-standortdaten.html";
		$crawler = $guzzleClient->request('POST', $link,
			['form_params' => ['resources_id' => '323'],
			'headers' => [ 'Referer' => "https://www.stw.berlin/{$lang}/"]]);


		$crawler = new Crawler((string) $crawler->getBody(true));
		$mensas = [];
		$crawler = $crawler
			->filter('#splFilterPanel .col-md-4')
			->reduce(function (Crawler $node, $i) use (&$mensas) {

				$origText = $this->convertCrawlerString($node->text());
				if ($node->filter('span')->count()>0) $origText = $this->convertCrawlerString($node->filter('span')->first()->attr('title'));

				preg_match("/\(([^\)]*)\)/", $origText, $aMatches);
				$id = $aMatches[1];
				$name = substr($origText, strpos($origText, ")") + 1);


				$val = [
					'id' => $id,
					'name' => trim($name)];
				$mensas[] = $val;
			}
		);
		return (new Response(json_encode($mensas), 200))
						->header('Content-Type', "json")
						->header('charset', "utf-8")
						->header('Access-Control-Allow-Origin', '*');
	}

		/**
	 * Fetches basic mensa info from STW Website and returnes them in JSON
	 *
	 * @return void
	 */
	public function getInfo($lang, $mensaID) {
		$guzzleClient = new GuzzleClient();
		$link = "https://www.stw.berlin/xhr/speiseplan-und-standortdaten.html";

		$param = ['resources_id' => $mensaID];

		$crawler = $guzzleClient->request('POST', $link,
			['form_params' => $param,
			'headers' => [ 'Referer' => "https://www.stw.berlin/{$lang}/"]]);

		$crawler = new Crawler((string) $crawler->getBody(true));
		$info = [];
		$info['name'] = $this->convertCrawlerString($crawler
			->filter('#listboxEinrichtungen > option:selected')->first()->text());

		$info['hasMenu'] = false;
		if ($crawler->filter('#splFilterPanel')->count()>0) {
			$info['hasMenu'] = true;
		} else {

			$info['desc'] = $this->convertCrawlerString($crawler->filter('#speiseplan > .white-box')->first()->html());
		}

		$loca = [];
		$crawler = $crawler
			->filterXpath('//article[contains(@class, "col-sm-12")]/div/div[contains(@class, "row")]/div/i')
			->reduce(function (Crawler $node, $k) use (&$loca) {
				$category = explode('glyphicon-', $node->attr('class'))[1];
				$cNode = $node->parents()->parents();
				switch ($category) {
					case "map-marker":
					case "envelope":
					case "earphone":
					case "transfer": {
						$cNode = $cNode->filter('.col-xs-10');
						$attrname = "none";
						switch ($category) {
							case "map-marker":
								$attrname = "adress"; break;
							case "envelope":
								$attrname = "mail"; break;
							case "earphone":
								$attrname = "phone"; break;
							case "transfer":
								$attrname = "transfer"; break;
						}
						if ($cNode->count()>0)
							$loca[$attrname] = $this->convertCrawlerString($cNode->first()->text());
						break;
					}
					case "time": {

						/*
						 *	- Make time obj, think about time representation.
						 *	- use nextAll() to iterate over nodes until icon comes
						*/
						$times = [];
						try {
							$next = $cNode->nextAll();
							$i = 0;
							while(true) {

								$next = $next->filter('.row')->first();
								if ($next->filter('i.glyphicon')->count()>0) break;

								// $obj = [
								// 	'name' => 'default',
								// 	'ampel' => $ampel,
								// 	'prices' => $prices,
								// 	'labels' => $labels,
								// 	'additives' => $additives];

								$row = $this->convertCrawlerString($next->text());
								echo $row;
								echo "\n";
								// preg_match("regex", $row, $timeMatches); //TODO
								// if (/* array == 2 */) {

								// } else /*throw */;



								$next = $next->nextAll();

							}
						} catch(Exception $e) {}
						break;
					}
				}

			}
		);
		$info['location'] = $loca;

		return (new Response(json_encode($info), 200))
						->header('Content-Type', "json")
						->header('charset', "utf-8")
						->header('Access-Control-Allow-Origin', '*');
	}


	/**
	 * Fetches the menu from a mensa from the STW Website and returnes them in JSON
	 *
	 * @return void
	 */
	public function getMenu(Request $request, $lang, $mensaID) {
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
		$crawler = $crawler
			->filter('.splGroupWrapper .splMeal')
			->reduce(function (Crawler $node, $j) use (&$mensas) {

				$additives = explode(',', $node->attr('lang'));
				if (strlen($additives[0]) < 1) $additives = [];

				$name = $this->convertCrawlerString($node->filter('span.bold')->first()->text());

				$prices = explode('/', substr(
					$this->convertCrawlerString(
							$node->filter('.col-xs-6.col-md-3.text-right')->first()->text())),2);

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
				$mensas[] = $val;
			}
		);
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
