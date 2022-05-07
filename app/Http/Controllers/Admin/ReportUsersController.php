<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban_user;
use App\Models\Reported_user;
use App\Models\Warn_user;
use Illuminate\Http\Request;

class ReportUsersController extends Controller
{
    public function index(Request $request)
    {
        $reported_users = Reported_user::join('users', 'users.id', '=', 'reported_users.user_id')->select('reported_users.*', 'users.firstname', 'users.lastname', 'users.username', 'users.email', 'users.profile_image')->orderBy('noreports', 'desc')->paginate(5);
        // , 'warn_users.deleted_at AS isWarned'

        foreach ($reported_users as $user) {
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

        // return ($reported_users);


        return view('userreport', compact('reported_users'));
    }
}
