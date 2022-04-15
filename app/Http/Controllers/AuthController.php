<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    // getting user using token
    public function test(Request $request)
    {
        return $request->bearerToken();
        return Auth::user();
    }

    public function __construct()
    {
        $this->middleware('auth:sanctum', [
            'only' => [
                'destroy'
            ]
        ]);
    }

    // login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response(['error' => $request->all()], 401);
        }

        $user = User::where('email',  $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => "Bad credentials",
                'status' => 401
            ];
        }

        $token = $user->createToken('cookingcousertoken')->plainTextToken;


        $response = [
            'user' => $user,
            'token' => $token,
            'status' => 200
        ];

        return $response;
    }

    // register
    public function register(Request $request)
    {
        // $fields = $request->validate([
        //     'firstname' => ['required', 'string'],
        //     'lastname' => ['required', 'string'],
        //     'username' => ['required', 'string'],
        //     'email' => ['required', 'string', 'unique:users', 'email'],
        //     'password' => ['required', 'string', 'confirmed']
        // ]);

        $fields = Validator::make($request->all(), [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'username' => ['required', 'string'],
            'email' => ['required', 'string', 'unique:users', 'email'],
            'password' => ['required', 'string', 'confirmed']
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('cookingcousertoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'status' => 200
        ];

        return $response;
    }

    // logout
    public function destroy(Request $request)
    {
        try {

            if ($request->route()->parameter('auth') != $request->user()->id) {
                return [
                    'message' => "Something went wrong with your request",
                ];
            }

            $request->user()->tokens()->delete();

            return [
                'message' => "Logged out",
            ];
        } catch (Exception $e) {
            return [
                'message' => "Something went wrong",
            ];
        }
    }

    // NOTE: handling Google and Facebook Auth

    // google login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // google callback
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            // dd($user->user);
            $this->registerOrLoginUser($user, 'google');
        } catch (Exception $e) {
            dd($e);
            // dd('error, try again, throw to frontend and redirect to login');
        }
    }

    // facebook login
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // facebook callback
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
        // dd(explode(' ', $user->user['name'])[0]);
        $this->registerOrLoginUser($user, 'facebook');
    }

    public function registerOrLoginUser($data, $type)
    {
        $user = User::where('email',  $data->email)->first();
        if (!$user) {
            $user = new User;
            $user->firstname = $type == 'facebook' ? explode(' ', $data->user['name'])[0] : $data->user['given_name'];
            $user->lastname = $type == 'facebook' ? explode(' ', $data->user['name'])[1] : $data->user['family_name'];
            $user->username = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->profile_image = $data->avatar;
            $user->save();
        }

        Auth::login($user);

        $token = $user->createToken('cookingcousertoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];
        // dd($response);
        return response($response, 201);
    }
}
