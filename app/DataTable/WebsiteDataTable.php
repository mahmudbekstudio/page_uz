<?php

namespace App\DataTable;

use App\Criteria\Website\AddMainWebsiteCriteriaCriteria;
use App\Criteria\Website\GetNameWebsiteCriteriaCriteria;
use App\Criteria\Website\GetRootWebsiteCriteriaCriteria;
use App\Repositories\WebsiteRepository;

class WebsiteDataTable extends DataTable
{
    protected string $repositoryClass = WebsiteRepository::class;
    protected array $columns = [
        'websites.id' => 'id',
        'websites.status' => 'status',
        'websites.created_at' => 'created_at',
        'websites.domain' => 'domain',
        'main_website.domain' => 'main_domain',
        'website_name.meta_value' => 'name',
    ];

    protected function handle()
    {
        return $this
            ->repository
            ->pushCriteria(GetRootWebsiteCriteriaCriteria::class)
            ->pushCriteria(GetNameWebsiteCriteriaCriteria::class)
            ->pushCriteria(AddMainWebsiteCriteriaCriteria::class);
    }

    protected function transform(array $list): array
    {
        $list = parent::transform($list);

        foreach ($list['data'] as $key => $item) {
            $item['domain'] = $item['main_domain'] ?: $item['domain'];
            unset($item['main_domain']);
            $list['data'][$key] = $item;
        }

        return $list;
    }
}
