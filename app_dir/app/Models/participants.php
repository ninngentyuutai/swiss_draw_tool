<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class participants extends Model
{
    protected $table = 'participants';
    protected $fillable = ['battle_record', 'point'];


    const STATUS_CREATED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVE = 2;
    const STATUS_END = 3;

    //ここは要件レベルで煮詰めたいけどとりま参考サイトの数字
    const POINT_WIN = 1;
    const POINT_DROW = 0.5;
    const POINT_LOSS = 0;


    /**
     * create_participant
     * 参加者を登録
     *
     * @param int $userId
     * @param int $tournamentId
     * @return bool update saccess :true
     */
    public function create_participant(int $userId, int $tournamentId) {
        try {
            $participant['user_id'] = $userId;
            $participant['tournament_id'] = $tournamentId;
            $participant['status'] = self::STATUS_CREATED;
            $participant['point'] = 0;
            $participant['os/m'] = 0;
            $participant['dos/m'] = 0;
            $participant['md/m'] = 0;
            $saveParticipants = $this->insert($participant);
            return true;
        } catch ( Exception $ex ) {
            return false;
        }
    }

    /**
     * get_tournament_participants
     * 参加者を取得
     *
     * @param int $tournamentId
     * @param string $status
     * @return object $result
     */
    public function get_tournament_participants($tournamentId, $status) {

        // const STATUS_CREATED = 0;
        // const STATUS_ACTIVE = 1;
        // const STATUS_DEACTIVE = 2;
        // const STATUS_END = 3;
        try {

            $serchStatus = '';
            switch ($status) {
                case 'created':
                    $serchStatus = self::STATUS_CREATED;
                    break;
                case 'active':
                    $serchStatus = self::STATUS_ACTIVE;
                    break;
                case 'deactive':
                    $serchStatus = self::STATUS_DEACTIVE;
                    break;
                case 'end':
                    $serchStatus = self::STATUS_END;
                    break;
            }
            $result = $this->where('tournament_id', $tournamentId)->where('status', $serchStatus)
            ->orderBy('point', 'desc')
            ->orderBy('os/m', 'desc')
            ->orderBy('dos/m', 'desc')
            ->orderBy('md/m', 'desc')
            ->get();
            return $result;
        } catch ( Exception $ex ) {
            return false;
        }
    }

    /**
     * get_aggregate_results
     * 集計結果を取得
     *
     * @param int $tournamentId
     * @param string $status
     * @return 
     */
    public function get_aggregate_results($tournamentId, $status) {

    // get_aggregate_results
    }

    /**
     * update_point
     * ポイント、対戦済み相手の更新
     *
     * @param int $tournamentId
     * @param array $roundDatas
     * @param int $round
     * @return bool update saccess :true
     */
    public function update_point($tournamentId, $roundDatas, $round) {
        // const POINT_WIN = 1;
        // const POINT_DROW = 0.5;
        // const POINT_LOSS = 0;
        try {
            $targets = $this->select('battle_record', 'point')
            ->where('tournament_id', $tournamentId);
            foreach ($roundDatas as $key => $roundData) {
                $target = $targets->where('id', $roundData['id'])->first();

                $battleRecord = $target->battle_record . ',' . (string)$roundData['opponent'];
                $point = $target->point + $roundData['point'];
                $target->update([
                    'battle_record' => $battleRecord,
                    'point' => $point
                ]);
            }
            return true;
        } catch ( Exception $ex ) {
            return false;
        }
    }



    public function update_result($tournamentId, $roundResults) {

        
    }



}
