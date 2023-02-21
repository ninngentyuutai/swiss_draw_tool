<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\matches;
use App\Models\tournaments;


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
        $userId =1;
        $startDatetime = now();
        //

        $tournamentsModel = new tournaments();

        $result = $tournamentsModel->create_tournament($userId, $startDatetime);
        if (!$result) {
            echo 'ようわからんDBエラー';exit();
        }
        $tournamentId = $tournamentsModel->get_tournament_id($userId)['id'];

        $matchesModel = new matches();
        $nextRound = 1;
        $result = $matchesModel->create_matches($tournamentId, $nextRound, $combination);
        return $tournamentId;exit();
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
            $result = $matchesModel->create_matches($tournamentId, $nextRound, $combination);
        } else {
            echo 'まだ終わってない試合があるやで';exit();
        }


    }

}
