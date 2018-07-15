<?php

namespace App\Api\Ads;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use stdClass;

/**
 * Class VkApi
 * @package App\Api\Ads
 */
class VkApi
{
    private $client;
    private $access_token;
    private $user_id;

    /**
     * VkApi constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
        $user = Auth::user();
        if (!is_null($user)) {
            if (!empty($user->vk_access_token)) {
                $this->access_token = $user->vk_access_token;
            }

            if (!empty($user->vk_user_id)) {
                $this->user_id = $user->vk_user_id;
            }
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function auth(): array
    {
        $token = [];
        if (request()->input('code')) {
            $client = new Client();
            try {
                $res = $client->request('GET', env('VK_URL_ACCESS_TOKEN'), [
                    'query' => [
                        'client_id' => env('VK_CLIENT_ID'),
                        'client_secret' => env('VK_CLIENT_SECRET'),
                        'code' => request()->input('code'),
                        'redirect_uri' => env('VK_REDIRECT_URI'),
                    ]
                ]);
                $token = json_decode($res->getBody(), true);
            } catch (GuzzleException $e) {
                throw new Exception('System exception', 400);
            }
        }

        return $token;
    }

    /**
     * @param string $id
     * @return stdClass
     * @throws Exception
     */
    public function getCampaigns(string $id): stdClass
    {
        try {
            $res = $this->client->request('GET', env('VK_API_URL') . 'ads.getCampaigns', [
                'query' => [
                    'account_id' => $id,
                    'access_token' => $this->access_token,
                    'v' => env('VK_VERSION')
                ]
            ]);
        } catch (GuzzleException $e) {
            throw new Exception('Error get campaigns', 400);
        }

        return json_decode($res->getBody());
    }

    /**
     * @return stdClass
     * @throws Exception
     */
    public function getAccounts(): stdClass
    {
        try {
            $res = $this->client->request('GET', env('VK_API_URL') . 'ads.getAccounts', [
                'query' => [
                    'access_token' => $this->access_token,
                    'v' => env('VK_VERSION')
                ]
            ]);
        } catch (GuzzleException $e) {
            throw new Exception('Error get accounts', 400);
        }

        return json_decode($res->getBody());
    }

    /**
     * @param string $account_id
     * @param array $campaign_ids
     * @return stdClass
     * @throws Exception
     */
    public function getAds(string $account_id, array $campaign_ids): stdClass
    {
        try {
            $res = $this->client->request('GET', env('VK_API_URL') . 'ads.getAds', [
                'query' => [
                    'access_token' => $this->access_token,
                    'account_id' => $account_id,
                    'campaign_ids' => json_encode($campaign_ids),
                    'v' => env('VK_VERSION')
                ]
            ]);
        } catch (GuzzleException $e) {
            throw new Exception('Error get ads', 400);
        }

        return json_decode($res->getBody());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getUser()
    {
        try {
            $res = $this->client->request('GET', env('VK_API_URL') . 'users.get', [
                'query' => [
                    'access_token' => $this->access_token,
                    'user_ids' => $this->user_id,
                    'fields' => 'photo_200',
                    'v' => env('VK_VERSION')
                ]
            ]);
        } catch (GuzzleException $e) {
            throw new Exception('Error get ads', 400);
        }

        return json_decode($res->getBody());
    }

    /**
     * @param string $account_id
     * @param string $ads_id
     * @return mixed
     * @throws Exception
     */
    public function deleteAds(string $account_id, string $ads_id)
    {
        try {
            $res = $this->client->request('GET', env('VK_API_URL') . 'ads.deleteAds', [
                'query' => [
                    'access_token' => $this->access_token,
                    'account_id' => $account_id,
                    'ids' => json_encode([$ads_id]),
                    'v' => env('VK_VERSION')
                ]
            ]);
        } catch (GuzzleException $e) {
            throw new Exception('Error get ads', 400);
        }

        return json_decode($res->getBody());
    }

    /**
     * @param string $account_id
     * @param array $description
     * @return mixed
     * @throws Exception
     */
    public function updateAds(string $account_id, array $description)
    {
        try {
            $ad_edit_specification = new stdClass();
            $ad_edit_specification->ad_id = key($description);
            $ad_edit_specification->description = mb_substr($description[key($description)], 0, 100);

            $res = $this->client->request('GET', env('VK_API_URL') . 'ads.updateAds', [
                'query' => [
                    'access_token' => $this->access_token,
                    'account_id' => $account_id,
                    'data' => json_encode([$ad_edit_specification]),
                    'v' => env('VK_VERSION')
                ]
            ]);
        } catch (GuzzleException $e) {
            throw new Exception('Error get ads', 400);
        }

        return json_decode($res->getBody());
    }
}
