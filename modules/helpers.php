<?php

/**
 * @name
 */

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;


/**
 * 获取模块的配置参数
 */
if (!function_exists('module')) {
  /**
   * Undocumented function
   *
   * @param [type] $key
   * @param [type] $default
   * @return void
   */
  function module($key, $default = null)
  {
  }
}
if (!function_exists('modules')) {
  /**
   * Undocumented function
   *
   * @param [type] $key
   * @param [type] $default
   * @return void
   */
  function modules($key, $default = null)
  {
  }
}



if (!function_exists('module_path')) {
  /**
   * 获取模块地址
   *
   * @param [type] $path
   * @param boolean $relative
   * @return void
   */
  function module_path($path, $relative = false)
  {
    $return = null;
    // var_dump(pathinfo("*/resouces/views"));
    // var_dump(is_dir(base_path("modules/admin")));
    // var_dump(is_file(base_path("modules/admin")));
    if (stripos($path, "*",) !== false) {
      $return = [];
      $path_exp = explode("*", $path);
      // $return = File::directories("modules/");
      foreach ($path_exp as $index => $item) {
        if ($item == "") {
          $filesystem = new Illuminate\Filesystem\Filesystem;
          $return = $filesystem->directories("modules");
        } else if ($index == sizeof($path_exp) - 1) {
          $return = array_reduce($return, function ($total, $i) use ($relative, $item, $filesystem) {
            if ($filesystem->exists($i . $item)) {
              array_push($total, $relative ? ($i . $item) : base_path($i . $item));
            }
            return $total;
          }, []);
        } else {
        }
      }
    } else {
      $return = $relative ? 'modules\\' . $path : base_path('modules\\' . $path);
    }
    return $return;
  }
}
if (!function_exists('module_env')) {
  function module_env($key = null)
  {
    $backtrace =  debug_backtrace();
    $file_path = $backtrace[0]['file'];
    $file_path = substr($file_path, strlen(base_path('modules')) + 1);
    $module_dir = substr($file_path, 0, strpos($file_path, '\\'));
    $env_path = module_path($module_dir . '/.env');
    if (!File::exists($env_path)) {
      return null;
    } else {
      $env = file_get_contents((string)$env_path);
      if (empty($key)) {
        return parse_ini_string($env, true);
      } else {
        $env = Arr::first(array_filter(explode("\n", $env)), function ($value) use ($key) {
          return in_array(substr(trim($value), 0, strlen($key) + 1), [$key . " ", $key . "="]);
        });
        $env = explode("=", $env);
        return trim($env[1]);
      }
    }
    return $backtrace;
  }
}
if (!function_exists('module_config')) {
  function module_config($key = null, $file = null)
  {
    $backtrace =  debug_backtrace();
    $file_path = $backtrace[0]['file'];
    $file_path = substr($file_path, strlen(base_path('modules')) + 1);
    $module_dir = substr($file_path, 0, strpos($file_path, '\\'));
    if (empty($key)) $key = $module_dir;
    $config_path = module_path($module_dir . '/config/' . $key . '.php');
    if (!File::exists($config_path)) {
      return null;
    } else {
      return require $config_path;
    }
  }
}