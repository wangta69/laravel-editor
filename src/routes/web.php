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
  Route::get('smart-editor', array('uses'=>'SmartEditorController@main'))->name('smarteditor');
  // Route::post('smart-editor', array('uses'=>'SmartEditorController@store'));
  Route::get('smart-editor/photo-upload', array('uses'=>'SmartEditorController@upload'))->name('smarteditor.photo');
  Route::post('smart-editor/photo-upload', array('uses'=>'SmartEditorController@uploadStore'));
  Route::post('smart-editor/photo-upload/html5', array('uses'=>'SmartEditorController@uploadStoreHtml5'));
});
