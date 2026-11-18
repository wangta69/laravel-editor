@php
    $base = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $opts = array_merge(
        config('pondol-editor.editors.easymde', []),
        [
            'theme' => config('pondol-editor.theme', 'light'),
            'uploadUrl' => route('editor.upload', ['driver' => 'easymde']),
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
    'driver' => 'easymde',
    'styles' => [$base . '/easymde/easymde.min.css'],
    'scripts' => [$base . '/easymde/easymde.min.js'],
    'initScript' =>
        '<script>PondolEditor.init("easymde", ' . json_encode($id) . ', ' . json_encode($opts) . ');</script>',
])
