<?php

namespace Modules\Admin\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Controller extends \App\Http\Controllers\Controller
{
  use ViewTrait;
}

trait ViewTrait
{
  function view_register(Request $request)
  {
    return view('admin.register', ['module_env' => module_env(), 'module_config' => module_config()]);
  }
  function view_login(Request $request)
  {
    return view('admin.login', ['module_env' => module_env(), 'module_config' => module_config()]);
  }
  function view_forget_password(Request $request)
  {
    return view('admin.forget-password', ['module_env' => module_env(), 'module_config' => module_config()]);
  }
  function view_index(Request $request)
  {
    if (!Auth::check()) {
      return  redirect("/admin/login");
    }
    return view('admin.index', ['module_env' => module_env(), 'module_config' => module_config()]);
  }



  function view_config(Request $request)
  {
    return view('admin.config.index', ['module_env' => module_env(), 'module_config' => module_config()]);
  }
}
