<?php

namespace Workdo\SocialMediaAnalytics\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\SocialMediaAnalytics\Services\SocialYouTubeService;
use Workdo\VCard\Entities\Social;

class SocialMediaSystemSetController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function facebookIndex()
    {
        $settings = getCompanyAllSetting();       
        return view('social-media-analytics::system_setup.facebook',compact('settings'));
    }

    public function facebookStore(Request $request)
    {
        if (Auth::user()->isAbleTo('socialmediaanalytics manage')) {
        
            $validator = Validator::make(
                $request->all(),
                [
                    'social_media_facebook_client_id' => 'required',
                    'social_media_facebook_access_token' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
    
                return redirect()->back()->with('error', $messages->first());
            }
    
            $post = $request->except('_token');
            foreach ($post as $key => $value) {
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];
    
                Setting::updateOrCreate($data, ['value' => $value]);
            }
    
            comapnySettingCacheForget();
    
            return redirect()->back()->with('success', __('FaceBook Settings Save Successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    } 

    public function instagramIndex(){
        $settings = getCompanyAllSetting();
        return view('social-media-analytics::system_setup.instagram',compact('settings'));
    }

    public function instagramStore(Request $request)
    {
        if (Auth::user()->isAbleTo('socialmediaanalytics manage')) {
        
            $validator = Validator::make(
                $request->all(),
                [
                    'social_media_instagram_client_id' => 'required',
                    'social_media_facebook_i_client_id' => 'required',
                    'social_media_instagram_access_token' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
    
                return redirect()->back()->with('error', $messages->first());
            }
    
            $post = $request->except('_token');
            foreach ($post as $key => $value) {
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];
    
                Setting::updateOrCreate($data, ['value' => $value]);
            }
    
            comapnySettingCacheForget();
    
            return redirect()->back()->with('success', __('Instagram Settings Save Successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function youtubeIndex(){
        $settings = getCompanyAllSetting();
        return view('social-media-analytics::system_setup.youtube',compact('settings'));
    }
    
    public function youtubeStore(Request $request)
    {
        if (Auth::user()->isAbleTo('socialmediaanalytics manage')) {
        
            $validator = Validator::make(
                $request->all(),
                [
                    'social_media_youtube_google_client_id' => 'required',
                    'social_media_youtube_google_client_secret' => 'required',
                    'social_media_youtube_google_client_secret' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
    
                return redirect()->back()->with('error', $messages->first());
            }
    
            $post = $request->except('_token');
            $post['social_media_youtube_google_redirect_uri'] = env('APP_URL').'social/auth/callback';

            foreach ($post as $key => $value) {
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];
    
                Setting::updateOrCreate($data, ['value' => $value]);
            }
    
            comapnySettingCacheForget();
    
            return redirect()->back()->with('success', __('Youtube Settings Save Successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function youtubeCallBack(Request $request){
        if(\Auth::user()->isAbleTo('socialmediaanalytics manage'))
        {
            if ($request->has('code')) {
                try {
                    $youtubeService = new SocialYouTubeService();
                    $youtubeService->authenticate($request->code);
                    return redirect('/socialmedia-system-setup/youtube')->with('success', __('YouTube connected successfully.'));
                } catch (\Exception $e) {
                    return redirect('/socialmedia-system-setup/youtube')->with('error', __($e->getMessage()));
                }
            }
            return redirect('/socialmedia-system-setup/youtube')->with('error', __('YouTube authentication failed.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
