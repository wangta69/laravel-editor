<?php
namespace Pondol\Editor\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
class TinymceEditorController extends Controller
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
    return view('editor::tinymce.sample');
  }

  public function store(Request $request) {
    print_r($request->all);  
  }
}