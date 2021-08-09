<?php 


Route::group(['namespace'=>'Amcoders\Plugin\update\http\controllers','middleware'=>['web','auth','admin', 'back_language'],'prefix'=>'admin/', 'as'=>'admin.'],function(){

	Route::resource('update','UpdateController');

});