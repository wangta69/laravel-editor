<?php

namespace Pondol\Editor\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Pondol\Editor\Events\ImageUploaded;

class ImageUploadService
{
    /**
     * 일반 multipart UploadedFile 저장
     *
     * @return array{success: bool, url?: string, filename?: string, size?: int, error?: string}
     */
    public function storeFile(UploadedFile $file, ?string $subPath = null): array
    {
        $allowedMimes = config('pondol-editor.upload.allowed_mimes', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
        $maxSize = (int) config('pondol-editor.upload.max_size', 5120);

        $validator = Validator::make(['file' => $file], [
            'file' => ['required', 'file', 'image', 'mimes:'.implode(',', $allowedMimes), 'max:'.$maxSize],
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'error' => $validator->errors()->first('file'),
            ];
        }

        $disk = config('pondol-editor.upload.disk', 'public');
        $path = trim($subPath ?? config('pondol-editor.upload.path', 'editor/images'), '/');
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'png');
        $filename = Str::uuid()->toString().'.'.$ext;

        $storedPath = $file->storeAs($path, $filename, $disk);

        $url = Storage::disk($disk)->url($storedPath);

        event(new ImageUploaded(
            disk: $disk,
            path: $storedPath,
            url: $url,
            filename: $filename,
            originalName: $file->getClientOriginalName(),
            size: (int) $file->getSize(),
            userId: auth()->id(),
        ));

        return [
            'success' => true,
            'url' => $url,
            'path' => $storedPath,
            'disk' => $disk,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
        ];
    }

    /**
     * Raw 바이너리 스트림 저장 (SmartEditor HTML5 업로드용)
     *
     * @return array{success: bool, url?: string, filename?: string, error?: string}
     */
    public function storeRaw(string $rawContent, string $originalName, ?string $subPath = null): array
    {
        $decodedName = urldecode($originalName);
        $ext = strtolower(pathinfo($decodedName, PATHINFO_EXTENSION));
        $allowed = config('pondol-editor.upload.allowed_mimes', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);

        if (! in_array($ext, $allowed, true)) {
            return ['success' => false, 'error' => 'not_allowed_extension'];
        }

        $maxBytes = (int) config('pondol-editor.upload.max_size', 5120) * 1024;
        if ($rawContent === '' || strlen($rawContent) > $maxBytes) {
            return ['success' => false, 'error' => 'invalid_size'];
        }

        // 실제 이미지 바이너리인지 검증 (경로 조작 및 위조 파일 차단)
        $imageInfo = @getimagesizefromstring($rawContent);
        if ($imageInfo === false) {
            return ['success' => false, 'error' => 'not_valid_image'];
        }

        $disk = config('pondol-editor.upload.disk', 'public');
        $path = trim($subPath ?? config('pondol-editor.upload.smarteditor_path', 'tmp/editor'), '/');
        $filename = Str::uuid()->toString().'.'.$ext;
        $fullPath = $path.'/'.$filename;

        Storage::disk($disk)->put($fullPath, $rawContent);

        $url = Storage::disk($disk)->url($fullPath);

        event(new ImageUploaded(
            disk: $disk,
            path: $fullPath,
            url: $url,
            filename: $filename,
            originalName: $decodedName,
            size: strlen($rawContent),
            userId: auth()->id(),
        ));

        return [
            'success' => true,
            'url' => $url,
            'path' => $fullPath,
            'disk' => $disk,
            'filename' => $filename,
        ];
    }
}
