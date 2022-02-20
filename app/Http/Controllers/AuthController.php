<?php

namespace App\Http\Controllers;

use App\Models\JwtToken;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $token = request()->bearerToken();
        JwtToken::where('token_title',$token)->delete();
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $jwt = new JwtToken([
            'user_id' => auth()->id(),
            'unique_id' => md5(CarbonImmutable::now()),
            'token_title' => $token,
            'expires_at' => CarbonImmutable::now()->modify('+6 hours'),
            'last_used_at' => CarbonImmutable::now(),
            'refreshed_at' => CarbonImmutable::now(),
        ]);
        $jwt->save();


        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  6000
        ]);
    }

    public function forgotPass(Request $request)
    {
        $email = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $email)->first();

        $token = auth()->tokenById($user->id);
        $jwt = new JwtToken([
            'user_id' => $user->id,
            'unique_id' => md5(CarbonImmutable::now()),
            'token_title' => $token,
            'expires_at' => CarbonImmutable::now()->modify('+6 hours'),
            'last_used_at' => CarbonImmutable::now(),
            'refreshed_at' => CarbonImmutable::now(),
        ]);
        $jwt->save();


        return response()->json(['jwt_token' => $token], 200);
    }

    public function resetPass(Request $request)
    {
        $email = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password','same:password', 'string'],
            'token' => ['required', 'string'],
        ]);

        $user = User::where('email', $email)->first();
        $token = JwtToken::where('token_title', $request->token)->first();

        if($token->user_id === $user->id){
            $user->update([
                'password' => $request->password,
            ]);
        }
        return response()->json(['user' => $user], 200);
    }
}
