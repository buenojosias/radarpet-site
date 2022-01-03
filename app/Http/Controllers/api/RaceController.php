<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Race;

class RaceController extends Controller
{
    public function index($specie) {
        $races = Race::where('specie_id', $specie)->orderBy('name','asc')->get();
        return response()->json(['data'=>$races]);
    }

}
