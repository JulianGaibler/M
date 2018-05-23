<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mensas;

class MensaController extends Controller
{

	/**
	 * 
	 *
	 * @return void
	 */
	public function getAll(Request $request) {
		$nolocation = $request->query('nolocation', true);
		$q; $r = [];
		for ($i=0; $i < 3; $i++) {
			if (!$nolocation) $q = Mensas::project(["location.adress"=>1 ,"location.times"=>1, "type"=>1, "nameA"=>1, "nameB"=>1, "mensa_id"=>1, "hasMenu"=>1,  "_id"=>1]);
			else $q = Mensas::project([]);
			$sub = $q->where('type', $i)->orderBy('nameA', 'asc')->get();
			if(!$nolocation) $this->getMissingAdditives($sub);
			$this->getWhenOpen($sub, $nolocation);
			$r[] = $sub;
		}
		return $r;
	}

	/**
	 * 
	 *
	 * @return void
	 */
	public function getSome(Request $request, $multiple) {
		$arr = explode(";", $multiple, Mensas::count());
		$v = Mensas::find($arr);
		$this->getMissingAdditives($v);
		$this->getWhenOpen($v);
		return $v;
	}

	/**
	 * ---
	 *
	 * @return ---
	 */
	private function getWhenOpen(&$sub, $nolocation=false) {
		$currentweekday = (((((int)date('w')-1) % 7) + 7) % 7);
		$currentminutetime = (((int)date('G'))*60) + ((int)date('i'));
		foreach($sub as $entry){
			$nextdayopen = $currentweekday;
			$inDays = 0;
			$hours = $entry['location']['times'][0]['hours'];
			while ($inDays<10) {
				if (sizeof($hours) <= $nextdayopen) {
					$nextdayopen = ((($nextdayopen+1) % 7) + 7) % 7;
					$inDays++;
				} else if ($inDays==0 && $hours[$nextdayopen]['close']-20 < $currentminutetime) {
					$nextdayopen = ((($nextdayopen+1) % 7) + 7) % 7;
					$inDays++;
				} else break;
			}
			if ($nolocation) {
				$x = $entry['location'];
				unset($x['times']);
				$entry['location'] = $x;
			}
			$entry['whenOpen'] = date(DATE_ATOM, strtotime("+".$inDays." days midnight"));
		}
	}

	/**
	 * 
	 *
	 * @return void
	 */
	public function getNear(Request $request) {
		$lat = floatval($request->query('lat', 0));
		$lng = floatval($request->query('lng', 0));
		$r = floatval($request->query('r', 0));
		$type = floatval($request->query('type', -1));
		if ($lat==0 || $lng==0 || $r==0)
			return (new Response("need coordniates 'lat' and 'lng' aswell as radius 'r'", 400));

		$mongodb = DB::getMongoClient()->m->selectCollection('mensas');

		$pipeline = array(
			array(
				'$geoNear'=> array(
					'near' => array($lng, $lat),
					'spherical' => true,
					'maxDistance' => $r/6371,
					'distanceMultiplier' => 6371, // Conversion to km
					'distanceField' => "distance"
				)
			),
			array(
				'$project'=> array(
					'_id' => 1,
					'type' => 1,
					'distance' => 1
				)
			),
		);

		if ($type!=-1) {
			$pipeline2 = array(
				array(
					'$match'=> array(
						'type' => $type
					)
				)
			);
			$pipeline = array_merge($pipeline,$pipeline2);
		}

		$pipeline2 = array(
			array(
				'$limit'=> 5
			)
		);
		$pipeline = array_merge($pipeline,$pipeline2);

		$returnCursor = $mongodb->aggregate($pipeline);
		$arrReturn = $returnCursor->toArray();

		foreach ($arrReturn as $value) {
			$value['_id'] = (string) $value['_id'];
		}

		return $arrReturn;
	}

	/**
	 * ---
	 *
	 * @return ---
	 */
	private function getMissingAdditives(&$sub) {
		foreach($sub as $entry) {
			$provider = $entry['origin']['provider'];
			$res = DB::collection('additives')->where('supportedBy', ['$not' => ['$in' => [$provider]]])->project(["id"=>1, "_id"=>0])->get();
			$entry['unsupportedAdditives'] = array_column($res->toArray(), 'id');
		}
	}
}
