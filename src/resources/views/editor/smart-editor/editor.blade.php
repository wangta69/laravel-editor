@php
    $base = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $opts = array_merge(['baseUrl' => $base . '/smart-editor'], $options ?? []);
@endphp

@include('editor::partials.textarea', [
    'name' => $name,
    'id' => $id,
    'value' => $value ?? null,
    'attr' => $attr ?? [],
    'options' => $opts,
])

@include('editor::partials.assets', [
    'driver' => 'smart-editor',
    'styles' => [],
    'scripts' => [$base . '/smart-editor/js/service/HuskyEZCreator.js'],
    'initScript' =>
        '<script>PondolEditor.init("smart-editor", ' .
        json_encode($id) .
        ', ' .
        json_encode($opts) .
        ');</script>',
])
