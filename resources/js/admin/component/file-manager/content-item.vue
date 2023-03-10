<template>
    <div class="content-item" :class="{'disabled': isLoading || disabled}" @click="itemClick">
        <div class="item-check" v-if="value.type !== 'folder'">
            <v-icon v-if="value.item.selected">mdi-checkbox-marked</v-icon>
            <v-icon v-else>mdi-checkbox-blank</v-icon>
        </div>
        <div class="item-ico">
            <div
                v-if="isImage"
                class="item-image"
            >
                <img :src="imageUrl" />
            </div>
            <v-icon v-else>{{itemIco}}</v-icon>
        </div>
        <v-tooltip top>
            <template v-slot:activator="{ on }">
                <div class="item-name" v-on="on">{{value.item.name}}<span v-show="value.item.extension">.{{value.item.extension}}</span></div>
            </template>
            <span>{{value.item.name}}<span v-show="value.item.extension">.{{value.item.extension}}</span></span>
        </v-tooltip>
        <v-menu bottom left class="item-menu">
            <template v-slot:activator="{ on }">
                <v-icon v-on="on" class="sub-menu">mdi-dots-vertical</v-icon>
            </template>
            <v-list class="sub-menu-list">
                <v-list-item v-for="(item, ind) in getSubMenuList(value)" :key="ind" @click="subMenuClicked(item.value, value)">
                    <v-list-item-title>{{item.text}}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
        <Dialog
                :title="$t('filemanager.rename_folder')"
                :value="dialog.renameFolder.show"
                @input="!$event && renameFolderClose()"
                size="medium"
                :actions="renameFolderActions"
                :disabled="isLoading"
        >
            <v-form ref="renameFolderForm" v-model="dialog.renameFolder.valid">
                <FormField :value="dialog.renameFolder.name" :params="folderNameParams" :events="{'change' : renameFolderNameChanged, 'keyup': renameFolderKeyup}"></FormField>
            </v-form>
        </Dialog>
        <Dialog
                :title="$t('filemanager.rename_file')"
                :value="dialog.renameFile.show"
                @input="!$event && renameFileClose()"
                size="medium"
                :actions="renameFileActions"
                :disabled="isLoading"
        >
            <v-form ref="renameFileForm" v-model="dialog.renameFile.valid">
                <FormField :value="dialog.renameFile.name" :params="fileNameParams" :events="{'change' : renameFileNameChanged, 'keyup': renameFileKeyup}"></FormField>
            </v-form>
        </Dialog>
    </div>
