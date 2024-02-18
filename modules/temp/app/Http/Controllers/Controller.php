<?php

namespace Modules\Temp\App\Http\Controllers;

use Illuminate\Http\Request;

class Controller extends \App\Http\Controllers\Controller
{
  use ViewTrait;
}

trait ViewTrait
{
  function view_index(Request $request)
  {
    return view('temp.welcome');
  }
}