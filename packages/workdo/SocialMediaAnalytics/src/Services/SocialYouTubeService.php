<?php
namespace Workdo\SocialMediaAnalytics\Services;

use App\Models\Setting;
use Google_Client;
use Google_Service_YouTube;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SocialYouTubeService {
    private $client;
    private $workspace;
    private $creatorId;

    public function __construct() {
        if (Auth::user()->type != 'super admin') {
            $this->client = new Google_Client();
            $this->workspace = getActiveWorkSpace();
            $this->creatorId = creatorId();

            $credentials = $this->getYouTubeCredentials();
            if (!$credentials || empty($credentials['client_id']) || empty($credentials['client_secret']) || empty($credentials['redirect_uri'])) {
                throw new \Exception(__('YouTube API credentials are missing. Please configure them in the settings.'));
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

        } else {
            throw new \Exception(__("Permission Denied."));
        }
    }

    private function getYouTubeCredentials() {
        return [
            'client_id'     => Setting::where(['key' => 'social_media_youtube_google_client_id', 'workspace' => $this->workspace, 'created_by' => $this->creatorId])->value('value'),
            'client_secret' => Setting::where(['key' => 'social_media_youtube_google_client_secret', 'workspace' => $this->workspace, 'created_by' => $this->creatorId])->value('value'),
            'redirect_uri'  => Setting::where(['key' => 'social_media_youtube_google_redirect_uri', 'workspace' => $this->workspace, 'created_by' => $this->creatorId])->value('value'),
        ];
    }

    public function getAuthUrl() {
        if (!$this->client->getClientId()) {
            throw new \Exception(__("Missing client ID. Please configure YouTube API credentials."));
        }
        return $this->client->createAuthUrl();
    }

    public function authenticate($code) {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        if (!isset($token['access_token'])) {
            throw new \Exception(__("Authentication failed."));
        }

        $this->storeToken('social_media_youtube_access_token', $token['access_token']);

        if (isset($token['refresh_token'])) {
            $this->storeToken('social_media_youtube_refresh_token', $token['refresh_token']);
        }

        $this->storeToken('social_media_youtube_token_expires_at', Carbon::now()->addSeconds($token['expires_in']));

        $this->client->setAccessToken($token);

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
            throw new \Exception(__($e->getMessage()));
        }
    }

    public function setupAccessToken()
    {
        try {
            // if ($this->isQuotaExceeded()) {
            //     throw new \Exception(__("YouTube API quota limit exceeded. Please try again later."));
            // }

            $tokenResponse = $this->setAccessTokenFromDB();
            if (!empty($tokenResponse) && !$tokenResponse['success']) {
                throw new \Exception($tokenResponse['message']);
            }
        } catch (\Google_Service_Exception $e) {
            $this->handleGoogleApiError($e);
        } catch (\Throwable $th) {
            throw new \Exception(($th->getMessage()));
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

                // throw new \Exception(__("Access token refreshed successfully."));
            } else {
                throw new \Exception(__("Failed to refresh access token."));
            }
            comapnySettingCacheForget();

        } catch (\Exception $e) {
            throw new \Exception(__($e->getMessage()));
        }
    }

    private function storeToken($key, $value) {
        Setting::updateOrInsert(
            ['key' => $key, 'workspace' => $this->workspace, 'created_by' => $this->creatorId],
            ['value' => $value]
        );
    }

    private function getToken($key) {
        return Setting::where(['key' => $key, 'workspace' => $this->workspace, 'created_by' => $this->creatorId])->value('value');
    }

    public function getYouTubeService() {
        try {
            // if ($this->isQuotaExceeded()) {
            //     throw new \Exception(__("YouTube API quota limit exceeded. Please try again later."));
            // }

            return new Google_Service_YouTube($this->client);
        } catch (\Google_Service_Exception $e) {
            $this->handleGoogleApiError($e);
        } catch (\Throwable $th) {
            throw new \Exception(__($th->getMessage()));
        }
    }

    private function handleGoogleApiError($e)
    {
        $error = json_decode($e->getMessage(), true);

        if (isset($error['error']) && isset($error['error']['errors'])) {
            foreach ($error['error']['errors'] as $err) {
                if ($err['reason'] === 'quotaExceeded') {
                    throw new \Exception(__("YouTube API quota limit exceeded. Please try again later or increase your quota in Google Cloud Console."));
                }
            }
        }
        throw new \Exception(__($e->getMessage()));
    }

    public function isQuotaExceeded()
    {
        try {
            $youtube = $this->getYouTubeService();
            $response = $youtube->channels->listChannels('statistics', [
                'mine' => true
            ]);

            return false;
        } catch (\Google_Service_Exception $e) {
            $error = json_decode($e->getMessage(), true);
            if (isset($error['error']) && isset($error['error']['errors'])) {
                foreach ($error['error']['errors'] as $err) {
                    if ($err['reason'] === 'quotaExceeded') {
                        return true;
                    }
                }
            }
            throw new \Exception($e->getMessage());
        }
    }
}

