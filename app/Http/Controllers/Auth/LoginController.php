<?php

namespace App\Http\Controllers\Auth;

use App\Api\Ads\VkApi;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/accounts';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function authVk()
    {
        if (is_null(request()->input('code'))) {
            return $this->redirectVkPage();
        }

        $api = new VkApi();
        $token = $api->auth();

        if (!isset($token['user_id'])) {
            return redirect('/');
        }

        $user = DB::table('users')->where('vk_user_id', $token['user_id'])->first();
        if ($user === null) {
            $user = new User();
            $user->login = $token['user_id'];
            $user->password = Hash::make($token['access_token'] . time());
            $user->email = isset($token['email']) ? $token['email'] : '';
            $user->vk_access_token = $token['access_token'];
            $user->vk_token_expires_in = $token['expires_in'];
            $user->vk_user_id = $token['user_id'];
            $user->save();
        } else {
            DB::table('users')->where('id', $user->id)->update(
                [
                    'vk_access_token' => $token['access_token'],
                    'vk_token_expires_in' => $token['expires_in'],
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );
        }

        Auth::loginUsingId($user->id);
        return redirect($this->redirectTo);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function redirectVkPage()
    {
        return redirect(env('VK_URL_AUTHORIZE') . http_build_query(
                [
                    'client_id' => env('VK_CLIENT_ID'),
                    'redirect_uri' => env('VK_REDIRECT_URI'),
                    'scope' => env('VK_SCOPE'),
                    'response_type' => 'code'
                ]
            )
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logoutUser(Request $request)
    {
        return $this->logout($request);
    }
}
