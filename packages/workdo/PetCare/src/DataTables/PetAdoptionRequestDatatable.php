<?php

namespace Workdo\PetCare\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\PetCare\Entities\PetAdoption;
use Workdo\PetCare\Entities\PetAdoptionRequest;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PetAdoptionRequestDatatable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['adoption_request_number', 'adoption_number', 'adopter_name','email','contact_number','request_status'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('adoption_request_number', function (PetAdoptionRequest $pet_adoption_request) {
                if (!empty($pet_adoption_request->adoption_request_number)) {
                    if (\Laratrust::hasPermission('pet_adoption_request show')) {
                        $url = route('pet.adoption.request.show', \Illuminate\Support\Facades\Crypt::encrypt($pet_adoption_request->id));
                        $adoption_request_number = PetAdoptionRequest::PetAdoptionRequestNumberFormat($pet_adoption_request->adoption_request_number);
                        $html = '<a class="btn btn-outline-primary" href="' . $url . '">
                                        ' . $adoption_request_number . '
                                    </a>';
                        return $html;
                    } else {
                        $adoption_request_number = PetAdoptionRequest::PetAdoptionRequestNumberFormat($pet_adoption_request->adoption_request_number);
                        $html = '<a href="#" class="btn btn-outline-primary">' . $adoption_request_number . '</a>';
                        return $html;
                    }
                } else {
                    $html = '--';
                    return $html;
                }
            })
            ->editColumn('adoption_number', function (PetAdoptionRequest $pet_adoption_request) {
                if (!empty($pet_adoption_request->pet_adoption_id)) {
                    $adoption = $pet_adoption_request->petAdoption;
                    $adoption_number = PetAdoption::PetAdoptionNumberFormat($adoption->adoption_number);
                    if (!empty($adoption) && !empty($adoption_number)) {
                        return ' <a href="#" class="btn btn-outline-primary">' . $adoption_number . '</a>';
                    } else {
                        $html = '--';
                        return $html;
                    }
                } else {
                    $html = '--';
                    return $html;
                }
            })
            ->editColumn('adopter_name', function (PetAdoptionRequest $pet_adoption_request) {
                return isset($pet_adoption_request->adopter_name) ? $pet_adoption_request->adopter_name : '-';
            })
            ->editColumn('email', function (PetAdoptionRequest $pet_adoption_request) {
                return isset($pet_adoption_request->email) ? $pet_adoption_request->email : '-';
            })
            ->editColumn('contact_number', function (PetAdoptionRequest $pet_adoption_request) {
                return isset($pet_adoption_request->contact_number) ? $pet_adoption_request->contact_number : '-';
            })
            ->editColumn('request_status', function (PetAdoptionRequest $pet_adoption_request) {
                if ($pet_adoption_request->request_status == 'pending') {
                    $html = '<span class="badge bg-warning p-2 px-3">' . ucwords(str_replace('_', ' ', $pet_adoption_request->request_status)) . '</span>';
                } else if ($pet_adoption_request->request_status == 'approved') {
                    $html = '<span class="badge bg-success p-2 px-3">' . ucwords(str_replace('_', ' ', $pet_adoption_request->request_status)) . '</span>';
                } else if ($pet_adoption_request->request_status == 'rejected') {
                    $html = '<span class="badge bg-danger p-2 px-3">' . ucwords(str_replace('_', ' ', $pet_adoption_request->request_status)) . '</span>';
                } else if ($pet_adoption_request->request_status == 'completed') {
                    $html = '<span class="badge bg-primary p-2 px-3">' . ucwords(str_replace('_', ' ', $pet_adoption_request->request_status)) . '</span>';
                } else {
                    $html = '<span class="badge bg-secondary p-2 px-3">' . ucwords(str_replace('_', ' ', $pet_adoption_request->request_status)) . '</span>';
                }
                return $html;
            })
            ->filterColumn('adoption_request_number', function ($query, $keyword) {
                $query->whereRaw("CONCAT('#ADR', LPAD(pet_adoption_requests.adoption_request_number, 5, '0')) LIKE ?", ["%$keyword%"]);
            })
            ->filterColumn('adoption_number', function ($query, $keyword) {
                $query->join('pet_adoptions', 'pet_adoptions.id', '=', 'pet_adoption_requests.pet_adoption_id')
                      ->whereRaw("CONCAT('#PAD', LPAD(pet_adoptions.adoption_number, 5, '0')) LIKE ?", ["%$keyword%"]);
            });
            
        if (
            \Laratrust::hasPermission('pet_adoption_request delete') ||
            \Laratrust::hasPermission('pet_adoption_request edit') ||
            \Laratrust::hasPermission('pet_adoption_request show')
        ) {
            $dataTable->addColumn('action', function ($pet_adoption_request) {
                return view('pet-care::pet_adoption_request.action', compact('pet_adoption_request'));
            });
            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PetAdoptionRequest $model): QueryBuilder
    {
        return $model->newQuery()
        ->join('pet_adoptions', 'pet_adoptions.id', '=', 'pet_adoption_requests.pet_adoption_id')
        ->select('pet_adoption_requests.*') 
        ->where('pet_adoption_requests.workspace', getActiveWorkSpace())
        ->where('pet_adoption_requests.created_by', creatorId());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('pet-adoption-request-table')
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
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false)->visible(false),
            Column::make('adoption_request_number')->title(__('Adoption Request Id')),
            Column::make('adoption_number')->title(__('Pet Adoption Id')),
            Column::make('adopter_name')->title(__("Adopter's Name")),
            Column::make('email')->title(__('EMail')),
            Column::make('contact_number')->title(__('Contact Number')),
            Column::make('request_status')->title(__('Adoption Request Status')),
        ];
        if (
            \Laratrust::hasPermission('pet_adoption_request delete') ||
            \Laratrust::hasPermission('pet_adoption_request edit') ||
            \Laratrust::hasPermission('pet_adoption_request show')
        ) {
            $action = [
                Column::computed('action')->exportable(false)->printable(false)->width(60)
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
        return 'PetAdoptionRequest_' . date('YmdHis');
    }
}
