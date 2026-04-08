<?php

namespace Workdo\PettyCashManagement\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Workdo\PettyCashManagement\Entities\Reimbursement;

class ReimbursementDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rawColumn = ['status','description'];
        $dataTable =  (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('user_id', function ($reimbursement) {
                return $reimbursement->user ? $reimbursement->user->name ?? '-' : '-';
            })
            ->editColumn('category_id', function ($reimbursement) {
                return $reimbursement->category ? $reimbursement->category->name ?? '-' : '-';
            })
            ->editColumn('amount', function ($reimbursement) {
                return $reimbursement->amount ? $reimbursement->amount : '-' ;
            })
            ->editColumn('request_date', function ($reimbursement) {
                return $reimbursement->created_at ? company_date_formate($reimbursement->request_date) ?? '-' : '-';
            })
            ->editColumn('approved_date', function ($reimbursement) {
                return $reimbursement->approved_date ? company_date_formate($reimbursement->approved_date) ?? '-' : '-';
            })
            ->editColumn('approved_by', function ($reimbursement) {
                return $reimbursement->approved_by
                    ? $reimbursement->approver->name ?? '-' : '-';
            })
            ->editColumn('description', function ($reimbursement) {
                if ($reimbursement->description) {
                    $url = route('reimbursement.description', $reimbursement->id);
                    return '<a class="action-item" data-url="' . $url . '" data-ajax-popup="true" data-bs-toggle="tooltip" title="Description" data-title="Description"><i class="fa fa-comment"></i></a>';
                } else {
                    return '-';
                }
            })
            ->editColumn('status', function ($reimbursement) {
                if ($reimbursement->status == 'approved') {
                    return '<span class="badge bg-success p-2 px-3 text-white">' . ucfirst($reimbursement->status) . '</span>';
                } elseif ($reimbursement->status == 'pending') {
                    return '<span class="badge bg-warning p-2 px-3 text-white">' . ucfirst($reimbursement->status) . '</span>';
                } elseif ($reimbursement->status == 'rejected') {
                    return '<span class="badge bg-danger p-2 px-3 text-white">' . ucfirst($reimbursement->status) . '</span>';
                } else {
                    return '<span class="badge bg-secondary p-2 px-3 text-white">Unknown</span>';
                }
            });
            if (\Laratrust::hasPermission('reimbursement edit') || \Laratrust::hasPermission('reimbursement delete')) {
                $dataTable->addColumn('action', function ($reimbursement) {
                    if($reimbursement->status == 'pending'){
                        return view('petty-cash-management::reimbursement.action', compact('reimbursement'));
                    }
                });
                $rawColumn[] = 'action';
            }
        return $dataTable->rawColumns($rawColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Reimbursement $model): QueryBuilder
    {
        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $model = $model->where('user_id',Auth::user()->id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
            $model = $model->orderBy('created_at', 'desc');
        } else {
            $model = $model->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
            $model = $model->orderBy('created_at', 'desc');
        }
       return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => "_MENU_" . __('Entries Per Page'),
                "searchPlaceholder" => __('Search...'),
                "search" => "",
                "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')
            ])
            ->initComplete('function() {
                var table = this;
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }');

        $exportButtonConfig = [
            'extend' => 'collection',
            'className' => 'btn btn-light-secondary dropdown-toggle',
            'text' => '<i class="ti ti-download me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Export"></i>',
            'buttons' => [
                [
                    'extend' => 'print',
                    'text' => '<i class="fas fa-print me-2"></i> ' . __('Print'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'csv',
                    'text' => '<i class="fas fa-file-csv me-2"></i> ' . __('CSV'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'excel',
                    'text' => '<i class="fas fa-file-excel me-2"></i> ' . __('Excel'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
            ],
        ];

        $buttonsConfig = array_merge([
            $exportButtonConfig,
            [
                'extend' => 'reset',
                'className' => 'btn btn-light-danger',
            ],
            [
                'extend' => 'reload',
                'className' => 'btn btn-light-warning',
            ],
        ]);

        $dataTable->parameters([
            "dom" =>  "
        <'dataTable-top'<'dataTable-dropdown page-dropdown'l><'dataTable-botton table-btn dataTable-search tb-search  d-flex justify-content-end gap-2'Bf>>
        <'dataTable-container'<'col-sm-12'tr>>
        <'dataTable-bottom row'<'col-5'i><'col-7'p>>",
            'buttons' => $buttonsConfig,
            "drawCallback" => 'function( settings ) {
                var tooltipTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=tooltip]")
                  );
                  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                  });
                  var popoverTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=popover]")
                  );
                  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                  });
                  var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                  var toastList = toastElList.map(function (toastEl) {
                    return new bootstrap.Toast(toastEl);
                  });
            }'
        ]);

        $dataTable->language([
            'buttons' => [
                'create' => __('Create'),
                'export' => __('Export'),
                'print' => __('Print'),
                'reset' => __('Reset'),
                'reload' => __('Reload'),
                'excel' => __('Excel'),
                'csv' => __('CSV'),
            ]
        ]);

        return $dataTable;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $column = [
            Column::make('id')->visible(false)->searchable(false)->printable(false)->exportable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('user_id')->title(__('User')),
            Column::make('category_id')->title(__('Category')),
            Column::make('amount')->title(__('Amount')),
            Column::make('request_date')->title(__('Request Date')),
            Column::make('approved_date')->title(__('Approved Date')),
            Column::make('approved_by')->title(__('Approved By')),
            Column::make('description')->title(__('Description')),
            Column::make('status')->title(__('Status')),
        ];
        if (\Laratrust::hasPermission('reimbursement edit') || \Laratrust::hasPermission('reimbursement delete')) {
            $action = [
                Column::computed('action')
                    ->title(__('Action'))
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)

            ];
            $column = array_merge($column, $action);
        }
        return $column;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
