<?php

namespace Botble\Agency\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Agency\Http\Requests\AgencyRequest;
use Botble\Agency\Repositories\Interfaces\AgencyInterface;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Illuminate\Http\Request;
use Botble\Agency\Http\Resources\AgencyResource;
use Exception;
use Botble\Agency\Tables\AgencyTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Agency\Forms\AgencyForm;
use Botble\Agency\Forms\AssignAgencyForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Agency\Http\Requests\AgentRequest;
use Carbon\Carbon;


class AgencyController extends BaseController
{
    /**
     * @var AgencyInterface
     */
    protected $AgencyRepository;

    /**
     * AgencyController constructor.
     * @param AgencyInterface $AgencyRepository
     */
    public function __construct(AgencyInterface $AgencyRepository)
    {
        $this->AgencyRepository = $AgencyRepository;
    }

    /**
     * Display all agencies
     * @param AgencyTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(AgencyTable $table)
    {
        

        page_title()->setTitle(trans('plugins/agency::agency.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
       
        
        page_title()->setTitle(trans('plugins/agency::agency.create'));
        return $formBuilder->
        create(AgencyForm::class)->renderForm();
        

    }

    /**
     * Insert new Agency into database
     *
     * @param AgencyRequest $request
     * @return BaseHttpResponse
     */
    public function store(AgencyRequest $request, BaseHttpResponse $response)
    {
        
        $customRequest = $request->input();
        if ($request->input('avatar_id')) {
            
            $image = app(MediaFileInterface::class)->getFirstBy(['url' => $request->input('avatar_id')]);
            
            if ($image) {
                $customRequest['avatar_id'] = $image->id;
            }
        }
 
    
        $agency = $this->AgencyRepository->createOrUpdate($customRequest);

        event(new CreatedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));
        
        return $response
            ->setPreviousUrl(route('agency.index'))
            ->setNextUrl(route('agency.edit', $agency->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

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
        $agency = $this->AgencyRepository->findOrFail($id);
        event(new BeforeEditContentEvent($request, $agency));
        page_title()->setTitle(trans('plugins/agency::agency.edit') . ' "' . $agency->name . '"');
       
        return $formBuilder->create(AgencyForm::class, ['model' => $agency])->renderForm();
    }

    /**
     * @param $id
     * @param AgencyRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, AgencyRequest $request, BaseHttpResponse $response)
    {
        $customRequest = $request->input();

        
        $agency = $this->AgencyRepository->findOrFail($id);
        $agency->is_featured = ($request->has('is_featured') && $request->is_profile_listing == 'false') ? 0 : 1;
        
        if ($request->input('avatar_id')) {
            $image = app(MediaFileInterface::class)->getFirstBy(['url' => $request->input('avatar_id')]);
            if ($image) {
                $agency->avatar_id = $image->id;
                $customRequest['avatar_id'] = $image->id;
            }
        }
        $agency->is_featured = $request->input('is_featured');
        $agency->fill($customRequest);

        $this->AgencyRepository->createOrUpdate($agency);

        event(new UpdatedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));

        return $response
            ->setPreviousUrl(route('agency.index'))
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
            $agency = $this->AgencyRepository->findOrFail($id);

            $this->AgencyRepository->delete($agency);

            event(new DeletedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));

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
            $agency = $this->AgencyRepository->findOrFail($id);
            $this->AgencyRepository->delete($agency);
            event(new DeletedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));
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

        $data = $this->agencyRepository->getModel()
            ->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description', 'LIKE', '%' . $keyword . '%')
            ->select(['id', 'name', 'description'])
            ->take(10)
            ->get();

        return $response->setData(AgencyResource::collection($data));
    }

    
    public function assignAgent(request $req, FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/agency::agency.create'));
        
        return $formBuilder->create(AssignAgencyForm::class)->renderForm();
    }


}
