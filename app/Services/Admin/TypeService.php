<?php
namespace App\Services\Admin;

use     App\Repositories\TypeRepository;
use App\Services\BaseService;

class TypeService extends BaseService
{
    private TypeRepository $typeRepository;

    public function __construct(TypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }
}
