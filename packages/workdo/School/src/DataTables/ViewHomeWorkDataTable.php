<?php

namespace Workdo\School\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\SchoolHomework;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;

class ViewHomeWorkDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['homework', 'classroom', 'subject', 'content'];

        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('homework', function (SchoolHomework $homework) {
                if (check_file($homework->homework) == false) {
                    $path = asset('packages/workdo/School/src/Resources/assets/image/img01.jpg');
                } else {
                    $path = get_file($homework->homework);
                }
                $html = '<a class="image-fixsize" href="'. $path .'" target="_blank">
                            <img src="' . $path . '" class="rounded border-2 border border-primary">
                        </a>';
                return $html;
            })
            ->editColumn('content', function (SchoolHomework $homework) {

                $route = route('homework.content', $homework->id);
                $name = __('Content');
                $html = '<a class="action-item btn btn-sm "
                                                data-url="' . $route . '"
                                                data-ajax-popup="true" data-bs-toggle="tooltip" title="' . $name . '" data-title="' . $name . '">
                                                <i class="fa fa-comment"></i>
                                            </a>';
                return $html;
            })
            ->editColumn('classroom', function (SchoolHomework $homework) {
                return !empty($homework->className) ? $homework->className->class_name : '';
            })
            ->filterColumn('classroom', function ($query, $keyword) {
                $query->whereHas('className', function ($q) use ($keyword) {
                    $q->where('class_name', 'like', "%$keyword%");
                });
            })
            ->editColumn('subject', function (SchoolHomework $homework) {
                return !empty($homework->subjectName) ? $homework->subjectName->subject_name : '';
            })
            ->filterColumn('subject', function ($query, $keyword) {
                $query->whereHas('subjectName', function ($q) use ($keyword) {
                    $q->where('subject_name', 'like', "%$keyword%");
                });
            });

        $dataTable->addColumn('action', function (SchoolHomework $homework) {
            return view('school::homework.viewaction', compact('homework'));
        });
        $rowColumn[] = 'action';

        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SchoolHomework $model , Request $request): QueryBuilder
    {
        if (Auth::user()->type == 'staff') {
            $homework = $model->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
        } else {
            $homework = $model->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
        }

        if (!empty($request->subject)) {
            $homework = $homework->where('subject', $request->subject);
        }

        return $homework;

    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('homework-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => 'function(d) {
                    var subject = $("select[name=subject]").val();
                    d.subject = subject
                }',
            ])
            ->orderBy(0)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>',
                ],
                'lengthMenu' => "_MENU_" . __('Entries Per Page'),
                "searchPlaceholder" => __('Search...'),
                "search" => "",
                "info" => __('Showing _START_ to _END_ of _TOTAL_ entries'),
            ])
            ->initComplete('function() {
                var table = this;
                    $("body").on("click", "#applyfilter", function() {

                    if (!$("select[name=subject]").val()) {
                        toastrs("Error!", "Please select Atleast One Filter ", "error");
                        return;
                    }

                    $("#homework-table").DataTable().draw();
                });

                $("body").on("click", "#clearfilter", function() {
                    $("select[name=subject]").val("")
                    $("#homework-table").DataTable().draw();
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
            "dom" => "
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
            }',
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
            ],
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
            Column::make('homework')->title(__('Image'))->searchable(false),
            Column::make('title')->title(__('Title')),
            Column::make('classroom')->title(__('Class')),
            Column::make('subject')->title(__('Subject')),
            Column::make('submission_date')->title(__('Submission Date')),
            Column::make('content')->title(__('Content')),
        ];

        $action = [
            Column::computed('action')
                ->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)

        ];

        $column = array_merge($column, $action);

        return $column;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Homework_' . date('YmdHis');
    }
}
