<?php

namespace Botble\Auction\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Auction\Http\Requests\AgentRequest;
use Botble\Auction\Models\Auction;
use Illuminate\Support\Facades\DB;
use Botble\RealEstate\Models\Account;
use Illuminate\Support\Arr;
use Botble\Auction\Models\AuctionAccountReference;

class AssignAuctionForm extends FormAbstract
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
        $AuctionAccountReference = new AuctionAccountReference();
        $AuctionAccountReferencelisting = $AuctionAccountReference->where('auction_id',$id)->get()->toArray();
        $AuctionAccountReferencenew = array();
        foreach ($AuctionAccountReferencelisting as $something){
            $AuctionAccountReferencenew[] = $something['account_id'];
        }
        $this->selectedIds = $AuctionAccountReferencenew;
        //echo "<pre>"; print_r($AuctionAccountReferencenew); exit;

        $this
            ->setupModel(new AuctionAccountReference)
            ->setValidatorClass(AgentRequest::class)
            ->setUrl(route('auction.saveAgent'))
            ->withCustomFields()
            ->add('assignAuction', 'select', [
                'label'      => trans('plugins/auction::auction.assignauction'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control select-full',
                    'multiple' => true,
                ],

                'choices'    => $newAccountList,
                'selected' => function () { // Allows handling data before passed to view for setting default values. Useful for related models
                    //$AuctionAccountReferencenew = array();
                    //echo "<pre>---"; print_r($newAccountList); exit;
                    //return array_pluck($AuctionAccountReferencenew, 'id');
                   //return Arr::pluck($id['AuctionAccountReference'], 'id');
                    return $this->selectedIds;
                }

            ])
            ->add('auctionId', 'hidden', [
                'value' => $auctionId,
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
