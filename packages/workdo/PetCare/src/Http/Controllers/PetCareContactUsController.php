<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\PetCare\DataTables\PetCareContactsDatatable;
use Workdo\PetCare\Entities\PetCareContact;
use Workdo\PetCare\Entities\PetCareSystemSetup;
use Workdo\PetCare\Events\DestroyPetCareContact;
use Workdo\PetCare\Events\UpdatePetCareContact;

class PetCareContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PetCareContactsDatatable $datatable)
    {
        if (\Auth::user()->isAbleTo('petcare_contacts manage')) {
            return $datatable->render('pet-care::contacts.index');
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
        return redirect()->back();
        return view('pet-care::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        return redirect()->back();
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
    public function edit($contactId)
    {
        if (\Auth::user()->isAbleTo('petcare_contacts edit')) {
            $decryptedContactId = \Illuminate\Support\Facades\Crypt::decrypt($contactId);
            $contact = PetCareContact::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedContactId);
            if (!$contact || $contact->created_by != creatorId() || $contact->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Contact not found.')], 401);
            }
            $status = PetCareContact::$Status;
            return view('pet-care::contacts.edit', compact('contact', 'contactId', 'status'));
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
    public function update(Request $request, $contactId)
    {
        if (\Auth::user()->isAbleTo('petcare_contacts edit')) {
            try {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name'          => 'required|string|max:255',
                        'email'         => 'required|email|max:255',
                        'subject'       => 'required|string|max:255',
                        'message'       => 'required|string',
                        'status'        => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $data = $validator->validated();

                $decryptedContactId = \Illuminate\Support\Facades\Crypt::decrypt($contactId);
                $contact = PetCareContact::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedContactId);
                if (!$contact || $contact->created_by != creatorId() || $contact->workspace != getActiveWorkSpace()) {
                    return redirect()->back()->with('error', __('Contact not found.'));
                }

                $contact->name    = $data['name'];
                $contact->email   = $data['email'];
                $contact->subject = $data['subject'];
                $contact->message = $data['message'];
                $contact->status  = $data['status'];
                $contact->save();

                event(new UpdatePetCareContact($request, $contact));

                return redirect()->back()->with('success', __('The contact details are updated successfully.'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($contactId)
    {
        if (\Auth::user()->isAbleTo('petcare_contacts delete')) {
            $decryptedContactId = \Illuminate\Support\Facades\Crypt::decrypt($contactId);
            $contact = PetCareContact::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedContactId);
            if (!$contact || $contact->created_by != creatorId() || $contact->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Contact not found.'));
            }
            event(new DestroyPetCareContact($contact));
            $contact->delete();

            return redirect()->back()->with('success', __('The contact has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function indexContactUsSettingPage()
    {
        if (\Auth::user()->isAbleTo('petcare_contact_us manage')) {
            $petcare_settings = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();

            $settings = [];

            foreach ($petcare_settings as $setting) {
                $settings[$setting->key] = $setting->value;
            }

            return view('pet-care::system_setup.contact_us.index', ['petcare_system_setup'  => $settings]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function contactUsSettingStore(Request $request)
    {
        if (\Auth::user()->isAbleTo('petcare_contact_us manage')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'contact_form_tagline_label'    => 'required|string|max:255',
                    'contact_form_title'            => 'required|string|max:255',
                    'contact_form_response_note'    => 'nullable|string|max:255',
                    'contact_google_map_iframe'         => [
                        'required',
                        'string',
                        function ($attribute, $value, $fail) {
                            if (!preg_match('/<iframe.*?src="([^"]+)".*?>/', $value, $matches)) {
                                $fail(__('Invalid Google Map iframe. Please provide a valid embed iframe.'));
                                return;
                            }
                            $iframeSrc = $matches[1];
                            if (!preg_match('/^https:\/\/www\.google\.com\/maps\/embed\?.*/', $iframeSrc)) {
                                $fail(__('The iframe source must be a valid Google Maps embed link.'));
                            }
                        }
                    ],
                    'contact_info_tagline_label'     => 'required|string|max:255',
                    'contact_info_title'             => 'required|string|max:255',
                    'contact_info_location_title'    => 'required|string|max:255',
                    'contact_info_location'          => 'required',
                    'contact_info_phone_title'       => 'required|string|max:255',
                    'contact_info_emergency_note'    => 'nullable|string|max:255',
                    'contact_info_email_title'       => 'required|string|max:255',
                    'contact_info_email_address'     => 'required|string|max:255',
                    'contact_info_location_icon'     => 'required|string|max:255',
                    'contact_info_phone_icon'        => 'required|string|max:255',
                    'contact_info_email_icon'        => 'required|string|max:255',
                ],
            );


            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->getMessageBag()->first());
            }

            if ($request->input('contact_info_phone_no')) {
                $validator = \Validator::make($request->all(), ['contact_info_phone_no' => 'required|regex:/^\+\d{1,3}\d{9,13}$/']);
                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            $textFields = [
                'contact_form_tagline_label'    => $request->input('contact_form_tagline_label'),
                'contact_form_title'            => $request->input('contact_form_title'),
                'contact_form_response_note'    => $request->input('contact_form_response_note'),
                'contact_google_map_iframe'     => $request->input('contact_google_map_iframe'),
                'contact_info_tagline_label'    => $request->input('contact_info_tagline_label'),
                'contact_info_title'            => $request->input('contact_info_title'),
                'contact_info_location_title'   => $request->input('contact_info_location_title'),
                'contact_info_location'         => $request->input('contact_info_location'),
                'contact_info_phone_title'      => $request->input('contact_info_phone_title'),
                'contact_info_phone_no'         => $request->input('contact_info_phone_no'),
                'contact_info_emergency_note'   => $request->input('contact_info_emergency_note'),
                'contact_info_email_title'      => $request->input('contact_info_email_title'),
                'contact_info_email_address'    => $request->input('contact_info_email_address'),
                'contact_info_location_icon'    => $request->input('contact_info_location_icon'),
                'contact_info_phone_icon'       => $request->input('contact_info_phone_icon'),
                'contact_info_email_icon'       => $request->input('contact_info_email_icon'),
            ];

            foreach ($textFields as $key => $value) {
                PetCareSystemSetup::updateOrCreate(
                    [
                        'key' => $key,
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }

            return redirect()->back()->with('success', __('The contact us details have been saved successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function messageShow($contactId)
    {
        $contact = PetCareContact::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($contactId);
        if (!$contact || $contact->created_by != creatorId() || $contact->workspace != getActiveWorkSpace()) {
            return response()->json(['error' => __('Pet Service not found.')], 401);
        }
        return view('pet-care::contacts.message', compact('contact'));
    }
}
