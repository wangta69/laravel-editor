<?php

namespace Pondol\Editor\View\Components;

use Illuminate\View\Component;

class EditorComponents extends Component
{
    public $template;

    public $name;

    public $id;

    public $value;

    public $attr;

    public $onsubmit;

    public $type;

    public $options; // [추가] 에디터 설정을 유동적으로 넘기기 위함

    public function __construct(
        $template = null,
        $name = null,
        $id = null,
        $value = null,
        $attr = null,
        $onsubmit = 'false',
        $type = 'single', // 기본값을 single로 설정
        $options = []     // [추가] 업로드 URL, 높이 등 커스텀 설정
    ) {
        $this->template = $template ?? config('pondol-editor.default-template');
        $this->name = $name;
        $this->id = $id ?? 'editor_'.uniqid(); // ID가 없으면 고유 ID 생성
        $this->value = $value;
        $this->attr = $attr;
        $this->onsubmit = $onsubmit;
        $this->type = $type;
        $this->options = $options;
    }

    public function render()
    {
        $editors = [];

        // 세션 기반 에디터 트래킹 로직 정교화
        switch ($this->type) {
            case 'start':
                session(['editor' => [['id' => $this->id]]]);
                $editors = session('editor');
                break;
            case 'single':
                session()->forget('editor');
                $editors = [['id' => $this->id]];
                break;
            case 'end':
                $currentEditors = session('editor', []);
                $currentEditors[] = ['id' => $this->id];
                $editors = $currentEditors;
                session()->forget('editor'); // 렌더링 후 세션 비우기
                break;
            default:
                $currentEditors = session('editor', []);
                $currentEditors[] = ['id' => $this->id];
                session(['editor' => $currentEditors]);
                $editors = $currentEditors;
                break;
        }

        $viewurl = 'editor::'.$this->template.'.component';

        return view($viewurl, [
            'name' => $this->name,
            'id' => $this->id,
            'value' => $this->value,
            'attr' => $this->attr,
            'onsubmit' => $this->onsubmit,
            'editors' => $editors,
            'index' => count($editors),
            'options' => $this->options, // 뷰에 옵션 전달
        ]);
    }
}
