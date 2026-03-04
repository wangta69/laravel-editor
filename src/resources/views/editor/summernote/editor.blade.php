{{-- resources/views/editor/summernote/editor.blade.php --}}
<textarea name="{{ $name }}" id="{{ $id }}">
@if (isset($value))
{!! $value !!}
@endif
</textarea>

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            $('#{{ $id }}').summernote({
                height: 350,
                lang: 'ko-KR',
                callbacks: {
                    onChange: function(contents) {
                        document.getElementById('{{ $id }}').value = contents;
                    }
                }
            });
        });
    </script>
@endsection
