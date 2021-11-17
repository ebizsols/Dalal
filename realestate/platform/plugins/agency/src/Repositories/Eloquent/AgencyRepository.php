<?php

namespace Botble\Agency\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Agency\Repositories\Interfaces\AgencyInterface;
use Illuminate\Support\Str;

class AgencyRepository extends RepositoriesAbstract implements AgencyInterface
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
