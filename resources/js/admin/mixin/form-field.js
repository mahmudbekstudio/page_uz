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
        textDataValue: {
            get: function () {
                if (typeof this.value === 'object' && this.value !== null) {
                    if (this.value[this.$i18n.locale]) {
                        return this.value[this.$i18n.locale];
                    }

                    const values = Object.values(this.value);

                    if (values.length) {
                        return values[0];
                    }

                    return null;
                }

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
