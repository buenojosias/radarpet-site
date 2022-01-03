<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specie;

class SpecieController extends Controller
{
    public function index() {
        $species = Specie::get();
        return response()->json(['data'=>$species]);
    }
}
