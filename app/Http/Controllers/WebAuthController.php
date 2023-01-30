<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\RegisteredUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Throwable;

class WebAuthController extends Controller
{
    public function registerUser(Request $request){
        // Membuat Validator dengn rules
        $validate = Validator::make($request->all(), [
            "reg_num" => 'required',
            'password' => ['required', Password::min(8)->numbers()],
        ]);

        // Cek Validasi
        if ($validate->fails()) {
            $errorMessages = $validate->messages()->toJson();
            return response()->json(new ResponseResource('Failed', 'Validation Error!', $errorMessages), 400);
        }

        // Cek if registration ID exists
        $regCheck = RegisteredUser::query()->where('reg_num', '=', $request->reg_num)->first();
        if(!$regCheck){
            return response()->json(new ResponseResource('Failed', 'NISN/NIP not found!', ["reg_num" => 'NISN/NIP not found!']), 404);
        }
        // Cek if registratin ID is used
        if($regCheck->is_used == 1){
            return response()->json(new ResponseResource('Failed', 'User already exists', ["all" => 'User already exists']), 409);
        }
        // Make a model and make a save transaction
        $userSave = new User();
        $userSave->reg_num = $request->reg_num;
        $userSave->profile_picture = 'default.png';
        $userSave->password = Hash::make($request->password);
        $userSave->level_id = 1;
        $userSave->created_at = date('Y-m-d');

        try{
            // Tries to do transaction
            $userSave->saveOrFail();

            // Update is_used field to 1 indicationg that the NIS is used
            $regCheck->is_used = 1;
            $regCheck->update();

            // Logs in the user
            $request->session()->regenerate();

            return response()->json(new ResponseResource('Success', 'Successfully registered user!', $userSave), 200);
        }catch(Throwable $err){
            return response()->json(new ResponseResource('Failed', 'Error on inserting data', ["all" => $err->getMessage()]), 400);
        }
    }

    public function logIn(Request $request){
        $validate = Validator::make($request->all(), [
            "reg_num" => 'required',
            'password' => ['required', Password::min(8)],
        ]);

        if($validate->fails()){
            $errorMessages = $validate->messages();
            return response()->json(new ResponseResource("Failed", "Validation Error!", $errorMessages), 400);
        }

        if(!Auth::attempt($request->only("reg_num", "password"))){
            return response()->json(new ResponseResource("Failed", "Invalid Login Details!", ["all" => "Invalid Login Details!"]), 401);
        }

        $request->session()->regenerate();
        return response()->json(new ResponseResource("Success", "Logged In Succesfully!", $request->user()), 200);
    }

    public function logOut(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(new ResponseResource("Success", "Logged Out", null), 200);
    }
}
