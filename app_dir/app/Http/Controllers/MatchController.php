<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\matches;


class MatchController extends Controller
{

    /**
     * victory_report
     * 勝敗報告
     *
     * @param Request $request
     * @param int $status 
     * @return bool update saccess :true
     */
    function victory_report(Request $request, int $status = 3) {
        //この辺テストデータ
        //participantIdはリクエストから取らない
        $participant = 1;
        $result = [1,0,2];
        //

        $matchesModel = new matches();

        $id = $request['id'];
        //$result = $request['result'];
        $execution = $matchesModel->updateResult($id, $participant, $result, $status);
        echo $execution;exit();


    }


}
