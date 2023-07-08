import Route from "./route";

const folderContent = {
    ...Route.admin('file-manager.folder-content'),
    token: true,
    callback: function(id = 0) {
        this.urlParam('{id}', id);
    }
};
const folderStaticContent = {
    ...Route.admin('file-manager.folder-static-content'),
    token: true,
    callback: function(id = 0) {
        this.urlParam('{id}', id);
    }
};
const createFolder = {
    ...Route.admin('file-manager.create-folder'),
    token: true,
    callback: function(folder_id, name) {
        this.data({
            folder_id,
            name
        });
    }
};
const renameFolder = {
    ...Route.admin('file-manager.rename-folder'),
    token: true,
    callback: function(id, name) {
        this.data({
            id,
            name
        });
    }
};
const deleteFolder = {
    ...Route.admin('file-manager.delete-folder'),
    token: true,
    callback: function(id = 0) {
        this.urlParam('{id}', id);
    }
};

const uploadFile = {
    ...Route.admin('file-manager.upload-file'),
    token: true,
    callback: function(folderId, file) {
        this.urlParam('{id}', folderId);
        this.headers({
            "Content-Type": "multipart/form-data"
        });
        const formData = new FormData();
        formData.append("file", file);
        this.setData(formData);
    }
};

const renameFile = {
    ...Route.admin('file-manager.rename-file'),
    token: true,
    callback: function(id, name) {
        this.data({
            id,
            name
        });
    }
};
const deleteFile = {
    ...Route.admin('file-manager.delete-file'),
    token: true,
    callback: function(id = 0) {
        this.urlParam('{id}', id);
    }
};

export default {
    folderContent,
    folderStaticContent,
    createFolder,
    renameFolder,
    deleteFolder,
    uploadFile,
    renameFile,
    deleteFile
};
