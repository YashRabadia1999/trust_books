<?php

namespace Workdo\PetCare\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\PetCare\Entities\PetCareContact;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PetCareContactsDatatable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['name','email','subject','status', 'message'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('name', function (PetCareContact $contact) {
                return isset($contact->name) ? $contact->name : '-';
            })
            ->editColumn('email', function (PetCareContact $contact) {
                return isset($contact->email) ? $contact->email : '-';
            })
            ->editColumn('subject', function (PetCareContact $contact) {
                return isset($contact->subject) ? $contact->subject : '-';
            })
            ->addColumn('status', function (PetCareContact $contact) {
                $status = [
                    'new'            => 'bg-primary',
                    'in_progress'    => 'bg-warning',
                    'replied'        => 'bg-success',
                    'closed'         => 'bg-secondary',
                    'spam'           => 'bg-danger',
                ];
                $class = isset($status[$contact->status]) ? $status[$contact->status] : 'bg-primary';

                $span = '<span class="badge ' . $class . ' p-2 px-3">' . PetCareContact::$Status[$contact->status] . '</span>';

                return '<span class="d-inline-flex align-items-center">' . $span . '</span>';
            })
            ->editColumn('message', function (PetCareContact $contact) {
                $html = '<a class="action-item" data-url="' . route('petcare.contact.us.message.show', $contact->id) . '"
                        data-ajax-popup="true" data-bs-toggle="tooltip" title="' . __('Message') . '"
                        data-title="' . __('Message') . '" style="cursor: pointer;"><i class="fa fa-comment"></i></a>';
                return $html;
            })
            ->filterColumn('status', function ($query, $keyword) {
                if (stripos('Spam', $keyword) !== false) {
                    $query->where('status', 'spam');
                } elseif (stripos('Closed', $keyword) !== false) {
                    $query->orWhere('status', 'closed');
                } elseif (stripos('Replied', $keyword) !== false) {
                    $query->orWhere('status', 'replied');
                } elseif (stripos('In Progress', $keyword) !== false) {
                    $query->orWhere('status', 'in_progress');
                } elseif (stripos('New', $keyword) !== false) {
                    $query->orWhere('status', 'new');
                }
            });
        if (
            \Laratrust::hasPermission('petcare_contacts edit')
            || \Laratrust::hasPermission('petcare_contacts delete')
            || \Laratrust::hasPermission('petcare_contacts status')
        ) {
            $dataTable->addColumn('action', function ($contact) {
                return view('pet-care::contacts.action', compact('contact'));
            });
            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PetCareContact $model): QueryBuilder
    {
        $query = $model->select('pet_care_contacts.*')->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('petcare-contacts-table')
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
            Column::make('name')->title(__('Name')),
            Column::make('email')->title(__('Email')),
            Column::make('subject')->title(__('Subject')),
            Column::make('status')->title(__('Status')),
            Column::make('message')->title(__('Message')),
        ];
        if (
            \Laratrust::hasPermission('petcare_contacts edit')|| \Laratrust::hasPermission('petcare_contacts delete')|| \Laratrust::hasPermission('petcare_contacts status')) {
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
        return 'PetCareContacts_' . date('YmdHis');
    }
}
