<?php

namespace Workdo\DrivingSchool\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Workdo\DrivingSchool\Entities\DrivingTestHub;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DrivingTestHubDatatable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['student_id','teacher_id','test_type_id','test_result','test_date'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn();
        $dataTable = $dataTable->editColumn('student_id',  function ($row) {
            return ucwords($row->student_name);
        });
        $dataTable = $dataTable->editColumn('teacher_id',  function ($row) {
            return ucwords($row->user_name);
        });
        $dataTable = $dataTable->editColumn('test_type_id',  function ($row) {
            return ucwords($row->test_type_name);
        });
        $dataTable = $dataTable->editColumn('test_result',  function ($row) {
            return ucwords($row->test_result);
        });
        $dataTable = $dataTable->editColumn('test_date', function ($row) {
            return company_date_formate($row->test_date);
        });
        if (
            \Laratrust::hasPermission('driving testhub edit') ||
            \Laratrust::hasPermission('driving testhub delete')
        ) {
            $dataTable->addColumn('action', function (DrivingTestHub $test_hub) {
                return view('driving-school::test_hub.action', compact('test_hub'));
            });

            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(DrivingTestHub $model): QueryBuilder
    {
        $class = $model->select('driving_test_hubs.*', 'driving_students.name as student_name','driving_test_types.name as test_type_name','users.name as user_name')
            ->join('driving_students', 'driving_students.id', 'driving_test_hubs.student_id')
            ->join('driving_test_types', 'driving_test_types.id', 'driving_test_hubs.test_type_id')
            ->join('users', 'users.id', 'driving_test_hubs.teacher_id')
            ->where('users.type','staff')
            ->where('driving_test_hubs.created_by', creatorId())
            ->where('driving_test_hubs.workspace', getActiveWorkSpace());

        return $class;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('driving_test_hubs-table')
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
            Column::make('test_type_id')->title(__('Test Type'))->name('driving_test_types.name'),
            Column::make('teacher_id')->title(__('Teacher'))->name('users.name'),
            Column::make('test_date')->title(__('Test Date'))->name('driving_test_hubs.test_date'),
            Column::make('test_score')->title(__('Test Score')),
            Column::make('test_result')->title(__('Test Result')),
        ];
        if (
            \Laratrust::hasPermission('driving testhub edit') ||
            \Laratrust::hasPermission('driving testhub delete')
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
        return 'driving_test_hubs_' . date('YmdHis');
    }
}
