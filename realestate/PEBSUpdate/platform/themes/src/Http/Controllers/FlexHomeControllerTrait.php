<?php

use Botble\Agency\Models\Agency;
use Botble\Agency\Repositories\Interfaces\AgencyInterface;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Agency\Models\AgencyAccountReference;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait FlexHomeControllerTrait
{
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
                'per_page' => 12,
                'current_paged' => (int)$request->input('page'),
            ],
        ]);
        SeoHelper::setTitle(__('Agencies'));

        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add(__('Agencies'), route('public.agencies'));

        return Theme::scope('PEBSUpdate.real-estate.agency.agencies', compact('agencies'))->render();
    }

    public function getAgency(
        string $id,
        Request $request,
        AgencyInterface $agencyRepository,
        PropertyInterface $propertyRepository,
        AccountInterface $accountRepository
    )
    {
        $agency = $agencyRepository->getFirstBy(['id' => $id]);

        if (!$agency) {
            abort(404);
        }

        SeoHelper::setTitle($agency->title);

        $properties = $propertyRepository->advancedGet([
            'condition' => [
                'author_id' => $agency->id,
                'author_type' => Agency::class,
            ],
            'paginate' => [
                'per_page' => 12,
                'current_paged' => (int)$request->input('page'),
            ],
            'with' => config('plugins.real-estate.real-estate.properties.relations'),
        ]);


        $AgencyAccountReference = new AgencyAccountReference();
        $AgencyAccountReferencelisting = $AgencyAccountReference->where('agency_id', $id)->get()->toArray();
        $accountList = array();
        foreach ($AgencyAccountReferencelisting as $something) {
            $accountId = $something['account_id'];

            $account = new Account();
            $accountData = $account->where('id', $accountId)->first();
            if (!empty($accountData)) {
                $accountData = $accountData->toArray();
            }

            $accountList[] = $accountData;

        }

        $agency = $agencyRepository->getFirstBy(['id' => $id]);
        $AgencyAccountReference = new AgencyAccountReference();
        $AgencyAccountReferencelisting = $AgencyAccountReference->where('agency_id', $id)->get()->toArray();
        $property = array();
        foreach ($AgencyAccountReferencelisting as $something) {
            $accountId = $something['account_id'];

            $account = new Property();
            $accountData = $account->where('id', $accountId)->first();
            if (!empty($accountData)) {
                $accountData = $accountData->toArray();
                $property[] = $accountData;
            }
        }

        return Theme::scope('PEBSUpdate.real-estate.agency.agency', compact('properties', 'property', 'agency', 'accountList'))
            ->render();
    }
}
