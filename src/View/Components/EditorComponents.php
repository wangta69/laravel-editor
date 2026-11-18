<?php

namespace Pondol\Editor\View\Components;

use Illuminate\View\Component;
use Pondol\Editor\Services\Editor;

class EditorComponents extends Component
{
    public string $template;

    public ?string $name;

    public string $id;

    public mixed $value;

    public mixed $attr;

    public string $onsubmit;

    public string $type;

    public array $options;

    public function __construct(
        $template = null,
        $name = null,
        $id = null,
        $value = null,
        $attr = null,
        $onsubmit = 'false',
        $type = 'single',
        $options = []
    ) {
        $this->template = $template ?? config('pondol-editor.default-template', 'suneditor');
        $this->name = $name;
        $this->id = $id ?: 'editor_'.uniqid();
        $this->value = $value;
        $this->attr = $attr;
        $this->onsubmit = (string) $onsubmit;
        $this->type = (string) $type;
        $this->options = is_array($options) ? $options : [];
    }

    public function render()
    {
        /** @var Editor $manager */
        $manager = app('editor');

        switch ($this->type) {
            case 'start':
            case 'single':
                $manager->resetInstances();
                $editors = $manager->registerInstance($this->id, $this->template);
                break;

            case 'end':
            default:
                $editors = $manager->registerInstance($this->id, $this->template);
                break;
        }

        $index = count($editors);

        if ($this->type === 'single' || $this->type === 'end') {
            $manager->resetInstances();
        }

        $viewurl = 'editor::editor.'.$this->template.'.component';
        if (! view()->exists($viewurl)) {
            $viewurl = 'editor::editor.'.$this->template.'.editor';
        }

        return view($viewurl, [
            'template' => $this->template,
            'name' => $this->name,
            'id' => $this->id,
            'value' => $this->value,
            'attr' => $this->attr,
            'onsubmit' => $this->onsubmit,
            'editors' => $editors,
            'index' => $index,
            'options' => $this->options,
        ]);
    }
}
