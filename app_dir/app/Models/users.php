<?php

/**
 * users
 * システム利用者の管理
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    protected $table = 'users';
    protected $fillable =
        ['wp_id', 'tournament_ids'];
    protected $casts = ['updated_at'];

    /**
     * create_user
     * ユーザー作成
     *
     * @param int $wpId
     * @return int update saccess : true , false
     */
    public function create_user($wpId) {
        try {
            $user['wp_id'] = $wpId;
            $saveParticipants = $this->insert($user);
            return true;
        } catch ( Exception $ex ) {
            return false;
        }
    }




    /**
     * login_user
     * ユーザーログイン
     *
     * @param int $wpId
     * @param  string $apiKey
     * @return int update saccess : true , false
     */
    public function login_user($wpId, $apiKey) {
        try {
            $user['wp_id'] = $wpId;
            $user['api_key'] = $apiKey;
            $saveParticipants = $this->insert($user);
            return true;
        } catch ( Exception $ex ) {
            return false;
        }
    }



}
