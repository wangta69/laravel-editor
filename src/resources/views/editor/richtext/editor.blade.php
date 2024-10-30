<textarea name="{{$name}}" id="{{$id}}" 
  @if(isset($attr)) 
    @foreach($attr as $k => $v)
      {{$k}}="{{$v}}" 
    @endforeach 
  @endif
  >@if(isset($value)){{$value}}@endif</textarea>

@section('styles')
@parent
<!-- <link rel="stylesheet" href="/plugins/editor/richtext/runtime/richtexteditor_preview.css" /> -->
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
var editor = new RichTextEditor(document.getElementById("{{$id}}"), editorcfg);
editor.attachEvent("change", function () {
  document.getElementById("inp_htmlcode").value = editor.getHTMLCode();
});
</script>
@endsection