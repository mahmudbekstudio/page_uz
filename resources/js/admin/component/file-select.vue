<template>
    <div class="file-select-component">
        <div class="content-item" v-for="item in value">
            <div class="item-close" @click="removeItem(item)">
                <v-icon>mdi-close-circle</v-icon>
            </div>
            <div class="item-ico">
                <v-icon>{{itemIco(item.extension)}}</v-icon>
            </div>
            <div class="item-name">{{item.name}}.{{item.extension}}</div>
        </div>
        <div
                v-if="showSelectBtn"
                class="content-item select-file-btn"
                @click="showDialog=true"
        >
            <div class="item-ico">
                <v-icon v-if="multiple">mdi-file-multiple</v-icon>
                <v-icon v-else>mdi-file</v-icon>
            </div>
            <div class="item-name">{{title || defaultTitleText}}</div>
        </div>
        <dialog-component
                title=""
                v-model="showDialog"
                :actions="[]"
                :persistent="required"
                size="xlarge"
                without-padding
        >
            <file-manager
                    :show="showDialog"
                    :value="value"
                    @input="saveSelected"
                    @cancel="cancelSelected"
                    :multiple="multiple"
                    :count="count"
                    :required="required"
                    :file-type="fileType"
            ></file-manager>
        </dialog-component>
    </div>
</template>
<script>
    import dialogComponent from './dialog-component';
    import fileManager from './file-manager/file-manager';
    import * as constants from '../constants';
    export default {
        name: 'file-select',
        data() {
            return {
                showDialog: false,
            }
        },
        props: {
            title: {
                type: String,
                default() {
                    return '';
                }
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
            fileType: {
                type: String,
                default () {
                    return constants.FILE_DEFAULT_TYPE
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
            }
        },
        computed: {
            defaultTitleText () {
                return this.multiple ? this.$t('filemanager.select_files') : this.$t('filemanager.select_file');
            },
            showSelectBtn() {
                if(this.multiple && (!this.count || this.count > this.value.length)) {
                    return true
                }

                return !this.value.length;
            }
        },
        methods: {
            removeItem(selectedItem) {
                console.log('removeItem', selectedItem);
                this.$emit('input', this.value.filter(item => item.id !== selectedItem.id));
            },
            itemIco(ext) {
                const type = constants.FILE_TYPES[ext] || constants.FILE_DEFAULT_TYPE;
                return constants.FILE_ICONS[type];
            },
            saveSelected(files) {
                console.log('files', files);
                this.showDialog = false;
                this.$emit('input', files);
            },
            cancelSelected() {
                this.showDialog = false;
            }
        },
        components: {
            dialogComponent,
            fileManager
        }
    }
</script>
<style scoped lang="scss">
    .select-file-btn {
        cursor: pointer;
        .item-name {
            text-align: center;
            cursor: pointer !important;
        }
        &:hover {
            background-color: #eee;
        }
    }
    .content-item {
        display: inline-block;
        border-radius: 3px;
        padding: 5px;
        border: 1px solid #ddd;
        width: 90px;
        height: 90px;
        margin: 3px;
        position: relative;
        vertical-align: middle;
        .item-close {
            position: absolute;
            right: 5px;
            top: 0;
            font-size: 18px;
            z-index: 10;
            cursor: pointer;
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
        }
        .item-name {
            width: 100%;
            height: 20px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: default;
        }
    }
</style>
