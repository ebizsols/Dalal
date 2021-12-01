<?php

namespace Botble\Agency\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Agency\Models\Agency;
use Botble\Agency\Repositories\Interfaces\AgencyInterface;
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
     * @param AgencyInterface $AgencyRepository
     * @return \Illuminate\Http\Response|\Response
     */
    public function agencies(Request $request, AgencyInterface $AgencyRepository)
    {
        SeoHelper::setTitle(__('Agencies'));

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add(__('Agencies'), route('public.agencies'));

        $agencies = $AgencyRepository->advancedGet([
            'condition' => [
                'agencies.status' => BaseStatusEnum::PUBLISHED,
            ],
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => (int)$request->input('page', 1),
            ],
            'order_by'  => ['agencies.created_at' => 'DESC'],
        ]);

        return Theme::scope('agency.agencies', compact('agencies'))->render();
    }

    /**
     * @param $key
     * @param AgencyInterface $AgencyRepository
     * @param SlugInterface $slugRepository
     * @return \Illuminate\Http\Response|\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function agency($key, AgencyInterface $AgencyRepository, SlugInterface $slugRepository)
    {
        $slug = $slugRepository->getFirstBy(['key' => $key, 'prefix' => SlugHelper::getPrefix(Agency::class)]);

        if (!$slug) {
            abort(404);
        }

        $agency = $AgencyRepository->getFirstBy([
            'id'     => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        if (!$agency) {
            abort(404);
        }

        SeoHelper::setTitle(__('Agencies') . ' - ' . $agency->name)
            ->setDescription($agency->description);

        $meta = new SeoOpenGraph;
        $meta->setDescription($agency->description);
        $meta->setUrl($agency->url);
        $meta->setTitle($agency->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add($agency->name, $agency->url);

        return Theme::scope('agency.agency', compact('agency'))->render();
    }
}
