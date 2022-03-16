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

            if ($request->hasFile('post_image')) {
                $completeFileName = $request->file('post_image')->getClientOriginalName();
                $filenameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                $fileExtensionOnly = $request->file('post_image')->getClientOriginalExtension();

                $compPic = str_replace(' ', '_', $filenameOnly) . '_' . time() . '.' . $fileExtensionOnly;

                $path = $request->file('post_image')->storeAs('public/posts', $compPic);


                $post_id = DB::table('posts')->insertGetId(array(
                    'user_id' => $request->user()->id,
                    'caption' => $request->caption,
                    'tag' => $request->tag,
                    'post_image' => $compPic,
                    "created_at" =>  \Carbon\Carbon::now(),
                    "updated_at" => \Carbon\Carbon::now(),
                ));

                if ($request->recipe) {
                    $data = json_decode($request->recipe, true);
                    DB::table('recipes')->insertGetId(array(
                        'post_id' => $post_id,
                        'ingredient' => json_encode($data['ingredients']),
                        'procedure' => json_encode($data['procedures']),
                        "created_at" =>  \Carbon\Carbon::now(),
                        "updated_at" => \Carbon\Carbon::now(),
                    ));
                }
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
}
