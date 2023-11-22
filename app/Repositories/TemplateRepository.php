<?php

namespace App\Repositories;

use App\Models\Template;
use App\Repositories\Traits\GetById;
use App\Repositories\Traits\Vars;

class TemplateRepository extends BaseRepository {

    use Vars, GetById;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Template::class;
    }
}
