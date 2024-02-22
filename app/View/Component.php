<?php

namespace App\View;

use Facade\FlareClient\View;

class Component extends \Illuminate\View\Component
{
  public $name;
  public $id;
  public $class;
  public $itemClass;
  public $data;
  public $props;
  /**
   * 创建一个组件实例。
   *
   * @param  string  $type
   * @param  string  $message
   * @return void
   */
  public function __construct()
  {
  }

  /**
   * 获取组件的视图 / 内容
   *
   * @return \Illuminate\View\View|\Closure|string
   */
  public function render()
  {
    if (empty($name)) return;
    if (!View::exists('components.' . $name)) return;
    return view('components.' . $name);
  }
}
