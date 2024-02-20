<?php

return [
  "title" => "Laravel Modular",
  "menu" => [
    ["path" => "", "title" => "总览面板", "icon" => "fas fa-tachometer-alt", "slug" => "",  "children" => [
      ["path" => "", "title" => "Dashboard v1", "icon" => "far fa-circle", "slug" => "",  "children" => []],
      ["path" => "/index2", "title" => "Dashboard v2", "icon" => "far fa-circle", "slug" => "",  "children" => []],
      ["path" => "/index3", "title" => "Dashboard v3", "icon" => "far fa-circle", "slug" => "",  "children" => []],
    ]],
    ["path" => "/market", "title" => "应用管理", "icon" => "fab fa-microsoft", "slug" => "",  "children" => [
      ["path" => "", "title" => "应用市场", "icon" => "far fa-circle", "slug" => "",  "children" => []],
      ["path" => "/installed", "title" => "安装管理", "icon" => "far fa-circle", "slug" => "",  "children" => []],
    ]],
    ["path" => "/user", "title" => "用户管理", "icon" => "fas fa-users", "slug" => "", "children" => []],
    ["path" => "/config", "title" => "系统管理", "icon" => "fab fa-windows", "slug" => "", "children" => [
      ["path" => "", "title" => "配置管理", "icon" => "far fa-circle", "slug" => "",  "children" => []],
      ["path" => "/menu", "title" => "目录管理", "icon" => "far fa-circle", "slug" => "",  "children" => []],
      ["path" => "/data", "title" => "数据管理", "icon" => "far fa-circle", "slug" => "",  "children" => []],
    ]],

    ["path" => "", "title" => "Dashboard", "icon" => "", "slug" => "", "visible" => false, "badge" => ["type" => "", "value" => ""], "children" => []],
  ]
];