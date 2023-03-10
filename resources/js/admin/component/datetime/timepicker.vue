<template>
    <v-menu
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        :nudge-right="40"
        :return-value.sync="picker"
        transition="scale-transition"
        offset-y
        max-width="290px"
        min-width="290px"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="picker"
                :label="label"
                prepend-icon="mdi-clock-time-four-outline"
                readonly
                v-bind="attrs"
                v-on="on"
            ></v-text-field>
        </template>
        <clock
            v-if="menu"
            v-bind="$props"
            @input="picker = $event"
            flat
            full-width
        >
            <v-btn
                text
                color="primary"
                @click="menu = false"
            >
                Cancel
            </v-btn>
            <v-btn
                text
                color="primary"
                @click="$refs.menu.save(picker)"
            >
                OK
            </v-btn>
        </clock>
    </v-menu>
</template>
<script>
import clock from "./clock";
import props from "./props";

export default {
    data () {
        return {
            menu: false,
        }
    },
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
    },
    components: {
        clock,
    }
}
</script>
