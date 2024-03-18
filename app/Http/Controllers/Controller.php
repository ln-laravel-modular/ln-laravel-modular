<?php

namespace App\Http\Controllers;

use App\Support\Helpers\ModuleHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Config;
use App\Support\Module;

class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function view($view = null, $data = [], $mergeData = [])
    {
        $return = array_merge([
            'request' => request()->all(),
            'config' => Module::currentConfig()
        ], $data);
        if (env('WEB_CONSOLE')) {
            echo "<script>window.\$data=" . json_encode($return, JSON_UNESCAPED_UNICODE) . ";</script>";
            echo "<script>console.log(`\$data`, window.\$data);</script>";
        }
        return view($view, $return, $mergeData);
    }

    public function from_admin()
    {
        return preg_match('/^admin*/', request()->route()->getPrefix());
    }

    function response_json($status, $message = null,  $data = null)
    {
        return response()->json([
            "status" => $status,
            "message" => $message,
            "data" => $data,
        ]);
    }
    function response_success($data = [], $message = null, $status = 200)
    {
        return $this->response_json($status, $message, $data);
    }
    function response_error($data = [], $message = null, $status = 400)
    {
        return $this->response_json($status, $message, $data);
    }
    function response_debug()
    {
    }
    /**
     * 检测API路由
     */
    public function isApiRoute()
    {
        $header = request()->header();
        // dump(request()->server('PHP_SELF'));
        // dump(request()->is("cli/*"));
        // dump(Route::currnet());
        if (request()->server('PHP_SELF') === 'artisan') return false;
        // $isFromOpenAPI=substr($header['refer']);
        return \Route::current()->computedMiddleware[0] === 'api';
    }
    public function getOriginalData($value)
    {
        $return = [];
        if ($value instanceof \Illuminate\Http\JsonResponse) {
            $return = ($value->getData(true))['data'];
        }
        return $return;
    }
}
