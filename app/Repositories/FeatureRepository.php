<?php

namespace App\Repositories;

use App\Models\Feature;
use App\Repositories\Traits\GetById;
use App\Repositories\Traits\Vars;

class FeatureRepository extends BaseRepository {

    use Vars, GetById;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Feature::class;
    }
}
