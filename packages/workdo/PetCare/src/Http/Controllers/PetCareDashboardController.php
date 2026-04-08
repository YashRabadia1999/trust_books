<?php

namespace Workdo\PetCare\Http\Controllers;

use App\Models\WorkSpace;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\PetCare\Entities\PetAdoption;
use Workdo\PetCare\Entities\PetAdoptionRequest;
use Workdo\PetCare\Entities\PetAdoptionRequestPayments;
use Workdo\PetCare\Entities\PetAppointment;
use Workdo\PetCare\Entities\PetCareBillingPayments;
use Workdo\PetCare\Entities\PetGroomingPackage;
use Workdo\PetCare\Entities\PetService;

class PetCareDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('petcare dashboard manage')) {
            $workspace            = WorkSpace::where('id', getActiveWorkSpace())->first();
            $data['totalAppointments']          = PetAppointment::where('created_by', creatorId())->where('workspace', $workspace->id)->count();
            $data['totalServices']              = PetService::where('created_by', creatorId())->where('workspace', $workspace->id)->count();
            $data['totalPackages']              = PetGroomingPackage::where('created_by', creatorId())->where('workspace', $workspace->id)->count();
            $data['totalAdoptions']             = PetAdoption::where('created_by', creatorId())->where('workspace', $workspace->id)->count();
            $data['totalAdoptionRequest']       = PetAdoptionRequest::where('created_by', creatorId())->where('workspace', $workspace->id)->count();

            // Appointments by status
            $appointmentStatusLabels = [];
            $appointmentStatusCounts = [];

            foreach (PetAppointment::$pet_appointment_status as $statusKey => $statusLabel) {
                $appointmentStatusLabels[] = $statusLabel;
                $count = PetAppointment::where('appointment_status', $statusKey)->count();
                $appointmentStatusCounts[] = $count;
            }

            $jsonStatusLabels = json_encode($appointmentStatusLabels);
            $jsonStatusCounts = json_encode($appointmentStatusCounts);


            // Adoption Request by status
            $adoptionStatuses       = PetAdoptionRequest::$adoption_request_status;
            $totalAdoptionRequest   = PetAdoptionRequest::where('created_by', creatorId())->where('workspace', $workspace->id)->count();

            $adoptionStatusesPercentage = [];
            $adoptionStatusesLabel = [];

            foreach ($adoptionStatuses as $status => $label) {
                $count = PetAdoptionRequest::where('created_by', creatorId())
                    ->where('workspace', $workspace->id)
                    ->where('request_status', $status)
                    ->count();
                $percentage = $totalAdoptionRequest > 0 ? ($count / $totalAdoptionRequest) * 100 : 0;
                $adoptionStatusesPercentage[] = round($percentage, 2);
                $adoptionStatusesLabel[] = $label;
            }
            $colors = ['#ffa21d', '#6fd943', '#FF3A6E', '#007bff'];


            // Last 10 days Appointment & Adoption Payment
            $dates = collect(range(14, 0))->map(function ($i) {
                $date = \Carbon\Carbon::now()->subDays($i);
                return [
                    'key' => $date->format('Y-m-d'),
                    'label' => $date->format('d M'),
                ];
            });

            $appointmentData = [];
            $adoptionData = [];
            $dateLabels = [];

            foreach ($dates as $day) {
                $dateLabels[] = $day['label'];

                $appointmentTotal = PetCareBillingPayments::whereDate('payment_date', $day['key'])
                    ->where('workspace', $workspace->id)
                    ->where('created_by', creatorId())
                    ->sum('amount');

                $adoptionTotal = PetAdoptionRequestPayments::whereDate('payment_date', $day['key'])
                    ->where('workspace', $workspace->id)
                    ->where('created_by', creatorId())
                    ->sum('amount');

                $appointmentData[] = round($appointmentTotal, 2);
                $adoptionData[] = round($adoptionTotal, 2);
            }

            $chartData = [
                'date' => $dateLabels,
                'appointment' => $appointmentData,
                'adoption' => $adoptionData,
            ];

            return view('pet-care::dashboard.index', compact('workspace', 'data', 'jsonStatusLabels', 'jsonStatusCounts', 'adoptionStatusesPercentage', 'adoptionStatusesLabel', 'colors','chartData'));
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
        return view('pet-care::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('pet-care::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('pet-care::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
