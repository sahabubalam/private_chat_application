<?php

use Illuminate\Support\Facades\Route;
use App\Events\WebsocketDemoEvent;

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

Route::get('/', function () {
    broadcast(new WebsocketDemoEvent('some data'));

    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('userList', 'MessageController@user_list')->name('userList');
Route::get('userMessage/{id}', 'MessageController@user_message')->name('userMessage');
Route::post('sendmessage', 'MessageController@send_message')->name('sendmessage');
Route::get('deletesinglemessage/{id}', 'MessageController@delete_single_message')->name('deletesinglemessage');
Route::get('deleteallmessage/{id}', 'MessageController@delete_all_message')->name('deleteallmessage');
