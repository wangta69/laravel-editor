@php
    $base = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $opts = array_merge(
        config('pondol-editor.editors.cherry-markdown', []),
        [
            'theme' => config('pondol-editor.theme', 'light'),
            'uploadUrl' => route('editor.upload', ['driver' => 'cherry-markdown']),
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
    <div id="{{ $id }}_holder"></div>
@endcomponent

@include('editor::partials.assets', [
    'driver' => 'cherry-markdown',
    'styles' => [$base . '/cherry-markdown/cherry-markdown.css'],
    'scripts' => [$base . '/cherry-markdown/cherry-markdown.js'],
    'initScript' =>
        '<script>PondolEditor.init("cherry-markdown", ' .
        json_encode($id) .
        ', ' .
        json_encode($opts) .
        ');</script>',
])
