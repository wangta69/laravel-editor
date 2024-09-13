<?php
namespace Pondol\Editor;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;

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

    if ($this->app->runningInConsole()) {
      $this->commands([
        Console\InstallCommand::class,
        // Console\InstallCommand::class,
      ]);
    }
    // $this->app->bind('editor', function($app) {
    $this->app->singleton('editor', function($app) {
      return new Editor;
    });
  }

	/**
   * Bootstrap any application services.
   *
   * @return void
   */
	public function boot()
  {
    // if (!$this->app->routesAreCached()) {
    //   require_once __DIR__ . '/Https/routes/web.php';
    // }
    $this->loadRoutesFrom(__DIR__.'/routes/web.php');

    $this->loadViewsFrom(__DIR__.'/resources/views/editor', 'editor');

    // set assets
		$this->publishes([
      __DIR__.'/public/plugins/editor/' => public_path('plugins/editor'),
    ], 'public');

    Blade::component('editor-components', EditorComponents::class);
    //  <x-editor-comments name="story" :id=1 :attr="default"/>
    // // LOAD THE VIEWS
    //   // - first the published views (in case they have any changes)
    // $this->publishes([
    //   __DIR__.'/resources/views/editor' => resource_path('views/editor'),
    // ]);
  }


}
