<?php

namespace Botble\Auction\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Auction\Models\Auction;
use Botble\Auction\Http\Requests\AuctionRequest;
use Botble\RealEstate\Models\Property;




class AuctionForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {

        $list = Property::all();
        foreach($list as $listIn) {
            //$Array = array();
                $ProId = $listIn->id;
                $ProName = $listIn->name;
                $ProNameId[$ProId] = $ProName;
            }




        $this
            ->setupModel(new Auction)
            ->setValidatorClass(AuctionRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                //'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('plugins/auction::auction.title'),
                    'data-counter' => 50,
                ],
            ])
            ->add('description', 'textarea', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
//            ->add('opening_price', 'number', [
//                //'label' => trans('core/base::forms.name'),
//                'label_attr' => ['class' => 'control-label required'],
//                'attr' => [
//                    'placeholder'  => trans('plugins/auction::auction.opening_price'),
//                    'data-counter' => 50,
//                ],
//            ])
            ->add('minimum_selling_price', 'number', [
                //'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('plugins/auction::auction.minimum_selling_price'),
                    'data-counter' => 50,
                ],
            ])

            ->add('start_date', 'datetime-local', [
                //'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('plugins/auction::auction.start_date'),
                    'data-counter' => 50,
                ],
            ])

            ->add('end_date', 'datetime-local', [
                //'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('plugins/auction::auction.end_date'),
                    'data-counter' => 50,
                ],
            ])



            ->add('status', 'select', [
                // 'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])

            ->add('property_id', 'select', [
                // 'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    =>  $ProNameId
            ])

            ->setBreakFieldPoint('status')



            ->add('is_featured', 'onOff', [
                'label'         => trans('core/base::forms.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('avatar_id', 'mediaImage', [
                'label'      => trans('core/base::forms.avatar_id'),
                'label_attr' => ['class' => 'control-label'],
                'value'      => $this->getModel()->avatar->url,
            ])
            ->setBreakFieldPoint('avatar_id');
  }
}
