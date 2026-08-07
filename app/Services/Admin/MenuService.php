<?php

namespace App\Services\Admin;

use App\Models\Type;
use App\Repositories\CategoryRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PostRepository;
use App\Repositories\RouteRepository;
use App\Repositories\TypeRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class MenuService extends BaseService
{
    private MenuRepository $menuRepository;
    private RouteRepository $routeRepository;
    private TypeRepository $typeRepository;
    private PostRepository $postRepository;
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->menuRepository = app(MenuRepository::class);
        $this->routeRepository = app(RouteRepository::class);
        $this->typeRepository = app(TypeRepository::class);
        $this->postRepository = app(PostRepository::class);
        $this->categoryRepository = app(CategoryRepository::class);
    }

    public function update($id, array $fields)
    {
        $fields['name'] = json_encode($fields['name']);
        return $this->menuRepository->update($fields, $id)->only(['id', 'name', 'items']);
    }

    public function create(array $fields)
    {
        $fields['name'] = json_encode($fields['name']);
        return $this->menuRepository->create($fields)->only(['id', 'name', 'items']);
    }

    public function links(): array
    {
        $websiteData = websiteData();
        $homePage = $this->postRepository->getById(Arr::get($websiteData, 'metas.pageHome'));

        $links = [
            [
                'type' => 'link',
                'title' => 'Links',
                'children' => [
                    [
                        'id' => 'custom',
                        'title' => 'Custom',
                        'url' => '#',
                        'type_id' => 0
                    ],
                    [
                        'id' => $homePage->id,
                        'title' => 'Home page',
                        'url' => $homePage->url,
                        'type_id' => $homePage->type_id
                    ]
                ]
            ]
        ];

        $types = $this->typeRepository->findWhere(['status' => 1], ['id', 'name', 'type']);
        $postTypeIds = $types->filter(function ($item) {
            return $item->type == Type::TYPE_POST;
        })->pluck('id')->toArray();
        $categoryTypeIds = $types->filter(function ($item) {
            return $item->type == Type::TYPE_CATEGORY;
        })->pluck('id')->toArray();

        $posts = $this
            ->postRepository
            ->with(['metas' => function (Builder $query) {
                $query
                    ->select(['post_id', 'meta_format', 'meta_key', 'meta_value'])
                    ->where('meta_key', '=', 'title');
            }])
            ->findWhere(['status' => 1, ['type_id', 'in', $postTypeIds]], ['id', 'type_id', 'url']);
        $postsList = [];

        foreach ($posts as $post) {
            if (!isset($postsList[$post->type_id]))
            {
                $postsList[$post->type_id] = [];
            }

            $postItem = $post->only(['id', 'type_id', 'url']);

            foreach ($post->metas as $meta) {
                if($meta->meta_key === 'title') {
                    $postItem['title'] = $meta->meta_value;
                    break;
                }
            }

            $postsList[$post->type_id][] = $postItem;
        }

        $categories = $this
            ->categoryRepository
            ->with(['metas' => function (Builder $query) {
                $query
                    ->select(['category_id', 'meta_format', 'meta_key', 'meta_value'])
                    ->where('meta_key', '=', 'title');
            }])
            ->findWhere(['status' => 1, ['type_id', 'in', $categoryTypeIds]], ['id', 'type_id', 'url']);
        $categoriesList = [];
        foreach ($categories as $category) {
            if (!isset($categoriesList[$category->type_id]))
            {
                $categoriesList[$category->type_id] = [];
            }

            $categoryItem = $category->only(['id', 'type_id', 'url']);
            foreach ($category->metas as $meta) {
                if($meta->meta_key === 'title') {
                    $categoryItem['title'] = $meta->meta_value;
                    break;
                }
            }

            $categoriesList[$category->type_id][] = $categoryItem;
        }

        foreach ($types as $type) {
            $link = [
                'type' => $type->type . '_' . $type->name,
                'title' => $type->name,
                'children' => []
            ];

            if ($type->type == Type::TYPE_CATEGORY) {
                if (isset($categoriesList[$type->id])) {
                    foreach ($categoriesList[$type->id] as $key => $value) {
                        $link['children'][] = $value;
                    }
                } else {
                    continue;
                }
            } else {
                if (isset($postsList[$type->id])) {
                    foreach ($postsList[$type->id] as $key => $value) {
                        $link['children'][] = $value;
                    }
                } else {
                    continue;
                }
            }

            $links[] = $link;
        }

        return $links;
    }
}
