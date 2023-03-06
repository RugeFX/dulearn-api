<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $materials = Material::with(['kelas','subject', 'user']);

        $searchQuery = $request->query('search');
        $filterQuery = $request->query('filter');

        if ($searchQuery && $filterQuery) {
            // Search dan filter ada
            $materials = $materials->where(function ($query) use ($searchQuery) {
                 $query->where('title', 'like', "%{$searchQuery}%")
                    ->orWhere('material', 'like', "%{$searchQuery}%");
                })->where('subject_id', $filterQuery);
        } elseif ($searchQuery) {
            // Hanya search
            $materials = $materials->where('title', 'like', "%{$searchQuery}%")
                                ->orWhere('material', 'like', "%{$searchQuery}%");
        } elseif ($filterQuery) {
            // Hanya filter
            $materials = $materials->where('subject_id', $filterQuery);
        }

        $materials = $materials->get();

        if ($materials->isEmpty()) {
            return response()->json(new ResponseResource("Failed", "No matching records found", null), 404);
        }
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
        if(!$request->user()->level_id == 2){
            return response()->json(new ResponseResource("Failed", 'Unauthorized', null), 401);
        }

        // Membuat Validator
        $validate = Validator::make($request->all(), [
            'class_id' => 'required',
            'subject_id' => 'required',
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
        $mat = Material::query()->with(['kelas', 'subject', 'user', 'posts'])->find($id);
        if(!$mat){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }
        return response()->json(new ResponseResource('Success', 'Material Data', $mat, 200));
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
        $mat = Material::query()->find($id);

        if(!$mat) {
            return response()->json(new ResponseResource("Failed", "This material doesn't exists!", null), 404);
        }

        if($request->user()->id != $mat->user_id){
            return response()->json(new ResponseResource("Failed", "You are not the owner of this material!", null), 400);
        }

        try{
            $upd = $mat->updateOrFail($request->only([
                'class_id', 'subject_id', 'title', 'material',
            ]));
            if (!$upd) {
                return response()->json(new ResponseResource("Failed", "Update failed!", null), 400);
            }
            return response()->json(new ResponseResource("Success", "Update successful!", $mat), 200);
        }
        catch(Throwable $ex){
            return response()->json(new ResponseResource("Failed", "Update failed!", $ex->getMessage()), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $mat = Material::find($id);
        if($request->user()->id == $mat->user->id){
            $mat->delete();
            return response()->json(new ResponseResource("Success", "Berhasil menghapus materi!", null), 200);
        }
    }

    public function owned(Request $request)
    {
        //
        $materials = Material::with(['kelas' ,'subject', 'user', 'posts'])->where('user_id', '=', $request->user()->id)->get();
        if(count($materials) < 1){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }
        return response()->json(new ResponseResource("Success", "Data Materials", $materials), 200);
    }
}
