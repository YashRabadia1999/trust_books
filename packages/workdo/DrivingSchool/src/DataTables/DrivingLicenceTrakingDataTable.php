<?php

namespace Workdo\DrivingSchool\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Workdo\DrivingSchool\Entities\DrivingLicenceTraking;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DrivingLicenceTrakingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['student_id','licence_type_id','test_result','test_date','application_date','licence_issue_date','licence_expiry_date'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn();
        $dataTable = $dataTable->editColumn('student_id',  function ($row) {
            return ucwords($row->student_name);
        });
        $dataTable = $dataTable->editColumn('licence_type_id',  function ($row) {
            return ucwords($row->licence_type);
        });
        $dataTable = $dataTable->editColumn('test_result',  function ($row) {
            return ucwords($row->test_result);
        });
        $dataTable = $dataTable->editColumn('test_date', function ($row) {
            return company_date_formate($row->test_date);
        });
        $dataTable = $dataTable->editColumn('application_date', function ($row) {
            return company_date_formate($row->application_date);
        });
        $dataTable = $dataTable->editColumn('licence_issue_date', function ($row) {
            return company_date_formate($row->licence_issue_date);
        });
        $dataTable = $dataTable->editColumn('licence_expiry_date', function ($row) {
            return company_date_formate($row->licence_expiry_date);
        });
        if (
            \Laratrust::hasPermission('licence traking edit') ||
            \Laratrust::hasPermission('licence traking delete')
        ) {
            $dataTable->addColumn('action', function (DrivingLicenceTraking $traking) {
                return view('driving-school::licence_traking.action', compact('traking'));
            });

            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(DrivingLicenceTraking $model): QueryBuilder
    {
        $licence_traking = $model->select('driving_licence_trackings.*', 'driving_students.name as student_name','driving_licence_types.name as licence_type')
            ->join('driving_students', 'driving_students.id', 'driving_licence_trackings.student_id')
            ->join('driving_licence_types', 'driving_licence_types.id', 'driving_licence_trackings.licence_type_id')
            ->where('driving_licence_trackings.created_by', creatorId())
            ->where('driving_licence_trackings.workspace', getActiveWorkSpace());
       return $licence_traking;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('driving_licence_trackings-table')
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
            Column::make('student_id')->title(__('Student'))->name('driving_students.name'),
            Column::make('licence_type_id')->title(__('Licence Type'))->name('driving_licence_types.name'),
            Column::make('application_date')->title(__('Application Date')),
            Column::make('test_date')->title(__('Test Date')),
            Column::make('test_result')->title(__('Test Result')),
            Column::make('licence_issue_date')->title(__('Issue Date')),
            Column::make('licence_number')->title(__('Licence Number')),
            Column::make('licence_expiry_date')->title(__('Expiry Date')),
        ];
        if (
            \Laratrust::hasPermission('licence traking edit') ||
            \Laratrust::hasPermission('licence traking delete') ||
            \Laratrust::hasPermission('licence traking show')
        ) {
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
        return 'licence_tracking_' . date('YmdHis');
    }
}
