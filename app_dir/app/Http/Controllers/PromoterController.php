<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\matches;

class PromoterController extends Controller
{

    public function create_start_tournaments() {
        // 作成可能チェック
        // todo x件の作成済みを条件化
        $isCreateable = true;

        if ($isCreateable) {

        } else {
            echo 'まだ';exit();
 
        }
    }

    public function create_end_tournaments() {



    }



    public function next_match(int $tournamentId = 0) {
        //この辺テストデータ
        $tournamentId = 123456789;
        $combination[0] = [1,2];
        $combination[1] = [3,4];
        //

        $matchesModel = new matches();

        $lastRound = $matchesModel->get_last_round($tournamentId);
        $nextRound = $lastRound + 1;

        $isNextMatch = $matchesModel->is_next_matches($tournamentId, $lastRound);
        if($isNextMatch) {
            $result = $matchesModel->create_matches($tournamentId, $nextRound, $combination);
        } else {
            echo 'まだ終わってない試合があるやで';exit();
        }


    }

}
