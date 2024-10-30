<?php
namespace Pondol\Editor\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'pondol:install-editor {type=full}'; // full, simple, skip

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Install the Laravel Editor controllers and resources';

  // protected $composer, $type;


  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {

    $type = $this->argument('type');

    switch($type) {
      case 'full':
        return $this->installLaravelEditor();
      default:
        return;
    }

  }


  private function installLaravelEditor()
  {

    \Artisan::call('storage:link');
    \Artisan::call('vendor:publish',  [
      '--force'=> true,
      '--provider' => 'Pondol\Editor\EditorServiceProvider'
    ]);
    $this->info("The pondol's laravel editor installed successfully."); 
  }

}
