<template>
    <div class="empty-layout">
        <slot name="header" v-if="inited"></slot>
        <v-main v-if="inited">
            <v-progress-linear class="progress" :active="isLoading" :fixed="true" :indeterminate="true"></v-progress-linear>
            <v-container
                    :class="{'fill-height': fillHeight}"
                    fluid
            >
                <slot></slot>
            </v-container>
        </v-main>
        <slot name="footer" v-if="inited"></slot>
        <v-snackbar
                v-model="snackbar.show"
                :color="snackbar.color"
                :timeout="snackbar.timeout"
                :absolut="viewConfig.snackbar.absolute"
                :bottom="viewConfig.snackbar.bottom"
                :left="viewConfig.snackbar.left"
                :multi-line="viewConfig.snackbar['multi-line']"
                :right="viewConfig.snackbar.right"
                :top="viewConfig.snackbar.top"
                :vertical="viewConfig.snackbar.vertical"
                class="layout-snackbar"
        >
            <v-btn
                    v-show="snackbar.showButton"
                    icon
                    @click="closeSnackbar"
            >
                <v-icon>mdi-close-box-outline</v-icon>
            </v-btn>
            <span v-html="snackbar.slot"></span>
        </v-snackbar>
        <confirm-component
                :value="confirm.show"
                :question="confirm.question"
                @onYesClick="confirm.yesCallback"
                @onNoClick="confirm.noCallback"
        ></confirm-component>
    </div>
</template>
<script>
    import {mapGetters, mapActions} from 'vuex';
    import viewConfig from '../../config/view';
    import ConfirmComponent from '../../component/confirm-component';

    export default {
        data: function () {
            return {
                viewConfig: viewConfig
            }
        },
        created() {
            this.$vuetify.theme.dark = this.isDark;
        },
        computed: {
            ...mapGetters({
                inited: 'setting/inited',
                isLoading: 'view/loading',
                isDark: 'view/isDark',
                snackbar: 'view/snackbar',
                fillHeight: 'view/containerFillHeight',
                confirm: 'view/confirm'
            })
        },
        methods: {
            ...mapActions({
                closeSnackbar: 'view/closeSnackbar',
            })
        },
        components: {
            ConfirmComponent
        }
    }
</script>
