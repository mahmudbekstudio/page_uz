<?php

namespace App\Repositories;

use App\Models\CategoryMeta;
use App\Repositories\Traits\Vars;

class CategoryMetaRepository extends BaseRepository {

    use Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return CategoryMeta::class;
    }
}
