@extends('editor::layout.blank')
@section('content')
<form method="post" action="{{ route('editor.richtext') }}" enctype="multipart/form-data">
  @csrf
  <input name="htmlcode" id="inp_htmlcode" type="hidden" />


    <div id="div_editor1" class="richtexteditor" style="width: 960px;margin:0 auto;">
    </div>

    <div style="margin:0 auto;padding:24px;">
      <button class="btn btn-primary">Submit</button>
    </div>

</form>
<!-- /banner-feature -->
@endsection

@section('styles')
@parent
<link rel="stylesheet" href="/plugins/editor/richtext/runtime/richtexteditor_preview.css" />
<link rel="stylesheet" href="/plugins/editor/richtext/rte_theme_default.css" />
@endsection

@section('scripts')
@parent

<script type="text/javascript" src="/plugins/editor/richtext/rte.js"></script>
<script type="text/javascript" src='/plugins/editor/richtext/plugins/all_plugins.js'></script>
<!-- <script type="text/javascript" src='/plugins/editor/richtext/rte-upload.js'></script> -->
<script>
// var uploadhandlerpath = "/rte-upload.php"; // for rte-upload.js
var editorcfg = {url_base: '/plugins/editor/richtext'};
var editor1 = new RichTextEditor(document.getElementById("div_editor1"), editorcfg);
editor1.attachEvent("change", function () {
  document.getElementById("inp_htmlcode").value = editor1.getHTMLCode();
});
</script>
@endsection
