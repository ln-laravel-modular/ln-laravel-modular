<?php

use Nwidart\Modules\Activators\FileActivator;
use Nwidart\Modules\Commands;

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */

    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */

    'stubs' => [
        'enabled' => false,
        'path' => base_path('vendor/nwidart/laravel-modules/src/Commands/stubs'),
        'files' => [
            // 'readme' => 'README.md',
            'routes/web' => 'Routes/web.php',
            'routes/api' => 'Routes/api.php',
            'views/index' => 'Resources/views/index.blade.php',
            // 'views/install' => 'Resources/views/install.blade.php',
            // 'views/intro' => 'Resources/views/intro.blade.php',
            'views/master' => 'Resources/views/layouts/master.blade.php',
            'scaffold/config' => 'Config/config.php',
            'composer' => 'composer.json',
            'assets/js/app' => 'Resources/assets/js/app.js',
            'assets/sass/app' => 'Resources/assets/sass/app.scss',
            'webpack' => 'webpack.mix.js',
            'package' => 'package.json',
        ],
        'replacements' => [
            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME'],
            'routes/api' => ['LOWER_NAME'],
            'webpack' => ['LOWER_NAME'],
            'json' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            'views/index' => ['LOWER_NAME'],
            'views/master' => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
                'PROVIDER_NAMESPACE',
            ],
        ],
        'gitkeep' => true,
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated module. This path also will be added
        | automatically to list of scanned folders.
        |
        */

        'modules' => base_path('modules'),
        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules assets path.
        |
        */

        'assets' => public_path('modules'),
        /*
        |--------------------------------------------------------------------------
        | The migrations path
        |--------------------------------------------------------------------------
        |
        | Where you run 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */

        'migration' => base_path('Database\\migrations'),
        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Set the generate key to false to not generate that folder
        */
        'generator' => [
            'config' => ['path' => 'Config', 'generate' => true],
            'command' => ['path' => 'Console\\Commands', 'generate' => false],
            'migration' => ['path' => 'Database\\Migrations', 'generate' => false],
            'seed' => ['path' => 'Database\\Seeders', 'generate' => false],
            'seeder' => ['path' => 'Database\\Seeders', 'generate' => true],
            'factory' => ['path' => 'Database\\factories', 'generate' => false],
            'model' => ['path' => 'Models', 'generate' => false],
            'routes' => ['path' => 'Routes', 'generate' => true],
            'controller' => ['path' => 'Http\\Controllers', 'generate' => true],
            // 'filter' => ['path' => 'Http\\Middleware', 'generate' => false],
            'middleware' => ['path' => 'Http\\Middleware', 'generate' => false],
            'request' => ['path' => 'Http\\Requests', 'generate' => false],
            'provider' => ['path' => 'Providers', 'generate' => true],
            'assets' => ['path' => 'Resources\\assets', 'generate' => true],
            'lang' => ['path' => 'Resources\\lang', 'generate' => false],
            'views' => ['path' => 'Resources\\views', 'generate' => true],
            'views-admin' => ['path' => 'Resources\\views\\admin', 'generate' => true],
            'views-market' => ['path' => 'Resources\\views\\market', 'generate' => true],
            'views-layout' => ['path' => 'Resources\\views\\layouts', 'generate' => true],
            'component-view' => ['path' => 'Resources\\views\\components', 'generate' => true],
            'public' => ['path' => 'Public', 'generate' => false],
            'test' => ['path' => 'Tests\\Unit', 'generate' => false],
            'test-feature' => ['path' => 'Tests\\Feature', 'generate' => false],
            'support' => ['path' => 'Support', 'generate' => false],
            'trait' => ['path' => 'Http\\Traits', 'generate' => false],
            'repository' => ['path' => 'Repositories', 'generate' => false],
            'event' => ['path' => 'Events', 'generate' => false],
            'listener' => ['path' => 'Listeners', 'generate' => false],
            'policies' => ['path' => 'Policies', 'generate' => false],
            'rules' => ['path' => 'Rules', 'generate' => false],
            'jobs' => ['path' => 'Jobs', 'generate' => false],
            'emails' => ['path' => 'Emails', 'generate' => false],
            'notifications' => ['path' => 'Notifications', 'generate' => false],
            'resource' => ['path' => 'Transformers', 'generate' => false],
            'component' => ['path' => 'View\\Components', 'generate' => false],
            'component-class' => ['path' => 'View\\Components', 'generate' => false],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Package commands
    |--------------------------------------------------------------------------
    |
    | Here you can define which commands will be visible and used in your
    | application. If for example you don't use some of the commands provided
    | you can simply comment them out.
    |
    */
    'commands' => [
        Commands\CommandMakeCommand::class,
        Commands\ComponentClassMakeCommand::class,
        Commands\ComponentViewMakeCommand::class,
        Commands\ControllerMakeCommand::class,
        Commands\DisableCommand::class,
        Commands\DumpCommand::class,
        Commands\EnableCommand::class,
        Commands\EventMakeCommand::class,
        Commands\JobMakeCommand::class,
        Commands\ListenerMakeCommand::class,
        Commands\MailMakeCommand::class,
        Commands\MiddlewareMakeCommand::class,
        Commands\NotificationMakeCommand::class,
        Commands\ProviderMakeCommand::class,
        Commands\RouteProviderMakeCommand::class,
        Commands\InstallCommand::class,
        Commands\ListCommand::class,
        Commands\ModuleDeleteCommand::class,
        Commands\ModuleMakeCommand::class,
        Commands\FactoryMakeCommand::class,
        Commands\PolicyMakeCommand::class,
        Commands\RequestMakeCommand::class,
        Commands\RuleMakeCommand::class,
        Commands\MigrateCommand::class,
        Commands\MigrateRefreshCommand::class,
        Commands\MigrateResetCommand::class,
        Commands\MigrateRollbackCommand::class,
        Commands\MigrateStatusCommand::class,
        Commands\MigrationMakeCommand::class,
        Commands\ModelMakeCommand::class,
        Commands\PublishCommand::class,
        Commands\PublishConfigurationCommand::class,
        Commands\PublishMigrationCommand::class,
        Commands\PublishTranslationCommand::class,
        Commands\SeedCommand::class,
        Commands\SeedMakeCommand::class,
        Commands\SetupCommand::class,
        Commands\UnUseCommand::class,
        Commands\UpdateCommand::class,
        Commands\UseCommand::class,
        Commands\ResourceMakeCommand::class,
        Commands\TestMakeCommand::class,
        Commands\LaravelModulesV6Migrator::class,
        Commands\ComponentClassMakeCommand::class,
        Commands\ComponentViewMakeCommand::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */

    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('vendor/*/*'),
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for composer.json file, generated by this package
    |
    */

    'composer' => [
        'vendor' => 'nwidart',
        'author' => [
            'name' => 'Nicolas Widart',
            'email' => 'n.widart@gmail.com',
        ],
        'composer-output' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here is the config for setting up caching feature.
    |
    */
    'cache' => [
        'enabled' => false,
        'key' => 'laravel-modules',
        'lifetime' => 60,
    ],
    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register' => [
        'translations' => true,
        /**
         * load files on boot or register method
         *
         * Note: boot not compatible with asgardcms
         *
         * @example boot|register
         */
        'files' => 'register',
    ],

    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    |
    | You can define new types of activators here, file, database etc. The only
    | required parameter is 'class'.
    | The file activator will store the activation status in storage/installed_modules
    */
    'activators' => [
        'file' => [
            'class' => FileActivator::class,
            'statuses-file' => base_path('modules_statuses.json'),
            'cache-key' => 'activator.installed',
            'cache-lifetime' => 604800,
        ],
    ],

    'activator' => 'file',

    'config' => [
        'update-keys' => [
            'basic' => ['name', 'slug', 'title', 'type', 'description'],
            'view' => ['ui', 'component', 'layout', 'theme'],
            'route' => ['prefix', 'web.prefix', 'api.prefix', 'admin.prefix', 'db.prefix', 'table.prefix'],
            'layout' => ['sidebar', 'navbar', 'web.sidebar', 'admin.sidebar'],
            'other' => ['key', 'md5', 'uuid'],
        ],
        'key-map' => [
            'prefix' => ['web.prefix', 'api.prefix', 'table.prefix'],
            'component' => ['web.component'],
            'layout' => ['web.layout'],
            'theme' => ['web.theme'],
        ],
        "files" => [
            'controllers' => 'Http\Controllers\\',
            'seeds' => 'Database\Seeders\\',
            'models' => 'Entities\\',
            'factories' => 'Database\factories\\',
            'migrations' => 'Database\Migrations\\',
            'components' => 'View\Components\\',
            'component-views' => 'Resources\views\components\\',
            'layouts' => 'Resources\views\layouts\\',
        ],
        'commands' => [
            "delete" => [],
            "disable" => [],
            "dump" => [],
            "enable" => [],
            "install" => [],
            "list" => [],
            "make" => [],
            "make-command" => [],
            "make-component" => [],
            "make-component-view" => [],
            "make-controller" => [],
            "make-event" => [],
            "make-factory" => [],
            "make-job" => [],
            "make-listener" => [],
            "make-mail" => [],
            "make-middleware" => [],
            "make-migration" => [],
            "make-model" => ['model'],
            "make-notification" => [],
            "make-policy" => [],
            "make-provider" => [],
            "make-request" => [],
            "make-resource" => [],
            "make-rule" => [],
            "make-seed" => [],
            "make-test" => [],
            "migrate" => [],
            "migrate-refresh" => [],
            "migrate-reset" => [],
            "migrate-rollback" => [],
            "migrate-status" => [],
            "publish" => [],
            "publish-config" => [],
            "publish-migration" => [],
            "publish-translation" => [],
            "route-provider" => [],
            "seed" => [],
            "setup" => [],
            "unuse" => [],
            "update" => [],
            "use" => [],
            "v6:migrate" => [],

        ],

    ]
];
