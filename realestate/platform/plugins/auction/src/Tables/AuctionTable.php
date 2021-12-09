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
            ->editColumn('price', function ($item) {
                if (!Auth::user()->hasPermission('auction.edit')) {
                    return $item->price;

                }
                return Html::link(route('auction.edit', $item->id), $item->price);
            })

//            ->editColumn('image', function ($item) {
//                return Html::image(RvMedia::getImageUrl($item->image->url, 'thumb', false, RvMedia::getDefaultImage()),
//                    $item->name, ['width' => 50]);
//            })
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

            ->addColumn('operations', function ($item) {
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
           // 'description',
            'price',
//            'image',
            'property_id',
            'created_at',


        ]);
            //->with(['image']);

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
                'title' => trans('core/base::tables.title'),
                'width' => '20px',
            ],
//            'description' => [
//                'title' => trans('core/base::tables.description'),
//                'width' => '20px',
//            ],
            'price' => [
                'title' => trans('core/base::tables.price'),
                'width' => '20px',
            ],
//            'image'  => [
//                'title' => trans('core/base::tables.image'),
//                'width' => '70px',
//            ],
            'property_id'  => [
                'property_id' => trans('core/base::tables.property_id'),
                'width' => '70px',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
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
