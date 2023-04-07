<?php

namespace App\Services\Admin;

use App\Helpers\DataFormat;
use App\Models\CategoryMeta;
use App\Repositories\CategoryMetaRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\RouteRepository;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class CategoryService extends BaseService
{
    private CategoryRepository $categoryRepository;
    private RouteRepository $routeRepository;
    private CategoryMetaRepository $categoryMetaRepository;

    public function __construct()
    {
        $this->categoryRepository = app(CategoryRepository::class);
        $this->routeRepository = app(RouteRepository::class);
        $this->categoryMetaRepository = app(CategoryMetaRepository::class);
    }

    public function getCategory($typeId, $categoryId)
    {
        $category = $this->categoryRepository->getById($categoryId);
        $fieldValues = getFieldValues(getTypeById($typeId)->fields);
        $result = array_merge($fieldValues, [
            'template' => $category->template_id,
            'parent' => $category->parent_id,
            'status' => $category->status,
            'routeName' => $category->route->name,
        ]);

        $category->metas->each(function (CategoryMeta $meta) use (&$result) {
            $result[$meta->meta_key] = DataFormat::toFormat($meta->meta_value, $meta->meta_format);
        });

        return $result;
    }

    public function update($typeId, $categoryId, array $fields)
    {
        $category = $this->categoryRepository->getById($categoryId);
        $category->template_id = Arr::get($fields, 'template');
        $category->parent_id = Arr::get($fields, 'parent');
        $category->status = Arr::get($fields, 'status');
        $category->save();

        $route = $category->route;
        $route->name = Arr::get($fields, 'routeName');
        $route->save();

        $metaFieldsExcept = ['template', 'parent', 'status', 'routeName'];
        $metaFields = Arr::except($fields, $metaFieldsExcept);
        $typeFields = getFields(getTypeById($typeId)->fields);
        $category->metas()->delete();
        foreach ($metaFields as $name => $value) {
            $format = Arr::get($typeFields[$name], 'params.valueType', DataFormat::getDefault());
            $categoryMetaAttributes = [
                'category_id' => $category->id,
                'meta_format' => $format,
                'meta_key' => $name,
                'meta_value' => DataFormat::toString($value, $format),
                'lang' => ''
            ];
            $this->categoryMetaRepository->create($categoryMetaAttributes);
        }

        return $category;
    }

    public function create($typeId, array $fields)
    {
        $categoryFields = ['template' => 'template_id', 'parent' => 'parent_id', 'status' => 'status'];
        $routeFields = ['routeName' => 'name'];
        $metaFieldsExcept = ['template', 'parent', 'status', 'routeName'];

        $categoryAttributes = ['type_id' => $typeId];
        foreach ($categoryFields as $key => $value) {
            $categoryAttributes[$value] = Arr::get($fields, $key);
        }
        $category = $this->categoryRepository->create($categoryAttributes);

        $routeAttributes = ['type_id' => $typeId, 'parent_id' => $category->id];
        foreach ($routeFields as $key => $value) {
            $routeAttributes[$value] = Arr::get($fields, $key);
        }
        $this->routeRepository->create($routeAttributes);

        $metaFields = Arr::except($fields, $metaFieldsExcept);
        $typeFields = getFields(getTypeById($typeId)->fields);
        foreach ($metaFields as $name => $value) {
            $format = Arr::get($typeFields[$name], 'params.valueType', DataFormat::getDefault());
            $categoryMetaAttributes = [
                'category_id' => $category->id,
                'meta_format' => $format,
                'meta_key' => $name,
                'meta_value' => DataFormat::toString($value, $format),
                'lang' => ''
            ];
            $this->categoryMetaRepository->create($categoryMetaAttributes);
        }

        return $category;
    }
}
