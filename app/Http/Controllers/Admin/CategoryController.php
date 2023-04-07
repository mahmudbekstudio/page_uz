<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CreateCategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\Admin\CategoryService;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(
        CategoryService $categoryService
    ) {
        $this->categoryService = $categoryService;
    }

    public function list(int $type, CategoryDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->setTypeId($type)->toArray());
    }

    public function create(int $type, CreateCategoryRequest $request)
    {
        $fieldNames = getFieldNames(getTypeById($type)->fields);
        $category = $this->categoryService->create($type, $request->only($fieldNames));
        return responseJsonData(true, ['category' => $category]);
    }

    public function edit(int $type, int $category, CreateCategoryRequest $request)
    {
        $fieldNames = getFieldNames(getTypeById($type)->fields);
        $categoryItem = $this->categoryService->update($type, $category, $request->only($fieldNames));
        return responseJsonData(true, ['category' => $categoryItem]);
    }

    public function get(int $type, int $category)
    {
        return responseJsonData(true, ['category' => $this->categoryService->getCategory($type, $category)]);
    }

    public function delete(int $type, Category $category)
    {
        if ($category->type_id == $type) {
            $category->route->delete();
            $category->delete();
        }

        return responseJsonData(true, ['category' => $category]);
    }

    public function activeList(int $type, CategoryRepository $categoryRepository)
    {
        return responseJsonData(true, ['categories' => $categoryRepository->getActiveList($type)]);
    }
}
