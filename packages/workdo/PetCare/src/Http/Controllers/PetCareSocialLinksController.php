<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\Entities\PetCareSocialLink;
use Workdo\PetCare\Events\CreatePetCareSocialLink;
use Workdo\PetCare\Events\DestroyPetCareSocialLink;
use Workdo\PetCare\Events\UpdatePetCareSocialLink;

class PetCareSocialLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('petcare_social_links manage')) {
            $socialMediaLinks = PetCareSocialLink::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->orderBy('id', 'desc')->get();
            return view('pet-care::system_setup.social_links.index', compact('socialMediaLinks'));
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
        if (\Auth::user()->isAbleTo('petcare_social_links create')) {
            return view('pet-care::system_setup.social_links.create');
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
        if (\Auth::user()->isAbleTo('petcare_social_links create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'social_media_icon' => 'required|string|max:255',
                    'social_media_name' => 'required|string|max:255',
                    'social_media_link' => 'required|url|max:255',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $data = $validator->validated();

            $socialLink = new PetCareSocialLink();
            $socialLink->social_media_icon   = $data['social_media_icon'];
            $socialLink->social_media_name  = $data['social_media_name'];
            $socialLink->social_media_link  = $data['social_media_link'];
            $socialLink->workspace  = getActiveWorkSpace();
            $socialLink->created_by = creatorId();
            $socialLink->save();

            event(new CreatePetCareSocialLink($request, $socialLink));

            return redirect()->back()->with('success', __('The Social Link has been created successfully.'));
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
    public function edit($socialLinkId)
    {
        if (\Auth::user()->isAbleTo('petcare_social_links edit')) {
            $decryptedSocialLinkId = \Illuminate\Support\Facades\Crypt::decrypt($socialLinkId);
            $socialLink = PetCareSocialLink::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedSocialLinkId);
            if (!$socialLink || $socialLink->created_by != creatorId() || $socialLink->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Social Link not found.')], 401);
            }
            return view('pet-care::system_setup.social_links.edit', compact('socialLinkId', 'socialLink'));
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
    public function update(Request $request, $socialLinkId)
    {
        if (\Auth::user()->isAbleTo('petcare_social_links edit')) {
            $decryptedSocialLinkId = \Illuminate\Support\Facades\Crypt::decrypt($socialLinkId);
            $socialLink = PetCareSocialLink::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedSocialLinkId);
            if (!$socialLink || $socialLink->created_by != creatorId() || $socialLink->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Social Link not found.'));
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'social_media_icon' => 'required|string|max:255',
                    'social_media_name' => 'required|string|max:255',
                    'social_media_link' => 'required|url|max:255',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $data = $validator->validated();
            $socialLink->social_media_icon  = $data['social_media_icon'];
            $socialLink->social_media_name  = $data['social_media_name'];
            $socialLink->social_media_link  = $data['social_media_link'];
            $socialLink->save();

            event(new UpdatePetCareSocialLink($request, $socialLink));

            return redirect()->back()->with('success', __('The Social Link has been Updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($socialLinkId)
    {
        if (\Auth::user()->isAbleTo('petcare_faq delete')) {
            try {
                $decryptedSocialLinkId = \Illuminate\Support\Facades\Crypt::decrypt($socialLinkId);
                $socialLink = PetCareSocialLink::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedSocialLinkId);
                if (!$socialLink || $socialLink->created_by != creatorId() || $socialLink->workspace != getActiveWorkSpace()) {
                    return redirect()->back()->with('error', __('Social Link not found.'));
                }

                event(new DestroyPetCareSocialLink($socialLink));
                $socialLink->delete();

                return redirect()->back()->with('success', __('The Social Link has been deleted.'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
