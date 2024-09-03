@extends('editor::layout.blank')
@section('content')
<form method="post" action="{{ route('editor.smarteditor') }}" enctype="multipart/form-data">
  @csrf
  <textarea name="ir1" id="ir1" style="width: 100%;">{{$data->ir1}}</textarea>
  <input type="button" onclick="submitContents(this);" value="서버로 내용 전송" />
</form>

<!-- /banner-feature -->
@endsection

@section('styles')
@parent
@endsection

@section('scripts')
@parent
<script src="/plugins/editor/smart-editor/js/service/HuskyEZCreator.js"></script>
<script>
  var oEditors = [];
  nhn.husky.EZCreator.createInIFrame({
    oAppRef: oEditors,
    elPlaceHolder: "ir1",
    sSkinURI: "/plugins/editor/smart-editor/SmartEditor2Skin.html",
    fCreator: "createSEditor2"
  });

  function submitContents(elClickedObj) {
    oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
    // 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("ir1").value를 이용해서 처리하면 됩니다.
    try {
      elClickedObj.form.submit();
    } catch(e) {}
  }
</script>
@endsection
