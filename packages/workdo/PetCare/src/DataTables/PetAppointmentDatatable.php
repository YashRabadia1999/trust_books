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

class PetAppointmentDatatable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['appointment_number', 'owner_name', 'assigned_staff_id', 'appointment_date', 'appointment_status'];
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
            ->editColumn('assigned_staff_id', function (PetAppointment $pet_appointment) {
                return isset($pet_appointment->assigned_staff_name) ? $pet_appointment->assigned_staff_name : '-';
            })
            ->editColumn('appointment_date', function (PetAppointment $pet_appointment) {
                return isset($pet_appointment->appointment_date) ? company_date_formate($pet_appointment->appointment_date) : '-';
            })
            ->editColumn('appointment_status', function (PetAppointment $pet_appointment) {
                if ($pet_appointment->appointment_status == 'pending') {
                    $html = '<span class="badge bg-warning p-2 px-3">' . ucwords(str_replace('_', ' ', $pet_appointment->appointment_status)) . '</span>';
                } else if ($pet_appointment->appointment_status == 'approved') {
                    $html = '<span class="badge bg-success p-2 px-3">' . ucwords(str_replace('_', ' ', $pet_appointment->appointment_status)) . '</span>';
                } else if ($pet_appointment->appointment_status == 'rejected') {
                    $html = '<span class="badge bg-danger p-2 px-3">' . ucwords(str_replace('_', ' ', $pet_appointment->appointment_status)) . '</span>';
                } else if ($pet_appointment->appointment_status == 'completed') {
                    $html = '<span class="badge bg-primary p-2 px-3">' . ucwords(str_replace('_', ' ', $pet_appointment->appointment_status)) . '</span>';
                } else {
                    $html = '<span class="badge bg-secondary p-2 px-3">' . ucwords(str_replace('_', ' ', $pet_appointment->appointment_status)) . '</span>';
                }
                return $html;
            })
            ->filterColumn('appointment_date', function ($query, $keyword) {
                try {
                    if (\Carbon\Carbon::hasFormat($keyword, 'd-m-Y')) {
                        // Full date 'd-m-Y'
                        $date = \Carbon\Carbon::createFromFormat('d-m-Y', $keyword)->format('Y-m-d');
                        return $query->where('appointment_date', 'LIKE', "%$date%");
                    } elseif (\Carbon\Carbon::hasFormat($keyword, 'm-Y')) {
                        // Month-Year 'm-Y'
                        $date = \Carbon\Carbon::createFromFormat('m-Y', $keyword)->format('Y-m');
                        return $query->where('appointment_date', 'LIKE', "%$date%");
                    } elseif (\Carbon\Carbon::hasFormat($keyword, 'd-m')) {
                        // Day-Month 'd-m'
                        $date = \Carbon\Carbon::createFromFormat('d-m', $keyword)->format('m-d');
                        return $query->where('appointment_date', 'LIKE', "%$date%");
                    } else {
                        // Separate day, month, and year checks
                        $hasDay = false;
                        $hasMonth = false;
                        $hasYear = false;

                        if (\Carbon\Carbon::hasFormat($keyword, 'd') && strlen($keyword) <= 2) {
                            $day = \Carbon\Carbon::createFromFormat('d', $keyword)->format('d');
                            $query->whereRaw('DAY(appointment_date) = ?', [$day]);
                            $hasDay = true;
                        }
                        if (\Carbon\Carbon::hasFormat($keyword, 'm') && strlen($keyword) <= 2) {
                            $month = \Carbon\Carbon::createFromFormat('m', $keyword)->format('m');
                            $query->orWhereRaw('MONTH(appointment_date) = ?', [$month]);
                            $hasMonth = true;
                        }
                        if (preg_match('/^\d{4}$/', $keyword)) {
                            $year = \Carbon\Carbon::createFromFormat('Y', $keyword)->format('Y');
                            $query->orWhereRaw('YEAR(appointment_date) = ?', [$year]);
                            $hasYear = true;
                        }

                        // Combine conditions to ensure proper logic
                        if ($hasDay || $hasMonth || $hasYear) {
                            return $query;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Invalid date format: ' . $keyword);
                }
            })
            ->filterColumn('owner_name', function ($query, $keyword) {
                $query->where('pet_owners.owner_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('assigned_staff_id', function ($query, $keyword) {
                $query->where('users.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('appointment_status', function ($query, $keyword) {
                $query->where('pet_appointments.appointment_status', 'like', "%{$keyword}%");
            });

        if (
            \Laratrust::hasPermission('pet_appointments delete') ||
            \Laratrust::hasPermission('pet_appointments edit') ||
            \Laratrust::hasPermission('pet_appointments show')
        ) {
            $dataTable->addColumn('action', function ($pet_appointment) {
                return view('pet-care::pet_appointments.action', compact('pet_appointment'));
            });
            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PetAppointment $model, Request $request): QueryBuilder
    {
        if (Auth::user()->type == 'company') {
            return $model
                ->select('pet_appointments.*', 'users.name as assigned_staff_name', 'pet_owners.owner_name')
                ->join('pet_owners', 'pet_appointments.pet_owner_id', '=', 'pet_owners.id')
                ->leftJoin('users', 'pet_appointments.assigned_staff_id', '=', 'users.id')
                ->where('pet_appointments.workspace', getActiveWorkSpace())
                ->where('pet_appointments.created_by', creatorId());
        } else {
            return $model
                ->select('pet_appointments.*', 'users.name as assigned_staff_name', 'pet_owners.owner_name')
                ->join('pet_owners', 'pet_appointments.pet_owner_id', '=', 'pet_owners.id')
                ->leftJoin('users', 'pet_appointments.assigned_staff_id', '=', 'users.id')
                ->where('pet_appointments.workspace', getActiveWorkSpace())
                ->where('pet_appointments.assigned_staff_id', Auth::user()->id);
        }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('pet-appointment-table')
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
            Column::make('assigned_staff_id')->title(__('Assigned Staff')),
            Column::make('appointment_date')->title(__('Date Of Appointment')),
            Column::make('appointment_status')->title(__('Appointment Status')),
        ];
        if (
            \Laratrust::hasPermission('pet_appointments delete') ||
            \Laratrust::hasPermission('pet_appointments edit') ||
            \Laratrust::hasPermission('pet_appointments show')
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
        return 'PetAppointment_' . date('YmdHis');
    }
}
