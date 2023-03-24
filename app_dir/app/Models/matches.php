<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class matches extends Model
{
    use HasFactory;

    const PARTICIPANT_PROMOTER = 0;
    const STATUS_CREATED = 0;
    //一人目の参加者が着席
    const STATUS_ONE_RESERVED = 1;
    const STATUS_TWO_RESERVED = 2;
    const STATUS_ONE_END = 3;
    const STATUS_TWO_END = 4;
    const STATUS_APPEAL = 5;

    protected $table = 'matches';
    /**
     * create_matches
     * 対戦結果を作成
     *
     * @param int $tournamentId
     * @param int $round
     * @param $combinations
     * @return bool update saccess :true
     */
    public function create_matches(int $tournamentId, int $round, $combinations) {
        // データベースに値をinsert

        try {
            $matches = [];
            $i = 0;
            foreach ($combinations as $key => $combination) {
                $matches[$i]["tournament_id"] = $tournamentId;
                $matches[$i]["round"] = $round;
                $matches[$i]["participant1_id"] = $combination[0];//一人目
                $matches[$i]["participant2_id"] = $combination[1];//二人目
                $i = $i + 1;
            }
            
            $save_matches = $this->insert($matches);
            return true;
        } catch ( Exception $ex ) {
            return false;
        }
    }
    
    /**
     * update_result
     * 対戦結果を登録
     *
     * @param string $id
     * @param string $participant 変更者 :0主催者  1,2 participant
     * @param array $result
     * @param $status
     * @return bool update saccess :true
     */
    public function update_result(int $id, string $participant, array $result, $status) {
        DB::transaction(function () use($id, $participant, $result, $status) {
            //statusチェック
            $getStatus = self::get_status($id);
            if (is_null($getStatus)) {
                //存在しないID　不正
                return false;
            }
            $oldStatus = $getStatus->status;
            $lastUpdatedBy = $getStatus->last_updated_by;
            //　更新できるかチェック
            $isUpdate = self::is_update_status($participant, $lastUpdatedBy, $status, $oldStatus);
            if ($isUpdate) {
                $data = [
                    'last_updated_by' => $participant,
                    'result' => $result,
                    'status' => $status
                ];
                $this->where('id', $id)
                ->update($data);
            } else {
                return false;
            }

        });
        return true;
    }
    
    /**
     * update_status
     * ステータス更新
     *
     * @param int $id
     * @param int $participant
     * @param int $status
     * @return int $return
     */
    public function update_status($id, $participant, $status) {
        DB::transaction(function () use($id, $participant, $status) {
            //statusチェック
            $getStatus = self::get_status($id);
            if (is_null($getStatus)) {
                //存在しないID　不正
                return false;
            }
            $oldStatus = $getStatus->status;
            $lastUpdatedBy = $getStatus->last_updated_by;
            //　更新できるかチェック
            $isUpdate = self::is_update_status($participant, $lastUpdatedBy, $status, $oldStatus);
            $data = [
                'last_updated_by' => $participant,
                'status' => $status
            ];
            $this->where('id', $id)
              ->update($data);
        });

        return true;
    }

    /**
     * get_last_round
     * 終了済み回戦数を取得
     *
     * @param int $tournamentId
     * @return int $return
     */
    public function get_last_round($tournamentId) {
        $round = $this->select('round')
            ->where('tournament_id', $tournamentId)
            ->orderBy('round')
            ->first();
        $return = is_null($round) ? 0 : $round['round'];
        return $return;
    }

    /**
     * get_status
     * 試合のステータスと最終更新者を取得
     *
     * @param int $tournamentId
     * @return object $matche
     */
    public function get_status($tournamentId) {
        $matche = $this->select('status', 'last_updated_by')
            ->where('tournament_id', $tournamentId)
            ->first();
        $return = $matche;
        return $return;
    }


    /**
     * is_next_matches
     * 次戦対戦組み合わせを作成できるか判定
     *
     * @param int $tournamentId
     * @param int $round
     * @return bool
     */
    public function is_next_matches(int $tournamentId, $round) {
        if ($round == 0) {
            return true;
        } else {
            $matches = $this->where([
                ['tournament_id', $tournamentId],
                ['round', $round],
                ['status', '<>', self::STATUS_TWO_END],        
            ])->count();
            $result = $matches == 0 ? true : false;
            return $result;
        }
    }

    /**
     * is_update_status
     * ステータス更新可否を判定
     *
     * @param int $participant
     * @param int $lastUpdatedBy
     * @param int $status
     * @param int $oldStatus
     * @return object $matche
     */
    public function is_update_status($participant, $lastUpdatedBy, $status, $oldStatus) {
        // const STATUS_CREATED = 0;
        // //一人目の参加者が着席
        // const STATUS_ONE_RESERVED = 1;
        // const STATUS_TWO_RESERVED = 2;
        // const STATUS_ONE_END = 3;
        // const STATUS_TWO_END = 4;
        // const STATUS_APPEAL = 5;
        $return = false;
        switch ($status) {
            case self::STATUS_CREATED:
                $return = true;
                break;
            
            case self::STATUS_ONE_RESERVED:
                if ($oldStatus === self::STATUS_CREATED) {
                    $return = true;
                }
                break;

            case self::STATUS_TWO_RESERVED:
                if ($oldStatus === self::STATUS_ONE_RESERVED && $participant !== $lastUpdatedBy) {
                    $return = true;
                }
                $return = true;
                break;
                
            case self::STATUS_ONE_END:
                if ($oldStatus === self::STATUS_TWO_RESERVED || $oldStatus === self::STATUS_ONE_END) {
                    $return = true;
                }
                $return = true;
                break;  

            case self::STATUS_TWO_END:
                if (($oldStatus === self::STATUS_ONE_END || $oldStatus === self::STATUS_APPEAL) && $participant !== $lastUpdatedBy) {
                    $return = true;
                }
                $return = true;
                break;

            case self::STATUS_APPEAL:
                if ($oldStatus === self::STATUS_ONE_END && $participant !== $lastUpdatedBy) {
                    $return = true;
                }
                $return = true;
                break;
        }
        return $return;
    }



    /**
     * get_round_results
     * 指定大会、ラウンドの結果を取得
     *
     * @param int $tournamentId
     * @param int $round
     * @return object $matches
     */
    public function get_round_results($tournamentId, $round) {
        $matches = $this->select('id', 'result', 'participant1_id', 'participant2_id', 'round')
            ->where([
                ['tournament_id', $tournamentId],
                ['round', $round],
                ['status', self::STATUS_TWO_END]
            ])
            ->get();
        return $matches;

    }







    
}
