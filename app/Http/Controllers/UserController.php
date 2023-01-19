<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Http\Resources\UserResource;
use App\Models\Level;
use App\Models\RegisteredUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil semua user
        $user = User::all();
        if(!$user || $user->isEmpty()){
            return new ResponseResource('Failed', 'No Data Found', null);
        }
        return new ResponseResource('Success', 'User Data', $user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Membuat Validator dengn rules
        $validate = Validator::make($request->all(), [
            "reg_num" => 'required',
            'name' => 'required',
            'password' => 'required',
            'level_id' => 'required',
            'created_at'
        ]);

        // Cek Validasi
        if ($validate->fails()) {
            $errorMessages = $validate->messages()->all();
            return new ResponseResource('Failed', 'Validation Error!', $errorMessages);
        }

        // Cek if registration ID exists
        $regCheck = RegisteredUser::where('reg_num', '=', $request->reg_num)->first();
        if(!$regCheck){
            return new ResponseResource('Failed', 'NISN/NIP not found!', null);
        }
        // Cek if registratin ID is used
        if($regCheck->is_used == 1){
            return new ResponseResource('Failed', 'User already exists', null);
        }
        // Make a model and make a save transaction
        $userSave = new User;
        $userSave->reg_num = $request->reg_num;
        $userSave->profile_picture = 'default.png';
        $userSave->name = $request->name;
        $userSave->password = Hash::make($request->password);
        $userSave->level_id = $request->level_id;
        $userSave->created_at = date('Y-m-d');
        try{
            $userSave->saveOrFail();
            return new ResponseResource('Success', 'Successfully inserted data!', $userSave->all());
        }catch(Throwable $err){
            return new ResponseResource('Failed', 'Error on inserting data', $err->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Cari user berdasarkan ID
        $user = User::find($id);
        if(!$user){
            return new ResponseResource('Failed', 'No Data Found', null);
        }
        return new ResponseResource('Success', 'User Data', new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
