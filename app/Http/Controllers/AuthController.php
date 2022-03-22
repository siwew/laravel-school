<?php

namespace App\Http\Controllers;

use App\Http\Requests\Register;
use App\Model\Teacher;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * 老师注册
     *
     * @param Register $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Register $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        Teacher::create($data);

        return api()->success(['msg' => '注册成功']);
    }


    /**
     * 登录相关接口
     *
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        $account = $request->input('account', '');
        $password = $request->input('password', '');
        $type = $request->input('type', '');

        if (!$account || !$password) {
            return api()->failed('账号或密码不能为空');
        }

        if (empty($type)) {
            return api()->failed('登录类型不能为空');
        }

        $check = \Auth::guard($type)->attempt([
            'account' => $account,
            'password' => $password
        ])->check();

        if (!$check) {
            return api()->failed('账号或密码错误');
        }

        $api = config('app.url') . '/oauth/token';
        $client = new Client();
        $response = $client->post($api, [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('passport.client_id'),
                'client_secret' => config('passport.client_secret'),
                'username' => $account,
                'password' => $password,
                'scope' => '',
            ],
        ]);
        return api()->success(json_decode((string) $response->getBody(), true));
    }


    /**
     * 登出开始前端实现清除token
     * @param Request $request
     * @return void
     */
    public function loginout(Request $request)
    {
        \Auth::guard($request->input('type', ''))->logout();

        return api()->success(['msg' => '退出成功']);
    }


//    public function line(Request $request)
//    {
//    }
}
