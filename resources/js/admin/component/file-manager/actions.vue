<template>
    <div class="file-manager-actions">
        <div class="actions">
            <v-btn-toggle multiple active-class="custom-toggle">
                <v-btn v-if="canAddFile" :disabled="isLoading" text depressed small @click="filePlusClicked">
                    <v-icon>mdi-file-plus</v-icon>
                </v-btn>
                <v-btn v-if="canAddFolder" :disabled="isLoading" text depressed small @click="folderPlusClicked">
                    <v-icon>mdi-folder-plus</v-icon>
                </v-btn>
                <v-btn :disabled="isLoading" text depressed small :class="{'v-btn--active': selectedFilesShow}" @click="showSelectedFiles">
                    <v-icon>mdi-order-bool-ascending-variant</v-icon>
                </v-btn>
            </v-btn-toggle>
        </div>
        <div class="selected-files" id="filemanager-action-selected-files" v-show="selectedFilesShow">
            <v-divider></v-divider>
            <div class="selected-files-list">
                <div v-if="!files.length" class="selected-list-empty">{{$t('words.empty')}}</div>
                <v-menu bottom left v-for="file in files" :key="file.item.id">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" text depressed small class="v-btn--active">
                            <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon v-bind="attrs" v-on="on">{{itemIco(file.item.extension)}}</v-icon>
                                </template>
                                <span>{{file.item.name}}<span v-show="file.item.extension">.{{file.item.extension}}</span></span>
                            </v-tooltip>
                        </v-btn>
                    </template>
                    <v-list class="sub-menu-list">
                        <!--v-list-item @click="gotoFolder(file)"><v-list-item-title>Go to folder</v-list-item-title></v-list-item-->
                        <v-list-item @click="unselect(file)"><v-list-item-title>{{ $t('words.unselect') }}</v-list-item-title></v-list-item>
                    </v-list>
                </v-menu>
            </div>
        </div>

        <Dialog
                :title="$t('filemanager.create_folder')"
                :value="dialog.folderCreate.show"
                @input="!$event && createFolderClose()"
                size="medium"
                :actions="createFolderActions"
                :disabled="isLoading"
        >
            <v-form ref="createFolderForm" v-model="dialog.folderCreate.valid">
                <FormField :value="dialog.folderCreate.name" :params="folderNameParams" :events="{'change' : folderCreateNameChanged, 'keyup': createFolderKeyup}"></FormField>
            </v-form>
        </Dialog>
        <Dialog
                :title="$t('filemanager.upload_file')"
                :value="dialog.uploadFile.show"
                @input="!$event && uploadFileClose()"
                size="medium"
                :actions="uploadFileActions"
                :disabled="isLoading"
        >
            <v-form ref="uploadFileForm" class="file-upload-form" v-model="dialog.uploadFile.valid">
                <div class="selected-files-list">
                    <div
                            v-for="(file, ind) in selectedFiles"
                            :key="ind"
                            class="selected-file"
                            :class="{'not-supported-file': !file.isSupport, 'uploading-error': file.error, 'uploaded-success': file.uploaded && !file.error}"
                    >
                        <v-icon v-show="!file.uploaded && !file.uploading && file.isSupport" class="remove-selected-file" @click="removeSelectedFile(ind)">{{$t('words.close')}}</v-icon>
                        <v-icon v-show="file.uploaded && !file.error">check</v-icon>
                        <v-progress-circular :size="24" :width="2" v-show="file.uploading" indeterminate></v-progress-circular>
                        <v-icon v-show="!file.isSupport || file.error" @click="removeSelectedFile(ind)">{{ $t('words.cancel') }}</v-icon>
                        <v-icon>{{ itemIco(file.extension) }}</v-icon>
                        <span>{{ file.fullName }}</span>
                    </div>
                </div>
                <v-btn block color="primary" @click="$refs.selectFile.click()">{{ $t('words.select_file') }}</v-btn>
                <input
                        type="file"
                        ref="selectFile"
                        @change="changeSelectFile"
                        multiple
                        class="select-file"
                        :accept="accept"
                />
            </v-form>
        </Dialog>
    </div>
