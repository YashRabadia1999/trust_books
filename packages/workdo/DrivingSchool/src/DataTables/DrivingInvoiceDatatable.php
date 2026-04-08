<?php

namespace Workdo\DrivingSchool\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Workdo\DrivingSchool\Entities\DrivingInvoice;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DrivingInvoiceDatatable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['id', 'due_amount', 'student_id', 'issue_date', 'due_date' ,'status'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn();
        $dataTable->editColumn('id', function (DrivingInvoice $invoice) {
            $invoiceNumber = DrivingInvoice::invoiceNumberFormat($invoice->id);

            if (\Laratrust::hasPermission('drivinginvoice show')) {
                $url = route('drivinginvoice.show', \Crypt::encrypt($invoice['id']));
                return '<a href="' . $url . '" class="btn btn-outline-primary">' . $invoiceNumber . '</a>';
            } else {
                return $invoiceNumber;
            }
        })
        ->addColumn('due_amount', function (DrivingInvoice $invoice) {
            return currency_format_with_sym($invoice->getDue());
        });
        $dataTable = $dataTable->editColumn('student_id',  function ($row) {
            return $row->student_name;
        })
        ->editColumn('issue_date', function (DrivingInvoice $invoice) {
            return company_date_formate($invoice->issue_date);
        })
        ->editColumn('due_date', function (DrivingInvoice $invoice) {
            $formattedDate = company_date_formate($invoice->due_date);
            if ($invoice->due_date < date('Y-m-d')) {
                return '<p class="text-danger">' . $formattedDate . '</p>';
            } else {
                return $formattedDate;
            }
        })
        ->editColumn('status', function (DrivingInvoice $invoice) {
            $status = DrivingInvoice::$statues[$invoice->status];
            if ($invoice->status == 0) {
                $html = '<span class="badge fix_badges  bg-info p-2 px-3 fixstatus">' .$status. '</span>';
            } elseif ($invoice->status == 1) {
                $html = '<span class="badge fix_badges bg-primary p-2 px-3 fixstatus">' . $status . '</span>';
            } elseif ($invoice->status == 2) {
                $html = '<span class="badge fix_badges  bg-danger p-2 px-3 fixstatus">' .$status . '</span>';
            }
            return $html;
        })
        ->filterColumn('status', function ($query, $keyword) {
            if (stripos('Draft', $keyword) !== false) {
                $query->where('status', 0);
            }
            elseif (stripos('Posted', $keyword) !== false) {
                $query->orWhere('status', 1);
            }
            elseif (stripos('Paid', $keyword) !== false) {
                $query->orWhere('status', 2);
            }
        });
        if (
            \Laratrust::hasPermission('drivinginvoice edit') ||
            \Laratrust::hasPermission('drivinginvoice delete') ||
            \Laratrust::hasPermission('drivinginvoice show')
        ) {
            $dataTable->addColumn('action', function (DrivingInvoice $invoice) {
                return view('driving-school::invoice.action', compact('invoice'));
            });

            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(DrivingInvoice $model): QueryBuilder
    {
        if (Auth::user()->type == 'student') {
            $invoice = $model->select('driving_invoices.*', 'driving_students.name as student_name')
                ->join('driving_students', 'driving_students.id', '=', 'driving_invoices.student_id')
                ->where('driving_students.user_id', Auth::user()->id)
                ->where('driving_invoices.created_by', creatorId())
                ->where('driving_invoices.workspace', getActiveWorkSpace());
        } else {
            $invoice = $model->select('driving_invoices.*', 'driving_students.name as student_name')
                ->join('driving_students', 'driving_students.id', '=', 'driving_invoices.student_id')
                ->where('driving_invoices.created_by', creatorId())
                ->where('driving_invoices.workspace', getActiveWorkSpace());
        }
        return $invoice;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('driving_invoices-table')
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
            Column::make('id')->title(__('Invoice')),
            Column::make('student_id')->title(__('Student'))->name('driving_students.name'),
            Column::make('issue_date')->title(__('Issue Date')),
            Column::make('due_date')->title(__('Due Date')),
            Column::computed('due_amount'),
            Column::make('status')->title(__('Status')),
        ];
        if (
            \Laratrust::hasPermission('drivinginvoice edit') ||
            \Laratrust::hasPermission('drivinginvoice delete') ||
            \Laratrust::hasPermission('drivinginvoice show')
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
        return 'driving_invoices_' . date('YmdHis');
    }
}
