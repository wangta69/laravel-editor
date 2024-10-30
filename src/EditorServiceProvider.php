<?php
namespace Pondol\Editor;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

use Pondol\Editor\Console\Commands\InstallCommand;
use Pondol\Editor\View\Components\EditorComponents;


class EditorServiceProvider extends ServiceProvider {


  /**
   * Where the route file lives, both inside the package and in the app (if overwritten).
   *
   * @var string
   */

	/**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    // $this->app->singleton('editor', function($app) {
    //   return new Editor;
    // });
  }

	/**
   * Bootstrap any application services.
   *
   * @return void
   */
	public function boot()
  {


    $this->publishes([
      __DIR__ . '/config/pondol-editor.php' => config_path('pondol-editor.php'),
    ], 'config');
    $this->mergeConfigFrom(
      __DIR__ . '/config/pondol-editor.php',
      'pondol-editor'
    );

    $this->loadEditorRoutes();

    $this->loadViewsFrom(__DIR__.'/resources/views/editor', 'editor');

    // set assets
		$this->publishes([
      __DIR__.'/public/plugins/editor/' => public_path('plugins/editor'),
    ], 'public-plugins');

    $this->commands([
      InstallCommand::class,
    ]);

    Blade::component('editor-components', EditorComponents::class);
  }

  private function loadEditorRoutes()
  {
    $config = config('pondol-editor');

    Route::prefix($config['prefix'])
      ->as($config['as'])
      ->middleware($config['middleware'])
      ->namespace('Pondol\Editor\Http\Controllers')
      ->group(__DIR__ . '/routes/web.php');
  }


}
