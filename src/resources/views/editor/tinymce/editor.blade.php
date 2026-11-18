@php
    $base = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $opts = array_merge(
        config('pondol-editor.editors.tinymce', []),
        [
            'theme' => config('pondol-editor.theme', 'light'),
            'uploadUrl' => route('editor.upload', ['driver' => 'tinymce']),
        ],
        $options ?? [],
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
    'driver' => 'tinymce',
    'styles' => [],
    'scripts' => [$base . '/tinymce/tinymce.min.js'],
    'initScript' =>
        '<script>PondolEditor.init("tinymce", ' . json_encode($id) . ', ' . json_encode($opts) . ');</script>',
])
