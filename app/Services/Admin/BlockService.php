<?php
namespace App\Services\Admin;

use App\Helpers\DataFormat;
use App\Models\Block;
use App\Models\BlockMeta;
use App\Repositories\BlockMetaRepository;
use App\Repositories\BlockRepository;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class BlockService extends BaseService
{
    private BlockRepository $blockRepository;
    private BlockMetaRepository $blockMetaRepository;

    public function __construct()
    {
        $this->blockRepository = app(BlockRepository::class);
        $this->blockMetaRepository = app(BlockMetaRepository::class);
    }

    public function getBlock($typeId, $blockId)
    {
        $block = $this->blockRepository->getById($blockId);
        $fieldValues = getFieldValues(getTypeById($typeId)->fields);
        $result = array_merge($fieldValues, [
            'status' => $block->status,
        ]);

        $block->metas->each(function (BlockMeta $meta) use (&$result) {
            $result[$meta->meta_key] = DataFormat::toFormat($meta->meta_value, $meta->meta_format);
        });

        return $result;
    }

    public function update($typeId, $blockId, array $fields)
    {
        $block = $this->blockRepository->getById($blockId);
        $block->status = Arr::get($fields, 'status', 0);
        $block->save();

        $metaFieldsExcept = config('app.template.except_fields');
        $metaFields = Arr::except($fields, $metaFieldsExcept);
        $typeFields = getFields(getTypeById($typeId)->fields);
        $block->metas()->delete();
        foreach ($metaFields as $name => $value) {
            $format = Arr::get($typeFields[$name], 'params.valueType', DataFormat::getDefault());
            $blockMetaAttributes = [
                'block_id' => $block->id,
                'meta_format' => $format,
                'meta_key' => $name,
                'meta_value' => DataFormat::toString($value, $format),
            ];
            $this->blockMetaRepository->create($blockMetaAttributes);
        }

        return $block;
    }

    public function create($typeId, array $fields)
    {
        $metaFieldsExcept = config('app.template.except_fields');

        $blockAttributes = ['type_id' => $typeId, 'status' => Arr::get($fields, 'status', 0)];
        $block = $this->blockRepository->create($blockAttributes);

        $metaFields = Arr::except($fields, $metaFieldsExcept);
        $typeFields = getFields(getTypeById($typeId)->fields);
        foreach ($metaFields as $name => $value) {
            $format = Arr::get($typeFields[$name], 'params.valueType', DataFormat::getDefault());
            $blockMetaAttributes = [
                'block_id' => $block->id,
                'meta_format' => $format,
                'meta_key' => $name,
                'meta_value' => DataFormat::toString($value, $format),
            ];
            $this->blockMetaRepository->create($blockMetaAttributes);
        }

        return $block;
    }

    public function delete($type, Block $block): Block
    {
        if ($block->type_id == $type) {
            $block->delete();
        }

        return $block;
    }
}
