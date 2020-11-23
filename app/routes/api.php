<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix'=>'{version}'], function(){
    version(app()->request->segment(2));

    Route::get('/test/{param?}', [ controller('Tests') , 'test']);

    Route::get('/fish/{fish_id}',[ controller('Fishes'), 'show' ]);
    Route::post('/fish',[ controller('Fishes'), 'create' ]);
    Route::get('/fishes',[ controller('Fishes'), 'index' ]);

});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
