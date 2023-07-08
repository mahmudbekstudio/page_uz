<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Filemanager\CreateFolderRequest;
use App\Http\Requests\Admin\Filemanager\RenameFileRequest;
use App\Http\Requests\Admin\Filemanager\RenameFolderRequest;
use App\Http\Requests\Admin\Filemanager\UploadFileRequest;
use App\Services\Admin\FolderFileService;
use Illuminate\Support\Arr;

class FolderFileController extends Controller
{
    private FolderFileService $folderFileService;

    public function __construct(FolderFileService $folderFileService)
    {
        $this->folderFileService = $folderFileService;
    }

    public function folderContent(int $id)
    {
        return responseJsonData(true, $this->folderFileService->folderContent($id));
    }

    public function folderStaticContent(int $id)
    {
        return responseJsonData(true, $this->folderFileService->folderStaticContent($id));
    }

    public function createFolder(CreateFolderRequest $request)
    {
        $data = $request->only(['folder_id', 'name']);
        $result = $this->folderFileService->createFolder(
            Arr::get($data, 'folder_id'),
            Arr::get($data, 'name')
        );

        if($result === false) {
            return responseJsonData(false, []);
        }

        return responseJsonData(true, $result);
    }

    public function renameFolder(RenameFolderRequest $request)
    {
        $data = $request->only(['id', 'name']);
        $result = $this->folderFileService->renameFolder(
            Arr::get($data, 'id'),
            Arr::get($data, 'name')
        );

        if($result === false) {
            return responseJsonData(false, []);
        }

        return responseJsonData(true, $result);
    }

    public function deleteFolder(int $id)
    {
        $result = $this->folderFileService->deleteFolder($id);

        if($result === false) {
            return responseJsonData(false, []);
        }

        return responseJsonData(true, $result);
    }

    public function uploadFile(int $id, UploadFileRequest $request)
    {
        $result = $this->folderFileService->uploadFile($id, $request->file('file'));

        if($result === false) {
            return responseJsonData(false, []);
        }

        return responseJsonData(true, ['file' => $result]);
    }

    public function renameFile(RenameFileRequest $request)
    {
        $data = $request->only(['id', 'name']);
        $result = $this->folderFileService->renameFile($data['id'], $data['name']);

        if($result === false) {
            return responseJsonData(false, []);
        }

        return responseJsonData(true, ['file' => $result]);
    }

    public function deleteFile($id)
    {
        $result = $this->folderFileService->deleteFile($id);

        if($result === false) {
            return responseJsonData(false, []);
        }

        return responseJsonData(true, ['file' => $result]);
    }
}
