<template>
    <div class="page-footer" v-if="actions.length">
    	<slot></slot>
    	<div class="flex-grow-1"></div>
    	<v-btn v-for="(btn, i) in actions" class="hidden-sm-and-down" :key="i" v-on="btn.on" v-bind="btn.bind">
            <v-icon v-if="btn.icon">{{btn.icon}}</v-icon>
            {{$t(btn.title)}}
        </v-btn>
    </div>
</template>
<script>
    export default {
        props: {
            actions: {
                type: Array,
                default: []
            }
        },
        computed: {
            //
        }
    }
</script>
