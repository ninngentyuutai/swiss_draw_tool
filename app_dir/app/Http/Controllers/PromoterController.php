<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\matches;
use App\Models\tournaments;
use App\Models\participants;

class PromoterController extends Controller
{

    public function create_start_tournament() {
        // 作成可能チェック
        // todo x件の作成済みを条件化
        $isCreateable = true;

        if ($isCreateable) {

        } else {
            echo 'まだ';exit();
 
        }
    }

    /**
     * create_end_tournament
     * 大会登録
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    public function create_end_tournament(Request $request) {
        //この辺テストデータ
        $combination[0] = [1,2];
        $combination[1] = [3,4];
        $userId = 1;
        $startDatetime = now();
        //

        $tournamentsModel = new tournaments();

        $result = $tournamentsModel->create_tournament($userId, $startDatetime);
        if (!$result) {
            echo 'ようわからんDBエラー';exit();
        }

    }

    /**
     * next_match
     * 次戦製造
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    public function next_match(Request $request) {
        $tournamentId = $request['tournament_id'];
        //この辺テストデータ
        $combination[0] = [1,2];
        $combination[1] = [3,4];
        //
        $matchesModel = new matches();
        $lastRound = $matchesModel->get_last_round($tournamentId);
        $nextRound = $lastRound + 1;
        $isNextMatch = $matchesModel->is_next_matches($tournamentId, $lastRound);

        if($isNextMatch) {
            $combination = [];

            $sumResults = self::sum_results($tournamentId, $lastRound);

            $result = $matchesModel->create_matches($tournamentId, $nextRound, $combination);
        } else {
            echo 'まだ終わってない試合があるやで';exit();
        }

    }

    /**
     * sum_results
     * 結果集計
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    private function sum_results($tournamentId, $lastRound) {

        $matchesModel = new matches();
        return 999;

    }

    /**
     * Batch
    */


    /**
     * start_tournament
     * 大会開始
     *
     * @param Request $request
     * @return bool update saccess :true
     */
    public function start_tournament(Request $request) {
        $tournamentId = $request['tournament_id'];
        // 開始可能チェック
        // // todo 最小参加人数x件の作成済みを条件化
        // $isCreateable = true;

        // if ($isCreateable) {

        // } else {
        //     echo 'まだ';exit();
 
        // }

        $participantsModel = new participants();
        $participants = $participantsModel->get_count_tournament_participants($tournamentId);


        $matchesModel = new matches();
        $nextRound = 1;
        $result = $matchesModel->create_matches($tournamentId, $nextRound, $combination);
        return $tournamentId;exit();


    }




}
