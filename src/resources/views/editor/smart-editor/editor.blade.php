{{-- resources/views/editor/smart-editor/editor.blade.php --}}
<textarea name="{{ $name }}" id="{{ $id }}"
    @if (isset($attr)) @foreach ($attr as $k => $v)
      {{ $k }}="{{ $v }}" 
    @endforeach @endif>
@if (isset($value))
{{ $value }}
@endif
</textarea>

@section('scripts')
    @parent
    <script src="/plugins/editor/smart-editor/js/service/HuskyEZCreator.js"></script>
    <script>
        window.oEditors = window.oEditors || [];

        (function() {
            function initSingleEditor() {
                nhn.husky.EZCreator.createInIFrame({
                    oAppRef: window.oEditors,
                    elPlaceHolder: "{{ $id }}",
                    sSkinURI: "/plugins/editor/smart-editor/SmartEditor2Skin.html",
                    sCSSBaseURI: "/plugins/editor/smart-editor/css/ko_KR",
                    htParams: {
                        bUseToolbar: true,
                        bUseVerticalResizer: true,
                        bUseModeChanger: true
                    },
                    fCreator: "createSEditor2"
                });

                var el = document.getElementById("{{ $id }}");
                if (el && el.form) {
                    el.form.addEventListener('submit', function() {
                        if (window.oEditors.getById["{{ $id }}"]) {
                            window.oEditors.getById["{{ $id }}"].exec("UPDATE_CONTENTS_FIELD", []);
                        }
                    });
                }
            }

            if (document.readyState === "complete") {
                initSingleEditor();
            } else {
                window.addEventListener('load', initSingleEditor);
            }
        })();
    </script>
@endsection
