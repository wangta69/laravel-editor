<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Storage;
use Illuminate\Http\UploadedFile;
use DB;
use Illuminate\Support\Facades\Log;
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
      // $this->middleware('auth');
  }

  // public function main()
  // {
  //   $data = new \stdclass;
  //   $data->ir1 = '<p>서버의 데이타는 이렇게 호출됩니다';
  //   return view('smart-editor.create', ['data' => $data]);
  // }

  // public function store(Request $request)
  // {
  //   // 데이타 처리
  // }


  // public function upload()
  // {
  //   return view('smart-editor.photo-upload', []);
  // }

  // public function uploadStore(Request $request) {
  //   $url = $request->get('callback').'?callback_func='.$request->get('callback_func');

  //   if ($request->hasFile('Filedata')) {
  //     $validator = Validator::make($request->all(), [
  //       'Filedata' => 'image',
  //     ]);

  //     // if it is not image file type
  //     if ($validator->fails()) {
  //       $url .= '&errstr=not_image_file';
  //       return redirect($url);
  //     }

  //     $file = $request->file('Filedata');
  //     $filepath = 'public/tmp/editor/'.session()->getId();

  //     $filename = $file->getClientOriginalName();
  //     $path = Storage::put($filepath, $file);

  //     $url .= '&bNewLine=true';
  //     $url .= '&sFilename='.basename($path);;
  //     $url .= '&sFileURL='.Storage::url($path);
  //   } else {
  //     $url .= '&errstr=not_exist_file';
  //   }
  //   return  $url;
  // }

  // public function uploadStoreHtml5(Request $request) {

  //   // Log::info($request->all());    

  //   $filename = $request->header('file-name');
  //   Log::info($filename); 

  //   $content = $request->getContent();

  //   // Log::info($content);

  //   $url = '';
  //   // $filename = rawurldecode($headers['file_name']);
  //   // $filename_ext = strtolower(array_pop(explode('.',$filename)));
  //   // $allow_file = array("jpg", "png", "bmp", "gif"); 

  //   // if(!in_array($filename_ext, $allow_file)) {
  //   //   $url .= "NOTALLOW_".$filename;
  //   //   return $url;
  //   // } else {
  //     $file = new \stdClass;
  //     $file->name = $filename;
  //     $file->content = $request->getContent();

  //     $filepath = 'public/tmp/editor/'.session()->getId();

  //     Storage::disk('local')->put($filepath."/".$file->name, $file->content);
  //       $url .= '&bNewLine=true';
  //       $url .= '&sFilename='.$file->name;
  //       $url .= '&sFileURL='.Storage::url($filepath).'/'.$file->name;
      
  //     return $url;
  // }
}