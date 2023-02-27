<?php

Route::post('dboy/login','DboyController@login');
Route::post('dboy/signup','DboyController@signup');
Route::get('dboy/homepage_ext','DboyController@homepage_ext');
Route::get('dboy/homepage','DboyController@homepage');
Route::get('dboy/overview','DboyController@overview');
Route::get('dboy/startRide','DboyController@startRide');
Route::get('dboy/userInfo/{id}','DboyController@userInfo');
Route::post('dboy/updateInfo','DboyController@updateInfo');
Route::get('dboy/lang','ApiController@lang');
Route::post('dboy/updateLocation','DboyController@updateLocation');
Route::get('dboy/staffStatus/{id}','DboyController@staffStatus');
Route::get('dboy/getPolylines','DboyController@getPolylines');
Route::post('dboy/rejected','DboyController@rejected');
Route::get('dboy/chkNotify','DboyController@chkNotify');
Route::get('dboy/cancelOrder','DboyController@cancelOrder');
?>