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

    public function byFolderId(int $id = 0, $isLocal = false)
    {
        $where = ['folder_id' => $id, 'is_local' => $isLocal];
        $columns = ['id', 'folder_id', 'size', 'name', 'extension', 'is_local'];

        if ($isLocal) {
            $websiteRepository = WebsiteRepository::getInstance();
            $currentWebsite = $websiteRepository->getCurrent();
            $websiteRepository->setCurrent($websiteRepository->getMain()->id);
            $list = $this->findWhere($where, $columns);
            $websiteRepository->setCurrent($currentWebsite->id);

            return $list;
        }

        return $this->findWhere($where, $columns);
    }

    public function getByName($folderId, $name, $ext, $isLocal = false)
    {
        return $this->findWhere(['folder_id' => $folderId, 'is_local' => $isLocal, 'name' => $name, 'extension' => $ext]);
    }
}
