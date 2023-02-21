<?php
namespace App\Services\Admin;

use App\Dto\Admin\User\ProfileData;
use App\Repositories\UserRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getProfile()
    {
        return getUserData();
    }

    public function updateProfile(ProfileData $data)
    {
        $authedUser = $this->userRepository->getAuthed();

        if($data->password) {
            if(Hash::check($data->old_password, $authedUser->password)) {
                $authedUser->password = $data->password;
                $authedUser->save();
            } else {
                return false;
            }
        }

        $authedUser->metas()->updateOrCreate([
            'meta_key' => 'first_name',
            'lang' => '',
        ], [
            'meta_value' => $data->first_name
        ]);
        $authedUser->metas()->updateOrCreate([
            'meta_key' => 'last_name',
            'lang' => '',
        ], [
            'meta_value' => $data->last_name
        ]);

        return true;
    }
}