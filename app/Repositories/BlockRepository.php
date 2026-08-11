<?php

namespace App\Repositories;

use App\Models\Block;
use App\Models\BlockMeta;
use App\Repositories\Traits\GetById;
use App\Repositories\Traits\Vars;
use Illuminate\Support\Arr;

class BlockRepository extends BaseRepository {

    use Vars, GetById;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Block::class;
    }

    public function getActiveList($typeId, $except = [])
    {
        return $this
            ->with('metas')
            ->whereNotIn('id', $except)
            ->where('type_id', $typeId)
            ->where('status', 1)
            ->get(['id'])
            ->map(function (Block $block) {
                $metas = $block->metas->mapWithKeys(function (BlockMeta $meta) {
                    return [$meta->meta_key => $meta->meta_value];
                })->toArray();

                return ['id' => $block->id, 'name'=> Arr::get($metas, 'title')];
            });
    }

    public function getByType(int $typeId)
    {
        return $this
            ->where('type_id', $typeId)
            ->get();
    }
}
