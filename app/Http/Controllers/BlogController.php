<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;


class BlogController extends Controller
{

     public function __construct()
    {
      $this->middleware('auth:api')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        return BlogResource::collection(Blog::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $blog = Blog::create([
        'user_id' => auth()->user()->id,
        'title' => $request->title,
        'body' => $request->body,
      ]);

      return new BlogResource($blog);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
         //echo "<pre>";Blog::find(1);die;
         return new BlogResource($blog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
      if (auth()->user()->id !== $blog->user_id) {
        return Response()->json(['error' => 'You can only edit your own Blogs.'], 403);
      }

      $blog->update($request->only(['title', 'body']));

      return new BlogResource($blog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Blog $blog)
    {
        if (auth()->user()->id !== $blog->user_id) {
        return response()->json(['error' => 'You can only delete your own Blogs.'], 403);
      }

      $blog->delete();

      return response()->json(null, 204);
  
    }
}
