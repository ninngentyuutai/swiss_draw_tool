<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\matches;

class PromoterController extends Controller
{
    public function next_match() {
        $tournamentId = '123456789';
        $combination[0] = ['ume','nuki'];
        $combination[1] = ['pan','minene'];
        matches::create_matches($tournamentId, $combination);



    }
}
