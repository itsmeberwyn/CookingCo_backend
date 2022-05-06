<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reported_user;
use Illuminate\Http\Request;

class ReportUsersController extends Controller
{
    public function index(Request $request)
    {
        $reported_users = Reported_user::join('users', 'users.id', '=', 'reported_users.user_id')->select('reported_users.*', 'users.firstname', 'users.lastname', 'users.username', 'users.email', 'users.profile_image')->paginate(5);


        return view('userreport', compact('reported_users'));
    }
}
