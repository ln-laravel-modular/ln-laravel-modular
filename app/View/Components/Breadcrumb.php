<?php

namespace App\View\Components;

class Breadcrumb extends \App\View\Component
{
  public $name;
  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($name = 'breadcrumb', $id = '', $class = '', $itemClass = '', $data = [], $props = [])
  {
    $this->name = $name;
    $this->id = $id;
    $this->class = $class;
    $this->itemClass = $itemClass;
    $this->data = $data;
    $this->props = $props;
  }
}
