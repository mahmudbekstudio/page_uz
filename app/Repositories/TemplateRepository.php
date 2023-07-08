<?php

namespace App\Repositories;

use App\Models\Template;
use App\Repositories\Traits\Vars;

class TemplateRepository extends BaseRepository {

    use Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Template::class;
    }

    /**
     * @param int $id
     * @return Template|null
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
