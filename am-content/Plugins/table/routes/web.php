<?php


Route::group(['namespace'=>'Amcoders\Plugin\table\http\controllers','middleware'=>['web','auth','store','verified','approval', 'back_language'],'as'=>'store.','prefix'=>'store'],function(){

	Route::resource('table','TableController');
	Route::post('tables/destroy','TableController@destroy')->name('tables.destroy');

});
