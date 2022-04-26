<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        // paginate, pwedeng show more...
        // $comments = Comment::join('users', 'users.id', '=', 'posts.user_id')->select('posts.*', 'users.firstname', 'users.lastname', 'users.username', 'users.email', 'users.profile_image')->where('posts.id', $request->route()->parameter('post_id'))->get();

        // return $request->post_id;
        $comments = Comment::join('users', 'users.id', '=', 'comments.user_id')->select('comments.*', 'users.firstname', 'users.lastname', 'users.profile_image', 'users.username')->where('comments.post_id', $request->get('post_id'))->skip($request->get('skip'))->take(6)->orderBy('created_at', 'desc')->get();
        return $comments;
    }

    public function store(Request $request)
    {
        // dd($request->user()->id, $request->post_id, $request->message);
        $comment = new Comment;

        $this->validate($request, [
            'post_id' => ['required'],
            'message' => ['required', 'max:255'],
        ]);

        $comment->user_id = $request->user()->id;
        $comment->post_id = $request->post_id;
        $comment->message = $request->message;

        if ($comment->save()) {
            return ['status' => 'success', 'message' => 'Comment saved successfully'];
        } else {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
        }
    }

    public function update(Request $request)
    {
        try {
            // dd($request->message);
            // maglagay ka ng logic sa fontend na kung saan magaappear lang si edit sa mga comment ni user (currently login user)
            // verify mo nalang sa ui kung may changes na ginawa si user, wag mo na papuntahin dito pag walang changes
            Comment::where('id', $request->route()->parameter('id'))->where('user_id', $request->user()->id)->update(['message' => $request->message]);

            return response()->json(['status' => 'success', 'message' => 'Update successfully']);
        } catch (QueryException $e) {
            return response()->json(['status' => 'Failed', 'message' => 'Something went wrong']);
        }
    }

    public function remove(Request $request)
    {
        try {
            Comment::where('id', $request->route()->parameter('id'))->where('user_id', $request->user()->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
        } catch (QueryException $e) {
            return response()->json(['status' => 'Failed', 'message' => 'Something went wrong']);
        }
    }
}
