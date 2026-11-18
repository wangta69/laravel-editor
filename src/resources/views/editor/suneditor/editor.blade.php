@php
    $base = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $opts = array_merge(
        config('pondol-editor.editors.suneditor', []),
        [
            'lang' => config('pondol-editor.lang', 'ko'),
            'theme' => config('pondol-editor.theme', 'light'),
            'uploadUrl' => route('editor.upload', ['driver' => 'suneditor']),
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
    'driver' => 'suneditor',
    'styles' => [$base . '/suneditor/suneditor.min.css'],
    'scripts' => [$base . '/suneditor/suneditor.min.js', $base . '/suneditor/langs/ko.js'],
    'initScript' =>
        '<script>PondolEditor.init("suneditor", ' . json_encode($id) . ', ' . json_encode($opts) . ');</script>',
])
