<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Type\CreateTypeRequest;
use App\Services\Admin\TypeService;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    private $typeService;

    public function __construct(TypeService $typeService)
    {
        $this->typeService = $typeService;
    }

    public function list()
    {
        //
    }

    public function create(CreateTypeRequest $request)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function delete($id)
    {
        //
    }
}
