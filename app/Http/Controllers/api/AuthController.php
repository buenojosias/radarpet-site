<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            "email" =>  "required|email",
            "password" =>  "required",
        ]);
        if($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }
        $user = User::where("email", $request->email)->first();
        if(is_null($user)) {
            return response()->json(["status" => "failed", "message" => "E-mail não cadastrado"]);
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()->json(["status" => "success", "login" => true, "token" => $token, "data" => $user]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Senha incorreta"]);
        }
    }

    public function logout() {
        $user = Auth::user();
        if(!is_null($user)) {
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            return response()->json(["status" => "success"]);
        } else {
            return response()->json(["status" => "failed", "message" => "Usuário não encontrado"]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }
        if(User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 1
        ]))
        {
            Auth::attempt(['email' => $request->email, 'password' => $request->password]);
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()->json(["status" => "success", "login" => true, "token" => $token, "data" => $user]);
        }
    }

    public function user() {
        $user = Auth::user();
        return response()->json(["status" => "success", "data" => $user]);
    }

}
