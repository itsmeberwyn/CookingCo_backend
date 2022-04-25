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

        $search = $request->get('tag');
        // dd($search);
        // $posts = Post::query()->whereJsonContains('tag', "%$search%")->orWhereJsonContains('caption', "%$search%")->get();
        // $posts = Post::whereRaw('JSON_CONTAINS(tag->tag, "meryenda")')->get();
        // $posts = Post::whereRaw("JSON_CONTAINS(JSON_EXTRACT(tag, '$.tags'), '\"meryenda\"')")->get();

        // NOTE: working
        $posts = Post::query()
            ->whereJsonContains('tag', $search)->join('users', 'users.id', '=', 'posts.user_id')->select('posts.*', 'users.firstname', 'users.lastname')
            ->get();

        foreach ($posts as $key => $post) {
            $file = Storage::url('public/posts/' . $post->post_image);

            $post['post_image'] = $file;
        }

        return ['data' => $posts];
    }

    public function popularPost(Request $request)
    {

        // NOTE: you set strict to false to avoid warnings from config/databasephp
        $posts = DB::select("
        SELECT name, image, count(*) as count
            FROM
            (
            SELECT JSON_EXTRACT(tag, '$[0]') as name, post_image as image FROM posts UNION ALL
            SELECT JSON_EXTRACT(tag, '$[1]') as name, post_image as image FROM posts UNION ALL
            SELECT JSON_EXTRACT(tag, '$[2]') as name, post_image as image FROM posts 
            ) q
            WHERE name <> 'null'
            GROUP BY name ORDER BY name DESC limit 5
        ");

        foreach ($posts as $key => $post) {
            $file = Storage::url('public/posts/' . $post->image);

            $post->image = $file;
        }

        return $posts;
    }

    public function getRandomRecipe(Request $request)
    {
        // $posts = Post::where('user_id', $request->user()->id)->with('recipe')->skip($request->skip)->take(6)->orderBy('created_at', 'desc')->get();
        $posts = Post::join('recipes', 'recipes.post_id', '=', 'posts.id')->join('users', 'users.id', '=', 'posts.user_id')->select('recipes.*', 'posts.*', 'users.firstname', 'users.lastname')->inRandomOrder()->take(5)->get();

        foreach ($posts as $key => $post) {
            $file = Storage::url('public/posts/' . $post->post_image);

            $post['post_image'] = $file;
        }


        return ["data" => $posts];


        // return $posts;
    }
}
