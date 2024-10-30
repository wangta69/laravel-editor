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

Route::get('smart-editor', array('uses'=>'SmartEditorController@create'))->name('smarteditor');
Route::post('smart-editor', array('uses'=>'SmartEditorController@store'));
Route::get('smart-editor/photo-upload', array('uses'=>'SmartEditorController@upload'))->name('smarteditor.photo');
Route::post('smart-editor/photo-upload', array('uses'=>'SmartEditorController@uploadStore'));
Route::post('smart-editor/photo-upload/html5', array('uses'=>'SmartEditorController@uploadStoreHtml5'));

Route::get('froala', array('uses'=>'FroalaEditorController@create'))->name('froala');
Route::post('froala', array('uses'=>'FroalaEditorController@store'));

Route::get('tinymce', array('uses'=>'TinymceEditorController@create'))->name('tinymce');
Route::post('tinymce', array('uses'=>'TinymceEditorController@store'));


Route::get('richtext', array('uses'=>'RichTextEditorController@create'))->name('richtext');
Route::post('richtext', array('uses'=>'RichTextEditorController@store'));

