<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaterialResource;
use App\Http\Resources\ResponseResource;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Throwable;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $materials = Material::all();
        return response()->json(new ResponseResource("Success", "Data Materials", $materials), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Cek autorisasi
        if(!$request->user()->tokenCan("modify-data")){
            return response()->json(new ResponseResource("Failed", 'Unauthorized', null), 401);
        }

        // Membuat Validator
        $validate = Validator::make($request->all(), [
            'class_id' => 'required|unique:App\Models\Class,id',
            'subject_id' => 'required|unique:App\Models\Subject,id',
            'title' => 'required',
            'material' => 'required',
        ]);

        // Cek Validasi
        if ($validate->fails()) {
            $errorMessages = $validate->messages()->all();
            return response()->json(new ResponseResource('Failed', 'Validation Error!', $errorMessages),400);
        }

        // Make a model and make a save transaction
        $matSave = new Material();
        $matSave->class_id = $request->class_id;
        $matSave->subject_id = $request->subject_id;
        $matSave->user_id = $request->user()->id;
        $matSave->title = $request->title;
        $matSave->material = $request->material;
        $matSave->created_at = date('Y-m-d');

        try{
            $matSave->saveOrFail();
            return response()->json(new ResponseResource('Success', 'Successfully inserted data!', $matSave), 200);
        }catch(Throwable $err){
            return response()->json(new ResponseResource('Failed', 'Error on inserting data', $err->getMessage(), 400));
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
        // Cari materi berdasarkan ID
        $mat = Material::find($id);
        if(!$mat){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }
        return response()->json(new ResponseResource('Success', 'Material Data', new MaterialResource($mat), 200));
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
