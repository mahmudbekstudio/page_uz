<?php

namespace App\Repositories\Traits;

use Illuminate\Database\Eloquent\Model;

trait GetById
{
    /**
     * @param int $id
     * @return Model|null
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
