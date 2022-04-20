<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::join('users', 'users.id', '=', 'posts.user_id')->select('posts.*', 'users.firstname', 'users.lastname', 'users.username', 'users.email', 'users.profile_image')->with('recipe')->skip($request->get('skip'))->take(6)->inRandomOrder()->get();

        foreach ($posts as $key => $post) {
            $file = Storage::url('public/posts/' . $post->post_image);

            $post['post_image'] = $file;
        }

        return $posts;
    }

    public function search(Request $request)
    {
        // $posts = Post::whereHas('posts', function (Builder $query) {
        //     $query->where('tag', 'like', 'meryenda' . '%');
        // })->get();

        $search = $request->tag;
        // dd($search);
        // $posts = Post::query()->whereJsonContains('tag', "%$search%")->orWhereJsonContains('caption', "%$search%")->get();
        // $posts = Post::whereRaw('JSON_CONTAINS(tag->tag, "meryenda")')->get();
        // $posts = Post::whereRaw("JSON_CONTAINS(JSON_EXTRACT(tag, '$.tags'), '\"meryenda\"')")->get();
        $posts = Post::query()
            ->whereJsonContains('tag', $search)
            ->get();
        return $posts;
    }
}
