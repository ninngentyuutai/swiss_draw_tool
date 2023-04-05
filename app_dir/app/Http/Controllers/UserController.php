<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;

class UserController extends Controller
{

    /**
     * api_login
     * フロントlogin時に発行したキーを保存する
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    public function api_login(Request $request) {
        return response()->json(
            [
                'result' => 'true'
            ]
            );
        //当初フロントはwp
        // $wpId = $request['wpId'];
        // $apiKey = $request['apiKey'];
        // $usersModel = new users();
        // $execution = $usersModel->create_user($wpId, $apiKey);
        // return response()->json(
        //     [
        //         'result' => 'true'
        //     ]
        //     );
    }

}
