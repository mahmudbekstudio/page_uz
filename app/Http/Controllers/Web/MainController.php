<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\Repositories\RouteRepository;
use App\Repositories\TypeRepository;
use App\Repositories\WebsiteRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class MainController extends Controller
{
    private TypeRepository $typeRepository;
    private RouteRepository $routeRepository;
    private PostRepository $postRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(
        TypeRepository $typeRepository,
        RouteRepository $routeRepository,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->typeRepository = $typeRepository;
        $this->routeRepository = $routeRepository;
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function redirectToHome($any = null) {
        $websiteRepository = WebsiteRepository::getInstance();
        $websiteMetas = $websiteRepository->getMetas();
        list($lang, $typeName, $routeName) = array_pad(explode('/', $any), 3, null);

        if (!in_array($lang, Arr::get($websiteMetas, 'languages_list'))) {
            $lang = Arr::get($websiteMetas, 'language');
        }

        if (!$typeName || !$routeName) {
            return goHome($lang);
        }

        $typeItem = $this->typeRepository->getByName($typeName);

        if (!$typeItem) {
            return go404($lang);
        }

        $routeItem = $this->routeRepository->getByTypeAndName($typeItem->id, $routeName);

        if (!$routeItem) {
            return go404($lang);
        }

        return redirect(implode('/', [$lang, $typeName, $routeName]));
    }

    public function index($lang = null, $typeName = null, $routeName = null)
    {
        $websiteRepository = WebsiteRepository::getInstance();
        $websiteMetas = $websiteRepository->getMetas();

        if (!in_array($lang, Arr::get($websiteMetas, 'languages_list'))) {
            $lang = Arr::get($websiteMetas, 'language');
        }

        if (!$typeName || !$routeName) {
            return goHome($lang);
        }

        $typeItem = $this->typeRepository->getByName($typeName);

        if (!$typeItem) {
            return go404($lang);
        }

        $routeItem = $this->routeRepository->getByTypeAndName($typeItem->id, $routeName);

        if (!$routeItem) {
            return go404($lang);
        }

        if (!in_array($lang, Arr::get($websiteMetas, 'languages_list'))) {
            $lang = Arr::get($websiteMetas, 'language');
        }

        app()->setLocale($lang);

        if (!$typeItem) {
            return go404($lang);
        }

        if (!$routeItem) {
            return go404($lang);
        }

        $isPost = $typeItem->type === Type::TYPE_POST;
        if ($isPost) {
            $item = $this->postRepository->getById($routeItem->parent_id);
        } else {
            $item = $this->categoryRepository->getById($routeItem->parent_id);
        }

        $metas = $item->metas;
        $publishStart = $metas->first(function ($meta) {
            return $meta->meta_key === 'publishStart';
        });
        $publishEnd = $metas->first(function ($meta) {
            return $meta->meta_key === 'publishEnd';
        });

        $isGoto404 =
            !$item->status ||
            (
                $publishStart &&
                $publishStart->meta_value &&
                Carbon::now()->lt(Carbon::create($publishStart->meta_value))
            ) ||
            (
                $publishEnd &&
                $publishEnd->meta_value &&
                Carbon::now()->subDay()->gt(Carbon::create($publishEnd->meta_value))
            );

        if ($isGoto404) {
            return go404($lang);
        }

        return viewTemplate($item, $typeItem, $routeItem, $isPost);
    }

    public function admin()
    {
        return view('admin');
    }
}
