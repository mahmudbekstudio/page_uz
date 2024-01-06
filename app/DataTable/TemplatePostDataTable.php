<?php

namespace App\DataTable;

use App\Criteria\Template\TypeTemplateCriteria;
use App\Models\Template;

class TemplatePostDataTable extends TemplateDataTable
{
    protected function handle()
    {
        return $this->repository->pushCriteria(new TypeTemplateCriteria(Template::TYPE_POST));
    }
}
