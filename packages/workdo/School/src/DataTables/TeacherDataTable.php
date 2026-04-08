<?php

namespace Workdo\School\DataTables;

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
use App\Models\User;

class TeacherDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['branches_name', 'departments_name' , 'designations_name' , 'company_doj'];

        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('branches_name', function (User $employee) {
                return $employee->branches_name ?? '-';
            })
            ->editColumn('departments_name', function (User $employee) {
                return $employee->departments_name ?? '-';
            })
            ->editColumn('designations_name', function (User $employee) {
                return $employee->designations_name ?? '-';
            })
            ->editColumn('company_doj', function (User $employee) {
                return !empty($employee->company_doj) ? company_date_formate($employee->company_doj) : '-';
            })
            ->filterColumn('company_doj', function ($query, $keyword) {
                $query->whereHas('company_doj', function ($q) use ($keyword) {
                    $q->where('employees.company_doj', 'like', "%$keyword%");
                });
            });
            if (\Laratrust::hasPermission('school_employee edit') ||
            \Laratrust::hasPermission('school_employee delete')) {
                $dataTable->addColumn('action', function (User $employee) {
                    return view('school::employee.action', compact('employee'));
                });
                $rowColumn[] = 'action';
            }
            return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
   public function query(User $model): QueryBuilder
{
    if (!in_array(\Auth::user()->type, \Auth::user()->not_emp_type)) {
        return $model->where('users.workspace_id', getActiveWorkSpace())
            ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
            ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
            ->where('users.id', \Auth::user()->id)
            ->select(
                'users.id as user_id',
                'users.name as name',
                'users.email as email',
                'employees.id as employee_pk_id',
                'employees.employee_id as employee_code',
                'employees.dob',
                'employees.gender',
                'employees.phone',
                'employees.address',
                'employees.branch_id',
                'employees.department_id',
                'employees.designation_id',
                'employees.company_doj',
                'employees.documents',
                'employees.account_holder_name',
                'employees.account_number',
                'employees.bank_name',
                'employees.bank_identifier_code',
                'employees.branch_location',
                'employees.tax_payer_id',
                'employees.workspace',
                'employees.created_by',
                'employees.created_at',
                'employees.updated_at',
                'branches.name as branches_name',
                'departments.name as departments_name',
                'designations.name as designations_name'
            );
    } elseif (Auth::user()->isAbleTo('school_employee manage')) {
        return $model->where('users.workspace_id', getActiveWorkSpace())
            ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
            ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
            ->where('users.type', 'staff')
            ->where('users.created_by', creatorId())
            ->select(
                'users.id as user_id',
                'users.name as name',
                'users.email as email',
                'employees.id as employee_pk_id',
                'employees.employee_id as employee_code',
                'employees.dob',
                'employees.gender',
                'employees.phone',
                'employees.address',
                'employees.branch_id',
                'employees.department_id',
                'employees.designation_id',
                'employees.company_doj',
                'employees.documents',
                'employees.account_holder_name',
                'employees.account_number',
                'employees.bank_name',
                'employees.bank_identifier_code',
                'employees.branch_location',
                'employees.tax_payer_id',
                'employees.workspace',
                'employees.created_by',
                'employees.created_at',
                'employees.updated_at',
                'branches.name as branches_name',
                'departments.name as departments_name',
                'designations.name as designations_name'
            );
    }
}

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('teacher-table')
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
            Column::make('user_id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('name')->title(__('Name')),
            Column::make('email')->title(__('Email')),
            Column::make('branches_name')->title(isset($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'))->name('branches.name'),
            Column::make('departments_name')->title(isset($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'))->name('departments.name'),
            Column::make('designations_name')->title(isset($company_settings['hrm_designation_name']) ? $company_settings['hrm_designation_name'] : __('Designation'))->name('designations.name'),
            Column::make('company_doj')->title(__('Date Of Joining'))->name('employees.company_doj'),
        ];
        if (\Laratrust::hasPermission('school_employee edit') ||
        \Laratrust::hasPermission('school_employee delete')) {
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
        return 'Teacher_' . date('YmdHis');
    }
}
