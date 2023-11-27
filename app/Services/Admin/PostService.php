<?php
namespace App\Services\Admin;

use App\Helpers\DataFormat;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\TypeRouteStructure;
use App\Repositories\PostMetaRepository;
use App\Repositories\PostRepository;
use App\Repositories\RouteRepository;
use App\Repositories\TypeRouteStructureRepository;
use App\Repositories\WebsiteRepository;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class PostService extends BaseService
{
    private PostRepository $postRepository;
    private RouteRepository $routeRepository;
    private PostMetaRepository $postMetaRepository;
    private TypeRouteStructureRepository $typeRouteStructure;

    public function __construct()
    {
        $this->postRepository = app(PostRepository::class);
        $this->routeRepository = app(RouteRepository::class);
        $this->postMetaRepository = app(PostMetaRepository::class);
        $this->typeRouteStructure = app(TypeRouteStructureRepository::class);
    }

    public function getPost($typeId, $postId)
    {
        $post = $this->postRepository->getById($postId);
        $fieldValues = getFieldValues(getTypeById($typeId)->fields);
        $result = array_merge($fieldValues, [
            'childOf' => $post->category_id,
            'template' => $post->template_id,
            'parent' => $post->parent_id,
            'status' => $post->status,
            'routeName' => $post->route->name,
        ]);

        $post->metas->each(function (PostMeta $meta) use (&$result) {
            $result[$meta->meta_key] = DataFormat::toFormat($meta->meta_value, $meta->meta_format);
        });

        return $result;
    }

    public function update($typeId, $postId, array $fields)
    {
        $post = $this->postRepository->getById($postId);
        $parent = Arr::get($fields, 'parent', 0);
        $routeName = Arr::get($fields, 'routeName', '');
        $this->updateTypeRouteStructure($post, (int)$parent, $routeName);
        $post->category_id = Arr::get($fields, 'childOf', 0);
        $post->template_id = Arr::get($fields, 'template', 0);
        $post->parent_id = $parent;
        $post->status = Arr::get($fields, 'status', 0);
        $post->save();

        $route = $post->route;
        $route->name = $routeName;
        $route->save();

        $metaFieldsExcept = config('app.template.except_fields');
        $metaFields = Arr::except($fields, $metaFieldsExcept);
        $typeFields = getFields(getTypeById($typeId)->fields);
        $post->metas()->delete();
        foreach ($metaFields as $name => $value) {
            $format = Arr::get($typeFields[$name], 'params.valueType', DataFormat::getDefault());
            $postMetaAttributes = [
                'post_id' => $post->id,
                'meta_format' => $format,
                'meta_key' => $name,
                'meta_value' => DataFormat::toString($value, $format),
            ];
            $this->postMetaRepository->create($postMetaAttributes);
        }

        return $post;
    }

    private function updateTypeRouteStructure(Post $post, $parent, $routeName)
    {
        $typeRoute = $this->typeRouteStructure->getItem($post->type_id, $post->id);
        if (!$typeRoute) return;
        $newParentTypeRoute = $this->typeRouteStructure->getItem($post->type_id, $parent);
        $oldParentTypeRoute = $this->typeRouteStructure->getItem($post->type_id, $post->parent_id);

        $structure = $newParentTypeRoute ? $newParentTypeRoute->structure : [];

        if ($newParentTypeRoute) {
            $structure[] = $newParentTypeRoute->parent_id;
        }

        $typeRoute->structure = $structure;
        $typeRoute->save();
        foreach ($typeRoute->params as $id) {
            $paramTypeRoute = $this->typeRouteStructure->getItem($post->type_id, $id);
            $index = array_search($typeRoute->parent_id, $paramTypeRoute->structure);
            $structure = array_merge($typeRoute->structure, array_slice($paramTypeRoute->structure, $index));
            $paramTypeRoute->structure = $structure;
            $paramTypeRoute->save();
        }
        $typeRouteParams = $typeRoute->params;
        $typeRouteParams[] = $typeRoute->parent_id;
        $params = [];

        if ($oldParentTypeRoute) {
            foreach ($oldParentTypeRoute->params as $id) {
                if (!in_array($id, $typeRouteParams)) {
                    $params[] = $id;
                }
            }

            $oldParentTypeRoute->params = $params;
            $oldParentTypeRoute->save();
        }

        if ($newParentTypeRoute) {
            $newParentTypeRoute->params = array_unique(array_merge($newParentTypeRoute->params, $typeRouteParams));
            $newParentTypeRoute->save();

            foreach ($newParentTypeRoute->structure as $id) {
                $newParentTypeRouteItem = $this->typeRouteStructure->getItem($post->type_id, $id);
                $newParentTypeRouteItem->params = array_unique(array_merge($newParentTypeRouteItem->params, $typeRouteParams));
                $newParentTypeRouteItem->save();
            }
        }

        if ($post->route->name != $routeName || $post->parent_id != $parent) {

            $url = array_merge([$post->type->name]/*, generateRouteUrl($post->type_id, $typeRoute->structure)*/, [$routeName]);
            $post->url = implode('/', $url);
            $post->save();

            /*foreach ($typeRoute->params as $id) {
                $typeRouteItem = $this->typeRouteStructure->getItem($post->type_id, $id);
                $postItem = $this->postRepository->getById($id);

                $urls = generateRouteUrl($post->type_id, $typeRouteItem->structure);

                if ($post->route->name != $routeName) {
                    $index = array_search($post->id, $typeRouteItem->structure);
                    $urls[$index] = $routeName;
                }

                $url = array_merge([$post->type->name], $urls, [$postItem->route->name]);
                $postItem->url = implode('/', $url);
                $postItem->save();
            }*/
        }
    }

    public function create($typeId, array $fields)
    {
        $postFields = ['childOf' => 'category_id', 'template' => 'template_id', 'parent' => 'parent_id', 'status' => 'status'];
        $routeFields = ['routeName' => 'name'];
        $metaFieldsExcept = config('app.template.except_fields');

        $postAttributes = ['type_id' => $typeId, 'url' => ''];
        foreach ($postFields as $key => $value) {
            $postAttributes[$value] = Arr::get($fields, $key, 0);
        }
        $post = $this->postRepository->create($postAttributes);
        //$typeRoute = $this->createTypeRouteStructure($post);
        $url = array_merge([$post->type->name]/*, generateRouteUrl($post->type_id, $typeRoute->structure)*/, [Arr::get($fields, 'routeName', '')]);
        $post->url = implode('/', $url);
        $post->save();

        $routeAttributes = ['type_id' => $typeId, 'parent_id' => $post->id];

        foreach ($routeFields as $key => $value) {
            $routeAttributes[$value] = Arr::get($fields, $key, '');
        }

        $this->routeRepository->create($routeAttributes);

        $metaFields = Arr::except($fields, $metaFieldsExcept);
        $typeFields = getFields(getTypeById($typeId)->fields);
        foreach ($metaFields as $name => $value) {
            $format = Arr::get($typeFields[$name], 'params.valueType', DataFormat::getDefault());
            $postMetaAttributes = [
                'post_id' => $post->id,
                'meta_format' => $format,
                'meta_key' => $name,
                'meta_value' => DataFormat::toString($value, $format),
            ];
            $this->postMetaRepository->create($postMetaAttributes);
        }

        return $post;
    }

    /*private function createTypeRouteStructure(Post $post): TypeRouteStructure
    {
        $structure = [];
        $mainPost = $post;
        if ($post->parent_id) {
            while ($post->parent_id) {
                $post = $this->postRepository->getById($post->parent_id);
                $structure[] = $post->id;

                $typeRoute = $this->typeRouteStructure->getItem($post->type_id, $post->id);
                $typeRoute->params = array_merge($typeRoute->params, [$mainPost->id]);
                $typeRoute->save();
            }
        }

        return $this->typeRouteStructure->create([
            'type_id' => $mainPost->type_id,
            'parent_id' => $mainPost->id,
            'params' => [],
            'structure' => array_reverse($structure)
        ]);
    }*/

    public function delete($type, Post $post): Post
    {
        $settings = WebsiteRepository::getInstance()->getMetas();

        if (
            $post->type_id == $type &&
            !(Arr::get($settings, 'pageHome') == $post->id || Arr::get($settings, 'page404') == $post->id)
        ) {
            $this->deleteTypeRouteStructure($post);
            $post->route->delete();
            $post->delete();
        }

        return $post;
    }

    private function deleteTypeRouteStructure(Post $post): TypeRouteStructure
    {
        $typeRoute = $this->typeRouteStructure->getItem($post->type_id, $post->id);

        /*foreach ($typeRoute->params as $id) {
            $typeRouteItem = $this->typeRouteStructure->getItem($post->type_id, $id);
            $structure = $typeRouteItem->structure;
            $index = array_search($post->id, $structure);

            if (isset($typeRouteItem->structure[$index + 1])) {
                $childPost = $this->postRepository->getById($structure[$index + 1]);
                $childPost->parent_id = $post->parent_id;
                $childPost->save();
            }

            $newStructure = [];
            foreach ($structure as $structureId) {
                if ($post->id != $structureId) {
                    $newStructure[] = $structureId;
                }
            }
            $typeRouteItem->structure = $newStructure;
            $typeRouteItem->save();

            $childPost = $this->postRepository->getById($id);
            $route = $childPost->route;
            $url = array_merge([$post->type->name], generateRouteUrl($post->type_id, $newStructure), [$route->name]);
            $childPost->url = implode('/', $url);
            $childPost->save();
        }*/

        $typeRoute->delete();
        return $typeRoute;
    }
}
