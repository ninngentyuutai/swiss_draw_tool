<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class participants extends Model
{
    protected $table = 'participants';

    const STATUS_CREATED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVE = 2;
    const STATUS_END = 3;

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
            ->orderBy('point', 'desc');
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

    public function update_result($tournamentId, $roundResults) {

        
    }



}
