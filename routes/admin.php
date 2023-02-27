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
  
Route::group(['namespace' => 'Admin','prefix' => env('admin')], function(){
// Route::namespace('Admin')->prefix(env('admin'))->group(function(){

    Route::get('/','AdminController@index');
    Route::get('login','AdminController@index');
    Route::post('login','AdminController@login');

    Route::group(['middleware' => 'admin'], function(){

        /*
        |-----------------------------------------
        |Dashboard and Account Setting & Logout
        |-----------------------------------------
        */
        Route::get('home','AdminController@home');
        Route::get('setting','AdminController@setting');
        Route::post('setting','AdminController@update');
        Route::get('logout','AdminController@logout');

        /*
        |------------------------------
        |Manage Users
        |------------------------------
        */
        Route::resource('user','UserController');
        Route::get('user/delete/{id}','UserController@delete');
        Route::get('user/status/{id}','UserController@status');
        Route::get('user/{id}/rate','UserController@rate');
        Route::get('user/{id}/viewqr','UserController@viewqr');
        Route::get('imageRemove/{id}','UserController@imageRemove');
        Route::get('loginWithID/{id}','UserController@loginWithID');
        Route::get('user/pay/{id}','UserController@pay');
        Route::get('user/payAll/{id}','UserController@payAll');
        Route::patch('user_pay/{id}','UserController@user_pay');
        Route::get('user/viewmap/{id}','UserController@viewmap');
        Route::patch('saveMap/{id}','UserController@saveMap');
        Route::get('user/stripeConnect/{id}','UserController@stripeConnect');

        /*
        |-------------------------------
        |Reporting Users
        |-------------------------------
        */
        Route::get('report_users','ReportController@index_users');
        Route::post('report_users','ReportController@report_users');
        Route::get('data_Users_id/{id}','ReportController@data_Users_id');
        Route::post('exportData_users','ReportController@exportData_users');

        /*
        |------------------------------
        |Manage Admin Account
        |------------------------------
        */
        Route::resource('adminUser','AdminUserController');
        Route::get('adminUser/delete/{id}','AdminUserController@delete');

        /*
        |------------------------------
        |Manage City
        |------------------------------
        */
        Route::resource('city','CityController');
        Route::get('city/delete/{id}','CityController@delete');
        Route::get('city/status/{id}','CityController@status');

        /*
        |------------------------------
        |Manage Language
        |------------------------------
        */
        Route::resource('language','LanguageController');
        Route::get('language/delete/{id}','LanguageController@delete');
        Route::get('language/status/{id}','LanguageController@status');

        /*
        |------------------------------
        |Manage App Pages
        |------------------------------
        */
        Route::resource('page','PageController');
        Route::resource('text','TextController');

        /*
        |------------------------------
        |Manage Welcome Slider
        |------------------------------
        */
        Route::resource('slider','SliderController');
        Route::get('slider/delete/{id}','SliderController@delete');

        /*
        |------------------------------
        |Manage Banner
        |------------------------------
        */
        Route::resource('banner','BannerController');
        Route::get('banner/delete/{id}','BannerController@delete');
        Route::get('banner/status/{id}','BannerController@status');

        /*
        |--------------------------------------
        |Menu Category
        |--------------------------------------
        */
        Route::resource('category','CategoryController');
        Route::get('category/delete/{id}','CategoryController@delete');
        Route::get('category/status/{id}','CategoryController@status');

        /*
        |------------------------------
        |Manage Offer
        |------------------------------
        */
        Route::resource('offer','OfferController');
        Route::get('offer/delete/{id}','OfferController@delete');
        Route::get('offer/status/{id}','OfferController@status');
        Route::post('offer/assign','OfferController@assign');

        /*
        |------------------------------
        |Delivery Staff
        |------------------------------
        */
        Route::resource('delivery','DeliveryController');
        Route::get('delivery/pay/{id}','DeliveryController@pay');
        Route::get('delivery/payAll/{id}','DeliveryController@payAll');
        Route::get('delivery/{id}/rate','DeliveryController@rate');
        Route::get('delivery/delete/{id}','DeliveryController@delete');
        Route::get('delivery/status/{id}','DeliveryController@status');
        Route::get('delivery/status_admin/{id}','DeliveryController@status_admin');
        Route::patch('delivery_pay/{id}','DeliveryController@delivery_pay');
        /*
        |-------------------------------
        |Manage Orders
        |-------------------------------
        */
        Route::get('order','OrderController@index');
        Route::get('orderStatus','OrderController@orderStatus');
        Route::post('order/dispatched','OrderController@dispatched');
        Route::get('order/print/{id}','OrderController@printBill');
        Route::get('order/edit/{id}','OrderController@edit');
        Route::post('order/edit/{id}','OrderController@_edit');
        Route::get('order/delete/{id}','OrderController@delete');

        Route::get('orderItem','OrderController@orderItem');
        Route::get('getUnit/{id}','OrderController@getUnit');
        Route::get('order/add','OrderController@add');
        Route::post('order/add','OrderController@_add');
        Route::get('getUser/{id}','OrderController@getUser');
        Route::get('getCity/{id}/{type}','OrderController@getCity');


        /*
        |-------------------------------
        |Manage Mandaditos
        |-------------------------------
        */
        Route::resource('commaned','CommanedController');
        Route::get('commaned','CommanedController@index');
        Route::get('commanedStatus','CommanedController@commanedStatus');
        Route::get('commaned/delete/{id}','CommanedController@delete');
        Route::get('commaned/status/{id}','CommanedController@status');


        /*
        |-------------------------------
        |Send Push Notification
        |-------------------------------
        */
        Route::get('push','PushController@index');
        Route::post('push','PushController@send');

        /*
        |-------------------------------
        |Reporting
        |-------------------------------
        */
        Route::get('report','ReportController@index');
        Route::post('report','ReportController@report');
        Route::get('payment','ReportController@payment');
        Route::get('paymentReport','ReportController@paymentReport');
        Route::post('exportData','ReportController@exportData');

        /*
        |-------------------------------
        |Reporting Staff
        |-------------------------------
        */
        Route::get('report_staff','ReportController@index_staff');
        Route::post('report_staff','ReportController@report_staff');
        Route::get('data_Staff_id/{id}','ReportController@data_Staff_id');
        Route::post('exportData_staff','ReportController@exportData_staff');
        /*
        |-------------------------------
        |Logs
        |-------------------------------
        */
        Route::get('logs','LogsController@index');
        Route::post('exportLogs','LogsController@exportLogs');

        /*
        |-------------------------------
        |App Users
        |-------------------------------
        */
        Route::get('appUser','AdminController@appUser');
        Route::get('appUser/status/{id}', 'AdminController@status');
        Route::get('appUser/trash/{id}', 'AdminController@trash');
    });


});
?>