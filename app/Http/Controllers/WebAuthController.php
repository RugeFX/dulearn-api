<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class WebAuthController extends Controller
{
    //
    // public function handle($request, Closure $next, ...$guards)
    // {
    //     $next($request)->header('Authorization', 'Bearer '. $request->session()->get('APITOKEN'));
    // }

    public function logIn(Request $request){
        $validate = Validator::make($request->all(), [
            "reg_num" => 'required',
            'password' => ['required', Password::min(8)],
        ]);

        if($validate->fails()){
            $errorMessages = $validate->messages()->all();
            return response()->json(new ResponseResource("Failed", "Validation Error!", $errorMessages), 400);
        }

        if(!Auth::attempt($request->only("reg_num", "password"))){
            return response()->json(new ResponseResource("Failed", "Invalid Login Details!", ["Invalid Login Details!"]), 401);
        }

        $request->session()->regenerate();
        return response()->json(new ResponseResource("Success!", "Logged In Succesfully!", $request->user()), 200);
    }

    public function logOut(Request $request){
        Auth::logout();
 
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(new ResponseResource("Success", "Logged Out", null), 200);
    }
}
