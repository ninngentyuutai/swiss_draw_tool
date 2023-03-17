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
    // ユーザーが同時に主催可能な大会の上限
    const MAX_PROMOTE = 5;
    const STATUS_BEFORE_START = 0;
    const STATUS_IN_SESSION = 1;

    // 要検討：




    /**
     * create_start_tournament
     * 大会登録開始
     *
     * @param Request $request
     * @return array $tournaments　ユーザーが作成したことのある過去の大会のデータを返す
     */
    public function create_start_tournament() {
        //この辺テストデータ
        $userId = 1;
        //





        // 作成可能チェック
        $isCreateable = self::is_createable($userId);

        if ($isCreateable) {
            // ユーザーが作成したことのある過去の大会のデータを返す

        } else {
            echo 'まだ';exit();
 
        }
    }

    /**
     * create_end_tournament
     * 大会登録完了
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

        $tournamentId = $tournamentsModel->create_tournament($userId, $startDatetime, $minMember, $recruit, $release);
        if ($tournamentId === 0) {
            echo 'ようわからんDBエラー';exit();
        } else {
            echo $tournamentId;exit();
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
            $samePointIdGroups[$x][] = 
                ['id' => $participants[$i]->id, 'point' => $participants[$i]->point, 'battle_record' => explode(',', $participants[$i]->battle_record), 'priority' => $participants[$i]->priority];
        }

        //グループ内でパートナー探し
        $retunParticipants = [];
        // retunParticipantsのparticipant2_idに入れたIDを通常配列としても利用
        $alreadyDecidedParticipants = [];
        // var_dump($samePointIdGroups);exit();
        foreach ($samePointIdGroups as $key => $samePointIdGroup) {

            //次戦優先度が高いやつ
            $priority = '';
            if ($samePointIdGroup[0]['priority'] > 0) {
                $priority = $samePointIdGroup[0];
            //優先度ない場合
            } else {
                $point = $samePointIdGroup[0]['point'];
                foreach ($samePointIdGroup as $key => $samePointParticipant) {
                    $pair = self::looking_for_partner($samePointIdGroup, $key, $alreadyDecidedParticipants);
                    if ($pair) {
                        $retunParticipants[] = $pair;
                    }
                }
            }

        }
        var_dump($retunParticipants);
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

    /**
     * is_createable
     * ユーザーが新しい大会を主催できるか
     *
     * @param int $userId
     * @return bool createable :true
     */
    private function is_createable($userId) {
        // const STATUS_BEFORE_START = 0;
        // const STATUS_IN_SESSION = 1;
        // const STATUS_END = 2;
        $tournamentsModel = new tournaments();
        $tournaments = $tournamentsModel->get_users_tournaments($tournamentId);        
        $isCreateable = true;
        $unfinished = 0;
        foreach ($tournaments as $key => $tournament) {
            switch ($tournament->status) {
                // 条件：未終了大会が上限以上ないこと
                case self::STATUS_BEFORE_START:
                    $unfinished = $unfinished + 1;
                    break;
                // 条件：アクティブな大会がないこと
                case self::STATUS_IN_SESSION:
                    $isCreateable = false;
                    break;
            }
        }
        if ($isCreateable && $unfinished >= self::MAX_PROMOTE) {
            $isCreateable = false;
        }
        return $isCreateable;
    }
    /**
     * looking_for_partner
     * グループ内でパートナー探し
     *
     * @param array $samePointParticipant
     * @param array &$alreadyDecidedParticipants

     * @return array $retunParticipants
     */
    private function looking_for_partner($samePointIdGroup, $key, &$alreadyDecidedParticipants) {
        $samePointParticipant = $samePointIdGroup[$key];

        //既に対戦相手決まってる人はパス
        if (is_int(array_search($samePointParticipant['id'], $alreadyDecidedParticipants))) {
            return false;
        } else {
            $samePointParticipant['opponentable'] = [];
            // $samePointParticipant['id']主観で相手(samePointParticipant2)を探す
            foreach ($samePointIdGroup as $key2 => $samePointParticipant2) {    
                // 対戦済みではない or 既に対戦相手決まってる人 or 自分はパス
                if (!(is_int(array_search($samePointParticipant2['id'], $samePointParticipant['battle_record'])) || is_int(array_search($samePointParticipant2['id'], $alreadyDecidedParticipants)) || $samePointParticipant['id'] === $samePointParticipant2['id'])) {
                    //対戦可能に入れる
                    $samePointParticipant['opponentable'][] = $samePointParticipant2['id'];
                }
            }

            //対戦可能な相手数で分岐
            switch (count($samePointParticipant['opponentable'])) {
                case 0:
                    echo 'call0';

                    // ポイントが低いグループがある
                    if (count($samePointIdGroup) !== $key + 1) {
                        echo 'ある';
         
                    // ポイントが低いグループがない
                    } else {
                        echo 'ない';

                    }


                    //ここ再起にしよう
                    # code...
                    return false;
                    break;
                case 1:
                    echo 'call1';
                    // 自分を1
                    $retunParticipant['participant1_id'] = $samePointParticipant['id'];
                    // 相手を2
                    $retunParticipant['participant2_id'] = $samePointParticipant['opponentable'][0];
                    // 決定済みリスト更新
                    $alreadyDecidedParticipants[] = $samePointParticipant['id'];
                    $alreadyDecidedParticipants[] = $samePointParticipant['opponentable'][0];
                    break;
                default:
                echo 'call2';
                    // 自分を1
                    $retunParticipant['participant1_id'] = $samePointParticipant['id'];
                    $participant2Id = '';
                    // 対戦可能な相手から未決定かつランダムな相手を検索
                    do {
                        $participant2Id = $samePointParticipant['opponentable'][random_int(0, count($samePointParticipant['opponentable']) - 1)];
                    } while (is_int(array_search($participant2Id, $alreadyDecidedParticipants)));
                    // 相手を2
                    $retunParticipant['participant2_id'] = $participant2Id;
                    // 決定済みリスト更新
                    $alreadyDecidedParticipants[] = $samePointParticipant['id'];
                    $alreadyDecidedParticipants[] = $participant2Id;

                    break;
            }
            return $retunParticipant;
        }

    }



}
