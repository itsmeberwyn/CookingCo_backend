<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reported_post;
use App\Models\Reported_user;
use Illuminate\Http\Request;

class ReportPostsController extends Controller
{
    public function index(Request $request)
    {
        $reported_posts = Reported_post::join('users', 'users.id', '=', 'reported_posts.user_id')->join('posts', 'posts.id', '=', 'reported_posts.post_id')->select('reported_posts.*', 'users.firstname', 'users.lastname', 'users.profile_image', 'posts.title', 'posts.caption')->paginate(5);

        return view('postreport', compact('reported_posts'));
    }
}
