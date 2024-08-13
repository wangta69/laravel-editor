<?php
namespace Pondol\Editor\View\Components;

use Illuminate\View\Component;

class EditorComponents extends Component
{
  private $name;
  private $id;
  private $value;
  private $attr;
  private $onsubmit;
  private $multi;
  private $start;
  private $end;

  private $editor = [];
  /**
   * @param $onsubmit  false: 수동으로 처리, true : submit시 자동으로 처리
   */
  public function __construct(
    $name=null, 
    $id=null, 
    $value=null,
    $attr=null, 
    $onsubmit='false', 
    $multi="false", 
    $type=null
    ) {

    $this->name = $name;
    $this->id = $id;
    $this->value = $value;
    $this->attr = $attr;
    $this->onsubmit = $onsubmit;
    $this->type = $type;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {
    
    $editors = [];
    // if($this->multi == 'true') { // multi 일경우 sesion에 현재 값들을 저장해 둔다.
    
    switch($this->type) {
      case 'start': // 기존 세션을 제거하고 
        session()->forget('editor');
        session(['editor' => [['id'=>$this->id]]]);
        break;
      case 'single': // single mode 인경우는 하나만 추가 하고 끝냄
        session()->forget('editor');
        $editors = [['id'=>$this->id]];
      case 'end': // 기존 sessiondmf $editors 변수에 저장
        $editor = session('editor');
        $editor[] = ['id'=>$this->id];
        $editors = $editor;
        break;
      default: // 세션 계속 추가
        $editor = session('editor');
        $editor[] = ['id'=>$this->id];
        session(['editor' =>  $editor]);
        break;
    }
    
    return view('editor::smart-editor.component', [
      'name' => $this->name,
      'id' => $this->id,
      'value' => $this->value,
      'attr' => $this->attr,
      'onsubmit' => $this->onsubmit,
      'editors' => $editors,
      'index' => $editors ? count($editors): 0
    ]);
  }
}
