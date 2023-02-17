<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class participants extends Model
{
    protected $table = 'participants';

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
            $saveParticipants = $this->insert($participant);
            return true;
        } catch ( Exception $ex ) {
            return false;
        }
    }


}
