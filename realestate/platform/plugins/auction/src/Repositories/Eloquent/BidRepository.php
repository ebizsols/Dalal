<?php

namespace Botble\Auction\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Auction\Repositories\Interfaces\BidInterface;
use Illuminate\Support\Str;

class BidRepository extends RepositoriesAbstract implements BidInterface
{
//      /**
//      * {@inheritDoc}
//      */
//     public function createUsername($name, $id = null)
//     {
//         $username = Str::slug($name);
//         $index = 1;
//         $baseSlug = $username;
//         while ($this->model->where('username', $username)->where('id', '!=', $id)->count() > 0) {
//             $username = $baseSlug . '-' . $index++;
//         }

//         if (empty($username)) {
//             $username = $baseSlug . '-' . time();
//         }

//         $this->resetModel();

//         return $username;
//     }
}
