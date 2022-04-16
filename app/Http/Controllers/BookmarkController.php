<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        // $bookmarks = Bookmark::where('user_id', $request->user()->id)->skip($request->skip)->take(3)->orderBy('created_at', 'desc')->get();

        $bookmarks = DB::table('bookmarks')
            ->join('posts', 'posts.id', '=', 'bookmarks.post_id')
            ->leftJoin('recipes', 'posts.id', '=', 'recipes.post_id')
            ->select('bookmarks.*', 'posts.caption', 'posts.post_image', 'recipes.id AS recipe')->where('bookmarks.user_id', $request->user()->id)->skip(0)->take(3)->orderBy('created_at', 'desc')->get();
        return $bookmarks;
    }

    public function store(Request $request)
    {
        $bookmark = new Bookmark;

        $this->validate($request, [
            'post_id' => ['required'],
        ]);

        $bookmark->user_id = $request->user()->id;
        $bookmark->post_id = $request->post_id;

        if ($bookmark->save()) {
            return ['status' => 'success', 'message' => 'Bookmark saved successfully'];
        } else {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
        }
    }

    public function destroy(Request $request)
    {
        $unbookmark = DB::table('bookmarks')->where('user_id', $request->user()->id)->where('post_id', $request->post_id)->delete(); //return 1 or 0

        if ($unbookmark) {
            return ['status' => 'success', 'message' => 'Remove bookmark successfully'];
        } else {
            return ['status' => 'failed', 'message' => 'Something went wrong'];
        }
    }

    public function checkUserBookmark(Request $request, $post_id)
    {
        $checkbookmark = DB::table('bookmarks')->where('user_id', $request->user()->id)->where('post_id', $post_id)->count();

        if ($checkbookmark > 0) {
            return ['status' => 'success', 'data' => $checkbookmark];
        } else {
            return ['status' => 'failed', 'message' => 'Something went wrong'];
        }
    }
}
