<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        return auth()->user();
    }

    public function patch(Request $request)
    {
        $user = User::find(auth()->user()->id);

        $user->email;
        $user->firstname;
        $user->lastname;
        $user->username;

        if (sizeof($request->profile_image) == 2) {
            $image = $request->profile_image['base64String']; // image base64 encoded
            $compPic =  '_image' . time() . '.' . $request->profile_image['format'];
            Storage::disk('public')->put($compPic, base64_decode($image));

            $user->profile_image = $compPic;
        }

        if ($user->provider_id !== null || $user->provider_id !== '') {
            $user->password = $request->password;
        }

        if ($user->save()) {
            return ['status' => 'success', 'message' => 'Profile updated successfully'];
        } else {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
        }
    }
}
