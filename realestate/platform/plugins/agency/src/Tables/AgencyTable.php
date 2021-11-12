<?php

namespace Botble\Agency\Tables;

use Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Agency\Repositories\Interfaces\AgencyInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class AgencyTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * CareerTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CareerInterface $careerRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, AgencyInterface $agencyRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $agencyRepository;

        // if (!Auth::user()->hasAnyPermission(['agency.edit', 'agency.destroy','agency.assignagency'])) {
        if (!Auth::user()->hasAnyPermission(['agency.edit', 'agency.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
            // echo "hello";exit;
        }
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('agency.edit')) {
                    return $item->name;
                }
                return Html::link(route('agency.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->editColumn('assignAgent', function ($item) {
                // if (!Auth::user()->hasPermission('agency.assignAgent')) {
                //     return $item->name;
                // }
                return Html::link(route('agency.assignAgent',  $item->id), 'Assign Agent');
            })
            
            ->addColumn('operations', function ($item) {
                return $this->getOperations('agency.edit', 'agency.destroy', $item);
            });
            

        return $this->toJson($data);
    }

    /**
     * Get the query object to be processed by table.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     * @since 2.1
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            'id',
            'name',
            'created_at',
            'status',
        ]);

        return $this->applyScopes($query);
    }

    /**
     * @return array
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
            'assignAgent' => [
                'title' => 'Assign Agent',
                'width' => '100px',
            ],
            

        ];
    }

    /**
     * @return array
     * @since 2.1
     * @throws \Throwable
     */
    public function buttons()
    {
        return $this->addCreateButton(route('agency.create'), 'agency.create');
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('agency.deletes'), 'agency.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
