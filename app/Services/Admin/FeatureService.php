<?php

namespace App\Services\Admin;

use App\Models\Feature;
use App\Models\Type;
use App\Repositories\FeatureRepository;
use App\Repositories\TypeRepository;
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

    public function getTypes($type)
    {
        $result = [];

        if (in_array($type, Type::pageTypes())) {
            $result = app(TypeRepository::class)->getByType($type);
        } elseif (in_array($type, Feature::typesList())) {
            $result = config('app.feature_types_list.' . $type, []);
        }

        return $result;
    }
}
