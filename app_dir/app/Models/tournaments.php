<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tournaments extends Model
{
    protected $table = 'tournaments';

    /**
     * create_tournament
     * 大会を登録
     *
     * @param int $userId
     * @param  $startDateTime
     * @return bool update saccess :true
     */
    public function create_tournament(int $userId, $startDateTime) {
        try {
            $tournament['promoter_id'] = $userId;
            $tournament['start_date_time'] = $startDateTime;
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
     * @return bool update saccess :true
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
    


}
