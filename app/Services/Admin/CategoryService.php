<?php

namespace App\Services\Admin;

use App\Helpers\DataFormat;
use App\Models\Category;
use App\Models\CategoryMeta;
use App\Models\TypeRouteStructure;
use App\Repositories\CategoryMetaRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\RouteRepository;
use App\Repositories\TypeRouteStructureRepository;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class CategoryService extends BaseService
{
    private CategoryRepository $categoryRepository;
    private RouteRepository $routeRepository;
    private CategoryMetaRepository $categoryMetaRepository;
    private TypeRouteStructureRepository $typeRouteStructure;

    public function __construct()
    {
        $this->categoryRepository = app(CategoryRepository::class);
        $this->routeRepository = app(RouteRepository::class);
        $this->categoryMetaRepository = app(CategoryMetaRepository::class);
        $this->typeRouteStructure = app(TypeRouteStructureRepository::class);
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
        $parent = Arr::get($fields, 'parent', 0);
        $routeName = Arr::get($fields, 'routeName', '');
        $this->updateTypeRouteStructure($category, (int)$parent, $routeName);
        $category->template_id = Arr::get($fields, 'template', 0);
        $category->parent_id = $parent;
        $category->status = Arr::get($fields, 'status', 0);
        $category->save();

        $route = $category->route;
        $route->name = $routeName;
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
            ];
            $this->categoryMetaRepository->create($categoryMetaAttributes);
        }

        return $category;
    }

    private function updateTypeRouteStructure(Category $category, $parent, $routeName)
    {
        $typeRoute = $this->typeRouteStructure->getItem($category->type_id, $category->id);
        $newParentTypeRoute = $this->typeRouteStructure->getItem($category->type_id, $parent);
        $oldParentTypeRoute = $this->typeRouteStructure->getItem($category->type_id, $category->parent_id);

        $structure = $newParentTypeRoute->structure;
        $structure[] = $newParentTypeRoute->parent_id;
        $typeRoute->structure = $structure;
        $typeRoute->save();
        foreach ($typeRoute->params as $id) {
            $paramTypeRoute = $this->typeRouteStructure->getItem($category->type_id, $id);
            $index = array_search($typeRoute->parent_id, $paramTypeRoute->structure);
            $structure = array_merge($typeRoute->structure, array_slice($paramTypeRoute->structure, $index));
            $paramTypeRoute->structure = $structure;
            $paramTypeRoute->save();
        }
        $typeRouteParams = $typeRoute->params;
        $typeRouteParams[] = $typeRoute->parent_id;
        $params = [];

        foreach ($oldParentTypeRoute->params as $id) {
            if (!in_array($id, $typeRouteParams)) {
                $params[] = $id;
            }
        }

        $oldParentTypeRoute->params = $params;
        $oldParentTypeRoute->save();
        $newParentTypeRoute->params = array_unique(array_merge($newParentTypeRoute->params, $typeRouteParams));
        $newParentTypeRoute->save();

        foreach ($newParentTypeRoute->structure as $id) {
            $newParentTypeRouteItem = $this->typeRouteStructure->getItem($category->type_id, $id);
            $newParentTypeRouteItem->params = array_unique(array_merge($newParentTypeRouteItem->params, $typeRouteParams));
            $newParentTypeRouteItem->save();
        }
        /*
        if ($category->parent_id != $parent) {
            foreach ($typeRoute->structure as $id) {
                $typeRouteItem = $this->typeRouteStructure->getItem($category->type_id, $id);
                $newParams = [];
                foreach ($typeRouteItem->params as $paramId) {
                    if ($paramId != $category->id) {
                        $newParams[] = $paramId;
                    }
                }
                $typeRouteItem->params = $newParams;
                $typeRouteItem->save();
            }

            $typeRouteItem = $this->typeRouteStructure->getItem($category->type_id, $parent);
            $typeRoute->structure = array_merge($typeRouteItem->structure, [$parent]);
            $typeRoute->save();

            foreach ($typeRoute->structure as $id) {
                $typeRouteItem = $this->typeRouteStructure->getItem($category->type_id, $id);
                $newParams = array_merge($typeRouteItem->params, [$category->id], $typeRoute->params);
                //$newParams[] = $category->id;
                $typeRouteItem->params = $newParams;
                $typeRouteItem->save();
            }

            $typeRouteParent = $this->typeRouteStructure->getItem($category->type_id, $category->parent_id);
            $parentParams = $typeRouteParent->params;
            foreach ($typeRoute->params as $id) {
                $newParams = [];
                foreach ($parentParams as $paramId) {
                    if ($paramId != $parent) {
                        $newParams[] = $paramId;
                    }
                }
                $parentParams = $newParams;
                $typeRouteItem = $this->typeRouteStructure->getItem($category->type_id, $id);
                $structure = $typeRouteItem->structure;
                $index = array_search($category->id, $structure);
                $typeRouteItem->structure = array_merge($typeRoute->structure, array_slice($structure, $index));;
                $typeRouteItem->save();
            }

            $typeRouteParent->params = $parentParams;
            $typeRouteParent->save();
        }*/


        if ($category->route->name != $routeName || $category->parent_id != $parent) {

            $url = array_merge([$category->type->name], generateRouteUrl($category->type_id, $typeRoute->structure), [$routeName]);
            $category->url = implode('/', $url);
            $category->save();

            foreach ($typeRoute->params as $id) {
                $typeRouteItem = $this->typeRouteStructure->getItem($category->type_id, $id);
                $categoryItem = $this->categoryRepository->getById($id);

                $urls = generateRouteUrl($category->type_id, $typeRouteItem->structure);

                if ($category->route->name != $routeName) {
                    $index = array_search($category->id, $typeRouteItem->structure);
                    $urls[$index] = $routeName;
                }

                $url = array_merge([$category->type->name], $urls, [$categoryItem->route->name]);
                $categoryItem->url = implode('/', $url);
                $categoryItem->save();
            }
        }
    }

    public function create($typeId, array $fields)
    {
        $categoryFields = ['template' => 'template_id', 'parent' => 'parent_id', 'status' => 'status'];
        $routeFields = ['routeName' => 'name'];
        $metaFieldsExcept = ['template', 'parent', 'status', 'routeName'];

        $categoryAttributes = ['type_id' => $typeId, 'url' => ''];
        foreach ($categoryFields as $key => $value) {
            $categoryAttributes[$value] = Arr::get($fields, $key, 0);
        }
        $category = $this->categoryRepository->create($categoryAttributes);
        $typeRoute = $this->createTypeRouteStructure($category);
        $url = array_merge([$category->type->name], generateRouteUrl($category->type_id, $typeRoute->structure), [Arr::get($fields, 'routeName', '')]);
        $category->url = implode('/', $url);
        $category->save();

        $routeAttributes = ['type_id' => $typeId, 'parent_id' => $category->id];
        foreach ($routeFields as $key => $value) {
            $routeAttributes[$value] = Arr::get($fields, $key, '');
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
            ];
            $this->categoryMetaRepository->create($categoryMetaAttributes);
        }

        return $category;
    }

    private function createTypeRouteStructure(Category $category): TypeRouteStructure
    {
        $structure = [];
        $mainCategory = $category;
        if ($category->parent_id) {
            while ($category->parent_id) {
                $category = $this->categoryRepository->getById($category->parent_id);
                $structure[] = $category->id;

                $typeRoute = $this->typeRouteStructure->getItem($category->type_id, $category->id);
                $typeRoute->params = array_merge($typeRoute->params, [$mainCategory->id]);
                $typeRoute->save();
            }
        }

        return $this->typeRouteStructure->create([
            'type_id' => $mainCategory->type_id,
            'parent_id' => $mainCategory->id,
            'params' => [],
            'structure' => array_reverse($structure)
        ]);
    }

    public function delete($type, Category $category): Category
    {
        if ($category->type_id == $type) {
            $this->deleteTypeRouteStructure($category);
            $category->route->delete();
            $category->delete();
        }

        return $category;
    }

    private function deleteTypeRouteStructure(Category $category): TypeRouteStructure
    {
        $typeRoute = $this->typeRouteStructure->getItem($category->type_id, $category->id);

        foreach ($typeRoute->params as $id) {
            $typeRouteItem = $this->typeRouteStructure->getItem($category->type_id, $id);
            $structure = $typeRouteItem->structure;
            $index = array_search($category->id, $structure);

            if (isset($typeRouteItem->structure[$index + 1])) {
                $childCategory = $this->categoryRepository->getById($structure[$index + 1]);
                $childCategory->parent_id = $category->parent_id;
                $childCategory->save();
            }

            $newStructure = [];
            foreach ($structure as $structureId) {
                if ($category->id != $structureId) {
                    $newStructure[] = $structureId;
                }
            }
            $typeRouteItem->structure = $newStructure;
            $typeRouteItem->save();

            $childCategory = $this->categoryRepository->getById($id);
            $route = $childCategory->route;
            $url = array_merge([$category->type->name], generateRouteUrl($category->type_id, $newStructure), [$route->name]);
            $childCategory->url = implode('/', $url);
            $childCategory->save();
        }

        $typeRoute->delete();
        return $typeRoute;
    }
}
