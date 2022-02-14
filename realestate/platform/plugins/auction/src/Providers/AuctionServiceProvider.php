<?php

namespace Botble\Auction\Providers;

use Botble\Auction\Models\Auction;
use Botble\Auction\Models\Bid;
use Botble\Auction\Models\AuctionAccountReference;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Botble\Auction\Repositories\Caches\AuctionCacheDecorator;
use Botble\Auction\Repositories\Caches\BidCacheDecorator;
use Botble\Auction\Repositories\Eloquent\AuctionRepository;
use Botble\Auction\Repositories\Eloquent\BidRepository;
use Botble\Auction\Repositories\Interfaces\AuctionInterface;
use Botble\Auction\Repositories\Interfaces\BidInterface;
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
        $this->app->bind(BidInterface::class, function () {
            return new BidCacheDecorator(new BidRepository(new Bid));
        });
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        SlugHelper::registerModule(Auction::class, 'auctions');
        SlugHelper::setPrefix(Auction::class, 'auctions');

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

        $modules = [Auction::class];
        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Language::registerModule($modules);
        }

        $this->app->booted(function () use ($modules) {
            SeoHelper::registerModule($modules);
        });
    }
}
