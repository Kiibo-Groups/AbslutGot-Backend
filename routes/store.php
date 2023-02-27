<?php

Route::post('store/login','StoreController@login');
Route::get('store/storeOpen/{id}','StoreController@storeOpen');
Route::get('store/homepage','StoreController@homepage');
Route::get('store/startRide','StoreController@startRide');
Route::get('store/userInfo/{id}','StoreController@userInfo');
Route::post('store/updateInfo','StoreController@updateInfo');
Route::get('store/lang','ApiController@lang');
Route::post('store/updateLocation','StoreController@updateLocation');
Route::get('store/orderProcess','StoreController@orderProcess');
Route::get('store/getItem','StoreController@getItem');
Route::get('store/changeStatus','StoreController@changeStatus');
Route::get('store/getStaffNearby/{id}','StoreController@getStaffNearby');
Route::get('store/overview','StoreController@overview');
Route::get('store/city','StoreController@city');
Route::get('store/updateCity','StoreController@updateCity');
?>