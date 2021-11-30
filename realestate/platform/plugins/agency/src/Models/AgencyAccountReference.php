<?php
namespace Botble\Agency\Models;

use Botble\realEstate\Models\Account;

use Botble\Base\Models\BaseModel;

class AgencyAccountReference extends BaseModel
{
  protected $fillable = ['agency_id','account_id'];
  public $timestamps = false;
  public function getAllData($agencyId)
    {
         return $this->join('re_accounts', 'agency_account_references.agency_id', '=','re_accounts.id')
              ->select('re_accounts.email','agency_account_references.account_id')
             ->where('agencyaccountrefrence.agency_id','=',$agencyId)
              ->get();
    }
    public function getData()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

}
