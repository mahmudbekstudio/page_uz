<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\CategoryMeta;
use App\Repositories\Traits\Vars;
use Illuminate\Support\Arr;

class CategoryRepository extends BaseRepository {

    use Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    public function getActiveList($typeId, $except = [])
    {
        return $this
            ->with('metas')
            ->whereNotIn('id', $except)
            ->where('type_id', $typeId)
            ->where('status', 1)
            ->get(['id', 'parent_id'])
            ->map(function (Category $category) {
                $metas = $category->metas->mapWithKeys(function (CategoryMeta $meta) {
                    return [$meta->meta_key => $meta->meta_value];
                })->toArray();

                return ['id' => $category->id, 'parent_id' => $category->parent_id, 'name'=> Arr::get($metas, 'title')];
            });
    }

    /**
     * @param int $id
     * @return Category|null
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
