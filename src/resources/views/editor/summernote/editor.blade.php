{{-- src/resources/views/editor/summernote/editor.blade.php --}}
@php
    $base = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $opts = array_merge(
        config('pondol-editor.editors.summernote', []),
        [
            'theme' => config('pondol-editor.theme', 'light'),
            'uploadUrl' => route('editor.upload', ['driver' => 'summernote']),
            'baseUrl' => $base . '/summernote',
            'jqueryUrl' => $base . '/core/jquery.min.js',
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
    'driver' => 'summernote',
    'styles' => [$base . '/summernote/summernote-lite.min.css'],
    'scripts' => [],
    'initScript' =>
        '<script>PondolEditor.init("summernote", ' . json_encode($id) . ', ' . json_encode($opts) . ');</script>',
])
