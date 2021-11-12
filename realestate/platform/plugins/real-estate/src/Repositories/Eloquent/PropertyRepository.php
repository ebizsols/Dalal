<?php

namespace Botble\RealEstate\Repositories\Eloquent;

use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Enums\PropertyTypeEnum;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class PropertyRepository extends RepositoriesAbstract implements PropertyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRelatedProperties(int $propertyId, $limit = 4, array $with = [])
    {
        $currentProperty = $this->findById($propertyId, ['categories']);

        $this->model = $this->originalModel;
        $this->model = $this->model->where('re_properties.id', '<>', $propertyId)
            ->notExpired();

        if ($currentProperty && $currentProperty->categories->count()) {
            $this->model
                ->join('re_property_categories', 're_property_categories.property_id', 're_properties.id')
                ->whereIn('re_property_categories.category_id', $currentProperty->categories->pluck('id')->toArray())
                ->where('type', $currentProperty->type);
        }

        $params = [
            'condition' => [
                ['status', 'NOT_IN', [PropertyStatusEnum::NOT_AVAILABLE]],
                'moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'order_by'  => [
                'created_at' => 'desc',
            ],
            'take'      => $limit,
            'paginate'  => [
                'per_page'      => 12,
                'current_paged' => 1,
            ],
            'with'      => $with,
        ];

        return $this->advancedGet($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties($filters = [], $params = [])
    {
        $filters = array_merge([
            'keyword'     => null,
            'type'        => null,
            'bedroom'     => null,
            'bathroom'    => null,
            'floor'       => null,
            'min_square'  => null,
            'max_square'  => null,
            'min_price'   => null,
            'max_price'   => null,
            'project'     => null,
            'category_id' => null,
            'city_id'     => null,
            'city'        => null,
            'location'    => null,
            'sort_by'     => null,
        ], $filters);

        switch ($filters['sort_by']) {
            case 'date_asc':
                $orderBy = [
                    're_properties.created_at' => 'asc',
                ];
                break;
            case 'date_desc':
                $orderBy = [
                    're_properties.created_at' => 'desc',
                ];
                break;
            case 'price_asc':
                $orderBy = [
                    're_properties.price' => 'asc',
                ];
                break;
            case 'price_desc':
                $orderBy = [
                    're_properties.price' => 'desc',
                ];
                break;
            case 'name_asc':
                $orderBy = [
                    're_properties.name' => 'asc',
                ];
                break;
            case 'name_desc':
                $orderBy = [
                    're_properties.name' => 'desc',
                ];
                break;
            default:
                $orderBy = [
                    're_properties.created_at' => 'desc',
                ];
                break;
        }

        $params = array_merge([
            'condition' => [
                ['re_properties.status', 'NOT_IN', [PropertyStatusEnum::NOT_AVAILABLE]],
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'order_by'  => [
                're_properties.created_at' => 'desc',
            ],
            'take'      => null,
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => 1,
            ],
            'select'    => [
                're_properties.*',
            ],
            'with'      => [],
        ], $params);

        $params['order_by'] = $orderBy;

        $this->model = $this->originalModel->notExpired();

        if ($filters['keyword'] !== null) {
            $this->model = $this->model
                ->where(function (Builder $query) use ($filters) {
                    return $query
                        ->where('re_properties.name', 'LIKE', '%' . $filters['keyword'] . '%')
                        ->orWhere('re_properties.location', 'LIKE', '%' . $filters['keyword'] . '%');
                });
        }

        if ($filters['type'] !== null) {
            if ($filters['type'] == PropertyTypeEnum::SALE) {
                $this->model = $this->model->where('re_properties.type', $filters['type'])
                    ->where('re_properties.status', PropertyStatusEnum::SELLING);
            } else {
                $this->model = $this->model->where('re_properties.type', $filters['type'])
                    ->where('re_properties.status', PropertyStatusEnum::RENTING);
            }
        }

        if ($filters['bedroom']) {
            if ($filters['bedroom'] < 5) {
                $this->model = $this->model->where('re_properties.number_bedroom', $filters['bedroom']);
            } else {
                $this->model = $this->model->where('re_properties.number_bedroom', '>=', $filters['bedroom']);
            }
        }

        if ($filters['bathroom']) {
            if ($filters['bathroom'] < 5) {
                $this->model = $this->model->where('re_properties.number_bathroom', $filters['bathroom']);
            } else {
                $this->model = $this->model->where('re_properties.number_bathroom', '>=', $filters['bathroom']);
            }
        }

        if ($filters['floor']) {
            if ($filters['floor'] < 5) {
                $this->model = $this->model->where('re_properties.number_floor', $filters['floor']);
            } else {
                $this->model = $this->model->where('re_properties.number_floor', '>=', $filters['floor']);
            }
        }

        if ($filters['min_square'] !== null || $filters['max_square'] !== null) {
            $this->model = $this->model
                ->where(function ($query) use ($filters) {
                    $minSquare = Arr::get($filters, 'min_square');
                    $maxSquare = Arr::get($filters, 'max_square');

                    /**
                     * @var \Illuminate\Database\Query\Builder $query
                     */
                    if ($minSquare !== null) {
                        $query = $query->where('re_properties.square', '>=', $minSquare);
                    }

                    if ($maxSquare !== null) {
                        $query = $query->where('re_properties.square', '<=', $maxSquare);
                    }

                    return $query;
                });
        }

        if ($filters['min_price'] !== null || $filters['max_price'] !== null) {
            $this->model = $this->model
                ->where(function ($query) use ($filters) {

                    $minPrice = Arr::get($filters, 'min_price');
                    $maxPrice = Arr::get($filters, 'max_price');

                    /**
                     * @var Builder $query
                     */
                    if ($minPrice !== null) {
                        $query = $query->where('re_properties.price', '>=', $minPrice);
                    }

                    if ($maxPrice !== null) {
                        $query = $query->where('re_properties.price', '<=', $maxPrice);
                    }

                    return $query;
                });
        }

        if ($filters['city'] !== null) {
            $this->model = $this->model
                ->join('cities', 'cities.id', '=', 're_properties.city_id')
                ->where('cities.slug', $filters['city']);
        }

        if ($filters['project'] !== null) {
            $this->model = $this->model->where('re_properties.project_id', $filters['project']);
        }

        if ($filters['category_id'] !== null) {
            $categoryIds = get_property_categories_related_ids($filters['category_id']);
            $this->model = $this->model
                ->join('re_property_categories', 're_property_categories.property_id', 're_properties.id')
                ->whereIn('re_property_categories.category_id', $categoryIds);
        }

        if ($filters['city_id']) {
            $this->model = $this->model->where('re_properties.city_id', $filters['city_id']);
        } elseif ($filters['location']) {
            $locationData = explode(',', $filters['location']);
            if (count($locationData) > 1) {
                $this->model = $this->model
                    ->join('cities', 'cities.id', '=', 're_properties.city_id')
                    ->join('states', 'states.id', '=', 'cities.state_id')
                    ->where(function ($query) use ($locationData) {
                        return $query
                            ->where('cities.name', 'LIKE', '%' . trim($locationData[0]) . '%')
                            ->orWhere('states.name', 'LIKE', '%' . trim($locationData[0]) . '%');
                    });
            } else {
                $this->model = $this->model
                    ->join('cities', 'cities.id', '=', 're_properties.city_id')
                    ->join('states', 'states.id', '=', 'cities.state_id')
                    ->where(function ($query) use ($filters) {
                        return $query
                            ->where('cities.name', 'LIKE', '%' . trim($filters['location']) . '%')
                            ->orWhere('states.name', 'LIKE', '%' . trim($filters['location']) . '%');
                    });
            }
        }

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty(int $propertyId, array $with = [])
    {
        $params = [
            'condition' => [
                'id'                => $propertyId,
                'moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'with'      => $with,
            'take'      => 1,
        ];

        $this->model = $this->originalModel->notExpired();

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesByConditions(array $condition, $limit, array $with = [])
    {
        $this->model = $this->originalModel->notExpired();

        $params = [
            'condition' => $condition,
            'with'      => $with,
            'take'      => $limit,
            'order_by'  => ['created_at' => 'desc'],
        ];

        return $this->advancedGet($params);
    }
}
