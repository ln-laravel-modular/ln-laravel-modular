<?php

namespace App\Http\Controllers;

use App\Support\Helpers\ModuleHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Config;
use App\Support\Module;
use Illuminate\Http\Request;

class Controller extends \Illuminate\Routing\Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests,
        \Illuminate\Foundation\Bus\DispatchesJobs,
        \Illuminate\Foundation\Validation\ValidatesRequests,
        \App\Traits\ActionSelectTrait;

    public function view($view = null, $data = [], $mergeData = [])
    {
        $return = array_merge([
            '$route' => [
                'method' => request()->method(),
                'url' => request()->url(),
                'fullUrl' => request()->fullUrl(),
            ],
            'request' => request()->all(),
            'config' => $config = Module::currentConfig(),
        ], is_array($view) ? $view : [], $data);

        $return['view'] = is_array($view) ? $config['slug'] . '::' . $config['slug'] . '.' . $config['layout'] . '.' . $return['view'] : $view;

        if (env('WEB_CONSOLE')) {
            echo "<script>window.\$data=" . json_encode($return, JSON_UNESCAPED_UNICODE) . ";</script>";
            echo "<script>console.log(`window.\$data`, window.\$data);</script>";
        }
        if (is_array($view) ? !isset($view['view']) : empty($view)) abort(404);

        return view($return['view'], $return, $mergeData);
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


trait ActionDeleteTrait
{
}
trait ActionUpdateTrait
{
}
trait ActionSelectTrait
{
}
trait ActionUpsertTrait
{
}
trait ActionImportTrait
{
}
trait ActionExportTrait
{
}
trait ActionIncreaseTrait
{
}
trait ActionDecreaseTrait
{
}
trait ActionReleaseTrait
{
}
