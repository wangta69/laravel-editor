@extends('editor::layout.blank')
@section('content')
<form method="post" action="{{ route('editor.froala') }}" enctype="multipart/form-data">
  @csrf
  <div id="editor">

    <textarea name="editor_content" id="edit"></textarea>
  </div>
  <button type="submit">Submit </button>
</form>
<!-- /banner-feature -->
@endsection

@section('styles')
@parent
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/froala_editor.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/froala_style.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/code_view.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/draggable.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/colors.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/emoticons.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/image_manager.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/image.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/line_breaker.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/table.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/char_counter.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/video.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/fullscreen.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/file.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/quick_insert.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/help.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/third_party/spell_checker.css">
<link rel="stylesheet" href="/plugins/editor/froala/css/plugins/special_characters.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css">
@endsection

@section('scripts')
@parent
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.2.7/purify.min.js"></script>

  <script type="text/javascript" src="/plugins/editor/froala/js/froala_editor.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/align.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/char_counter.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/code_beautifier.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/code_view.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/colors.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/draggable.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/emoticons.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/entities.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/file.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/font_size.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/font_family.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/fullscreen.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/image.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/image_manager.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/line_breaker.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/inline_style.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/link.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/lists.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/paragraph_format.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/paragraph_style.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/quick_insert.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/quote.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/table.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/save.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/url.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/video.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/help.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/print.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/third_party/spell_checker.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/special_characters.min.js"></script>
  <script type="text/javascript" src="/plugins/editor/froala/js/plugins/word_paste.min.js"></script>


  <script>
    (function () {
      new FroalaEditor("#edit", {attribution: false})
    })()
  </script>
@endsection