</template>
<script>
    import app from '../../service/app';
    import validation from '../../config/validation';
    import Dialog from '../dialog-component';
    import FormField from '../form/field-component';
    import * as constants from '../../constants';
    import http from '../../service/http';
    import filemanagerApi from '../../api/file-manager';
    import {FILE_TYPES} from "../../constants";

    export default {
        data: function() {
            return {
                uploadFileActions: [],
                createFolderActions: [],
                selectedFilesShow: false,
                dialog: {
                    folderCreate: {
                        show: false,
                        name: '',
                        valid: false,
                    },
                    uploadFile: {
                        show: false,
                        files: [],
                        valid: false,
                    }
                },
                folderNameParams: {
                    label: 'words.name',
                    placeholder: 'words.enter_folder_name',
                    rules: [
                        validation.required('words.name')
                    ]
                },
                selectedFiles: []
            }
        },
        props: {
            isLoading: {
                type: Boolean,
                default: false
            },
            fileType: {
                type: String,
                default: constants.FILE_DEFAULT_TYPE
            },
            selectedFolderId: {
                type: Number,
                default: 0
            },
            files: {
                type: Array,
                default() {
                    return []
                }
            },
            canAddFile: {
                type: Boolean,
                default () {
                    return true;
                }
            },
            canAddFolder: {
                type: Boolean,
                default () {
                    return true;
                }
            },
        },
        computed: {
            accept () {
                const list = [];
                for (let key in constants.FILE_TYPES) {
                    if (constants.FILE_TYPES[key] === this.fileType) {
                        list.push('.' + key);
                    }
                }
                return list.length ? list.join(',') : '*/*';
            }
        },
        watch: {
            'dialog.folderCreate.show'(val) {
                if(!val) {
                    for(let i = 0; i < this.createFolderActions.length; i++) {
                        this.createFolderActions[i].disabled = false;
                    }
                }
            },
            'dialog.uploadFile.show'(val) {
                if(!val) {
                    for(let i = 0; i < this.uploadFileActions.length; i++) {
                        this.uploadFileActions[i].disabled = false;
                    }
                }
            },
            isLoading(val) {
                if(this.dialog.folderCreate.show) {
                    for(let i = 0; i < this.createFolderActions.length; i++) {
                        this.createFolderActions[i].disabled = !!val;
                    }
                }

                if(this.dialog.uploadFile.show) {
                    for(let i = 0; i < this.uploadFileActions.length; i++) {
                        this.uploadFileActions[i].disabled = !!val;
                    }
                }
            },
            selectedFiles(val) {
                for(let i = 0; i < this.uploadFileActions.length; i++) {
                    if(this.uploadFileActions[i].text === this.$t('words.upload')) {
                        this.uploadFileActions[i].disabled = !val.length;
                    }
                }
            }
        },
        created() {
            this.createFolderActions.push({
                    color: 'default',
                    text: this.$t('words.close'),
                    disabled: false,
                    click: () => this.createFolderClose()
                });
            this.createFolderActions.push(
                {
                    color: 'primary',
                    text: this.$t('words.save'),
                    disabled: false,
                    click: () => this.createFolderSave()
                }
            );
            this.uploadFileActions.push({
                color: 'default',
                text: this.$t('words.close'),
                disabled: false,
                click: () => this.uploadFileClose()
            });
            this.uploadFileActions.push({
                color: 'primary',
                text: this.$t('words.upload'),
                disabled: true,
                click: () => this.uploadFileSave()
            });
        },
        methods: {
            gotoFolder(file) {
                this.$emit('onGotoFolder', file);
            },
            unselect(file) {
                this.$emit('onUnselect', file);
            },
            itemIco(ext) {
                const type = constants.FILE_TYPES[ext] || constants.FILE_DEFAULT_TYPE;
                return constants.FILE_ICONS[type];
            },
            filePlusClicked: function () {
                this.dialog.uploadFile.show = true;
            },
            folderPlusClicked: function () {
                this.dialog.folderCreate.show = true;
            },
            createFolderKeyup: function(e) {
                let key = e.which || e.keyCode || 0;
                if(e instanceof KeyboardEvent && key === 13) {
                    this.createFolderSave();
                }
            },
            createFolderSave: function () {
                this.$refs.createFolderForm.validate();
                if(!this.dialog.folderCreate.valid) {
                    app.errorMessage(this.$t('words.form_is_not_valid'));
                    return false;
                }
                this.$emit('onCreateFolderSave', this.dialog.folderCreate);
            },
            uploadFile(file, finallyCallback) {
                file.uploading = true;
                http(filemanagerApi.uploadFile)
                    .callback(this.selectedFolderId, file.file)
                    .send()
                    .then(response => {
                        if(response.data.result) {
                            file.uploaded = true;
                        } else {
                            file.uploaded = false;
                            file.error = true;
                        }
                    })
                    .catch(error => {
                        file.error = this.$t('words.error');
                        console.log(error);
                    })
                    .then(() => {
                        file.uploading = false;
                        finallyCallback(file);
                    })
            },
            uploadFileSave: function() {
                this.$refs.uploadFileForm.validate();
                if(!this.dialog.uploadFile.valid) {
                    app.errorMessage(this.$t('words.form_is_not_valid'));
                    return false;
                }
                let uploadingResult = [];
                let uploadingList = [];
                for(let i = 0; i < this.selectedFiles.length; i++) {
                    if(this.selectedFiles[i].isSupport && !this.selectedFiles[i].uploaded) {
                        uploadingList.push(this.selectedFiles[i]);
                    }
                }
                if(uploadingList.length) {
                    let fileItem = uploadingList.shift();
                    let callback = uploadedFile => {
                        if(uploadedFile.uploaded) {
                            uploadingResult.push(uploadedFile);
                        }
                        if(uploadingList.length) {
                            fileItem = uploadingList.shift();
                            this.uploadFile(fileItem, callback);
                        } else {
                            this.$emit('onUploadFileSave', uploadingResult);
                        }
                    };
                    this.uploadFile(fileItem, callback);
                }
            },
            createFolderClose: function () {
                this.dialog.folderCreate.name = '';
                this.dialog.folderCreate.show = false;
                this.$refs.createFolderForm.reset();
                this.$emit('onCreateFolderClose');
            },
            uploadFileClose: function () {
                this.dialog.uploadFile.files = [];
                this.dialog.uploadFile.show = false;
                this.selectedFiles = [];
                this.$refs.uploadFileForm.reset();
            },
            folderCreateNameChanged: function (val) {
                this.dialog.folderCreate.name = val;
            },
            showSelectedFiles: function () {
                this.selectedFilesShow = !this.selectedFilesShow;
                this.$emit('onShowSelectedFiles', this.selectedFilesShow)
            },
            changeSelectFile: function (e) {
                const files = e.target.files;
                if(files.length) {
                    for(let i = 0; i < files.length; i++) {
                        const fileFullName = files[i].name;
                        const lastDotIndex = fileFullName.lastIndexOf('.');
                        const fileExt = lastDotIndex > -1 ? fileFullName.slice(lastDotIndex + 1).toLowerCase() : '';
                        if(fileExt) {
                            this.selectedFiles.push({
                                fullName: fileFullName,
                                name: fileFullName.slice(0, lastDotIndex),
                                size: files[i].size,
                                extension: fileExt,
                                isSupport: !!constants.FILE_TYPES[fileExt],
                                icon: constants.FILE_ICONS[constants.FILE_TYPES[fileExt] || constants.FILE_DEFAULT_TYPE],
                                file: files[i],
                                uploading: false,
                                uploaded: false,
                                error: ''
                            });
                        }
                    }
                }
                e.target.value = '';
            },
            removeSelectedFile(ind) {
                this.selectedFiles.splice(ind, 1);
            }
        },
        components: {
            Dialog,
            FormField
        }
    }
</script>
<style scoped lang="scss">
    .selected-list-empty {
        line-height: 34px;
        padding-left: 10px;
        font-weight: bold;
    }
    .file-upload-form {
        margin-top: 20px;
    }
    .file-manager-actions {}
    .selected-files-list {
        padding: 0 3px;
        .v-btn {
            margin: 3px 3px 3px 0;
        }
    }
    .select-file {
        display: none;
    }
    .not-supported-file {
        span {
            color: red;
            text-decoration: line-through;
        }
    }
    .uploading-error {
        span {
            color: red;
            font-weight: bold;
        }
    }
    .uploaded-success {
        span {
            color: #1976d2;
            font-weight: bold;
        }
    }
    .remove-selected-file {
        cursor: pointer;
    }
    .selected-file {
        height: 29px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .actions {
        padding: 3px 0 1px 3px;
    }
    .custom-toggle {
        &:before {
            opacity: 0 !important;
        }
    }
</style>
