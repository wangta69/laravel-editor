# Wizwig Editor for Laravel
> 현재 배포중인 에디터들을 라라벨에서 바로 사용할 수 있게 처리된 플러그인

## document

[공식사이트](https://www.onstory.fun/doc/programming/laravel/package.laraveleditor)


## Installation
```
composer require wangta69/laravel-editor
php artisan pondol:install-editor
```

## Configure
> config/pondol-editor 에서 default-template 값을 변경하시면 원하시는 에디터로 적용됩니다.

### Templates
- richtext : RichText Editor (default)
- tinymce : TinyMce
- smart-editor : Naver Smart Editor
- froala: Froala Editor

## Tests
> 세팅이 완료된 후 서비스가능한 Templates 을 입력하시면 세팅을 확인 하실 수 있습니다.
```
https://yourdomain/editor/[templates]
https://yourdomain/editor/smart-editor // Naver Smart Editor 적용시
```
## Ex
### before
```
<form>
  <textarea name="comment"></textarea>
  <button type="submit">Save</button>
</form>
```
### after
```
<form>
  @include ('editor::default', ['name'=>'comment', 'id'=>'comment-id', 'value'=>'', 'attr'=>['class'=>'']])
  <button type="submit">Save</button>
</form>
```
#### 다른 Templates 사용하기
> configure에 설정된 template외에 다른 것을 사용하고 싶다면 아래처럼 처리하시면 됩니다.
- TinyMce 적용예
``` 
<form>
  @include ('editor::tinymce.editor', ['name'=>'comment', 'id'=>'comment-id', 'value'=>'', 'attr'=>['class'=>'']])
  <button type="submit">Save</button>
</form>
```

![laravel WYSIWYG editor](./assets/images/editor-sample.png)

- [for more visit](https://www.onstory.fun/doc/programming/laravel/package.laraveleditor)


  