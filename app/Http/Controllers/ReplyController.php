<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Reply;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ReplyController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->query('postid') != null){
            $postid = $request->query('postid');
            $replies = Reply::with(['post', 'user' => fn ($query) => $query->with('registeredUser')])->where('post_id', '=', $postid)->get();
            if(count($replies) < 1) {
                return response()->json(new ResponseResource("Failed", "Replies with post id : ".$postid." doesn't exists!", null), 404);
            }
            return response()->json(new ResponseResource("Success", "Replies data post id : ".$postid, $replies), 200);
        }
        $replies = Reply::with(['post', 'user'])->get();
        return response()->json(new ResponseResource("Success", "Replies data", $replies), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Ref : 'post_id', 'user_id', 'reply', 'created_at',
        $validate = Validator::make($request->all(), [
            'post_id' => 'required', 
            'reply' => 'required',
        ]);

        // Cek Validasi
        if ($validate->fails()) {
            $errorMessages = $validate->messages()->all();
            return response()->json(new ResponseResource('Failed', 'Validation Error!', $errorMessages),400);
        }

        try{
            $reply = Reply::query()->create([
                'post_id' => $request->post_id, 
                'user_id' => $request->user()->id, 
                'reply' => $request->reply,
                'created_at' => date("Y-m-d")
            ]);
            return response()->json(new ResponseResource('Success', 'Insert Successfull!', $reply),200);
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
        $reply = Reply::with(['post', 'user'])->where("id", "=", $id)->first();

        if(!$reply){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }

        return response()->json(new ResponseResource("Success", "Reply data", $reply), 200);
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
        $reply = Reply::query()->find($id);

        if(!$reply) {
            return response()->json(new ResponseResource("Failed", "This reply doesn't exists!", null), 404);
        }

        if($request->user()->id != $reply->user_id){
            return response()->json(new ResponseResource("Failed", "You are not the owner of this reply!", null), 400);
        }

        try{
            $upd = $reply->updateOrFail($request->only([
                'post_id', 'user_id', 'reply', 'created_at',
            ]));
            if (!$upd) {
                return response()->json(new ResponseResource("Failed", "Update failed!", null), 400);
            }
            return response()->json(new ResponseResource("Success", "Update successful!", $reply), 200);
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
        $reply = Reply::find($id);

        if(!$reply) {
            return response()->json(new ResponseResource("Failed", "This reply doesn't exists!", null), 404);
        }

        if($request->user()->id == $reply->user_id){
            $reply->delete();
            return response()->json(new ResponseResource("Success", "Reply successfully deleted!", null), 200);
        }
        return response()->json(new ResponseResource("Failed", "You are not the owner of this reply!", null), 400);
    }

    public function owned(Request $request)
    {
        //
        $replies = Reply::with(['post', 'user'])->where('user_id', '=', $request->user()->id)->get();
        if(count($replies) < 1){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }
        return response()->json(new ResponseResource("Success", "Data Replies", $replies), 200);
    }
}
