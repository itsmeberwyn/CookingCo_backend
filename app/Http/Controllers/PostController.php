<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $image = $request->post_image['base64String']; // image base64 encoded
            $compPic =  '_image' . time() . '.' . $request->post_image['format'];
            Storage::disk('public')->put($compPic, base64_decode($image));


            $post_id = DB::table('posts')->insertGetId(array(
                'user_id' => $request->user()->id,
                'caption' => $request->caption,
                'tag' => json_encode($request->tag),
                'post_image' => $compPic,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),
            ));

            if ($request->recipe) {
                DB::table('recipes')->insertGetId(array(
                    'post_id' => $post_id,
                    'ingredient' => json_encode($request->recipe['ingredients']),
                    'procedure' => json_encode($request->recipe['procedures']),
                    "created_at" =>  \Carbon\Carbon::now(),
                    "updated_at" => \Carbon\Carbon::now(),
                ));
            }
            DB::commit();
            return ['status' => 'success', 'message' => 'Post saved successfully'];
        } catch (\Exception $e) {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
            DB::rollback();
        }
    }

    // NOTE: the content of this function will be moved to the profile page
    public function index(Request $request)
    {
        // paghiwalayin mo nalang sa frontend ung post lang at ung post na may recipe
        // user json parse to parse the array object string
        $posts = Post::where('user_id', $request->user()->id)->with('recipe')->skip($request->skip)->take(3)->orderBy('created_at', 'desc')->get();

        foreach ($posts as $key => $post) {
            $file = Storage::url('public/posts/' . $post->post_image);

            $post['post_image'] = $file;
        }

        return $posts;
    }

    public function show(Request $request)
    {
        $posts = Post::where('id', $request->route()->parameter('post_id'))->with('recipe')->get();

        return $posts;
    }
}
