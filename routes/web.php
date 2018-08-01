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

Route::get('/', 'TodosController@index');

Route::get('/todo', 'TodosController@index');
Route::get('/todo/all', 'TodosController@all');
Route::get('/todo/create', 'TodosController@create');
Route::post('/todo/store', 'TodosController@store');
Route::get('/todo/{todo}/show','TodosController@show');
Route::get('/todo/{todo}/gshow','TodosController@gridshow');
Route::get('/todo/{todo}/edit','TodosController@edit');
Route::post('/todo/{todo}/update','TodosController@update');
Route::get('/todo/{todo}/deleteTask','TodosController@deleteTask');
Route::get('/todo/trash/{todo}','TodosController@trashTask');
Route::get('/todo/pin/{todo}','TodosController@pinTask');
Route::get('/todo/trash','TodosController@trash');
Route::get('/todo/restore/{todo}','TodosController@restore');
Route::get('/todo/archive/{todo}','TodosController@archiveTask');
Route::get('/todo/archive','TodosController@archived');
Route::get('/todo/unarchive/{todo}','TodosController@unarchive');
Route::get('/todo/search','TodosController@search');
Route::post('/todo/find','TodosController@find');
Route::get('/todo/clearall','TodosController@clearall');
Route::get('todo/getcompleted','TodosController@getCompleted');
Route::get('todo/getProcessing','TodosController@getProcessing');
Route::get('todo/getPending','TodosController@getPending');
Route::get('todo/help','TodosController@help');
Route::get('todo/gridview','TodosController@gridview');
Route::get('/todo/myorder','TodosController@myorder');
Route::get('/todo/changeorder',function(){
    $todos = App\Todo::orderBy('order','ASC')
                        ->get();
    return view('todo.changeorder',compact('todos'));
});
Route::post('/todo/changeorder','TodosController@order');
Route::get('todo/sort/by/title','TodosController@sortByTitle');
Route::get('todo/sort/by/date','TodosController@sortByDate');
Route::resource('todo','TodosController');
Auth::routes();

Route::get('/home', 'TodosController@index')->name('home');
