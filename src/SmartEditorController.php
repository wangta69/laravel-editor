<?php
namespace Pondol\Editor;

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
      // $this->middleware('auth');
  }

  public function main()
  {
    $data = $this->_main();
    return view('editor::smart-editor.sample', $data);
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