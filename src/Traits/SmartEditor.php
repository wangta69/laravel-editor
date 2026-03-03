<?php

namespace Pondol\Editor\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

trait SmartEditor
{
    public function _uploadStore($request, $path = 'tmp/editor')
    {
        $url = $request->get('callback').'?callback_func='.$request->get('callback_func');

        if ($request->hasFile('Filedata')) {
            $validator = Validator::make($request->all(), [
                'Filedata' => 'image|max:5120', // 최대 5MB 제한 추가
            ]);

            if ($validator->fails()) {
                $url .= '&errstr=not_image_file';

                return ['error' => $url];
            }

            $file = $request->file('Filedata');
            // 파일명 중복 방지를 위해 UUID나 타임스탬프 결합
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
            $fullPath = $file->storeAs('public/'.$path, $filename);

            $url .= '&bNewLine=true';
            $url .= '&sFilename='.$filename;
            $url .= '&sFileURL='.Storage::url($fullPath);
        } else {
            $url .= '&errstr=not_exist_file';
        }

        return ['error' => false, 'url' => $url];
    }

    public function _uploadStoreHtml5($request)
    {
        $url = '';

        $file = new \stdClass;
        $file->name = $request->header('file-name');
        $file->content = $request->getContent();

        // $filename_ext = end(explode('.', $name));
        $filename_ext = pathinfo($file->name, PATHINFO_EXTENSION);
        $allow_file = ['jpg', 'png', 'bmp', 'gif'];
        if (! in_array($filename_ext, $allow_file)) {
            $url .= 'NOTALLOW_'.$file->name;

            return $url;
        } else {
            $filepath = 'public/tmp/editor/'.session()->getId();
            Storage::disk('local')->put($filepath.'/'.$file->name, $file->content);
            $url .= '&bNewLine=true';
            $url .= '&sFilename='.$file->name;
            $url .= '&sFileURL='.Storage::url($filepath).'/'.$file->name;

            return $url;
        }
    }
}
