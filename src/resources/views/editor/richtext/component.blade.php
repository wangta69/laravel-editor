<textarea name="{{$name}}" id="{{$id}}" 
  @if(isset($attr)) 
    @foreach($attr as $k => $v)
      {{$k}}="{{$v}}" 
    @endforeach 
  @endif
  >@if(isset($value)){{$value}}@endif</textarea>

@section('styles')
@parent
@if($editors)
<link rel="stylesheet" href="/plugins/editor/richtext/runtime/richtexteditor_preview.css" />
<link rel="stylesheet" href="/plugins/editor/richtext/rte_theme_default.css" />
@endif
@endsection

@section('scripts')
@parent
@if($editors)
<script type="text/javascript" src="/plugins/editor/richtext/rte.js"></script>
<script type="text/javascript" src='/plugins/editor/richtext/plugins/all_plugins.js'></script>
<!-- <script type="text/javascript" src='/plugins/editor/richtext/rte-upload.js'></script> -->

@endif
<script>
@if($editors)
var editorcfg = {url_base: '/plugins/editor/richtext'};
@foreach($editors as $k=>$editor)
var editor{{$k}} = new RichTextEditor(document.getElementById("{{$editor['id']}}"), editorcfg);
editor{{$k}}.attachEvent("change", function () {
  document.getElementById("inp_htmlcode").value = editor{{$k}}.getHTMLCode();
});

@endforeach
@endif


</script>

@endsection