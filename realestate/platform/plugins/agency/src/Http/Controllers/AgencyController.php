<?php

namespace Botble\Agency\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Agency\Http\Requests\AgencyRequest;
use Botble\Agency\Repositories\Interfaces\AgencyInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
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
        // echo "heelo"; exit;

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
        return $formBuilder->create(AgencyForm::class)->renderForm();
    }

    /**
     * Insert new Agency into database
     *
     * @param AgencyRequest $request
     * @return BaseHttpResponse
     */
    public function store(AgencyRequest $request, BaseHttpResponse $response)
    {
        $agency = $this->AgencyRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));
        // ECHO "hello"; exit;
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
       // echo "<pre>"; print_r($agency); exit;

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
        $agency = $this->AgencyRepository->findOrFail($id);

        $agency->fill($request->input());

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

    // agent assignment
    public function assignAgent(request $req, FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/agency::agency.create'));
        
        return $formBuilder->create(AssignAgencyForm::class)->renderForm();
    }

    // /**
    //  * Insert new Agency into database
    //  *
    //  * @param AgencyRequest $request
    //  * @return BaseHttpResponse
    //  */
    // public function saveAgent(AgencyRequest $request, BaseHttpResponse $response)
    // {
    //     $agency = $this->AgencyRepository->createOrUpdate($request->input());

    //     event(new CreatedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));
    //     // ECHO "hello"; exit;
    //     return $response
    //         ->setPreviousUrl(route('agency.assignAgent'))
    //         ->setNextUrl(route('agency.saveAgent', $agency->id))
    //         ->setMessage(trans('core/base::notices.create_success_message'));
    // }

    /**
     * Insert new Agency into database
     *
     * @param AgencyRequest $request
     * @return BaseHttpResponse
     */
    public function saveAgent(AgentRequest $request, BaseHttpResponse $response)
    {
        echo "i am here"; print_r($request->input()); exit;
        $agency = $this->AgencyRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));
        // ECHO "hello"; exit;
        return $response
            ->setPreviousUrl(route('agency.assignAgent'))
            ->setNextUrl(route('agency.saveAgent', $agency->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }


}
