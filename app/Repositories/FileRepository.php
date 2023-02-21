<?php
namespace App\Repositories;

use App\Models\File;

class FileRepository extends BaseRepository {
    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return File::class;
    }

    public function byFolderId(int $id = 0)
    {
        return $this->findByField('folder_id', $id, ['id', 'folder_id', 'size', 'name', 'extension']);
    }

    public function getByName($folderId, $name, $ext)
    {
        return $this->findWhere(['folder_id' => $folderId, 'name' => $name, 'extension' => $ext]);
    }
}