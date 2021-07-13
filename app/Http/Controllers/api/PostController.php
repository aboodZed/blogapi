<?php

namespace App\Http\Controllers\api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Post as PostResource;
use Illuminate\Support\Facades\Auth;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return $this->sendResponse(PostResource::collection($posts), 'Posts retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $input = $request->all();
        $input['user_id'] = Auth::id();
        $post = Post::create($input);
        return $this->sendResponse($post, 'Post added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (empty($post)) {
            return $this->sendError('Post not found!');
        }
        return $this->sendResponse(new PostResource($post), 'Post retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }
        if ($post->user_id != Auth::id()) {
            return $this->sendError('unauthorized');
        }
        $input = $request->all();
        $post->title = $input['title'];
        $post->description = $input['description'];
        $post->save();

        return $this->sendResponse(new PostResource($post), 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return $this->sendError('unauthorized');
        }
        $post->delete();
        return $this->sendResponse(new PostResource($post), 'Post deleted successfully');
    }

    public function userPosts($id)
    {
        $posts = Post::where('user_id', $id)->get();
        return $this->sendResponse(PostResource::collection($posts), 'Posts retrieved successfully');
    }
}
