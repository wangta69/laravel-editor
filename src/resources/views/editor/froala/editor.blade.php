@php
    $base = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $opts = array_merge(['theme' => config('pondol-editor.theme', 'light')], $options ?? []);
    $froalaCssPlugins = [
        'code_view',
        'draggable',
        'colors',
        'emoticons',
        'image_manager',
        'image',
        'line_breaker',
        'table',
        'char_counter',
        'video',
        'fullscreen',
        'file',
        'quick_insert',
        'help',
        'special_characters',
    ];
    $froalaJsPlugins = [
        'align',
        'char_counter',
        'code_beautifier',
        'code_view',
        'colors',
        'draggable',
        'emoticons',
        'entities',
        'file',
        'font_size',
        'font_family',
        'fullscreen',
        'image',
        'image_manager',
        'line_breaker',
        'inline_style',
        'link',
        'lists',
        'paragraph_format',
        'paragraph_style',
        'quick_insert',
        'quote',
        'table',
        'save',
        'url',
        'video',
        'help',
        'print',
        'special_characters',
        'word_paste',
    ];
    $styles = array_merge(
        [
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css',
            $base . '/froala/css/froala_editor.css',
            $base . '/froala/css/froala_style.css',
            $base . '/froala/css/third_party/spell_checker.css',
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css',
        ],
        array_map(fn($p) => $base . '/froala/css/plugins/' . $p . '.css', $froalaCssPlugins),
    );
    $scripts = array_merge(
        [
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.2.7/purify.min.js',
            $base . '/froala/js/froala_editor.min.js',
            $base . '/froala/js/third_party/spell_checker.min.js',
        ],
        array_map(fn($p) => $base . '/froala/js/plugins/' . $p . '.min.js', $froalaJsPlugins),
    );
@endphp

@include('editor::partials.textarea', [
    'name' => $name,
    'id' => $id,
    'value' => $value ?? null,
    'attr' => $attr ?? [],
    'options' => $opts,
])

@include('editor::partials.assets', [
    'driver' => 'froala',
    'styles' => $styles,
    'scripts' => $scripts,
    'initScript' =>
        '<script>PondolEditor.init("froala", ' . json_encode($id) . ', ' . json_encode($opts) . ');</script>',
])
