<?php

namespace Botble\Auction\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Auction\Http\Requests\AuctionRequest;
use Botble\Auction\Repositories\Interfaces\AuctionInterface;
use Botble\Auction\Repositories\Eloquent\AuctionRepository;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Illuminate\Http\Request;
use Botble\Auction\Http\Resources\AuctionResource;
use Exception;
use Botble\Auction\Tables\AuctionTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Auction\Forms\AuctionForm;
use Botble\Auction\Forms\AssignAuctionForm;
use Botble\Base\Forms\FormBuilder;
use Carbon\Carbon;


class AuctionController extends BaseController
{
    /**
     * @var AuctionInterface
     */
    protected $AuctionRepository;

    /**
     * AuctionController constructor.
     * @param AuctionInterface $AuctionRepository
     */
    public function __construct(AuctionInterface $AuctionRepository)
    {
        $this->AuctionRepository = $AuctionRepository;
    }

    /**
     * Display all agencies
     * @param AuctionTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(AuctionTable $table)
    {

        page_title()->setTitle(trans('plugins/auction::auction.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {


        page_title()->setTitle(trans('plugins/auction::auction.create'));
        return $formBuilder->
        create(AuctionForm::class)->renderForm();


    }

    /**
     * Insert new Auction into database
     *
     * @param AuctionRequest $request
     * @return BaseHttpResponse
     */
    public function store(AuctionRequest $request, BaseHttpResponse $response)
    {


        $customRequest = $request->input();


        $startDate = $customRequest['start_date'];
        $startDate = str_replace('T', ' ', $startDate).':00';
        $customRequest['start_date'] = $startDate;
        //echo "<pre>"; print_r( $customRequest['start_date']); exit();

        $endDate = $customRequest['end_date'];
        $endDate = str_replace('T', ' ', $endDate).':00';
        $customRequest['end_date'] = $endDate;
   //echo "<pre>"; print_r( $customRequest['end_date']); exit();

        if ($request->input('avatar_id')) {

            $image = app(MediaFileInterface::class)->getFirstBy(['url' => $request->input('avatar_id')]);

            if ($image) {
                $customRequest['avatar_id'] = $image->id;
            }
        }





        $auction = $this->AuctionRepository->createOrUpdate($customRequest);

        event(new CreatedContentEvent(AUCTION_MODULE_SCREEN_NAME, $request, $auction));

        return $response
            ->setPreviousUrl(route('auction.index'))
            ->setNextUrl(route('auction.edit', $auction->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }
//

    /**
     * Show edit form
     *
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $auction = $this->AuctionRepository->findOrFail($id);
        event(new BeforeEditContentEvent($request, $auction));
        page_title()->setTitle(trans('plugins/auction::auction.edit') . ' "' . $auction->name . '"');

        return $formBuilder->create(AuctionForm::class, ['model' => $auction])->renderForm();
    }

    /**
     * @param $id
     * @param AuctionRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, AuctionRequest $request, BaseHttpResponse $response)
    {
        $customRequest = $request->input();


        $auction = $this->AuctionRepository->findOrFail($id);
////        $auction->is_featured = ($request->has('is_featured') && $request->is_profile_listing == 'false') ? 0 : 1;
////
      if ($request->input('avatar_id')) {
          $image = app(MediaFileInterface::class)->getFirstBy(['url' => $request->input('avatar_id')]);
            if ($image) {
               $auction->avatar_id = $image->id;
               $customRequest['avatar_id'] = $image->id;
           }
       }
//        //$auction->is_featured = $request->input('is_featured');
        $auction->fill($customRequest);

        $this->AuctionRepository->createOrUpdate($auction);

        event(new UpdatedContentEvent(AUCTION_MODULE_SCREEN_NAME, $request, $auction));

        return $response
            ->setPreviousUrl(route('auction.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $auction = $this->AuctionRepository->findOrFail($id);

            $this->AuctionRepository->delete($auction);

            event(new DeletedContentEvent(AUCTION_MODULE_SCREEN_NAME, $request, $auction));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $auction = $this->AuctionRepository->findOrFail($id);
            $this->AuctionRepository->delete($auction);
            event(new DeletedContentEvent(AUCTION_MODULE_SCREEN_NAME, $request, $auction));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
    /**
     * @param Request $request
     * @param BaseHttpResponse $response
      */
    public function getList(Request $request, BaseHttpResponse $response)
    {

        $keyword = $request->input('q');

        if (!$keyword) {
            return $response->setData([]);
        }

        $data = $this->AuctionRepository->getModel()
            ->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description', 'LIKE', '%' . $keyword . '%')
            ->select(['id', 'title', 'description','minimum_selling_price','start_date','end_date', 'avatar_id', 'property_id'])
            ->take(10)
            ->get();

        return $response->setData(AuctionResource::collection($data));
    }


    public function assignAgent(request $req, FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/auction::auction.create'));

        return $formBuilder->create(AssignAuctionForm::class)->renderForm();
    }


}
