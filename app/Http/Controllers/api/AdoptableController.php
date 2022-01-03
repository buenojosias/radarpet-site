<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Adoptable;
use App\Models\Pet;

class AdoptableController extends Controller
{
    
    public function index() {
        $adoptables = Adoptable::where('status', 'active')
        ->whereHas('pet')->whereHas('image')
        ->with(['pet.specie','pet.race','image'])->paginate();
        foreach($adoptables as $adoptable) {
            $age['months'] = $adoptable->pet->age;
            $adoptable->years = floor($age['months'] / 12);
            $adoptable->months = $age['months'] % 12;
            if($adoptable->years == 0 and $adoptable->months <= 2) {
                $adoptable->age = "Filhote";
            } else if($adoptable->years == 0 and $adoptable->months > 2) {
                $adoptable->age = $adoptable->months . " mes(es)";
            } else if($adoptable->years > 0 and $adoptable->months == 0) {
                $adoptable->age = $adoptable->years . " ano(s)";
            } else if($adoptable->years > 0 and $adoptable->months > 0) {
                $adoptable->age = $adoptable->years . " ano(s) e " . $adoptable->months . " mes(es)";
            }
        }
        return $adoptables;
    }

    public function show($id) {
        $adoptable = Adoptable::with(['pet.specie','pet.race','images'])->find($id);
        $age['months'] = $adoptable->pet->age;
        $adoptable->years = floor($age['months'] / 12);
        $adoptable->months = $age['months'] % 12;
        if($adoptable->years == 0 and $adoptable->months <= 2) {
            $adoptable->age = "Filhote";
        } else if($adoptable->years == 0 and $adoptable->months > 2) {
            $adoptable->age = $adoptable->months . " mes(es)";
        } else if($adoptable->years > 0 and $adoptable->months == 0) {
            $adoptable->age = $adoptable->years . " ano(s)";
        } else if($adoptable->years > 0 and $adoptable->months > 0) {
            $adoptable->age = $adoptable->years . " ano(s) e " . $adoptable->months . " mes(es)";
        }
        return $adoptable;
    }

    public function userAdoptables() {
        $user = Auth::user();
        $adoptables = Adoptable::where('user_id', $user->id)->with('pet.specie','image')->orderBy('id','desc')->get();
        return response()->json(['data'=>$adoptables]);
    }

    public function userAdoptable($id) {
        $user = Auth::user();
        $adoptable = Adoptable::where('user_id', $user->id)->with('pet.specie','pet.race','image')->find($id);
        return $adoptable;
    }

    public function store(Request $request) {
        //return [$request->adoptable];
        $user = Auth::user();
        $adoptable = $user->adoptables()->create([
            'castrated' => $request->adoptable['castrated'],
            'details' => $request->adoptable['details'],
            'dewomed' => $request->adoptable['dewomed'],
            'details' => $request->adoptable['details'],
            'status' => 'active',
        ]);
        if($adoptable) {
            $pet = $adoptable->pet()->create($request->pet);
            $image = $adoptable->image()->create($request->image);
        }
        return response()->json(["status" => "success", "id" => $adoptable->id]);
    }

    public function update(Request $request, $id) {
        $user = Auth::user();
        if(!$adoptable = Adoptable::where('user_id', $user->id)->find($id))
        return response()->json(["status" => "failed", "message" => "Registro não encontrado para o usuário logado."]);

        if($adoptable->update($request->all())) {
            return response()->json(["status" => "success", "message" => "Informações atualizadas com sucesso."]);
        } else {
            return response()->json(["status" => "failed", "message" => "Erro ao atualizar dados."]);
        }
    }

}
