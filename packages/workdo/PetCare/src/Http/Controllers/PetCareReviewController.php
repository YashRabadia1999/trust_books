<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\Entities\PetCareReview;
use Workdo\PetCare\Entities\PetCareSystemSetup;
use Workdo\PetCare\Events\CreatePetCareReview;
use Workdo\PetCare\Events\DestroyPetCareReview;
use Workdo\PetCare\Events\UpdatePetCareReview;

class PetCareReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('petcare_review manage')) {
            $petcare_settings = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->whereIn('key', ['review_tagline_label', 'review_heading_title', 'review_form_heading_title', 'review_form_sub_title'])->get();
            $petCareReviews = PetCareReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->orderBy('id', 'desc')->get();

            $settings = [];

            foreach ($petcare_settings as $setting) {
                $settings[$setting->key] = $setting->value;
            }

            return view('pet-care::system_setup.review.index', ['petcare_system_setup'  => $settings, 'petCareReviews' => $petCareReviews]);
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
        if (\Auth::user()->isAbleTo('petcare_review create')) {
            return view('pet-care::system_setup.review.create');
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
        if (\Auth::user()->isAbleTo('petcare_review create')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'reviewer_name'   => 'required|string|max:255',
                    'reviewer_email'  => 'required|email|max:255',
                    'rating'          => 'required|integer|min:1|max:5',
                    'review'          => 'required|string|max:255',
                    'display_status'  => 'required|in:0,1',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $data = $validator->validated();

            $displayStatus = ($request->input('display_status', '0') === '1') ? 'on' : 'off';

            $petCareReview                      = new PetCareReview();
            $petCareReview->reviewer_name       = $data['reviewer_name'];
            $petCareReview->reviewer_email      = $data['reviewer_email'];
            $petCareReview->rating              = $data['rating'];
            $petCareReview->review              = $data['review'];
            $petCareReview->display_status      = $displayStatus;
            $petCareReview->review_status       = 'pending';
            $petCareReview->workspace           = getActiveWorkSpace();
            $petCareReview->created_by          = creatorId();
            $petCareReview->save();

            event(new CreatePetCareReview($request, $petCareReview));

            return redirect()->back()->with('success', __('The review has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function reviewSettingStore(Request $request)
    {
        if (\Auth::user()->isAbleTo('petcare_review manage')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'review_tagline_label'              =>  'required|string|max:255',
                    'review_heading_title'              =>  'required|string|max:255',
                    'review_form_heading_title'         =>  'required|string|max:255',
                    'review_form_sub_title'             =>  'required|string|max:255',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $Post = [
                'review_tagline_label'              =>  $request->input('review_tagline_label'),
                'review_heading_title'              =>  $request->input('review_heading_title'),
                'review_form_heading_title'         =>  $request->input('review_form_heading_title'),
                'review_form_sub_title'             =>  $request->input('review_form_sub_title'),
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
    public function edit($reviewId)
    {
        if (\Auth::user()->isAbleTo('petcare_review edit')) {
            $decryptedReviewId = \Illuminate\Support\Facades\Crypt::decrypt($reviewId);
            $petCareReview = PetCareReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedReviewId);
            if (!$petCareReview || $petCareReview->created_by != creatorId() || $petCareReview->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption not found.'));
            }
            return view('pet-care::system_setup.review.edit', compact('petCareReview', 'reviewId'));
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
    public function update(Request $request, $reviewId)
    {
        if (\Auth::user()->isAbleTo('petcare_review edit')) {

            $decryptedReviewId = \Illuminate\Support\Facades\Crypt::decrypt($reviewId);
            $petCareReview = PetCareReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedReviewId);
            if (!$petCareReview || $petCareReview->created_by != creatorId() || $petCareReview->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption not found.'));
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'reviewer_name'   => 'required|string|max:255',
                    'reviewer_email'  => 'required|email|max:255',
                    'rating'          => 'required|integer|min:1|max:5',
                    'review'          => 'required|string|max:255',
                    'display_status'  => 'required|in:0,1',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $data = $validator->validated();

            $displayStatus = ($request->input('display_status', '0') === '1') ? 'on' : 'off';

            $petCareReview->reviewer_name   = $data['reviewer_name'];
            $petCareReview->reviewer_email  = $data['reviewer_email'];
            $petCareReview->rating          = $data['rating'];
            $petCareReview->review          = $data['review'];
            $petCareReview->display_status  = $displayStatus;
            $petCareReview->save();

            event(new UpdatePetCareReview($request, $petCareReview));

            return redirect()->back()->with('success', __('The review are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($reviewId)
    {
        if (\Auth::user()->isAbleTo('petcare_review delete')) {
            $decryptedReviewId = \Illuminate\Support\Facades\Crypt::decrypt($reviewId);
            $petCareReview = PetCareReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedReviewId);
            if (!$petCareReview || $petCareReview->created_by != creatorId() || $petCareReview->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption not found.'));
            }

            event(new DestroyPetCareReview($petCareReview));
            $petCareReview->delete();

            return redirect()->back()->with('success', __('The review has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function reviewDetails($reviewId)
    {
        $petCareReview = PetCareReview::find($reviewId);
        if (!$petCareReview || $petCareReview->created_by != creatorId() || $petCareReview->workspace != getActiveWorkSpace()) {
            return response()->json(['error' => __('Review not found.')], 401);
        }
        return view('pet-care::system_setup.review.reviewDetails', compact('petCareReview'));
    }

    public function action($reviewId)
    {
        if (\Auth::user()->isAbleTo('petcare_review action')) {
            $decryptedReviewId = \Illuminate\Support\Facades\Crypt::decrypt($reviewId);
            $petCareReview = PetCareReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedReviewId);
            if (!$petCareReview || $petCareReview->created_by != creatorId() || $petCareReview->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption not found.'));
            }

            return view('pet-care::system_setup.review.action', compact('petCareReview', 'reviewId'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function changeAction(Request $request, $reviewId)
    {
        if (\Auth::user()->isAbleTo('petcare_review action')) {
            $decryptedReviewId = \Illuminate\Support\Facades\Crypt::decrypt($reviewId);
            $petCareReview = PetCareReview::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedReviewId);
            if (!$petCareReview || $petCareReview->created_by != creatorId() || $petCareReview->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption not found.'));
            }

            $validated = $request->validate([
                'review_status' => 'required|in:approved,rejected',
            ]);

            $petCareReview->review_status = $validated['review_status'];
            $petCareReview->save();

            return redirect()->back()->with('success', __('The review status has been updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
