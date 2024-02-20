<?php

namespace Modules\Admin\App\Http\Controllers;

use Illuminate\Http\Request;

class Controller extends \App\Http\Controllers\Controller
{
  use ViewTrait;
}

trait ViewTrait
{
  function view_index(Request $request)
  {
    return view('admin.index', ['module_env' => module_env(), 'module_config' => module_config()]);
  }

  function view_config(Request $request)
  {
    return view('admin.config.index', ['module_env' => module_env(), 'module_config' => module_config()]);
  }
}