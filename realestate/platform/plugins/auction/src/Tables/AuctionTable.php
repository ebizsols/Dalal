<?php

namespace Botble\Auction\Tables;
use Botble\Auction\Repositories\Interfaces\AuctionInterface;
use Auth;
use BaseHelper;
use Botble\Auction\Models\Auction;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use RvMedia;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class AuctionTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * CareerTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CareerInterface $careerRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, AuctionInterface $auctionRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $auctionRepository;

        // if (!Auth::user()->hasAnyPermission(['auction.edit', 'auction.destroy','auction.assignauction'])) {
        if (!Auth::user()->hasAnyPermission(['auction.edit', 'auction.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;

        }
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @since 2.1
     */
    public function ajax()
    {

     $Listing= Auction::all();
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('title', function ($item) {
                if (!Auth::user()->hasPermission('auction.edit')) {
                    return $item->title;

                }
                return Html::link(route('auction.edit', $item->id), $item->title);
            })
//            ->editColumn('description', function ($item) {
//                if (!Auth::user()->hasPermission('auction.edit')) {
//                    return $item->description;
//
//                }
//                return Html::link(route('auction.edit', $item->id), $item->description);
//            })
//            ->editColumn('opening_price', function ($item) {
//                if (!Auth::user()->hasPermission('auction.edit')) {
//                    return $item->opening_price;
//
//                }
//                return Html::link(route('auction.edit', $item->id), $item->opening_price);
//            })

            ->editColumn('minimum_selling_price', function ($item) {
                if (!Auth::user()->hasPermission('auction.edit')) {
                    return $item->minimum_selling_price;

                }
                return Html::link(route('auction.edit', $item->id), $item->minimum_selling_price);
            })

            ->editColumn('start_date', function ($item) {
                if (!Auth::user()->hasPermission('auction.edit')) {
                    return $item->start_date;

                }
                return Html::link(route('auction.edit', $item->id), $item->start_date);
            })

            ->editColumn('end_date', function ($item) {
                if (!Auth::user()->hasPermission('auction.edit')) {
                    return $item->end_date;

                }
                return Html::link(route('auction.edit', $item->id), $item->end_date);
            })



            ->editColumn('avatar_id', function ($item) {

                //echo "<pre>"; print_r($item)
                //echo "<pre>"; print_r($item->avatar); exit;
                return Html::image(RvMedia::getImageUrl($item->avatar->url, 'thumb', false, RvMedia::getDefaultImage()),
                    $item->name, ['width' => 50]);
            })
            ->editColumn('property_id', function ($item) {
                if (!Auth::user()->hasPermission('auction.edit')) {
                    return $item->property_id;

                }
                return Html::link(route('auction.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('bids', function ($item) {
                $id = $item->id;
                $displayBids = route('auction.displayBids', $id);
                $opration = '<a href="'.$displayBids.'" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-original-title="Bid"><i class="fa fa-gavel"></i></a>';
                return $opration;//$this->getOperations('auction.edit', 'auction.destroy', $item);
            })

            ->addColumn('operations', function ($item) {
                $id = $item->id;
                $editRoute = route('auction.edit', $id);
                $deleteRoute = route('auction.deletes', $id);
                $displayBids = route('auction.displayBids', $id);
                $opration = '<ul data-dtr-index="0" class="dtr-details">
                <li class="text-center" data-dtr-index="10" data-dt-row="0" data-dt-column="10">

                <span class="dtr-data"><div class="table-actions">
                <a href="'.$editRoute.'" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-original-title="Edit"><i class="fa fa-edit"></i></a>
                 <a href="'.$displayBids.'" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-original-title="Bid"><i class="fa fa-gavel"></i></a>
                <a href="#" class="btn btn-icon btn-sm btn-danger deleteDialog" data-bs-toggle="tooltip" data-section="'.$deleteRoute.'" role="button" data-bs-original-title="Delete">

                <i class="fa fa-trash"></i>
                </a>
                </div>
                </span>
                </li>
                </ul>';
                return $this->getOperations('auction.edit', 'auction.destroy', $item);
            });
             


        return $this->toJson($data);
    }

    /**
     * Get the query object to be processed by table.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     * @since 2.1
     */
    public function query()
    {


        $query = $this->repository->getModel()->select([
            'id',
            'title',
            //'price',
            //'description',
            'minimum_selling_price',
            'start_date',
            'end_date',
            'avatar_id',
            'property_id',
            'created_at',


        ])->with(['avatar']);

        return $this->applyScopes($query);
    }

    /**
     * @return array
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'title' => trans('title'),
                'width' => '20px',
            ],
//            'price' => [
////                'title' => trans('price'),
////                'width' => '20px',
////            ],
//
            'minimum_selling_price' => [
                'title' => trans('minimum_selling_price'),
                'width' => '20px',
            ],
            'start_date' => [
                'title' => trans('start_date'),
                'width' => '20px',
            ],
            'end_date' => [
                'title' => trans('end_date'),
                'width' => '20px',
            ],

            'avatar_id'  => [
                'title' => trans('avatar_id'),
                'width' => '70px',
            ],
            'property_id'  => [
                'property_id' => trans('property_id'),
                'width' => '70px',
            ],
            'created_at' => [
                'title' => trans('created_at'),
                'width' => '100px',
            ],

            'bids' => [
                'title' => trans('Bids'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     * @since 2.1
     * @throws \Throwable
     */
    public function buttons()
    {
        return $this->addCreateButton(route('auction.create'), 'auction.create');
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('auction.deletes'), 'auctiony.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            'title' => [
                'title'    => trans('core/base::tables.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],

        ];
    }
}
