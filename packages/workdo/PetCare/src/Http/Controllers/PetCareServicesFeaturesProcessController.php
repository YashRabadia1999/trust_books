<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\Entities\PetService;
use Workdo\PetCare\Entities\PetServiceIncludedFeatures;
use Workdo\PetCare\Entities\PetServiceProcessSteps;
use Workdo\PetCare\Events\SavedPetServiceIncludedFeatures;
use Workdo\PetCare\Events\SavedPetServiceProcessSteps;

class PetCareServicesFeaturesProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function showFeaturesProcessPage($serviceId)
    {
        if (\Auth::user()->isAbleTo('pet_services add features & process')) {
            try {
                $decryptedServiceId = \Illuminate\Support\Facades\Crypt::decrypt($serviceId);
                $petService = PetService::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedServiceId);
                if (!$petService || $petService->created_by != creatorId() || $petService->workspace != getActiveWorkSpace()) {
                    return redirect()->back()->with('error', __('Pet service not found.'));
                }
    
                $features = $petService->serviceIncludedFeatures()->get();
                $processSteps = $petService->serviceProcessSteps()->get();
    
                return view('pet-care::pet_services.features_process', compact('serviceId', 'petService', 'features', 'processSteps'));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    public function storeServiceFeatures(Request $request, $serviceId)
    {
        if (\Auth::user()->isAbleTo('pet_services add features & process')) {

            try {
                $decryptedServiceId = \Illuminate\Support\Facades\Crypt::decrypt($serviceId);
                $petService = PetService::where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())
                    ->find($decryptedServiceId);

                if (!$petService || $petService->created_by != creatorId() || $petService->workspace != getActiveWorkSpace()) {
                    return redirect()->back()->with('error', __('Pet service not found.'));
                }

                $validator = Validator::make(
                    $request->all(),
                    [
                        'feature_icon'                      => 'required|array',
                        'feature_icon.*'                    => 'required|string',
                        'feature_name'                      => 'required|array',
                        'feature_name.*'                    => 'required|string',
                        'feature_description'               => 'required|array',
                        'feature_description.*'             => 'required|string',
                    ],
                    [
                        'feature_icon.*.required'        => __('The icon field is required for all features.'),
                        'feature_name.*.required'        => __('The name field is required for all features.'),
                        'feature_description.*.required' => __('The description field is required for all features.'),
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $featureIdsFromRequest = $request->feature_id ?? [];

                $existingFeatureIdsInDB = $petService->serviceIncludedFeatures()->pluck('id')->toArray();

                $featureIdsToDelete = array_diff($existingFeatureIdsInDB, array_filter($featureIdsFromRequest));

                if (!empty($featureIdsToDelete)) {
                    PetServiceIncludedFeatures::where('service_id', $petService->id)
                        ->whereIn('id', $featureIdsToDelete)
                        ->delete();
                }

                $created = false;
                $includedFeatures = [];

                foreach ($request->feature_name as $index => $name) {
                    $featureId = $featureIdsFromRequest[$index] ?? null;

                    if ($featureId) {
                        $feature = PetServiceIncludedFeatures::where('id', $featureId)
                            ->where('service_id', $petService->id)
                            ->first();

                        if ($feature) {
                            $feature->feature_icon        = $request->feature_icon[$index];
                            $feature->feature_name        = $name;
                            $feature->feature_description = $request->feature_description[$index] ?? null;
                            $feature->save();

                            $includedFeatures[] = $feature;
                        }
                    } else {
                        $feature = new PetServiceIncludedFeatures();
                        $feature->service_id          = $petService->id;
                        $feature->feature_icon        = $request->feature_icon[$index];
                        $feature->feature_name        = $name;
                        $feature->feature_description = $request->feature_description[$index] ?? null;
                        $feature->workspace           = getActiveWorkSpace();
                        $feature->created_by          = creatorId();
                        $feature->save();
                        
                        $includedFeatures[] = $feature;

                        $created = true;
                    }
                }

                event(new SavedPetServiceIncludedFeatures($request, $includedFeatures, $petService));
                $message = $created ? __('The service features has been created successfully.') : __('The service features are updated successfully.');

                return redirect()->back()->with('success', $message);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    public function storeServiceProcessSteps(Request $request, $serviceId)
    {
        if (\Auth::user()->isAbleTo('pet_services add features & process')) {

            try {
                $decryptedServiceId = \Illuminate\Support\Facades\Crypt::decrypt($serviceId);
                $petService = PetService::where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())
                    ->find($decryptedServiceId);

                if (!$petService || $petService->created_by != creatorId() || $petService->workspace != getActiveWorkSpace()) {
                    return redirect()->back()->with('error', __('Pet service not found.'));
                }

                $validator = Validator::make(
                    $request->all(),
                    [
                        'process_name'        => 'required|array',
                        'process_name.*'      => 'required|string',
                        'process_description'  => 'required|array',
                        'process_description.*' => 'required|string',
                    ],
                    [
                        'process_name.*.required'        => __('The process title field is required for all steps.'),
                        'process_description.*.required' => __('The process description field is required for all steps.'),
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }


                $processIdsFromRequest = $request->process_id ?? [];
                $existingProcessIdsInDB = $petService->serviceProcessSteps()->pluck('id')->toArray();

                $processIdsToDelete = array_diff($existingProcessIdsInDB, array_filter($processIdsFromRequest));

                if (!empty($processIdsToDelete)) {
                    PetServiceProcessSteps::where('service_id', $petService->id)->whereIn('id', $processIdsToDelete)->delete();
                }


                $created = false;
                $processSteps = [];

                foreach ($request->process_name as $index => $name) {
                    $processId = $processIdsFromRequest[$index] ?? null;

                    if ($processId) {
                        $process = PetServiceProcessSteps::where('id', $processId)
                            ->where('service_id', $petService->id)
                            ->first();

                        if ($process) {
                            $process->process_name = $name;
                            $process->process_description = $request->process_description[$index] ?? null;
                            $process->save(); 

                            $processSteps[] = $process;
                        }
                    } else {
                        $process = new PetServiceProcessSteps();
                        $process->service_id          = $petService->id;
                        $process->process_name        = $name;
                        $process->process_description = $request->process_description[$index] ?? null;
                        $process->workspace           = getActiveWorkSpace();
                        $process->created_by          = creatorId();
                        $process->save();

                        $processSteps[] = $process;
                        $created = true;
                    }
                }

                event(new SavedPetServiceProcessSteps($request, $processSteps, $petService));
                $message = $created ? __('The service process steps have been created successfully.') : __('The service process steps are updated successfully.');

                return redirect()->back()->with('success', $message);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }
}
