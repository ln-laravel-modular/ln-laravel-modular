<?php

namespace App\Http\Controllers;

use App\Support\Helpers\ModuleHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Config;
use Nwidart\Modules\Laravel\Module;

class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function view($view = null, $data = [], $mergeData = [])
    {
        return view($view, array_merge([
            'config' => Module::currentConfig()
        ], $data), $mergeData);
    }

    public function from_admin()
    {
        return preg_match('/^admin*/', request()->route()->getPrefix());
    }
}
