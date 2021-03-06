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

Route::group(['prefix'=>'/api/manage/product','middleware'=>['admin.auth','authority']],function(){
    Route::get('/', 'manage\productController@getAll');
    Route::get('/{id}', 'manage\productController@getById');
    Route::get('/getByTypeId/{typeId}', 'manage\productController@getByTypeId');
    Route::post('/', 'manage\productController@insert');
    Route::put('/', 'manage\productController@update');
    Route::delete('/{id}', 'manage\productController@delete');
    Route::post('/upload/{id}','manage\productController@upload');
});

Route::group(['prefix'=>'/api/manage/type','middleware'=>['admin.auth','authority']],function(){
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

Route::group(['prefix'=>'/api/shopping/product'],function(){
    Route::get('/', 'shopping\showController@getAll');
    Route::get('/type', 'shopping\showController@getTypeAll');
    Route::get('/getByTypeId/{typeId}', 'shopping\showController@getByTypeId');
    Route::get('/getById/{id}', 'shopping\showController@getById');
    Route::get('/saleWell', 'shopping\showController@saleWell');
    Route::get('/newest', 'shopping\showController@newest');
});

Route::group(['prefix'=>'/api/admin'],function(){
    Route::get('/', 'manage\adminController@getAdminSession');
    Route::put('/', 'manage\adminController@updateAdmin');
});

Route::group(['prefix'=>'/api/user/'],function(){
    Route::get('/', 'manage\adminController@getUsersSession');
    Route::put('/', 'manage\adminController@updateUser');

});

Route::group(['prefix'=>'/api/shopping/address','middleware'=>['user.auth']],function (){
    Route::get('/getByUserId','shopping\addressController@getByUserId');
    Route::get('/{id}','shopping\addressController@getById');
    Route::delete('/{id}','shopping\addressController@delete');
    Route::post('/','shopping\addressController@insert');
    Route::put('/','shopping\addressController@update');
});

Route::group(['prefix'=>'/api/shopping/order','middleware'=>['user.auth']],function(){
    Route::post('/', 'shopping\orderController@addOrder');
    Route::post('/batchAdd', 'shopping\orderController@batchAdd');
    Route::put('/pay/{id}', 'shopping\orderController@pay');
    Route::get('/', 'shopping\orderController@getOrder');
    Route::get('/test', 'shopping\orderController@test');
});
//权限认证
Route::group(['prefix'=>'/api/manage/role','middleware'=>['admin.auth','admin.authority']],function(){
    Route::put('/updateAdminRole/{id}/{roleId}', 'manage\RoleController@updateAdminRole');
//    Route::get('/test', 'manage\RoleController@test');
});
Route::resource('/api/manage/role','manage\RoleController')->middleware('admin.auth','admin.authority');

