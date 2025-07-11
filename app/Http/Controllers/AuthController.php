<?php

namespace App\Http\Controllers;


use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        Log::info('Register request received:', ['data' => $request->all()]);

        $input = $request->validated();

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
