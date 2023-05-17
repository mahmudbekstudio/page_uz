export default {
    props: {
        value: null,
        disabled: {
            type: Boolean,
            default: false
        },
        params: {
            type: Object,
            default: {}
        },
        events: {
            type: Object,
            default: {}
        }
    },
    computed: {
        dataValue: {
            get: function () {
                return this.value;
            },
            set: function (newValue) {
                this.$emit('input', newValue);
            }
        },
        labelText() {
            let label = this.params?.label ? this.$t(this.params.label) : null;

            if (label && this.params?.validation?.hasOwnProperty('required')) {
                label += ' *';
            }

            return label;
        }
    }
}
