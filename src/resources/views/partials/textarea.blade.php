@php
    $theme = $options['theme'] ?? config('pondol-editor.theme', 'light');
    $attrHtml = '';
    if (isset($attr)) {
        if (is_array($attr)) {
            foreach ($attr as $k => $v) {
                $attrHtml .= ' ' . e($k) . '="' . e($v) . '"';
            }
        } elseif (is_string($attr)) {
            $attrHtml = ' ' . $attr;
        }
    }
@endphp
<div class="pondol-editor-wrapper pondol-editor-theme-{{ $theme }}" data-editor-wrapper="{{ $id }}">
    <textarea name="{{ $name }}" id="{{ $id }}"{!! $attrHtml !!}>{{ $value ?? '' }}</textarea>
    {{ $slot ?? '' }}
</div>
