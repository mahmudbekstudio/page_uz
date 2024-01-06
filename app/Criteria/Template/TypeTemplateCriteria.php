<?php

namespace App\Criteria\Template;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class TypeTemplateCriteria.
 *
 * @package namespace App\Criteria\Template;
 */
class TypeTemplateCriteria implements CriteriaInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('templates.type', '=', $this->type);
    }
}
