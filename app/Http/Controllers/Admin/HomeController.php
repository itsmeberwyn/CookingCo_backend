<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

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
        $feedbacks = Feedback::join('users', 'users.id', '=', 'feedbacks.user_id')->select('feedbacks.*', 'users.firstname', 'users.lastname', 'users.username', 'users.email', 'users.profile_image')->paginate(5);
        return view('home', compact('feedbacks'));
    }
}
