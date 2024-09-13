<?php

namespace Pondol\Editor\Console;

use Illuminate\Console\Command;
// use Illuminate\Filesystem\Filesystem;
// use Illuminate\Support\Str;
// use Symfony\Component\Process\PhpExecutableFinder;
// use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
  // use InstallsBladeStack;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'editor:install';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Install the Laravel Editor controllers and resources';


  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    return $this->installLaravelEditor();
  }


  private function installLaravelEditor()
  {

    \Artisan::call('storage:link');
    \Artisan::call('vendor:publish',  [
      '--force'=> true,
      '--provider' => 'Pondol\Editor\EditorServiceProvider'
    ]);
    $this->info('The laravel editor installed successfully.'); 
  }


}
