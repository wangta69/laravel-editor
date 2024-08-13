<textarea name="{{$name}}" id="{{$id}}" 
  @if(isset($attr)) 
    @foreach($attr as $k => $v)
      {{$k}}="{{$v}}" 
    @endforeach 
  @endif
  >@if(isset($value)){{$value}}@endif</textarea>
@section('scripts')
@parent
@if($editors)
<script src="/plugins/editor/smart-editor/js/service/HuskyEZCreator.js"></script>
@endif
<script>
$(function(){
// 폼 submit 하기 전 에디터의 내용을 textarea에 넣음. 
@if($onsubmit == 'true' && $editors)
  @foreach($editors as $k=>$editor)
  $('#{{$editor['id']}}').closest('form').on('submit', function() {
    oEditors{{$k}}.getById["{{$editor['id']}}"].exec("UPDATE_CONTENTS_FIELD", []); // 에디터의 내용이 textarea에 적용됩니다.
  });
  @endforeach
@endif
})
  @if($editors)

  @foreach($editors as $k=>$editor)
  var oEditors{{$k}} = [];
  nhn.husky.EZCreator.createInIFrame({
    oAppRef: oEditors{{$k}},
    elPlaceHolder: "{{$editor['id']}}", // 적용될 textarea 의 ID값 입력
    sSkinURI: "/plugins/editor/smart-editor/SmartEditor2Skin.html",
    sCSSBaseURI: "/plugins/editor/smart-editor/css/ko_KR",
    htParams : {
      bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
      bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
      bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
      //aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
      fOnBeforeUnload : function(){
        //alert("완료!");
      }
    }, //boolean
    fOnAppLoad : function(){
      //예제 코드
      //oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
    },
    fCreator: "createSEditor2"
  });
 @endforeach

  function updateContentsField() {
    @foreach($editors as $k=>$editor)
    oEditors{{$k}}.getById["{{$editor['id']}}"].exec("UPDATE_CONTENTS_FIELD", []);
    @endforeach
  }
  @endif

  function submitContentsField(f) {
    @foreach($editors as $editor)
    oEditors.getById[{{$editor['id']}}].exec("UPDATE_CONTENTS_FIELD", []);
    @endforeach
    try {
      f.submit();
    } catch (e) {}
  }
</script>

@endsection