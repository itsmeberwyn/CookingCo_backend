<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reported_post;
use App\Models\Reported_user;
use App\Models\Warn_user;
use Illuminate\Http\Request;

class ReportPostsController extends Controller
{
    public function index(Request $request)
    {
        $reported_posts = Reported_post::join('users', 'users.id', '=', 'reported_posts.user_id')->join('posts', 'posts.id', '=', 'reported_posts.post_id')->select('reported_posts.*', 'users.firstname', 'users.lastname', 'users.profile_image', 'posts.title', 'posts.caption')->paginate(5);


        foreach ($reported_posts as $user) {
            $data = Warn_user::where('user_id', $user->user_id)->first();

            if ($data != null) {
                if ($data->deleted_at == null) {
                    $user['isSeen'] = false;
                } else {
                    $user['isSeen'] = true;
                }
            } else {
                $user['isSeen'] = true;
            }
        }

        return view('postreport', compact('reported_posts'));
    }
}
