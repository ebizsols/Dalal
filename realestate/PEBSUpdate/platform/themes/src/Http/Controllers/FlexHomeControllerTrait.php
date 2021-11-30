<?php

use Botble\Agency\Repositories\Interfaces\AgencyInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;

trait FlexHomeControllerTrait{
    /**
     * @param Request $request
     * @param AccountInterface $accountRepository
     * @param AgencyInterface $accountRepository
     * @return \Response
     */
    public function getAgencies(Request $request, AgencyInterface $agencyRepository)
    {

        //echo "hello"; exit;
        //echo "<pre>"; print_r($agencyRepository); exit;
        $agencies = $agencyRepository->advancedGet([
            'paginate' => [
                'per_page'      => 12,
                'current_paged' => (int)$request->input('page'),
            ],
            // 'withCount' => [
            //     'properties' => function ($query) {
            //         return RepositoryHelper::applyBeforeExecuteQuery($query, $query->getModel());
            //     }
            // ],
        ]);

        SeoHelper::setTitle(__('Agencies'));

        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add(__('Agencies'), route('public.agencies'));

        return Theme::scope('real-estate.agencies', compact('agencies'))->render();
    }
}
