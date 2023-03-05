<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Subject;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $subjects = Subject::with(['materials'])->get();
        return response()->json(new ResponseResource("Success", "Subjects data", $subjects), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'subject' => 'required', 
        ]);

        // Cek Validasi
        if ($validate->fails()) {
            $errorMessages = $validate->messages()->all();
            return response()->json(new ResponseResource('Failed', 'Validation Error!', $errorMessages),400);
        }

        try{
            $subject = Subject::query()->create([
                'subject' => $request->subject, 
            ]);
            return response()->json(new ResponseResource('Success', 'Insert Successfull!', $subject),200);
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
        $subject = Subject::with(['materials'])->where("id", "=", $id)->first();

        if(!$subject){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }

        return response()->json(new ResponseResource("Success", "Subject data", $subject), 200);
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
        $subject = Subject::query()->find($id);

        if(!$subject) {
            return response()->json(new ResponseResource("Failed", "This subject doesn't exists!", null), 404);
        }

        try{
            $upd = $subject->updateOrFail($request->only([
                'subject'
            ]));
            if (!$upd) {
                return response()->json(new ResponseResource("Failed", "Update failed!", null), 400);
            }
            return response()->json(new ResponseResource("Success", "Update successful!", $subject), 200);
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
        $subject = Subject::find($id);

        if(!$subject) {
            return response()->json(new ResponseResource("Failed", "This subject doesn't exists!", null), 404);
        }

        if($request->user()->level_id == 2){
            $subject->delete();
            return response()->json(new ResponseResource("Success", "Subject successfully deleted!", null), 200);
        }
        return response()->json(new ResponseResource("Failed", "You are not allowed to delete this subject!", null), 401);
    }
}
