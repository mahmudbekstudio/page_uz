<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\MenuDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Menu\CreateMenuRequest;
use App\Models\Menu;
use App\Services\Admin\MenuService;

class MenuController extends Controller
{
    private MenuService $menuService;

    public function __construct(
        MenuService $menuService
    ) {
        $this->menuService = $menuService;
    }

    public function list(MenuDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->toArray());
    }

    public function create(CreateMenuRequest $request)
    {
        $menu = $this->menuService->create($request->only(['name', 'items']));
        return responseJsonData(true, ['menu' => $menu]);
    }

    public function edit(int $menu, CreateMenuRequest $request)
    {
        return responseJsonData(true, ['menu' => $this->menuService->update($menu, $request->only('name', 'items'))]);
    }

    public function get(Menu $menu)
    {
        return responseJsonData(true, ['menu' => $menu->only(['id', 'name', 'items'])]);
    }

    public function delete(Menu $menu)
    {
        if (getCurrentWebsiteId() == $menu->website_id) {
            $menu->delete();
        }

        return responseJsonData(true, ['menu' => $menu]);
    }

    public function links()
    {
        return responseJsonData(true, ['links' => $this->menuService->links()]);
    }
}
