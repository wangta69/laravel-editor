@extends('editor::layout.blank')
@section('content')
<form method="post" action="{{ route('editor.tinymce') }}" enctype="multipart/form-data">
  @csrf
  <div id="editor">

    <textarea name="editor_content" id="edit"></textarea>
  </div>
  <input type="submit"  value="서버로 내용 전송" />
</form>
<!-- /banner-feature -->
@endsection


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
    selector: '#edit',  // change this value according to your HTML
    plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media  codesample table charmap pagebreak nonbreaking anchor  insertdatetime advlist lists  wordcount help charmap quickbars emoticons',
    // plugins: 'ai powerpaste preview casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link math media mediaembed codesample table charmap pagebreak nonbreaking anchor tableofcontents insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker editimage help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable footnotes mergetags autocorrect typography advtemplate markdown revisionhistory',
    // toolbar: 'image',
    images_file_types: 'jpg,svg,webp',
    license_key: 'gpl',
    file_picker_callback: (callback, value, meta) => {
      // Provide file and text for the link dialog
      if (meta.filetype == 'file') {
        callback('mypage.html', { text: 'My text' });
      }

      // Provide image and alt text for the image dialog
      if (meta.filetype == 'image') {
        callback('myimage.jpg', { alt: 'My alt text' });
      }

      // Provide alternative source and posted for the media dialog
      if (meta.filetype == 'media') {
        callback('movie.mp4', { source2: 'alt.ogg', poster: 'image.jpg' });
      }
    }
  });
})()
</script>
@endsection
