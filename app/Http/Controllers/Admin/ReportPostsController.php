<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban_user;
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
            $datawarn = Warn_user::where('user_id', $user->user_id)->first();
            $databan = Ban_user::where('user_id', $user->user_id)->first();

            if ($datawarn != null) {
                if ($datawarn->deleted_at == null) {
                    $user['isSeen'] = true;
                } else {
                    $user['isSeen'] = false;
                }
            } else {
                $user['isSeen'] = false;
            }

            if ($databan != null) {
                if ($databan->deleted_at == null) {
                    $user['isBan'] = true;
                } else {
                    $user['isBan'] = false;
                }
            } else {
                $user['isBan'] = false;
            }
        }

        return view('postreport', compact('reported_posts'));
    }
}
