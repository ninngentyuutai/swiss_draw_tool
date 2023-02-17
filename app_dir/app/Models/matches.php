<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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




    public function create_matches(int $tournamentId, int $round, $combinations) {
        // データベースに値をinsert
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
    }

    /**
     * updateResult
     * 対戦結果を登録
     *
     * @string $id
     * @string $participant 変更者 :0主催者  1,2 participant
     * @array $result
     * @$status
     * @retrn bool update saccess :true
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
            'result' => $result
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
            
        $result = $round->isEmpty() ? 1 : $round['round'];
        return $result;
    }
    /**
     * is_next_matches
     * 次戦対戦組み合わせを作成できるか判定
     *
     * @int $tournamentId
     * @int $round
     * @retrn bool
     */
    public function is_next_matches(int $tournamentId, $round) {
        $matches = $this->where([
            ['tournament_id', $tournamentId],
            ['round', $round],
            ['status', '<>', self::STATUS_TWO_END],        
        ]);
        $result = $round->isEmpty() ? true : false;
        return $result;
    }
}
