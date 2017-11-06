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
		$this->getWhenOpen($v);
		return $v;
	}

	/**
	 * Converts DOMcrawler mumbo-jumbo into clean strings
	 *
	 * @return String
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
}
