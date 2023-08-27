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

    public function index($lang, $typeName, $routeName)
    {
        $typeItem = $this->typeRepository->getByName($typeName);

        if (!$typeItem) {
            return go404($lang);
        }

        $routeItem = $this->routeRepository->getByTypeAndName($typeItem->id, $routeName);

        if (!$routeItem) {
            return go404($lang);
        }

        $isPost = $typeItem->type === Type::TYPE_POST;
        if ($isPost) {
            $item = $this->postRepository->getById($routeItem->parent_id);
        } else {
            $item = $this->categoryRepository->getById($routeItem->parent_id);
        }

        if (!$item->status) {
            return go404($lang);
        }

        return viewTemplate($item, $typeItem, $routeItem, $isPost);
        //return view('welcome');
        //dd([$lang, $typeName, $routeName, $item->toArray()]);
        //return [$lang, $typeName, $routeName, $item->toArray()];
    }

    public function admin()
    {
        return view('admin');
    }
}
