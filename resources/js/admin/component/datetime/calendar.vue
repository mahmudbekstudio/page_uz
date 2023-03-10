<template>
    <v-date-picker
        v-model="picker"
        scrollable
        show-adjacent-months
        :full-width="fullWidth"
        :type="type"
        :readonly="readonly"
        :range="range"
        :multiple="multiple"
        :min="min"
        :max="max"
        :locale="locale"
        :first-day-of-week="firstDayOfWeek"
        :elevation="elevation"
        :disabled="disabled"
        :active-picker="activePicker"
        :allowed-dates="allowedDates"
        :event-color="eventColor"
        :events="events"
    >
        <v-spacer></v-spacer>
        <slot />
    </v-date-picker>
</template>
<script>
import props from "./props";

export default {
    props: {
        ...props.calendar
    },
    computed: {
        picker: {
            get() {
                return this.value;
            },
            set(newValue) {
                this.$emit('input', newValue);
            }
        },
        elevation () {
            return this.flat ? undefined : 3;
        }
    }
}
</script>
