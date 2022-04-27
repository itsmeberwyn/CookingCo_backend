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

    public function registerOrLoginUser(Request $request)
    {
        // return ['data' => $request->all()];
        $user = User::where('email',  $request->email)->first();
        if (!$user) {
            $user = new User;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->provider_id = $request->provider_id;
            $user->profile_image = $request->profile_image;
            $user->save();
        }

        Auth::login($user);

        $token = $user->createToken('cookingcousertoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'status' => 200
        ];
        // dd($response);
        return ['data' => $response];
    }
}
