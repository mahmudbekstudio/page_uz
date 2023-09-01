<template>
    <div class="style-constructor">
        <v-tabs
            v-model="currentTab"
            align-with-title
        >
            <v-tabs-slider color="yellow" class="tab-active-line"></v-tabs-slider>
            <v-tab
                v-for="(block, key) in blocks"
                :key="'tab' + key"
                class="constructor-tab-header"
            >{{$t('words.' + block.title)}}</v-tab>
        </v-tabs>
        <v-tabs-items v-model="currentTab">
            <v-tab-item
                v-for="(block, key) in blocks"
                :key="'tab' + key"
            >
                <v-container fluid>
                    <v-row
                        v-for="(item, index) of customStyles[block.id]"
                        :key="index"
                    >
                        <v-col cols="4" :class="{'is-first': !index}">
                            <v-text-field
                                v-model="item.selector"
                                clearable
                                :label="index ? 'Selector' : ''"
                                type="text"
                                :readonly="!index"
                            >
                                <template v-slot:prepend>
                                    <div class="text-nowrap">
                                        <v-btn
                                            fab
                                            small
                                            depressed
                                            v-if="index"
                                            @click="removeStyle(block.id, index)"
                                        >
                                            <v-icon dark>
                                                mdi-minus
                                            </v-icon>
                                        </v-btn>
                                        #{{block.id}}
                                    </div>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col cols="8">
                            <div>{</div>
                            <div>
                                <v-textarea
                                    label="List of styles"
                                    v-model="item.styles"
                                    hint="Every style in single line"
                                ></v-textarea>
                            </div>
                            <div>}</div>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-btn
                                block
                                @click="addStyle(block.id)"
                            >
                                Add new rule
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-tab-item>
        </v-tabs-items>
    </div>
</template>
<script>
export default {
    data () {
        return {
            currentTab: null,
            customStyles: {},
        }
    },
    computed: {
        //
    },
    props: {
        blocks: {
            type: Array,
            default () {
                return [];
            }
        },
    },
    created() {
        this.initCustomStyles(this.blocks);
        this.$emit('getStylesCallback', this.getStylesCallback)
    },
    methods: {
        getStylesCallback () {
            const result = {};

            for (const selector in this.customStyles) {
                result[selector] = {};
                let isFirst = true;

                for (const styleItem of this.customStyles[selector]) {
                    if (styleItem.selector || isFirst) {
                        result[selector][styleItem.selector] = styleItem.styles.split("\n");
                    }

                    isFirst = false;
                }
            }

            return result;
        },
        removeStyle (blockId, index) {
            this.customStyles[blockId].splice(index, 1);
        },
        addStyle (blockId) {
            this.customStyles[blockId].push({
                selector: '',
                styles: ''
            });
        },
        initCustomStyles (blocks) {
            const stylesList = {};

            for (const block of blocks) {
                const styles = [];
                let first = {};
                for (const selector in block.customStyles) {
                    if (!selector) {
                        first = {selector, styles: block.customStyles[selector].join("\n")};
                    } else if(selector) {
                        styles.push({selector, styles: block.customStyles[selector].join("\n")});
                    }
                }
                styles.unshift(first);
                stylesList[block.id] = styles;
            }

            this.customStyles = stylesList;
        }
    },
    watch: {
        blocks: {
            handler(val) {
                this.initCustomStyles(val);
            },
            deep: true
        },
    }
}
</script>
<style lang="scss">
.style-constructor {
    .is-first {
        .v-input__slot {
            display: none;
        }
    }
}
</style>
