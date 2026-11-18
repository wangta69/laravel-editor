<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    */
    'prefix' => 'editor',
    'as' => 'editor.',
    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Default Template & Theme
    |--------------------------------------------------------------------------
    | 지원: suneditor, summernote, smart-editor, easymde, cherry-markdown,
    |       vditor, editorjs, tinymce, froala, richtext
    */
    'default-template' => env('PONDOL_EDITOR_DEFAULT', 'suneditor'),
    'theme' => env('PONDOL_EDITOR_THEME', 'light'), // 'light' | 'dark'
    'lang' => env('PONDOL_EDITOR_LANG', 'ko'),
    'height' => 350,

    /*
    |--------------------------------------------------------------------------
    | Blade Section Mode
    |--------------------------------------------------------------------------
    | 기존 레이아웃 호환을 위해 'section'(@section/@yield)이 기본값입니다.
    | @stack/@push 기반 레이아웃을 쓰는 경우 'stack'으로 변경하세요.
    */
    'section_mode' => 'section', // 'section' | 'stack'

    /*
    |--------------------------------------------------------------------------
    | Assets (Local 기본)
    |--------------------------------------------------------------------------
    */
    'assets' => [
        'base_url' => '/plugins/editor',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Upload
    |--------------------------------------------------------------------------
    */
    'upload' => [
        'disk' => env('PONDOL_EDITOR_DISK', 'public'),
        'path' => 'editor/images',
        'smarteditor_path' => 'tmp/editor',
        'max_size' => 5120, // KB (5MB)
        'allowed_mimes' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Per-Editor Default Options
    |--------------------------------------------------------------------------
    */
    'editors' => [
        'suneditor' => [
            'height' => '350px',
        ],
        'summernote' => [
            'height' => 350,
            'lang' => 'ko-KR',
        ],
        'easymde' => [
            'spellChecker' => false,
            'minHeight' => '300px',
        ],
        'cherry-markdown' => [
            'height' => '450px',
            'locale' => 'en_US', // ko_KR 미지원 (zh_CN | en_US | ru_RU 중 선택)
        ],
        'vditor' => [
            'height' => 420,
            'mode' => 'ir', // 'wysiwyg' | 'ir' | 'sv'
            'lang' => 'ko_KR',
        ],
        'editorjs' => [
            'minHeight' => 250,
        ],
        'tinymce' => [
            'license_key' => 'gpl',
            'language' => 'ko_KR',
        ],
    ],
];
