<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Models\RegisteredUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Throwable;

class AuthController extends Controller
{
    //
    public function loginUser(Request $request) {

        $validate = Validator::make($request->all(), [
            "reg_num" => 'required',
            'password' => ['required'],
        ]);

        if($validate->fails()){
            $errorMessages = $validate->messages()->all();
            return response()->json(new ResponseResource("Failed", "Validation Error!", $errorMessages), 400);
        }

        if(!Auth::attempt($request->only("reg_num", "password"))){
            return response()->json(new ResponseResource("Failed", "Invalid Login Details!", ["Invalid Login Details!"]), 400);
        }

        $user = User::query()->where("reg_num", $request->reg_num)->first();

        if($user->level_id == 2){
            return response()->json(new ResponseResource("Success", "Login Success!", $user->createToken("APITOKEN", ['read-data', 'modify-all'])->plainTextToken), 200);
        }
        return response()->json(new ResponseResource("Success", "Login Success!", $user->createToken("APITOKEN", ['read-data', 'modify-self-post'])->plainTextToken), 200);
    }

    public function registerUser(Request $request){
        // Membuat Validator dengn rules
        $validate = Validator::make($request->all(), [
            "reg_num" => 'required',
            'password' => ['required', Password::min(8)->numbers()],
        ]);

        // Cek Validasi
        if ($validate->fails()) {
            $errorMessages = $validate->messages()->all();
            return response()->json(new ResponseResource('Failed', 'Validation Error!', $errorMessages), 400);
        }

        // Cek if registration ID exists
        $regCheck = RegisteredUser::query()->where('reg_num', '=', $request->reg_num)->first();
        if(!$regCheck){
            return response()->json(new ResponseResource('Failed', 'NISN/NIP not found!', null), 404);
        }
        // Cek if registratin ID is used
        if($regCheck->is_used == 1){
            return response()->json(new ResponseResource('Failed', 'User already exists', null), 409);
        }
        // Make a model and make a save transaction
        $userSave = new User();
        $userSave->reg_num = $request->reg_num;
        $userSave->profile_picture = 'default.png';
        $userSave->password = Hash::make($request->password);
        $userSave->level_id = 1;
        $userSave->created_at = date('Y-m-d');

        try{
            $userSave->saveOrFail();
            return response()->json(new ResponseResource('Success', 'Successfully registered user!', $userSave), 200);
        }catch(Throwable $err){
            return response()->json(new ResponseResource('Failed', 'Error on inserting data', $err->getMessage()), 400);
        }
    }

    public function logOutUser(Request $request){
        $user = $request->user();
        if(!$user){
            return response()->json(new ResponseResource("Failed", "You Are Not Authenticated Yet / Invalid Token", null), 400);
        }
        $user->tokens()->delete();
        return response()->json(new ResponseResource("Success", "Logged Out Successfully!", null), 200);
    }

    public function regInfo(Request $request){
        $user = RegisteredUser::where('reg_num', $request->reg_num)->first();
        if(!$user){
            return response()->json(new ResponseResource('Failed', 'NISN Not Found!', null), 404);
        }
        return response()->json(new ResponseResource('Success', 'NISN Info', $user), 200);
    }
}
