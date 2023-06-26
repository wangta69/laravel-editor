# NAVER SMART Editor for Laravel




## Installation
```
composer require wangta69/laravel-editor
```
```
php artisan vendor:publish --provider="Pondol\Editor\EditorServiceProvider"
```

### 2. 이미지등을 저장할 storage를 만든다.
> 아래와 같이 걸어두면 /project/public/storage 의 내용들이  /project/storage/app/public 으로 심볼릭 링크로 된다.
```
php artisan storage:link
```


####
> https;//yourdomain/editor/smart-editor


## 기존 프로젝트에 Smart-Editor 사용하기
> naver smart editor 가 정상적으로 노출되는 것을 확인했다면 이제 실제 프로제트에서 editor를 호출하여 작업해 보자

> 원소스
```
@section('content')
<div id="content">
    <h2 class="title">제품등록</h2>

		{{ Form::open(['route'=>['market.admin.item.store'], 'method'=>'POST', 'enctype'=>'multipart/form-data', 'class'=>'']) }}

		<table class="table">
			<col width="120px" />
			<col width="*" />
			<tr>
					<th class="active">제목</th>
					<td><input name="name" class="form-control">
					</td>
			</tr>
			<tr>
					<th class="active">자세한설명
					</th>
					<td><textarea name="descriptions" class="form-control" id="descriptions"></textarea></td>
			</tr>

		</table>
		<div class="text-center">
				<button type="submit" class="btn btn-primary">제품등록</button>
		</div>
		{{ Form::close() }}
</div>
@endsection

```
> 적용된 이후의 소스
```
@section('content')
<div id="content">
    @include('layouts.admin.main-top')
    <h2 class="title">제품등록</h2>

		{{ Form::open(['route'=>['market.admin.item.store'], 'method'=>'POST', 'enctype'=>'multipart/form-data', 'class'=>'']) }}

		<table class="table">
			<col width="120px" />
			<col width="*" />
			<tr>
					<th class="active">제목</th>
					<td><input name="name" class="form-control" value="">
					</td>
			</tr>
			<tr>
					<th class="active">자세한설명
					</th>
					<td><textarea name="descriptions" class="form-control" id="descriptions"></textarea></td>
			</tr>

		</table>
		<div class="text-center">
				<button type="button" onclick="submitContents(this);" class="btn btn-primary">제품등록</button> <!-- onclick 시 아래 정의된 submitContents()  호출 -->
		</div>
		{{ Form::close() }}
</div>
@endsection

@section('scripts')
  @parent
	{{ Html::script('/plugins/editor/smart-editor/js/service/HuskyEZCreator.js') }}
<script>
  var oEditors = [];
  nhn.husky.EZCreator.createInIFrame({
    oAppRef: oEditors,
    elPlaceHolder: "descriptions", // 적용될 textarea 의 ID값 입력
    sSkinURI: "/plugins/editor/smart-editor/SmartEditor2Skin.html",
    fCreator: "createSEditor2"
  });

  function submitContents(elClickedObj) {
    oEditors.getById["descriptions"].exec("UPDATE_CONTENTS_FIELD", []);	
    try {
      elClickedObj.form.submit();
    } catch(e) {}
  }
</script>
@endsection
```

