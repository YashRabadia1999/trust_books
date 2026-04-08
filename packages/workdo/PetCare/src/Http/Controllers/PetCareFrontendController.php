<?php

namespace Workdo\PetCare\Http\Controllers;

use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\Entities\PetCareSystemSetup;
use Workdo\PetCare\Entities\PetService;
use Workdo\PetCare\Entities\PetAdoption;
use Workdo\PetCare\Entities\PetAdoptionRequest;
use Workdo\PetCare\Entities\PetAppointment;
use Workdo\PetCare\Entities\PetCareContact;
use Workdo\PetCare\Entities\PetCareFAQ;
use Workdo\PetCare\Entities\PetCareReview;
use Workdo\PetCare\Entities\PetCareSocialLink;
use Workdo\PetCare\Entities\PetGroomingPackage;
use Workdo\PetCare\Entities\PetOwner;
use Workdo\PetCare\Entities\Pets;
use Workdo\PetCare\Entities\PetServiceReview;
use Workdo\PetCare\Events\CreatePetAdoptionRequest;
use Workdo\PetCare\Events\CreatePetAppointment;
use Workdo\PetCare\Events\CreatePetCareContact;
use Workdo\PetCare\Events\CreatePetCareReview;
use Workdo\PetCare\Events\CreateServiceReview;

