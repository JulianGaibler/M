<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\DB;
use App\Mensas;
use Illuminate\Http\Request;

$app->group(['prefix' => 'api/'], function () use ($app) {
	$app->get('/mensas', function(Request $request) {

		$nolocation = $request->query('nolocation', true);
		$q; $r = [];
		for ($i=0; $i < 3; $i++) {
			if (!$nolocation) $q = Mensas::project(["location.adress"=>1, "type"=>1, "nameA"=>1, "nameB"=>1, "mensa_id"=>1, "_id"=>1])->orderBy('nameA', 'desc');
			else $q = Mensas::project([]);
			$r[] = $q->where('type', $i)->get();
		}
		return $r;
	});

	$app->get('/mensas/{multiple}', function($multiple) {
		$arr = explode(";", $multiple, Mensas::count());
		$v = Mensas::find($arr);
		return $v;
	});

	$app->get('/menu/{mensaID}', 'CrawlerController@getMenu');

	$app->get('/additives', function() {
		$v = DB::collection('additives')->project(['_id' => 0])->get();
		return $v;
	});
});
