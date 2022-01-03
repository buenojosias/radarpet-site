<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Manifest;
use Illuminate\Http\Request;

class ManifestController extends Controller
{
    public function index($occurrence) {
        $user = Auth::user();
        $manifests = Manifest::where('occurrence_id', $occurrence)->with('user')->with('location')->orderBy('id','desc')->get();
        return ['data'=>$manifests];
    }

    public function store(Request $request, $id) {
        $user = Auth::user();

        $manifest = Manifest::create([
            'user_id' => $user->id,
            'occurrence_id' => $id,
            'description' => $request->manifest['description']
        ]);
        if($manifest) {
            if($request->manifest['occurrence_type']=='missing') {
                if($location = $manifest->location()->create($request->location))
                    return response()->json(["status" => "success"]);
            }
            return response()->json(["status" => "success"]);
        }
        return response()->json(["status" => "failed", "message" => "Ocorreu um erro ao salvar"]);
    }
}
