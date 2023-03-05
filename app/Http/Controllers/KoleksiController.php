<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Koleksi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class KoleksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // if($request->query('matid') != null){
        //     $matid = $request->query('matid');
        //     $koleksis = Koleksi::with(['user', 'material'])->where('material_id', '=', $matid)->get();
        //     if(count($posts) < 1) {
        //         return response()->json(new ResponseResource("Failed", "Posts with material id : ".$matid." doesn't exists!", null), 404);
        //     }
        //     return response()->json(new ResponseResource("Success", "Posts data material id : ".$matid, $posts), 200);
        // }
        $koleksis = Koleksi::with(['user', 'material'])->get();
        return response()->json(new ResponseResource("Success", "Koleksi data", $koleksis), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Ref : 'user_id', 'material_id', 'created_at'
        $validate = Validator::make($request->all(), [
            'material_id' => 'required',
        ]);

        // Cek Validasi
        if ($validate->fails()) {
            $errorMessages = $validate->messages()->all();
            return response()->json(new ResponseResource('Failed', 'Validation Error!', $errorMessages),400);
        }

        try{
            $koleksi = Koleksi::query()->create([
                'user_id' => $request->user()->id,
                'material_id' => $request->material_id, 
                'created_at' => date("Y-m-d")
            ]);
            return response()->json(new ResponseResource('Success', 'Insert Successful!', $koleksi),200);
        }catch(QueryException $ex){
            return response()->json(new ResponseResource('Failed', 'Insert Error!', $ex), 400);
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
        //
        $koleksi = Koleksi::with(['user', 'material'])->where("id", "=", $id)->first();

        if(!$koleksi){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }

        return response()->json(new ResponseResource("Success", "Posts data", $koleksi), 200);
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
        $koleksi = Koleksi::query()->find($id);

        if(!$koleksi) {
            return response()->json(new ResponseResource("Failed", "This koleksi doesn't exists!", null), 404);
        }

        if($request->user()->id != $koleksi->user_id){
            return response()->json(new ResponseResource("Failed", "You are not the owner of this koleksi!", null), 400);
        }

        try{
            $upd = $koleksi->updateOrFail($request->only([
                'material_id',
            ]));
            if (!$upd) {
                return response()->json(new ResponseResource("Failed", "Update failed!", null), 400);
            }
            return response()->json(new ResponseResource("Success", "Update successful!", $koleksi), 200);
        }
        catch(Throwable $ex){
            return response()->json(new ResponseResource("Failed", "Update failed!", $ex->getMessage()), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $koleksi = Koleksi::find($id);

        if(!$koleksi) {
            return response()->json(new ResponseResource("Failed", "This koleksi doesn't exists!", null), 404);
        }

        if($request->user()->id == $koleksi->user->id){
            $koleksi->delete();
            return response()->json(new ResponseResource("Success", "Koleksi successfully deleted!", null), 200);
        }
        return response()->json(new ResponseResource("Failed", "You are not the owner of this koleksi!", null), 400);
    }

    public function owned(Request $request)
    {
        //
        $koleksis = Koleksi::with(['user', 'material' => fn ($query) => $query->with('subject')])->where('user_id', '=', $request->user()->id)->get();
        if(count($koleksis) < 1){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }
        return response()->json(new ResponseResource("Success", "Data koleksi", $koleksis), 200);
    }

    public function addKoleksi(Request $request, $matid){
        try{
            $koleksi = Koleksi::query()->create([
                'user_id' => $request->user()->id,
                'material_id' => $matid, 
                'created_at' => date("Y-m-d")
            ]);
            return response()->json(new ResponseResource('Success', 'Insert Successful!', $koleksi),200);
        }catch(QueryException $ex){
            return response()->json(new ResponseResource('Failed', 'Insert Error!', $ex), 400);
        }
    }

    public function removeKoleksi(Request $request, $matid)
    {
        $koleksi = Koleksi::query()->where("material_id", '=', $matid)->first();

        if(!$koleksi){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }

        $koleksi->delete();
        return response()->json(new ResponseResource("Success", "Koleksi successfully deleted!", null), 200);
    }
}
