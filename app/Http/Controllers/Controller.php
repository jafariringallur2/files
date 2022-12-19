<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function uploadFile(Request $request){
        $validator = Validator::make(Request()->all(), [
            'auth_key' => 'required',
            'bucket' => 'required',
            "file" => "required" ,
            "file_name" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, "errorMsg" => $validator->errors()->first()]);
        }
        if($request->auth_key != env('AUTH_KEY')){
            return response()->json(['success' => false, "errorMsg" => "invalid key"]);
        }
        $new_file_name = $request->bucket.'/'.$request->file_name;
        Storage::disk('public')->put($new_file_name, file_get_contents($request->file));
        $url = url($new_file_name);
        return response()->json([
            'success' => true,
            'file_url' => $url,
        ]);
    }
}
