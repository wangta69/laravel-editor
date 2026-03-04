# 🚀 Laravel WYSIWYG Editor Hub

> **현재 배포 중인 주요 웹 에디터들을 라라벨 프로젝트에서 즉시 사용할 수 있도록 통합한 플러그인입니다.**  
> 하나의 패키지로 TinyMCE, Summernote, Naver Smart Editor 등 다양한 에디터를 설정 하나로 자유롭게 교체하세요.

---

## 🌟 Real-world Usage

이 라이브러리는 **[길라(Gilra)](https://gilra.kr)** (Online Fortune Service)의 커뮤니티 Q&A, 1:1 상담소, 공지사항 기능을 구축하는 데 실제로 사용되었습니다. 특히 **사주 명반 이미지 자동 첨부 및 전문가 답변 시스템**을 구현하며 안정성과 유연성이 검증되었습니다.

---

## ✨ Key Features

- **Multi-Editor Support**: 5종 이상의 유명 에디터 완벽 지원 (Summernote 추가)
- **Blade Component Ready**: `<x-editor-components />` 태그로 간편하게 삽입
- **Smart Instance Management**: 한 페이지 내 여러 개의 에디터 인스턴스를 세션 기반으로 자동 관리
- **Defer-loading Compatible**: jQuery `defer` 환경에서도 로딩 순서 꼬임 없이 안정적으로 작동
- **Image Upload System**: 커스텀 저장 경로 설정을 지원하는 전용 Trait 제공 (`SmartEditor`)
- **Cosmic Dark Theme**: 다크 모드 UI 환경에 최적화된 CSS 커스텀 지원

---

## 📚 Documentation

상세한 사용법과 커스텀 가이드는 [공식 사이트](https://www.onstory.fun/doc/programming/laravel/package.laraveleditor)에서 확인하실 수 있습니다.

---

## 🛠 Installation

### 1. Composer를 통해 설치

```bash
composer require wangta69/laravel-editor
```

### 2. 에디터 리소스 및 설정파일 발행

```bash
php artisan pondol:install-editor
```

_이 명령어를 실행하면 `config/pondol-editor.php` 파일과 `public/plugins/editor` 폴더에 필요한 정적 파일들이 생성됩니다._

---

## ⚙️ Configuration

`config/pondol-editor.php` 파일에서 기본으로 사용할 에디터 템플릿과 라우팅 옵션을 수정할 수 있습니다.

### Supported Templates

| Key            | Editor Name            | License     | 특징                                                 |
| :------------- | :--------------------- | :---------- | :--------------------------------------------------- |
| `summernote`   | **Summernote**         | MIT         | **추천.** 가볍고 라이선스 문구 없는 완전 무료 에디터 |
| `tinymce`      | **TinyMCE**            | Community   | 가장 강력한 기능과 전 세계적인 안정성                |
| `smart-editor` | **Naver Smart Editor** | Open Source | 국내 사용자에게 친숙한 네이버 스타일                 |
| `richtext`     | **RichText Editor**    | Commercial  | 매우 가벼운 고성능 에디터                            |
| `froala`       | **Froala Editor**      | Commercial  | 세련된 디자인과 모바일 최적화 에디터                 |

---

## 📖 Usage Examples

### 1. 기본 사용법 (Before & After)

설정파일에 지정된 기본 에디터를 불러옵니다.

**Before:**

```html
<form>
  <textarea name="comment"></textarea>
  <button type="submit">Save</button>
</form>
```

**After (Component 방식):**

```html
<form>
  <x-editor-components name="comment" id="comment-id" value="초기 내용" />
  <button type="submit">Save</button>
</form>
```

**After (Blade Include 방식):**

```html
<form>
  @include ('editor::default', ['name'=>'comment', 'id'=>'comment-id',
  'value'=>'', 'attr'=>['class'=>'']])
  <button type="submit">Save</button>
</form>
```

### 2. 특정 템플릿 강제 지정

기본 설정과 상관없이 페이지마다 다른 에디터를 사용하고 싶을 때 유용합니다.

**TinyMCE 적용 시:**

```html
@include('editor::tinymce.editor', [ 'name' => 'comment', 'id' => 'comment-id',
'value' => $data->content, 'attr' => ['class' => 'form-control'] ])
```

---

## 🚀 Advanced Usage

### 1. 한 페이지에 여러 에디터 사용 (Multi-instance)

`type` 속성을 통해 세션 기반의 인스턴스 관리가 가능합니다.

```html
<!-- 첫 번째 에디터 (시작: 기존 세션 초기화) -->
<x-editor-components name="content1" id="editor1" type="start" />

<!-- 중간 에디터들 -->
<x-editor-components name="content2" id="editor2" />

<!-- 마지막 에디터 (종료: 세션 정리 및 전체 로드) -->
<x-editor-components name="content3" id="editor3" type="end" />
```

### 2. 스크립트 Defer 로딩 대응

본 라이브러리는 jQuery가 `defer`로 로드되는 최신 웹 환경에서도 안정적으로 작동합니다. 모든 초기화 코드는 `window.load` 시점에 실행되어 라이브러리 부재로 인한 `Uncaught ReferenceError`를 방지합니다.

---

## 📸 Image Upload (SmartEditor Trait)

이미지 업로드 경로를 컨트롤러에서 유동적으로 지정할 수 있습니다.

```php
use Pondol\Editor\Traits\SmartEditor;

class PostController extends Controller
{
    use SmartEditor;

    public function uploadImage(Request $request)
    {
        // _uploadStore 메서드가 유효성 검사 및 저장을 자동으로 처리합니다.
        // 두 번째 인자로 storage/app/public 하위의 저장 경로를 지정할 수 있습니다.
        $result = $this->_uploadStore($request, 'uploads/posts');

        if ($result['error']) {
            return response()->json(['success' => false, 'message' => 'Upload failed']);
        }
        return $result['url'];
    }
}
```

---

## 🎨 Styling for Dark Mode

길라(Gilra) 서비스와 같은 다크 테마 환경을 위해 에디터의 배경 및 툴바 색상을 오버라이딩할 수 있습니다.

```css
/* Summernote Dark Mode 예시 */
.note-editor {
  background-color: #1b263b !important;
}
.note-toolbar {
  background-color: #2a3b57 !important;
}
.note-editable {
  color: #fff !important;
}
```

---

## 🧪 Tests & Verification

설치 후 아래 주소로 접속하여 각 에디터가 정상적으로 렌더링되는지 즉시 테스트할 수 있습니다.

- **기본 테스트**: `https://your-domain.com/editor/richtext`
- **스마트 에디터**: `https://your-domain.com/editor/smart-editor`
- **TinyMCE**: `https://your-domain.com/editor/tinymce`
- **Summernote**: `https://your-domain.com/editor/summernote`

---

## 🖼 Screenshots

![laravel WYSIWYG editor sample](./assets/images/editor-sample.png)
_다양한 환경에서 일관되고 세련된 편집 경험을 제공합니다._

---

## 🤝 Contribution & Support

- **Issues**: 버그나 개선 사항은 [GitHub Issues](https://github.com/wangta69/laravel-editor/issues)에 남겨주세요.
- **Support**: [onstory.fun](https://www.onstory.fun)
- **Real-world Case**: [길라(Gilra) 운세 서비스](https://gilra.kr)

---

Copyright © wangta69. Licensed under the MIT License.
