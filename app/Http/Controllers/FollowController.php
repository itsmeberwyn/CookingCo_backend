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

    public function unfollow(Request $request, $following_id)
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


    public function getFollowers(Request $request, $user_id = null)
    {
        return Follow::where('following_id', $user_id ? $user_id : $request->user()->id)->join('users', 'users.id', '=', 'follows.follower_id')->select('follows.*', 'users.id AS user_id', 'users.firstname', 'users.lastname', 'users.username', 'users.email', 'users.profile_image')->get();
    }

    public function getFollowings(Request $request, $user_id = null)
    {
        return Follow::where('follower_id', $user_id ? $user_id : $request->user()->id)->join('users', 'users.id', '=', 'follows.following_id')->select('follows.*', 'users.id AS user_id', 'users.firstname', 'users.lastname', 'users.username', 'users.email', 'users.profile_image')->get();
    }

    public function countFollows(Request $request, $user_id = null)
    {
        $countFollowers = Follow::where('following_id', $user_id ? $user_id : $request->user()->id)->count();
        $countFollowings = Follow::where('follower_id', $user_id ? $user_id : $request->user()->id)->count();

        return ['followerscount' => $countFollowers, "followingscount" => $countFollowings];
    }

    public function isfollow(Request $request, $user_id)
    {
        $isfollowing = Follow::where('follower_id', $request->user()->id)->where('following_id', $user_id)->get();
        // $isfollower = Follow::where('follower_id', $user_id)->where('following_id', $request->user()->id)->count();

        return ['isfollowing' => $isfollowing];
    }
}
