<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban_user;
use App\Models\Reported_comment;
use App\Models\Warn_user;
use Illuminate\Http\Request;

class ReportCommentsController extends Controller
{
    public function index(Request $request)
    {
        $reported_comments = Reported_comment::join('users', 'users.id', '=', 'reported_comments.user_id')->join('comments', 'comments.id', '=', 'reported_comments.comment_id')->select('reported_comments.*', 'users.firstname', 'users.lastname', 'users.profile_image', 'comments.post_id', 'comments.message')->paginate(5);


        foreach ($reported_comments as $user) {
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

        return view('commentreport', compact('reported_comments'));
    }
}
