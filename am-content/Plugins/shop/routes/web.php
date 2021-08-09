<?php

//,'verified'
Route::group(['as' =>'store.','prefix'=>'store','namespace'=>'Amcoders\Plugin\shop\http\controllers','middleware'=>['web','auth','store','approval', 'back_language']],function(){

	Route::get('dashboard','DashboardController@dashboard')->name('dashboard');
 
	Route::get('/my-media','ShopController@media')->name('media');
	Route::post('/media/destroy','ShopController@MediaDestroy')->name('medias.destroy');

	Route::resource('product','ShopController');
	Route::resource('addon-product','AddonProductController');
	Route::post('addon-products/destroy','AddonProductController@destroy')->name('addon-products.destroy');
	Route::post('products/destroy','ShopController@destroy')->name('products.destroy');
	Route::get('my-day','ShopController@day')->name('day.show');
	Route::post('shop-day','ShopController@updateday')->name('day.update');

	Route::get('/settings/my-information','ShopController@information')->name('my.information');
	Route::get('/settings/payouts','StatementController@payouts')->name('payouts');
	Route::get('/settings/payouts/setup','StatementController@setup')->name('payout.edit');
	Route::post('/payouts/setup/paypal','StatementController@paypalSetup')->name('payout.paypal');
	Route::post('/payouts/setup/bank','StatementController@bankSetup')->name('payout.bank');

	Route::post('withdraw','StatementController@withdraw')->name('withdraw');

	Route::post('information-update','ShopController@informationupdate')->name('my.information.update');
	Route::resource('coupon','CouponController');
	Route::post('coupons/destroy','CouponController@destroy')->name('coupons.destroy');
	Route::post('subscribe','ShopController@subscribe')->name('subscribe');

	Route::resource('order','OrderController');
	Route::get('order/invoice/{id}','OrderController@invoice')->name('invoice');
	Route::get('order/invoice/print/{id}','OrderController@invoice_print')->name('invoice_print');
	Route::get('/settings/earnings','StatementController@Earning')->name('earnings');

	Route::resource('menu','MenuController');
	Route::post('mendestroy','MenuController@destroy')->name('menu.des');

	Route::resource('reservation','ReservationController');
	Route::get('reservation/invoice/{id}','ReservationController@invoice')->name('reserve_invoice');
	Route::get('reservation/invoice/print/{id}','ReservationController@invoice_print')->name('reserve_invoice_print');

    Route::get('/reserve_image','ReserveImageController@index')->name('reserve_image.index');
	Route::get('/reserve_image_create','ReserveImageController@create')->name('reserve_image.create');
	Route::post('/reserve_image_store','ReserveImageController@store')->name('reserve_image.store');
	Route::get('/reserve_image_edit/{id}','ReserveImageController@edit')->name('reserve_image.edit');
	Route::put('/reserve_image_update/{id}','ReserveImageController@update')->name('reserve_image.update');
	Route::post('/reserve_image_destroy','ReserveImageController@destroy')->name('reserve_image.destroy');
});



Route::group(['namespace'=>'Amcoders\Plugin\shop\http\controllers','middleware'=>['web','auth','admin', 'back_language'],'prefix'=>'admin/', 'as'=>'admin.'],function(){

	Route::get('/resturents','UserController@vendors')->name('vendor.index');
	Route::get('/resturents/requests','UserController@requests')->name('vendor.requests');
	Route::get('/add_resturent','ShopController@informationcreate')->name('vendor.create');
	Route::post('/store_resturent','ShopController@informationstore')->name('vendor.store');
	Route::post('/ridersupdate/{id}','UserController@riderUpdate')->name('rider.update');

	Route::get('/rider','UserController@riders')->name('rider.index');
	Route::get('/rider/requests','UserController@riderrequests')->name('rider.requests');

	Route::get('/user/{id}','UserController@show')->name('vendor.show');
	Route::post('/userdestroy','UserController@UsersDelete')->name('vendor.destroys');
	Route::post('/resturentsupdate/{id}','UserController@sellerUpdate')->name('vendor.update');
	Route::get('/signalremove/{id}','UserController@signalremove')->name('signal.remove');

	Route::get('/payout/request','PayoutController@request')->name('payout.request');
	Route::get('/payout/history','PayoutController@history')->name('payout.history');
	Route::get('/payout/accounts','PayoutController@accounts')->name('payout.accounts');
	Route::get('/payout/{id}','PayoutController@show')->name('payout.show');
	Route::post('/payoutupdate/{id}','PayoutController@payoutUpdate')->name('payout.update');
	Route::get('/payout/destroy/{id}','PayoutController@destroy')->name('payout.destroy');

	Route::get('/customers','UserController@customers')->name('customer.index');

	Route::get('/sliders','SliderController@index')->name('slider.index');
	Route::get('/slider_create','SliderController@create')->name('slider.create');
	Route::post('/slider_store','SliderController@store')->name('slider.store');
	Route::get('/slider_edit/{id}','SliderController@edit')->name('slider.edit');
	Route::put('/slider_update/{id}','SliderController@update')->name('slider.update');
	Route::post('/slider_destroy','SliderController@destroy')->name('slider.destroy');

	Route::post('/get_products', 'SliderController@get_products')->name('get_products');

});


