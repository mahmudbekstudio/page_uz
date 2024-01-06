<template>
    <div class="template-list">
        <v-tabs align-with-title :value="tab">
            <v-tabs-slider color="yellow"></v-tabs-slider>
            <v-tab v-for="item in tabs" :key="item" @click="clickTab(item)">{{ $t('words.' + item) }}</v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item>
                <template-data-table type="layout" title="Layout" route="admin.template.listLayout"/>
            </v-tab-item>
            <v-tab-item>
                <template-data-table type="post" title="Post" route="admin.template.listPost"/>
            </v-tab-item>
            <v-tab-item>
                <template-data-table type="category" title="Category" route="admin.template.listCategory"/>
            </v-tab-item>
            <v-tab-item><feature /></v-tab-item>
            <v-tab-item eager><setting /></v-tab-item>
        </v-tabs-items>
    </div>
</template>
<script>
import templateDataTable from "./templateDataTable.vue";
import feature from "./feature.vue";
import setting from "./setting.vue";

export default {
    data () {
        return {
            tab: 0,
            tabs: [
                'layout',
                'post',
                'category',
                'feature',
                'setting'
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

                if (this.tab === -1) {
                    this.tab = 0;
                }
            }
        }
    },
    watch: {
        '$route.params.tab' (value) {
            this.initTab(value);
        },
        '$route.fullPath' (value) {
            console.log(value, this.tab);
            this.initTab(this.$route.params.tab);
        },
    },
    components: {
        templateDataTable,
        feature,
        setting,
    }
}
</script>
