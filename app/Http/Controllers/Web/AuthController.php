<?php

namespace App\Http\Controllers\Web;

use App\Dto\Auth\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function loginPage()
    {
        return '
<form method="post">
    <input type="text" name="email" placeholder="Email">
    <input type="text" name="password" placeholder="Password">
    <input type="submit" value="Send">
</form>';
    }

    public function login(LoginRequest $request)
    {
        if ($this->authService->login(new LoginData($request->only(['email', 'password'])))) {
            session()->flash(
                'email',
                'The provided credentials do not match our records.'
            );
        }

        return redirect('user/cabinet');
        //return back();
    }

    public function logout()
    {
        $this->authService->logout();
        return true;
    }

    public function cabinet()
    {
        return 'Cabinet';
    }
}
