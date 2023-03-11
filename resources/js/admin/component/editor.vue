<template>
    <div>
        <tinyMceEditor
            :disabled="disabled"
            :init="initConfig"
            :inline="inline"
            :plugins1="plugins"
            :toolbar1="toolbar"
            v-model="content"
        />
        <dialog-component
            title=""
            v-model="showDialog"
            :actions="[]"
            :persistent="required"
            size="xlarge"
            without-padding
            class="editor-dialog"
        >
            <file-manager
                :show="showDialog"
                @input="saveSelected"
                @cancel="cancelSelected"
                :multiple="false"
                :count="1"
                :required="required"
                :file-type="selectedFileType"
            ></file-manager>
        </dialog-component>
    </div>
</template>
<script>
import dialogComponent from './dialog-component';
import * as constants from "../constants";
import {mapGetters} from "vuex";

export default {
    data () {
        return {
            showDialog: false,
            required: false,
            selectedFileType: constants.FILE_DEFAULT_TYPE,
            filePickerCallback: null,
            filePickerMeta: null,
        };
    },
    props: {
        disabled: {
            type: Boolean,
            default () {
                return false;
            }
        },
        init: {
            type: Object,
            default () {
                return {};
            }
        },
        inline: {
            type: Boolean,
            default () {
                return false;
            }
        },
        value: {
            type: String,
            default () {
                return '';
            }
        }
    },
    computed: {
        ...mapGetters({
            website: 'view/website',
        }),
        content: {
            get() {
                return this.value;
            },
            set(newValue) {
                this.$emit('input', newValue);
            }
        },
        initConfig () {
            this.init.menubar = '';
            this.init.plugins = this.plugins;
            this.init.toolbar = this.toolbar;
            this.init.file_picker_callback = (callback, value, meta) => {
                this.showDialog = true;
                this.filePickerCallback = callback;
                this.filePickerMeta = meta;

                /* Provide file and text for the link dialog */
                if (meta.filetype === 'file') {
                    this.selectedFileType = constants.FILE_DEFAULT_TYPE;
                }

                /* Provide image and alt text for the image dialog */
                if (meta.filetype === 'image') {
                    this.selectedFileType = constants.FILE_IMAGE_TYPE;
                }

                /* Provide alternative source and posted for the media dialog */
                if (meta.filetype === 'media') {
                    this.selectedFileType = constants.FILE_VIDEO_TYPE;
                }
            };
            return this.init;
        },
        plugins () {
            const list = [
                'lists',
                'link',
                'autolink',
                'anchor',
                'image',
                'media',
                'table',
                'charmap',
                'code',
                'codesample',
                'emoticons',
                'fullscreen',
                'insertdatetime'
            ];
            return list.join(' ');
        },
        toolbar () {
            return [
                'undo redo | bold italic underline strikethrough | fontselect fontsizeselect | fullscreen',
                'alignleft aligncenter alignright alignjustify | numlist bullist | outdent indent blockquote | forecolor backcolor removeformat',
                'link anchor | image media | table | charmap emoticons insertdatetime | code codesample'
            ];
        }
    },
    methods: {
        saveSelected(files) {
            const selectedFile = files[0];
            const selectedFilePath = this.website.fileBaseUrl + selectedFile.folderPath + '/' + selectedFile.name + '.' + selectedFile.extension;
            this.showDialog = false;
            /* Provide file and text for the link dialog */
            if (this.filePickerMeta.filetype === 'file') {
                this.filePickerCallback(selectedFilePath, { text: selectedFile.name });
            }

            /* Provide image and alt text for the image dialog */
            if (this.filePickerMeta.filetype === 'image') {
                this.filePickerCallback(selectedFilePath, { alt: selectedFile.name });
            }

            /* Provide alternative source and posted for the media dialog */
            if (this.filePickerMeta.filetype === 'media') {
                this.filePickerCallback(selectedFilePath);
            }
        },
        cancelSelected() {
            this.showDialog = false;
        },
    },
    components: {
        dialogComponent,
    }
}
</script>
<style>
.editor-dialog {
    z-index: 1301;
}
</style>
