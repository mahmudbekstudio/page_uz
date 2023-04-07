<?php

namespace App\Repositories;

use App\Models\PostMeta;
use App\Repositories\Traits\Vars;

class PostMetaRepository extends BaseRepository {

    use Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return PostMeta::class;
    }
}
