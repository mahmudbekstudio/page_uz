<?php

namespace App\Repositories;

use App\Models\Route;
use App\Repositories\Traits\Vars;

class RouteRepository extends BaseRepository {

    use Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Route::class;
    }

    public function getByTypeAndName($typeId, $name)
    {
        $varKey = $name . '_' . $typeId;
        $route = $this->getVar($varKey);

        if (!$route) {
            $route = $this->findWhere(['type_id' => $typeId, 'name' => $name])->first();
            $this->setVar($varKey, $route);
        }

        return $route;
    }
}
