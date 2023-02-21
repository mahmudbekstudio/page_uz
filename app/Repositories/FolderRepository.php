<?php
namespace App\Repositories;

use App\Models\Folder;

class FolderRepository extends BaseRepository {
    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Folder::class;
    }

    public function byParentId(int $id = 0)
    {
        return $this->findByField('parent_id', $id, ['id', 'name', 'path']);
    }
}