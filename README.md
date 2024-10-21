# Wizwig Editor for Laravel
> 현재 배포중인 에디터들을 라라벨에서 바로 사용할 수 있게 처리된 플러그인

## 제공 Editor
- TinyMce
- Naver Smart Editor
- Froala Editor
## document

[공식사이트](https://www.onstory.fun/doc/programming/laravel/package.laraveleditor)


## Installation
```
composer require wangta69/laravel-editor
php artisan pondol:install-editor
```

## Tests
> for tinymce editor :  https://yourdomain/editor/tinymce
> for naver smart editor :  https://yourdomain/editor/smart-editor
> for naver smart editor :  https://yourdomain/editor/froala

## Ex
### before
```
<form>
  <textarea name="comment"></textarea>
  <button type="submit">Save</button>
</form>
```
### after
#### tinymce editor
```
<form>
  @include ('editor::tinymce.editor', ['name'=>'comment', 'id'=>'comment-id', 'value'=>'', 'attr'=>['class'=>'']])
  <button type="submit">Save</button>
</form>
```
#### naver smart editor
```
<form>
  @include ('editor::smart-editor.editor', ['name'=>'comment', 'id'=>'comment-id', 'value'=>'', 'attr'=>['class'=>'']])
  <button type="submit">Save</button>
</form>
```
#### froala editor
```
<form>
  @include ('editor::froala.editor', ['name'=>'comment', 'id'=>'comment-id', 'value'=>'', 'attr'=>['class'=>'']])
  <button type="submit">Save</button>
</for

![laravel WYSIWYG editor](./assets/images/editor-sample.png)

- for more [visit](https://www.onstory.fun/doc/programming/laravel/package.laraveleditor)


  