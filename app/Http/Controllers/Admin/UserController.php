<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = Post::doesnthave('recipe')->where('user_id', $request->route()->parameter('user_id'))->orderBy('created_at', 'desc')->get();
        $datarecipe = Post::join('recipes', 'recipes.post_id', '=', 'posts.id')->where('user_id', $request->route()->parameter('user_id'))->get();

        $posts = collect($data)->chunk(3);
        $recipes = collect($datarecipe)->chunk(3);

        $totalPost = Post::where('user_id', $request->route()->parameter('user_id'))->count();
        $user = User::find($request->route()->parameter('user_id'));

        return view('user', compact('posts', 'user', 'totalPost', 'recipes'));
    }
}
