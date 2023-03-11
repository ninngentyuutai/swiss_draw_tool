<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\matches;
use App\Models\tournaments;
use App\Models\participants;

class PromoterController extends Controller
{
    const POINT_WIN = 1;
    const POINT_DROW = 0.5;
    const POINT_LOSS = 0;

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
        // $combination[0] = [1,2];
        // $combination[1] = [3,4];
        $userId = 1;
        $startDatetime = now();
        $minMember = 5;
        $recruit = 0;
        $release = 0;
        //

        $tournamentsModel = new tournaments();

        $execution = $tournamentsModel->create_tournament($userId, $startDatetime, $minMember, $recruit, $release);
        if (!$execution) {
            echo 'ようわからんDBエラー';exit();
        } else {
            echo $tournamentsModel->get_tournament_id($userId);exit();
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

            $execution = $matchesModel->create_matches($tournamentId, $nextRound, $combination);
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
        // 試合情報を集約
        $matchesModel = new matches();
        $roundResults = $matchesModel->get_round_results($tournamentId, $lastRound);

        $roundDatas = [];
        $i = 0;
        foreach ($roundResults as $key => $roundResult) {
            $roundDatas[$i]['id'] = $roundResult->participant1_id;
            $roundDatas[$i]['opponent'] = $roundResult->participant2_id;
            $roundDatas[$i]['point'] = self::conv_result_to_point($roundResult->result);
            $i = $i + 1;
            $roundDatas[$i]['id'] = $roundResult->participant2_id;
            $roundDatas[$i]['opponent'] = $roundResult->participant1_id;
            $roundDatas[$i]['point'] = self::conv_result_to_point($roundResult->result, true);
            $i = $i + 1;
        }

        $participantsModel = new participants();

        $execution = $participantsModel->update_point($tournamentId, $roundDatas, $roundResults[0]->round);


        $execution = $participantsModel->update_result($tournamentId, $roundResults);

        return $result;

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
        $participants = $participantsModel->get_tournament_participants($tournamentId, 'created');
        $participantsCount = $participants->count();
        $tournamentsModel = new tournaments();
        $minMember = $tournamentsModel->get_min_member($tournamentId);
        if ($minMember > $participantsCount) {
            echo '参加者はいませんでした';exit();
        }

        $matchesModel = new matches();
        $combination = $this->set_combination($tournamentId, $participants, $participantsModel, $matchesModel);

        $nextRound = 1;
        $result = $matchesModel->create_matches($tournamentId, $nextRound, $combination);
        return $tournamentId;exit();

    }

    // ここからプライベート
    private function set_combination($tournamentId, $participants, $participantsModel, $matchesModel) {

        $combination = [];

        $samePointIdGroups = [];
        
        $x = 0;
        //同じポイント状況の人をグループ
        for ($i=0; $i < count($participants); $i++) {

            if ($i !== 0 && $participants[$i]->point !== $participants[$i - 1]->point) {
                $x = $x + 1;
            }
            $samePointIdGroups[$x][] = ['id' => $participants[$i]->id, 'point' => $participants[$i]->point];
        }
        //グループ内でパートナー探し 
        foreach ($samePointIdGroups as $key => $samePointIdGroup) {

            foreach ($samePointIdGroup as $key => $samePointIds) {
                //var_dump($samePointIds);
            }
            // if () {

            // }
            # code...
        }
        var_dump($samePointIdGroups);
        exit();

    }
    /**
     * conv_result_to_point
     * 対戦結果をもとに勝敗ポイントを返却
     *
     * @param string $result
     * @return int $point
     */
    private function conv_result_to_point($result, $is2p = false) {
        // const POINT_WIN = 1;
        // const POINT_DROW = 0.5;
        // const POINT_LOSS = 0;
        $games = explode(',', $result);
        $isWin = 0;
        foreach ($games as $key => $value) {
            $isWin = $isWin + (integer)$value;
        }
        if ($is2p) {
            switch ($isWin) {
                case $isWin >= 1:
                    $point = self::POINT_WIN;
                    break;
                case 0:
                    $point = self::POINT_DROW;
                    break;
                default:
                    $point = self::POINT_LOSS;
                break;
            }
        } else {
            switch ($isWin) {
                case $isWin < 0:
                    $point = self::POINT_WIN;
                    break;
                case 0:
                    $point = self::POINT_DROW;
                    break;
                default:
                    $point = self::POINT_LOSS;
                break;
            }
        }
        return $point;
    }


}
