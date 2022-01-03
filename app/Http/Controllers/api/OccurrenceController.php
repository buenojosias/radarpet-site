<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Occurrence;
use App\Models\Location;
use Carbon\Carbon;
// use App\Models\Location;
use Illuminate\Support\Facades\DB;

class OccurrenceController extends Controller
{

    public function index($type) {
        $occurrences = Occurrence::where('type', $type)
        ->whereHas('pet')->whereHas('location')->whereHas('image')
        ->with(['pet.specie','pet.race','location','image'])
        // ->orderByRaw(DB::select("select *,ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS($lat) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS($lat)) * COS( RADIANS( `longitude` ) - RADIANS($long))) * 6380 AS distance from locations having distance <= 20000 order by distance"))
        ->orderBy('id','desc')
        ->get();
        foreach($occurrences as $occurrence) {
            Carbon::setLocale('pt_BR');
            $occurrence->occurred_at = Carbon::parse($occurrence->occurred_at)->diffForHumans();
        }
        return $occurrences;
    }

    // public function index($type) {
    //     $lat = $mylat ?? -25.3877618;
    //     $long = $mylong ?? -49.2951362;

    //     $occurrences = DB::table('occurrences')
    //         ->join('locations', 'occurrences.id', '=', 'locations.locationable_id')
    //         ->join('pets', 'occurrences.id', '=', 'pets.petable_id')
    //         ->join('images', 'occurrences.id', '=', 'images.imageable_id')
    //         ->join('species', 'pets.specie_id', '=', 'species.id')
    //         ->join('races', 'pets.race_id', '=', 'races.id')
    //         ->select([
    //             'occurrences.type','occurrences.id','occurrences.reward','occurrences.occurred_at',
    //             'images.path',
    //             'locations.district','locations.address','locations.city',
    //             //DB::raw("(6380*ACOS(COS(RADIANS(latitude))*COS(RADIANS($lat))*COS(RADIANS(longitude)-RADIANS($long))+SIN(RADIANS(latitude))*SIN(RADIANS($lat)))) AS distance, $lat AS latitude, $long AS longitude"),
    //             'pets.name','pets.gender','pets.size',
    //             // 'species.name AS specie','races.name AS race'
    //         ])
    //         //->where('occurrences.type', $type)
    //         ->where('occurrences.status', 'active')
    //         ->orderBy('occurrences.id')
    //         //->orderBy('distance')
    //         ->get();

    //     foreach($occurrences as $occurrence) {
    //         Carbon::setLocale('pt_BR');
    //         $occurrence->occurred_at = Carbon::parse($occurrence->occurred_at)->diffForHumans();
    //     }

    //     return $occurrences;
    // }

    public function show($type, $id) {
        $occurrence = Occurrence::with(['pet.specie','pet.race','location','images'])->where('type', $type)->findOrFail($id);
        Carbon::setLocale('pt_BR');
        $occurrence->diff_for_humans = Carbon::parse($occurrence->occurred_at)->diffForHumans();
        return $occurrence;
    }

    public function userOccurrences() {
        $user = Auth::user();
        $occurrences = Occurrence::where('user_id', $user->id)->with('pet.specie','image')->orderBy('id','desc')->get();
        return response()->json(['data'=>$occurrences]);
    }

    public function userOccurrence($id) {
        $user = Auth::user();
        $occurrence = Occurrence::where('user_id', $user->id)->with('pet.specie','pet.race','image')->find($id);
        return $occurrence;
    }

    public function store(Request $request) {
        $user = Auth::user();
        $occurrence = $user->occurrences()->create([
            'type' => $request->occurrence['type'],
            'details' => $request->occurrence['details'],
            'reward' => $request->occurrence['reward'],
            'status' => 'active',
            'occurred_at' => \Carbon\Carbon::now(),
        ]);
        if($occurrence) {
            $pet = $occurrence->pet()->create($request->pet);
            $location = $occurrence->location()->create($request->location);
            $image = $occurrence->image()->create($request->image);
        }
        return response()->json(["status" => "success", "id" => $occurrence->id]);
    }

    public function update(Request $request, $id) {
        $user = Auth::user();
        if(!$occurrence = Occurrence::where('user_id', $user->id)->find($id))
        return response()->json(["status" => "failed", "message" => "Registro não encontrado para o usuário logado."]);

        if($occurrence->update($request->all())) {
            return response()->json(["status" => "success", "message" => "Informações atualizadas com sucesso."]);
        } else {
            return response()->json(["status" => "failed", "message" => "Erro ao atualizar dados."]);
        }
    }

}

// $mylat = -25.387654673836128;
// $mylong = -49.29446176922547;

// $lat = $mylat ?? -25.4947402;
// $long = $mylong ?? -49.4298814;

// $locations = DB::select("select *,ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS($lat) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS($lat)) * COS( RADIANS( `longitude` ) - RADIANS($long))) * 6380 AS distance from locations having distance<=20000 order by distance");

// return $locations;


// $occurrences = Occurrence::select(['occurrences.*', 'locations.district as location_district'])
//     ->join('locations', 'locations.locationable_id', '=', 'occurrences.id')
//     ->orderBy('locations.district')
//     ->get();