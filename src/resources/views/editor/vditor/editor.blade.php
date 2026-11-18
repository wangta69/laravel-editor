@php
    $base = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $opts = array_merge(
        config('pondol-editor.editors.vditor', []),
        [
            'theme' => config('pondol-editor.theme', 'light'),
            'cdn' => $base . '/vditor',
            'uploadUrl' => route('editor.upload', ['driver' => 'vditor']),
        ],
        $options ?? [],
    );
    $vditorLang = $opts['lang'] ?? 'ko_KR';
@endphp

@component('editor::partials.textarea', [
    'name' => $name,
    'id' => $id,
    'value' => $value ?? null,
    'attr' => $attr ?? [],
    'options' => $opts,
])
    <div id="{{ $id }}_holder"></div>
@endcomponent

@include('editor::partials.assets', [
    'driver' => 'vditor',
    'styles' => [$base . '/vditor/dist/index.css'],
    'scripts' => [
        $base . '/vditor/dist/js/i18n/' . $vditorLang . '.js',
        $base . '/vditor/dist/js/icons/ant.js',
        $base . '/vditor/dist/index.min.js',
    ],
    'initScript' =>
        '<script>PondolEditor.init("vditor", ' . json_encode($id) . ', ' . json_encode($opts) . ');</script>',
])
