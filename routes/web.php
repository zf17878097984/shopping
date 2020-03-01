<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix'=>'/api/shopping'],function (){
    Route::get('/login', 'userLogingController@login');
    Route::get('/loginOut', 'userLogingController@loginOut');
    Route::post('/register','userLogingController@register');
});
Route::group(['prefix'=>'/api/manage'],function (){
    Route::get('/login', 'manage\adminLoginController@login');
    Route::get('/loginOut', 'manage\adminLoginController@loginOut');
    Route::post('/register','manage\adminLoginController@register');
});

Route::group(['prefix'=>'/api/manage/product','middleware'=>['admin.auth']],function(){
    Route::get('/', 'manage\productController@getAll');
    Route::get('/{id}', 'manage\productController@getById');
    Route::get('/getByTypeId/{typeId}', 'manage\productController@getByTypeId');
    Route::post('/', 'manage\productController@insert');
    Route::put('/', 'manage\productController@update');
    Route::delete('/{id}', 'manage\productController@delete');
    Route::post('/upload/{id}','manage\productController@upload');
});

Route::group(['prefix'=>'/api/manage/type','middleware'=>['admin.auth']],function(){
    Route::get('/', 'manage\typeController@getAll');
    Route::get('/{id}', 'manage\typeController@getById');
    Route::post('/', 'manage\typeController@insert');
    Route::put('/', 'manage\typeController@update');
    Route::delete('/{id}', 'manage\typeController@delete');
});


Route::group(['prefix'=>'/api/shopping/shoppingCart','middleware'=>['user.auth']],function(){
    Route::get('/', 'shopping\shoppingCartController@getAll');
    Route::get('/getById/{id}', 'shopping\shoppingCartController@getById');
    Route::post('/', 'shopping\shoppingCartController@insert');
    Route::post('/batchInsert', 'shopping\shoppingCartController@batchInsert');
    Route::delete('/{id}', 'shopping\shoppingCartController@delete');
    Route::delete('/batchDelete/{ids}', 'shopping\shoppingCartController@batchDelete');
});

Route::group(['prefix'=>'/api/shopping/wallet','middleware'=>['user.auth']],function(){
    Route::get('/', 'shopping\walletController@getWalletByUserId');
    Route::put('/addCredit', 'shopping\walletController@addCredit');
});

Route::group(['prefix'=>'/api/shopping'],function(){
    Route::get('/', 'shopping\showController@getAll');
    Route::get('/type', 'shopping\showController@getTypeAll');
    Route::get('/getByTypeId/{typeId}', 'shopping\showController@getByTypeId');
    Route::get('/{id}', 'shopping\showController@getById');

});

Route::group(['prefix'=>'/api/shopping/user'],function(){
   // Route::get('/', 'shopping\userController@getUser');
});

Route::group(['prefix'=>'/api/shopping/address','middleware'=>['user.auth']],function (){
    Route::get('/getByUserId','shopping\addressController@getByUserId');
    Route::get('/{id}','shopping\addressController@getById');
    Route::delete('/{id}','shopping\addressController@delete');
    Route::post('/','shopping\addressController@insert');
    Route::put('/','shopping\addressController@update');
});

