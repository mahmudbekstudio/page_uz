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
                :label="$t(label)"
                prepend-icon="mdi-clock-time-four-outline"
                readonly
                v-bind="attrs"
                v-on="on"
                :append-icon="appendIcon"
                @click:append="appendClick"
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
                {{$t('words.cancel')}}
            </v-btn>
            <v-btn
                text
                color="primary"
                @click="$refs.menu.save(picker)"
            >
                {{$t('words.ok')}}
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
        appendIcon () {
            return this.picker ? 'close' : '';
        }
    },
    methods: {
        appendClick () {
            this.picker = '';
        }
    },
    components: {
        clock,
    }
}
</script>
