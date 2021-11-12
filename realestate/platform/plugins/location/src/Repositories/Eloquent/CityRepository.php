<?php

namespace Botble\Location\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Builder;

class CityRepository extends RepositoriesAbstract implements CityInterface
{
    /**
     * {@inheritDoc}
     */
    public function filters($keyword, $limit = 10, array $with = [], array $select = ['cities.*'])
    {
        $data = $this->model
            ->where('cities.status', BaseStatusEnum::PUBLISHED)
            ->join('states', 'states.id', '=', 'cities.state_id')
            ->join('countries', 'countries.id', '=', 'cities.country_id')
            ->where('states.status', BaseStatusEnum::PUBLISHED)
            ->where('countries.status', BaseStatusEnum::PUBLISHED)
            ->where(function (Builder $query) use ($keyword) {
                return $query
                    ->where('cities.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('states.name', 'LIKE', '%' . $keyword . '%');
            })
            ->limit($limit);

        if ($with) {
            $data = $this->model->with($with);
        }

        return $this->applyBeforeExecuteQuery($data)->get($select);
    }
}

