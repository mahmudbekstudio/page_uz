<template>
    <div class="component-file-manager" :class="{'nav-hidden': !showNav, 'selected-files': selectedFilesShow}">
        <v-progress-linear class="progress" v-show="isLoading" :indeterminate="true"></v-progress-linear>

        <div class="navigation">
            <v-tabs v-model="folderTab" class="navigation-tabs">
                <v-tabs-slider></v-tabs-slider>
                <v-tab><v-icon>mdi-folder-home</v-icon></v-tab>
                <v-tab><v-icon>mdi-folder-network</v-icon></v-tab>
            </v-tabs>
            <v-tabs-items v-model="folderTab">
                <v-tab-item :key="0">
                    <TreeView :items="navList" @onClick="navClicked" default-icon="mdi-folder" opened-icon="mdi-folder-open"></TreeView>
                </v-tab-item>
                <v-tab-item :key="1">
                    <TreeView :items="navStaticList" @onClick="navClicked" default-icon="mdi-folder" opened-icon="mdi-folder-open"></TreeView>
                </v-tab-item>
            </v-tabs-items>
        </div>
        <div class="toolbar">
            <v-btn small text fab @click.prevent="toggleNav">
                <v-icon>mdi-menu</v-icon>
            </v-btn>
            <breadcrumb :items="breadcrumbItems" @click="breadcrumbClicked"></breadcrumb>
            <v-divider></v-divider>
            <Actions
                    :is-loading="isLoading"
                    :file-type="fileType"
                    :selected-folder-id="selectedFolder.id"
                    :files="selectedFiles"
                    @onCreateFolderSave="createFolderSave"
                    @onShowSelectedFiles="showSelectedFiles"
                    @onUploadFileSave="uploadFileSave"
                    @onGotoFolder="actionGotoFolder($event)"
                    @onUnselect="actionUnselect($event)"
                    :can-add-file="!selectedFolder.is_local"
                    :can-add-folder="!selectedFolder.is_local"
            ></Actions>
        </div>
        <div class="content" id="filemanager-content">
            <div class="content-type-list">
                <ContentType
                        v-for="item in content"
                        :value="item"
                        :isLoading="isLoading"
                        :disabled="isDisabled(item)"
                        @onRenameFolderSave="renameFolderSave($event, item)"
                        @onDeleteFolder="deleteFolder(item)"
                        @onRenameFileSave="renameFileSave($event, item)"
                        @onDeleteFile="deleteFile(item)"
                        @input="itemSelected($event)"
                        :selectedFolder="selectedFolder"
                        :key="item.id"
                        :can-rename-folder="!selectedFolder.is_local"
                        :can-rename-file="!selectedFolder.is_local"
                        :can-delete-folder="!selectedFolder.is_local"
                        :can-delete-file="!selectedFolder.is_local"
                        :file-base-url="selectedFolder.is_local ? '' : website.fileBaseUrl"
                ></ContentType>
            </div>
        </div>
        <div class="footer">
            <v-spacer></v-spacer>
            <v-btn
                    color="default"
                    text
                    @click="cancelClick"
                    :disabled="actionBtnDisabled"
            >
                Cancel
            </v-btn>
            <v-btn
                    color="primary"
                    text
                    @click="saveClick"
                    :disabled="actionBtnDisabled"
            >
                Save
            </v-btn>
        </div>
    </div>
