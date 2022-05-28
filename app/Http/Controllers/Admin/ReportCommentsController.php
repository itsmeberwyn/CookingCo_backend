<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban_user;
use App\Models\Comment;
use App\Models\HiddenContent;
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
            $isComment = Comment::withTrashed()->where('id', $user->comment_id)->first();
            // dd($isComment);

            if ($isComment->trashed()) {
                $user['isTrash'] = true;
            } else {
                $user['isTrash'] = false;
            }

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

        // dd($reported_comments);

        return view('commentreport', compact('reported_comments'));
    }

    public function create(Request $request)
    {
        $comment = Reported_comment::where('user_id', $request->user_id)->get();
        $reported_comment = new Reported_comment;


        if (blank($comment)) {
            $reported_comment->user_id = $request->user_id;
            $reported_comment->comment_id = $request->comment_id;
            $reported_comment->reason = json_encode([$request->reason]);
            $reported_comment->reported_by = json_encode([$request->user()->id]);
            $reported_comment->noreports = '1';

            if ($reported_comment->save()) {
                return ['status' => 'success', 'message' => 'Report sent successfully'];
            } else {
                return ['status' => 'Failed', 'message' => 'Something went wrong'];
            }
        } else {
            $reason = json_decode($comment[0]->reason);
            $reported_by = json_decode($comment[0]->reported_by);

            array_push($reason, $request->reason);
            array_push($reported_by, $request->user()->id);

            $comment[0]->reason = json_encode($reason);
            $comment[0]->reported_by = json_encode($reported_by);
            $comment[0]->noreports = (int)$comment[0]->noreports + 1;

            if ($comment[0]->save()) {
                return ['status' => 'success', 'message' => 'Report sent successfully'];
            } else {
                return ['status' => 'Failed', 'message' => 'Something went wrong'];
            }
        }
    }

    public function hide(Request $request)
    {
        $isComment = Comment::withTrashed()->where('id', $request->get('id'))->first();

        if ($isComment->trashed()) {
            HiddenContent::where('content_id', $request->get('id'))->where('type', 'comment')->delete();
            Comment::withTrashed()->where('id', $request->get('id'))->restore();

            return redirect()->back()->with('success', 'Comment restore successfully');
        } else {
            Comment::where('id', $request->get('id'))->delete();
            $hiddenContent = new HiddenContent;

            $hiddenContent->content_id = $request->get('id');
            $hiddenContent->type = 'comment';

            if ($hiddenContent->save()) {
                return redirect()->back()->with('success', 'Comment hide successfully');
            } else {
                return ['status' => 'Failed', 'message' => 'Something went wrong'];
            }
        }
    }
}
