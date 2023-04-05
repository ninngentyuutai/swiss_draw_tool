<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tournaments extends Model
{
    protected $table = 'tournaments';
    protected $fillable =
        ['promoter_id', 'start_date_time', 'min_member', 'recruit', 'release'];
    protected $casts = ['start_date_time'];

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
     * @return int update saccess : tournament_id , failure: 0
     */
    public function create_tournament(int $userId, $startDateTime, $minMember, $recruit, $release) {
        try {
            $tournament['promoter_id'] = $userId;
            $tournament['start_date_time'] = $startDateTime;
            $tournament['min_member'] = $minMember;
            $tournament['recruit'] = $recruit;
            $tournament['release'] = $release;
            $tournament['status'] = self::STATUS_BEFORE_START;
            $createdAt = now(); 
            $tournament['created_at'] = $createdAt;

            $savetournament = $this->insert($tournament);
            if ($savetournament) {
                $tournamentId = $this->select('id')
                ->where('promoter_id', $userId)
                ->where('created_at', $createdAt)
                ->first();
                return $tournamentId->id;
            } else {
                return 0;
            }

        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * get_users_tournaments
     * ユーザーが主催する大会を取得
     *
     * @param int $userId
     * @return array tournaments
     */
    public function get_users_tournaments(int $userId) {
        try {
            $tournaments = $this->where('promoter_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();
            return $tournaments;
        } catch (\Throwable $e) {
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
        } catch (\Throwable $e) {
            return false;
        }
    }

}
