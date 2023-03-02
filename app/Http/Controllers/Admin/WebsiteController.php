<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\WebsiteDataTable;
use App\Http\Controllers\Controller;

class WebsiteController extends Controller
{
    public function list(WebsiteDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->toArray());
    }

    public function byId()
    {
        return responseJson(true);
    }

    public function create()
    {
        return responseJson(true);
    }

    public function update()
    {
        return responseJson(true);
    }

    public function delete()
    {
        return responseJson(true);
    }
}
