<?php

namespace Pondol\Editor;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Pondol\Editor\Console\Commands\CleanTmpImagesCommand;
use Pondol\Editor\Console\Commands\InstallCommand;
use Pondol\Editor\Services\Editor;
use Pondol\Editor\Services\ImageUploadService;
use Pondol\Editor\View\Components\EditorComponents;

class EditorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/pondol-editor.php',
            'pondol-editor'
        );

        $this->app->singleton('editor', function () {
            return new Editor;
        });

        $this->app->singleton(ImageUploadService::class, function () {
            return new ImageUploadService;
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/pondol-editor.php' => config_path('pondol-editor.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/public/plugins/editor/' => public_path('plugins/editor'),
        ], 'public-plugins');

        $assetGroups = [
            'core', 'suneditor', 'summernote', 'smart-editor',
            'easymde', 'cherry-markdown', 'vditor', 'editorjs',
            'tinymce', 'froala', 'richtext',
        ];
        foreach ($assetGroups as $group) {
            $source = __DIR__.'/public/plugins/editor/'.$group;
            if (is_dir($source)) {
                $this->publishes([
                    $source => public_path('plugins/editor/'.$group),
                ], 'public-plugins-'.$group);
            }
        }

        $this->loadEditorRoutes();
        $this->loadViewsFrom(__DIR__.'/resources/views', 'editor');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                CleanTmpImagesCommand::class,
            ]);
        }

        Blade::component('editor-components', EditorComponents::class);
    }

    private function loadEditorRoutes(): void
    {
        $config = config('pondol-editor');

        Route::prefix($config['prefix'] ?? 'editor')
            ->as($config['as'] ?? 'editor.')
            ->middleware($config['middleware'] ?? ['web', 'auth'])
            ->namespace('Pondol\Editor\Http\Controllers')
            ->group(__DIR__.'/routes/web.php');
    }
}
