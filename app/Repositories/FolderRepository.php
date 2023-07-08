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

    public function byParentId(int $id = 0, $isLocal = false)
    {
        $where = ['parent_id' => $id, 'is_local' => $isLocal];
        $columns = ['id', 'name', 'path', 'is_local'];

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
}
