<?php

namespace App\Http\Controllers\Api;

use App\Dto\Auth\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Manager;
use Tymon\JWTAuth\Token;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->authService->setGuard('api');
        $this->authService->setRemember(true);
    }

    public function login(LoginRequest $request)
    {
        $token = $this->authService->login(new LoginData($request->only(['email', 'password'])));
        if (!$token) {
            return responseJsonMessage(false, [
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        return $this->getLoginResponse($token);
    }

    public function logout()
    {
        $this->authService->logout();
        return responseJson(true);
    }

    public function refreshToken(JWT $jwt, Manager $manager)
    {
        $token = $manager->refresh($jwt->getToken())->get();
        $user = auth()->loginUsingId($manager->decode(new Token($token))->get('sub'));
        return $this->getLoginResponse($token, $user);
    }

    private function getLoginResponse(string $token, $user = null)
    {
        return responseJsonData(true, $this->getLoginDataResponse($token, $user ?? $this->authService->user()));
    }

    private function getLoginDataResponse(string $token, $user): array
    {
        return [
            'token' => $this->authService->tokenResponse($token),
            'user' => getUserData($user)
        ];
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $send = $this->authService->forgotPassword(
            $request->only('email'), 'admin.auth.forgot-password'
        );

        if($send === Password::RESET_THROTTLED) {
            return responseJsonMessage(false, trans('passwords.throttled'));
        }

        return responseJsonMessage(true, trans('passwords.sent'));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
        $authUser = null;
        $status = Password::reset(
            $credentials,
            function ($user, $password) use (&$authUser) {
                $user->forceFill(['password' => $password]);
                $user->save();
                $authUser = $user;
                event(new PasswordReset($user));
            }
        );

        $data = [];
        $result = $status === Password::PASSWORD_RESET;

        if($result) {
            $token = $this->authService->loginByUser($authUser);
            $data = $this->getLoginDataResponse($token, $authUser);
        }

        $message = trans($status);

        return $result ? responseJson(true, [$message], $data) : responseJsonMessage(false, $message);
    }

    public function cabinet()
    {
        return responseJsonMessage(true, 'Cabinet');
    }
}
