<?php

namespace App\Services\Admin;

use App\Repositories\WebsiteRepository;
use App\Services\BaseService;

class WebsiteService extends BaseService
{
    private WebsiteRepository $websiteRepository;

    public function __construct(WebsiteRepository $websiteRepository)
    {
        $this->websiteRepository = $websiteRepository;
    }
}
