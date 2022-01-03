<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AdoptionRequest;
use Illuminate\Http\Request;

class AdoptionRequestController extends Controller
{
    public function index($adoptable) {
        $user = Auth::user();
        $requests = AdoptionRequest::where('adoptable_id', $adoptable)->with('user')->orderBy('id','desc')->get();
        return ['data'=>$requests];
    }

    public function store(Request $request, $id) {
        $user = Auth::user();
        $adprequest = AdoptionRequest::create([
            'user_id' => $user->id,
            'adoptable_id' => $id,
            'description' => $request->adprequest['description']
        ]);
        if($adprequest) {
            return response()->json(["status" => "success"]);
        }
        return response()->json(["status" => "failed", "message" => "Ocorreu um erro ao salvar"]);
    }
}
