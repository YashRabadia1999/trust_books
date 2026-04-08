<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\Entities\PetCareFAQ;
use Workdo\PetCare\Entities\PetCareSystemSetup;
use Workdo\PetCare\Events\CreatePetCareFAQ;
use Workdo\PetCare\Events\DestroyPetCareFAQ;
use Workdo\PetCare\Events\UpdatePetCareFAQ;

class PetCareFAQController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('petcare_faq manage')) {
            $faqs = PetCareFAQ::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->orderBy('id', 'desc')->get();
            $petcare_settings = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->whereIn('key', ['have_questions_title', 'have_questions_description'])->get();

            $settings = [];

            foreach ($petcare_settings as $setting) {
                $settings[$setting->key] = $setting->value;
            }
            return view('pet-care::system_setup.FAQ.index', ['petcare_system_setup'  => $settings, 'faqs'  => $faqs]);
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
        if (\Auth::user()->isAbleTo('petcare_faq create')) {
            return view('pet-care::system_setup.FAQ.create');
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
        if (\Auth::user()->isAbleTo('petcare_faq create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'faq_icon'      => 'required|string|max:255',
                    'faq_topic'     => 'required|string|max:255',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $data = $validator->validated();
            $faq = new PetCareFAQ();
            $faq->faq_icon          = $data['faq_icon'];
            $faq->faq_topic         = $data['faq_topic'];
            $faq->workspace         = getActiveWorkSpace();
            $faq->created_by        = creatorId();
            $faq->save();

            event(new CreatePetCareFAQ($request, $faq));

            return redirect()->back()->with('success', __('The FAQs has been created successfully.'));
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
    public function edit($faqId)
    {
        if (\Auth::user()->isAbleTo('petcare_faq edit')) {
            $decryptedFAQsId = \Illuminate\Support\Facades\Crypt::decrypt($faqId);
            $FAQs = PetCareFAQ::find($decryptedFAQsId);
            if (!$FAQs || $FAQs->created_by != creatorId() || $FAQs->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('FAQ not found.'));
            }
            return view('pet-care::system_setup.FAQ.edit', compact('faqId', 'FAQs'));
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
    public function update(Request $request, $faqId)
    {
        if (\Auth::user()->isAbleTo('petcare_faq edit')) {

            $decryptedFAQsId = \Illuminate\Support\Facades\Crypt::decrypt($faqId);
            $faq = PetCareFAQ::find($decryptedFAQsId);
            if (!$faq || $faq->created_by != creatorId() || $faq->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('FAQ not found.'));
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'faq_icon'      => 'required|string|max:255',
                    'faq_topic'     => 'required|string|max:255',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $data = $validator->validated();
            $faq->faq_icon          = $data['faq_icon'];
            $faq->faq_topic         = $data['faq_topic'];
            $faq->save();

            event(new UpdatePetCareFAQ($request, $faq));

            return redirect()->back()->with('success', __('The FAQs has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($faqId)
    {
        if (\Auth::user()->isAbleTo('petcare_faq delete')) {
            try {
                $decryptedFAQsId = \Illuminate\Support\Facades\Crypt::decrypt($faqId);
                $faq = PetCareFAQ::find($decryptedFAQsId);
                if (!$faq || $faq->created_by != creatorId() || $faq->workspace != getActiveWorkSpace()) {
                    return redirect()->back()->with('error', __('FAQ not found.'));
                }

                event(new DestroyPetCareFAQ($faq));
                $faq->delete();

                return redirect()->back()->with('success', __('The FAQ has been deleted.'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function faqSettingStore(Request $request)
    {
        if (\Auth::user()->isAbleTo('petcare_faq manage')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'have_questions_title'           =>  'required|string|max:255',
                    'have_questions_description'     =>  'required|string|max:255',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $Post = [
                'have_questions_title'          =>  $request->input('have_questions_title'),
                'have_questions_description'    =>  $request->input('have_questions_description'),
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
            return redirect()->back()->with('success', __('The have questions setting have been saved successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
