<?php

Auth::routes();

//Route::get('lang/{lang}', ['as'=>'lang.switch', 'uses'=>'LanguageController@switchLang']);
/////////////////////////
Route::get('/', function () {
    return view('index');
});
Route::post('/web_login', 'WebLoginController@web_login')->name('web.login.submit');
Route::post('/web_register', 'WebLoginController@web_register')->name('web.register.submit');
Route::get('web_logout/', 'WebLoginController@web_logout')->name('web.logout');
// Route::get('/', 'HomeController@index')->name('index');
Route::get('/licence', 'HomeController@licence')->name('licence');
Route::post('/contact', 'HomeController@contact')->name('contact');
Route::get('/child_category/{id}', 'AdController@child_category')->name('child_category');
Route::get('/category/{id}', 'AdController@ads')->name('category');
Route::get('/showImg/{id}', 'HomeController@showImg')->name('showImg');
Route::get('/deleteImgAd/{id}/{name}', 'HomeController@deleteImgAd')->name('deleteImgAd');
Route::get('logout', 'Auth\LoginController@logout');
//Route::get('/profile', 'HomeController@profile')->name('profile')->middleware('guest:web');
Route::post('/search', 'AdController@search')->name('search');
Route::get('/ad/{id}', 'AdController@show')->name('web-ad.show');

Route::group(['prefix' => '/','middleware'=>'auth'], function()
{
    Route::get('/profile', 'HomeController@profile')->name('profile');
    Route::get('/edit-profile', 'HomeController@edit_profile')->name('edit-profile');
    Route::post('/edit-profile', 'HomeController@update_profile')->name('update-profile');
    Route::get('/seller_profile/{id}', 'HomeController@seller_profile')->name('seller-profile');
    Route::get('/notification', 'HomeController@notification')->name('notification');

    Route::get('/chat', 'HomeController@chat')->name('chat');
    Route::get('/chat-single/{id}', 'HomeController@single_chat')->name('chat.single');
    Route::get('/chat-on-ad/{id}', 'HomeController@new_chat')->name('chat.new');
    Route::post('/chat', 'HomeController@chat_store')->name('chat.store');

    Route::get('/ad', 'AdController@create')->name('web-ad.create');
    Route::post('/ad', 'AdController@store')->name('web-ad.store');
    Route::post('/comment', 'AdController@comment')->name('comment.store');
    Route::get('/favourite/{id}', 'AdController@favourite')->name('favourite');
    Route::get('/search', 'AdController@search')->name('search.get');
    Route::post('/rate', 'HomeController@rate')->name('rate');

    Route::get('/redirect_note/{type}/{id?}', 'HomeController@redirect_note')->name('redirect_note');

});
//admin password reset routes
Route::group(['prefix'=>'/admin','namespace'=>'Auth'],function() {
    Route::get('/password/reset','AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/email','AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::post('/password/reset','AdminResetPasswordController@reset');
    Route::get('/password/reset/{token}','AdminResetPasswordController@showResetForm')->name('admin.password.reset');
});
Route::group(['prefix'=>'/admin','namespace'=>'Admin'],function() {
    //auth
    Route::get('/login', 'AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'AdminLoginController@login')->name('admin.login.submit');
    Route::get('logout/', 'AdminLoginController@logout')->name('admin.logout');
    //dashboard
    Route::get('/', 'AdminController@dashboard')->name('admin.dashboard');

    Route::get('/profile', 'AdminController@edit_profile')->name('edit_profile');

    Route::get('setting', 'SettingController@get_setting')->name('setting.get_setting');
    Route::patch('setting/{id}', 'SettingController@update_setting')->name('setting.update_setting');

    Route::resource('admin', 'AdminController');
    Route::post('admin/activate/{id}', 'AdminController@activate')->name('active_admin');
    Route::resource('roles', 'RolesController');

    Route::resource('user', 'UserController');
    Route::get('active_user', 'UserController@active_users')->name('user.active_users');
    Route::get('not_active_user', 'UserController@not_active_users')->name('user.not_active_users');
    Route::post('user/activate/{id}', 'UserController@activate')->name('active_user');

    Route::resource('category', 'CategoryController');
    Route::post('category/activate/{id}', 'CategoryController@activate')->name('active_category');

    Route::resource('slider', 'SliderController');

    Route::resource('contact', 'ContactController');
    Route::get('collective_notice', 'NotificationController@collective_notice')->name('notification.collective_notice');
    Route::resource('notification', 'NotificationController');
    Route::get('district', 'CityController@districts')->name('district.index');
    Route::resource('city', 'CityController');

    Route::resource('bank', 'BankController');
    Route::post('bank/activate/{id}', 'BankController@activate')->name('active_bank');
    Route::resource('ad', 'AdController');

});
