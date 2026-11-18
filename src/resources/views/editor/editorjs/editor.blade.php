@php
    $base = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $opts = array_merge(
        config('pondol-editor.editors.editorjs', []),
        [
            'theme' => config('pondol-editor.theme', 'light'),
            'uploadUrl' => route('editor.upload', ['driver' => 'editorjs']),
        ],
        $options ?? [],
    );
@endphp

@component('editor::partials.textarea', [
    'name' => $name,
    'id' => $id,
    'value' => $value ?? null,
    'attr' => $attr ?? [],
    'options' => $opts,
])
    <div id="{{ $id }}_holder" class="pondol-editorjs-holder"></div>
@endcomponent

@include('editor::partials.assets', [
    'driver' => 'editorjs',
    'styles' => [],
    'scripts' => [
        $base . '/editorjs/editorjs.umd.min.js',
        $base . '/editorjs/header.umd.js',
        $base . '/editorjs/list.umd.js',
        $base . '/editorjs/image.umd.js',
        $base . '/editorjs/quote.umd.js',
        $base . '/editorjs/code.umd.js',
        $base . '/editorjs/table.umd.js',
    ],
    'initScript' =>
        '<script>PondolEditor.init("editorjs", ' . json_encode($id) . ', ' . json_encode($opts) . ');</script>',
])
