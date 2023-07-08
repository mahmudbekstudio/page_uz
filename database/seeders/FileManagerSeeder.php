<?php

namespace Database\Seeders;

use App\Repositories\FileRepository;
use App\Repositories\FolderRepository;
use App\Repositories\WebsiteRepository;
use App\Services\Admin\FolderFileService;
use App\Services\AuthService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FileManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $websiteRepository = WebsiteRepository::getInstance();
        $mainWebsite = $websiteRepository->getMain();
        $websiteRepository->setCurrent($mainWebsite->id);
        app(AuthService::class)->loginById(1);

        $rootPath = 'images/file-manager';
        $list = Storage::disk('resources')->allDirectories($rootPath);
        $folderFileService = app(FolderFileService::class);
        $folderRepository = app(FolderRepository::class);
        $fileRepository = app(FileRepository::class);
        $folderFileService->setRootPath('/' . $rootPath);
        $addedFolders = [];

        foreach ($list as $folder) {
            $folder = str_replace('\\', '/', $folder);
            $folder = str_replace($rootPath . '/', '', $folder);
            $folder = explode('/', $folder);
            $parentId = 0;
            $fullFolderPath = implode('/', $folder);
            $folderName = array_pop($folder);

            $folder = implode('/', $folder);

            if (!empty($folder)) {
                $parentId = $addedFolders[$folder]['id'];
            }

            $addedFolders[$fullFolderPath] = $folderFileService->createFolder($parentId, $folderName, true);
            if ($addedFolders[$fullFolderPath] === false) {
                $addedFolders[$fullFolderPath] = $folderRepository->findWhere(['parent_id' => $parentId, 'name' => $folderName, 'is_local' => true])->first()->toArray();
            }
        }

        $list = Storage::disk('resources')->allFiles($rootPath);
        foreach ($list as $filePath) {
            $size = filesize(resource_path($filePath));
            $filePath = str_replace('\\', '/', $filePath);
            $filePath = str_replace($rootPath . '/', '', $filePath);
            $filePath = explode('/', $filePath);

            $fileName = array_pop($filePath);
            $dotpos = strripos($fileName, ".");
            $name = substr($fileName, 0, $dotpos);
            $ext = substr($fileName, $dotpos + 1);
            $filePath = implode('/', $filePath);

            $fileRepository->firstOrCreate([
                'folder_id' => $addedFolders[$filePath]['id'],
                'size' => $size,
                'name' => $name,
                'extension' => $ext,
                'is_local' => true
            ]);
        }

        $folderFileService->resetRootPath();
    }
}
