<?php

namespace Botble\Auction\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Auction\Repositories\Interfaces\AuctionInterface;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Repositories\Interfaces\SlugInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use SeoHelper;
use SlugHelper;
use Theme;

class PublicController extends Controller
{
    /**
     * @param Request $request
     * @param AuctionInterface $AuctionRepository
     * @return \Illuminate\Http\Response|\Response
     */
    public function agencies(Request $request, AuctionInterface $AuctionRepository)
    {
        SeoHelper::setTitle(__('Auctions'));

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add(__('Auctions'), route('public.Auctions'));

        $agencies = $AuctionRepository->advancedGet([
            'condition' => [
                'Auctions.status' => BaseStatusEnum::PUBLISHED,
            ],
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => (int)$request->input('page', 1),
            ],
            'order_by'  => ['Auctions.created_at' => 'DESC'],
        ]);

        //return Theme::scope('auction.Auctions', compact('Auctions'))->render();
    }

    /**
     * @param $key
     * @param AuctionInterface $AuctionRepository
     * @param SlugInterface $slugRepository
     * @return \Illuminate\Http\Response|\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function auction($key, AuctionInterface $AuctionRepository, SlugInterface $slugRepository)
    {
        $slug = $slugRepository->getFirstBy(['key' => $key, 'prefix' => SlugHelper::getPrefix(Auction::class)]);

        if (!$slug) {
            abort(404);
        }

        $auction = $AuctionRepository->getFirstBy([
            'id'     => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        if (!$auction) {
            abort(404);
        }

        SeoHelper::setTitle(__('Auctions') . ' - ' . $auction->name)
            ->setDescription($auction->description);

        $meta = new SeoOpenGraph;
        $meta->setDescription($auction->description);
        $meta->setUrl($auction->url);
        $meta->setTitle($auction->name);
        $meta->setType('article');

       

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add($auction->name, $auction->url);

        return Theme::scope('auction.auction', compact('auction'))->render();
    }
}
