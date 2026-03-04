{{-- resources/views/editor/summernote/component.blade.php --}}
<textarea name="{{ $name }}" id="{{ $id }}" class="summernote-editor"
    @if (isset($attr)) @foreach ($attr as $k => $v) {{ $k }}="{{ $v }}" @endforeach @endif>
@if (isset($value))
{!! $value !!}
@endif
</textarea>

@section('styles')
    @parent
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        /* 길라 다크 테마 유지 */
        .note-editor.note-frame {
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            background-color: #1b263b !important;
        }

        .note-toolbar {
            background-color: #2a3b57 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }

        .note-btn {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #e0e1dd !important;
        }

        .note-btn:hover {
            background-color: var(--bs-primary) !important;
            color: #fff !important;
        }

        .note-editable {
            background-color: #1b263b !important;
            color: #e0e1dd !important;
        }

        /* 드롭다운 UI 오류 방지 (DropdownUI.js 관련) */
        .note-dropdown-menu {
            background-color: #2a3b57 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
    </style>
@endsection

@section('scripts')
    @parent
    {{-- [핵심] defer를 추가하여 jQuery 로드 이후에 순차 실행되도록 함 --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/lang/summernote-ko-KR.min.js" defer></script>

    <script>
        (function() {
            // defer된 jQuery와 Summernote가 모두 로드될 때까지 기다림
            function waitForLibraries(callback) {
                if (window.jQuery && window.jQuery().summernote) {
                    callback();
                } else {
                    // 50ms마다 재확인
                    setTimeout(function() {
                        waitForLibraries(callback);
                    }, 50);
                }
            }

            window.addEventListener('load', function() {
                waitForLibraries(function() {
                    @if ($editors)
                        @foreach ($editors as $k => $editor)
                            $('#{{ $editor['id'] }}').summernote({
                                height: 350,
                                lang: 'ko-KR',
                                placeholder: '고민 내용을 상세히 적어주시면 더 정확한 풀이가 가능합니다.',
                                toolbar: [
                                    ['style', ['style']],
                                    ['font', ['bold', 'underline', 'clear']],
                                    ['color', ['color']],
                                    ['para', ['ul', 'ol', 'paragraph']],
                                    ['table', ['table']],
                                    ['insert', ['link', 'picture', 'video']],
                                    ['view', ['fullscreen', 'codeview', 'help']]
                                ],
                                callbacks: {
                                    onChange: function(contents) {
                                        document.getElementById('{{ $editor['id'] }}')
                                            .value = contents;
                                    }
                                }
                            });
                        @endforeach
                    @endif
                });
            });
        })();
    </script>
@endsection
