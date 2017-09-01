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

$app->group(['prefix' => 'api/{lang}'], function () use ($app) {
	$app->get('/mensas', 'MensaController@allMensas');
	$app->get('/additives', 'MensaController@allAdditives');
	$app->get('/mensas/{mensaIDmensaID}', 'MensaController@getInfo');
	$app->get('/mensas/{mensaIDmensaID}/menu', 'MensaController@getMenu');
});
