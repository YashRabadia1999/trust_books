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
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\SchoolParent;

class StudentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['student_gender' , 'roll_number' , 'class_name' , 'school_grade_name' ,'parent_name' , 'relation'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('student_gender', function (User $student) {
                return isset($student->schoolStudent)
                ? (isset($student->schoolStudent->student_gender)
                    ? $student->schoolStudent->student_gender
                    : (isset($student->admission->gender)
                        ? $student->admission->gender
                        : '-')
                )
                : '-';
            })
            ->filterColumn('student_gender', function ($query, $keyword) {
                $query->whereHas('schoolStudent', function ($q) use ($keyword) {
                    $q->where('student_gender', 'like', "%$keyword%");
                });
            })
            ->orderColumn('student_gender', function ($query, $order) {
                $query->leftJoin('school_students', 'school_students.user_id', '=', 'users.id')
                ->orderBy('school_students.student_gender', $order);
            })
            ->editColumn('roll_number', function (User $student) {
                return isset($student->schoolStudent) ? $student->schoolStudent->roll_number : '-' ;
            })
            ->filterColumn('roll_number', function ($query, $keyword) {
                $query->whereHas('schoolStudent', function ($q) use ($keyword) {
                    $q->where('roll_number', 'like', "%$keyword%");
                });
            })
            ->editColumn('class_name', function (User $student) {
                return isset($student->schoolStudent) ? isset($student->schoolStudent->class) ? $student->schoolStudent->class->class_name : '-' : '-' ;
            })
            ->filterColumn('class_name', function ($query, $keyword) {
                $query->whereHas('schoolStudent.class', function ($q) use ($keyword) {
                    $q->where('class_name', 'like', "%$keyword%");
                });
            })
            ->editColumn('school_grade_name', function (User $student) {
                return isset($student->schoolStudent) ? isset($student->schoolStudent->grade) ? $student->schoolStudent->grade->grade_name : '-': '-'  ;
            })
            ->filterColumn('school_grade_name', function ($query, $keyword) {
                $query->whereHas('schoolStudent.grade', function ($q) use ($keyword) {
                    $q->where('grade_name', 'like', "%$keyword%");
                });
            })
            ->editColumn('parent_name', function (User $student) {
                $father_name = isset($student->schoolStudent)
                    ? (isset($student->schoolStudent->father_name)
                        ? $student->schoolStudent->father_name
                        : (isset($student->admission->father_name)
                            ? $student->admission->father_name
                            : '-')
                      )
                    : '-';

                $mother_name = isset($student->schoolStudent)
                    ? (isset($student->schoolStudent->mother_name)
                        ? $student->schoolStudent->mother_name
                        : (isset($student->admission->mother_name)
                            ? $student->admission->mother_name
                            : '-')
                      )
                    : '-';

                return $father_name . ', ' . $mother_name;
            })
            ->filterColumn('parent_name', function ($query, $keyword) {
                $query->whereHas('schoolStudent', function ($q) use ($keyword) {
                    $q->where('father_name', 'like', "%$keyword%")->orWhere('mother_name', 'like', "%$keyword%");
                })
                ->orWhereHas('admission', function ($q) use ($keyword) {
                    $q->where('father_name', 'like', "%$keyword%")->orWhere('mother_name', 'like', "%$keyword%");
                });
            })
            ->editColumn('relation', function (User $student) {
                $father = isset($student->schoolStudent->father_name) || isset($student->admission->father_name)  ? 'Father' : '' ;

                $mother = isset($student->schoolStudent->mother_name) || isset($student->admission->mother_name) ? 'Mother' : '';
                return $father . ', ' . $mother;

            })
            ->filterColumn('relation', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    if (stripos('father', $keyword) !== false) {
                        $q->whereHas('schoolStudent', function ($q) {
                            $q->whereNotNull('father_name');
                        })->orWhereHas('admission', function ($q) {
                            $q->whereNotNull('father_name');
                        });
                    }
                    if (stripos('mother', $keyword) !== false) {
                        $q->whereHas('schoolStudent', function ($q) {
                            $q->whereNotNull('mother_name');
                        })->orWhereHas('admission', function ($q) {
                            $q->whereNotNull('mother_name');
                        });
                    }
                });
            });
            if (\Laratrust::hasPermission('school_student edit') ||
            \Laratrust::hasPermission('school_student delete') ||
            \Laratrust::hasPermission('school_student show')) {
                $dataTable->addColumn('action', function (User $student) {
                    return view('school::student.action', compact('student'));
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
        if (Auth::user()->type == 'student') {
            return $model->where('id', Auth::id())
            ->where('workspace_id', getActiveWorkSpace())
            ->where('type', 'student')
            ->with([
                'schoolStudent' => function ($query) {
                    $query->with(['class', 'grade']);
                },
                'admission'
            ]);
        } elseif (Auth::user()->type == 'parent') {
            $parent = SchoolParent::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->where('user_id', Auth::user()->id)->first();
            $student = SchoolStudent::whereRaw("FIND_IN_SET($parent->user_id, parent_id)")->first();
            return $model->where('users.id', $student->user_id)
                ->where('workspace_id', getActiveWorkSpace())
                ->where('type', 'student')
                ->with([
                    'schoolStudent' => function ($query) {
                        $query->with(['class', 'grade']);
                    },
                    'admission'
                ]);
        } else {
            return $model->where('workspace_id', getActiveWorkSpace())
            ->where('type', 'student')
            ->with([
                'schoolStudent' => function ($query) {
                    $query->with(['class', 'grade']);
                },
                'admission'
            ]);
        }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('student-table')
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
        $column =  [
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('name')->title(__('Name')),
            Column::make('email')->title(__('Email')),
            Column::make('mobile_no')->title(__('Contact')),
            Column::make('student_gender')->title(__('Gender'))->orderable(false),
            Column::make('roll_number')->title(__('Student Number'))->orderable(false),
            Column::make('class_name')->title(__('Current Class'))->orderable(false),
            Column::make('school_grade_name')->title(__('Grade'))->orderable(false),
            Column::make('parent_name')->title(__('Parent'))->orderable(false),
            Column::make('relation')->title(__('Relation'))->orderable(false),
        ];
        if (\Laratrust::hasPermission('school_student edit') ||
        \Laratrust::hasPermission('school_student delete') ||
        \Laratrust::hasPermission('school_student show')) {
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
        return 'Students_' . date('YmdHis');
    }
}



