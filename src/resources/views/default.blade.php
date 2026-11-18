@php
    $resolvedTemplate = $template ?? config('pondol-editor.default-template', 'suneditor');
    $resolvedId = $id ?? 'editor_' . uniqid();
@endphp

@include('editor::editor.' . $resolvedTemplate . '.editor', [
    'template' => $resolvedTemplate,
    'name' => $name,
    'id' => $resolvedId,
    'value' => $value ?? null,
    'attr' => $attr ?? [],
    'options' => $options ?? [],
])
