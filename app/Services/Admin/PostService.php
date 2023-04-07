<?php
namespace App\Services\Admin;

use App\Helpers\DataFormat;
use App\Models\PostMeta;
use App\Repositories\PostMetaRepository;
use App\Repositories\PostRepository;
use App\Repositories\RouteRepository;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class PostService extends BaseService
{
    private PostRepository $postRepository;
    private RouteRepository $routeRepository;
    private PostMetaRepository $postMetaRepository;

    public function __construct()
    {
        $this->postRepository = app(PostRepository::class);
        $this->routeRepository = app(RouteRepository::class);
        $this->postMetaRepository = app(PostMetaRepository::class);
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
        $post->category_id = Arr::get($fields, 'childOf');
        $post->template_id = Arr::get($fields, 'template');
        $post->parent_id = Arr::get($fields, 'parent');
        $post->status = Arr::get($fields, 'status');
        $post->save();

        $route = $post->route;
        $route->name = Arr::get($fields, 'routeName');
        $route->save();

        $metaFieldsExcept = ['childOf', 'template', 'parent', 'status', 'routeName'];
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
                'lang' => ''
            ];
            $this->postMetaRepository->create($postMetaAttributes);
        }

        return $post;
    }

    public function create($typeId, array $fields)
    {
        $postFields = ['childOf' => 'category_id', 'template' => 'template_id', 'parent' => 'parent_id', 'status' => 'status'];
        $routeFields = ['routeName' => 'name'];
        $metaFieldsExcept = ['childOf', 'template', 'parent', 'status', 'routeName'];

        $postAttributes = ['type_id' => $typeId];
        foreach ($postFields as $key => $value) {
            $postAttributes[$value] = Arr::get($fields, $key);
        }
        $post = $this->postRepository->create($postAttributes);

        $routeAttributes = ['type_id' => $typeId, 'parent_id' => $post->id];
        foreach ($routeFields as $key => $value) {
            $routeAttributes[$value] = Arr::get($fields, $key);
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
                'lang' => ''
            ];
            $this->postMetaRepository->create($postMetaAttributes);
        }

        return $post;
    }
}
