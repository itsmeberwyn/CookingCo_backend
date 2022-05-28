<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $topUser = Rating::selectRaw("SUM(rate) as total_rate")
        //     ->groupBy('user_id')
        //     ->get();
        // $topUser = Rating::join('users', 'users.id', '=', 'ratings.user_id')->selectRaw("users.firstname, users.lastname, SUM(rate) as total_rate")
        //     ->groupBy('user_id')
        //     ->get();
        // $topUser = DB::table('ratings')
        //     ->join('posts', 'posts.id',  '=', 'ratings.post_id')
        //     ->join('users', 'users.id', '=', 'posts.user_id')
        //     ->selectRaw("ratings.user_id, users.firstname, users.lastname, SUM(rate) as total_rate")
        //     ->groupBy('users.id')
        //     ->get();

        // dd($topUser);
        $feedbacks = Feedback::join('users', 'users.id', '=', 'feedbacks.user_id')->select('feedbacks.*', 'users.firstname', 'users.lastname', 'users.username', 'users.email', 'users.profile_image')->paginate(5);
        return view('home', compact('feedbacks'));
    }
}
