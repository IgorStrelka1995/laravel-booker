<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($data)) {
            return response()->json([
                'message' => 'User with this credentials did not found.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $data['email'])->first();

        return response()->json(['access_token' => $user->createToken('api_token')->plainTextToken]);
    }
}
