<?php
namespace Pondol\Editor\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
class FroalaEditorController extends Controller
{


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
    return view('editor::froala.sample');
  }

  public function store(Request $request) {
    $pattern = '|(<p data-f-id)(.*)/p>|';
    // preg_match_all($pattern1, $request->editor_content, $matches);
    // print_r($matches);

    $editor_content =  preg_replace($pattern, '', $request->editor_content);    
    echo $editor_content;
  }

}