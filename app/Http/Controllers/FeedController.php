<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with('recipe')->skip($request->skip)->take(3)->inRandomOrder()->get();

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
