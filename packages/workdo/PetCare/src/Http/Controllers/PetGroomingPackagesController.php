<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\FormBuilder\Events\ViewForm;
use Workdo\PetCare\DataTables\PetGroomingPackagesDatatable;
use Workdo\PetCare\Entities\PetGroomingPackage;
use Workdo\PetCare\Entities\PetService;
use Workdo\PetCare\Entities\PetVaccine;
use Workdo\PetCare\Events\CreatePetGroomingPackage;
use Workdo\PetCare\Events\DestroyPetGroomingPackage;
use Workdo\PetCare\Events\UpdatePetGroomingPackage;

class PetGroomingPackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PetGroomingPackagesDatatable $dataTable)
    {
        if (\Auth::user()->isAbleTo('pet_grooming_packages manage')) {
            return $dataTable->render('pet-care::pet_grooming_packages.index');
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
        if (\Auth::user()->isAbleTo('pet_grooming_packages create')) {
            $allServices = PetService::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('service_name', 'id');
            $allVaccines = PetVaccine::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('vaccine_name', 'id');
            return view('pet-care::pet_grooming_packages.create', compact('allServices', 'allVaccines'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('pet_grooming_packages create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'package_icon' => 'required|string|max:255',
                    'package_name' => 'required|string|max:255',
                    'total_package_amount' => 'required|numeric|min:0',
                    'services' => 'array',
                    'service_prices' => 'array',
                    'vaccines' => 'array',
                    'vaccine_prices' => 'array',
                    'package_features' => 'required|string',
                    'description'  => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $package = new PetGroomingPackage();
            $package->package_name         = $request->package_name;
            $package->package_icon         = $request->package_icon ?? null;
            $package->package_features     = $request->package_features ?? null;
            $package->description          = $request->description ?? null;
            $package->total_package_amount = $request->total_package_amount;
            $package->workspace            = getActiveWorkSpace();
            $package->created_by           = creatorId();
            $package->save();

            // Sync services with prices
            $servicesData = [];
            if ($request->services && $request->service_prices) {
                foreach ($request->services as $key => $serviceId) {
                    if ($serviceId) {
                        $servicesData[$serviceId] = [
                            'service_price' => $request->service_prices[$key] ?? 0.00,
                        ];
                    }
                }
            }
            $package->services()->sync($servicesData);

            // Sync vaccines with prices
            $vaccinesData = [];
            if ($request->vaccines && $request->vaccine_prices) {
                foreach ($request->vaccines as $key => $vaccineId) {
                    if ($vaccineId) {
                        $vaccinesData[$vaccineId] = [
                            'vaccine_price' => $request->vaccine_prices[$key] ?? 0.00,
                        ];
                    }
                }
            }
            $package->vaccines()->sync($vaccinesData);

            event(new CreatePetGroomingPackage($request, $package, $servicesData, $vaccinesData));

            return redirect()->back()->with('success', __('The pet grooming Package created successfully.'));
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
        if (\Auth::user()->isAbleTo('pet_grooming_packages show')) {

            if (!request()->ajax()) {
                return redirect()->back();
            }
            
            $petGroomingPackage = PetGroomingPackage::where('id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();

            if (!$petGroomingPackage || $petGroomingPackage->created_by != creatorId() || $petGroomingPackage->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Pet Grooming Package not found.')], 401);
            }
            $packageServices = $petGroomingPackage->services;
            $packageVaccines = $petGroomingPackage->vaccines;
            return View('pet-care::pet_grooming_packages.show', compact('petGroomingPackage', 'packageServices', 'packageVaccines'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (\Auth::user()->isAbleTo('pet_grooming_packages edit')) {
            $petGroomingPackage = PetGroomingPackage::where('id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();

            if (!$petGroomingPackage || $petGroomingPackage->created_by != creatorId() || $petGroomingPackage->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Pet Grooming Package not found.')], 401);
            }
            $packageServices = $petGroomingPackage->services;
            $packageVaccines = $petGroomingPackage->vaccines;
            $allServices = PetService::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('service_name', 'id');
            $allVaccines = PetVaccine::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('vaccine_name', 'id');
            return View('pet-care::pet_grooming_packages.edit', compact('allServices', 'allVaccines', 'petGroomingPackage', 'packageServices', 'packageVaccines'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (\Auth::user()->isAbleTo('pet_grooming_packages edit')) {
            $petGroomingPackage = PetGroomingPackage::find($id);

            if (!$petGroomingPackage || $petGroomingPackage->created_by != creatorId() || $petGroomingPackage->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet grooming package not found.'));
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'package_icon' => 'required|string|max:255',
                    'package_name' => 'required|string|max:255',
                    'total_package_amount' => 'required|numeric|min:0',
                    'services' => 'array',
                    'service_prices' => 'array',
                    'vaccines' => 'array',
                    'vaccine_prices' => 'array',
                    'package_features' => 'required|string',
                    'description'  => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $petGroomingPackage->package_name           = $request->package_name;
            $petGroomingPackage->total_package_amount   = $request->total_package_amount;
            $petGroomingPackage->package_icon           = $request->package_icon ?? null;
            $petGroomingPackage->package_features       = $request->package_features ?? null;
            $petGroomingPackage->description            = $request->description ?? null;
            $petGroomingPackage->save();

            // Sync services with prices
            $servicesData = [];
            if ($request->services && $request->service_prices) {
                foreach ($request->services as $key => $serviceId) {
                    if ($serviceId) {
                        $servicesData[$serviceId] = [
                            'service_price' => $request->service_prices[$key] ?? 0.00,
                        ];
                    }
                }
            }
            $petGroomingPackage->services()->sync($servicesData);

            // Sync vaccines with prices
            $vaccinesData = [];
            if ($request->vaccines && $request->vaccine_prices) {
                foreach ($request->vaccines as $key => $vaccineId) {
                    if ($vaccineId) {
                        $vaccinesData[$vaccineId] = [
                            'vaccine_price' => $request->vaccine_prices[$key] ?? 0.00,
                        ];
                    }
                }
            }
            $petGroomingPackage->vaccines()->sync($vaccinesData);

            event(new UpdatePetGroomingPackage($request, $petGroomingPackage, $servicesData, $vaccinesData));

            return redirect()->back()->with('success', __('The pet grooming package details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (\Auth::user()->isAbleTo('pet_grooming_packages delete')) {
            $petGroomingPackage = PetGroomingPackage::find($id);

            if (!$petGroomingPackage || $petGroomingPackage->created_by != creatorId() || $petGroomingPackage->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet Grooming Package not found.'));
            }

            event(new DestroyPetGroomingPackage($petGroomingPackage));
            $petGroomingPackage->delete();
            return redirect()->back()->with('success', __('The pet grooming package has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getServicePrice(Request $request)
    {
        $serviceId = $request->input('serviceId');

        $service = PetService::find($serviceId);

        if ($service) {
            return response()->json(['servicePrice' => $service->price]);
        }

        return response()->json(['error' => 'Service not found'], 404);
    }

    public function getVaccinePrice(Request $request)
    {
        $vaccineId = $request->input('vaccineId');

        $vaccine = PetVaccine::find($vaccineId);

        if ($vaccine) {
            return response()->json(['vaccinePrice' => $vaccine->price]);
        }

        return response()->json(['error' => 'Vaccine not found'], 404);
    }

    public function description($id)
    {
        $petGroomingPackage = PetGroomingPackage::find($id);
        if (!$petGroomingPackage || $petGroomingPackage->created_by != creatorId() || $petGroomingPackage->workspace != getActiveWorkSpace()) {
            return response()->json(['error' => __('Pet grooming package not found.')], 401);
        }
        return view('pet-care::pet_grooming_packages.description', compact('petGroomingPackage'));
    }

    public function getMultipulPackagePrice(Request $request)
    {
        $packageIds = $request->input('packageIds', []);

        if (!is_array($packageIds)) {
            return response()->json(['error' => __('Invalid input. Expected an array of package IDs.')], 401);
        }

        $packages = PetGroomingPackage::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->whereIn('id', $packageIds)->pluck('total_package_amount', 'id');

        if ($packages) {
            return response()->json(['prices' => array_values($packages->toArray())]);
        }

        return response()->json(['error' => __('Package not found.')], 404);
    }

    public function getMultipulServicePrice(Request $request)
    {
        $serviceIds = $request->input('serviceIds', []);

        if (!is_array($serviceIds)) {
            return response()->json(['error' => __('Invalid input. Expected an array of service IDs.')], 401);
        }

        $services = PetService::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->whereIn('id', $serviceIds)->pluck('price', 'id');

        if ($services) {
            return response()->json(['prices' => array_values($services->toArray())]);
        }

        return response()->json(['error' => 'Service not found'], 404);
    }
}
