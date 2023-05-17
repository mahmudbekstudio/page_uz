<?php

namespace App\Repositories;

use App\Models\TypeRouteStructure;
use App\Repositories\Traits\Vars;

class TypeRouteStructureRepository extends BaseRepository {

    use Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return TypeRouteStructure::class;
    }

    public function getItem($typeId, $parentId): TypeRouteStructure|null
    {
        $result = $this->getVar($typeId . '_' . $parentId);

        if (!$result) {
            $result = $this->findWhere(['type_id' => $typeId, 'parent_id' => $parentId])->first();
            $this->setVar($typeId . '_' . $parentId, $result);
        }

        return $result;
    }
}
