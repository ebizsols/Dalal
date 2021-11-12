<?php

namespace Botble\Agency\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;

use Botble\Agency\Repositories\Interfaces\AgentInterface;
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

class AgentController extends BaseController
{
    /**
     * @var AgentInterface
     */
   
    protected $AgentRepository;         

    /**
     * AgencyController constructor.
     * @param AgentInterface $AgencyRepository
     */
    public function __construct(AgentInterface $AgentRepository)
    {
        $this->AgentRepository = $AgentRepository;
    }

    public function assignAgent(request $req, FormBuilder $formBuilder)
    {
        // check agency id if exist or not if not then redirect to listing page
        
        page_title()->setTitle(trans('plugins/agency::agency.create'));
        
        return $formBuilder->create(AssignAgencyForm::class)->renderForm();
    }

    

    /**
     * Insert new Agency into database
     *
     * @param AgencyRequest $request+
     * @return BaseHttpResponse
     */
    public function saveAgent(AgentRequest $request, BaseHttpResponse $response)
    {
        // echo "i am here"; print_r($request->input()); exit;
        $agentIds = isset($request->assignAgency)?$request->assignAgency:array();
        $agencyId = isset($request->agencyId)?$request->agencyId:0;
        

        foreach ($agentIds as $agentId){

            $agency = $this->AgentRepository->createOrUpdate([
                'account_id'=>$agentId, 
                'agency_id'=>$agencyId
            ]);
        }

        //echo "<pre>"; print_r('check db'); exit;

        event(new CreatedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));
        // ECHO "hello"; exit;
        return $response
            ->setPreviousUrl(route('agency.assignAgent'))
            ->setNextUrl(route('agency.saveAgent', $agency->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * Insert new Agency into database
     *
     * @param AgencyRequest $request
     * @return BaseHttpResponse
     */
    public function store(AgentRequest $request, BaseHttpResponse $response)
    {
        $agency = $this->AgencyRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));
        // ECHO "hello"; exit;
        return $response
            ->setPreviousUrl(route('agency.saveAgent'))
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
        $agency = $this->AgentRepository->findOrFail($id);
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
    public function update($id, AgentRequest $request, BaseHttpResponse $response)
    {
        $agency = $this->AgencyRepository->findOrFail($id);

        $agency->fill($request->input());

        $this->AgencyRepository->createOrUpdate($agency);

        event(new UpdatedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));

        return $response
            ->setPreviousUrl(route('agency.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}