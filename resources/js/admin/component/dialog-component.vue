<template>
    <div class="dialog-component">
        <v-dialog
                v-model="show"
                :disabled="disabled"
                :fullscreen="fullscreen"
                :persistent="persistent"
                :scrollable="scrollableValue"
                :width="width"
                :max-width="maxWidthValue"
                :retain-focus="false"
        >
            <v-card
                v-if="fullscreen"
                :class="classes + (overlay ? ' dialog-overlay' : '')"
                tile
                class="dialog-card"
            >
                <v-toolbar
                    flat
                    color="primary"
                    dark
                    max-height="64"
                >
                    <v-btn
                        icon
                        color="default"
                        @click="close"
                        v-if="showCloseButton"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <span class="text-h5">{{$t(title, titleValues)}}</span>
                    <div class="dialog-top"><slot name="top" /></div>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            v-for="(btn, i) in actions"
                            :key="i"
                            :color="btn.color === 'primary' ? 'default' : btn.color"
                            :disabled="btn.disabled"
                            text
                            @click="btn.click"
                            dark
                        >
                            {{$t(btn.text)}}
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text :class="{'without-padding': withoutPadding}">
                    <slot></slot>
                </v-card-text>

                <div style="flex: 1 1 auto;"></div>
            </v-card>
            <v-card v-else>
                <v-card-title v-if="title">
                    <span class="text-h5">{{$t(title)}}</span>
                    <v-spacer></v-spacer>
                    <v-btn
                        icon
                        color="default"
                        @click="close"
                        v-if="showCloseButton"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider v-if="title"></v-divider>
                <v-card-text :class="{'without-padding': withoutPadding}">
                    <slot></slot>
                </v-card-text>
                <v-divider v-if="actions.length"></v-divider>
                <v-card-actions v-if="actions.length">
                    <v-spacer></v-spacer>
                    <v-btn
                        v-for="(btn, i) in actions"
                        :key="i"
                        :color="btn.color"
                        text
                        @click="btn.click"
                        :disabled="btn.disabled"
                    >
                        {{$t(btn.text)}}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    const dialogSizes = {
        small: 300,
        medium: 600,
        large: 900,
        xlarge: 1200
    };
    export default {
        name: 'dialog-component',
        data() {
            return {
                showCloseButton: true,
                show: false,
                scrollableValue: false
            }
        },
        computed: {
            maxWidthValue() {
                return dialogSizes[this.size] || this.maxWidth;
            },
        },
        created() {
            this.show = this.value;
            this.scrollableValue = this.fullscreen || this.scrollable;
            this.showCloseButton = !this.persistent;
        },
        props: {
            classes: {
                type: String,
                default() {
                    return '';
                }
            },
            withoutPadding: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            actions: {
                type: Array,
                default() {
                    return [
                        {
                            color: 'default',
                            text: 'words.ok',
                            disabled: false,
                            click: () => this.show = false
                        }
                    ]
                }
            },
            title: {
                type: String,
                default() {
                    return '';
                }
            },
            titleValues: {
                type: Object,
                default() {
                    return {};
                }
            },
            value: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            disabled: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            overlay: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            fullscreen: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            persistent: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            scrollable: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            width: {
                type: [String, Number],
                default() {
                    return null;
                }
            },
            maxWidth: {
                type: [String, Number],
                default() {
                    return null;
                }
            },
            size: {
                type: String,
                default() {
                    return 'medium';//small, medium, large, xlarge
                }
            }
        },
        watch: {
            persistent(val) {
                this.showCloseButton = !val;
            },
            show(val) {
                this.$emit('input', val);

                if (!val) {
                    this.$emit('close');
                }
            },
            fullscreen(val) {
                this.scrollable = val;
            },
            value(val) {
                this.show = val;
            },
            scrollable(val) {
                this.scrollableValue = val;
            }
        },
        methods: {
            close() {
                this.show = false;
            }
        }
    }
</script>
<style scoped>
.dialog-overlay {
    position: relative;
}
.dialog-overlay:before {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    z-index: 2;
    background: rgba(0, 0, 0, .4)
}
    .dialog-card {
        overflow: auto;
        flex: 1 1 auto;
    }
    .without-padding {
        padding: 0 !important;
    }
    .dialog-top {
        margin-left: 20px;
    }
</style>
