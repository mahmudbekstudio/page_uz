export default {
    calendar: {
        label: {
            type: String,
            default () {
                return 'Date';
            }
        },
        value: {
            type: String|Array,
        },
        flat: {
            type: Boolean,
            default () {
                return false;
            }
        },
        firstDayOfWeek: {
            type: String|Number,
            default () {
                return 0;
            }
        },
        disabled: {
            type: Boolean,
            default () {
                return false;
            }
        },
        fullWidth: {
            type: Boolean,
            default () {
                return false;
            }
        },
        multiple: {
            type: Boolean,
            default () {
                return false;
            }
        },
        activePicker: {
            type: String,
            default () {
                return undefined;//'DATE', 'MONTH', 'YEAR'
            }
        },
        type: {
            type: String,
            default () {
                return 'date';// date, month
            }
        },
        locale: {
            type: String,
            default () {
                return undefined;
            }
        },
        min: {
            type: String,
            default () {
                return undefined;
            }
        },
        max: {
            type: String,
            default () {
                return undefined;
            }
        },
        readonly: {
            type: Boolean,
            default () {
                return false;
            }
        },
        range: {
            type: Boolean,
            default () {
                return false;
            }
        },
        allowedDates: {
            type: Function,
            default () {
                return val => true;
            }
        },
        eventColor: {
            type: Array | Function | Object | String,
            default () {
                return 'warning';
            }
        },
        events: {
            type: Array | Function | Object,
            default () {
                return null;
            }
        },
    },
    clock: {
        label: {
            type: String,
            default () {
                return 'Time';
            }
        },
        value: {
            type: String|Array,
        },
        readonly: {
            type: Boolean,
            default () {
                return false;
            }
        },
        min: {
            type: String,
            default () {
                return undefined;
            }
        },
        max: {
            type: String,
            default () {
                return undefined;
            }
        },
        fullWidth: {
            type: Boolean,
            default () {
                return false;
            }
        },
        flat: {
            type: Boolean,
            default () {
                return false;
            }
        },
        disabled: {
            type: Boolean,
            default () {
                return false;
            }
        },
    }
}
