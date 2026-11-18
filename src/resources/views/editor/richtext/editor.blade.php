@php
    $base = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $opts = array_merge(['baseUrl' => $base . '/richtext'], $options ?? []);
@endphp

@include('editor::partials.textarea', [
    'name' => $name,
    'id' => $id,
    'value' => $value ?? null,
    'attr' => $attr ?? [],
    'options' => $opts,
])

@include('editor::partials.assets', [
    'driver' => 'richtext',
    'styles' => [$base . '/richtext/rte_theme_default.css'],
    'scripts' => [$base . '/richtext/rte.js', $base . '/richtext/plugins/all_plugins.js'],
    'initScript' =>
        '<script>PondolEditor.init("richtext", ' . json_encode($id) . ', ' . json_encode($opts) . ');</script>',
])
