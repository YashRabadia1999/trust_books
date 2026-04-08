<?php

namespace Workdo\PetCare\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Workdo\PetCare\Entities\PetAppointment;
use Workdo\PetCare\Entities\PetOwner;
use Workdo\PetCare\Entities\Pets;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
use Workdo\PetCare\DataTables\PetAppointmentDatatable;
use Workdo\PetCare\Entities\PetAppointmentPackages;
use Workdo\PetCare\Entities\PetAppointmentServicePackage;
use Workdo\PetCare\Entities\PetAppointmentServices;
use Workdo\PetCare\Entities\PetCareSystemSetup;
use Workdo\PetCare\Entities\PetGroomingPackage;
use Workdo\PetCare\Entities\PetService;
use Workdo\PetCare\Events\CreatePetAppointment;
use Workdo\PetCare\Events\DestroyPetAppointment;
use Workdo\PetCare\Events\UpdatePetAppointment;

class PetAppointmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PetAppointmentDatatable $datatable)
    {
        if (\Auth::user()->isAbleTo('pet_appointments manage')) {
            return $datatable->render('pet-care::pet_appointments.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (\Auth::user()->isAbleTo('pet_appointments create')) {
            $staff = User::where('workspace_id', getActiveWorkSpace())
                ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
                ->where('users.created_by', creatorId())->emp()
                ->select('users.*', 'users.id as ID', 'employees.*', 'users.name as name', 'users.email as email', 'users.id as id')->pluck('name', 'id');
            $packages = PetGroomingPackage::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('package_name', 'id');
            $services = PetService::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('service_name', 'id');
            $allKeys                = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('key')->unique()->values();
            $petCareSystemSetup     = PetCareSystemSetup::whereIn('key', $allKeys)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->keyBy('key');
            $contactInfoOpenTime            = $petCareSystemSetup['contact_info_open_time']->value ?? null;
            $contactInfoCloseTime           = $petCareSystemSetup['contact_info_close_time']->value ?? null;

            $time_options = ['' => 'Select a time'];
            $start_timestamp = strtotime($contactInfoOpenTime);
            $end_timestamp = strtotime($contactInfoCloseTime);

            if ($start_timestamp && $end_timestamp) {
                for ($time = $start_timestamp; $time <= $end_timestamp; $time += 3600) {
                    $formatted_time = date('h:i A', $time);
                    if (!array_key_exists($formatted_time, $time_options)) {
                        $time_options[$formatted_time] = $formatted_time;
                    }
                }
            }
            return view('pet-care::pet_appointments.create', compact('staff', 'packages', 'services', 'time_options'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('pet_appointments create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'owner_name'                    =>  'required|string|max:255',
                    'email'                         =>  'required|email',
                    'address'                       =>  'nullable|string',
                    'pet_name'                      => 'required|string|max:255',
                    'species'                       => 'required|string|max:255',
                    'breed'                         => 'required|string|max:255',
                    'date_of_birth'                 => 'required|date',
                    'gender'                        => ['required', Rule::in(['Male', 'Female'])],
                    'assigned_staff_id'             => 'required|exists:users,id',
                    'service_id'                    => 'required_without:package_id|array|nullable',
                    'service_id.*'                  => 'exists:pet_services,id',
                    'package_id'                    => 'required_without:service_id|array|nullable',
                    'package_id.*'                  => 'exists:pet_grooming_packages,id',
                    'appointment_date'              => 'required|date',
                    'appointment_time'              => 'required|string',
                    'total_service_package_amount'  => 'required|numeric',
                    'notes'                         => 'nullable|string',
                ],
                [
                    'service_id.required_without' => __('Please select at least one service or package.'),
                    'package_id.required_without' => __('Please select at least one service or package.'),
                    'service_id.*.exists'         => __('Invalid service or package selected.'),
                    'package_id.*.exists'         => __('Invalid service or package selected.'),
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if ($request->input('contact_number')) {
                $validator = Validator::make($request->all(), ['contact_number' => 'required|regex:/^\+\d{1,3}\d{9,13}$/']);
                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            // Create Pet Owner
            $owner = new PetOwner();
            $owner->owner_name     = $request->owner_name;
            $owner->email          = $request->email;
            $owner->contact_number = $request->contact_number;
            $owner->address        = $request->address ?? null;
            $owner->workspace      = getActiveWorkSpace();
            $owner->created_by     = creatorId();
            $owner->save();

            // Create Pet
            $pet = new Pets();
            $pet->pet_owner_id   = $owner->id;
            $pet->pet_name       = $request->pet_name;
            $pet->species        = $request->species;
            $pet->breed          = $request->breed;
            $pet->date_of_birth  = $request->date_of_birth ?? null;
            $pet->gender         = $request->gender;
            $pet->workspace      = getActiveWorkSpace();
            $pet->created_by     = creatorId();
            $pet->save();

            // Create Pet Appointment
            $appointment = new PetAppointment();
            $appointment->appointment_number           = self::petAppointmentNumber();
            $appointment->pet_owner_id                 = $owner->id;
            $appointment->pet_id                       = $pet->id;
            $appointment->assigned_staff_id            = $request->assigned_staff_id;
            $appointment->appointment_date             = $request->appointment_date ?? now();
            $appointment->appointment_time             = $request->appointment_time ?? null;
            $appointment->appointment_status           = 'pending';
            $appointment->total_service_package_amount = $request->total_service_package_amount;
            $appointment->notes                        = $request->notes ?? null;
            $appointment->workspace                    = getActiveWorkSpace();
            $appointment->created_by                   = creatorId();
            $appointment->save();

            if (is_array($request->service_id)) {
                $appointment->appointmentServices()->sync($request->service_id);
            }

            if (is_array($request->package_id)) {
                $appointment->appointmentPackages()->sync($request->package_id);
            }

            event(new CreatePetAppointment($request, $owner, $pet, $appointment));

            return redirect()->route('pet.appointments.index')->with('success', __('The pet appointment has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (\Auth::user()->isAbleTo('pet_appointments show')) {
            try {
                $petAppointmentId = \Illuminate\Support\Facades\Crypt::decrypt($id);
                $petAppointment = PetAppointment::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($petAppointmentId);
                if (!$petAppointment || $petAppointment->created_by != creatorId() || $petAppointment->workspace != getActiveWorkSpace()) {
                    return redirect()->back()->with('error', __('Something went wrong.'));
                }
                $petOwner = $petAppointment->petOwner;
                $pet = $petAppointment->pet;
                $selectedServices = $petAppointment->appointmentServices;
                $selectedPackages = $petAppointment->appointmentPackages;
    
                return view('pet-care::pet_appointments.show', compact('petAppointment', 'petOwner', 'pet', 'selectedServices', 'selectedPackages'));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($pet_appointment_id)
    {
        if (\Auth::user()->isAbleTo('pet_appointments edit')) {
            $petAppointmentId = \Illuminate\Support\Facades\Crypt::decrypt($pet_appointment_id);
            $petAppointment = PetAppointment::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($petAppointmentId);
            if (!$petAppointment || $petAppointment->created_by != creatorId() || $petAppointment->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }

            $petOwner = $petAppointment->petOwner;
            $pet = $petAppointment->pet;

            $selectedServiceIds = $petAppointment->appointmentServices->pluck('id')->toArray();
            $servicePrice = $petAppointment->appointmentServices->sum('price');

            $selectedPackageIds = $petAppointment->appointmentPackages->pluck('id')->toArray();
            $packagePrice = $petAppointment->appointmentPackages->sum('total_package_amount');

            $staff = User::where('workspace_id', getActiveWorkSpace())
                ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
                ->where('users.created_by', creatorId())->emp()
                ->select('users.*', 'users.id as ID', 'employees.*', 'users.name as name', 'users.email as email', 'users.id as id')->pluck('name', 'id');
            $packages = PetGroomingPackage::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('package_name', 'id');
            $services = PetService::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('service_name', 'id');

            $allKeys                = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('key')->unique()->values();
            $petCareSystemSetup     = PetCareSystemSetup::whereIn('key', $allKeys)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->keyBy('key');
            $contactInfoOpenTime            = $petCareSystemSetup['contact_info_open_time']->value ?? null;
            $contactInfoCloseTime           = $petCareSystemSetup['contact_info_close_time']->value ?? null;

            $time_options = ['' => 'Select a time'];
            $start_timestamp = strtotime($contactInfoOpenTime);
            $end_timestamp = strtotime($contactInfoCloseTime);

            if ($start_timestamp && $end_timestamp) {
                for ($time = $start_timestamp; $time <= $end_timestamp; $time += 3600) {
                    $formatted_time = date('h:i A', $time);
                    if (!array_key_exists($formatted_time, $time_options)) {
                        $time_options[$formatted_time] = $formatted_time;
                    }
                }
            }

            return view('pet-care::pet_appointments.edit', compact('petAppointment', 'petOwner', 'pet', 'pet_appointment_id', 'staff', 'packages', 'services', 'selectedServiceIds', 'selectedPackageIds', 'servicePrice', 'packagePrice', 'time_options'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $pet_appointment_id)
    {
        if (\Auth::user()->isAbleTo('pet_appointments edit')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'owner_name'        => 'required|string|max:255',
                    'email'             => 'required|email',
                    'address'           => 'nullable|string',
                    'pet_name'          => 'required|string|max:255',
                    'species'           => 'required|string|max:255',
                    'breed'             => 'required|string|max:255',
                    'date_of_birth'     => 'required|date',
                    'gender'            => ['required', Rule::in(['Male', 'Female'])],
                    'assigned_staff_id' => 'required|exists:users,id',
                    'service_id'        => 'required_without:package_id|array|nullable',
                    'service_id.*'      => 'exists:pet_services,id',
                    'package_id'        => 'required_without:service_id|array|nullable',
                    'package_id.*'      => 'exists:pet_grooming_packages,id',
                    'appointment_date'  => 'required|date',
                    'appointment_time'  => 'required|string',
                    'total_service_package_amount'  => 'required|numeric',
                    'notes'             => 'nullable|string',
                ],
                [
                    'service_id.required_without' => __('Please select at least one service or package.'),
                    'package_id.required_without' => __('Please select at least one service or package.'),
                    'service_id.*.exists'         => __('Invalid service or package selected.'),
                    'package_id.*.exists'         => __('Invalid service or package selected.'),
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if ($request->input('contact_number')) {
                $validator = Validator::make($request->all(), ['contact_number' => 'required|regex:/^\+\d{1,3}\d{9,13}$/']);
                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            $petAppointmentId = decrypt($pet_appointment_id);
            $petAppointment = PetAppointment::find($petAppointmentId);
            if (!$petAppointment || $petAppointment->created_by != creatorId() || $petAppointment->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }

            // Update PetOwner
            $owner = $petAppointment->petOwner;
            $owner->owner_name          = $request->owner_name;
            $owner->email               = $request->email;
            $owner->contact_number      = $request->contact_number;
            $owner->address             = $request->address ?? null;
            $owner->save();

            // Update Pet
            $pet = $petAppointment->pet;
            $pet->pet_name              = $request->pet_name;
            $pet->species               = $request->species;
            $pet->breed                 = $request->breed;
            $pet->date_of_birth         = $request->date_of_birth ?? null;
            $pet->gender                = $request->gender;
            $pet->save();

            // Update PetAppointment
            $petAppointment->assigned_staff_id              = $request->assigned_staff_id;
            $petAppointment->appointment_date               = $request->appointment_date ?? now();
            $petAppointment->appointment_time               = $request->appointment_time ?? null;
            $petAppointment->total_service_package_amount   = $request->total_service_package_amount;
            $petAppointment->notes                          = $request->notes ?? null;
            $petAppointment->save();

            if (is_array($request->service_id)) {
                $petAppointment->appointmentServices()->sync($request->service_id);
            } else {
                $petAppointment->appointmentServices()->sync([]);
            }

            // Sync Packages
            if (is_array($request->package_id)) {
                $petAppointment->appointmentPackages()->sync($request->package_id);
            } else {
                $petAppointment->appointmentPackages()->sync([]);
            }

            event(new UpdatePetAppointment($request, $owner, $pet, $petAppointment));

            return redirect()->route('pet.appointments.index')->with('success', __('The pet appointment are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($petAppointmentId)
    {
        if (\Auth::user()->isAbleTo('pet_appointments delete')) {
            $petAppointment = PetAppointment::find($petAppointmentId);
            if (!$petAppointment || $petAppointment->created_by != creatorId() || $petAppointment->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }

            $ownerDeleted = false;
            $petDeleted = false;

            if ($petAppointment->pet_owner_id && PetOwner::where('id', $petAppointment->pet_owner_id)->exists()) {
                $petAppointment->petOwner->delete();
                $ownerDeleted = true;
            }

            if ($petAppointment->pet_id && Pets::where('id', $petAppointment->pet_id)->exists()) {
                $petAppointment->pet->delete();
                $petDeleted = true;
            }

            event(new DestroyPetAppointment($petAppointment));
            $petAppointment->delete();

            $message = 'The pet appointment';
            if ($petDeleted) $message .= ', pet';
            if ($ownerDeleted) $message .= ', and owner';
            $message .= ' has been deleted.';

            return redirect()->route('pet.appointments.index')->with('success', __($message));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public static function petAppointmentNumber()
    {
        $latest = PetAppointment::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }
        return $latest->appointment_number + 1;
    }

    public function appointmentStatusEdit($petAppointmentId)
    {
        if (\Auth::user()->isAbleTo('pet_appointments status update')) {
            $decryptedPetAppointmentId = decrypt($petAppointmentId);
            $petAppointment = PetAppointment::find($decryptedPetAppointmentId);
            if (!$petAppointment || $petAppointment->created_by != creatorId() || $petAppointment->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Something went wrong.')], 401); 
            }
            $petAppointmentStatus = PetAppointment::$pet_appointment_status;
            return view('pet-care::pet_appointments.status_update', compact('petAppointmentId', 'petAppointmentStatus', 'petAppointment'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function appointmentStatusUpdate(Request $request, $petAppointmentId)
    {
        if (\Auth::user()->isAbleTo('pet_appointments status update')) {
            $decryptedPetAppointmentId = decrypt($petAppointmentId);
            $petAppointment = PetAppointment::find($decryptedPetAppointmentId);
            if (!$petAppointment || $petAppointment->created_by != creatorId() || $petAppointment->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
            if ($request->appointment_status === 'completed') {
                if ($petAppointment->getDueAmount() == 0) {
                    $petAppointment->appointment_status = 'completed';
                    $petAppointment->save();
                } else {
                    return redirect()->back()->with('error', __('Cannot mark the appointment as completed, Payment is still due remains.'));
                }
            } else {
                $petAppointment->appointment_status = $request->appointment_status;
                $petAppointment->save();
            }
            return redirect()->route('pet.appointments.index')->with('success', __('The pet appointment status are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
