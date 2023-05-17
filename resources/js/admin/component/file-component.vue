<template>
    <div class="file-component">
        <label
                class="file-label"
                v-show="!(value.length === 1 && !multiple)"
        >
            <slot name="button">
                <v-btn color="default" @click.prevent="selectFile"><v-icon v-html="uploadIcon"></v-icon>{{$t(title)}}</v-btn>
            </slot>
            <input
                    class="hidden"
                    :accept="accept.join(',')"
                    type="file"
                    id="file"
                    ref="file"
                    :multiple="multiple"
                    v-on:change="handleFileUpload()"
            />
        </label>
        <div :class="listClass">
            <template v-for="item in value" v-bind:item="item">
                <div class="file-item" :title="$t('defect.remove')" @click="removeItem(item)"><v-icon>mdi-file</v-icon> {{item.name}}</div>
            </template>
        </div>
    </div>
</template>
<script>
    import * as _ from 'lodash';
    export default {
        name: 'file-component',
        props: {
            accept: {
                type: Array,
                default() {
                    return ['*/*'];
                }
            },
            extList: {
                type: Array,
                default() {
                    return [];
                }
            },
            title: {
                type: String,
                default() {
                    return this.multiple ?
                        'translations.words.select-files' :
                        'translations.words.select-file';
                }
            },
            value: {
                type: Array,
                default: []
            },
            multiple: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            listClass: {
                type: String,
                default() {
                    return 'items-list';
                }
            }
        },
        computed: {
            uploadIcon() {
                return this.multiple ? 'mdi-upload-multiple' : 'mdi-upload'
            },
        },
        methods: {
            selectFile() {
                this.$refs.file.click();
            },
            handleFileUpload() {
                let result = [];
                let files = [];
                if(this.extList.length) {
                    //
                } else {
                    files = this.$refs.file.files;
                }

                if(files.length) {
                    result = _.clone(this.value);
                    _.forEach(files, item => result.push(item));
                    this.emitResult(result);
                }

                this.$refs.file.value = null;
            },
            emitResult(result) {
                this.$logger.info('selected files', result);
                this.$emit('input', result);
            },
            removeItem(selectedItem) {
                const result = this.value.filter(item => item !== selectedItem);
                this.emitResult(result);
            }
        }
    }
</script>
