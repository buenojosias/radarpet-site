<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Occurrence;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index() {
        $recents = Occurrence::with(['pet.specie','location','image'])->orderBy('id','desc')->limit(4)->get();
        foreach($recents as $recent) {
            Carbon::setLocale('pt_BR');
            $recent->occurred_at = Carbon::parse($recent->occurred_at)->diffForHumans();
        }
        return response()->json(['data'=>$recents]);
    }
}
