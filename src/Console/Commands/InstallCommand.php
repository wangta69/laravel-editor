<?php

namespace Pondol\Editor\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class InstallCommand extends Command
{
    protected $signature = 'pondol:install-editor
        {type=full : full | assets | all}
        {--package : Download assets into package src/public/plugins/editor}
        {--only= : Comma-separated editor keys (e.g. suneditor,easymde)}';

    protected $description = 'Install the Laravel Editor resources and optionally download open-source local assets';

    public function handle(): int
    {
        $type = $this->argument('type');

        return match ($type) {
            'full' => $this->installLaravelEditor(),
            'assets' => $this->downloadLocalAssets(),
            'all' => ($this->downloadLocalAssets() === self::SUCCESS && $this->installLaravelEditor() === self::SUCCESS)
                ? self::SUCCESS
                : self::FAILURE,
            default => self::SUCCESS,
        };
    }

    private function getOnlyFilter(): array
    {
        $only = (string) $this->option('only');
        if (trim($only) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $only))));
    }

    private function installLaravelEditor(): int
    {
        $this->call('storage:link');

        $only = $this->getOnlyFilter();
        if (empty($only)) {
            $this->call('vendor:publish', [
                '--force' => true,
                '--provider' => 'Pondol\Editor\EditorServiceProvider',
            ]);
        } else {
            $tags = array_merge(['config', 'public-plugins-core'], array_map(fn ($k) => 'public-plugins-'.$k, $only));
            foreach ($tags as $tag) {
                $this->call('vendor:publish', [
                    '--force' => true,
                    '--provider' => 'Pondol\Editor\EditorServiceProvider',
                    '--tag' => $tag,
                ]);
            }
        }

        $this->info("The pondol's laravel editor installed successfully.");

        return self::SUCCESS;
    }

    private function downloadLocalAssets(): int
    {
        $baseDir = $this->option('package')
            ? dirname(__DIR__, 2).'/public/plugins/editor'
            : public_path('plugins/editor');

        $manifest = $this->assetManifest();
        $only = $this->getOnlyFilter();
        if (! empty($only)) {
            $manifest = array_intersect_key($manifest, array_flip($only));
        }

        $this->info("Downloading local editor assets into: {$baseDir}");

        foreach ($manifest as $editor => $files) {
            $this->line(" - [{$editor}]");
            foreach ($files as $relativePath => $url) {
                $target = $baseDir.'/'.$editor.'/'.$relativePath;
                File::ensureDirectoryExists(dirname($target));

                try {
                    $response = Http::timeout(30)->get($url);
                    if ($response->successful()) {
                        File::put($target, $response->body());
                        $this->line("   OK: {$relativePath}");
                    } else {
                        $this->warn("   FAIL ({$response->status()}): {$url}");
                    }
                } catch (\Throwable $e) {
                    $this->warn("   ERROR: {$url} ({$e->getMessage()})");
                }
            }
        }

        $this->info('Local editor assets download completed.');

        return self::SUCCESS;
    }

    private function assetManifest(): array
    {
        return [
            'core' => [
                'jquery.min.js' => 'https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js',
            ],
            'suneditor' => [
                'suneditor.min.js' => 'https://cdn.jsdelivr.net/npm/suneditor@latest/dist/suneditor.min.js',
                'suneditor.min.css' => 'https://cdn.jsdelivr.net/npm/suneditor@latest/dist/suneditor.min.css',
                'langs/ko.js' => 'https://cdn.jsdelivr.net/npm/suneditor@latest/src/langs/ko.js',
            ],
            'summernote' => [
                'summernote-lite.min.js' => 'https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/summernote-lite.min.js',
                'summernote-lite.min.css' => 'https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/summernote-lite.min.css',
                'lang/summernote-ko-KR.min.js' => 'https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/lang/summernote-ko-KR.min.js',
                'font/summernote.woff2' => 'https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/font/summernote.woff2',
                'font/summernote.woff' => 'https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/font/summernote.woff',
                'font/summernote.ttf' => 'https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/font/summernote.ttf',
            ],
            'easymde' => [
                'easymde.min.js' => 'https://cdn.jsdelivr.net/npm/easymde@2.21.0/dist/easymde.min.js',
                'easymde.min.css' => 'https://cdn.jsdelivr.net/npm/easymde@2.21.0/dist/easymde.min.css',
            ],
            'cherry-markdown' => [
                'cherry-markdown.js' => 'https://cdn.jsdelivr.net/npm/cherry-markdown@0.11.9/dist/cherry-markdown.js',
                'cherry-markdown.css' => 'https://cdn.jsdelivr.net/npm/cherry-markdown@0.11.9/dist/cherry-markdown.css',
                'fonts/ch-icon.woff2' => 'https://cdn.jsdelivr.net/npm/cherry-markdown@0.11.9/dist/fonts/ch-icon.woff2',
                'fonts/ch-icon.woff' => 'https://cdn.jsdelivr.net/npm/cherry-markdown@0.11.9/dist/fonts/ch-icon.woff',
                'fonts/ch-icon.ttf' => 'https://cdn.jsdelivr.net/npm/cherry-markdown@0.11.9/dist/fonts/ch-icon.ttf',
                'fonts/ch-icon.eot' => 'https://cdn.jsdelivr.net/npm/cherry-markdown@0.11.9/dist/fonts/ch-icon.eot',
                'fonts/ch-icon.svg' => 'https://cdn.jsdelivr.net/npm/cherry-markdown@0.11.9/dist/fonts/ch-icon.svg',
            ],
            'vditor' => [
                'dist/index.min.js' => 'https://cdn.jsdelivr.net/npm/vditor@latest/dist/index.min.js',
                'dist/index.css' => 'https://cdn.jsdelivr.net/npm/vditor@latest/dist/index.css',
                'dist/js/i18n/ko_KR.js' => 'https://cdn.jsdelivr.net/npm/vditor@latest/dist/js/i18n/ko_KR.js',
                'dist/js/i18n/en_US.js' => 'https://cdn.jsdelivr.net/npm/vditor@latest/dist/js/i18n/en_US.js',
                'dist/js/icons/ant.js' => 'https://cdn.jsdelivr.net/npm/vditor@latest/dist/js/icons/ant.js',
                'dist/js/lute/lute.min.js' => 'https://cdn.jsdelivr.net/npm/vditor@latest/dist/js/lute/lute.min.js',
                'dist/css/content-theme/light.css' => 'https://cdn.jsdelivr.net/npm/vditor@latest/dist/css/content-theme/light.css',
                'dist/css/content-theme/dark.css' => 'https://cdn.jsdelivr.net/npm/vditor@latest/dist/css/content-theme/dark.css',
            ],
            'editorjs' => [
                'editorjs.umd.min.js' => 'https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest/dist/editorjs.umd.min.js',
                'header.umd.js' => 'https://cdn.jsdelivr.net/npm/@editorjs/header@latest',
                'list.umd.js' => 'https://cdn.jsdelivr.net/npm/@editorjs/list@latest',
                'image.umd.js' => 'https://cdn.jsdelivr.net/npm/@editorjs/image@latest',
                'quote.umd.js' => 'https://cdn.jsdelivr.net/npm/@editorjs/quote@latest',
                'code.umd.js' => 'https://cdn.jsdelivr.net/npm/@editorjs/code@latest',
                'table.umd.js' => 'https://cdn.jsdelivr.net/npm/@editorjs/table@latest',
            ],
        ];
    }
}
