<template>
    <div class="form-constructor-components-list">
        <v-tabs
            v-model="currentTab"
            align-with-title
            v-if="showTabs"
        >
            <v-tabs-slider color="yellow" class="tab-active-line"></v-tabs-slider>
            <v-tab
                v-for="(components, key) in list"
                :key="'tab' + key"
                class="constructor-tab-header"
                v-if="components.length"
            >{{$t('words.' + key)}}</v-tab>
        </v-tabs>
        <v-tabs-items v-model="currentTab">
            <v-tab-item
                v-for="(components, key) in list"
                :key="'tab' + key"
            >
                <draggable v-bind="dragOptions" v-model="list[key]">
                    <transition-group type="transition">
                        <element-cover
                            v-for="element in components"
                            :key="'element-cover-' + element.id"
                            :element="element"
                        ></element-cover>
                    </transition-group>
                </draggable>
            </v-tab-item>
        </v-tabs-items>
    </div>
</template>
<script>
import draggable from 'vuedraggable';
import elementCover from "./element/element-cover";

export default {
    data () {
        return {
            currentTab: 0,
            dragOptions: {
                animation: 200,
                group: {
                    name: 'cell-element',
                    pull: 'clone',
                    put: false,
                },
                disabled: false,
                ghostClass: 'ghost',
                sort: false,
            },
        }
    },
    computed: {
        showTabs () {
            const result = [];

            for (const tabKey in this.list) {
                if (this.list[tabKey].length) {
                    result.push(tabKey)
                }
            }

            if (!result[this.currentTab]) {
                this.currentTab = 0;
            }

            return result.length > 1;
        }
    },
    props: {
        list: {
            type: Object,
            default () {
                return {};
            }
        }
    },
    components: {
        draggable,
        elementCover,
    }
}
</script>
