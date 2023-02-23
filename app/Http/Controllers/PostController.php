<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Post;
use Illuminate\Http\Request;

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
        //
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
