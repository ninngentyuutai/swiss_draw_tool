<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\matches;

class PromoterController extends Controller
{
    public function next_match() {
        $tournamentId = 123456789;
        $nextRound = 2;
        $combination[0] = [1,2];
        $combination[1] = [3,4];

        $matchesModel = new matches();
        $lastRound = $matchesModel->get_last_round($tournamentId);
        $nextRound = $lastRound + 1;

        $isNextMatch = $matchesModel->is_next_matches($tournamentId, $lastRound);
        if($isNextMatch) {
            $result = $matchesModel->create_matches($tournamentId, $nextRound, $combination);

        }


    }

}
