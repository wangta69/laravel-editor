<?php

namespace Pondol\Editor\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanTmpImagesCommand extends Command
{
    protected $signature = 'pondol:clean-editor-tmp {--days=1 : Delete temporary editor files older than N days}';

    protected $description = 'Clean expired temporary images uploaded by the editor';

    public function handle(): int
    {
        $days = max(0, (int) $this->option('days'));
        $diskName = config('pondol-editor.upload.disk', 'public');
        $tmpPath = trim(config('pondol-editor.upload.smarteditor_path', 'tmp/editor'), '/');

        $disk = Storage::disk($diskName);
        if (! $disk->exists($tmpPath)) {
            $this->info('No temporary editor directory found.');

            return self::SUCCESS;
        }

        $cutoff = now()->subDays($days)->getTimestamp();
        $deleted = 0;

        foreach ($disk->allFiles($tmpPath) as $file) {
            if ($disk->lastModified($file) < $cutoff) {
                $disk->delete($file);
                $deleted++;
            }
        }

        // 빈 하위 디렉터리 정리
        foreach (array_reverse($disk->allDirectories($tmpPath)) as $dir) {
            if (empty($disk->allFiles($dir)) && empty($disk->allDirectories($dir))) {
                $disk->deleteDirectory($dir);
            }
        }

        $this->info("Cleaned {$deleted} temporary editor file(s) older than {$days} day(s).");

        return self::SUCCESS;
    }
}
