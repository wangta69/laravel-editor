<?php
namespace Pondol\Editor\Http\Controllers;

use Illuminate\Http\Request;
use Pondol\Editor\Traits\SmartEditor;

use App\Http\Controllers\Controller;
class SmartEditorController extends Controller
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
    return view('editor::smart-editor.sample', $data);
  }

  public function store(Request $request) {
    print_r($request->all());
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