<?php

namespace App\Http\Controllers;

use App\Api\Ads\VkApi;

/**
 * Class AccountsController
 * @package App\Http\Controllers
 */
class AccountsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {
        $vk_api = new VkApi();
        $accounts = $vk_api->getAccounts();
        $user = $vk_api->getUser();

        return view('accounts', [
            'accounts' => $accounts->response,
            'profile' => isset($user->response[0]) ? $user->response[0] : []
        ]);
    }
}
