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


//Modul Mobile
Route::group(['prefix' => 'api/v1'], function () {
    //Modul User
    Route::get('user/{id}/viewProfile', 'MobileController@viewProfile');

    Route::post('doLogin', 'MobileController@doLogin');
    Route::post('doRegist', 'MobileController@doRegist');
    
    Route::put('user/{id}/editProfile', 'MobileController@editProfile');

    //Modul Pelanggan
    Route::get('museum', 'MobileController@getMuseumData');
    Route::get('museum/{id}/getDetailMuseum', 'MobileController@getDetailMuseum');
    Route::get('user/{id}/historyOrder', 'MobileController@historyOrder');

    Route::post('user/{id}/topupSaldo', 'MobileController@topupSaldo');
    Route::post('user/{id}/orderPemandu', 'MobileController@orderPemandu');

    Route::put('order/{id}/giveMuseumRating', 'MobileController@giveMuseumRating');


    //Modul Pemandu
});

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::match(['GET', 'POST'], '/register', function(){
    return redirect('/login');
});

Route::get('/home', 'HomeController@index')->name('home');

//Modul Museum
Route::get('/museums', 'MuseumController@index')->name('museums');
Route::get('/museums/delete/{id}', 'MuseumController@delete')->name('museums.delete');
Route::get('/museums/getData/{id}', 'MuseumController@getData')->name('museums.getData');

Route::post('/museums/add', 'MuseumController@add')->name('museums.add');
Route::post('/museums/edit', 'MuseumController@edit')->name('museums.edit');

//Modul Order

//Modul User
Route::get('/users', 'UserController@index')->name('users');
Route::get('/users/delete/{id}', 'UserController@delete')->name('users.delete');
Route::get('/users/getData/{id}', 'UserController@getData')->name('users.getData');

Route::post('/users/add', 'UserController@add')->name('users.add');
Route::post('/users/edit', 'UserController@edit')->name('users.edit');

//Modul Role
Route::get('/roles', 'UserRoleController@index')->name('roles');
Route::get('/roles/delete/{id}', 'UserRoleController@delete')->name('roles.delete');
Route::get('/roles/getData/{id}', 'UserRoleController@getData')->name('roles.getData');

Route::post('/roles/add', 'UserRoleController@add')->name('roles.add');
Route::post('/roles/edit', 'UserRoleController@edit')->name('roles.edit');