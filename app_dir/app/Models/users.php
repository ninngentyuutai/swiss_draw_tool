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
        ['name', 'wp_id', 'tournament_ids', 'api_key'];
    protected $casts = ['updated_at'];

    /**
     * create_user
     * 大会を登録
     *
     * @param int $wpId
     * @param  string $apiKey
     * @return int update saccess : true , false
     */
    public function create_user($wpId, $apiKey) {
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
