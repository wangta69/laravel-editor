<?php
namespace Pondol\Editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Storage;
use Illuminate\Http\UploadedFile;
use DB;
use Illuminate\Support\Facades\Log;
use Pondol\Editor\Traits\SmartEditor;

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

}