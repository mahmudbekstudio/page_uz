<?php

namespace App\Http\Controllers\Admin;

use App\Dto\Admin\User\ProfileData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\ProfileRequest;
use App\Services\Admin\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getProfile()
    {
        $profile = $this->userService->getProfile();
        return responseJsonData(!empty($profile), $profile);
    }

    public function updateProfile(ProfileRequest $request)
    {
        $profileData = new ProfileData($request->only(['first_name', 'last_name', 'password', 'old_password']));
        return $this->userService->updateProfile($profileData) ?
            $this->getProfile() :
            responseJsonMessage(false, __('validation.old_password_incorrect'));
    }
}
