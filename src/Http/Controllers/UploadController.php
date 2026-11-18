<?php

namespace Pondol\Editor\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Pondol\Editor\Services\ImageUploadService;

class UploadController extends Controller
{
    public function store(Request $request, ImageUploadService $uploader): JsonResponse
    {
        $driver = $request->input('driver', 'default');

        // SunEditor(file-0), EditorJS(image), Vditor(file[]), 일반(file) 모두 수용
        $file = $request->file('file')
            ?? $request->file('image')
            ?? $request->file('file-0')
            ?? (is_array($request->file('file')) ? ($request->file('file')[0] ?? null) : null);

        if (! $file) {
            return $this->errorResponse($driver, '업로드할 파일이 없습니다.');
        }

        $result = $uploader->storeFile($file);

        if (! $result['success']) {
            return $this->errorResponse($driver, $result['error'] ?? '업로드 실패');
        }

        return match ($driver) {
            // SunEditor 포맷: { result: [{ url, name, size }] }
            'suneditor' => response()->json([
                'result' => [
                    [
                        'url' => $result['url'],
                        'name' => $result['filename'],
                        'size' => $result['size'],
                    ],
                ],
            ]),

            // Editor.js @editorjs/image 포맷: { success: 1, file: { url } }
            'editorjs' => response()->json([
                'success' => 1,
                'file' => [
                    'url' => $result['url'],
                ],
            ]),

            // Vditor 포맷: { msg, code: 0, data: { errFiles: [], succMap: { name: url } } }
            'vditor' => response()->json([
                'msg' => '',
                'code' => 0,
                'data' => [
                    'errFiles' => [],
                    'succMap' => [
                        ($result['original_name'] ?? $result['filename']) => $result['url'],
                    ],
                ],
            ]),

            // TinyMCE 포맷: { location: url }
            'tinymce' => response()->json([
                'location' => $result['url'],
            ]),

            // 기본 (Summernote, EasyMDE, Cherry Markdown 등)
            default => response()->json([
                'success' => true,
                'url' => $result['url'],
                'filename' => $result['filename'],
            ]),
        };
    }

    private function errorResponse(string $driver, string $message): JsonResponse
    {
        return match ($driver) {
            'suneditor' => response()->json(['errorMessage' => $message], 422),
            'editorjs' => response()->json(['success' => 0, 'message' => $message], 422),
            'vditor' => response()->json(['msg' => $message, 'code' => 1], 422),
            default => response()->json(['success' => false, 'error' => $message], 422),
        };
    }
}
