<?php

namespace Workdo\SmsCredit\DataTables;

use Workdo\SmsCredit\Entities\SmsCreditPurchase;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SmsCreditPurchaseDatatable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function (SmsCreditPurchase $purchase) {
                return company_date_formate($purchase->created_at);
            })
            ->editColumn('amount_paid', function (SmsCreditPurchase $purchase) {
                return 'GHS ' . number_format($purchase->amount_paid, 2);
            })
            ->editColumn('status', function (SmsCreditPurchase $purchase) {
                $statusColors = [
                    'pending' => 'warning',
                    'completed' => 'success',
                    'failed' => 'danger',
                    'cancelled' => 'secondary'
                ];
                $color = $statusColors[$purchase->status] ?? 'info';
                return '<span class="badge bg-' . $color . '">' . ucfirst($purchase->status) . '</span>';
            })
            ->addColumn('action', function (SmsCreditPurchase $purchase) {
                return view('sms-credit::action', compact('purchase'));
            })
            ->rawColumns(['action', 'status']);
    }

    public function query(SmsCreditPurchase $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('client_id', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('sms-credit-purchase-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => __("_MENU_") . __('Entries Per Page'),
                "searchPlaceholder" => __('Search...'),
                "search" => "",
            ])
            ->parameters([
                "dom" => "
                                <'dataTable-top row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>
                                <'dataTable-container'<'row mt-3'<'col-sm-12'tr>>>
                                <'dataTable-bottom row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>
                                ",
                'buttons' => [],
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
                        return new bootstrap.Popover(tooltipTriggerEl);
                      });
                      var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                      var toastList = toastElList.map(function (toastEl) {
                        return new bootstrap.Toast(toastEl);
                      });
                }'
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('id')->title(__('ID'))->searchable(false),
            Column::make('created_at')->title(__('Date')),
            Column::make('transaction_id')->title(__('Transaction ID')),
            Column::make('credits_purchased')->title(__('Credits')),
            Column::make('amount_paid')->title(__('Amount')),
            Column::make('mobile_number')->title(__('Mobile Number')),
            Column::make('status')->title(__('Status')),
            Column::computed('action')
                ->title(__('Action'))
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'SmsCreditPurchase_' . date('YmdHis');
    }
}
