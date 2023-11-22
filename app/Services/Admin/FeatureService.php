<?php

namespace App\Services\Admin;

use App\Models\Feature;
use App\Repositories\FeatureRepository;
use App\Services\BaseService;

class FeatureService extends BaseService
{
    private FeatureRepository $featureRepository;

    public function __construct()
    {
        $this->featureRepository = app(FeatureRepository::class);
    }

    public function delete(Feature $feature)
    {
        $feature->delete();

        return true;
    }
}
