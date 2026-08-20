<template>
    <div>
        <v-radio-group
            v-model="typeValue"
            :label="$t('words.contentType')"
            row
        >
            <v-radio
                v-for="item of list"
                :key="item.value"
                :label="$t(item.text)"
                :value="item.value"
            ></v-radio>
        </v-radio-group>
        <div class="editor-field">
            <label class="field-label v-label theme--light">{{labelText}}</label>
            <editor
                v-if="typeValue === 'editor'"
                v-model="contentValueValue"
                type="simple"
            ></editor>
            <v-text-field
                v-if="typeValue === 'text'"
                v-model="contentValueValue"
                :label="labelText"
                :placeholder="labelText"
            ></v-text-field>
        </div>
    </div>
</template>
<script>
import mixins from '../../../../mixin';
import editor from "../../../editor.vue";

export default {
    components: {editor},
    mixins: [mixins.get('formField')],
    props: {
        //
    },
    data () {
        return {
            typeValue: 'text',
            contentValueValue: '',
        }
    },
    computed: {
        list () {
            return [
                {text: 'words.text', value: 'text'},
                {text: 'words.editor', value: 'editor'},
            ];
        }
    },
    created () {
        this.setValue(this.dataValue);
    },
    watch: {
        dataValue (value) {
            this.setValue(value)
        },
        typeValue () {
            this.valueChanged();
        },
        contentValueValue () {
            this.valueChanged();
        },
    },
    methods: {
        valueChanged () {
            this.dataValue = {
                type: this.typeValue,
                value: this.contentValueValue,
            };
        },
        setValue (value) {
            if (value.type) {
                this.typeValue = value.type;
            }

            if (value.value) {
                this.contentValueValue = value.type === 'text' ? value.value.replace(/<\/?[^>]+(>|$)/g, "") : value.value;
            }
        }
    }
}
</script>
<style scoped lang="scss"></style>
