<?php
namespace Pondol\Editor\Services;

use Illuminate\Filesystem\Filesystem;
use File;


class Editor
{

  public static function extractBase64Image($str, $destination_dir, $destination_public_url) {
    $pattern = '/<img[^>]*src=["\']?([^>"\']+)["\']?[^>]*>/i';
    preg_match_all($pattern, $str, $matches);
    foreach($matches[1] as $k=>$matched) {
      $pos = strpos($matched, 'data:image');
      if($pos !== false) {
        list($type, $data) = explode(';', $matched);
        list($type, $extension) = explode('/', $type);
        list(, $data) = explode(',', $data);

        $file_name = uniqid().'.'.$extension;
        (new Filesystem)->ensureDirectoryExists($destination_dir);
        File::put($destination_dir. '/' . $file_name, base64_decode($data));

        // 현재 문서 변경
        $str = str_replace($matched, $destination_public_url.$file_name, $str);
      }
    }
    return $str;
  }

}