{{-- resources/views/editor/richtext/component.blade.php --}}
<textarea name="{{ $name }}" id="{{ $id }}"
    @if (isset($attr)) @foreach ($attr as $k => $v)
      {{ $k }}="{{ $v }}" 
    @endforeach @endif>
@if (isset($value))
{{ $value }}
@endif
</textarea>

@section('styles')
    @parent
    @if ($editors)
        <link rel="stylesheet" href="/plugins/editor/richtext/rte_theme_default.css" />
    @endif
@endsection

@section('scripts')
    @parent
    @if ($editors)
        <script type="text/javascript" src="/plugins/editor/richtext/rte.js"></script>
        <script type="text/javascript" src='/plugins/editor/richtext/plugins/all_plugins.js'></script>
    @endif
    <script>
        // 전역 객체에 에디터 인스턴스를 저장하여 외부에서 접근 가능하게 함
        window.RTEditorInstances = window.RTEditorInstances || {};

        @if ($editors)
            var editorcfg = {
                url_base: '/plugins/editor/richtext'
            };
            @foreach ($editors as $k => $editor)
                (function() {
                    var editorId = "{{ $editor['id'] }}";
                    var instance = new RichTextEditor(document.getElementById(editorId), editorcfg);

                    // 인스턴스 보관
                    window.RTEditorInstances[editorId] = instance;

                    // 값이 바뀔 때마다 원본 textarea에 동기화 (ID 수정됨)
                    instance.attachEvent("change", function() {
                        document.getElementById(editorId).value = instance.getHTMLCode();
                    });
                })();
            @endforeach
        @endif
    </script>
@endsection
