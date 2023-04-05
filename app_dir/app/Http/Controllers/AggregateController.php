<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AggregateController extends Controller
{
    public function index() {
    return response()->json(
        [
            'sample' => 'サンプル??'
        ]
        );



    }
}
