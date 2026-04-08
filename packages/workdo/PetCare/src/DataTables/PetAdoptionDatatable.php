<?php

namespace Workdo\PetCare\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\PetCare\Entities\PetAdoption;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PetAdoptionDatatable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['adoption_number', 'pet_image', 'pet_name', 'species', 'breed', 'adoption_amount', 'availability'];

        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('adoption_number', function (PetAdoption $pet_adoption) {
                if (!empty($pet_adoption->adoption_number)) {
                    if (\Laratrust::hasPermission('pet_adoption show')) {
                        $url = route('pet.adoption.show', \Illuminate\Support\Facades\Crypt::encrypt($pet_adoption->id));
                        $adoption_number = PetAdoption::PetAdoptionNumberFormat($pet_adoption->adoption_number);
                        $html = '<a href="#"  data-url="' . $url . '" class="btn btn-outline-primary" data-size="md"
                                data-ajax-popup="true" data-title="View Adoption Details"
                                data-bs-whatever="View Adoption Details">'
                            . $adoption_number .
                            '</a>';
                        return $html;
                    } else {
                        $adoption_number = PetAdoption::PetAdoptionNumberFormat($pet_adoption->adoption_number);
                        $html = '<a href="#" class="btn btn-outline-primary">' . $adoption_number . '</a>';
                        return $html;
                    }
                } else {
                    $html = '--';
                    return $html;
                }
            })
            ->editColumn('pet_image', function (PetAdoption $pet_adoption) {
                if (!empty($pet_adoption->pet_image)) {
                    if (check_file($pet_adoption->pet_image)) {
                        $fileUrl = get_file($pet_adoption->pet_image);
                    } else {
                        $fileUrl = asset('packages/workdo/PetCare/src/Resources/assets/image/default.png');
                    }
                    $html =
                        '<div class="image-fixsize">
                            <a href="' . $fileUrl . '" target="_blank">
                                <img id="image" src="' . $fileUrl . '" class="rounded border-2 border border-primary" >
                            </img>
                        </div>';

                    return $html;
                } else {
                    return '<span>-</span>';
                }
            })
            ->editColumn('pet_name', function (PetAdoption $pet_adoption) {
                return isset($pet_adoption->pet_name) ? $pet_adoption->pet_name : '-';
            })
            ->editColumn('species', function (PetAdoption $pet_adoption) {
                return isset($pet_adoption->species) ? $pet_adoption->species : '-';
            })
            ->editColumn('breed', function (PetAdoption $pet_adoption) {
                return isset($pet_adoption->breed) ? $pet_adoption->breed : '-';
            })
            ->editColumn('adoption_amount', function (PetAdoption $pet_adoption) {
                return currency_format_with_sym($pet_adoption->adoption_amount);
            })
            ->editColumn('availability', function (PetAdoption $pet_adoption) {
                $color = 'secondary';
                if ($pet_adoption->availability === 'available_now') {
                    $color = 'success';
                } elseif ($pet_adoption->availability === 'coming_soon') {
                    $color = 'warning';
                } elseif ($pet_adoption->availability === 'adopted') {
                    $color = 'primary';
                } elseif ($pet_adoption->availability === 'not_available') {
                    $color = 'danger';
                }
                $label = PetAdoption::$availability[$pet_adoption->availability] ?? ucwords(str_replace('_', ' ', $pet_adoption->availability));
                return '<span class="badge fix_badges bg-' . $color . ' p-2 px-3">' . $label . '</span>';
            })
            ->filterColumn('adoption_number', function ($query, $keyword) {
                $query->whereRaw("CONCAT('#PAD', LPAD(pet_adoptions.adoption_number, 5, '0')) LIKE ?", ["%$keyword%"]);
            });
        if (
            \Laratrust::hasPermission('pet_adoption delete') ||
            \Laratrust::hasPermission('pet_adoption edit') ||
            \Laratrust::hasPermission('pet_adoption show')
        ) {
            $dataTable->addColumn('action', function ($pet_adoption) {
                return view('pet-care::pet_adoption.action', compact('pet_adoption'));
            });
            $rowColumn[] = 'action';
        }

        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PetAdoption $model): QueryBuilder
    {
        $query = $model->select('pet_adoptions.*')->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('pet-adoption-table')
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
            Column::make('adoption_number')->title(__('Adoption Id')),
            Column::make('pet_image')->title(__('Image')),
            Column::make('pet_name')->title(__('Pet Name')),
            Column::make('species')->title(__('Species')),
            Column::make('breed')->title(__('Breed')),
            Column::make('adoption_amount')->title(__('Adoption Amount')),
            Column::make('availability')->title(__('Availability Status')),
        ];
        if (
            \Laratrust::hasPermission('pet_adoption delete') ||
            \Laratrust::hasPermission('pet_adoption edit') ||
            \Laratrust::hasPermission('pet_adoption show')
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
        return 'PetAdoption_' . date('YmdHis');
    }
}
