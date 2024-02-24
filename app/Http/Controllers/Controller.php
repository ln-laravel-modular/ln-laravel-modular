<?php

namespace App\Http\Controllers;

use App\Support\Helpers\ModuleHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Config;

class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function view($view = null, $data = [], $mergeData = [])
    {
        $module_config = ModuleHelper::current_config();
        return view($view, array_merge(['module_config' => $module_config], $data), $mergeData);
    }
}