</template>
<script>
    const fileManagerContentType = function(type, item) {
        return {
            type: type,
            item: item
        };
    };

    const fileManagerFolder = function (folder) {
        return fileManagerContentType('folder', {
            name: folder.label,
            folder
        })
    };

    const fileManagerFile = function (id, folder_id, name, extension = '', size = 0, selected = false, is_local = false, folderPath = '') {
        return fileManagerContentType('file', {
            id,
            folder_id,
            name,
            extension,
            size,
            is_local,
            folderPath,
            selected
        })
    };

    /*const treeViewItem = function(id, label, children = [], active = false, opened = false, loading = false) {
        return {
            id: id,
            label: label,
            active: active,
            opened: opened,
            loading: loading,
            children: children,
            loaded: false
        };
    };*/

    const navItem = function (params) {
        const defaultParams = {
            id: 0,
            label: '',
            path: '',
            active: false,
            loading: false,
            opened: false,
            files: [],
            children: []
        };
        return Object.assign({}, defaultParams, params);
    };

    import breadcrumb from '../breadcrumb';
    import TreeView from '../tree-view/tree-view';
    import http from '../../service/http';
    import app from '../../service/app';
    import ContentType from './content-item';
    import Actions from './actions';
    import * as constants from '../../constants';
    import filemanagerApi from '../../api/file-manager';
    import {mapGetters} from 'vuex';
    export default {
        data: function() {
            return {
                folderTab: 0,
                inited: false,
                showNav: true,
                breadcrumbItems: [],
                navList: [],
                navStaticList: [],
                content: [],
                isLoading: false,
                isFolderLoading: false,
                isStaticFolderLoading: false,
                selectedFolder: {},
                selectedFilesShow: false,
                selectedFiles: [],
            }
        },
        props: {
            fileType: {
                type: String,
                default: constants.FILE_DEFAULT_TYPE
            },
            multiple: {
                type: Boolean,
                default() {
                    return false
                }
            },
            count: {
                type: Number,
                default () {
                    return 0;
                }
            },
            required: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            value: {
                type: Array,
                default() {
                    return [];
                }
            },
            show: {
                type: Boolean,
                default() {
                    return false;
                }
            }
        },
        watch: {
            /*selectedFolder: {
                handler: function (newVal, oldVal) {
                    this.updateSelectedFolder(newVal);
                },
                deep: false
            },*/
            value(newVal) {
                this.initSelectFiles(newVal);
            },
            show(val) {
                if(this.inited && val) {
                    this.init();
                }
            }
        },
        created() {
            //
        },
        mounted: function() {
            this.init();
        },
        computed: {
            ...mapGetters({
                website: 'view/website',
            }),
            actionBtnDisabled() {
                return this.required && !this.selectedFiles.length;
            },
            selectedFileIds() {
                const ids = [];
                this.selectedFiles.forEach(({item}) => ids.push(item.id));
                return ids;
            }
        },
        methods: {
            isDisabled (item) {
                return item.type !== 'folder' &&
                    constants.FILE_DEFAULT_TYPE !== this.fileType &&
                    constants.FILE_TYPES[item.item.extension] !== this.fileType;
            },
            initSelectFiles(val) {
                this.selectedFiles = val.map(file =>
                    fileManagerFile(
                        file.id,
                        file.folder_id,
                        file.name,
                        file.extension,
                        file.size,
                        false,
                        file.is_local,
                        file.folderPath
                    )
                );
            },
            init() {
                this.initSelectFiles(this.value);
                this.isLoading = true;
                this.loadFolderContent();
                this.loadStaticFolderContent();
            },
            loadFolderContent() {
                http(filemanagerApi.folderContent)
                    .callback(0)
                    .send()
                    .then(response => {
                        this.navList = [];
                        const foldersList = response.data.data.folder;
                        const rootFolder = navItem({
                            label: 'Home',
                            path: '/' + this.website.metas['root-folder-path'],
                            opened: true,
                            active: true,
                            is_local: false,
                            files: response.data.data.file
                        });

                        for(let i = 0; i < foldersList.length; i++) {
                            rootFolder.children.push(navItem({
                                label: foldersList[i].name,
                                id: foldersList[i].id,
                                path: foldersList[i].path,
                                is_local: false,
                            }));
                        }


                        this.navList.push(rootFolder);
                        this.updateSelectedFolder(rootFolder);
                        //this.selectedFolder = rootFolder;
                    })
                    .catch(error => {
                        console.log('error', error);
                        app.errorMessage(this.$t('words.error_loading_folder_failed'))
                    })
                    .then(() => {
                        this.isFolderLoading = false;
                        this.isLoading = this.isStaticFolderLoading;
                        this.inited = true;
                    });
            },
            loadStaticFolderContent() {
                http(filemanagerApi.folderStaticContent)
                    .callback(0)
                    .send()
                    .then(response => {
                        this.navStaticList = [];
                        const foldersList = response.data.data.folder;
                        const rootFolder = navItem({
                            label: 'Static',
                            path: '/',
                            opened: true,
                            active: false,
                            is_local: true,
                            files: response.data.data.file
                        });

                        for(let i = 0; i < foldersList.length; i++) {
                            rootFolder.children.push(navItem({
                                label: foldersList[i].name,
                                id: foldersList[i].id,
                                path: foldersList[i].path,
                                is_local: true,
                            }));
                        }

                        this.navStaticList.push(rootFolder);
                        //this.updateSelectedFolder(rootFolder);
                    })
                    .catch(error => {
                        console.log('error', error);
                        app.errorMessage(this.$t('words.error_loading_folder_failed'))
                    })
                    .then(() => {
                        this.isStaticFolderLoading = false;
                        this.isLoading = this.isFolderLoading;
                        this.inited = true;
                    });
            },
            actionGotoFolder(file) {
                console.log('file', file);
            },
            actionUnselect(file) {
                file.item.selected = false;
                this.selectedFiles = this.selectedFiles.filter(({item}) => item.id !== file.item.id);
            },
            deleteFile(item) {
                this.isLoading = true;
                http(filemanagerApi.deleteFile)
                    .callback(item.item.id)
                    .send()
                    .then(response => {
                        if(response.data.result) {
                            item.item.selected = false;
                            this.itemSelected(item);
                            app.openMessage(this.$t('words.file_deleted'));
                            this.updateContent(this.selectedFolder);
                        } else {
                            app.errorMessage(this.$t('words.error_delete_file_failed'))
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                        app.errorMessage(this.$t('words.error_delete_file_failed'))
                    })
                    .then(() => {
                        this.isLoading = false;
                    });
            },
            renameFileSave(renameFileForm, item) {
                this.isLoading = true;
                http(filemanagerApi.renameFile)
                    .callback(item.item.id, renameFileForm.name)
                    .send()
                    .then(response => {
                        if(response.data.result) {
                            app.openMessage(this.$t('words.file_name_changed'));
                            renameFileForm.show = false;
                            this.updateContent(this.selectedFolder);
                        } else {
                            app.errorMessage(this.$t('words.error_rename_file_failed'))
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                        app.errorMessage(this.$t('words.error_rename_file_failed'))
                    })
                    .then(() => {
                        this.isLoading = false;
                    });
            },
            deleteFolder(item) {
                this.isLoading = true;
                http(filemanagerApi.deleteFolder)
                    .callback(item.item.folder.id)
                    .send()
                    .then(response => {
                        if(response.data.result) {
                            app.openMessage(this.$t('words.folder_deleted'));
                            this.updateContent(this.selectedFolder);
                        } else {
                            app.errorMessage(this.$t('words.error_delete_folder_failed'))
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                        app.errorMessage(this.$t('words.error_delete_folder_failed'))
                    })
                    .then(() => {
                        this.isLoading = false;
                    });
            },
            renameFolderSave(renameFolderForm, item) {
                this.isLoading = true;
                http(filemanagerApi.renameFolder)
                    .callback(item.item.folder.id, renameFolderForm.name)
                    .send()
                    .then(response => {
                        if(response.data.result) {
                            app.openMessage(this.$t('words.folder_name_changed'));
                            renameFolderForm.show = false;
                            this.updateContent(this.selectedFolder);
                        } else {
                            app.errorMessage(this.$t('words.error_rename_folder_failed'))
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                        app.errorMessage(this.$t('words.error_rename_folder_failed'))
                    })
                    .then(() => {
                        this.isLoading = false;
                    });
            },
            uploadFileSave(list) {
                this.updateContent(this.selectedFolder);
            },
            updateSelectedFolder(node) {
                this.selectedFolder = node;
                this.setBreadcrumbs(node);
                this.content = [];

                for(let i = 0; i < node.children.length; i++) {
                    this.content.push(fileManagerFolder(node.children[i]));
                }

                for(let i = 0; i < node.files.length; i++) {
                    const file = node.files[i];
                    const selectedIndex = this.selectedFileIds.indexOf(file.id);

                    if(selectedIndex > -1) {
                        this.selectedFiles[selectedIndex].item.selected = true;
                        this.content.push(this.selectedFiles[selectedIndex]);
                    } else {
                        this.content.push(fileManagerFile(file.id, file.folder_id, file.name, file.extension, file.size, false, file.is_local, file.folderPath));
                    }
                }
            },
            cancelClick() {
                if(this.actionBtnDisabled) {
                    return false;
                }
                this.$emit('cancel');
            },
            saveClick() {
                if(this.actionBtnDisabled) {
                    return false;
                }
                this.$emit('input', this.selectedFiles.map(({item, folderPath}) => ({
                    extension: item.extension,
                    folder_id: item.folder_id,
                    folderPath: folderPath || item.folderPath,
                    id: item.id,
                    name: item.name,
                    is_local: item.is_local,
                    size: item.size
                })));
            },
            itemSelected(item) {
                if(item.type === 'folder') {
                    this.navClicked(item.item.folder);
                } else {
                    if(!this.multiple && this.selectedFiles.length) {
                        this.selectedFiles[0].item.selected = false;
                        this.selectedFiles = [];
                    }

                    if(item.item.selected) {
                        if (this.count && this.count <= this.selectedFiles.length) {
                            item.item.selected = false;
                            app.infoMessage(this.$t('filemanager.you_can_select_only_count', {count: this.count}));
                        } else {
                            item.folderPath = this.selectedFolder.path;
                            this.selectedFiles.push(item);
                        }
                    } else {
                        this.selectedFiles = this.selectedFiles.filter(sItem => sItem !== item);
                    }
                }
            },
            setBreadcrumbs: function(selectedFolder) {
                let result = [];
                let searchByTree = function (list, folder) {
                    const listLength = list.length;
                    for(let i = 0; i < listLength; i++) {
                        if(folder.id === list[i].id) {
                            result.push({title: folder.label, data: folder});
                            return true;
                        }
                        if(list[i].children && list[i].children.length) {
                            if(searchByTree(list[i].children, folder, result)) {
                                result.push({title: list[i].label, data: list[i]});
                                return true;
                            }
                        }
                    }
                    return false;
                };
                searchByTree(selectedFolder.is_local ? this.navStaticList : this.navList, selectedFolder);
                this.breadcrumbItems = result.reverse();
            },
            updateContent: function(node) {
                node.loading = true;
                http(filemanagerApi[node.is_local ? 'folderStaticContent' : 'folderContent'])
                    .callback(node.id)
                    .send()
                    .then(response => {
                        const foldersList = response.data.data.folder;
                        node.children = [];
                        node.files = response.data.data.file;

                        for(let i = 0; i < foldersList.length; i++) {
                            node.children.push(navItem({
                                label: foldersList[i].name,
                                id: foldersList[i].id,
                                path: foldersList[i].path,
                                is_local: foldersList[i].is_local,
                            }));
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                        app.errorMessage(this.$t('words.error_loading_folder_failed'))
                    })
                    .then(() => {
                        node.loading = false;
                        this.updateSelectedFolder(node);
                        //this.selectedFolder = node;
                    });
            },
            toggleNav: function () {
                this.showNav = !this.showNav;
            },
            breadcrumbClicked: function (item) {
                this.navClicked(item.data);
            },
            navClicked: function (node) {
                if(node.active) {
                    node.opened = !node.opened
                    return false;
                }
                node.opened = !node.opened ? true : !node.active;
                if(this.selectedFolder) {
                    this.selectedFolder.active = false;
                }
                node.active = true;
                if(!node.loaded) {
                    this.updateContent(node);
                }
            },
            createFolderSave: function(form) {
                this.isLoading = true;
                http(filemanagerApi.createFolder)
                    .callback(this.selectedFolder.id, form.name)
                    .send()
                    .then(response => {
                        if(response.data.result) {
                            form.show = false;
                            app.successMessage(this.$t('words.created'));
                            this.updateContent(this.selectedFolder);
                        } else {
                            app.errorMessage(this.$t('filemanager.folder_exist'));
                        }
                    })
                    .catch(error => {
                        app.errorMessage(this.$t('words.error'));
                    })
                    .then(() => {
                        this.isLoading = false;
                    })
            },
            showSelectedFiles: function (isShow) {
                setTimeout(() => {
                    const filemanagerContent = document.getElementById('filemanager-content');
                    if (isShow) {
                        filemanagerContent.style.paddingTop = document.getElementById('filemanager-action-selected-files').clientHeight + 78 + 'px';
                    } else {
                        filemanagerContent.style.paddingTop = '78px';
                    }
                }, 100);
                this.selectedFilesShow = isShow;
            }
        },
        components: {
            breadcrumb,
            TreeView,
            ContentType,
            Actions
        }
    }
</script>
<style scoped lang="scss">
    .component-file-manager {
        position: relative;
        min-height: 200px;
        display: flex;
        .progress {
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            z-index: 10;
            height: 5px !important;
            margin: 0;
        }
        .breadcrumbs {
            position: absolute;
            top: 0;
            left: 350px;
            right: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 22px;
        }
        &.nav-hidden {
            .navigation {
                display: none;
            }
            .toolbar {
                padding-left: 3px;
            }
            .content {
                padding-left: 3px;
            }
            .footer {
                left: 0;
            }
            .breadcrumbs {
                left: 50px;
            }
        }
        .navigation {
            height: 100%;
            max-height: calc(100% - 0px);
            width: 300px;
            position: absolute;
            background-color: #fff;
            left: 0;
            max-width: 100%;
            overflow-y: auto;
            overflow-x: hidden;
            top: 0;
            z-index: 3;
            border-right: 1px solid silver;

            .navigation-tabs::v-deep {
                position: sticky;
                top: 0;
                z-index: 1;

                .v-item-group {
                    background-color: #EEE;
                }
            }
        }
        .toolbar {
            padding-left: 300px;
            background-color: #f5f5f5;
            z-index: 2;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            border-bottom: 1px solid silver;
        }
        .content {
            padding: 78px 10px 49px 303px;
            width: 100%;
            .content-type-list {
                max-height: 385px;
                min-height: 192px;
                overflow: auto;
            }
        }
        &.selected-files {
            .content {
                //
            }
        }
        .footer {
            position: absolute;
            left: 300px;
            bottom: 0;
            right: 0;
            border-top: 1px solid silver;
            z-index: 3;
            text-align: right;
            padding: 5px 10px;
        }
    }
</style>
