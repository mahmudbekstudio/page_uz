<template>
    <div class="file-select-component">
        <div class="content-item" v-for="item in value">
            <div class="item-close" @click="removeItem(item)">
                <v-icon>mdi-close-circle</v-icon>
            </div>
            <div class="item-ico">
                <div
                    v-if="isImage(item)"
                    class="item-image"
                >
                    <img :src="imageUrl(item)" />
                </div>
                <v-icon v-else>{{itemIco(item.extension)}}</v-icon>
            </div>

            <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <div class="item-name" v-on="on">{{item.name}}<span v-show="item.extension">.{{item.extension}}</span></div>
                </template>
                <div class="item-name">{{item.name}}<span v-show="item.extension">.{{item.extension}}</span></div>
            </v-tooltip>
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
            <div class="item-name">{{$t(title || defaultTitleText)}}</div>
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
    import {mapGetters} from "vuex";
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
            ...mapGetters({
                website: 'view/website',
            }),
            defaultTitleText () {
                return this.multiple ? 'filemanager.select_files' : 'filemanager.select_file';
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
                this.$emit('input', this.value.filter(item => item.id !== selectedItem.id));
            },
            itemIco(ext) {
                const type = constants.FILE_TYPES[ext] || constants.FILE_DEFAULT_TYPE;
                return constants.FILE_ICONS[type];
            },
            saveSelected(files) {
                this.showDialog = false;
                this.$emit('input', files);
            },
            cancelSelected() {
                this.showDialog = false;
            },
            isImage(val) {
                return constants.FILE_TYPES[val.extension] === 'image';
            },
            imageUrl(val) {
                if(!this.isImage(val)) {
                    return false;
                }

                return this.website.fileBaseUrl + val.folderPath + '/' + val.name + '.' + val.extension;
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
            right: -12px;
            top: -15px;
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
        }
    }
</style>
