{{-- resources/views/editor/smart-editor/component.blade.php --}}
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
    @if ($editors)
        <script src="/plugins/editor/smart-editor/js/service/HuskyEZCreator.js"></script>
    @endif
    <script>
        // 전역 에디터 객체 통합 (여러 에디터가 이 배열 하나를 공유해야 getById가 작동함)
        window.oEditors = window.oEditors || [];

        (function() {
            function initSmartEditor() {
                @if ($editors)
                    @foreach ($editors as $k => $editor)
                        nhn.husky.EZCreator.createInIFrame({
                            oAppRef: window.oEditors,
                            elPlaceHolder: "{{ $editor['id'] }}",
                            sSkinURI: "/plugins/editor/smart-editor/SmartEditor2Skin.html",
                            sCSSBaseURI: "/plugins/editor/smart-editor/css/ko_KR",
                            htParams: {
                                bUseToolbar: true,
                                bUseVerticalResizer: true,
                                bUseModeChanger: true
                            },
                            fCreator: "createSEditor2"
                        });

                        // 폼 제출 시 자동 동기화 (Native JS로 구현하여 jQuery defer 대응)
                        var targetTextarea = document.getElementById("{{ $editor['id'] }}");
                        if (targetTextarea && targetTextarea.form) {
                            targetTextarea.form.addEventListener('submit', function() {
                                if (window.oEditors.getById["{{ $editor['id'] }}"]) {
                                    window.oEditors.getById["{{ $editor['id'] }}"].exec("UPDATE_CONTENTS_FIELD",
                                        []);
                                }
                            });
                        }
                    @endforeach
                @endif
            }

            // 페이지 로드 완료 후 실행 (defer된 jQuery가 로드될 시간을 벌어줌)
            if (document.readyState === "complete") {
                initSmartEditor();
            } else {
                window.addEventListener('load', initSmartEditor);
            }
        })();

        // 외부에서 호출 가능한 범용 업데이트 함수
        function updateContentsField() {
            if (!window.oEditors || !window.oEditors.getById) return;
            @foreach ($editors as $editor)
                if (window.oEditors.getById["{{ $editor['id'] }}"]) {
                    window.oEditors.getById["{{ $editor['id'] }}"].exec("UPDATE_CONTENTS_FIELD", []);
                }
            @endforeach
        }
    </script>
@endsection
