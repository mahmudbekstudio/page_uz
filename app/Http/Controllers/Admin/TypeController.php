<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\TypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Type\CreateTypeRequest;
use App\Models\Type;
use App\Repositories\TypeRepository;
use App\Services\Admin\TypeService;

class TypeController extends Controller
{
    private $typeService;

    public function __construct(TypeService $typeService)
    {
        $this->typeService = $typeService;
    }

    public function list(TypeDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->toArray());
    }

    public function create(CreateTypeRequest $request, TypeRepository $repository)
    {
        return responseJsonData(true, ['type' => $repository->create($request->all())]);
    }

    public function edit(int $id, CreateTypeRequest $request, TypeRepository $repository)
    {
        return responseJsonData(true, ['type' => $repository->update($request->all(), $id)]);
    }

    public function get(Type $type)
    {
        return responseJsonData(true, ['type' => $type]);
    }

    public function delete(Type $type)
    {
        $type->delete();
        return responseJsonData(true, ['type' => $type]);
    }

    public function getCategories(TypeRepository $repository)
    {
        return responseJsonData(true, ['list' => $repository->categories()]);
    }
}
