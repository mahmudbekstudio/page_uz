<template>
    <v-container>
        <v-row no-gutters>
            <v-col
                cols="12"
                sm="4"
            >
                <datepicker
                    v-bind="$props"
                    v-model="date"
                    :label="$t(label[0])"
                />
            </v-col>
            <v-col
                cols="12"
                sm="4"
            >
                <timepicker
                    v-bind="$props"
                    v-model="time"
                    :label="$t(label[1])"
                />
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import datepicker from "./datepicker";
import timepicker from "./timepicker";
import props from './props';

export default {
    data () {
        return {
            time: null,
            date: null
        }
    },
    props: {
        ...props.calendar,
        ...props.clock,
        label: {
            type: Array,
            default () {
                return [
                    'words.components.date',
                    'words.components.time'
                ];
            }
        }
    },
    created() {
        this.valueChanged();
    },
    watch: {
        date (newValue, oldValue) {
            if (typeof newValue !== 'string') {
                newValue = newValue.join(',');
                oldValue = oldValue !== null ? oldValue.join(',') : null;
            }

            if (newValue !== oldValue) {
                this.changed();
            }
        },
        time (newValue, oldValue) {
            if (newValue !== oldValue) {
                this.changed();
            }
        },
        value () {
            this.valueChanged();
        }
    },
    methods: {
        changed() {
            let result;
            if (this.date) {
                if (typeof this.date === 'string') {
                    result = '';
                    result = this.date;

                    if (this.time) {
                        result += ' ' + this.time;
                    }
                } else {
                    result = [];
                    for (let date of this.date) {
                        if (this.time) {
                            date += ' ' + this.time;
                        }
                        result.push(date);
                    }
                }
            }

            this.$emit('input', result);
        },
        valueChanged () {
            if (this.value && this.value.length) {
                if (typeof this.value === 'string') {
                    const valueArr = this.value.split(' ');
                    this.date = valueArr.length >= 1 ? valueArr[0] : null;
                    this.time = valueArr.length >= 2 ? valueArr[1] : null;
                } else {
                    this.date = [];
                    for (let datetime of this.value) {
                        const valueArr = datetime.split(' ');
                        let date = valueArr.length >= 1 ? valueArr[0] : null;
                        this.time = valueArr.length >= 2 ? valueArr[1] : null;
                        this.date.push(date);
                    }
                }
            }
        }
    },
    components: {
        datepicker,
        timepicker,
    }
}
</script>
