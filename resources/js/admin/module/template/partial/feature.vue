<template>
    <div>
        <list-partial v-if="isList">
            <template v-slot:filter="props">
                <v-toolbar
                    flat
                    class="data-table-header"
                >
                    <v-toolbar-title>Feature</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-btn
                        depressed
                        color="primary"
                        @click="create"
                    >
                        {{$t('words.create')}}
                    </v-btn>
                </v-toolbar>
            </template>
        </list-partial>
        <dialog-component
            :title="dialog.title"
            v-model="dialog.show"
            :actions="dialog.actions"
            @close="gotoList()"
            fullscreen
            :overlay="isLoading"
        ><form-partial
            :id="id"
        /></dialog-component>
    </div>
</template>
<script>
import feature from '../../feature/feature';
export default {
    extends: feature,
    computed: {
        isList() {
            return this.$route.name.endsWith('.tab')
        },
    },
    methods: {
        create () {
            this.$router.push({name: 'template.create-feature'});
        },
        gotoList() {
            this.$router.push({name: 'template.tab', params: {tab: 'feature'}});
        },
    },
}
</script>
