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
Route::group(['prefix' => 'editor', 'as' => 'editor.', 'namespace' => 'Pondol\Editor', 'middleware' => ['web']], function () {
  Route::get('smart-editor', array('uses'=>'Http\Controllers\SmartEditorController@create'))->name('smarteditor');
  Route::post('smart-editor', array('uses'=>'Http\Controllers\SmartEditorController@store'));
  Route::get('smart-editor/photo-upload', array('uses'=>'Http\Controllers\SmartEditorController@upload'))->name('smarteditor.photo');
  Route::post('smart-editor/photo-upload', array('uses'=>'Http\Controllers\SmartEditorController@uploadStore'));
  Route::post('smart-editor/photo-upload/html5', array('uses'=>'Http\Controllers\SmartEditorController@uploadStoreHtml5'));

  Route::get('froala', array('uses'=>'Http\Controllers\FroalaEditorController@create'))->name('froala');
  Route::post('froala', array('uses'=>'Http\Controllers\FroalaEditorController@store'));
  Route::get('froala/photo-upload', array('uses'=>'Http\Controllers\FroalaEditorControlle@upload'))->name('froala.photo');
  Route::post('froala/photo-upload', array('uses'=>'Http\Controllers\FroalaEditorControlle@uploadStore'));
  Route::post('froala/photo-upload/html5', array('uses'=>'Http\Controllers\FroalaEditorControlle@uploadStoreHtml5'));

});
