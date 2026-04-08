<?php

namespace Workdo\PetCare\DataTables;

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
use Workdo\PetCare\Entities\PetAdoptionRequestPayments;

class PetAdoptionRequestPaymentsSummaryDatatable extends DataTable
{
    protected $adoption_request_id;

    public function with(array|string $key, mixed $value = null): static
    {
        if (is_array($key)) {
            if (isset($key['adoption_request_id'])) {
                $this->adoption_request_id = $key['adoption_request_id'];
            }
        } elseif ($key === 'adoption_request_id') {
            $this->adoption_request_id = $value;
        }

        return parent::with($key, $value);
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['payer_name', 'payment_method', 'amount', 'payment_date', 'reference', 'description', 'payment_receipt'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('payer_name', function (PetAdoptionRequestPayments $pet_adoption_request_payments) {
                return isset($pet_adoption_request_payments->payer_name) ? $pet_adoption_request_payments->payer_name : '-';
            })
            ->editColumn('payment_method', function (PetAdoptionRequestPayments $pet_adoption_request_payments) {
                return isset($pet_adoption_request_payments->payment_method) ? $pet_adoption_request_payments->payment_method : '-';
            })
            ->editColumn('amount', function (PetAdoptionRequestPayments $pet_adoption_request_payments) {
                return isset($pet_adoption_request_payments->amount) ? currency_format_with_sym($pet_adoption_request_payments->amount) : '-';
            })
            ->editColumn('payment_date', function (PetAdoptionRequestPayments $pet_adoption_request_payments) {
                return isset($pet_adoption_request_payments->payment_date) ? company_date_formate($pet_adoption_request_payments->payment_date) : '-';
            })
            ->editColumn('reference', function (PetAdoptionRequestPayments $pet_adoption_request_payments) {
                return isset($pet_adoption_request_payments->reference) ? $pet_adoption_request_payments->reference : '-';
            })
            ->editColumn('description', function (PetAdoptionRequestPayments $pet_adoption_request_payments) {
                $url = route('pet.adoption.request.payments.description', $pet_adoption_request_payments->id);
                $html = '<a class="action-item" data-url="' . $url . '" data-ajax-popup="true" data-bs-toggle="tooltip" title="' . __('Description') . '" data-title="' . __('Description') . '"><i class="fa fa-comment"></i></a>';
                return $html;
            })
            ->editColumn('payment_receipt', function (PetAdoptionRequestPayments $pet_adoption_request_payments) {
                if (!empty($pet_adoption_request_payments->payment_receipt)) {
                    if (check_file($pet_adoption_request_payments->payment_receipt)) {
                        $fileUrl = get_file($pet_adoption_request_payments->payment_receipt);
                    } else {
                        $fileUrl = asset('packages/workdo/PetCare/src/Resources/assets/image/default.png');
                    }

                    $html = '
                        <div class="action-btn me-2">
                            <a href="' . $fileUrl . '" download class="mx-3 bg-primary btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Download" target="_blank">
                                <i class="ti ti-download text-white"></i>
                            </a>
                        </div>
                        <div class="action-btn">
                            <a href="' . $fileUrl . '" class="mx-3 btn bg-secondary btn-sm align-items-center" data-bs-toggle="tooltip" title="Show" target="_blank">
                                <i class="ti ti-crosshair text-white"></i>
                            </a>
                        </div>';

                    return $html;
                } else {
                    return '<span>--</span>';
                }
            });

        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PetAdoptionRequestPayments $model): QueryBuilder
    {
        $decryptedAppointmentId = \Illuminate\Support\Facades\Crypt::decrypt($this->adoption_request_id);
        return $model->where('adoption_request_id', $decryptedAppointmentId)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('adoption-request-payment-summary-table')
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
        return [
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('payer_name')->title(__('Payer Name')),
            Column::make('payment_method')->title(__('Payment Method')),
            Column::make('amount')->title(__('Amount')),
            Column::make('payment_date')->title(__('Payment Date')),
            Column::make('reference')->title(__('Reference')),
            Column::make('description')->title(__('Description')),
            Column::make('payment_receipt')->title(__('Payment Receipt')),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AdoptionRequestPaymentSummary_' . date('YmdHis');
    }
}
