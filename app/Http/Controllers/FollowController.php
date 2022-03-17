<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'following_id' => ['required'],
            ]);

            $doesfollow = Follow::where('follower_id', $request->following_id)->where('following_id', $request->user()->id)->get();

            // return sizeof($doesfollow);
            if (sizeof($doesfollow) > 0) {
                DB::table('follows')->where('follower_id', $request->following_id)->update([
                    'doesfollowback' => '1'
                ]);
            }

            DB::table('follows')->insert(array(
                'follower_id' => $request->user()->id,
                'following_id' => $request->following_id,
                'doesfollowback' => sizeof($doesfollow) > 0 ? 1 : 0,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),
            ));

            DB::commit();
            return ['status' => 'success', 'message' => 'Follow saved successfully'];
        } catch (\Exception $e) {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
            DB::rollback();
        }
    }

    public function unfollow(Request $request)
    {
        DB::beginTransaction();
        try {
            $doesfollow = Follow::where('follower_id', $request->following_id)->where('following_id', $request->user()->id)->get();

            // return sizeof($doesfollow);
            if (sizeof($doesfollow) > 0) {
                DB::table('follows')->where('follower_id', $request->following_id)->update(
                    ['doesfollowback' => '0']
                );
            }

            $unfollow = DB::table('follows')->where('follower_id', $request->user()->id)->where('following_id', $request->route()->parameter('following_id'))->delete(); //return 1 or 0

            DB::commit();

            if ($unfollow) {
                return ['status' => 'success', 'message' => 'Unfollow success'];
            } else {
                return ['status' => 'success', 'message' => 'Already unfollow'];
            }
        } catch (\Exception $e) {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
            DB::rollback();
        }
    }


    public function getFollowers(Request $request)
    {
        return Follow::where('following_id', $request->user()->id)->get();
    }

    public function getFollowings(Request $request)
    {
        return Follow::where('follower_id', $request->user()->id)->get();
    }
}
