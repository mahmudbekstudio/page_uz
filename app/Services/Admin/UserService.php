<?php
namespace App\Services\Admin;

use App\Dto\Admin\User\CreateData;
use App\Dto\Admin\User\ProfileData;
use App\Dto\Admin\User\UpdateData;
use App\Helpers\DataFormat;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
        ], [
            'meta_value' => $data->first_name
        ]);
        $authedUser->metas()->updateOrCreate([
            'meta_key' => 'last_name',
        ], [
            'meta_value' => $data->last_name
        ]);

        return true;
    }

    public function create(CreateData $data): User
    {
        /** @var User $user */
        $user = $this->userRepository->create(Arr::only($data->toArray(), ['email', 'password', 'status']));
        $user->assignRole($data->role);
        $user->metas()->create([
            'meta_key' => 'first_name',
            'meta_value' => $data->first_name,
            'meta_format' => DataFormat::FORMAT_STRING,
        ]);
        $user->metas()->create([
            'meta_key' => 'last_name',
            'meta_value' => $data->last_name,
            'meta_format' => DataFormat::FORMAT_STRING,
        ]);
        return $user;
    }

    public static function createDefaultRoles()
    {
        foreach(User::getRoles() as $role) {
            Role::findOrCreate($role, User::GUARD_NAME);
        }
    }

    public function delete(User $user): bool
    {
        $user->delete();
        return true;
    }

    public function update(User $user, UpdateData $data): bool
    {
        $user->status = $data->status;

        if ($data->password) {
            $user->password = $data->password;
        }

        $user->save();
        $user->metas()->updateOrCreate([
            'meta_key' => 'first_name',
        ], [
            'meta_value' => $data->first_name
        ]);
        $user->metas()->updateOrCreate([
            'meta_key' => 'last_name',
        ], [
            'meta_value' => $data->last_name
        ]);

        $user->syncRoles($data->role);

        return true;
    }
}
