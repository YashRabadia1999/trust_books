<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\Entities\PetCareSystemSetup;
use Workdo\PetCare\Entities\PetService;
use Workdo\PetCare\Entities\PetServiceReview;
use Workdo\PetCare\Events\CreateServiceReview;
use Workdo\PetCare\Events\DestroyServiceReview;
use Workdo\PetCare\Events\UpdateServiceReview;

class PetServiceReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('service_review manage')) {
            $petcare_settings = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->whereIn('key', ['service_review_heading_title', 'service_review_form_heading_title', 'service_review_form_sub_title'])->get();
            $serviceReviews = PetServiceReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->orderBy('id', 'desc')->get();

            $settings = [];

            foreach ($petcare_settings as $setting) {
                $settings[$setting->key] = $setting->value;
            }

            return view('pet-care::system_setup.service_reviews.index', ['petcare_system_setup'  => $settings, 'serviceReviews' => $serviceReviews]);
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
        if (\Auth::user()->isAbleTo('service_review create')) {
            $services = PetService::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('service_name', 'id');
            return view('pet-care::system_setup.service_reviews.create', compact('services'));
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
        if (\Auth::user()->isAbleTo('service_review create')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'service_reviewer_name'   => 'required|string|max:255',
                    'service_reviewer_email'  => 'required|email|max:255',
                    'service_id'              => 'required|exists:pet_services,id',
                    'service_rating'          => 'required|integer|min:1|max:5',
                    'service_review'          => 'required|string|max:255',
                    'service_display_status'  => 'required|in:0,1',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $data = $validator->validated();

            $displayStatus = ($request->input('service_display_status', '0') === '1') ? 'on' : 'off';

            $serviceReview                      = new PetServiceReview();
            $serviceReview->reviewer_name       = $data['service_reviewer_name'];
            $serviceReview->reviewer_email      = $data['service_reviewer_email'];
            $serviceReview->service_id          = $data['service_id'];
            $serviceReview->rating              = $data['service_rating'];
            $serviceReview->review              = $data['service_review'];
            $serviceReview->display_status      = $displayStatus;
            $serviceReview->review_status       = 'pending';
            $serviceReview->workspace           = getActiveWorkSpace();
            $serviceReview->created_by          = creatorId();
            $serviceReview->save();

            event(new CreateServiceReview($request, $serviceReview));

            return redirect()->back()->with('success', __('The service review has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function serviceReviewSettingStore(Request $request)
    {
        if (\Auth::user()->isAbleTo('service_review manage')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'service_review_heading_title'          =>  'required|string|max:255',
                    'service_review_form_heading_title'     =>  'required|string|max:255',
                    'service_review_form_sub_title'         =>  'required|string|max:255'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $Post = [
                'service_review_heading_title'          =>  $request->input('service_review_heading_title'),
                'service_review_form_heading_title'     =>  $request->input('service_review_form_heading_title'),
                'service_review_form_sub_title'         =>  $request->input('service_review_form_sub_title')
            ];

            foreach ($Post as $key => $value) {
                PetCareSystemSetup::updateOrCreate(
                    [
                        'key' => $key,
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId()
                    ],
                    [
                        'value' => $value
                    ]
                );
            }
            return redirect()->back()->with('success', __('The review setting have been saved successfully.'));
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
        return redirect()->back();
        return view('pet-care::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($serviceReviewId)
    {
        if (\Auth::user()->isAbleTo('service_review edit')) {
            $decryptedReviewId = \Illuminate\Support\Facades\Crypt::decrypt($serviceReviewId);
            $serviceReview = PetServiceReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedReviewId);

            if (!$serviceReview || $serviceReview->created_by != creatorId() || $serviceReview->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Service review not found.')], 401);
            }

            $services = PetService::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('service_name', 'id');

            return view('pet-care::system_setup.service_reviews.edit', compact('serviceReview', 'serviceReviewId', 'services'));
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
    public function update(Request $request, $serviceReviewId)
    {
        if (\Auth::user()->isAbleTo('service_review edit')) {

            $decryptedReviewId = \Illuminate\Support\Facades\Crypt::decrypt($serviceReviewId);
            $serviceReview = PetServiceReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedReviewId);
            if (!$serviceReview || $serviceReview->created_by != creatorId() || $serviceReview->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Service review not found.'));
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'service_reviewer_name'   => 'required|string|max:255',
                    'service_reviewer_email'  => 'required|email|max:255',
                    'service_id'              => 'required|exists:pet_services,id',
                    'service_rating'          => 'required|integer|min:1|max:5',
                    'service_review'          => 'required|string|max:255',
                    'service_display_status'  => 'required|in:0,1',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $data = $validator->validated();

            $displayStatus = ($request->input('service_display_status', '0') === '1') ? 'on' : 'off';

            $serviceReview->reviewer_name       = $data['service_reviewer_name'];
            $serviceReview->reviewer_email      = $data['service_reviewer_email'];
            $serviceReview->service_id          = $data['service_id'];
            $serviceReview->rating              = $data['service_rating'];
            $serviceReview->review              = $data['service_review'];
            $serviceReview->display_status      = $displayStatus;
            $serviceReview->save();

            event(new UpdateServiceReview($request, $serviceReview));

            return redirect()->back()->with('success', __('The service review are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($serviceReviewId)
    {
        if (\Auth::user()->isAbleTo('service_review delete')) {
            $decryptedReviewId = \Illuminate\Support\Facades\Crypt::decrypt($serviceReviewId);
            $serviceReview = PetServiceReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedReviewId);
            if (!$serviceReview || $serviceReview->created_by != creatorId() || $serviceReview->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Service review not found.'));
            }

            event(new DestroyServiceReview($serviceReview));
            $serviceReview->delete();

            return redirect()->back()->with('success', __('The service review has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function reviewDetails($serviceReviewId)
    {
        $serviceReview = PetServiceReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($serviceReviewId);
        if (!$serviceReview || $serviceReview->created_by != creatorId() || $serviceReview->workspace != getActiveWorkSpace()) {
            return response()->json(['error' => __('Service review not found.')], 401);
        }
        return view('pet-care::system_setup.service_reviews.review_details', compact('serviceReview'));
    }

    public function action($serviceReviewId)
    {
        if (\Auth::user()->isAbleTo('service_review action')) {
            $decryptedReviewId = \Illuminate\Support\Facades\Crypt::decrypt($serviceReviewId);
            $serviceReview = PetServiceReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedReviewId);
            if (!$serviceReview || $serviceReview->created_by != creatorId() || $serviceReview->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Service review not found.')], 401);
            }

            return view('pet-care::system_setup.service_reviews.action', compact('serviceReview', 'serviceReviewId'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function changeAction(Request $request, $serviceReviewId)
    {
        if (\Auth::user()->isAbleTo('service_review action')) {
            $decryptedReviewId = \Illuminate\Support\Facades\Crypt::decrypt($serviceReviewId);
            $serviceReview = PetServiceReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedReviewId);
            if (!$serviceReview || $serviceReview->created_by != creatorId() || $serviceReview->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Service review not found.'));
            }

            $validated = $request->validate([
                'review_status' => 'required|in:approved,rejected',
            ]);

            $serviceReview->review_status = $validated['review_status'];
            $serviceReview->save();

            return redirect()->back()->with('success', __('The service review status has been updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
