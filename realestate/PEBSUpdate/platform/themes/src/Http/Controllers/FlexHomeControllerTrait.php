<?php

use Botble\Agency\Repositories\Interfaces\AgencyInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait FlexHomeControllerTrait{
    /**
     * @param Request $request
     * @param AccountInterface $accountRepository
     * @param AgencyInterface $accountRepository
     * @return \Response
     */
    public function getAgencies(Request $request, AgencyInterface $agencyRepository)
    {
        $agencies = $agencyRepository->advancedGet([
            'paginate' => [
                'per_page'      => 12,
                'current_paged' => (int)$request->input('page'),
            ],
        ]);
        SeoHelper::setTitle(__('Agencies'));

        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add(__('Agencies'), route('public.agencies'));

        return Theme::scope('PEBSUpdate.real-estate.agency.agencies', compact('agencies'))->render();
    }
}
