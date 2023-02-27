<?php
include('admin.php');
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
 



Route::fallback(function () {
    return view('errors.404'); // template should exists
});


Route::group(['namespace' => 'User','prefix' => env('user')], function(){

    Route::get('/','AdminController@index');
    Route::get('login','AdminController@index');
    Route::post('login','AdminController@login');

    Route::group(['middleware' => 'auth'], function(){

        /*
        |-----------------------------------------
        |Dashboard and Account Setting & Logout
        |-----------------------------------------
        */
        Route::get('home','AdminController@home');
        Route::get('setting','AdminController@setting');
        Route::post('setting','AdminController@update');
        Route::get('logout','AdminController@logout');
        Route::get('close','AdminController@close');
        Route::get('stripeConnect/{id}','AdminController@stripeConnect');


        /*
        |------------------------------
        |Asignacion de mesas
        |------------------------------
        */ 

        Route::resource('tables','TablesController');
        Route::get('tables/delete/{id}','TablesController@delete');
        Route::get('tables/status/{id}','TablesController@status');


        /*
        |--------------------------------------
        |Menu Category
        |--------------------------------------
        */
        Route::get('search','CategoryController@search')->name('search');
        Route::resource('category','CategoryController');
        Route::get('category/delete/{id}','CategoryController@delete');
        Route::get('category/status/{id}','CategoryController@status');

        /*
        |--------------------------------------
        |loyalty Program
        |--------------------------------------
        */
        Route::resource('loyalty','LoyaltyController');
        Route::get('loyalty/delete/{id}','LoyaltyController@delete');
        Route::get('loyalty/status/{id}','LoyaltyController@status');
        
        /*
        |--------------------------------------
        |Item Type
        |--------------------------------------
        */
        Route::resource('type','TypeController');
        Route::get('type/delete/{id}','TypeController@delete');

        /*
        |--------------------------------------
        |Manage Addon
        |--------------------------------------
        */
        Route::get('search_addon','AddonController@search_addon')->name('search_addon');
        Route::resource('addon','AddonController');
        Route::get('addon/delete/{id}','AddonController@delete');

        /*
        |--------------------------------------
        |Menu Items
        |--------------------------------------
        */
        Route::get('search_item','ItemController@search_item')->name('search_item');
        Route::resource('item','ItemController');
        Route::get('item/delete/{id}','ItemController@delete');
        Route::get('item/status/{id}','ItemController@status');
        Route::post('itemAddon','ItemController@addon');
        Route::get('export','ItemController@export');
        Route::get('import','ItemController@import');
        Route::post('import','ItemController@_import');

        /*
        |------------------------------
        |Manage Offer
        |------------------------------
        */
        Route::resource('offer','OfferController');
        Route::get('offer/delete/{id}','OfferController@delete');
        Route::get('offer/status/{id}','OfferController@status');

        /*
        |------------------------------
        |Delivery Staff
        |------------------------------
        */
        Route::resource('delivery','DeliveryController');
        Route::get('delivery/delete/{id}','DeliveryController@delete');
        Route::get('delivery/status/{id}','DeliveryController@status');

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
        |Logs
        |-------------------------------
        */
        Route::get('logs','LogsController@index');
        Route::post('exportLogs','LogsController@exportLogs');

        /*
        |-------------------------------
        |Manage Orders
        |-------------------------------
        */
        Route::get('order','OrderController@index');
        Route::get('orderStatus','OrderController@orderStatus');
        Route::get('order/print/{id}','OrderController@printBill');
        Route::post('order/dispatched','OrderController@dispatched');
        Route::get('order/edit/{id}','OrderController@edit');
        Route::post('order/edit/{id}','OrderController@_edit');
        Route::get('orderItem','OrderController@orderItem');
        Route::get('getUnit/{id}','OrderController@getUnit');
        Route::get('order/add','OrderController@add');
        Route::post('order/add','OrderController@_add');
        Route::get('getUser/{id}','OrderController@getUser');


        /*
        |-------------------------------
        |POS & Kitchen
        |-------------------------------
        */
        Route::get('kitchen_orders','OrderController@kitchen_orders');

    });
});
