<?php

namespace App\Http\Controllers;

use App\Ads;
use App\Api\Ads\VkApi;
use Illuminate\Support\Facades\DB;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class CampaignsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param string $account_name
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(string $account_name, string $id)
    {
        $vk_api = new VkApi();
        $campaigns = $vk_api->getCampaigns($id);

        if (!isset($campaigns->response)) {
            return redirect('accounts');
        }

        return view('campaigns', ['campaigns' => $campaigns->response]);
    }

    /**
     * @param string $account_name
     * @param string $account_id
     * @param string $campaign_name
     * @param string $campaign_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \Exception
     */
    public function ads(string $account_name, string $account_id, string $campaign_name, string $campaign_id)
    {
        if ($block_ads_id = request()->input('block_ads')) {
            return $this->blockAd($account_id, $block_ads_id);
        }

        if ($description = request()->input('description')) {
            return $this->updateAds($account_id, $description);
        }

        $vk_api = new VkApi();
        $ads = $vk_api->getAds($account_id, [$campaign_id]);
        $ads_ids = [];
        foreach ($ads->response as $ad) {
            $ads_ids = $ad->id;
        }

        $ads_note = DB::table('ads')->whereNotIn('ads_id', $ads_ids)->get();

        return view('ads', [
            'ads' => $ads->response,
            'ads_note' => $ads_note,
            'breadcrumbs' => [
                'account' => [
                    'uri' => '/accounts',
                    'name' => $account_name
                ],
                'campaign' => [
                    'uri' => "/{$account_name}/{$account_id}/",
                    'name' => $campaign_name
                ]
            ]
        ]);
    }

    /**
     * @param string $account_id
     * @param string $ads_id
     * @return
     * @throws \Exception
     */
    private function blockAd(string $account_id, string $ads_id)
    {
        $vk_api = new VkApi();
        $vk_api->deleteAds($account_id, $ads_id);
        return redirect(url()->current());
    }

    /**
     * @param string $account_id
     * @param array $description
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    private function updateAds(string $account_id, array $description)
    {
        $vk_api = new VkApi();
        $vk_api->updateAds($account_id, $description);
        $ad = Ads::where('ads_id', '=', key($description))->first();
        if (!$ad) {
            $ad = new Ads();
            $ad->ads_id = key($description);
            $ad->note = mb_substr($description[key($description)], 0, 100);
            $ad->save();
        } else {
            $ad->note = mb_substr($description[key($description)], 0, 100);
            $ad->save();
        }

        return redirect(url()->current());
    }
}
