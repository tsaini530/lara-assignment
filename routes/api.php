<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'api','middleware' => ['api']] , function(){
	Route::get('/shops', 'ShopController@index');	
	Route::post('/shop', 'ShopController@createShop');	
	Route::post('/shop/{id}/product', 'ShopController@createProduct');	
	Route::get('/shop/{id}/products', 'ShopController@allProduct');	
	Route::put('/shop/{id}/product/{product_id}','ShopController@updateProduct');
	Route::patch('/shop/{id}/product/{product_id}','ShopController@updateProduct');	
	Route::delete('/shop/{id}/product/{product_id}','ShopController@deleteProduct');



});