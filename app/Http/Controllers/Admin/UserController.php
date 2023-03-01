<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\UsersDataTable;
use App\Dto\Admin\User\CreateData;
use App\Dto\Admin\User\ProfileData;
use App\Dto\Admin\User\UpdateData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\CreateUserRequest;
use App\Http\Requests\Admin\User\ProfileRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\JsonResponse;

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

    public function list(UsersDataTable $usersDataTable)
    {
        return responseJsonData(true, $usersDataTable->toArray());
    }

    public function byId(int $id)
    {
        return responseJsonData(true, getUserData($id));
    }

    public function create(CreateUserRequest $request): JsonResponse
    {
        $createUserData = new CreateData($request->only(['email', 'first_name', 'last_name', 'password', 'role', 'status']));
        $user = $this->userService->create($createUserData);
        return responseJsonData(!!$user, $user ? $user->toArray() : []);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $updateUserData = new UpdateData($request->only(['first_name', 'last_name', 'password', 'role', 'status']));
        $result = $this->userService->update($user, $updateUserData);
        return responseJson($result);
    }

    public function delete(User $user)
    {
        $result = $this->userService->delete($user);
        return responseJsonData($result, $result ? $user->toArray() : []);
    }
}
