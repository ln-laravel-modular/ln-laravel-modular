<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
// use Nwidart\Modules\Facades\Module as FacadesModule;
/**
 * @method static string current()
 * @method static mixed currentConfig($key = null, $current = null, ...$extras)
 * @method static mixed allConfig($key = null)
 * @method static mixed allConfigCollapse($key)
 * @method static mixed setCurrentConfig($key = null, $value = null, $current = null)
 *
 */
class Module extends \Nwidart\Modules\Facades\Module
{
    // use \Illuminate\Support\Traits\Macroable;

    public static function current(): string
    {
        // 查找当前模块
        $backtrace = Arr::first(debug_backtrace(), function ($debug) {
            return strtolower(substr($debug['file'] ?? '', 0, strlen(base_path('modules'))))  == strtolower(base_path('modules'));
        });
        // var_dump($backtrace);
        $file_path = $backtrace['file'];
        $file_path = substr($file_path, strlen(base_path('modules')) + 1);
        // var_dump($file_path);
        $module_dir = substr($file_path, 0, strpos($file_path, DIRECTORY_SEPARATOR));
        // var_dump($module_dir);
        return is_win() ? strtolower($module_dir) : $module_dir;
    }

    // 返回指定关键字的值，如果该关键字属于映射中，则可取上级关键字
    public static function currentConfig($key = null, $current = null, ...$extras)
    {
        $current = empty($current) ? Module::current() : $current;
        // 未检测到模块名称，终止
        if (empty($current)) return;
        $config = Config::get($current) ?? require base_path('modules' . DIRECTORY_SEPARATOR . $current . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'config.php');

        // var_dump([$current, $config]);
        // slug
        if (!isset($config['slug'])) $config['slug'] = strtolower($config['name']);
        if (!isset($config['layout'])) $config['layout'] = 'master';
        // prefix
        if (isset($config['prefix'])) {
            if (empty(Arr::get($config, 'web.prefix'))) Arr::set($config, 'web.prefix', $config['prefix']);
        }


        // 读取当前模块文件并分组
        if (in_array('files', $extras)) {
            $config['generators'] = config('modules.paths.generator');
            $files = app('files')->allFiles('modules' . DIRECTORY_SEPARATOR . Module::currentConfig('name', $current));
            // array_push($config['files'], "\\Modules\\" . $config['name'] . "\\" . $file->getRelativePath() . "\\" . pathinfo($file->getPathName(), PATHINFO_FILENAME));
            foreach (config('modules.paths.generator') ?? [] as $fileKey => $generator) {
                Arr::set($config['generators'], $fileKey . '.path', DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . $config['name'] . DIRECTORY_SEPARATOR . $generator['path']);
                if (in_array($fileKey, ['config', 'routes'])) continue;
                $fileKey = Str::plural($fileKey);
                $config[$fileKey] = [];
                $filePath = $generator['path'];
                foreach ($files as $file) {
                    // var_dump([$file->getRelativePathName(), substr($file->getRelativePathName(), 0, strlen($filePath)), $filePath]);
                    if (substr($file->getRelativePathName(), 0, strlen($filePath)) == $filePath) {
                        array_push($config[$fileKey], DIRECTORY_SEPARATOR . "Modules" . DIRECTORY_SEPARATOR . $config['name'] . DIRECTORY_SEPARATOR . $file->getRelativePath() . DIRECTORY_SEPARATOR . pathinfo($file->getPathName(), PATHINFO_FILENAME));
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
    }
    // 返回所有模块指定关键字的值
    public static function allConfig($key = null)
    {
        // 返回所有模块文件夹名
        $return = Module::all();

        foreach ($return as $name => $module) {
            $return[$name] = Module::currentConfig($key, $name);

            if (empty($return[$name])) unset($return[$name]);
        }

        return $return;
    }
    // 返回将多个数组合并为一个数组
    public static function allConfigCollapse($key)
    {
        return Arr::collapse(Module::allConfig($key));
    }
    // 更新模块配置
    public static function setCurrentConfig($key = null, $value = null, $current = null)
    {
        $current = empty($current) ? Module::current() : $current;
        $return = [];
        // 全部更新
        if (empty($key)) {
            $return = $value;
        } else {
            $return = Module::currentConfig(null, $current);
            Arr::set($return, $key, $value);
        }
        // 清除附加字段
        unset($return['generators']);
        foreach (config('modules.paths.generator') ?? [] as $fileKey => $generator) {
            $fileKey = Str::plural($fileKey);
            unset($return[$fileKey]);
        }
        // 创建PHP文件并写入数组
        file_put_contents(base_path('modules' . DIRECTORY_SEPARATOR . $current . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'config.php'), "<?php\n\nreturn " . var_export($return, true) . ";\n");
        return $return;
    }
    //
    public static function view()
    {
        return "I'm a macro";
    }
}

// var_dump(Module::current());
// var_dump(Module::currentConfig());
// var_dump(Module::allConfigCollapse('admin.sidebar'));
// Module::mixin(new Module);

trait ConfigTrait
{
}
trait PackageTrait
{
}
trait ComposerTrait
{
}