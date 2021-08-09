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

/*
Hi Developer Khana Api Version is comming soon this commented code is just for example

Thanks Stay With us

*/

Route::group(['namespace'=>'Api\Customer'],function(){
	Route::post('/login','CustomerController@Login');
	Route::post('/register','CustomerController@Register');
	Route::post('/customer/table/booking','TableController@store');
	Route::post('/sign_with_social', 'CustomerController@loginWithSocial');
});


Route::group(['namespace'=>'Api\Customer','middleware'=>'auth:sanctum'],function(){
    Route::post('/save-device-token', 'FireBaseController@saveDeviceToken');

	Route::post('/info','CustomerController@Info');
	Route::post('/logout','CustomerController@Logout');
	Route::post('/create_order','OrderController@store');
	Route::get('/customer/orders','OrderController@index');
	Route::get('/customer/order/{id}','OrderController@details');
	Route::post('/customer/settings','SettingsController@update');
	Route::get('/customer/review','ReviewController@index');
	Route::post('/customer/review','ReviewController@store');
	Route::post('/user/settings','SettingsController@update');

	Route::post('/create_reservation','TableController@storeDB');
	Route::post('/customer/cancel_reservation', 'TableController@cancelReservation');
	Route::get('/customer/reservation/{id}','TableController@details');
    Route::get('/customer/get_list_of_reservation', 'TableController@get_list_of_reservation');


	//Cart
    Route::post('/customer/myCart', 'OrderController@myCart');
    Route::post('/customer/addToCart', 'OrderController@addToCart');
    Route::post('/customer/editCart', 'OrderController@editCart');
    Route::post('/customer/deleteCart', 'OrderController@deleteCart');
    //cancel order
    Route::post('/customer/cancel_order', 'OrderController@cancelOrder');
    //Notifications
    Route::get('/customer/notifications','CustomerController@notificationList');

});

Route::group(['namespace'=>'Api\Rider','middleware'=>'auth:sanctum'],function(){
	Route::get('/todaysorder','RiderController@TodaysPendingOrders');
	Route::get('/allorders','RiderController@AllOrders');
	Route::get('/orderview/{id}','RiderController@OrderView');
	Route::post('/accept','RiderController@accept');
	Route::post('/decline','RiderController@decline');
	Route::post('/complete','RiderController@completeOrder');
	Route::post('/status','RiderController@status');


});



Route::group(['namespace'=>'Api\frontend'],function(){
	Route::get('/home/{id}','FrontendController@home');
	Route::get('/allcity','FrontendController@AllCity');
	// Route::get('/get_resturents/{id}','FrontendController@getResturents');
	Route::get('/allcategory','FrontendController@category');
	Route::get('/cuisine_category','FrontendController@cuisine_category');
	Route::get('/city/{id}','FrontendController@CityByUsers');
	Route::get('/area/{slug}','FrontendController@GetCityId');
	Route::get('/offerable/{id}','FrontendController@offerAble');
	Route::get('/restaurant/{id}','FrontendController@restaurantView');
	Route::get('/productlist/{id}','FrontendController@ResturantProductList');
	Route::get('/delivery_fee','FrontendController@deliveryfee');

	Route::get('/main', 'FrontendController@main');
	Route::get('/restaurant_qr/{id}', 'FrontendController@restaurant_qr');
	Route::get('/list_of_categories', 'FrontendController@list_of_categories');
	Route::get('/list_of_foodies', 'FrontendController@list_of_foodies');
	Route::get('/list_of_restaurants', 'FrontendController@list_of_restaurants');
	Route::get('/list_of_foodies_of_group/{id}', 'FrontendController@list_of_foodies_of_group');
	Route::post('/search', 'FrontendController@search');
	Route::get('/list_of_restaurants_by_category', 'FrontendController@list_of_restaurants_by_category');
	Route::get('/get_list_of_tables', 'FrontendController@get_list_of_tables');
});





