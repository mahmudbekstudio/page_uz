<template>
    <v-time-picker
        format="24hr"
        scrollable
        v-model="picker"
        :readonly="readonly"
        :min="min"
        :max="max"
        :full-width="fullWidth"
        :elevation="elevation"
        :disabled="disabled"
    >
        <v-spacer></v-spacer>
        <slot />
    </v-time-picker>
</template>
<script>
import props from "./props";

export default {
    props: {
        ...props.clock
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
