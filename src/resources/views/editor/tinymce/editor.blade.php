{{-- resources/views/editor/tinymce/editor.blade.php --}}
<textarea name="{{ $name }}" id="{{ $id }}"
    @if (isset($attr)) @foreach ($attr as $k => $v) {{ $k }}="{{ $v }}" @endforeach @endif>
@if (isset($value))
{{ $value }}
@endif
</textarea>

@section('scripts')
    @parent
    <script src="/plugins/editor/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        (function() {
            function initSingleTinyMCE() {
                if (typeof tinymce === 'undefined') return;

                tinymce.init({
                    selector: '#{{ $id }}',
                    plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help quickbars emoticons',
                    license_key: 'gpl',
                    skin: 'oxide-dark',
                    content_css: 'dark',
                    setup: function(editor) {
                        editor.on('change', function() {
                            editor.save();
                        });
                    }
                });
            }
            window.addEventListener('load', initSingleTinyMCE);
        })();
    </script>
@endsection
