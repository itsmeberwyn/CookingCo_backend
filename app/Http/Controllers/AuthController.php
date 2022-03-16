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
    public function index(Request $request)
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
            return response([
                'message' => "Bad credentials"
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;


        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 200);
    }

    // register
    public function store(Request $request)
    {
        $fields = $request->validate([
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'username' => ['required', 'string'],
            'email' => ['required', 'string', 'unique:users', 'email'],
            'password' => ['required', 'string', 'confirmed']
        ]);

        $user = User::create([
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        $token = $user->createToken('cookingcousertoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
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
}
