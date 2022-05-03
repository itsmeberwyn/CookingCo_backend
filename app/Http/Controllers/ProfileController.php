<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index()
    {
        return auth()->user();
    }

    public function patch(Request $request)
    {
        $user = User::find(auth()->user()->id);

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username =  $request->username;
        $user->bio =  $request->bio;

        if (sizeof($request->profile_image) == 2) {
            $image = $request->profile_image['base64String']; // image base64 encoded
            $compPic =  '_image' . time() . '.' . $request->profile_image['format'];
            Storage::disk('public')->put('profiles/' . $compPic, base64_decode($image));

            $image = Image::make(public_path("storage/posts/profiles/{$compPic}"))->fit(1200, 1200);
            $image->save();

            $user->profile_image = $compPic;
        }

        if ($user->provider_id !== null || $user->provider_id !== '') {
            $user->password = Hash::make($request->password);
        }

        if ($user->save()) {
            return ['data' => $user, 'status' => 'success', 'message' => 'Profile updated successfully'];
        } else {
            return ['status' => 'failed', 'message' => 'Something went wrong'];
        }
    }
}
