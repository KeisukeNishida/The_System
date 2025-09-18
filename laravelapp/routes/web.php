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

use App\Http\Middleware\HelloMiddleware;
use App\Http\Middleware\TrimStrings;

Route::get('/', function () {
    return view('welcome');
});


Route::get('hello', 'HelloController@index'); // ->middleware('auth');
Route::post('hello', 'HelloController@post');

Route::get('hello/add', 'HelloController@add');
Route::post('hello/add', 'HelloController@create');

Route::get('hello/edit', 'HelloController@edit');
Route::post('hello/edit', 'HelloController@update');

Route::get('hello/del', 'HelloController@del');
Route::post('hello/del', 'HelloController@remove');

Route::get('hello/show', 'HelloController@show');

Route::get('hello/auth', 'HelloController@getAuth');
Route::post('hello/auth', 'HelloController@postAuth');


Route::get('person', 'PersonController@index');

Route::get('person/find', 'PersonController@find');
Route::post('person/find', 'PersonController@search');

Route::get('person/add', 'PersonController@add');
Route::post('person/add', 'PersonController@create');

Route::get('person/edit', 'PersonController@edit');
Route::post('person/edit', 'PersonController@update');

Route::get('person/del', 'PersonController@delete');
Route::post('person/del', 'PersonController@remove');


Route::get('board', 'BoardController@index');

Route::get('board/add', 'BoardController@add');
Route::post('board/add', 'BoardController@create');


Route::resource('rest', 'RestappController');


Route::get('hello/rest', 'HelloController@rest');

Route::get('hello/session', 'HelloController@ses_get');
Route::post('hello/session', 'HelloController@ses_put');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// カードゲーム
Route::get('/card', 'CardGameController@form')->middleware('auth');
Route::post('/card', 'CardGameController@create')->middleware('auth');
Route::get('/card/{id}', 'CardGameController@show')->middleware('auth');

// ホームページ
Route::get('/', 'HomePageController@show')->name('home.top');

Route::get('/home02', 'HomePageController@home02')->name('home.page02');

Route::get('/game01', 'GameController@game01')->name('Game.game01');

Route::get('/game02', 'GameController@game02')->name('Game.game02');

Route::get('/game03', 'GameController@game03')->name('Game.game03');

Route::get('/game04', 'GameController@game04')->name('Game.game04');

Route::get('/game05', 'GameController@game05')->name('Game.game05');