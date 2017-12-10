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


$app->group(['prefix' => 'api/'], function () use ($app) {

	$app->get('/info/servertime', function(Request $request) {
		return response()->json(['currentTime' => date(DATE_ATOM)], 200);
	});

	$app->put('/user/create', function(Request $request) {
		$user = new mUser();
		$user->save();
		return response()->json(['_id' => $user->_id], 200);
	});
	$app->post('/user/update', function(Request $request) {
		$res = mUser::where('_id', $request->getContent())->first();
		if ($res) {
			$res->touch();
			return response()->json('updated', 200);
		} else return response()->json('not updated', 400);
	});

	$app->get('/mensas', ['middleware' => ['cacheFetch', 'cachePut'], 'uses' => 'MensaController@getAll']);

	$app->get('/mensas/{multiple}', ['middleware' => ['cacheFetch', 'cachePut'], 'uses' => 'MensaController@getSome']);

	$app->get('/menu/{mensaID}', ['middleware' => ['cacheFetch', 'cachePut'], 'uses' => 'CrawlerController@getMenu']);

	$app->get('/additives', ['middleware' => ['cacheFetch', 'cachePut'], function() use ($app) {
		$v = DB::collection('additives')->project(['_id' => 0,'supportedBy' => 0])->get();
		return $v;
	}]);

	$app->get('/sections', ['middleware' => ['cacheFetch', 'cachePut'], function() use ($app) {
		$v = DB::collection('menu_sections')->project(['_id' => 0, 'translations' => 0])->get();
		return $v;
	}]);
});
