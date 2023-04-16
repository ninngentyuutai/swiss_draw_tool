<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use App\Models\login;

class UserController extends Controller
{

    /**
     * api_login
     * フロントloginしapi_keyを発行する
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    public function api_login(Request $request) {
        $wpId = $request['wp_id'];
        $loginModel = new login();
        $result = $loginModel->login($wpId);

        return response()->json(['api_Key' => $result]);
    }

    /**
     * api_islogin
     * ユーザーのログインチェック
     *
     * @param Request $request
     * @return result bool login :true
     */
    public function api_islogin(Request $request) {
        $apiKey = $request['datas']['api_key'];
    
        $loginModel = new login();
        if (isset($apiKey)) {
            // $result = $test;
            $result = $apiKey;
            // $result = (string)$loginModel->is_login($apiKey);
        } else {
            $result = 'false';

        }

        return response()->json(['result' => $result]);
    

    }






}
