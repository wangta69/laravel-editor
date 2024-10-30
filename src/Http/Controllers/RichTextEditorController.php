<?php
namespace Pondol\Editor\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
class RichTextEditorController extends Controller
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
    return view('editor::richtext.sample');
  }

  public function store(Request $request) {
    print_r($request->all());
  }
}