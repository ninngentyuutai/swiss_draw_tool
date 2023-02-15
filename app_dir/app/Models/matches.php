<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matches extends Model
{
    use HasFactory;

    protected $table = 'matches';

    public function create_matches(string $tournamentId, $combinations) {
        // データベースに値をinsert
        $matches = [];
        $i = 0;
        foreach ($combinations as $key => $combination) {
            $matches[$i]["tournament_id"] = $tournamentId;
            $matches[$i]["participant1_id"] = $combination[0];//一人目
            $matches[$i]["participant2_id"] = $combination[1];//二人目
            $i = $i + 1;
        }
        
        $save_matches = $this->insert($matches);
    }



    public function updateResult(string $userId, array $result, $status) {
        return true;
    }


}
