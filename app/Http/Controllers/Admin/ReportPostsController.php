<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban_user;
use App\Models\Post;
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

    public function create(Request $request)
    {
        $post = Reported_post::where('post_id', $request->post_id)->get();
        $reported_post = new Reported_post;


        if (blank($post)) {
            $reported_post->user_id = $request->user_id;
            $reported_post->post_id = $request->post_id;
            $reported_post->reason = json_encode([$request->reason]);
            $reported_post->reported_by = json_encode([$request->user()->id]);
            $reported_post->noreports = '1';

            if ($reported_post->save()) {
                return ['status' => 'success', 'message' => 'Report sent successfully'];
            } else {
                return ['status' => 'Failed', 'message' => 'Something went wrong'];
            }
        } else {

            $reason = json_decode($post[0]->reason);
            $reported_by = json_decode($post[0]->reported_by);

            array_push($reason, $request->reason);
            array_push($reported_by, $request->user()->id);

            $post[0]->reason = json_encode($reason);
            $post[0]->reported_by = json_encode($reported_by);
            $post[0]->noreports = (int)$post[0]->noreports + 1;

            if ($post[0]->save()) {
                return ['status' => 'success', 'message' => 'Report sent successfully'];
            } else {
                return ['status' => 'Failed', 'message' => 'Something went wrong'];
            }
        }
    }
}
