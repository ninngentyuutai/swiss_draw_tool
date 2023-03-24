<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\participants;

class ParticipantController extends Controller
{

    //参加登録、未参加、参加中、参加終了
    const STATUS_CREATED = 0;
    const STATUS_NOT_ATTENDED = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DEACTIVE = 3;
    const STATUS_UNREGISTER = 4;


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
        //userIdはリクエストから取らない
        $userId = 1;
        //
        $tournamentId = $request['tournament_id'];

        $participantsModel = new participants();
        $return = $participantsModel->create_participant($userId, $tournamentId);
        echo (string)$return; exit();
    }

    /**
     * unregister_end_participant
     * 大会参加者登録解除
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    public function unregister_end_participant(Request $request) {

        //この辺テストデータ
        //userIdはリクエストから取らない
        $userId = 1;
        //

        $tournamentId = $request['tournament_id'];

        $participantsModel = new participants();
        $getStatus = $participantsModel->get_status($userId, $tournamentId);
        $status = $getStatus->status;
        $doUpdate = false;
        // ステータスが参加登録の場合
        if ($status == self::STATUS_CREATED) {
            $doUpdate = true;
        }
        if ($doUpdate) {
            $status = self::STATUS_UNREGISTER;
            $execution = $participantsModel->update_status($userId, $tournamentId, $status);
            echo 'dekita';exit();
        }
    }
}
