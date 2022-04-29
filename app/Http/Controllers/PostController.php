<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Recipe;
use App\Models\User;
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
                'title' => $request->title,
                'caption' => $request->caption,
                'tag' => json_encode($request->tag),
                'post_image' => $compPic,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),
            ));

            if ($request->recipe['ingredients'] || $request->recipe['procedures']) {
                DB::table('recipes')->insertGetId(array(
                    'post_id' => $post_id,
                    'duration' => $request->duration,
                    'calories' => $request->calories,
                    'servings' => $request->servings,
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

    // NOTE: work here!! create another method for fetching the recipe data
    public function index(Request $request, $user_id = null)
    {

        // return $user_id;
        // paghiwalayin mo nalang sa frontend ung post lang at ung post na may recipe
        // user json parse to parse the array object string
        // $posts = Post::where('user_id', $request->user()->id)->with('recipe')->skip($request->skip)->take(9)->orderBy('created_at', 'desc')->get();
        // return $user_id ? "user id " .  $user_id : "current user id " . $request->user()->id;
        $posts = Post::doesnthave('recipe')->where('user_id', $user_id ? $user_id : $request->user()->id)->skip($request->get('skip'))->take(9)->orderBy('created_at', 'desc')->get();

        foreach ($posts as $key => $post) {
            $file = Storage::url('public/posts/' . $post->post_image);

            $post['post_image'] = $file;
        }

        $totalPost = Post::where('user_id', $user_id ? $user_id : $request->user()->id)->count();
        $user = User::find($user_id ? $user_id : $request->user()->id);
        return ["info" => $user, "totalpost" => $totalPost, "data" => $posts];
        // return $posts;
    }

    public function getDataRecipe(Request $request, $user_id = null)
    {
        // $posts = Post::where('user_id', $request->user()->id)->with('recipe')->skip($request->skip)->take(6)->orderBy('created_at', 'desc')->get();
        $posts = Post::join('recipes', 'recipes.post_id', '=', 'posts.id')->where('user_id', $user_id ? $user_id : $request->user()->id)->skip($request->get('skip'))->take(9)->get();
        foreach ($posts as $key => $post) {
            $file = Storage::url('public/posts/' . $post->post_image);

            $post['post_image'] = $file;
        }


        $user = User::find($user_id ? $user_id : $request->user()->id);
        return ["info" => $user, "data" => $posts];


        // return $posts;
    }

    public function show(Request $request, $id)
    {
        $posts = Post::join('users', 'users.id', '=', 'posts.user_id')->select('posts.*', 'users.firstname', 'users.lastname', 'users.username', 'users.email', 'users.profile_image')->with('recipe')->where('posts.id', $request->route()->parameter('post_id'))->get();
        $file = Storage::url('public/posts/' . $posts[0]['post_image']);

        $posts[0]['post_image'] = $file;

        return $posts;
    }

    public function patch(Request $request)
    {
        // return ['data' => $request->all()];
        DB::beginTransaction();
        try {

            DB::table('posts')->where(
                'id',
                $request->id
            )->update(array(
                'title' => $request->title,
                'caption' => $request->caption,
                'tag' => json_encode($request->tag),
                "updated_at" => \Carbon\Carbon::now(),
            ));

            if ($request->recipe['ingredients'] || $request->recipe['procedures']) {
                DB::table('recipes')->where(
                    'post_id',
                    $request->id
                )->update(array(
                    'duration' => $request->duration,
                    'calories' => $request->calories,
                    'servings' => $request->servings,
                    'ingredient' => json_encode($request->recipe['ingredients']),
                    'procedure' => json_encode($request->recipe['procedures']),
                    "updated_at" => \Carbon\Carbon::now(),
                ));
            }
            DB::commit();
            return ['data' => $request->all(), 'status' => 'success', 'message' => 'Post saved successfully'];
        } catch (\Exception $e) {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
            DB::rollback();
        }
    }
}
