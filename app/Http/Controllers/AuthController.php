<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'c_password' => 'required|same:password',
            'phone_number' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token = $user->createToken('apiToken')->plainTextToken;

        $success = [
            'user' => $user,
            'token' => $token
        ];


        return $this->sendResponse($success, 'User registered successfully.');
    }


    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $token = $user->createToken('apiToken')->plainTextToken;
            $success = [
                'user' => $user,
                'token' => $token
            ];

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorized.', ['error'=>'Unauthorized']);
        }
    }


    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        // Revoke the current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }


    public function refresh(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        // Example of generating a new token
        $newToken = $user->createToken('new-token')->plainTextToken;
        $success = [
            'user' => $user,
            'token' => $newToken
        ];
        return $this->sendResponse($success, 'Token refreshed successfully.');


    }



}
