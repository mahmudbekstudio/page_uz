<template>
    <v-menu
        offset-y
        v-model="colorPicker"
        :close-on-content-click="false"
        min-width="290px"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-bind="{...params, ...attrs}"
                v-on="{...events, ...on}"
                v-model="dataValue"
                :disabled="disabled"
                :hint="params?.hint ? $t(params.hint) : null"
                :label="labelText"
                :placeholder="params?.placeholder ? $t(params.placeholder) : null"
                type="text"
                readonly
                clearable
            >
                <template slot="prepend">
                    <div class="selected-color" :style="{'background-color': dataValue}"></div>
                </template>
            </v-text-field>
        </template>
        <v-card
            v-if="colorPicker"
            max-width="290px"
            height="335px"
            class="color-field"
        >
            <v-tabs v-model="colorTab">
                <v-tab key="canvas">Canvas</v-tab>
                <v-tab key="swatch">Swatch</v-tab>
            </v-tabs>

            <v-tabs-items v-model="colorTab">
                <v-tab-item key="canvas">
                    <v-color-picker
                        v-model="colorData"
                        dot-size="15"
                        hide-inputs
                        mode="hexa"
                    ></v-color-picker>
                </v-tab-item>
                <v-tab-item key="swatch">
                    <v-color-picker
                        v-model="colorData"
                        hide-canvas
                        hide-inputs
                        hide-sliders
                        mode="hexa"
                        show-swatches
                        swatches-max-height="226"
                    ></v-color-picker>
                </v-tab-item>
            </v-tabs-items>
            <v-card-actions>
                <v-btn
                    class="ma-1"
                    color="primary"
                    plain
                    @click="dataValue=colorData.hexa;colorPicker=false"
                >
                    Ok
                </v-btn>
                <v-btn
                    class="ma-1"
                    color="secondary"
                    plain
                    @click="colorPicker=false"
                >
                    Cancel
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-menu>
</template>
<script>
    import mixins from '../../../mixin';

    export default {
        data () {
            return {
                colorTab: 'canvas',
                colorPicker: false,
                colorData: null,
            }
        },
        mixins: [mixins.get('formField')],
        props: {
            //
        },
    }
</script>
<style scoped lang="scss">
.selected-color {
    width: 25px;
    height: 25px;
    border-radius: 15px;
    border: 1px solid #999;
}
.color-field {
    background-color: #FFF;
}
</style>
