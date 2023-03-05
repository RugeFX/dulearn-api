<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Post;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->query('matid') != null){
            $matid = $request->query('matid');
            $posts = Post::with(['user', 'material'])->where('material_id', '=', $matid)->get();
            if(count($posts) < 1) {
                return response()->json(new ResponseResource("Failed", "Posts with material id : ".$matid." doesn't exists!", null), 404);
            }
            return response()->json(new ResponseResource("Success", "Posts data material id : ".$matid, $posts), 200);
        }
        $posts = Post::with(['user', 'material'])->get();
        return response()->json(new ResponseResource("Success", "Posts data", $posts), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Ref : 'user_id', 'material_id', 'title', 'body', 'created_at'
        $validate = Validator::make($request->all(), [
            'material_id' => 'required',
            'title' => 'required',
            'body' => 'required',
        ]);

        // Cek Validasi
        if ($validate->fails()) {
            $errorMessages = $validate->messages()->all();
            return response()->json(new ResponseResource('Failed', 'Validation Error!', $errorMessages),400);
        }

        try{
            $post = Post::query()->create([
                'user_id' => $request->user()->id,
                'material_id' => $request->material_id, 
                'title' => $request->title, 
                'body' => $request->body, 
                'created_at' => date("Y-m-d")
            ]);
            return response()->json(new ResponseResource('Success', 'Insert Successfull!', $post),200);
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
        $post = Post::with(['user', 'material'])->where("id", "=", $id)->first();

        if(!$post){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }

        return response()->json(new ResponseResource("Success", "Posts data", $post), 200);
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
        $post = Post::query()->find($id);

        if(!$post) {
            return response()->json(new ResponseResource("Failed", "This post doesn't exists!", null), 404);
        }

        if($request->user()->id != $post->user_id){
            return response()->json(new ResponseResource("Failed", "You are not the owner of this post!", null), 400);
        }

        try{
            $upd = $post->updateOrFail($request->only([
                'material_id', 'title', 'body'
            ]));
            if (!$upd) {
                return response()->json(new ResponseResource("Failed", "Update failed!", null), 400);
            }
            return response()->json(new ResponseResource("Success", "Update successful!", $post), 200);
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
        $post = Post::find($id);

        if(!$post) {
            return response()->json(new ResponseResource("Failed", "This post doesn't exists!", null), 404);
        }

        if($request->user()->id == $post->user->id){
            $post->delete();
            return response()->json(new ResponseResource("Success", "Post successfully deleted!", null), 200);
        }
        return response()->json(new ResponseResource("Failed", "You are not the owner of this post!", null), 400);
    }

    public function owned(Request $request)
    {
        //
        $posts = Post::with(['user', 'material'])->where('user_id', '=', $request->user()->id)->get();
        if(count($posts) < 1){
            return response()->json(new ResponseResource('Failed', 'No Data Found', null), 404);
        }
        return response()->json(new ResponseResource("Success", "Data Posts", $posts), 200);
    }
}
