<?php

namespace Workdo\PetCare\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\PetCare\Entities\PetAppointment;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PetCareBillingPaymentsDatatable extends DataTable
{
    /**  
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['appointment_number', 'owner_name', 'service_name', 'package_name', 'total_service_package_amount', 'due_amount', 'payment_status'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('appointment_number', function (PetAppointment $pet_appointment) {
                if (!empty($pet_appointment->appointment_number)) {
                    if (\Laratrust::hasPermission('pet_appointments show')) {
                        $url = route('pet.appointments.show', \Illuminate\Support\Facades\Crypt::encrypt($pet_appointment->id));
                        $appointment_number = PetAppointment::petAppointmentNumberFormat($pet_appointment->appointment_number);
                        $html = '<a class="btn btn-outline-primary" href="' . $url . '">
                                        ' . $appointment_number . '
                                    </a>';
                        return $html;
                    } else {
                        $appointment_number = PetAppointment::petAppointmentNumberFormat($pet_appointment->appointment_number);
                        $html = '<a href="#" class="btn btn-outline-primary">' . $appointment_number . '</a>';
                        return $html;
                    }
                } else {
                    $html = '--';
                    return $html;
                }
            })
            ->editColumn('owner_name', function (PetAppointment $pet_appointment) {
                return isset($pet_appointment->owner_name) ? $pet_appointment->owner_name : '-';
            })
            ->editColumn('service_name', function (PetAppointment $pet_appointment) {
                return isset($pet_appointment->service_name) ? $pet_appointment->service_name : '-';
            })
            ->editColumn('package_name', function (PetAppointment $pet_appointment) {
                return isset($pet_appointment->package_name) ? $pet_appointment->package_name : '-';
            })
            ->editColumn('total_service_package_amount', function (PetAppointment $pet_appointment) {
                return  isset($pet_appointment->total_service_package_amount) ? currency_format_with_sym($pet_appointment->total_service_package_amount) : '-';
            })
            ->editColumn('due_amount', function (PetAppointment $pet_appointment) {
                return currency_format_with_sym($pet_appointment->getDueAmount());
            })
            ->editColumn('payment_status', function (PetAppointment $pet_appointment) {

                if ($pet_appointment->getDueAmount() == 0) {
                    $class  = 'bg-primary';
                    $status =  __('Paid');
                } elseif ($pet_appointment->getDueAmount() == $pet_appointment->total_service_package_amount) {
                    $class  = 'bg-danger';
                    $status = __('Unpaid');
                } else {
                    $class  = 'bg-info';
                    $status = __('Partialy Paid');
                }
                return '<span class="badge fix_badges ' . $class . ' p-2 px-3">' . $status . '</span>';
            })

            ->filterColumn('appointment_number', function ($query, $keyword) {
                $query->where('pet_appointments.appointment_number', 'like', "%{$keyword}%");
            })

            ->filterColumn('owner_name', function ($query, $keyword) {
                $query->where('pet_owners.owner_name', 'like', "%{$keyword}%");
            })

            ->filterColumn('service_name', function ($query, $keyword) {
                $query->where('pet_services.service_name', 'like', "%{$keyword}%");
            })

            ->filterColumn('package_name', function ($query, $keyword) {
                $query->where('pet_grooming_packages.package_name', 'like', "%{$keyword}%");
            })

            ->filterColumn('total_service_package_amount', function ($query, $keyword) {
                $query->where('pet_appointments.total_service_package_amount', 'like', "%{$keyword}%");
            });

        if (
            \Laratrust::hasPermission('billing_payments create') ||
            \Laratrust::hasPermission('billing_payments show')
        ) {
            $dataTable->addColumn('action', function ($pet_appointment) {
                return view('pet-care::billing_payments.action', compact('pet_appointment'));
            });
            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PetAppointment $model): QueryBuilder
    {
        return $model
            ->select('pet_appointments.*', 'pet_owners.owner_name')
            ->join('pet_owners', 'pet_appointments.pet_owner_id', '=', 'pet_owners.id')
            ->whereIn('pet_appointments.appointment_status', ['approved', 'completed'])
            ->where('pet_appointments.workspace', getActiveWorkSpace())
            ->where('pet_appointments.created_by', creatorId());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('petcare-billing-payments-table')
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
            Column::make('id')->name('pet_appointments.id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('appointment_number')->title(__('Appointment Id')),
            Column::make('owner_name')->title(__('Appointment Creator')),
            Column::make('total_service_package_amount')->title(__('Total Amount')),
            Column::make('due_amount')->title(__('Due Amount'))->searchable(false)->orderable(false),
            Column::make('payment_status')->title(__('Payment Status'))->searchable(false)->orderable(false),
        ];
        if (
            \Laratrust::hasPermission('billing_payments create') ||
            \Laratrust::hasPermission('billing_payments show')
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
        return 'PetCareBillingPayments_' . date('YmdHis');
    }
}
