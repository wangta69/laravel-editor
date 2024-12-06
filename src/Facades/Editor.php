<?php
namespace Pondol\Editor\Facades;

use Illuminate\Support\Facades\Facade;

class Editor extends Facade
{

  protected static $cached = false;
  protected static function getFacadeAccessor()
  {
    return 'editor';
  }
}
