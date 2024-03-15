<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
            return strtolower($module_dir);
        });
        // 返回指定关键字的值，如果该关键字属于映射中，则可取上级关键字
        \Nwidart\Modules\Module::macro('currentConfig', function ($key = null, $current = null, ...$extras) {
            $current = empty($current) ? strtolower(\Nwidart\Modules\Module::current()) : $current;
            $config = Config::get($current) ?? require base_path('modules/' . $current . '/config/config.php');

            // slug
            if (!isset($config['slug'])) $config['slug'] = strtolower($config['name']);
            // prefix
            if (isset($config['prefix'])) {
                if (!isset($config['web']['prefix'])) $config['web']['prefix'] = $config['prefix'];
            }


            // 读取当前模块文件并分组
            if (in_array('files', $extras)) {
                $config['generators'] = config('modules.paths.generator');
                $files = app('files')->allFiles('modules\\' . Module::currentConfig('name', $current));
                // array_push($config['files'], "\\Modules\\" . $config['name'] . "\\" . $file->getRelativePath() . "\\" . pathinfo($file->getPathName(), PATHINFO_FILENAME));
                foreach (config('modules.paths.generator') ?? [] as $fileKey => $generator) {
                    Arr::set($config['generators'], $fileKey . '.path', '\\Modules\\' . $config['name'] . '\\' . $generator['path']);
                    if (in_array($fileKey, ['config', 'routes'])) continue;
                    $fileKey = Str::plural($fileKey);
                    $config[$fileKey] = [];
                    $filePath = $generator['path'];
                    foreach ($files as $file) {
                        // var_dump([$file->getRelativePathName(), substr($file->getRelativePathName(), 0, strlen($filePath)), $filePath]);
                        if (substr($file->getRelativePathName(), 0, strlen($filePath)) == $filePath) {
                            array_push($config[$fileKey], "\\Modules\\" . $config['name'] . "\\" . $file->getRelativePath() . "\\" . pathinfo($file->getPathName(), PATHINFO_FILENAME));
                        }
                    }
                }
            }
            // 读取当前模块可适配主题
            if (in_array('themes', $extras)) {
            }

            if (empty($key)) return $config;
            foreach (config("modules.config.key-map") as $config_key => $config_map) {
                if (in_array($key, $config_map)) {
                    // Arr::set($config, $key);
                    return Arr::get($config, $key) ?? Arr::get($config, $config_key);
                }
            }
            // var_dump($config, $key);
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
            $return = [];
            // 全部更新
            if (empty($key)) {
                $return = $value;
            } else {
                $return = \Nwidart\Modules\Module::currentConfig(null, $current);
                Arr::set($return, $key, $value);
            }
            // 清除附加字段
            unset($return['generators']);
            foreach (config('modules.paths.generator') ?? [] as $fileKey => $generator) {
                $fileKey = Str::plural($fileKey);
                unset($return[$fileKey]);
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