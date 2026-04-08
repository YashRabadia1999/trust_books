<?php

namespace Workdo\PetCare\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\PetCare\Entities\PetAdoptionRequest;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PetAdoptionRequestPaymentsDatatable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['adoption_request_number', 'adopter_name','total_adoption_amount','due_amount','payment_status'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('adoption_request_number', function (PetAdoptionRequest $pet_adoption_request) {
                if (!empty($pet_adoption_request->adoption_request_number)) {
                    if (\Laratrust::hasPermission('adoption_request_payments show')) {
                        $url = route('pet.adoption.request.show', \Illuminate\Support\Facades\Crypt::encrypt($pet_adoption_request->id));
                        $adoption_request_number = PetAdoptionRequest::petAdoptionRequestNumberFormat($pet_adoption_request->adoption_request_number);
                        $html = '<a class="btn btn-outline-primary" href="' . $url . '">
                                        ' . $adoption_request_number . '
                                    </a>';
                        return $html;
                    } else {
                        $adoption_request_number = PetAdoptionRequest::petAdoptionRequestNumberFormat($pet_adoption_request->adoption_request_number);
                        $html = '<a href="#" class="btn btn-outline-primary">' . $adoption_request_number . '</a>';
                        return $html;
                    }
                } else {
                    $html = '--';
                    return $html;
                }
            })
            ->editColumn('adopter_name', function (PetAdoptionRequest $pet_adoption_request) {
                return isset($pet_adoption_request->adopter_name) ? $pet_adoption_request->adopter_name : '-';
            })
            ->editColumn('total_adoption_amount', function (PetAdoptionRequest $pet_adoption_request) {
                $PetAdoptionAmount = isset($pet_adoption_request->petAdoption) ? $pet_adoption_request->petAdoption->adoption_amount : 0;
                return isset($PetAdoptionAmount) ? currency_format_with_sym($PetAdoptionAmount) : '-';                
            })            
            ->editColumn('due_amount', function (PetAdoptionRequest $pet_adoption_request) {
                return currency_format_with_sym($pet_adoption_request->getAdoptionRequestDueAmount());
            })
            ->editColumn('payment_status', function (PetAdoptionRequest $pet_adoption_request) {
                $PetAdoptionAmount = isset($pet_adoption_request->petAdoption) ? $pet_adoption_request->petAdoption->adoption_amount : 0;
                if ($pet_adoption_request->getAdoptionRequestDueAmount() == 0)
                {
                    $class  = 'bg-primary';
                    $status =  __('Paid');
                }
                elseif($pet_adoption_request->getAdoptionRequestDueAmount() == $PetAdoptionAmount)
                {
                    $class  = 'bg-danger';
                    $status = __('Unpaid');
                }
                else
                {
                    $class  = 'bg-info';
                    $status = __('Partialy Paid');
                }
                return '<span class="badge fix_badges '.$class.' p-2 px-3">'. $status .'</span>';
            })
            ->filterColumn('adoption_request_number', function ($query, $keyword) {
                $query->where('pet_adoption_requests.adoption_request_number', 'like', "%{$keyword}%");
            })
            
            ->filterColumn('adopter_name', function ($query, $keyword) {
                $query->where('pet_adoption_requests.adopter_name', 'like', "%{$keyword}%");
            })
            
            ->filterColumn('total_adoption_amount', function ($query, $keyword) {
                $query->whereHas('petAdoption', function ($q) use ($keyword) {
                    $q->where('adoption_amount', 'like', "%{$keyword}%");
                });
            });            
        if (
            \Laratrust::hasPermission('adoption_request_payments create') ||
            \Laratrust::hasPermission('adoption_request_payments show')
        ) {
            $dataTable->addColumn('action', function ($pet_adoption_request) {
                return view('pet-care::adoption_request_payments.action', compact('pet_adoption_request'));
            });
            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PetAdoptionRequest $model): QueryBuilder
    {
        return $model->select('pet_adoption_requests.*')->whereIn('request_status', ['approved', 'completed'])->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('adoption-request-payments-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
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
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('adoption_request_number')->title(__('Adoption Request Id')),
            Column::make('adopter_name')->title(__("Adopter's Name")),
            Column::make('total_adoption_amount')->title(__('Adoption Amount')),
            Column::computed('due_amount')->title(__('Due Amount')),
            Column::computed('payment_status')->title(__('Payment Status')),
        ];
        if (
            \Laratrust::hasPermission('adoption_request_payments create') ||
            \Laratrust::hasPermission('adoption_request_payments show')
        ) {
            $action = [
                Column::computed('action')->exportable(false)->printable(false)->width(60)
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
        return 'AdoptionRequestPayments_' . date('YmdHis');
    }
}
