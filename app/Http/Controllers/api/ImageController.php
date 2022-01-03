<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function upload(Request $request) {
        //return $request->all();
        //return $request->file('picture_file');
        $timestamp = \Carbon\Carbon::now()->timestamp;
        $validator = Validator::make($request->all(), [
            'picture_file' => 'required|mimes:jpeg,jpg,png|max:4056'
        ]);
        if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "Imagem inválida"]);
        }

        $image = $request->file('picture_file');
        $extension = $image->getClientOriginalExtension();
        $filename = now()->timestamp . '.' . 'jpg';
        $picture = Image::make($image);
        $picture->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $picture->save('pets/'.$filename);
        $image_path = env("APP_URL") . "/pets/" . $filename;
        return response()->json(["status" => "success", "path" => $image_path]);
    }
}
