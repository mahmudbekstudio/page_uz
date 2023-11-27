<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\PostMeta;
use App\Repositories\Traits\GetById;
use App\Repositories\Traits\Vars;
use Illuminate\Support\Arr;

class PostRepository extends BaseRepository {

    use Vars, GetById;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Post::class;
    }

    public function getActiveList($typeId, $except = [])
    {
        return $this
            ->with('metas')
            ->whereNotIn('id', $except)
            ->where('type_id', $typeId)
            ->where('status', 1)
            ->get(['id', 'parent_id'])
            ->map(function (Post $post) {
                $metas = $post->metas->mapWithKeys(function (PostMeta $meta) {
                    return [$meta->meta_key => $meta->meta_value];
                })->toArray();

                return ['id' => $post->id, 'parent_id' => $post->parent_id, 'name'=> Arr::get($metas, 'title')];
            });
    }

    public function getByType(int $typeId)
    {
        return $this
            ->where('type_id', $typeId)
            ->get();
    }
}
