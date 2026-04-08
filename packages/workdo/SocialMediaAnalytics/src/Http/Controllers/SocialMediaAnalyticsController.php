<?php

namespace Workdo\SocialMediaAnalytics\Http\Controllers;

use App\Models\Setting;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google_Client;
use Google_Service_YouTubeAnalytics;
use Google_Service_YouTube;


class SocialMediaAnalyticsController extends Controller
{
    public $client;
    public $workspace;
    public $creatorId;
    public function getFacebookData()
    {
        $settings = getCompanyAllSetting();
        if (empty($settings['social_media_facebook_client_id']) || empty($settings['social_media_facebook_access_token'])) {
            return back()->with('error', __('please add credentials first.'));
        }

        $pageId = $settings['social_media_facebook_client_id'];
        $accessToken = $settings['social_media_facebook_access_token'];
        try {
            $pageId = $settings['social_media_facebook_client_id'];
            $accessToken = $settings['social_media_facebook_access_token'];

            // ========== 1. Page Fans ==========
            $url = "https://graph.facebook.com/v19.0/{$pageId}/insights/page_fans?period=day&access_token={$accessToken}";
            $response = Http::get($url);

            $totalFans = 0;
            $audienceGrowthData = [];

            if ($response->successful()) {
                $data = $response->json();
                $values = $data['data'][0]['values'] ?? [];
                $totalFans = $values[0]['value'] ?? 0;

                foreach ($values as $value) {
                    $audienceGrowthData[] = [
                        'date' => $value['end_time'] ?? null,
                        'fans_count' => $value['value'] ?? 0,
                    ];
                }
            }

            // ========== 2. Total Page Actions ==========
            $urls = "https://graph.facebook.com/v19.0/{$pageId}/insights/page_total_actions?access_token={$accessToken}";
            $response = Http::get($urls);
            $daily = [];
            $weekly = 0;
            $monthly = 0;

            if ($response->successful()) {
                foreach ($response->json()['data'] ?? [] as $item) {
                    foreach ($item['values'] ?? [] as $value) {
                        $formatted = [
                            'date' => $value['end_time'] ?? null,
                            'value' => $value['value'] ?? 0,
                        ];

                        if ($item['period'] === 'day') {
                            $daily[] = $formatted;
                        } elseif ($item['period'] === 'week') {
                            $weekly += $formatted['value'];
                        } elseif ($item['period'] === 'days_28') {
                            $monthly += $formatted['value'];
                        }
                    }
                }
            }

            // ========== 3. Impressions ==========
            $url = "https://graph.facebook.com/v19.0/{$pageId}/insights/page_impressions?access_token={$accessToken}";
            $response = Http::get($url);

            $idaily = [];
            $iweekly = [];
            $idays28 = [];

            if ($response->successful()) {
                foreach ($response->json()['data'] ?? [] as $metric) {
                    foreach ($metric['values'] ?? [] as $value) {
                        $formatted = [
                            'x' => $value['end_time'] ?? null,
                            'y' => $value['value'] ?? 0
                        ];

                        if ($metric['period'] === 'day') {
                            $idaily[] = $formatted;
                        } elseif ($metric['period'] === 'week') {
                            $iweekly[] = $formatted;
                        } elseif ($metric['period'] === 'days_28') {
                            $idays28[] = $formatted;
                        }
                    }
                }
            }

            // ========== 4. Post Reactions ==========
            $posts = Http::get("https://graph.facebook.com/v22.0/{$pageId}/posts?access_token={$accessToken}")->json();
            $totalReactions = 0;
            $totalComments = 0;
            $totalMessages = 0;

            foreach ($posts['data'] ?? [] as $post) {
                $postId = $post['id'] ?? null;

                // Reactions
                $reactionsUrl = "https://graph.facebook.com/v22.0/{$postId}/reactions?summary=true&access_token={$accessToken}";
                $reactions = Http::get($reactionsUrl)->json();
                $totalReactions += $reactions['summary']['total_count'] ?? 0;

                // Comments
                $commentsUrl = "https://graph.facebook.com/v22.0/{$postId}/comments?summary=true&access_token={$accessToken}";
                $comments = Http::get($commentsUrl)->json();
                $totalComments += $comments['summary']['total_count'] ?? 0;
            }

            // ========== 5. Messages ==========
            $conversations = Http::get("https://graph.facebook.com/v22.0/{$pageId}/conversations?access_token={$accessToken}")->json();
            foreach ($conversations['data'] ?? [] as $conversation) {
                $messagesUrl = "https://graph.facebook.com/v22.0/{$conversation['id']}/messages?access_token={$accessToken}";
                $messages = Http::get($messagesUrl)->json();
                $totalMessages += count($messages['data'] ?? []);
            }

            // ========== 6. Post Types ==========
            $postsData = Http::get("https://graph.facebook.com/v22.0/{$pageId}/posts?fields=id,created_time,attachments&access_token={$accessToken}")->json();
            $imageCount = 0;
            $videoCount = 0;
            $linkCount = 0;

            foreach ($postsData['data'] ?? [] as $post) {
                foreach ($post['attachments']['data'] ?? [] as $attachment) {
                    $type = $attachment['type'] ?? 'no_type';
                    if ($type === 'image_share') $imageCount++;
                    elseif ($type === 'video_inline') $videoCount++;
                    elseif ($type === 'share') $linkCount++;
                }
            }

            // ========== 7. Country-wise Fans ==========
            $countryResponse = Http::get("https://graph.facebook.com/v18.0/{$pageId}/insights/page_fans_country?access_token={$accessToken}");
            $fanData = $countryResponse->json()['data'][0]['values'][0]['value'] ?? [];

            // ========== 8. Language ==========
            $langResponse = Http::get("https://graph.facebook.com/v22.0/{$pageId}/insights/page_fans_locale?access_token={$accessToken}");
            $fanLanguages = $langResponse->json()['data'][0]['values'][0]['value'] ?? [];

            return view('social-media-analytics::socialmediaanalytics.facebook', compact(
                'fanLanguages',
                'fanData',
                'linkCount',
                'imageCount',
                'videoCount',
                'totalFans',
                'daily',
                'weekly',
                'monthly',
                'idaily',
                'iweekly',
                'idays28',
                'totalReactions',
                'totalComments',
                'totalMessages',
                'audienceGrowthData'
            ));
        } catch (\Exception $e) {
            Log::error("Facebook API error: " . $e->getMessage());
            return back()->with('error', __('Failed to load Facebook analytics data. Please try again later.'));
        }
    }
    public function getInstagramData()
    {
        $settings = getCompanyAllSetting();
        if (empty($settings['social_media_instagram_access_token']) || empty($settings['social_media_instagram_client_id'])) {
            return back()->with('error', 'please add credentials first.');
        }
        $accessToken = $settings['social_media_instagram_access_token'];
        $facebookId = $settings['social_media_facebook_i_client_id'];
        $instagramId = $settings['social_media_instagram_client_id'];
        $url = "https://graph.facebook.com/v22.0/{$instagramId}";

        $response = Http::get($url, [
            'fields' => "instagram_business_account",
            'access_token' => $accessToken,
        ]);
        $instagramBusiness = $response->json();
        if (isset($instagramBusiness['error'])) {
            $errorMessage = $instagramBusiness['error']['message'] ?? 'Unknown error';
            $errorCode = $instagramBusiness['error']['code'] ?? 'N/A';
            $errorSubcode = $instagramBusiness['error']['error_subcode'] ?? 'N/A';
        
            // Log the full error for debugging
            \Log::error(__('Instagram API error'), $instagramBusiness['error']);
        
            return redirect()->back()->with('error', "Instagram API Error [Code $errorCode/$errorSubcode]: $errorMessage");
        }
        $igUserId = $instagramBusiness['instagram_business_account']['id'] ? $instagramBusiness['instagram_business_account']['id'] : $instagramBusiness['id'];
        try {
            //media
            $mediaUrl = "https://graph.facebook.com/v22.0/{$igUserId}/media?fields=id,caption,like_count,comments_count&access_token={$accessToken}";
            $response = Http::get($mediaUrl);
            $mediaItems = $response->json()['data'] ?? [];

            $totalEngagement = 0;

            foreach ($mediaItems as $media) {
                $likes = $media['like_count'] ?? 0;
                $comments = $media['comments_count'] ?? 0;
                $totalEngagement += $likes + $comments;
            }

            //follower
            $responsess = Http::get("https://graph.facebook.com/v22.0/{$igUserId}/insights", [
                'metric' => 'follower_count',
                'period' => 'day',
                'access_token' => $accessToken,
            ]);

            $data = $responsess->json();
            $followerCount = $data['data'][0]['values'][0]['value'] ?? 0;
            $followers = [];

            if (!empty($data['data'][0]['values'])) {
                foreach ($data['data'][0]['values'] as $value) {
                    $followers[] = [
                        'date' => $value['end_time'],
                        'count' => $value['value'],
                    ];
                }
            }
            //comment
            $responseas = Http::get("https://graph.facebook.com/v22.0/{$igUserId}/media", [
                'fields' => 'id,caption',
                'access_token' => $accessToken,
            ]);

            $media = $responseas->json()['data'] ?? [];
            $totalComments = 0;

            foreach ($media as $item) {
                $mediaId = $item['id'];

                $mediaResponse = Http::get("https://graph.facebook.com/v22.0/{$mediaId}", [
                    'fields' => 'comments_count',
                    'access_token' => $accessToken,
                ]);

                $commentsCount = $mediaResponse->json()['comments_count'] ?? 0;
                $totalComments += $commentsCount;
            }

            //post
            $postresponse = Http::get("https://graph.facebook.com/v22.0/{$igUserId}/media", [
                'fields' => "id,media_type,timestamp",
                'access_token' => $accessToken,
            ]);

            $postdata = $postresponse->json();
            $photoCount = 0;
            $videoCount = 0;
            $postCount = 0;
            $storyCount = 0;

            $dateWisePostCount = [];
            if (isset($postdata['data'])) {
                $postCount = count($postdata['data']);
            } else {
                $postCount = 0;
            }

            if (isset($postdata['data'])) {
                foreach ($postdata['data'] as $media) {
                    if ($media['media_type'] == 'IMAGE') {
                        $photoCount++;
                    } elseif ($media['media_type'] == 'VIDEO') {
                        $videoCount++;
                    } elseif ($media['media_type'] === 'STORY') {
                        $storyCount++;
                    }
                    $date = \Carbon\Carbon::parse($media['timestamp'])->toDateString(); // Format as YYYY-MM-DD

                    // Increment post count by date
                    if (!isset($dateWisePostCount[$date])) {
                        $dateWisePostCount[$date] = 0;
                    }
                    $dateWisePostCount[$date]++;
                }
            } else {
                $photoCount = 0;
                $videoCount = 0;
            }
            $dates = array_keys($dateWisePostCount);
            $counts = array_values($dateWisePostCount);

            //active user by day
            $activeresponse = Http::get("https://graph.facebook.com/v22.0/17841472030442339/insights", [
                'metric' => 'reach',
                'period' => 'day',
                'access_token' => 'EAATRtSAmgZAIBOyoZAIJE4FiwXnrUhM4MtRBxRscVH0MOOFcQFanUxu4FFxourEHXrJWOqdNjWlA0dtjQUXNfysCvwJLXJk7TUgU1W70FoH0Ev4BDbZB8DH42ZApN30Q5iOwjEcnaWW6ChjPZBMexZB83CZBGr8hfVXZARN72H5g6KrWGbKaJFV5xQjbcsDDcaoA1gZDZD'
            ]);

            $reachData = $activeresponse->json()['data'][0]['values'] ?? 0;

            //story replies
            $storyurl = "https://graph.facebook.com/v22.0/{$igUserId}/stories?access_token={$accessToken}";
            $storyresponse = Http::get($storyurl)->json();

            $totals = [
                'impressions' => 0,
                'reach' => 0,
                'taps_forward' => 0,
                'taps_back' => 0,
                'replies' => 0,
                'exits' => 0,
            ];

            $storyCount = 0;

            if (isset($storyResponse['data'])) {
                foreach ($storyResponse['data'] as $story) {
                    $storyId = $story['id'];
                    $insightUrl = "https://graph.facebook.com/v22.0/{$storyId}/insights?metric=impressions,reach,taps_forward,taps_back,replies,exits&access_token={$accessToken}";
                    $insightResponse = Http::get($insightUrl)->json();

                    if (isset($insightResponse['data'])) {
                        $metrics = collect($insightResponse['data'])->mapWithKeys(function ($item) {
                            return [$item['name'] => $item['values'][0]['value'] ?? 0];
                        });

                        foreach ($totals as $key => $value) {
                            $totals[$key] += $metrics[$key] ?? 0;
                        }

                        $storyCount++;
                    }
                }
            }

            $totals['average_impressions'] = $storyCount > 0 ? round($totals['impressions'] / $storyCount, 2) : 0;

            //total likes 
            $totalurl = "https://graph.facebook.com/v22.0/{$igUserId}/insights?metric=likes&period=day&metric_type=total_value&access_token={$accessToken}";

            $totalresponse = Http::get($totalurl)->json();

            $likesData = $response['data'] ?? [];

            $totalLikes = collect($likesData)->sum('like_count');

            //The number of saves of your posts, reels and videos
            $saveurl = "https://graph.facebook.com/v22.0/{$igUserId}/insights?metric=saves&period=day&metric_type=total_value&access_token={$accessToken}&breakdowns=media_product_type";

            $saveresponse = Http::get($saveurl)->json();

            $totalSaves = 0;

            if (isset($saveresponse['data'])) {
                foreach ($saveresponse['data'] as $metric) {
                    if (isset($metric['total_value']['value'])) {
                        $totalSaves += $metric['total_value']['value'];
                    }
                }
            }

            //The number of shares of your posts, stories, reels, videos and live videos.
            $shareurl = "https://graph.facebook.com/v22.0/{$igUserId}/insights?metric=shares&period=day&metric_type=total_value&access_token={$accessToken}&breakdowns=media_product_type"; // Fixed URL

            $shareresponse = Http::get($shareurl)->json();

            $totalshares = 0;

            if (isset($shareresponse['data'])) {
                foreach ($shareresponse['data'] as $metric) {
                    if (isset($metric['total_value']['value'])) {
                        $totalshares += $metric['total_value']['value'];
                    }
                }
            }

            //The number of times your content was played or displayed. Content includes reels, posts, stories.
            $playedurl = "https://graph.facebook.com/v22.0/{$igUserId}/insights?metric=views&period=day&metric_type=total_value&access_token={$accessToken}&breakdowns=media_product_type";

            $plyedresponse = Http::get($playedurl)->json();

            $totalViews = 0;

            if (isset($plyedresponse['data'])) {
                foreach ($plyedresponse['data'] as $metric) {
                    if (isset($metric['total_value']['value'])) {
                        $totalViews += $metric['total_value']['value'];
                    }
                }
            }

            //country
            $counturl = "https://graph.facebook.com/v22.0/{$igUserId}/insights";

            $response = Http::get($counturl, [
                'metric' => 'engaged_audience_demographics',
                'period' => 'lifetime',
                'breakdowns' => 'country',
                'access_token' => $accessToken,
            ]);

            $countdata = $response->json();

            $audience = [];

            if (isset($countdata['data'][0]['total_value']['breakdowns'][0]['results'])) {
                foreach ($countdata['data'][0]['total_value']['breakdowns'][0]['results'] as $result) {
                    $countryCode = $result['dimension_values'][1] ?? 'Unknown';
                    $value = $result['value'] ?? 0;

                    $audience[] = [
                        'country' => $countryCode,
                        'count' => $value,
                    ];
                }
            }

            //tags
            $tagresponse = Http::get("https://graph.facebook.com/{$igUserId}/tags", [
                'fields' => 'id,username',
                'access_token' => $accessToken,
            ]);

            $tags = $tagresponse->json('data') ?? [];
            $totalTags = count($tags);
            return view('social-media-analytics::socialmediaanalytics.instagram', compact('totalTags', 'audience', 'totalViews', 'totalshares', 'totalSaves', 'totalLikes', 'totals', 'storyCount', 'reachData', 'counts', 'dates', 'videoCount', 'photoCount', 'postCount', 'totalEngagement', 'followerCount', 'totalComments', 'followers'));
        } catch (\Exception $e) {
            Log::error(__("Instagram API error: ") . $e->getMessage());
            return back()->with('error', __('Failed to load Instagram analytics data. Please try again later.'));
        }
    }

