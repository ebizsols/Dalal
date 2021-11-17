<?php

namespace Botble\Agency\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Agency\Http\Requests\AgentRequest;
use Botble\Agency\Models\Agency;
use Illuminate\Support\Facades\DB;
use Botble\RealEstate\Models\Account;
use Illuminate\Support\Arr;
use Botble\Agency\Models\AgencyAccountRefrence;

class AssignAgencyForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $agncyId = $this->getRequest()->id;
        
        $account = new Account();
        $accountList = $account->get()->toArray();
        $newAccountList = array();
        foreach ($accountList as $accountListIn) {
            $accountId = $accountListIn['id'];
            $accountEmail = $accountListIn['email'];
            $newAccountList[$accountId] = $accountEmail;
        }

        $id = $this->getRequest()->id;
        $AgencyAccountRefrence = new AgencyAccountRefrence();
        $AgencyAccountRefrencelisting = $AgencyAccountRefrence->where('agency_id',$id)->get()->toArray();
        $AgencyAccountRefrencenew = array();
        foreach ($AgencyAccountRefrencelisting as $something){
            $AgencyAccountRefrencenew[] = $something['account_id'];
        }
        $this->selectedIds = $AgencyAccountRefrencenew;
        //echo "<pre>"; print_r($AgencyAccountRefrencenew); exit;

        $this
            ->setupModel(new AgencyAccountRefrence)
            ->setValidatorClass(AgentRequest::class)
            ->setUrl(route('agency.saveAgent'))
            ->withCustomFields()
            ->add('assignAgency', 'select', [
                'label'      => trans('plugins/agency::agency.assignagency'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control select-full',
                    'multiple' => true,
                ],

                'choices'    => $newAccountList,
                'selected' => function () { // Allows handling data before passed to view for setting default values. Useful for related models
                    //$AgencyAccountRefrencenew = array();
                    //echo "<pre>---"; print_r($newAccountList); exit;
                    //return array_pluck($AgencyAccountRefrencenew, 'id');
                   //return Arr::pluck($id['AgencyAccountRefrence'], 'id');
                    return $this->selectedIds;
                }

            ])
            ->add('agencyId', 'hidden', [
                'value' => $agncyId,
            ])

            ->add('status', 'select', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status')

            ->add('button', 'select', [
                'label'      => trans('core/base::tables.button'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
