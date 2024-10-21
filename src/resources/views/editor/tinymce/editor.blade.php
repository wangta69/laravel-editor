<textarea name="{{$name}}" id="{{$id}}" 
  @if(isset($attr)) 
    @foreach($attr as $k => $v)
      {{$k}}="{{$v}}" 
    @endforeach 
  @endif
  >@if(isset($value)){{$value}}@endif</textarea>

@section('scripts')
@parent
<script src="/plugins/editor/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/accordion/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/advlist/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/anchor/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/autolink/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/autoresize/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/autosave/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/charmap/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/code/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/codesample/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/directionality/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/emoticons/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/fullscreen/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/help/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/image/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/importcss/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/insertdatetime/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/link/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/lists/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/media/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/nonbreaking/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/pagebreak/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/preview/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/quickbars/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/save/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/searchreplace/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/table/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/visualblocks/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/visualchars/plugin.min.js" referrerpolicy="origin"></script>
<script src="/plugins/editor/tinymce/plugins/wordcount/plugin.min.js" referrerpolicy="origin"></script>

<script>
(function () {
  tinymce.init({
    selector: '#{{$id}}',  // change this value according to your HTML
    plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media  codesample table charmap pagebreak nonbreaking anchor  insertdatetime advlist lists  wordcount help charmap quickbars emoticons',
    license_key: 'gpl'
  });
})()
</script>
@endsection