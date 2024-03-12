<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module as FacadesModule;
use Nwidart\Modules\Module;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 返回当前模块的文件夹名
        \Nwidart\Modules\Module::macro('current', function () {
            // 查找当前模块
            $backtrace = Arr::first(debug_backtrace(), function ($debug) {
                return strtolower(substr($debug['file'] ?? '', 0, strlen(base_path('modules'))))  == strtolower(base_path('modules'));
            });
            $file_path = $backtrace['file'];
            $file_path = substr($file_path, strlen(base_path('modules')) + 1);
            $module_dir = substr($file_path, 0, strpos($file_path, '\\'));
            return $module_dir;
        });
        // 返回指定关键字的值，如果该关键字属于映射中，则可取上级关键字
        \Nwidart\Modules\Module::macro('currentConfig', function ($key = null, $current = null) {
            $current = empty($current) ? strtolower(\Nwidart\Modules\Module::current()) : $current;
            $config = Config::get($current) ?? require base_path('modules/' . $current . '/config/config.php');
            if (empty($key)) return $config;
            foreach (config("modules.config.key-map") as $config_key => $config_map) {
                if (in_array($key, $config_map)) {
                    return Arr::get($config, $key) ?? Arr::get($config, $config_key);
                }
            }
            return Arr::get($config, $key);
        });
        // 返回所有模块指定关键字的值
        \Nwidart\Modules\Module::macro('allConfig', function ($key = null) {
            // 返回所有模块文件夹名
            $return = \Nwidart\Modules\Facades\Module::all();

            foreach ($return as $name => $module) {
                $return[$name] = \Nwidart\Modules\Module::currentConfig($key, $name);

                if (empty($return[$name])) unset($return[$name]);
            }

            return $return;
        });
        // 返回将多个数组合并为一个数组
        \Nwidart\Modules\Module::macro('allConfigCollapse', function ($key) {
            return Arr::collapse(\Nwidart\Modules\Module::allConfig($key));
        });
        // 更新模块配置
        \Nwidart\Modules\Module::macro('setCurrentConfig', function ($key = null, $value = null, $current = null) {
            $current = empty($current) ? strtolower(\Nwidart\Modules\Module::current()) : $current;
            $return = \Nwidart\Modules\Module::currentConfig(null, $current);
            // 全部更新
            if (empty($key)) {
            }
            // 创建PHP文件并写入数组
            file_put_contents(base_path('modules/' . $current . '/config/config.php'), "<?php\n\nreturn " . var_export($return, true) . ";\n");
            return $return;
        });
        //
        \Nwidart\Modules\Module::macro('view', function () {
            return "I'm a macro";
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
