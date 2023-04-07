<template>
    <v-menu
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        :return-value.sync="picker"
        transition="scale-transition"
        offset-y
        max-width="290px"
        min-width="auto"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="picker"
                :label="label"
                prepend-icon="mdi-calendar"
                readonly
                v-bind="attrs"
                v-on="on"
                :append-icon="appendIcon"
                @click:append="appendClick"
            ></v-text-field>
        </template>
        <calendar
            v-bind="$props"
            flat
            full-width
            @input="picker = $event"
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
        </calendar>
    </v-menu>
</template>
<script>
import calendar from "./calendar";
import props from "./props";

export default {
    data () {
        return {
            menu: false,
        }
    },
    props: {
        ...props.calendar,
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
        appendIcon () {
            return this.picker ? 'close' : '';
        }
    },
    methods: {
        appendClick () {
            this.picker = typeof this.picker === 'string' ? '' : [];
        }
    },
    components: {
        calendar,
    }
}
</script>
