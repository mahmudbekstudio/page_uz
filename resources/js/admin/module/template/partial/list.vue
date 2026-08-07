<template>
    <div class="template-list">
        <v-tabs align-with-title :value="tab">
            <v-tabs-slider color="yellow"></v-tabs-slider>
            <v-tab v-for="item in tabs" :key="item" @click="clickTab(item)">{{ $t('words.' + item) }}</v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item>
                <template-data-table type="layout" :title="$t('words.layout')" route="admin.template.listLayout"/>
            </v-tab-item>
            <v-tab-item>
                <template-data-table type="post" :title="$t('words.post')" route="admin.template.listPost"/>
            </v-tab-item>
            <v-tab-item>
                <template-data-table type="category" :title="$t('words.category')" route="admin.template.listCategory"/>
            </v-tab-item>
            <v-tab-item><feature /></v-tab-item>
        </v-tabs-items>
    </div>
</template>
<script>
import templateDataTable from "./templateDataTable.vue";
import feature from "./feature.vue";

export default {
    data () {
        return {
            tab: 0,
            tabs: [
                'layout',
                'post',
                'category',
                'features',
            ]
        }
    },
    created() {
        this.initTab(this.$route.params.tab);
    },
    methods: {
        clickTab (type) {
            if (this.tabs.indexOf(type) !== this.tab) {
                this.$router.push({name: 'template.tab', params: {tab: type}});
            }
        },
        initTab(tab) {
            tab = tab || this.tabs[0];

            if (this.$route.name === 'template.tab') {
                this.tab = this.tabs.indexOf(tab);
            } else if (this.$route.name.indexOf('feature') > -1) {
                this.tab = this.tabs.indexOf('features');
            }

            if (this.tab === -1) {
                this.tab = 0;
            }
        }
    },
    watch: {
        '$route.params.tab' (value) {
            this.initTab(value);
        },
        '$route.fullPath' (value) {
            this.initTab(this.$route.params.tab);
        },
    },
    components: {
        templateDataTable,
        feature,
    }
}
</script>
