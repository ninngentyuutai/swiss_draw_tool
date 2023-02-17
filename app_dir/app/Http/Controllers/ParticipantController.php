<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\participants;

class ParticipantController extends Controller
{
    // todo userid  はパラメータで貰わない

    /**
     * register_start_participant
     * 大会参加者登録開始
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    public function register_start_participant(Request $request) {
        // 作成可能チェック
        // todo 時間、大会の重複登録を条件化
        $isCreateable = true;

        if ($isCreateable) {

        } else {
            echo 'まだ';exit();
 
        }
    }
    /**
     * register_end_participant
     * 大会参加者登録
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    public function register_end_participant(Request $request) {

        //この辺テストデータ
        $userId = 1;
        //

        $participantsModel = new participants();
        $participantsModel->create_participant($userId, $tournamentId);

    }
}
