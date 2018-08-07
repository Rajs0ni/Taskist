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
Route::get('/todo/view/{todo}', 'TodosController@index1');
Route::get('/todo', 'TodosController@index');
Route::get('/todo/all', 'TodosController@all');
Route::get('/todo/create', 'TodosController@create');
Route::get('/create/{todo}','TodosController@create1');
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
Route::get('/addcollaborator','TodosController@addcollab');
Route::get('/collab','TodosController@collab');
Route::post('/todo/changeorder','TodosController@order');
Route::get('todo/sort/by/title','TodosController@sortByTitle');
Route::get('/setsession','TodosController@setsession');
Route::get('todo/sort/by/date','TodosController@sortByDate');
Route::get('/acceptcollab','TodosController@acceptcollab');
Route::get('/rejectcollab','TodosController@rejectcollab');
Route::get('/todo/color','TodosController@color');
Route::resource('todo','TodosController');
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index');
Route::get('/home', 'TodosController@index')->name('home');
Route::post('/addreminder','TodosController@addreminder');
Route::get('/getreminder','TodosController@getreminder');
Route::get('/removeremindernoti','TodosController@removeremindernoti');
Route::get('/getremtime','TodosController@getremtime');
Route::get('/removereminder','TodosController@removereminder');

