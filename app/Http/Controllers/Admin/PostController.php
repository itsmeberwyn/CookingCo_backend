<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $post = Post::join('users', 'users.id', '=', 'posts.user_id')->select('posts.*', 'users.firstname', 'users.lastname', 'users.username', 'users.email', 'users.profile_image', 'users.provider_id')->with('recipe')->where('posts.id', $request->route()->parameter('post_id'))->get();

        $time = Carbon::createFromTimeStamp(strtotime($post[0]->created_at))->diffForHumans();

        $comments = Comment::join('users', 'users.id', '=', 'comments.user_id')->select('comments.*', 'users.firstname', 'users.lastname', 'users.profile_image', 'users.username')->where('comments.post_id', $request->route()->parameter('post_id'))->orderBy('created_at', 'desc')->get();

        if ($post[0]->recipe !== null) {
            $ingredients = json_decode($post[0]->recipe->ingredient);
            $procedures = json_decode($post[0]->recipe->procedure);
            return view('post', compact('post', 'time', 'comments', 'ingredients', 'procedures'));
        }

        // dd($post);
        return view('post', compact('post', 'time', 'comments'));
    }
}

// "id" => 8
// "user_id" => 2
// "post_id" => 28
// "message" => "eeeeqwe"
// "created_at" => "2022-04-13 08:36:20"
// "updated_at" => "2022-04-13 08:36:20"
// "deleted_at" => null
// "firstname" => "Romaine"
// "lastname" => "Schiller"
// "profile_image" => "default-img.webp"
// "username" => "carolanne.schmitt"


// "id" => 4
// "post_id" => 5
// "duration" => "10 mins"
// "calories" => "123"
// "servings" => "3"
// "procedure" => "[{"procedure":"test test"}]"
// "ingredient" => "[{"ingredient":"test"}]"
// "created_at" => "2022-04-07 10:14:12"
// "updated_at" => "2022-04-07 10:14:12"