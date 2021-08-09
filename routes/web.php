<?php

use App\Http\Controllers\FireBaseController;
use App\Http\Controllers\HomeController;


Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::patch('/fcm-token', [HomeController::class, 'updateToken'])->name('fcmToken');
Route::post('/send-notification2',[HomeController::class,'notification'])->name('send.notification2');

Route::get('/send-web-push-notificaiton', [FireBaseController::class, 'index'])->name('send-push.notificaiton');

Route::post('/save-device-token', [FireBaseController::class, 'saveDeviceToken'])->name('save-device.token');
Route::post('/send-notification', [FireBaseController::class, 'sendNotification'])->name('send.notification');


Route::get('/fcm', 'HomeController@fcm')->name('fcm');

Route::post('/update-user-FCM', 'FireBaseController@updateUserFCM')->name('updateUserFCM');

Route::get('/back_language/{locale}', function ($locale){
    App::setLocale($locale);
    session()->put('back_locale', $locale);
    return redirect()->back();
})->name('back_language');

Route::get('clear_cache', function() {
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('config:cache');
    // \Artisan::call('route:cache');
    return "Cache is cleared and config cleared and config cached";
});

Route::get('clear_config', function() {
    \Artisan::call('config:clear');
    return "Config is cleared";
});

Route::get('key_generate', function() {
    \Artisan::call('key:generate');
    return "Key is Generated";
});

Route::group(['namespace'=>'Admin','middleware'=>'web'],function(){
	// Route::get('language', 'LanguageController@index')->name('language.index');
	// Route::get('language', 'LanguageController@create')->name('language.create');
	// Route::post('language', 'LanguageController@store')->name('language.store');
	// Route::get('language/{id}', 'LanguageController@show')->name('language.show');
	// Route::get('language/{id}', 'LanguageController@edit')->name('language.edit');
	// Route::post('language/{lang_code}/{theme_name}', 'LanguageController@update')->name('language.update');
	Route::get('language', 'LanguageController@set')->name('language.set');
	// Route::post('language/{lang_code}/{theme_name}', 'LanguageController@delete')->name('language.delete');

});

Route::post('media_store', 'Admin\MediaController@store')->name('admin.media.store');
Route::get('admin/media_json', 'Admin\MediaController@json')->name('admin.medias.json');
Route::get('admin/media/info/{id}', 'Admin\MediaController@show')->name('admin.medias.show');
Route::get('admin/media_destroy', 'Admin\MediaController@destroy')->name('admin.medias.destroy');

Route::group(['namespace'=>'Admin','middleware' => ['admin', 'web', 'store', 'back_language'], 'prefix' => 'admin'],function(){
	Route::get('dashboard', 'DashboardController@Dashboard')->name('admin.dashboard');
	Route::get('announcement', 'DashboardController@announcement')->name('admin.announcement');
	Route::get('mysettings', 'DashboardController@announcement')->name('admin.admin.mysettings');
});

