<?php

use Illuminate\Support\Facades\Route;

// 통합 이미지 업로드 API (신규 에디터 + Summernote + TinyMCE 공용)
Route::post('upload', 'UploadController@store')->name('upload');

// 기존 SmartEditor 라우트 100% 유지
Route::get('smart-editor', 'SmartEditorController@create')->name('smarteditor');
Route::post('smart-editor', 'SmartEditorController@store');
Route::get('smart-editor/photo-upload', 'SmartEditorController@upload')->name('smarteditor.photo');
Route::post('smart-editor/photo-upload', 'SmartEditorController@uploadStore');
Route::post('smart-editor/photo-upload/html5', 'SmartEditorController@uploadStoreHtml5')->name('smarteditor.photo.html5');

// 기존 레거시 라우트 100% 유지
Route::get('froala', 'FroalaEditorController@create')->name('froala');
Route::post('froala', 'FroalaEditorController@store');

Route::get('tinymce', 'TinymceEditorController@create')->name('tinymce');
Route::post('tinymce', 'TinymceEditorController@store');

Route::get('richtext', 'RichTextEditorController@create')->name('richtext');
Route::post('richtext', 'RichTextEditorController@store');

// 모든 에디터 통합 샘플 라우트 (route:cache 호환)
Route::get('sample/{template?}', 'SampleController@show')->name('sample');
Route::post('sample/{template}', 'SampleController@store')->name('sample.store');
