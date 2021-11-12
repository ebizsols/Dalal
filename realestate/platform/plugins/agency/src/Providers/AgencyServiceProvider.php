<?php

namespace Botble\Agency\Providers;

use Botble\Agency\Models\Agency;
use Botble\Agency\Models\AgencyAccountRefrence;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Botble\Agency\Repositories\Caches\AgencyCacheDecorator;
use Botble\Agency\Repositories\Eloquent\AgencyRepository;
use Botble\Agency\Repositories\Interfaces\AgencyInterface;
use Botble\Agency\Repositories\Caches\AgentCacheDecorator;
use Botble\Agency\Repositories\Eloquent\AgentRepository;
use Botble\Agency\Repositories\Interfaces\AgentInterface;
use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Language;
use SeoHelper;
use SlugHelper;

class AgencyServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function register()
    {
    
        //echo "agency register"; exit;
        $this->app->bind(AgencyInterface::class, function () {
            return new AgencyCacheDecorator(new AgencyRepository(new agency));
        });

        $this->app->bind(AgentInterface::class, function () {
            return new AgentCacheDecorator(new AgentRepository(new AgencyAccountRefrence));
        });
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        //echo "agency Boot"; exit;
        SlugHelper::registerModule(Agency::class, 'agencies');
        SlugHelper::setPrefix(Agency::class, 'agencies');

        $this->setNamespace('plugins/agency')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web']);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-agency',
                'priority'    => 7,
                'parent_id'   => null,
                'name'        => 'plugins/agency::agency.name',
                'icon'        => 'far fa-newspaper',
                'url'         => route('agency.index'),
                'permissions' => ['agency.index'],
            ]);
        });

        $modules = [Agency::class];
        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Language::registerModule($modules);
        }

        $this->app->booted(function () use ($modules) {
            SeoHelper::registerModule($modules);
        });
    }
}