class PetCareFrontendController extends Controller
{
    private function loadWorkspaceData($slug)
    {
        try {
            $workspace              = WorkSpace::where('slug', $slug)->first();

            if ($workspace) {
                $moduleName = 'PetCare';
                $status     = module_is_active($moduleName, $workspace->created_by);
            } else {
                abort(404);
            }

            $allKeys                = PetCareSystemSetup::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->pluck('key')->unique()->values();
            $petCareSystemSetup     = PetCareSystemSetup::whereIn('key', $allKeys)->where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->get()->keyBy('key');

            return [$workspace, $petCareSystemSetup, $status];
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ShowPetCareFrontendPage($slug = null)
    {
        try {
            [$workspace, $petCareSystemSetup, $status] = $this->loadWorkspaceData($slug);

            $petServices            = PetService::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->latest()->take(3)->get();
            $petAdoptions           = PetAdoption::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->whereIn('availability', ['available_now', 'coming_soon'])->get();
            $petGroomingPackage     = PetGroomingPackage::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->latest()->take(3)->get();
            $petCareReviews         = PetCareReview::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->where('display_status', 'on')->where('review_status', 'approved')->get();

            $serviceTaglineLabel    = $petCareSystemSetup['service_tagline_label']->value ?? null;
            $serviceTitle           = $petCareSystemSetup['service_title']->value ?? null;

            $adoptionTaglineLabel   = $petCareSystemSetup['adoption_tagline_label']->value ?? null;
            $adoptionTitle          = $petCareSystemSetup['adoption_title']->value ?? null;

            $packageTaglineLabel    = $petCareSystemSetup['package_tagline_label']->value ?? null;
            $packageTitle           = $petCareSystemSetup['package_title']->value ?? null;

            $reviewTaglineLabel             = $petCareSystemSetup['review_tagline_label']->value ?? null;
            $reviewHeadingTitle             = $petCareSystemSetup['review_heading_title']->value ?? null;
            $reviewFormHeadingTitle         = $petCareSystemSetup['review_form_heading_title']->value ?? null;
            $reviewFormSubTitle             = $petCareSystemSetup['review_form_sub_title']->value ?? null;

            $ctaHeadingTitle        = $petCareSystemSetup['cta_heading_title']->value ?? null;
            $ctaDescription         = $petCareSystemSetup['cta_description']->value ?? null;

            $bannerTagline          = $petCareSystemSetup['banner_tagline']->value ?? null;
            $bannerHeadingTitle     = $petCareSystemSetup['banner_heading_title']->value ?? null;
            $bannerSubTitle         = $petCareSystemSetup['banner_sub_title']->value ?? null;

            if (isset($petCareSystemSetup['banner_images'])) {
                $decodedbannerImages = json_decode($petCareSystemSetup['banner_images']->value, true) ?? [];
            } else {
                $decodedbannerImages = [];
            }

            $bannerDecorativeImage = $petCareSystemSetup['banner_decorative_image']->value ?? null;

            if ($status == true) {
                return view('pet-care::frontend.index', compact('workspace', 'slug', 'petCareSystemSetup', 'petServices', 'petAdoptions', 'petGroomingPackage', 'serviceTaglineLabel', 'serviceTitle', 'adoptionTaglineLabel', 'adoptionTitle', 'packageTaglineLabel', 'packageTitle', 'reviewTaglineLabel', 'reviewHeadingTitle', 'reviewFormHeadingTitle', 'reviewFormSubTitle', 'petCareReviews', 'ctaHeadingTitle', 'ctaDescription', 'decodedbannerImages', 'bannerTagline', 'bannerHeadingTitle', 'bannerSubTitle', 'bannerDecorativeImage'));
            } else {
                return redirect()->back()->with('error', __('Module is not active'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ReviewFormDataStore(Request $request, $slug = null)
    {
        try {
            $workspace = WorkSpace::where('slug', $slug)->first();
            $validator = Validator::make(
                $request->all(),
                [
                    'reviewer_name'   => 'required|string|max:255',
                    'reviewer_email'  => 'required|email|max:255',
                    'rating'          => 'required|integer|min:1|max:5',
                    'review'          => 'required|string|max:255',
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
            $petCareReview->workspace           = $workspace->id;
            $petCareReview->created_by          = $workspace->created_by;
            $petCareReview->save();

            event(new CreatePetCareReview($request, $petCareReview));

            return redirect()->back()->with('success', __('The review has been saved successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ShowPackagesPage($slug = null)
    {
        try {
            [$workspace, $petCareSystemSetup, $status] = $this->loadWorkspaceData($slug);

            $petGroomingPackages = PetGroomingPackage::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->latest()->paginate(9);

            $paymentPoliciesTaglineLabel        = $petCareSystemSetup['payment_policies_tagline_label']->value ?? null;
            $paymentPoliciesHeadingTitle        = $petCareSystemSetup['payment_policies_heading_title']->value ?? null;

            if (isset($petCareSystemSetup['payment_policies'])) {
                $decodedPaymentPolicies = json_decode($petCareSystemSetup['payment_policies']->value, true) ?? [];
            } else {
                $decodedPaymentPolicies = [];
            }

            if ($status == true) {
                return view('pet-care::frontend.partials.packages', compact('workspace', 'slug', 'petCareSystemSetup', 'petGroomingPackages', 'paymentPoliciesTaglineLabel', 'paymentPoliciesHeadingTitle', 'decodedPaymentPolicies'));
            } else {
                return redirect()->back()->with('error', __('Module is not active'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ShowServicesPage($slug = null)
    {
        try {
            [$workspace, $petCareSystemSetup, $status] = $this->loadWorkspaceData($slug);

            $petServices = PetService::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->latest()->paginate(9);

            if ($status == true) {
                return view('pet-care::frontend.partials.services', compact('workspace', 'slug', 'petCareSystemSetup', 'petServices'));
            } else {
                return redirect()->back()->with('error', __('Module is not active'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ShowServiceDetailsPage($slug = null, $serviceId = null)
    {
        try {
            [$workspace, $petCareSystemSetup, $status] = $this->loadWorkspaceData($slug);

            $decryptedServiceId = \Illuminate\Support\Facades\Crypt::decrypt($serviceId);
            $petService = PetService::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->find($decryptedServiceId);
            if (!$petService || $petService->created_by != $workspace->created_by || $petService->workspace != $workspace->id) {
                return redirect()->back()->with('error', __('Pet service not found.'));
            }
            $serviceReviews  =  $petService->reviews()->get();

            $serviceDetailsHeadingTaglineLabel      = $petCareSystemSetup['service_details_heading_tagline_label']->value ?? null;

            $serviceDetailsFeaturesTaglineLabel     = $petCareSystemSetup['service_details_features_tagline_label']->value ?? null;
            $serviceDetailsFeaturesHeadingTitle     = $petCareSystemSetup['service_details_features_heading_title']->value ?? null;

            $serviceDetailsProcessTaglineLabel      = $petCareSystemSetup['service_details_process_tagline_label']->value ?? null;
            $serviceDetailsProcessHeadingTitle      = $petCareSystemSetup['service_details_process_heading_title']->value ?? null;

            $serviceFeatures                        = $petService->serviceIncludedFeatures()->get();
            $serviceProcessSteps                    = $petService->serviceProcessSteps()->get();

            $serviceReviewHeadingTitle              = $petCareSystemSetup['service_review_heading_title']->value ?? null;
            $serviceReviewFormHeadingTitle          = $petCareSystemSetup['service_review_form_heading_title']->value ?? null;
            $serviceReviewFormSubTitle              = $petCareSystemSetup['service_review_form_sub_title']->value ?? null;

            if ($status == true) {
                return view('pet-care::frontend.partials.service_details', compact('workspace', 'slug', 'petCareSystemSetup', 'petService', 'serviceReviews', 'serviceDetailsHeadingTaglineLabel', 'serviceDetailsFeaturesTaglineLabel', 'serviceDetailsFeaturesHeadingTitle', 'serviceDetailsProcessTaglineLabel', 'serviceDetailsProcessHeadingTitle', 'serviceFeatures', 'serviceProcessSteps', 'serviceReviewHeadingTitle', 'serviceReviewFormHeadingTitle', 'serviceReviewFormSubTitle'));
            } else {
                return redirect()->back()->with('error', __('Module is not active'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ServiceReviewFormDataStore(Request $request, $slug = null)
    {
        try {
            $workspace = WorkSpace::where('slug', $slug)->first();
            $validator = Validator::make(
                $request->all(),
                [
                    'service_reviewer_name'   => 'required|string|max:255',
                    'service_reviewer_email'  => 'required|email|max:255',
                    'service_id'              => 'required|exists:pet_services,id',
                    'service_rating'          => 'required|integer|min:1|max:5',
                    'service_review'          => 'required|string|max:255',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $data = $validator->validated();

            $displayStatus = ($request->input('display_status', '0') === '1') ? 'on' : 'off';

            $serviceReview = new PetServiceReview();
            $serviceReview->reviewer_name       = $data['service_reviewer_name'];
            $serviceReview->reviewer_email      = $data['service_reviewer_email'];
            $serviceReview->service_id          = $data['service_id'];
            $serviceReview->rating              = $data['service_rating'];
            $serviceReview->review              = $data['service_review'];
            $serviceReview->display_status      = $displayStatus;
            $serviceReview->review_status       = 'pending';
            $serviceReview->workspace           = $workspace->id;
            $serviceReview->created_by          = $workspace->created_by;
            $serviceReview->save();

            event(new CreateServiceReview($request, $serviceReview));

            return redirect()->back()->with('success', __('The service review has been saved successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ShowAboutUsPage($slug = null)
    {
        try {
            [$workspace, $petCareSystemSetup, $status] = $this->loadWorkspaceData($slug);

            $aboutUsTitle           = $petCareSystemSetup['about_us_title']->value ?? null;
            $aboutUsDescription     = $petCareSystemSetup['about_us_description']->value ?? null;
            $aboutUsImage           = $petCareSystemSetup['about_us_image']->value ?? null;
            $milestonesTaglineLabel           = $petCareSystemSetup['milestones_tagline_label']->value ?? null;
            $milestonesTitle                  = $petCareSystemSetup['milestones_title']->value ?? null;
            $teamMemberTaglineLabel           = $petCareSystemSetup['team_member_tagline_label']->value ?? null;
            $teamMemberTitle                = $petCareSystemSetup['team_member_title']->value ?? null;

            $totalPetServices = PetService::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->count();
            $totalPetPackages = PetGroomingPackage::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->count();
            $totalPetAdoptions = PetAdoption::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->whereIn('availability', ['available_now', 'coming_soon', 'adopted'])->count();
            $totalPetAppointments = PetAppointment::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->count();

            $staff = User::where('workspace_id', $workspace->id)
                ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
                ->where('users.created_by', $workspace->created_by)->emp()
                ->select('users.id', 'users.name', 'users.email', 'users.avatar')->get();

            if ($status == true) {
                return view('pet-care::frontend.partials.about_us', compact('workspace', 'slug', 'petCareSystemSetup', 'aboutUsTitle', 'aboutUsDescription', 'aboutUsImage', 'milestonesTaglineLabel', 'milestonesTitle', 'totalPetServices', 'totalPetPackages', 'totalPetAdoptions', 'totalPetAppointments', 'teamMemberTaglineLabel', 'teamMemberTitle', 'staff'));
            } else {
                return redirect()->back()->with('error', __('Module is not active'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ShowFAQPage($slug = null)
    {
        try {
            [$workspace, $petCareSystemSetup, $status] = $this->loadWorkspaceData($slug);

            $faqs = PetCareFAQ::with('questionAnswers')->where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->get();

            $haveQuestionsTitle        = $petCareSystemSetup['have_questions_title']->value ?? null;
            $haveQuestionsDescription  = $petCareSystemSetup['have_questions_description']->value ?? null;
            $contactInfoPhoneNo        = $petCareSystemSetup['contact_info_phone_no']->value ?? null;

            if ($status == true) {
                return view('pet-care::frontend.partials.faq', compact('workspace', 'slug', 'petCareSystemSetup', 'faqs', 'haveQuestionsTitle', 'haveQuestionsDescription', 'contactInfoPhoneNo'));
            } else {
                return redirect()->back()->with('error', __('Module is not active'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ShowContactUsPage($slug = null)
    {
        try {
            [$workspace, $petCareSystemSetup, $status] = $this->loadWorkspaceData($slug);

            $contactFormTaglineLabel        = $petCareSystemSetup['contact_form_tagline_label']->value ?? null;
            $contactFormTitle               = $petCareSystemSetup['contact_form_title']->value ?? null;
            $contactFormResponseNote        = $petCareSystemSetup['contact_form_response_note']->value ?? null;
            $contactGoogleMapIframe         = $petCareSystemSetup['contact_google_map_iframe']->value ?? null;
            $contactInfoTaglineLabel        = $petCareSystemSetup['contact_info_tagline_label']->value ?? null;
            $contactInfoTitle               = $petCareSystemSetup['contact_info_title']->value ?? null;
            $contactInfoLocationTitle       = $petCareSystemSetup['contact_info_location_title']->value ?? null;
            $contactInfoLocation            = $petCareSystemSetup['contact_info_location']->value ?? null;
            $contactInfoPhoneTitle          = $petCareSystemSetup['contact_info_phone_title']->value ?? null;
            $contactInfoPhoneNo             = $petCareSystemSetup['contact_info_phone_no']->value ?? null;
            $contactInfoEmergencyNote       = $petCareSystemSetup['contact_info_emergency_note']->value ?? null;
            $contactInfoEmailTitle          = $petCareSystemSetup['contact_info_email_title']->value ?? null;
            $contactInfoEmailAddress        = $petCareSystemSetup['contact_info_email_address']->value ?? null;
            $contactInfoLocationIcon        = $petCareSystemSetup['contact_info_location_icon']->value ?? null;
            $contactInfoPhoneIcon           = $petCareSystemSetup['contact_info_phone_icon']->value ?? null;
            $contactInfoEmailIcon           = $petCareSystemSetup['contact_info_email_icon']->value ?? null;
            $contactInfoStartDay            = $petCareSystemSetup['contact_info_start_day']->value ?? null;
            $contactInfoEndDay              = $petCareSystemSetup['contact_info_end_day']->value ?? null;
            $contactInfoOpenTime            = $petCareSystemSetup['contact_info_open_time']->value ?? null;
            $contactInfoCloseTime           = $petCareSystemSetup['contact_info_close_time']->value ?? null;

            $contactInfoSocialLinks      = PetCareSocialLink::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->get(['social_media_name', 'social_media_icon', 'social_media_link']);

            if ($status == true) {
                return view('pet-care::frontend.partials.contact_us', compact('workspace', 'slug', 'petCareSystemSetup', 'contactFormTaglineLabel', 'contactFormTitle', 'contactFormResponseNote', 'contactGoogleMapIframe', 'contactInfoTaglineLabel', 'contactInfoTitle', 'contactInfoLocationTitle', 'contactInfoLocation', 'contactInfoPhoneTitle', 'contactInfoPhoneNo', 'contactInfoEmergencyNote', 'contactInfoEmailTitle', 'contactInfoEmailAddress', 'contactInfoLocationIcon', 'contactInfoPhoneIcon', 'contactInfoEmailIcon', 'contactInfoSocialLinks', 'contactInfoStartDay', 'contactInfoEndDay', 'contactInfoOpenTime', 'contactInfoCloseTime'));
            } else {
                return redirect()->back()->with('error', __('Module is not active'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ContactUsFormDataStore(Request $request, $slug = null)
    {
        try {
            $workspace = WorkSpace::where('slug', $slug)->first();
            $validator = Validator::make(
                $request->all(),
                [
                    'name'          => 'required|string|max:255',
                    'email'         => 'required|email|max:255',
                    'subject'       => 'required|string|max:255',
                    'message'       => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $data = $validator->validated();

            $contact = new PetCareContact();
            $contact->name          = $data['name'];
            $contact->email         = $data['email'];
            $contact->subject       = $data['subject'];
            $contact->message       = $data['message'];
            $contact->status        = 'new';
            $contact->workspace     = $workspace->id;
            $contact->created_by    = $workspace->created_by;
            $contact->save();


            event(new CreatePetCareContact($request, $contact));

            return redirect()->back()->with('success', __('Thank you! Your message has been sent successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ShowAdoptionFormPage($slug = null, $adoptionId)
    {
        try {
            [$workspace, $petCareSystemSetup, $status] = $this->loadWorkspaceData($slug);

            $adoptionApplicationFormTaglineLabel        = $petCareSystemSetup['adoption_application_form_tagline_label']->value ?? null;
            $adoptionApplicationFormHeadingTitle               = $petCareSystemSetup['adoption_application_form_heading_title']->value ?? null;

            $decryptedAdoptionId = \Illuminate\Support\Facades\Crypt::decrypt($adoptionId);
            $petAdoption = PetAdoption::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->find($decryptedAdoptionId);
            if (!$petAdoption || $petAdoption->created_by != $workspace->created_by || $petAdoption->workspace != $workspace->id) {
                return redirect()->back()->with('error', __('Pet adoption not found.'));
            }

            if ($status == true) {
                return view('pet-care::frontend.partials.adoption_application_form', compact('workspace', 'slug', 'petCareSystemSetup', 'adoptionApplicationFormTaglineLabel', 'adoptionApplicationFormHeadingTitle', 'petAdoption', 'adoptionId'));
            } else {
                return redirect()->back()->with('error', __('Module is not active'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function AdoptionFormDataStore(Request $request, $slug = null, $adoptionId)
    {
        try {
            $workspace = WorkSpace::where('slug', $slug)->first();
            $validator = Validator::make(
                $request->all(),
                [
                    'full_name' => 'required|string|max:255',
                    'email'     => 'required|email|max:255',
                    'address'   => 'required|string|max:500',
                    'reason'    => 'required|string|max:1000',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if ($request->input('phone')) {
                $validator = Validator::make($request->all(), ['phone' => 'required|regex:/^\+\d{1,3}\d{9,13}$/']);
                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            $decryptedAdoptionId = \Illuminate\Support\Facades\Crypt::decrypt($adoptionId);
            $petAdoption = PetAdoption::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->find($decryptedAdoptionId);
            if (!$petAdoption || $petAdoption->created_by != $workspace->created_by || $petAdoption->workspace != $workspace->id) {
                return redirect()->back()->with('error', __('Pet adoption not found.'));
            }

            $petAdoptionRequest = new PetAdoptionRequest();
            $petAdoptionRequest->pet_adoption_id         = $petAdoption->id;
            $petAdoptionRequest->adoption_request_number = PetAdoptionRequestController::petAdoptionRequestNumber();
            $petAdoptionRequest->adopter_name            = $request['full_name'];
            $petAdoptionRequest->email                   = $request['email'];
            $petAdoptionRequest->contact_number          = $request['phone'];
            $petAdoptionRequest->address                 = $request['address'];
            $petAdoptionRequest->reason_for_adoption     = $request['reason'];
            $petAdoptionRequest->request_status          = 'pending';
            $petAdoptionRequest->workspace               = $workspace->id;
            $petAdoptionRequest->created_by              = $workspace->created_by;
            $petAdoptionRequest->save();

            event(new CreatePetAdoptionRequest($request, $petAdoptionRequest));

            return redirect()->back()->with('success', __('The adoption request has been created successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ShowAppointmentFormPage($slug = null, $serviceId = null, $packageId = null)
    {
        try {
            [$workspace, $petCareSystemSetup, $status] = $this->loadWorkspaceData($slug);

            $appointmentBookingFormTaglineLabel        = $petCareSystemSetup['appointment_booking_form_tagline_label']->value ?? null;
            $appointmentBookingFormHeadingTitle        = $petCareSystemSetup['appointment_booking_form_heading_title']->value ?? null;
            $appointmentBookingFormResponseNote        = $petCareSystemSetup['appointment_booking_form_response_note']->value ?? null;
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

            $petService = null;
            $petPackage = null;

            if (!empty($serviceId) && $serviceId !== '0') {
                $decryptedServiceId = \Illuminate\Support\Facades\Crypt::decrypt($serviceId);
                $petService = PetService::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->find($decryptedServiceId);
                if (!$petService || $petService->created_by != $workspace->created_by || $petService->workspace != $workspace->id) {
                    return redirect()->back()->with('error', __('Pet adoption not found.'));
                }
            }

            if (!empty($packageId) && $packageId !== '0') {
                $decryptedPackageId = \Illuminate\Support\Facades\Crypt::decrypt($packageId);
                $petPackage = PetGroomingPackage::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->find($decryptedPackageId);
                if (!$petPackage || $petPackage->created_by != $workspace->created_by || $petPackage->workspace != $workspace->id) {
                    return redirect()->back()->with('error', __('Pet Grooming Package not found.'));
                }
            }

            $packages = PetGroomingPackage::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->pluck('package_name', 'id');
            $services = PetService::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->pluck('service_name', 'id');

            if ($status == true) {
                return view('pet-care::frontend.partials.appointment_form', compact('workspace', 'slug', 'petCareSystemSetup', 'petService', 'serviceId', 'petPackage', 'packageId', 'services', 'packages', 'appointmentBookingFormTaglineLabel', 'appointmentBookingFormHeadingTitle', 'appointmentBookingFormResponseNote', 'time_options'));
            } else {
                return redirect()->back()->with('error', __('Module is not active'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function AppointmentFormDataStore(Request $request, $slug = null)
    {
        try {
            $workspace = WorkSpace::where('slug', $slug)->first();
            $validator = Validator::make(
                $request->all(),
                [
                    'name'                          =>  'required|string|max:255',
                    'email'                         =>  'required|email',
                    'address'                       =>  'nullable|string',
                    'pet_name'                      => 'required|string|max:255',
                    'species'                       => 'required|string|max:255',
                    'breed'                         => 'required|string|max:255',
                    'date_of_birth'                 => 'required|date',
                    'gender'                        => ['required', Rule::in(['Male', 'Female'])],
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
                ],
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

            // Create PetOwner
            $owner = new PetOwner();
            $owner->owner_name     = $request->name;
            $owner->email          = $request->email;
            $owner->contact_number = $request->contact_number;
            $owner->address        = $request->address ?? null;
            $owner->workspace      = $workspace->id;
            $owner->created_by     = $workspace->created_by;
            $owner->save();

            // Create Pets
            $pet = new Pets();
            $pet->pet_owner_id   = $owner->id;
            $pet->pet_name       = $request->pet_name;
            $pet->species        = $request->species;
            $pet->breed          = $request->breed;
            $pet->date_of_birth  = $request->date_of_birth ?? null;
            $pet->gender         = $request->gender;
            $pet->workspace      = $workspace->id;
            $pet->created_by     = $workspace->created_by;
            $pet->save();

            // Create PetAppointment
            $appointment = new PetAppointment();
            $appointment->appointment_number            = PetAppointmentsController::petAppointmentNumber();
            $appointment->pet_owner_id                  = $owner->id;
            $appointment->pet_id                        = $pet->id;
            $appointment->appointment_date              = $request->appointment_date ?? now();
            $appointment->appointment_time              = $request->appointment_time ?? null;
            $appointment->appointment_status            = 'pending';
            $appointment->total_service_package_amount  = $request->total_service_package_amount;
            $appointment->notes                         = $request->notes ?? null;
            $appointment->workspace                     = $workspace->id;
            $appointment->created_by                    = $workspace->created_by;
            $appointment->save();

            if (is_array($request->service_id)) {
                $appointment->appointmentServices()->sync($request->service_id);
            }

            if (is_array($request->package_id)) {
                $appointment->appointmentPackages()->sync($request->package_id);
            }

            event(new CreatePetAppointment($request, $owner, $pet, $appointment));

            return redirect()->back()->with('success', __('The pet appointment has been created successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function FrontgetMultipulPackagePrice(Request $request, $slug = null)
    {
        try {
            [$workspace,$status] = $this->loadWorkspaceData($slug);

            $packageIds = $request->input('packageIds', []);

            if (!is_array($packageIds)) {
                return response()->json(['error' => 'Invalid input. Expected an array of package IDs.'], 422);
            }

            $packages = PetGroomingPackage::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->whereIn('id', $packageIds)->pluck('total_package_amount', 'id');

            if ($status && $packages) {
                return response()->json(['prices' => array_values($packages->toArray())]);
            }

            return response()->json(['error' => 'Package not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function FrontgetMultipulServicePrice(Request $request, $slug = null)
    {
        try {
            [$workspace,$status] = $this->loadWorkspaceData($slug);

            $serviceIds = $request->input('serviceIds', []);

            if (!is_array($serviceIds)) {
                return response()->json(['error' => 'Invalid input. Expected an array of service IDs.'], 422);
            }

            $services = PetService::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->whereIn('id', $serviceIds)->pluck('price', 'id');

            if ($status && $services) {
                return response()->json(['prices' => array_values($services->toArray())]);
            }

            return response()->json(['error' => 'Service not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
