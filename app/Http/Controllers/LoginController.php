<?php

/*
 * The Lcobucci\JWT\ library doesn't work in Laravel 9
 * This controller isn't called in anywhere
 * Just ignore this file while the library is updating
 * A "Laravel way" library is used to handle authentication in AuthController: JwtToken
 * You can find the JwtToken documentation in https://laravel-jwt-auth.readthedocs.io/en/latest/
 *
 * */





namespace App\Http\Controllers;

use App\Models\JwtToken;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\InMemory;

class LoginController extends Controller
{

    /**
     * Handle an authentication attempt.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = User::where('id',auth()->id())->get();
            $token = $this->token($user);
            session(['jwt_token' => $token->toString()]);
            return response()->json($token, 200);
        }

        return response()->json(['Unauthorized' => 'Credentials are wrong!'], 401);
    }

    public function logout(Request $request)
    {
        $token = JwtToken::where('token_title', session()->get('jwt_token'))->get();
        $token->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return response()->json(['logout' => 'Ok'], 200);
    }

    public function newToken(User $user){
        $config = Configuration::forAsymmetricSigner(
            new Signer\Rsa\Sha256(),
            InMemory::plainText(env('JWT_SECRET', 'yMjckoQO1hPPeiSBq2FaEeA0DKdhynrGZKeC5bldL4xAAzuiL8WmqLfMAmQ74dqM')),
            InMemory::base64Encoded('mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=')
        );

        $token = $config->builder()
            ->issuedBy(env('APP_URL', 'http://127.0.0.1/'))
            ->issuedAt(CarbonImmutable::now())
            ->canOnlyBeUsedAfter(CarbonImmutable::now()->subMinutes(5))
            ->expiresAt(CarbonImmutable::now()->modify('+1 hour'))
            ->withClaim('user_uuid', $user->uuid)
            ->getToken($config->signer(), $config->signingKey());;
        $jwt = new JwtToken([
            'user_id' => $user->id,
            'unique_id' => md5($token->toString()),
            'token_title' => $token->toString(),
            'expires_at' => $token->claims()->get('exp'),
            'last_used_at' => CarbonImmutable::now(),
            'refreshed_at' => CarbonImmutable::now(),
        ]);
        $jwt->save();


        return $token;
    }

    public function forgotPass(Request $request)
    {
        $email = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $email)->get();
        $token = $this->token($user)->toString();
        return response()->json(['jwt_token' => $token], 200);
    }

    public function resetPass(Request $request)
    {
        $email = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'token' => ['required', 'string'],
        ]);

        $user = User::where('email', $email)->get();
        $token = JwtToken::where('token_title', $request->token)->get();

        if($token->user_id === $user->id){
            $user->update([
                'password' => $request->password,
            ]);
        }
        return response()->json(['user' => $user], 200);
    }
}
