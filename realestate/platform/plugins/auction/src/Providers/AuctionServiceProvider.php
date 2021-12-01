<?php

namespace Botble\Auction\Providers;

use Botble\Agency\Models\Agency;
use Botble\Agency\Models\AgencyAccountReference;
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

class AuctionServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function register()
    {

        //echo "auction register"; exit;
        $this->app->bind(AuctionInterface::class, function () {
            return new AuctionCacheDecorator(new AuctionRepository(new auction));
        });

        $this->app->bind(AgentInterface::class, function () {
            return new AgentCacheDecorator(new AgentRepository(new AuctionAccountReference));
        });
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        //echo "auction Boot"; exit;
        SlugHelper::registerModule(Auction::class, 'agencies');
        SlugHelper::setPrefix(Auction::class, 'agencies');

        $this->setNamespace('plugins/auction')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web']);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-auction',
                'priority'    => 7,
                'parent_id'   => null,
                'name'        => 'plugins/auction::auction.name',
                'icon'        => 'far fa-newspaper',
                'url'         => route('auction.index'),
                'permissions' => ['auction.index'],
            ]);
        });

        $modules = [auction::class];
        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Language::registerModule($modules);
        }

        $this->app->booted(function () use ($modules) {
            SeoHelper::registerModule($modules);
        });
    }
}
