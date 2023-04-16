<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Repositories\Traits\Vars;

class MenuRepository extends BaseRepository {

    use Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Menu::class;
    }

    /**
     * @param int $id
     * @return Menu|null
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
