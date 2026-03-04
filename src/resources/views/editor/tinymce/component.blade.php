{{-- resources/views/editor/tinymce/component.blade.php --}}
<textarea name="{{ $name }}" id="{{ $id }}"
    @if (isset($attr)) @foreach ($attr as $k => $v) {{ $k }}="{{ $v }}" @endforeach @endif>
@if (isset($value))
{{ $value }}
@endif
</textarea>

@section('scripts')
    @parent
    @if ($editors)
        {{-- 메인 JS 하나만 로드하면 플러그인은 내부적으로 자동 로드됩니다 --}}
        <script src="/plugins/editor/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    @endif
    <script>
        (function() {
            function initTinyMCE() {
                if (typeof tinymce === 'undefined') {
                    setTimeout(initTinyMCE, 100); // 로드될 때까지 잠시 대기
                    return;
                }

                @foreach ($editors as $k => $editor)
                    tinymce.init({
                        selector: '#{{ $editor['id'] }}',
                        plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help quickbars emoticons',
                        toolbar: 'undo redo | blocks | bold italic strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | emoticons charmap | removeformat',
                        images_file_types: 'jpg,svg,webp',
                        license_key: 'gpl',
                        language: 'ko_KR', // 한글 팩이 있다면 적용

                        // [길라 전용 다크 테마 설정]
                        skin: 'oxide-dark',
                        content_css: 'dark',

                        setup: function(editor) {
                            // 내용이 변경될 때마다 원본 textarea에 동기화
                            editor.on('change', function() {
                                editor.save();
                            });
                        }
                    });
                @endforeach
            }

            // defer 환경 대응: 페이지 로드 완료 후 실행
            if (document.readyState === "complete") {
                initTinyMCE();
            } else {
                window.addEventListener('load', initTinyMCE);
            }
        })();
    </script>
@endsection
