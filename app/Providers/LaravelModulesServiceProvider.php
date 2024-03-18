<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Laravel\LaravelFileRepository;

class LaravelModulesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        LaravelFileRepository::macro('current', function () {
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
