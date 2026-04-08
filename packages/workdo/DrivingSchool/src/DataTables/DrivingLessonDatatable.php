<?php

namespace Workdo\DrivingSchool\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\DrivingSchool\Entities\DrivingClass;
use Workdo\DrivingSchool\Entities\DrivingLesson;
use Workdo\DrivingSchool\Entities\DrivingStudent;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DrivingLessonDatatable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['id', 'status'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn();
            $dataTable->editColumn('id', function (drivinglesson $lesson) {
                $lessonNumber = DrivingLesson::lessonNumberFormat($lesson->id);

                if (\Laratrust::hasPermission('drivingclass show')) {
                    $url = route('lesson.show', \Crypt::encrypt($lesson['id']));
                    return '<a href="' . $url . '" class="btn btn-outline-primary">' . $lessonNumber . '</a>';
                } else {
                    return $lessonNumber;
                }
            })
            ->editColumn('status', function (drivinglesson $lesson) {
                $status = DrivingLesson::$statues[$lesson->status];
                if ($lesson->status == 0) {
                    $html = '<span class="badge fix_badges bg-primary p-2 px-3 status">' . $status . '</span>';
                } elseif ($lesson->status == 1) {
                    $html = '<span class="badge fix_badges bg-info p-2 px-3 status">' . $status . '</span>';
                } elseif ($lesson->status == 2) {
                    $html = '<span class="badge fix_badges bg-secondary p-2 px-3 status">' . $status . '</span>';
                } elseif ($lesson->status == 3) {
                    $html = '<span class="badge fix_badges bg-danger p-2 px-3 status">' . $status . '</span>';
                }
                return $html;
            })
            ->filterColumn('status', function ($query, $keyword) {
                if (stripos('Draft', $keyword) !== false) {
                    $query->where('status', 0);
                } elseif (stripos('Start', $keyword) !== false) {
                    $query->orWhere('status', 1);
                } elseif (stripos('Complete', $keyword) !== false) {
                    $query->orWhere('status', 2);
                } elseif (stripos('Cancel', $keyword) !== false) {
                    $query->orWhere('status', 3);
                }
            });
        if (\Laratrust::hasPermission('drivinglesson show')) {
            $dataTable->addColumn('action', function (DrivingLesson $lesson) {
                return view('driving-school::lesson.action', compact('lesson'));
            });

            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Request $request): QueryBuilder
    {
        $user = Auth::user();
        $currentMonth = date('m');
        $currentYear = date('Y');
        $startMonth = $request->start_month ? date('m', strtotime($request->start_month)) : $currentMonth;
        $startYear = $request->start_month ? date('Y', strtotime($request->start_month)) : $currentYear;
        $endMonth = $request->end_month ? date('m', strtotime($request->end_month)) : $currentMonth;
        $endYear = $request->end_month ? date('Y', strtotime($request->end_month)) : $currentYear;

        $driving_lessons = DrivingLesson::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->where(function ($query) use ($startMonth, $startYear, $endMonth, $endYear) {
                $query->where(function ($q) use ($startMonth, $startYear) {
                    $q->whereMonth('start_date_time', '=', $startMonth)
                        ->whereYear('start_date_time', '=', $startYear);
                })
                    ->orWhere(function ($q) use ($endMonth, $endYear) {
                        $q->whereMonth('end_date_time', '=', $endMonth)
                            ->whereYear('end_date_time', '=', $endYear);
                    });
            });

        if ($user->type == 'company') {
            $driving_lessons->where('created_by', creatorId());
        } elseif ($user->type == 'student') {
            $user = DrivingStudent::where('user_id', $user->id)->first();
            $driving_lessons->whereIn('class_id', DrivingClass::whereRaw('FIND_IN_SET(?, student_id) > 0', [$user->id])->pluck('id'));
        } elseif ($user->type == 'staff') {
            $user = User::where('id', $user->id)->first();
            $driving_lessons->whereIn('class_id', DrivingClass::whereRaw('FIND_IN_SET(?, teacher_id) > 0', [$user->id])->pluck('id'));
        } elseif ($user->type == 'driving student') {
            $student = DrivingStudent::where('user_id', $user->id)->first();
            $driving_lessons = DrivingLesson::whereIn('class_id', function ($query) use ($student) {
                $query->select('id')
                    ->from('driving_classes')
                    ->whereRaw("FIND_IN_SET(?, student_id) > 0", [$student->id]);
            });
        }

        return $driving_lessons;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('driving_lessons-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => 'function(d) {
                    var start_month = $("input[name=start_month]").val();
                    d.start_month = start_month

                    var end_month = $("input[name=end_month]").val();
                    d.end_month = end_month

                }',
            ])
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
                $("body").on("click", "#applyfilter", function() {

                    if (!$("input[name=start_month]").val() && !$("input[name=end_month]").val()) {
                        toastrs("Error!", "Please select Atleast One Filter ", "error");
                        return;
                    }

                    $("#driving_lessons-table").DataTable().draw();
                });

                $("body").on("click", "#clearfilter", function() {
                    $("input[name=start_month]").val("")
                    $("input[name=end_month]").val("")
                    $("#driving_lessons-table").DataTable().draw();
                });

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
            Column::make('id')->title(__('Lessons')),
            Column::make('name')->title(__('Lesson Name')),
            Column::make('start_date_time')->title(__('Start Date & Time')),
            Column::make('end_date_time')->title(__('End Date & Time')),
            Column::make('status')->title(__('Status')),
        ];
        if (\Laratrust::hasPermission('drivinglesson show')) {
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
        return 'driving_lessons_' . date('YmdHis');
    }
}