Route::group(['namespace' => 'Admin','middleware' => ['admin', 'web', 'back_language'], 'prefix' => 'admin'],function(){
	Route::get('dashboard', 'DashboardController@Dashboard')->name('admin.dashboard');
	Route::get('announcement', 'DashboardController@announcement')->name('admin.announcement');

	Route::get('mysettings', 'UserController@index')->name('admin.admin.mysettings');

	Route::get('media', 'MediaController@index')->name('admin.media.index');

	Route::get('page', 'PageController@index')->name('admin.page.index');
	Route::get('page_create', 'PageController@create')->name('admin.page.create');
	Route::get('page_edit/{id}', 'PageController@edit')->name('admin.page.edit');
	Route::put('page_update/{id}', 'PageController@update')->name('admin.page.update');
	Route::get('page_destroy', 'PageController@destroy')->name('admin.page.destroy');

	Route::get('theme', 'ThemeController@index')->name('admin.theme.index');
	Route::post('theme_upload', 'ThemeController@upload')->name('admin.theme.upload');

	Route::get('menu', 'MenuController@index')->name('admin.menu.index');
	Route::get('menu_show', 'MenuController@show')->name('admin.menu.show');
	Route::get('menu_edit/{id}', 'MenuController@edit')->name('admin.menu.edit');
	Route::get('menu_update/{id}', 'MenuController@update')->name('admin.menu.update');
	Route::post('menu_store', 'MenuController@store')->name('admin.menu.store');
	Route::post('menu_destroy', 'MenuController@destroy')->name('admin.menues.destroy');

	Route::get('groups', 'GroupController@index')->name('admin.group.index');
	Route::get('group_show', 'GroupController@show')->name('admin.group.show');
	Route::get('group_edit/{id}', 'GroupController@edit')->name('admin.group.edit');
	Route::put('group_update/{id}', 'GroupController@update')->name('admin.group.update');
	Route::post('group_store', 'GroupController@store')->name('admin.group.store');
	Route::post('group_destroy', 'GroupController@destroy')->name('admin.group.destroy');

	Route::get('group_product', 'GroupProductController@index')->name('admin.group.product.index');
	Route::get('group_product_show', 'GroupProductController@show')->name('admin.group.product.show');
	Route::get('group_product_edit/{id}', 'GroupProductController@edit')->name('admin.group.product.edit');
	Route::put('group_product_update/{id}', 'GroupProductController@update')->name('admin.group.product.update');
	Route::post('group_product_store', 'GroupProductController@store')->name('admin.group.product.store');
	Route::post('group_product_destroy', 'GroupProductController@destroy')->name('admin.group.product.destroy');

	Route::get('seo', 'SeoController@index')->name('admin.seo.index');

	Route::get('filesystem', 'FilesystemController@index')->name('admin.filesystem.index');

	Route::get('env', 'EnvController@index')->name('admin.env.index');

	Route::get('language', 'LanguageController@index')->name('admin.language.index');
	Route::get('language_create', 'LanguageController@create')->name('admin.language.create');
	Route::post('language_store', 'LanguageController@store')->name('admin.lang.store');
	Route::post('language_set', 'LanguageController@set')->name('admin.lang.set');
	Route::get('language_edit/{id}/{theme_name}', 'LanguageController@edit')->name('admin.lang.edit');
	Route::post('language_update/{lang_code}/{theme_name}', 'LanguageController@update')->name('admin.lang.update');
	Route::post('language_delete/{lang_code}/{theme_name}', 'LanguageController@delete')->name('admin.lang.delete');

	Route::get('role', 'RoleController@index')->name('admin.role.index');
	Route::get('role_create', 'RoleController@create')->name('admin.role.create');
	Route::post('role_store', 'RoleController@store')->name('admin.role.store');
	Route::get('role_edit/{id}', 'RoleController@edit')->name('admin.role.edit');
	Route::put('role_update/{id}', 'RoleController@update')->name('admin.role.update');
	Route::post('role_destroy', 'RoleController@destroy')->name('admin.roles.destroy');

	Route::get('users', 'AdminController@index')->name('admin.users.index');
	Route::get('user_create', 'AdminController@create')->name('admin.users.create');
	Route::post('user_store', 'AdminController@store')->name('admin.users.store');
	Route::get('user_edit/{id}', 'AdminController@edit')->name('admin.users.edit');
	Route::put('user_update/{id}', 'AdminController@update')->name('admin.users.update');
	Route::post('user_destroy', 'AdminController@destroy')->name('admin.users.destroy');

	Route::post('genupdate', 'UserController@genUpdate')->name('admin.users.genupdate');
	Route::post('passup', 'UserController@updatePassword')->name('admin.users.passup');

	Route::get('customizer', 'CustomizerController@index')->name('admin.customizer.index');
	Route::get('customizer_section_option', 'CustomizerController@section_option')->name('admin.customizer.section_option');
	Route::get('customizer_page_change', 'CustomizerController@page_change')->name('admin.customizer.page_change');
	Route::get('customizer_save', 'CustomizerController@save')->name('admin.customizer.save');

	Route::get('seos', 'SeoController@index')->name('admin.seo.index');
	Route::get('seo_edit/{id}', 'SeoController@edit')->name('admin.seo.edit');
	Route::get('seo_update/{id}', 'SeoController@update')->name('admin.seo.update');
	Route::post('seo_store', 'SeoController@store')->name('admin.seo.store');

	Route::get('filesystem', 'FilesystemController@index')->name('admin.filesystem.index');
	Route::post('filesystem_store', 'FilesystemController@store')->name('admin.filesystem.store');

	Route::get('env', 'EnvController@index')->name('admin.env.index');
	Route::post('env_store', 'EnvController@store')->name('admin.env.store');
});

Route::group(['namespace' => 'Store','middleware' => 'store', 'prefix' => 'store'],function(){
	Route::get('dashboard', 'DashboardController@dashboard')->name('store.dashboard');
	Route::post('status', 'DashboardController@status')->name('store.status');
	Route::post('is_reserve_open', 'DashboardController@is_reserve_open')->name('store.is_reserve_open');
	// Route::get('gallery', 'DashboardController@gallery')->name('store.gallery');

	Route::get('sizes', 'SizeController@index')->name('store.size.index');
	Route::get('size_show', 'SizeController@show')->name('store.size.show');
	Route::get('size_edit/{id}', 'SizeController@edit')->name('store.size.edit');
	Route::put('size_update/{id}', 'SizeController@update')->name('store.size.update');
	Route::post('size_store', 'SizeController@store')->name('store.size.store');
	Route::post('size_destroy', 'SizeController@destroy')->name('store.size.destroy');

	Route::get('tables', 'TableController@index')->name('store.tables.index');
	Route::get('table_show', 'TableController@show')->name('store.table.show');
	Route::get('table_edit/{id}', 'TableController@edit')->name('store.table.editable');
	Route::put('table_update/{id}', 'TableController@update')->name('store.table.upgrade');
	Route::post('table_store', 'TableController@store')->name('store.table.insert');
	Route::post('table_destroy', 'TableController@destroy')->name('store.table.delete');

	Route::get('table_image/{id}', 'TableImageController@index')->name('store.table_image.index');
// 	Route::get('table_image_show', 'TableImageController@show')->name('store.table_image.show');
	Route::get('table_image_edit/{id}', 'TableImageController@edit')->name('store.table_image.edit');
	Route::put('table_image_update/{id}', 'TableImageController@update')->name('store.table_image.update');
	Route::post('table_image_store', 'TableImageController@store')->name('store.table_image.store');
	Route::post('table_image_destroy', 'TableImageController@destroy')->name('store.table_image.destroy');

	Route::get('table_image/{id}','TableImageController@index')->name('store.table_image.index');
	Route::get('/table_image_create/{id}','TableImageController@create')->name('store.table_image.create');
	Route::post('/table_image_store','TableImageController@store')->name('store.table_image.store');
	Route::get('/table_image_edit/{id}','TableImageController@edit')->name('store.table_image.edit');
	Route::put('/table_image_update/{id}','TableImageController@update')->name('store.table_image.update');
	Route::post('/table_image_destroy','TableImageController@destroy')->name('store.table_image.destroy');

	Route::get('table_day/{id}','TableDayController@index')->name('store.table_day.index');
	Route::post('table_update_day/{id}','TableDayController@update')->name('store.table_day.update');
});

Auth::routes();
