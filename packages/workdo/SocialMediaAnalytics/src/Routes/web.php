<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Workdo\SocialMediaAnalytics\Http\Controllers\SocialMediaAnalyticsController;
use Workdo\SocialMediaAnalytics\Http\Controllers\SocialMediaSystemSetController;
use Workdo\SocialMediaAnalytics\Services\SocialYouTubeService;

Route::group(['middleware' => ['web', 'auth', 'verified','PlanModuleCheck:SocialMediaAnalytics']], function () {
    Route::get('socialmediaanalytics/facebook', [SocialMediaAnalyticsController::class,'getFacebookData'])->name('get.facebook.data');    
    Route::get('socialmediaanalytics/instagram', [SocialMediaAnalyticsController::class,'getInstagramData'])->name('get.instagram.data');    
    Route::get('socialmediaanalytics/youtube', [SocialMediaAnalyticsController::class,'getYoutubeData'])->name('get.youtube.data');    
    Route::get('socialmedia-system-setup/facebook', [SocialMediaSystemSetController::class,'facebookIndex'])->name('social-system.index');
    Route::post('socialmedia-system-setup/facebook/store', [SocialMediaSystemSetController::class,'facebookStore'])->name('socialmediaanalytics-facebook.store');
    Route::get('socialmedia-system-setup/instagram', [SocialMediaSystemSetController::class,'instagramIndex'])->name('socialmediaanalytics-instagram.index');
    Route::post('socialmedia-system-setup/instagram/store', [SocialMediaSystemSetController::class,'instagramStore'])->name('socialmediaanalytics-instagram.store');
    Route::get('socialmedia-system-setup/youtube', [SocialMediaSystemSetController::class,'youtubeIndex'])->name('socialmediaanalytics-youtube.index');
    Route::post('socialmedia-system-setup/youtube/store', [SocialMediaSystemSetController::class,'youtubeStore'])->name('socialmediaanalytics-youtube.store');
    Route::get('social/auth/callback', [SocialMediaSystemSetController::class, 'youtubeCallBack']);
    Route::get('social/auth', function () {
        return redirect((new SocialYouTubeService())->getAuthUrl());
    })->name('social.youtube.auth');
});


