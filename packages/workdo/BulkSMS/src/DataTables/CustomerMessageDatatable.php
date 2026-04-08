<?php

namespace Workdo\BulkSMS\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\BulkSMS\Entities\CustomerMessage;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomerMessageDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['name', 'message'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('created_at', function (CustomerMessage $message) {
                return company_date_formate($message->created_at);
            });

        if (
            Auth::user()->isAbleTo('bulksms_contact edit') ||
            Auth::user()->isAbleTo('bulksms_contact delete') ||
            Auth::user()->isAbleTo('bulksms_contact manage')
        ) {
            $dataTable->addColumn('action', function (CustomerMessage $message) {
                return view('bulk-sms::customermessage.action', compact('message'));
            });
        }

        foreach ($rowColumn as $row) {
            $dataTable->rawColumns([$row]);
        }

        return $dataTable->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Workdo\BulkSMS\Entities\CustomerMessage $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CustomerMessage $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->select('customer_messages.*');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('customer-message-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => __("_MENU_") . __('Entries Per Page'),
                "searchPlaceholder" => __('Search...'),
                "search" => "",
            ])
            ->initComplete('function() {
                var table = this;
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }');

        $exportButtonConfig = [];

        $buttonsConfig = array_merge([
            'pageLength',
        ], $exportButtonConfig);

        $dataTable->parameters([
            "dom" => "
                <'dataTable-top row'<'dataTable-dropdown page-dropdown col-lg-2 col-sm-12'l><'dataTable-botton table-btn col-lg-6 col-sm-12'B><'dataTable-search tb-search col-lg-3 col-sm-12'f>>
                <'dataTable-container'<'col-sm-12'tr>>
                <'dataTable-bottom row'<'col-sm-5'i><'col-sm-7'p>>
            ",
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
     *
     * @return array
     */
    public function getColumns(): array
    {
        $column = [
            Column::make('id')->title(__('ID'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('name')->title(__('Template Name')),
            Column::make('message')->title(__('Message Preview'))->width(400),
            Column::make('created_at')->title(__('Created At')),
        ];

        if (
            Auth::user()->isAbleTo('bulksms_contact edit') ||
            Auth::user()->isAbleTo('bulksms_contact delete') ||
            Auth::user()->isAbleTo('bulksms_contact manage')
        ) {
            $action = [
                Column::computed('action')
                    ->title(__('Action'))
                    ->class('text-end')
                    ->exportable(false)
                    ->printable(false)
                    ->searchable(false)
                    ->orderable(false)
                    ->width(150),
            ];

            $column = array_merge($column, $action);
        }

        return $column;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CustomerMessage_' . date('YmdHis');
    }
}
