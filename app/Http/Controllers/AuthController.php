<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Oauth\PasswordGrantClient;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(PasswordGrantClient $client, Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|exists:users',
            'password' => 'required|min:6',
        ]);

        $response = $client->getTokens($request->email, $request->password);

        if (isset($response['error_code'])) {
            return response()->json(['message' => $response['error_message']], $response['error_code']);
        }

        return response()->json([
            'user'          => User::where('email', $request->email)->first(),
            'access_token'  => $response['access_token'],
            'refresh_token' => $response['refresh_token'],
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $token = (User::create($request->all()))->createToken('User Token');

        return response()->json(['token' => $token->accessToken]);
    }
}
