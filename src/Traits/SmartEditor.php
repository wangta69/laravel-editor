<?php

namespace Pondol\Editor\Traits;

use Illuminate\Http\Request;
use Pondol\Editor\Services\ImageUploadService;

trait SmartEditor
{
    public function _uploadStore(Request $request, $path = 'tmp/editor')
    {
        $url = $request->get('callback').'?callback_func='.$request->get('callback_func');

        if (! $request->hasFile('Filedata')) {
            return [
                'error' => false,
                'url' => $url.'&errstr=not_exist_file',
            ];
        }

        /** @var ImageUploadService $uploader */
        $uploader = app(ImageUploadService::class);
        $result = $uploader->storeFile($request->file('Filedata'), $path);

        if (! $result['success']) {
            return ['error' => $url.'&errstr=not_image_file'];
        }

        $url .= '&bNewLine=true';
        $url .= '&sFilename='.$result['filename'];
        $url .= '&sFileURL='.$result['url'];

        return ['error' => false, 'url' => $url];
    }

    public function _uploadStoreHtml5(Request $request)
    {
        $rawName = (string) $request->header('file-name', '');

        /** @var ImageUploadService $uploader */
        $uploader = app(ImageUploadService::class);
        $subPath = trim(config('pondol-editor.upload.smarteditor_path', 'tmp/editor'), '/').'/'.session()->getId();

        $result = $uploader->storeRaw($request->getContent(), $rawName, $subPath);

        if (! $result['success']) {
            return 'NOTALLOW_'.urldecode($rawName);
        }

        return '&bNewLine=true&sFilename='.$result['filename'].'&sFileURL='.$result['url'];
    }
}
