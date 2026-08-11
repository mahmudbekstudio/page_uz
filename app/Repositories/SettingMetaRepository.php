<?php

namespace App\Repositories;

use App\Models\SettingMeta;
use App\Repositories\Traits\Vars;

class SettingMetaRepository extends BaseRepository {

    use Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return SettingMeta::class;
    }
}
