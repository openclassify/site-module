<?php namespace Visiosoft\SiteModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Visiosoft\SiteModule\Alias\Contract\AliasRepositoryInterface;
use Visiosoft\SiteModule\Alias\AliasRepository;
use Anomaly\Streams\Platform\Model\Site\SiteAliasesEntryModel;
use Visiosoft\SiteModule\Alias\AliasModel;
use Visiosoft\SiteModule\Site\Contract\SiteRepositoryInterface;
use Visiosoft\SiteModule\Site\SiteRepository;
use Anomaly\Streams\Platform\Model\Site\SiteSiteEntryModel;
use Visiosoft\SiteModule\Site\SiteModel;
use Illuminate\Routing\Router;

class SiteModuleServiceProvider extends AddonServiceProvider
{

    /**
     * Additional addon plugins.
     *
     * @type array|null
     */
    protected $plugins = [];

    /**
     * The addon Artisan commands.
     *
     * @type array|null
     */
    protected $commands = [];

    /**
     * The addon's scheduled commands.
     *
     * @type array|null
     */
    protected $schedules = [];

    /**
     * The addon API routes.
     *
     * @type array|null
     */
    protected $api = [];

    /**
     * The addon routes.
     *
     * @type array|null
     */
    protected $routes = [
        'admin/site/aliases' => 'Visiosoft\SiteModule\Http\Controller\Admin\AliasesController@index',
        'admin/site/aliases/create' => 'Visiosoft\SiteModule\Http\Controller\Admin\AliasesController@create',
        'admin/site/aliases/edit/{id}' => 'Visiosoft\SiteModule\Http\Controller\Admin\AliasesController@edit',
        'admin/site' => 'Visiosoft\SiteModule\Http\Controller\Admin\SiteController@index',
        'admin/site/create' => 'Visiosoft\SiteModule\Http\Controller\Admin\SiteController@create',
        'admin/site/edit/{id}' => 'Visiosoft\SiteModule\Http\Controller\Admin\SiteController@edit',
        'admin/site/info/{siteID}' => 'Visiosoft\SiteModule\Http\Controller\Admin\SiteController@info',
        'admin/site/reset_mysql_password/{siteID}' => 'Visiosoft\SiteModule\Http\Controller\Admin\SiteController@resetMysqlPassword',
        'admin/site/reset_ssh_password/{siteID}' => 'Visiosoft\SiteModule\Http\Controller\Admin\SiteController@resetSshPassword',
        'sh/newsite' => 'Visiosoft\SiteModule\Http\Controller\SiteController@setup',
        'sh/delsite' => 'Visiosoft\SiteModule\Http\Controller\SiteController@deleteSite',
        '/conf/host/{siteID}' => 'Visiosoft\SiteModule\Http\Controller\SiteController@getNginxConfig',
        '/conf/php/{siteID}' => 'Visiosoft\SiteModule\Http\Controller\SiteController@getPhpConfig',
        '/conf/nginx/{siteID}' => 'Visiosoft\SiteModule\Http\Controller\SiteController@getCustomNginxConfig',
        '/conf/supervisor/{siteID}' => 'Visiosoft\SiteModule\Http\Controller\SiteController@getSupervisorConfig',
        '/conf/alias/{aliasID}' => 'Visiosoft\SiteModule\Http\Controller\AliasController@getNginxConfig'
    ];

    /**
     * The addon middleware.
     *
     * @type array|null
     */
    protected $middleware = [
        //Visiosoft\SiteModule\Http\Middleware\ExampleMiddleware::class
    ];

    /**
     * Addon group middleware.
     *
     * @var array
     */
    protected $groupMiddleware = [
        //'web' => [
        //    Visiosoft\SiteModule\Http\Middleware\ExampleMiddleware::class,
        //],
    ];

    /**
     * Addon route middleware.
     *
     * @type array|null
     */
    protected $routeMiddleware = [];

    /**
     * The addon event listeners.
     *
     * @type array|null
     */
    protected $listeners = [
        //Visiosoft\SiteModule\Event\ExampleEvent::class => [
        //    Visiosoft\SiteModule\Listener\ExampleListener::class,
        //],
    ];

    /**
     * The addon alias bindings.
     *
     * @type array|null
     */
    protected $aliases = [
        //'Example' => Visiosoft\SiteModule\Example::class
    ];

    /**
     * The addon class bindings.
     *
     * @type array|null
     */
    protected $bindings = [
        SiteAliasesEntryModel::class => AliasModel::class,
        SiteSiteEntryModel::class => SiteModel::class,
    ];

    /**
     * The addon singleton bindings.
     *
     * @type array|null
     */
    protected $singletons = [
        AliasRepositoryInterface::class => AliasRepository::class,
        SiteRepositoryInterface::class => SiteRepository::class,
    ];

    /**
     * Additional service providers.
     *
     * @type array|null
     */
    protected $providers = [
        //\ExamplePackage\Provider\ExampleProvider::class
    ];

    /**
     * The addon view overrides.
     *
     * @type array|null
     */
    protected $overrides = [
        //'streams::errors/404' => 'module::errors/404',
        //'streams::errors/500' => 'module::errors/500',
    ];

    /**
     * The addon mobile-only view overrides.
     *
     * @type array|null
     */
    protected $mobile = [
        //'streams::errors/404' => 'module::mobile/errors/404',
        //'streams::errors/500' => 'module::mobile/errors/500',
    ];

    /**
     * Register the addon.
     */
    public function register()
    {
        // Run extra pre-boot registration logic here.
        // Use method injection or commands to bring in services.
    }

    /**
     * Boot the addon.
     */
    public function boot()
    {
        // Run extra post-boot registration logic here.
        // Use method injection or commands to bring in services.
    }

    /**
     * Map additional addon routes.
     *
     * @param Router $router
     */
    public function map(Router $router)
    {
        // Register dynamic routes here for example.
        // Use method injection or commands to bring in services.
    }

}
