<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $post = new Post;

        if ($request->hasFile('post_image')) {
            $completeFileName = $request->file('post_image')->getClientOriginalName();
            $filenameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $fileExtensionOnly = $request->file('post_image')->getClientOriginalExtension();

            // $compPic = str_replace(' ', '_', $filenameOnly) . '-' . rand() . '_' . time() . '.' . $fileExtensionOnly;
            $compPic = str_replace(' ', '_', $filenameOnly) . '_' . time() . '.' . $fileExtensionOnly;
            // $compPic = now()->timestamp . '_' . str_replace(' ', '_', $filenameOnly);

            // dd($completeFileName, $filenameOnly, $fileExtensionOnly, $compPic);

            $path = $request->file('post_image')->storeAs('public/posts', $compPic);

            $post->user_id = $request->user()->id;
            $post->caption = $request->caption;
            $post->post_image = $compPic;
        }

        if ($post->save()) {
            return ['status' => 'success', 'message' => 'Post saved successfully'];
        } else {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
        }
    }

    // NOTE: the content of this function will be moved to the profile page
    public function index(Request $request)
    {
        $posts = Post::where('user_id', $request->user()->id)->get();
        $postsImgURL = [];

        // foreach ($posts as $post) {
        // $file = Storage::get('public/posts/' . $post->post_image);
        // array_push($postsImgURL, $file);
        // }

        foreach ($posts as $post) {
            $file = Storage::url('public/posts/' . $post->post_image);
            array_push($postsImgURL, $file);
        }

        return $postsImgURL;
    }
}
