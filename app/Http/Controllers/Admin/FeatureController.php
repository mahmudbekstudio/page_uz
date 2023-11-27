<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\FeatureDataTable;
use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\FeatureType;
use App\Models\Type;
use App\Repositories\TypeRepository;
use App\Services\Admin\FeatureService;

class FeatureController extends Controller
{
    private FeatureService $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    public function list(FeatureDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->toArray());
    }

    public function create()
    {
        return responseJsonData(true, ['feature' => []]);
    }

    public function edit(int $feature)
    {
        return responseJsonData(true, ['feature' => []]);
    }

    public function get(Feature $feature)
    {
        return responseJsonData(true, ['feature' => []]);
    }

    public function getByType(string $type)
    {
        return responseJsonData(true, ['list' => []]);
    }

    public function canDeleteFeature(Feature $feature)
    {
        return true;
    }

    public function delete(Feature $feature)
    {
        $result = true;

        if (getCurrentWebsiteId() == $feature->website_id) {
            if ($this->canDeleteFeature($feature)) {
                $result = $this->featureService->delete($feature);
            } else {
                return responseJsonMessage(false, trans('error.feature_used'));
            }
        }

        return responseJsonData($result, ['feature' => []]);
    }

    public function typesList(int $typeId, TypeRepository $typeRepository)
    {
        $result = [];
        /*switch ($typeId)
        {
            case [FeatureType::POST_TYPE_ID]:
                $result = Type::where('type', 'post')->get();
                break;
            case [FeatureType::POST_LIST_TYPE_ID]:
                return [];
        }*/

        return responseJsonData(true, ['typesList' => $typeRepository->getByType('post')]);
    }
}
