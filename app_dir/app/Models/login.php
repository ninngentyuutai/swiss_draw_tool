<?php

/**
 * login
 * ログイン中の管理
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;


class login extends Model
{
    protected $table = 'login';
    protected $fillable =
        ['wp_id', 'api_key', 'taime_out'];
    protected $casts = ['taime_out'];

    /**
     * login
     * ユーザーログイン
     *
     * @param int $wpId
     * @return string $apiKey 
     */
    public function login($wpId) {
        try {
            $date = new DateTime(now());
            $date->modify('+1 day');
            do {
                $apiKey = str_pad((string)array_rand(0, 9999999999), 10);
                $isAlreadyExist = $this->select('count(*)')
                ->where('api_key', $apiKey);
            } while ($isAlreadyExist == 0);

            $data = [
                'api_key' => $apiKey,
                'taime_out' => $date,
            ];
            $this->where('wp_id', $wpId)
            ->update($data);
            return $apiKey;
        } catch ( Exception $ex ) {
            return false;
        }
    }

    /**
     * deadline_extension
     * 有効期限延長
     *
     * @param  string $apiKey
     * @return bool update saccess : true , false
     */
    public function deadline_extension($apiKey) {
        try {
            $date = new DateTime(now());
            $date->modify('+1 day');
            $data = [
                'taime_out' => $date,
            ];
            $this->where('api_key', $apiKey)
            ->update($data);
            return true;
        } catch ( Exception $ex ) {
            return false;
        }
    }

    /**
     * is_login
     * ログイン判定
     *
     * @param  string $apiKey
     * @return bool login : true , false
     */
    public function is_login($apiKey) {
        try {
            $isAlreadyExist = $this->api_key->where('api_key', $apiKey)->count();
            if ($isAlreadyExist == 0 ) {
                return false;
            } else {
                return true;
            }
        } catch ( Exception $ex ) {
            return false;
        }
    }





}
