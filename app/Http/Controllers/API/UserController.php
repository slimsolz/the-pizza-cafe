<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\ProfileResponse;
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
        $user->last_name = $authRequest->last_name;
        $user->email = $authRequest->email;
        $user->password = bcrypt($authRequest->password);
        $user->isAdmin = false;
        $user->address = $authRequest->address;
        $user->phone_number = $authRequest->phone_number;
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

    public function updateProfile(Request $request, ProfileRequest $profileRequest)
    {
        $user = User::find($request->auth->id);
        $user->address = $profileRequest->address;
        $user->phone_number = $profileRequest->phone_number;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'profile updated successful',
            'data' => new ProfileResponse($user),
        ], Response::HTTP_OK);
    }

    public function getProfile(Request $request)
    {
        return new ProfileResponse($request->auth);
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