    private function getYouTubeCredentials()
    {
        return [
            'client_id'     => Setting::where(['key' => 'social_media_youtube_google_client_id', 'workspace' => $this->workspace, 'created_by' => $this->creatorId])->value('value'),
            'client_secret' => Setting::where(['key' => 'social_media_youtube_google_client_secret', 'workspace' => $this->workspace, 'created_by' => $this->creatorId])->value('value'),
            'redirect_uri'  => Setting::where(['key' => 'social_media_youtube_google_redirect_uri', 'workspace' => $this->workspace, 'created_by' => $this->creatorId])->value('value'),
        ];
    }
    private function getToken($key)
    {
        return Setting::where(['key' => $key, 'workspace' => $this->workspace, 'created_by' => $this->creatorId])->value('value');
    }
    public function setAccessTokenFromDB()
    {
        try {
            $accessToken = $this->getToken('social_media_youtube_access_token');
            $expiresAt = $this->getToken('social_media_youtube_token_expires_at');
            $refreshToken = $this->getToken('social_media_youtube_refresh_token');
            if ($accessToken) {
                if (!$expiresAt || Carbon::now()->gt(Carbon::parse($expiresAt))) {
                    if ($refreshToken) {
                        $this->refreshAccessToken($refreshToken);
                    } else {
                        throw new \Exception(__("No access token found. Please authenticate again."));
                    }
                } else {
                    $this->client->setAccessToken($accessToken);
                }
                // throw new \Exception("Access token set successfully.");
            } else {
                throw new \Exception(__("No access token found. Please authenticate again."));
            }
            comapnySettingCacheForget();

        } catch (\Exception $e) {
            
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    private function refreshAccessToken($refreshToken)
    {
        try {
            $token = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);

            if (isset($token['access_token'])) {
                // Store new access token
                $this->storeToken('social_media_youtube_access_token', $token['access_token']);
                $this->storeToken('social_media_youtube_token_expires_at', Carbon::now()->addSeconds($token['expires_in']));

                if (isset($token['refresh_token'])) {
                    // Sometimes a new refresh token is returned, update it
                    $this->storeToken('social_media_youtube_refresh_token', $token['refresh_token']);
                }

                $this->client->setAccessToken($token['access_token']);

            } else {
                throw new \Exception(__("Failed to refresh access token."));
            }
            comapnySettingCacheForget();

        } catch (\Exception $e) {
            throw new \Exception(__($e->getMessage()));
        }
    }
    private function storeToken($key, $value)
    {
        Setting::updateOrInsert(
            ['key' => $key, 'workspace' => $this->workspace, 'created_by' => $this->creatorId],
            ['value' => $value]
        );
    }
    public function getYoutubeData()
    {
        $settings = getCompanyAllSetting();
        if (empty($settings['social_media_youtube_access_token']) || empty($settings['social_media_youtube_token_expires_at']) || empty($settings['social_media_youtube_refresh_token'])) {
            return back()->with('error', 'please authenticate your YouTube account before proceeding.');
        }
        //subscriber
        $this->client = new Google_Client();
        $this->workspace = getActiveWorkSpace();
        $this->creatorId = creatorId();

        $credentials = $this->getYouTubeCredentials();
        
        if (!$credentials || empty($credentials['client_id']) || empty($credentials['client_secret']) || empty($credentials['redirect_uri'])) {
            return back()->with('error', 'please add credentials first.');
            
        }

        $this->client->setClientId($credentials['client_id']);
        $this->client->setClientSecret($credentials['client_secret']);
        $this->client->setRedirectUri($credentials['redirect_uri']);
        $this->client->addScope([
            Google_Service_YouTube::YOUTUBE,
            Google_Service_YouTube::YOUTUBE_FORCE_SSL,
            Google_Service_YouTube::YOUTUBE_READONLY,
            Google_Service_YouTube::YOUTUBE_UPLOAD,
            Google_Service_YouTube::YOUTUBEPARTNER_CHANNEL_AUDIT,
        ]);
        $this->client->setAccessType('offline');

        if ($this->getToken('social_media_youtube_access_token')) {
            $this->setAccessTokenFromDB();
        }
        $youtube = new Google_Service_YouTube($this->client);
        $channelsResponse = $youtube->channels->listChannels('snippet,contentDetails,statistics', array(
            'mine' => 'true'
        ));
        if (isset($channelsResponse['items'][0])) {
            $channelId = $channelsResponse['items'][0]['id'];
            $subscriberCount = $channelsResponse['items'][0]['statistics']['subscriberCount'];
        } else {
            return response()->json(['error' => 'No channel found or invalid access token']);
        }
        //likes
        $totalLikes = 0;
        $nextPageToken = null;
        $totalDislikes = 0;
        $totalViews = 0;
        $totalVideos = 0;
        $totalComments = 0;
        do {
            $videoList = $youtube->search->listSearch('id', [
                'channelId' => $channelId,
                'maxResults' => 50,
                'pageToken' => $nextPageToken,
            ]);

            foreach ($videoList['items'] as $video) {
                if (isset($video['id']['videoId'])) {
                    $videoId = $video['id']['videoId'];
                    $videoDetails = $youtube->videos->listVideos('statistics', [
                        'id' => $videoId
                    ]);
                    if (isset($videoDetails['items'][0]['statistics']['likeCount'])) {
                        $totalLikes += $videoDetails['items'][0]['statistics']['likeCount']; // Add like count
                    }
                    $date = Carbon::now()->format('d-n-Y');
                    if (!isset($videoData[$date])) {
                        $videoData[$date] = [
                            'commentCount' => 0,
                            'dislikeCount' => 0,
                            'favoriteCount' => 0,
                            'likeCount' => 0,
                            'viewCount' => 0
                        ];
                    }

                    $videoData[$date]['commentCount'] += $videoDetails['items'][0]['statistics']['commentCount'];
                    $videoData[$date]['dislikeCount'] += $videoDetails['items'][0]['statistics']['dislikeCount'];
                    $videoData[$date]['favoriteCount'] += $videoDetails['items'][0]['statistics']['favoriteCount'];
                    $videoData[$date]['likeCount'] += $videoDetails['items'][0]['statistics']['likeCount'];
                    $videoData[$date]['viewCount'] += $videoDetails['items'][0]['statistics']['viewCount'];
                }
            }

            $nextPageToken = $videoList['nextPageToken'] ?? null;
        } while ($nextPageToken);

        //dislikes
        $videoDetails = $youtube->videos->listVideos('statistics', [
            'id' => $videoId
        ]);

        if (isset($videoDetails['items'][0]['statistics']['dislikeCount'])) {
            $totalDislikes += $videoDetails['items'][0]['statistics']['dislikeCount']; // Add dislike count
        }

        //views
        $videoDetails = $youtube->videos->listVideos('statistics', [
            'id' => $videoId
        ]);

        if (isset($videoDetails['items'][0]['statistics']['viewCount'])) {
            $totalViews += $videoDetails['items'][0]['statistics']['viewCount']; // Add view count
        }

        //total video 
        $channelsResponse = $youtube->channels->listChannels('contentDetails', [
            'id' => $channelId
        ]);

        if (isset($channelsResponse['items'][0]['contentDetails']['relatedPlaylists']['uploads'])) {
            $playlistId = $channelsResponse['items'][0]['contentDetails']['relatedPlaylists']['uploads'];

            $playlistItems = $youtube->playlistItems->listPlaylistItems('snippet', [
                'playlistId' => $playlistId,
                'maxResults' => 50,
            ]);
            $totalVideos = $playlistItems['pageInfo']['totalResults'];
        }

        //comment
        $videoDetails = $youtube->videos->listVideos('statistics', [
            'id' => $videoId
        ]);

        if (isset($videoDetails['items'][0]['statistics']['commentCount'])) {
            $totalComments += $videoDetails['items'][0]['statistics']['commentCount']; // Add comment count
        }

        //chart
        $analytics = new Google_Service_YouTubeAnalytics($this->client);

        $startDate = Carbon::now()->subMonths(12)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $resp = $analytics->reports->query([
            'ids' => 'channel==MINE',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'metrics' => 'subscribersGained',
            'dimensions' => 'day',
        ]);
        $dates = [];
        $subscribers = [];

        if (!empty($resp->rows)) {
            foreach ($resp->rows as $row) {
                [$date, $subscribersGained] = $row;
                $formattedDate = Carbon::parse($date)->format('d-m-Y');
                $dates[] = $formattedDate;
                $subscribers[] = (int) $subscribersGained;
            }
        }

        //like dislike chart
        $datess = [];
        $likes = [];
        $dislikes = [];

        foreach ($videoData as $date => $data) {
            $datess[] = $date;
            $likes[] = (int) $data['likeCount'];
            $dislikes[] = (int) $data['dislikeCount'];
        }

        //subscribers per country
        $responsasdasde = $analytics->reports->query([
            'ids' => 'channel==MINE',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'metrics' => 'subscribersGained',
            'dimensions' => 'country',
            'sort' => '-subscribersGained',
            'maxResults' => 10,
        ]);

        $countryData = [];

        if (!empty($responsasdasde->rows)) {
            foreach ($responsasdasde->rows as $row) {
                $country = $row[0];
                $subscribers = $row[1];
                $countryData[] = [
                    'country' => $country,
                    'subscribers' => (int) $subscribers
                ];
            }
        }
        $countries = array_column($countryData, 'country');
        $subscribers = array_column($countryData, 'subscribers');

        //Gender type and count


        $genderStatsResponse = $analytics->reports->query([
            'ids' => 'channel==MINE',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'metrics' => 'viewerPercentage',
            'dimensions' => 'gender',
        ]);
        $genderData = [];
        if (!empty($genderStatsResponse->rows)) {
            foreach ($genderStatsResponse->rows as $row) {
                [$gender, $viewerPercentage] = $row;
                $genderData[] = [
                    'gender' => $gender,
                    'viewerPercentage' => (float) $viewerPercentage
                ];
            }
        }

        //device
        $deviceresponse = $analytics->reports->query([
            'ids' => 'channel==MINE', // Get data for the authenticated user's channel
            'startDate' => '2023-04-01',
            'endDate' => '2025-05-01',
            'metrics' => 'views', // Get the number of views
            'dimensions' => 'deviceType', // Group by device type (mobile, desktop, tablet)
            'sort' => '-views', // Sort by the number of views (descending)
        ]);
        $deviceData = [];
        if (!empty($deviceresponse->rows)) {
            foreach ($deviceresponse->rows as $row) {
                $deviceType = ucfirst(strtolower($row[0]));  // Capitalize device type
                $viewCount = $row[1];

                $deviceData[] = [
                    'device' => $deviceType,
                    'views' => $viewCount
                ];
            }
        }
        //subscribe per country count 
        $countryresponse = $analytics->reports->query([
            'ids' => 'channel==MINE',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'metrics' => 'subscribersGained',  // Metric for subscriber gain
            'dimensions' => 'country',  // Grouping by country
            'sort' => '-subscribersGained',  // Sorting by number of subscribers gained
        ]);
        return view('social-media-analytics::socialmediaanalytics.youtube', compact('deviceData', 'genderData', 'countryData', 'countries', 'subscribers', 'datess', 'likes', 'dates', 'dislikes', 'subscribers', 'subscriberCount', 'totalLikes', 'totalDislikes', 'totalViews', 'totalVideos', 'totalComments', 'videoData'));
    }
}
