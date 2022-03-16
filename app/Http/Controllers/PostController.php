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
        // if ($request->recipe) {
        //     dd('true');
        // }
        // dd(json_decode($request->recipe, true));
        DB::beginTransaction();
        try {

            // $post = new Post;

            if ($request->hasFile('post_image')) {
                $completeFileName = $request->file('post_image')->getClientOriginalName();
                $filenameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                $fileExtensionOnly = $request->file('post_image')->getClientOriginalExtension();

                // $compPic = str_replace(' ', '_', $filenameOnly) . '-' . rand() . '_' . time() . '.' . $fileExtensionOnly;
                $compPic = str_replace(' ', '_', $filenameOnly) . '_' . time() . '.' . $fileExtensionOnly;
                // $compPic = now()->timestamp . '_' . str_replace(' ', '_', $filenameOnly);

                // dd($completeFileName, $filenameOnly, $fileExtensionOnly, $compPic);

                $path = $request->file('post_image')->storeAs('public/posts', $compPic);

                // using eloquent
                // $post->user_id = $request->user()->id;
                // $post->caption = $request->caption;
                // $post->post_image = $compPic;

                $post_id = DB::table('posts')->insertGetId(array(
                    'user_id' => $request->user()->id,
                    'caption' => $request->caption,
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


            // using eloquent
            // if ($post->save()) {
            //     if ($request->recipe) {
            //         $recipe = new Recipe;
            //         $data = json_decode($request->recipe, true);
            //         // dd(json_encode($data['procedures']));
            //         // $post->id;
            //         $recipe->post_id = $post->id;
            //         $recipe->ingredient = json_encode($data['ingredients']);
            //         $recipe->procedure = json_encode($data['procedures']);
            //         return ['status' => 'success', 'message' => 'Post saved successfully'];
            //     }
            // } else {
            //     return ['status' => 'Failed', 'message' => 'Something went wrong'];
            // }
        } catch (\Exception $e) {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
            DB::rollback();
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
