export default {
    props: {
        title: {
            type: String,
            default() {
                return this.multiple ? 'filemanager.select_files' : 'filemanager.select_file';
            }
        },
        multiple: {
            type: Boolean,
            default() {
                return false
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
    },
    computed: {
        dataValue: {
            get: function () {
                return this.value || [];
            },
            set: function (newValue) {
                this.$emit('input', newValue);
            }
        }
    }
}
