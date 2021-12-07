<?php
namespace Botble\Auction\Models;

use Botble\realEstate\Models\Account;

use Botble\Base\Models\BaseModel;

class AuctionAccountReference extends BaseModel
{
  protected $fillable = ['auction_id','account_id'];
  public $timestamps = false;
  public function getAllData($auctionId)
    {
         return $this->join('re_accounts', 'auction_account_references.auction_id', '=','re_accounts.id')
              ->select('re_accounts.email','auction_account_references.account_id')
             ->where('auctionaccountrefrence.auction_id','=',$auctionId)
              ->get();
    }
    public function getData()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

}