</template>
<script>
    import * as constants from '../../constants';
    import Dialog from '../dialog-component';
    import FormField from '../form/field-component';
    import validation from '../../config/validation';
    import app from '../../service/app';
    import {mapGetters} from "vuex";
    export default {
        data() {
            return {
                renameFolderActions: [],
                renameFileActions: [],
                dialog: {
                    renameFolder: {
                        show: false,
                        name: '',
                        valid: false,
                    },
                    renameFile: {
                        show: false,
                        name: '',
                        valid: false,
                    },
                },
                folderNameParams: {
                    label: 'Name *',
                    placeholder: 'Enter Folder Name',
                    rules: [
                        validation.required('Name')
                    ]
                },
                fileNameParams: {
                    label: 'Name *',
                    placeholder: 'Enter File Name',
                    suffix: '',
                    rules: [
                        validation.required('Name')
                    ]
                },
            }
        },
        props: {
            value: {
                type: Object,
                default: {
                    type: null,
                    item: {}
                }
            },
            isLoading: {
                type: Boolean,
                default: false
            },
            disabled: {
                type: Boolean,
                default: false
            },
            selectedFolder: {
                type: Object,
                default() {
                    return {};
                }
            }
        },
        computed: {
            ...mapGetters({
                website: 'view/website',
            }),

            itemIco() {
                if(this.value.type === 'folder') {
                    return 'mdi-folder';
                }

                const type = constants.FILE_TYPES[this.value.item.extension] || constants.FILE_DEFAULT_TYPE;

                return constants.FILE_ICONS[type];
            },
            isImage() {
                return this.value.type === 'file' && constants.FILE_TYPES[this.value.item.extension] === 'image';
            },
            imageUrl() {
                if(!this.isImage) {
                    return false;
                }

                return this.website.fileBaseUrl + this.selectedFolder.path + '/' + this.value.item.name + '.' + this.value.item.extension;
            }
        },
        watch: {
            'dialog.renameFolder.show'(val) {
                if(!val) {
                    for(let i = 0; i < this.renameFolderActions.length; i++) {
                        this.renameFolderActions[i].disabled = false;
                    }
                }
            },
            'dialog.renameFile.show'(val) {
                if(!val) {
                    for(let i = 0; i < this.renameFileActions.length; i++) {
                        this.renameFileActions[i].disabled = false;
                    }
                }
            },
            isLoading(val) {
                if(this.dialog.renameFolder.show) {
                    for(let i = 0; i < this.renameFolderActions.length; i++) {
                        this.renameFolderActions[i].disabled = !!val;
                    }
                }

                if(this.dialog.renameFile.show) {
                    for(let i = 0; i < this.renameFileActions.length; i++) {
                        this.renameFileActions[i].disabled = !!val;
                    }
                }
            },
        },
        created() {
            this.renameFolderActions.push({
                color: 'default',
                text: this.$t('words.close'),
                disabled: false,
                click: () => this.renameFolderClose()
            });
            this.renameFolderActions.push(
                {
                    color: 'primary',
                    text: this.$t('words.save'),
                    disabled: false,
                    click: () => this.renameFolderSave()
                }
            );

            this.renameFileActions.push({
                color: 'default',
                text: this.$t('words.close'),
                disabled: false,
                click: () => this.renameFileClose()
            });
            this.renameFileActions.push(
                {
                    color: 'primary',
                    text: this.$t('words.save'),
                    disabled: false,
                    click: () => this.renameFileSave()
                }
            );
        },
        methods: {
            renameFolderKeyup: function(e) {
                let key = e.which || e.keyCode || 0;
                if(e instanceof KeyboardEvent && key === 13) {
                    this.renameFolderSave();
                }
            },
            renameFileKeyup(e) {
                let key = e.which || e.keyCode || 0;
                if(e instanceof KeyboardEvent && key === 13) {
                    this.renameFileSave();
                }
            },
            renameFolderNameChanged: function (val) {
                this.dialog.renameFolder.name = val;
            },
            renameFileNameChanged(val) {
                this.dialog.renameFile.name = val;
            },
            renameFolderClose() {
                this.dialog.renameFolder.name = '';
                this.dialog.renameFolder.show = false;
                this.$refs.renameFolderForm.reset();
                this.$emit('onRenameFolderClose');
            },
            renameFileClose() {
                this.dialog.renameFile.name = '';
                this.dialog.renameFile.show = false;
                this.$refs.renameFileForm.reset();
                this.$emit('onRenameFileClose');
            },
            renameFolderSave() {
                this.$refs.renameFolderForm.validate();
                if(!this.dialog.renameFolder.valid) {
                    app.errorMessage('Form is not valid');
                    return false;
                }
                this.$emit('onRenameFolderSave', this.dialog.renameFolder);
            },
            renameFileSave() {
                this.$refs.renameFileForm.validate();
                if(!this.dialog.renameFile.valid) {
                    app.errorMessage('Form is not valid');
                    return false;
                }
                this.$emit('onRenameFileSave', this.dialog.renameFile);
            },
            getSubMenuList(item) {
                const result = [];

                if(item.type === 'folder') {
                    result.push({
                        text: 'Open',
                        value: 'open'
                    });
                    result.push({
                        text: 'Rename',
                        value: 'rename'
                    });
                    result.push({
                        text: 'Delete',
                        value: 'delete'
                    });
                } else {
                    result.push({
                        text: 'Select',
                        value: 'select'
                    });
                    /*if(constants.FILE_TYPES[this.value.item.extension] === 'image') {
                        result.push({
                            text: 'Open image',
                            value: 'open_image'
                        });
                    }*/
                    result.push({
                        text: 'Rename',
                        value: 'rename'
                    });
                    result.push({
                        text: 'Delete',
                        value: 'delete'
                    });
                }

                return result;
            },
            subMenuClicked(event, val) {
                if(val.type === 'folder') {
                    switch (event) {
                        case 'open':
                            this.itemClick();
                            break;
                        case 'rename':
                            this.dialog.renameFolder.name = this.value.item.name;
                            this.dialog.renameFolder.show = true;
                            break;
                        case 'delete':
                            app.openConfirm(
                                'Do you really want to delete folder ' + '"' + this.value.item.name + '"?',
                                () => this.$emit('onDeleteFolder')
                            );
                            break;

                    }
                } else {
                    switch (event) {
                        case 'select':
                            this.itemClick();
                            break;
                        case 'open_image':
                            //TODO: open image in new tab
                            //window.open(url, '_blank').focus();
                            break;
                        case 'rename':
                            this.dialog.renameFile.name = this.value.item.name;
                            this.fileNameParams.suffix = '.' + this.value.item.extension;
                            this.dialog.renameFile.show = true;
                            break;
                        case 'delete':
                            app.openConfirm(
                                'Do you really want to delete file ' + '"' + this.value.item.name + '.' + this.value.item.extension + '"?',
                                () => this.$emit('onDeleteFile')
                            );
                            break;

                    }
                }
            },
            itemClick() {
                if(this.disabled) return false;

                this.value.item.selected = !this.value.item.selected;
                this.$emit('input', this.value);
            }
        },
        components: {
            Dialog,
            FormField
        }
    }
</script>
<style scoped lang="scss">
    .content-item {
        display: inline-block;
        cursor: pointer;
        border-radius: 3px;
        padding: 5px;
        border: 1px solid #ddd;
        width: 90px;
        height: 90px;
        margin: 3px;
        position: relative;
        vertical-align: middle;
        &.disabled {
            &:before {
                content: "";
                width: 100%;
                height: 100%;
                display: block;
                position: absolute;
                left: 0;
                top: 0;
                cursor: default;
                z-index: 10;
                background-color: silver;
                opacity: .4;
            }
            &:hover {
                background: none;
            }
        }
        &:hover {
            background-color: #eee;
        }
        .item-check {
            position: absolute;
            left: 5px;
            top: 0;
            font-size: 18px;
            z-index: 9;
        }
        .item-ico {
            width: 100%;
            height: 60px;
            text-align: center;
            overflow: hidden;
            .v-icon {
                font-size: 60px;
                line-height: 60px;
            }
            img {
                max-width: 100%;
                max-height: 100%;
            }
        }
        .item-name {
            width: 100%;
            height: 20px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: default;
            z-index: 1000;
            position: relative;
        }
        .item-menu {
            position: absolute;
            right: -6px;
            top: 1px;
        }
        .v-icon.sub-menu {
            position: absolute;
            top: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 50%;
        }
    }
</style>
