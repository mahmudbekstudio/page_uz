<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\Traits\GetById;
use App\Repositories\Traits\Vars;

class SettingRepository extends BaseRepository {

    use Vars, GetById;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Setting::class;
    }

    public function getByType(int $typeId)
    {
        return $this
            ->where('type_id', $typeId)
            ->get();
    }
}
