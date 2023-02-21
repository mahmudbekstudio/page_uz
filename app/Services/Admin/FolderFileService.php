<?php
namespace App\Services\Admin;

use App\Models\Folder;
use App\Repositories\FileRepository;
use App\Repositories\FolderRepository;
use App\Services\BaseService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FolderFileService extends BaseService {

    private FolderRepository $folderRepository;
    private FileRepository $fileRepository;

    public function __construct(FolderRepository $folderRepository, FileRepository $fileRepository)
    {
        $this->folderRepository = $folderRepository;
        $this->fileRepository = $fileRepository;
    }

    public function folderContent(int $id = 0): array
    {
        return [
            'folder' => $this->folderRepository->byParentId($id),
            'file' => $this->fileRepository->byFolderId($id)
        ];
    }

    public function createFolder($folderId, $name)
    {
        $folderExist = $this->folderRepository->findWhere(['parent_id' => $folderId, 'name' => $name]);

        if(!$folderExist->isEmpty()) {
            return false;
        }

        if($folderId) {
            try {
                $parentFolder = $this->folderRepository->find($folderId);
                $path = $parentFolder->path;
            } catch (\Exception $e) {
                return false;
            }
        } else {
            $path = $this->websiteRootPath();
        }

        $path .= '/' . $name;

        if(!Storage::makeDirectory($path)) {
            return false;
        }

        $newFolder = $this->folderRepository->create(['parent_id' => $folderId, 'name' => $name, 'path' => $path]);

        return $newFolder->toArray();
    }

    public function renameFolder(int $id, string $name)
    {
        try {
            $folder = $this->folderRepository->find($id);
        } catch (\Exception $e) {
            return false;
        }

        $parentFolder = null;
        if($folder->parent_id) {
            $parentFolder = $this->folderRepository->find($folder->parent_id);
        }

        return $this->moveFolder($folder, $name, $parentFolder);
    }

    public function moveFolder(Folder $folder, string $folderName, Folder $toFolder = null)
    {
        if($toFolder) {
            $path = $toFolder->path;
            $parentId = $toFolder->id;
        } else {
            $path = $this->websiteRootPath();
            $parentId = 0;
        }

        $newPath = $path . '/' . $folderName;

        $folderWithNameExist = $this->folderRepository->findByField('path', $newPath);

        if(!$folderWithNameExist->isEmpty()) {
            return false;
        }

        Storage::makeDirectory($newPath);

        $folderFiles = $this->fileRepository->byFolderId($folder->id);

        if(!$folderFiles->isEmpty()) {
            $folderFiles->each(function ($item) use ($folder, $newPath) {
                //TODO: check after file upload
                $fileName = $item->name . '.' . $item->extension;
                Storage::move($folder->path . '/' . $fileName, $newPath . '/' . $fileName);
            });
        }

        if(!Storage::deleteDirectory($folder->path, $newPath)) {
            return false;
        }

        $folder->name = $folderName;
        $folder->path = $newPath;
        $folder->parent_id = $parentId;
        $folder->save();

        return $folder->toArray();
    }

    public function deleteFolder(int $id)
    {
        try {
            $folder = $this->folderRepository->find($id);
        } catch (\Exception $e) {
            return false;
        }

        $childFolder = $this->folderRepository->byParentId($id);

        if(!$childFolder->isEmpty()) {
            return false;
        }

        $files = $this->fileRepository->byFolderId($id);

        if(!$files->isEmpty()) {
            return false;
        }

        if(!Storage::deleteDirectory($folder->path)) {
            return false;
        }

        //$this->folderRepository->deleteWhere([['path', 'LIKE', $folder->path . '/%']]);
        $this->folderRepository->delete($id);

        return $folder->toArray();
    }

    private function websiteRootPath(): string
    {
        return '/' . getCurrentWebsiteId();
    }

    public function uploadFile(int $folderId, UploadedFile $file)
    {
        try {
            $size = $file->getSize();
            $fileName = explode('.', $file->getClientOriginalName());
            $name = $fileName[0];
            $ext = $fileName[1];
            $newName = $name;

            $k = 0;
            while (!$this->fileRepository->getByName($folderId, $newName, $ext)->isEmpty()) {
                $k++;
                $newName = $name . ' (' . $k . ')';
            }
            $name = $newName;

            if($folderId) {
                $folder = $this->folderRepository->find($folderId);
                $folderPath = $folder->path;
            } else {
                $folderPath = $this->websiteRootPath();
            }

            if(!$file->storePubliclyAs($folderPath, $name . '.' . $ext)) {
                return false;
            }

            return $this->fileRepository->create([
                'folder_id' => $folderId,
                'size' => $size,
                'name' => $name,
                'extension' => $ext
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function renameFile($id, $name)
    {
        $file = $this->fileRepository->find($id);
        return $this->moveFile($id, $file->folder_id, $name);
    }

    public function moveFile($fileId, $toFolderId, $toFileName)
    {
        try {
            $file = $this->fileRepository->find($fileId);

            if($file->folder_id === $toFolderId && $file->name === $toFileName) {
                return $file;
            }

            if(!$this->fileRepository->getByName($toFolderId, $toFileName, $file->extension)->isEmpty()) {
                return false;
            }

            if($toFolderId) {
                $toFolder = $this->folderRepository->find($toFolderId);
                $toFolderPath = $toFolder->path;
            } else {
                $toFolderPath = $this->websiteRootPath();
            }


            $folder = $file->folder;
            $folderPath = $folder ? $folder->path : $this->websiteRootPath();

            if(!Storage::move($folderPath . '/' . $file->name . '.' . $file->extension, $toFolderPath . '/' . $toFileName . '.' . $file->extension)) {
                return false;
            }

            $file->folder_id = $toFolderId;
            $file->name = $toFileName;
            $file->save();

            return $file;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteFile($id)
    {
        try {
            $file = $this->fileRepository->find($id);
            $folder = $file->folder;
            $folderPath = $folder ? $folder->path : $this->websiteRootPath();

            if(!Storage::delete($folderPath . '/' . $file->name . '.' . $file->extension)) {
                return false;
            }

            $file->delete();

            return $file;
        } catch (\Exception $e) {
            return false;
        }
    }
}