<?php
namespace Pondol\Editor\Http\Controllers;

use Illuminate\Http\Request;
use Pondol\Editor\Traits\SmartEditor;

use App\Http\Controllers\Controller;
class TinymceEditorController extends Controller
{

  use SmartEditor;
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
  }

  public function create()
  {
    $data = $this->_create();
    return view('editor::tinymce.sample', $data);
  }

  public function store(Request $request) {
    $pattern = '|(<p data-f-id)(.*)/p>|';
    // preg_match_all($pattern1, $request->editor_content, $matches);
    // print_r($matches);

    $editor_content =  preg_replace($pattern, '', $request->editor_content);    
    echo $editor_content;
  }

  public function upload()
  {
    return view('editor::smart-editor.photo-upload', []);
  }

  public function uploadStore(Request $request) {
    $result =  $this->_uploadStore($request);
    if($result['error']) {
      return redirect($result['error']);
    }
    return $result['url'];
  }

  public function uploadStoreHtml5(Request $request) {
    return $this->_uploadStoreHtml5($request);
  }

}