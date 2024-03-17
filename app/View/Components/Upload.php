<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Upload extends \App\View\Component
{
  public $id;
  public $name;
  public $class;
  /**
   * alert 类型。
   *
   * @var string
   */
  public $type;

  /**
   * alert 消息。
   *
   * @var string
   */
  public $message;

  /**
   * 创建一个组件实例。
   *
   * @param  string  $type
   * @param  string  $message
   * @return void
   */
  public function __construct($class)
  {
    $this->class = $class;
  }

  /**
   * 获取组件的视图 / 内容
   *
   * @return \Illuminate\View\View|\Closure|string
   */
  public function render()
  {
    return view('components.upload');
  }
}
