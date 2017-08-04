<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client as GuzzleClient;

class MensaController extends Controller
{
	/**
	 * Fetches all Mensas from STW Website and returnes them in JSON
	 *
	 * @return void
	 */
	public function index($lang)
	{
		$guzzleClient = new GuzzleClient();
		$link = "https://www.stw.berlin/xhr/speiseplan-und-standortdaten.html";
		$crawler = $guzzleClient->request('POST', $link,
			['form_params' => ['resources_id' => '323'],
			'headers' => [ 'Referer' => "https://www.stw.berlin/{$lang}/"]]);


		$crawler = new Crawler((string) $crawler->getBody(true));
		$mensas = [];
		$crawler = $crawler
			->filter('#listboxEinrichtungen > option')
			->reduce(function (Crawler $node, $i) use (&$mensas) {
				$val = [
					'name' => utf8_decode($node->text()),
					'id' => (int) $node->attr('value')];
				$mensas[] = $val;
			}
		);
		return (new Response(json_encode($mensas), 200))
						->header('Content-Type', "json")
						->header('charset', "utf-8")
						->header('Access-Control-Allow-Origin', '*');
		}
}
