<?php
namespace Pondol\Editor;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

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

    // // LOAD THE VIEWS
    //   // - first the published views (in case they have any changes)
    // $this->publishes([
    //   __DIR__.'/resources/views/editor' => resource_path('views/editor'),
    // ]);
  }


}
