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
    const STATUS_ONE_RESERVED = 1;
    const STATUS_TWO_RESERVED = 2;
    const STATUS_START = 3;
    const STATUS_ONE_END = 4;
    const STATUS_TWO_END = 5;

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
     * updateResult
     * 対戦結果を登録
     *
     * @param string $id
     * @param string $participant 変更者 :0主催者  1,2 participant
     * @param array $result
     * @param $status
     * @return bool update saccess :true
     */
    public function updateResult(int $id, string $participant, array $result, $status) {
        //statusチェック


        //主催者による結果修正は戦績登録終了後
        // これおかしいわな
        // アップデートが有効かのチェックはコントローラー行きかな
        
        // if ($participant == PARTICIPANT_PROMOTER && $status !== STATUS_TWO_END) {
        //     throw new Exception("STATUS_BEFORE_END");
        // } else {
        //     if($status ) {

        //     }
        // }

        $data = [
            'last_updated_by' => $participant,
            'result' => $result,
            'status' => $status
        ];
        $this->where('id', $id)
          ->update($data);
        return true;
    }
    
    public function get_last_round($tournamentId) {
        $round = $this->select('round')
            ->where('tournament_id', $tournamentId)
            ->orderBy('round')
            ->first();
        $result = is_null($round) ? 0 : $round['round'];
        return $result;
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
            ]);
            $result = is_null($round) ? true : false;
            return $result;
        }
    }
}
