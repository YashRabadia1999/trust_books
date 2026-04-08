<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{ __('Package Icon') }}</th>
                        <td>
                            @if ($petGroomingPackage->package_icon)
                                <span class="{{ $petGroomingPackage->package_icon }} fs-3"></span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Package Name') }}</th>
                        <td>{{ !empty($petGroomingPackage->package_name) ? $petGroomingPackage->package_name : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Package Amount') }}</th>
                        <td>{{ !empty($petGroomingPackage->total_package_amount) ? currency_format_with_sym($petGroomingPackage->total_package_amount) : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Package Features') }}</th>
                        <td>
                            @if (!empty($petGroomingPackage->package_features))
                                @foreach (explode(',', $petGroomingPackage->package_features) as $package_feature)
                                    <span class="badge p-2 px-3 bg-primary">{{ trim($package_feature) }}</span>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Description') }}</th>
                        <td>
                            <div class="text-wrap text-break text-justify">
                                {{ !empty($petGroomingPackage->description) ? $petGroomingPackage->description : '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Services') }}</th>
                        <td>
                            @if ($packageServices->count() >= 1)
                                @foreach ($packageServices as $service)
                                    <li>
                                        <span>{{ $service->service_name }}</span>
                                        <span class="mx-2">-</span>
                                        <span>{{ currency_format_with_sym($service->pivot->service_price) }}</span>
                                    </li>
                                @endforeach
                            @else
                                <span>- -</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Vaccines') }}</th>
                        <td>
                            @if ($packageVaccines->count() >= 1)
                                @foreach ($packageVaccines as $vaccine)
                                    <li>
                                        <span>{{ $vaccine->vaccine_name }}</span>
                                        <span class="mx-2">-</span>
                                        <span>{{ currency_format_with_sym($vaccine->pivot->vaccine_price) }}</span>
                                    </li>
                                @endforeach
                            @else
                                <span>- -</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
