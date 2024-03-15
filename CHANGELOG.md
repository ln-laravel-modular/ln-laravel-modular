# CHANGELOG

- [ ] 应用安装模块 Market
- [x] 默认 UI 框架选择: [Bootstrap@4](https://v4.bootcss.com/)
- [x] 默认后台 UI 模板选择: AdminLTE
- [x] 自适应Laravel模块加载器: [nwidart/laravel-modules@8](https://laravelmodules.com/docs/v8/introduction)
<!-- - [ ] 模块加载器: ModuleLoader -->
<!-- - [ ] 模块辅助函数器: module-helpers -->
- [x] Markdown 转 HTML: `Str::markdown()`
- [ ] Market
- [ ] Themes

```sh
composer require nwidart/laravel-modules@8.0.3
```

## 模块分类

- 核心：Core
- 底层：Admin、Market、Temp
- 样式：bootstrap、adminlte
- 应用：todo、note、navigation、toolkit
- 主题：webstack(navigation)
- 拓展：crypto(toolkit)

### 模块继承规则

```sh
ui.layouts.master
   └─ project.layouts.master
      ├─ project.web.index
      └─ project.admin.index
```

## 目录结构

```sh
Modules/
  └─ Blog/
     ├─ Config/
     │  └─ config.php
     ├─ Console/
     ├─ Database/
     │  ├─ factories/
     │  ├─ Migrations/
     │  └─ Seeders/
     │     └─ BlogDatabaseSeeder.php
     ├─ Entities/
     │  ├─ BlogContent.php
     │  ├─ BlogMeta.php
     │  └─ BlogRelationship.php
     ├─ Events/
     ├─ Http/
     │  ├─ Controllers/
     │  │  └─ BlogController.php
     │  ├─ Middleware/
     │  └─ Requests/
     ├─ Providers/
     │  ├─ BlogServiceProvider.php
     │  └─ RouteServiceProvider.php
     ├─ Public/
     ├─ Repositories/
     ├─ Resources/
     │  ├─ assets/
     │  │  ├─ js/
     │  │  └─ sass/
     │  ├─ lang/
     │  └─ views/
     │     ├─ {project}/
     │     ├─ admin/
     │     │  └─ config.blade.php
     │     ├─ /components/
     │     ├─ market/
     │     │  ├─ install.blade.php
     │     │  └─ intro.blade.php
     │     └─ layouts/
     │        └─ index.blade.php
     ├─ Routes/
     │  ├─ api.php
     │  └─ web.php
     ├─ Tests/
     │  ├─ Feature/
     │  └─ Unit/
     ├─ View/
     │  └─ Components/
     ├─ CHANGELOG.md
     ├─ composer.json
     ├─ LICENSE
     ├─ module.json
     ├─ package.json
     ├─ README.md
     └─ webpack.mix.js
```

## config.php

```php
return [
    "name" => "",
    "slug" => "",
    'title' => "",
    "prefix" => null,
    "ui" => null,
    "component" => null,
    "layout" => "bootstrap",
    "theme" => "default",
    'navbar-left' => [],
    'navbar-right' => [],
    'sidebar' => [
        ["path" => "", "title" => '', "icon" => "", "slug" => "", "visible" => true, "badge" => [], "children" => []],
        '/admin' => [],
    ],
    "web" => [
        'prefix' => null,
        'navbar-left' => [],
        'navbar-right' => [],
        'sidebar' => [
        ],
    ],
    'api' => [
        'prefix' => null,
    ],
    'admin' => [
        'prefix' => 'admin',
        'navbar-left' => [],
        'navbar-right' => [],
        'sidebar' => [
            ["path" => "", "title" => '', "icon" => "", "slug" => "", "visible" => true, "active" => false, "badge" => [], "children" => [
                ["path" => "/metas", "title" => "标识管理", "children" => [
                    ["path" => "/insert", "title" => "新增", "visible" => false,],
                    ["path" => "/update/{id?}", "title" => "编辑", "visible" => false,],
                    ["path" => "/select/{id}", "title" => "详情", "visible" => false,],
                ],],
                ["path" => "/contents", "title" => "内容管理",],
                ["path" => "/comments", "title" => "评论管理",],
                ["path" => "/links", "title" => "外链管理",],
                ["path" => "/options", "title" => "应用设置",],
                ["path" => "/themes", "title" => "主题设置", "visible" => false,],
                ["path" => "/extras", "title" => "拓展设置", "visible" => false,],
            ]],

        ],
    ],
    'table' => [
        // table prefix, null ,''
        'prefix' => null,
        // table initalisation data
        'initialization' => [
            'metas' => [],
            'contents' => [],
            'fields' => [],
            'comments' => [],
            'links' => [],
            'relationships' => [],
            'users' => [],
            'options' => [],
        ],
    ],
];
```

## module.json

```json
{
    "name": "ModuleName",
    "alias": "moduleAlias",
    "description": "",
    "keywords": [],
    "priority": 0,
    "providers": [
    ],
    "aliases": {},
    "files": [],
    "requires": []
}
```

## helper

### Module

```php
// 返回当前模块文件夹名
Module::current();
// 返回所有模块的配置信息
Module::allConfig();
// 返回所有 enabled 模块的配置信息
Module::allEnabledConfig();
// 返回所有 disabled 模块的配置信息
Module::allDisabledConfig();
```
