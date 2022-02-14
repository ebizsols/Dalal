<?php

namespace Botble\Auction\Tables;
use Botble\Auction\Repositories\Interfaces\BidInterface;
use Auth;
use BaseHelper;
use Botble\Auction\Models\Bid;
use Botble\Auction\Models\Auction;
use Botble\RealEstate\Models\Account;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use RvMedia;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class BidsTable extends TableAbstract
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
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, BidInterface $BidRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $BidRepository;

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
         
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            
            ->editColumn('user_id', function ($item) {
            $user_id = $item->user_id;
            $data = Account::find($user_id);
             return (isset($data->email))? $data->email:'';
                
            })
           ->editColumn('bid_amount', function ($item) {
                return $item->bid_amount;
                
            })
            ->editColumn('title', function ($item) {
                $auction_id = $item->auction_id;
             $data = Auction::find($auction_id);
             return $data->title;
                
            })

            ->editColumn('status', function ($item) {
                return $item->status;
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('y-m-d');
            })
            ->addColumn('operations', function ($item) {
                
                return '--';
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

        
        $requestUrl = $this->getAjaxUrl();
        $requestUrlExp = explode('displayBids/', $requestUrl);
        $requestUrlExp1 = isset($requestUrlExp[1])?$requestUrlExp[1]:'';
        $requestUrlExp1Exp = explode('?', $requestUrlExp1);
        $auctionId = isset($requestUrlExp1Exp[0])?$requestUrlExp1Exp[0]:'';

        //echo "<pre>";print_r($requestUrlExp1Exp0);exit;
        $query = $this->repository->getModel()->select([
            'id',
            'auction_id',
            'user_id',
            'bid_amount',
            'status',
            'created_at',
            'updated_at',
        ])->where('auction_id',$auctionId);

        return $this->applyScopes($query);
    }

    /**
     * @return array
     * @since 2.1
     */
    public function columns()
    {
        return [
            'user_id' => [
                'title' => trans('email'),
                'width' => '20px',
            ],
            'bid_amount' => [
                'title' => trans('bid_amount'),
                'width' => '20px',
            ],
            'title' => [
                'title' => trans('title'),
                'width' => '20px',
            ],
            'status' => [
                'title' => trans('status'),
                'width' => '20px',
            ],
           
            'created_at' => [
                'title' => trans('created_at'),
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
