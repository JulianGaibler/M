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
use App\mUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


$app->group(['prefix' => 'api/', 'middleware' => ['cacheFetch', 'cachePut']], function () use ($app) {

	$app->get('/info/servertime', function(Request $request) {
		return response()->json(['currentTime' => date(DATE_ATOM)], 200);
	});

	$app->put('/user/create', function(Request $request) {
		$user = new mUser();
		$user->save();
		return response()->json(['_id' => $user->_id], 200);
	});

	$app->get('/mensas', 'MensaController@getAll');

	$app->get('/mensas/{multiple}', 'MensaController@getSome');

	$app->get('/menu/{mensaID}', 'CrawlerController@getMenu');

	$app->get('/additives', function() {
		$v = DB::collection('additives')->project(['_id' => 0,'supportedBy' => 0])->get();
		return $v;
	});

	$app->get('/sections', function() {
		$v = DB::collection('menu_sections')->project(['_id' => 0, 'translations' => 0])->get();
		return $v;
	});
});
