<?php

namespace Database\Seeders;

use App\Models\Website;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\UserMeta;
use App\Helpers\DataFormat;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesList = [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_ADMIN,
            User::ROLE_MANAGER,
            User::ROLE_PUBLISHER,
            User::ROLE_USER
        ];

        $statusList = [
            User::STATUS_NOT_CONFIRMED,
            User::STATUS_ACTIVE,
            User::STATUS_BLOCKED
        ];

        foreach($rolesList as $role) {
            Role::findOrCreate($role, User::GUARD_NAME);
        }

        if(isProd()) {
            $list = [];
        } else {
            $websiteUuids = [
                Website::STATUS_NOT_CONFIRMED => 1,
                Website::STATUS_ACTIVE => 2,
                Website::STATUS_BLOCKED => 3,
                Website::STATUS_TEMPORARILY_CLOSED => 4,
                Website::STATUS_FORBIDDEN => 5,
                Website::STATUS_CLOSED => 6,
                7,
                8,
            ];
            $domainPostfix = config('app.main_website');
            $password = '123456';

            foreach ($websiteUuids as $websiteId) {
                foreach($statusList as $status) {
                    foreach($rolesList as $role) {
                        $user = User::firstOrCreate([
                            'website_id' => $websiteId,
                            'status' => $status,
                            'email' => 'test_' . $websiteId . '_' . $status . '_' . $role . '@' . $domainPostfix
                        ], [
                            'password' => $password
                        ]);

                        $user->assignRole($role);

                        UserMeta::firstOrCreate([
                            'website_id' => $websiteId,
                            'user_id' => $user->id,
                            'meta_key' => 'first_name',
                            'meta_value' => 'First Name ' . $user->id . ' ' . $websiteId . '_' . $status . '_' . $role,
                            'meta_format' => DataFormat::FORMAT_STRING,
                            'lang' => ''
                        ]);

                        UserMeta::firstOrCreate([
                            'website_id' => $websiteId,
                            'user_id' => $user->id,
                            'meta_key' => 'last_name',
                            'meta_value' => 'Last Name ' . $user->id,
                            'meta_format' => DataFormat::FORMAT_STRING,
                            'lang' => ''
                        ]);
                    }
                }
            }
        }
    }
}
