@php
    $coreBase = rtrim(config('pondol-editor.assets.base_url', '/plugins/editor'), '/');
    $sectionMode = $options['section_mode'] ?? null ?: config('pondol-editor.section_mode', 'section');
    $driver = $driver ?? 'default';
    $coreVer = @filemtime(public_path('plugins/editor/core/pondol-editor.js')) ?: '2.0.0';
@endphp

@if ($sectionMode === 'inline')
    @once('pondol-editor-core-css')
        <link rel="stylesheet" href="{{ $coreBase }}/core/pondol-editor.css?v={{ $coreVer }}">
    @endonce
    @if (!empty($styles))
        @once('pondol-editor-css-' . $driver)
            @foreach ($styles as $href)
                <link rel="stylesheet" href="{{ $href }}">
            @endforeach
        @endonce
    @endif
    @once('pondol-editor-core-js')
        <script src="{{ $coreBase }}/core/pondol-editor.js?v={{ $coreVer }}"></script>
        <script src="{{ $coreBase }}/core/pondol-editor-drivers.js?v={{ $coreVer }}"></script>
    @endonce
    @if (!empty($scripts))
        @once('pondol-editor-js-' . $driver)
            @foreach ($scripts as $src)
                <script src="{{ $src }}"></script>
            @endforeach
        @endonce
    @endif
    @if (!empty($initScript))
        {!! $initScript !!}
    @endif
@elseif ($sectionMode === 'stack')
    @push('styles')
        @once('pondol-editor-core-css')
            <link rel="stylesheet" href="{{ $coreBase }}/core/pondol-editor.css">
        @endonce
        @if (!empty($styles))
            @once('pondol-editor-css-' . $driver)
                @foreach ($styles as $href)
                    <link rel="stylesheet" href="{{ $href }}">
                @endforeach
            @endonce
        @endif
    @endpush

    @push('scripts')
        @once('pondol-editor-core-js')
            <script src="{{ $coreBase }}/core/pondol-editor.js?v={{ $coreVer }}"></script>
            <script src="{{ $coreBase }}/core/pondol-editor-drivers.js?v={{ $coreVer }}"></script>
        @endonce
        @if (!empty($scripts))
            @once('pondol-editor-js-' . $driver)
                @foreach ($scripts as $src)
                    <script src="{{ $src }}"></script>
                @endforeach
            @endonce
        @endif
        @if (!empty($initScript))
            {!! $initScript !!}
        @endif
    @endpush
@else
    @section('styles')
        @parent
        @once('pondol-editor-core-css')
            <link rel="stylesheet" href="{{ $coreBase }}/core/pondol-editor.css">
        @endonce
        @if (!empty($styles))
            @once('pondol-editor-css-' . $driver)
                @foreach ($styles as $href)
                    <link rel="stylesheet" href="{{ $href }}">
                @endforeach
            @endonce
        @endif
    @endsection

    @section('scripts')
        @parent
        @once('pondol-editor-core-js')
            <script src="{{ $coreBase }}/core/pondol-editor.js?v={{ $coreVer }}"></script>
            <script src="{{ $coreBase }}/core/pondol-editor-drivers.js?v={{ $coreVer }}"></script>
        @endonce
        @if (!empty($scripts))
            @once('pondol-editor-js-' . $driver)
                @foreach ($scripts as $src)
                    <script src="{{ $src }}"></script>
                @endforeach
            @endonce
        @endif
        @if (!empty($initScript))
            {!! $initScript !!}
        @endif
    @endsection
@endif
