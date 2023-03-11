<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tournaments extends Model
{
    protected $table = 'tournaments';
    const STATUS_BEFORE_START = 0;
    const STATUS_IN_SESSION = 1;
    const STATUS_END = 2;



    /**
     * create_tournament
     * 大会を登録
     *
     * @param int $userId
     * @param  $startDateTime
     * @param int $minMember
     * 
     * @return int update saccess :true
     */
    public function create_tournament(int $userId, $startDateTime, $minMember, $recruit, $release) {
        try {
            $tournament['promoter_id'] = $userId;
            $tournament['start_date_time'] = $startDateTime;
            $tournament['min_member'] = $minMember;
            $tournament['recruit'] = $recruit;
            $tournament['release'] = $release;

            $savetournament = $this->insert($tournament);

            return true;
        } catch ( Exception $ex ) {
            return false;
        }
    }

    /**
     * get_tournament_id
     * ユーザーが主催するアクティブな大会IDを取得
     *
     * @param int $userId
     * @return int id
     */
    public function get_tournament_id(int $userId) {
        try {
            $tournamentId = $this->select('id')
                ->where('promoter_id', $userId)
                // nullは仮
                ->where('status', null)
                ->first();
            return $tournamentId;
        } catch ( Exception $ex ) {
            return false;
        }

    }

    /**
     * get_min_member
     * 最小開催人数を取得
     *
     * @param int $id
     * @return int min_member
     */
    public function get_min_member($tournamentId) {
        try {
            $selectResult = $this->select('min_member')
                ->where('id', $tournamentId)
                ->first();
            return $selectResult->min_member;
        } catch ( Exception $ex ) {
            return false;
        }
    }

}
