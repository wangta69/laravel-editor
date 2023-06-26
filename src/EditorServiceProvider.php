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
   // public $routeFilePath = '/routes/bbs/base.php';

	/**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('editor', function($app) {
            return new Editor;
        });
    }

	/**
     * Bootstrap any application services.
     *
     * @return void
     */
    //public function boot(\Illuminate\Routing\Router $router)
	public function boot()
  {
    if (!$this->app->routesAreCached()) {
      require_once __DIR__ . '/Https/routes/web.php';
    }
  }
}
