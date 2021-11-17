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
use Botble\Agency\Models\AgencyAccountRefrence;
use Illuminate\Support\Facades\DB;

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
       
         
        $agentIds = isset($request->assignAgency)?$request->assignAgency:array();
        $agencyId = isset($request->agencyId)?$request->agencyId:array();

        //delete data before insert
        $agencyAccountRefrence = new AgencyAccountRefrence();
        $agencyAccountRefrence->where('agency_id', $agencyId)->delete();
        
        if(!empty($agentIds)){
            foreach ($agentIds as $agentId){

                $agency = $this->AgentRepository->createOrUpdate([
                    'account_id'=>$agentId, 
                    'agency_id'=>$agencyId
                ]);
            }
            event(new CreatedContentEvent(AGENCY_MODULE_SCREEN_NAME, $request, $agency));
        }
        
        return $response
            ->setNextUrl(route('agency.index'))
            ->setMessage(trans('plugins/agency::agency.agent'));
        
    }

    
}