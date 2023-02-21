<?php
namespace App\Services;

use App\Dto\Auth\LoginData;
use App\Models\User;
use App\Repositories\WebsiteRepository;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;

class AuthService extends BaseService
{
    private $remember = false;
    private $guard = null;

    public function setRemember($remember)
    {
        $this->remember = !!$remember;
    }

    public function setGuard($guard)
    {
        $this->guard = $guard;
    }

    public function getAuth($guard = null)
    {
        $guard = $guard ?? $this->guard;
        return auth($guard);
    }

    public function login(LoginData $data, $remember = null, $guard = null)
    {
        $remember = $remember ?? $this->remember;
        return $this->getAuth($guard)->attempt($data->toArray(), $remember);
    }

    public function loginByUser(User $user, $remember = null, $guard = null)
    {
        $remember = $remember ?? $this->remember;
        return $this->getAuth($guard)->login($user, $remember);
    }

    public function loginById(int $userId, $remember = null, $guard = null)
    {
        $remember = $remember ?? $this->remember;
        return $this->getAuth($guard)->loginUsingId($userId, $remember);
    }

    public function user($guard = null)
    {
        return $this->getAuth($guard)->user();
    }

    public function check($guard = null)
    {
        return $this->getAuth($guard)->check();
    }

    public function logout($guard = null)
    {
        $this->getAuth($guard)->logout();
    }

    public function refreshToken($guard = null)
    {
        return $this->getAuth($guard)->refresh();
    }

    public function tokenById(int $userId, $guard = null)
    {
        return $this->getAuth($guard)->tokenById($userId);
    }

    public function payload($guard = null)
    {
        return $this->getAuth($guard)->payload();
    }

    public function expiresIn($guard = null)
    {
        return $this->getAuth($guard)->factory()->getTTL();
    }

    public function refreshExpiresIn()
    {
        return config('jwt.refresh_ttl');
    }

    public function tokenResponse(string $token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->expiresIn(),
            'refresh_expires_in' => $this->refreshExpiresIn(),
            'token_created' => now()
        ];
    }

    public function forgotPassword(array $credentials, $resetRouteName = 'password.reset')
    {
        ResetPassword::$toMailCallback = function ($notifiable, $token) use ($resetRouteName) {
            $url = url(route($resetRouteName, [
                'token' => $token,
                'email' => $notifiable->email,
            ], false));

            return (new MailMessage)
                ->from('noreply@' . app()->domain(), WebsiteRepository::getInstance()->getMetaValue('name'))
                ->subject(Lang::get('Reset Password Notification'))
                ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
                ->action(Lang::get('Reset Password'), $url)
                ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
                ->line(Lang::get('If you did not request a password reset, no further action is required.'));
        };
        return Password::sendResetLink($credentials);
    }
}