<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\matches;


class MatchController extends Controller
{

    const PARTICIPANT_PROMOTER = 0;
    const STATUS_CREATED = 0;
    //一人目の参加者が着席
    const STATUS_ONE_RESERVED = 1;
    const STATUS_TWO_RESERVED = 2;
    const STATUS_ONE_END = 3;
    const STATUS_TWO_END = 4;
    const STATUS_APPEAL = 5;

    /**
     * reserve
     * 対戦ルーム着席
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    function reserve(Request $request) {
        //この辺テストデータ
        //participantIdはリクエストから取らない
        $participant = 1;
        //
        $id = $request['id'];

        $matchesModel = new matches();
        $getStatus = $matchesModel->get_status($id);
        $status = $getStatus->status;
        $lastUpdatedBy = $getStatus->last_updated_by;

        $doUpdate = false;
        // ステータスが作成の場合
        if ($status == self::STATUS_CREATED) {
            $doUpdate = true;
        // ステータスが片方着席の場合、未着席参加者のリクエストであれば
        } elseif($status == self::STATUS_ONE_RESERVED && $lastUpdatedBy != $participant) {
            $doUpdate = true;
        }
        //ステータス更新させる
        if ($doUpdate) {
            $status = $status + 1;
            $execution = $matchesModel->update_status($id, $participant, $status);
            echo 'dekita';exit();

        }
    }

    /**
     * victory_report
     * 勝敗報告
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    function victory_report(Request $request) {
        //この辺テストデータ
        //participantIdはリクエストから取らない
        $participant = 1;
        $result = [1,0,2];
        //

        $id = $request['id'];
        $status = 3;
        $matchesModel = new matches();

        $execution = $matchesModel->update_result($id, $participant, $result, $status);
        echo $execution;exit();
    }

    /**
     * approval
     * 対戦結果OK
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    function approval(Request $request) {
        //この辺テストデータ
        //participantIdはリクエストから取らない
        $participant = 1;
        //
        $id = $request['id'];

        $matchesModel = new matches();
        $getStatus = $matchesModel->get_status($id);
        $status = $getStatus->status;
        $lastUpdatedBy = $getStatus->last_updated_by;

        $doUpdate = false;

        // ステータスが片方完了の場合、未着席参加者のリクエストであれば
        if($status == self::STATUS_ONE_END && $lastUpdatedBy != $participant) {
            $doUpdate = true;
        }
        //ステータス更新させる
        if ($doUpdate) {
            $status = $status + 1;
            $execution = $matchesModel->update_status($id, $participant, $status);
            echo 'dekita';exit();
        }
    }

    /**
     * appeal
     * 対戦結果NG
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    function appeal(Request $request) {
        //この辺テストデータ
        //participantIdはリクエストから取らない
        $participant = 1;
        //
        $id = $request['id'];

        $matchesModel = new matches();
        $getStatus = $matchesModel->get_status($id);
        $status = $getStatus->status;
        $lastUpdatedBy = $getStatus->last_updated_by;

        $doUpdate = false;

        // ステータスが片方完了の場合、未着席参加者のリクエストであれば
        if($status == self::STATUS_ONE_END && $lastUpdatedBy != $participant) {
            $doUpdate = true;
        }
        //ステータス更新させる
        if ($doUpdate) {
            $status = self::STATUS_APPEAL;
            $execution = $matchesModel->update_status($id, $participant, $status);
            echo 'dekita';exit();

        }

    }


}
