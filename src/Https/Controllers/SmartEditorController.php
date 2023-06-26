<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Pondol\Editor\SmartEditor;

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

}