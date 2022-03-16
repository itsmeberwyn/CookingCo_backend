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
        $comments = Comment::where('post_id', $request->post_id)->skip($request->skip)->take(3)->orderBy('created_at', 'asc')->get();
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
}
