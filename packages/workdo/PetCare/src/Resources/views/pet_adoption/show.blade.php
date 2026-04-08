<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table modal-table">
                <tr role="row">
                    <th>{{ __('Adoption Id') }}</th>
                    <td>{{ isset($petAdoption->adoption_number) ? Workdo\PetCare\Entities\PetAdoption::PetAdoptionNumberFormat($petAdoption->adoption_number) : '-' }}</td>
                </tr>
                <tr role="row">
                    <th>{{ __('Pet Name') }}</th>
                    <td>{{ $petAdoption->pet_name ?? '-' }}</td>
                </tr>
                <tr role="row">
                    <th>{{ __('Species') }}</th>
                    <td>{{ $petAdoption->species ?? '-' }}</td>
                </tr>
                <tr role="row">
                    <th>{{ __('Breed') }}</th>
                    <td>{{ $petAdoption->breed ?? '-' }}</td>
                </tr>
                <tr role="row">
                    <th>{{ __('Adoption Amount') }}</th>
                    <td>{{ isset($petAdoption->adoption_amount) ? currency_format_with_sym($petAdoption->adoption_amount) : '-' }}
                    </td>
                </tr>
                <tr role="row">
                    <th>{{ __('Age') }}</th>
                    @php
                        $dob = $petAdoption->date_of_birth ?? null;
                        $ageText = '-';

                        if ($dob) {
                            $dob = \Carbon\Carbon::parse($dob);
                            $now = \Carbon\Carbon::now();
                            $diff = $dob->diff($now);

                            $years = $diff->y;
                            $months = $diff->m;
                            $days = $diff->d;

                            $parts = [];

                            if ($years > 0) {
                                $parts[] = $years . ' ' . __('year') . ($years > 1 ? 's' : '');
                            }
                            if ($months > 0) {
                                $parts[] = $months . ' ' . __('month') . ($months > 1 ? 's' : '');
                            }
                            if (empty($parts) && $days > 0) {
                                $parts[] = $days . ' ' . __('day') . ($days > 1 ? 's' : '');
                            }

                            $ageText = count($parts) ? implode(', ', $parts) : __('0');
                        }
                    @endphp
                    <td>
                        {{ $ageText }}
                    </td>
                </tr>
                <tr role="row">
                    <th>{{ __('Gender') }}</th>
                    <td>{{ $petAdoption->gender ?? '-' }}</td>
                </tr>
                <tr role="row">
                    <th>{{ __('Health Condition') }}</th>
                    <td>
                        @php
                            $health = $petAdoption->health_status;
                            $healthLabel = \Workdo\PetCare\Entities\PetAdoption::$health_status[$health] ?? $health;
                        @endphp
                        {{ $healthLabel }}
                    </td>
                </tr>
                <tr role="row">
                    <th>{{ __('Availability') }}</th>
                    <td>
                        @php
                            $availability = $petAdoption->availability;
                            $status_color = [
                                'available_now' => 'success',
                                'coming_soon' => 'warning',
                                'adopted' => 'primary',
                                'not_available' => 'danger',
                            ];
                            $color = $status_color[$availability] ?? 'secondary';
                            $label = \Workdo\PetCare\Entities\PetAdoption::$availability[$availability] ?? $availability;
                        @endphp
                        <span class="badge fix_badges bg-{{ $color }} p-2 px-3">{{ $label }}</span>
                    </td>
                </tr>                
                <tr role="row">
                    <th>{{ __('Classification Tags') }}</th>
                    <td>
                        @if (!empty($petAdoption->classification_tags))
                            @foreach (explode(',', $petAdoption->classification_tags) as $tag)
                                <span class="badge p-2 px-3 bg-primary">{{ trim($tag) }}</span>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr role="row">
                    <th>{{ __('Description') }}</th>
                    <td>
                        <div class="text-wrap text-break text-justify">
                            {{ !empty($petAdoption->description) ? $petAdoption->description : '-' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
