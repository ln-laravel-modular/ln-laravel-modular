<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends \App\View\Component
{
  public $name;
  public $id;
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

  public $data;
  public $props;
  /**
   * 创建一个组件实例。
   *
   * @param  string  $type
   * @param  string  $message
   * @return void
   */
  public function __construct($type, $message)
  {
    $this->type = $type;
    $this->message = $message;
  }

  /**
   * 获取组件的视图 / 内容
   *
   * @return \Illuminate\View\View|\Closure|string
   */
  public function render()
  {
    return view('components.alert');
  }
}
