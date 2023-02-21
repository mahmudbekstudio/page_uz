<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuthService;

class MainController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index()
    {
        //
    }

    public function settings()
    {
        return responseJsonData(true, [
            'user' => getUserData(auth()->user())
        ]);
    }
}
