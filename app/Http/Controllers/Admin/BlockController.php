<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\BlockDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Block\CreateBlockRequest;
use App\Models\Block;
use App\Repositories\BlockRepository;
use App\Services\Admin\BlockService;

class BlockController extends Controller
{
    private BlockService $blockService;

    public function __construct(
        BlockService $blockService
    ) {
        $this->blockService = $blockService;
    }

    public function list(int $type, BlockDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->setTypeId($type)->toArray());
    }

    public function create(int $type, CreateBlockRequest $request)
    {
        $fieldNames = getFieldNames(getTypeById($type)->fields);
        $block = $this->blockService->create($type, $request->only($fieldNames));
        return responseJsonData(true, ['block' => $block]);
    }

    public function edit(int $type, int $block, CreateBlockRequest $request)
    {
        $fieldNames = getFieldNames(getTypeById($type)->fields);
        $blockItem = $this->blockService->update($type, $block, $request->only($fieldNames));
        return responseJsonData(true, ['block' => $blockItem]);
    }

    public function get(int $type, int $block)
    {
        return responseJsonData(true, ['block' => $this->blockService->getBlock($type, $block)]);
    }

    public function delete(int $type, Block $block)
    {
        return responseJsonData(true, ['block' => $this->blockService->delete($type, $block)]);
    }

    public function activeList(int $type, int $selectedId, BlockRepository $blockRepository)
    {
        return responseJsonData(true, ['blocks' => $blockRepository->getActiveList($type, [$selectedId])]);
    }
}
