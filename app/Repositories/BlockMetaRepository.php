<?php

namespace App\Repositories;

use App\Models\BlockMeta;
use App\Repositories\Traits\Vars;

class BlockMetaRepository extends BaseRepository {

    use Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return BlockMeta::class;
    }
}
