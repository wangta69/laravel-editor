<?php
namespace Pondol\Editor\View\Components;

use Illuminate\View\Component;

class EditorCommnents extends Component
{
  public $name;
  public $id;
  public $attr;
  public function __construct($name=null, $id=null, $attr=null) {

    $this->name = $name;
    $this->id = $id;
    $this->attr = $attr;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {
    return view('editor::smart-editor.component', [
      'name' => $this->name,
      'id' => $this->id,
      'attr' => $attr
    ]);
  }
}
