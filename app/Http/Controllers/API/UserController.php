<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(AuthRequest $authRequest)
    {
        $user = new User();
        $user->first_name = $authRequest->first_name;
        $user->last_name = $authRequest->first_name;
        $user->email = $authRequest->email;
        $user->password = bcrypt($authRequest->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'registration successful',
            'data' => new AuthResource($user),
            'token' => $this->jwt($user),
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $loginRequest)
    {
        $userDetails = $loginRequest->only('email', 'password');
        $foundUser = User::where('email', $userDetails['email'])->first();

        if (!$foundUser || !Hash::check($userDetails['password'], $foundUser->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'success' => true,
            'message' => 'login successful',
            'token' => $this->jwt($foundUser),
            'data' => new AuthResource($foundUser),
        ], Response::HTTP_OK);
    }

    /**
    * Create a token
    *
    * @param \App\User $user
    * @return string
    */
    protected function jwt(User $user) {
       $payload = [
           'iss' => "jwt",
           'sub' => $user->id,
           'iat' => time(),
           'exp' => time() + 60 * 60 * 24
       ];
       return JWT::encode($payload, env('JWT_SECRET'));
    }
}
