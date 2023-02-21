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
        }
    }
}
