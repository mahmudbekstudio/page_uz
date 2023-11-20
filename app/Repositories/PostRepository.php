<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\PostMeta;
use App\Repositories\Traits\Vars;
use Illuminate\Support\Arr;

class PostRepository extends BaseRepository {

    use Vars;

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

    /**
     * @param int $id
     * @return Post|null
     */
    public function getById(int $id)
    {
        $result = $this->getVar($id);

        if (!$result) {
            $result = $this->find($id);
            $this->setVar($id, $result);
        }

        return $result;
    }
}
