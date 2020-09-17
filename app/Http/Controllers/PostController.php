<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|max:10240',
            'user_id' => 'required',
        ]);

        $data = [];
        if ($request->hasFile('file')) {
            $destinationPath = public_path('/uploads');
            $file = $request->file;
            $filename = time().'.'.$file->getClientOriginalName();
            $file->move($destinationPath, $filename);

            $post = new Post();
            $post->file_path = $filename;
            $post->title = $request->title;
            $post->location = $request->location;
            $post->user_id = $request->user_id;

            if ($post->save()) {
                return response()->json(['success'=>true, 'data'=>$post, 'message' => 'Post created successfully.']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Something went wrong.']);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
