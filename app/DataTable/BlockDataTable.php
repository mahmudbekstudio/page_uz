<?php

namespace App\DataTable;

use App\Criteria\Block\SetTypeIdCriteria;

use App\Criteria\Block\AddTitleCriteria;
use App\Repositories\BlockRepository;

class BlockDataTable extends DataTable
{
    protected string $repositoryClass = BlockRepository::class;
    protected string $sortBy = 'created_at';
    protected bool $sortDesc = true;
    protected array $columns = [
        'blocks.id' => 'id',
        'blocks.status' => 'status',
        'blocks.created_at' => 'created_at',
        'block_title.meta_value' => 'title',
    ];
    protected $typeId;

    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
        return $this;
    }

    protected function handle()
    {
        return $this
            ->repository
            ->pushCriteria(new SetTypeIdCriteria($this->typeId))
            ->pushCriteria(AddTitleCriteria::class);
    }
}
