<template>
    <div>
        <v-select
            :items="wrapperItems"
            v-model="wrapperValue"
            :label="$t('words.wrapper')"
        ></v-select>
        <v-select
            v-if="headerType"
            :items="headerItems"
            v-model="headerValue"
            :label="$t('words.header')"
        ></v-select>
        <v-text-field
            v-if="linkUrl"
            v-model="linkUrlValue"
            :label="$t('words.link_url')"
            :placeholder="$t('words.link_url')"
        ></v-text-field>
        <v-select
            v-if="linkTarget"
            :items="linkTargetItems"
            v-model="linkTargetValue"
            :label="$t('words.link_target')"
        ></v-select>
    </div>
</template>
<script>
import mixins from '../../../../mixin';

export default {
    mixins: [mixins.get('formField')],
    props: {
        //
    },
    data () {
        return {
            wrapperValue: 'container',
            headerValue: 'h1',
            linkUrlValue: '',
            linkTargetValue: '_self',
            headerType: false,
            linkUrl: false,
            linkTarget: false,
        }
    },
    computed: {
        wrapperItems () {
            return [
                {text: 'container', value: 'container'},
                {text: 'header', value: 'header'},
                {text: 'link', value: 'link'},
                {text: 'paragraph', value: 'paragraph'},
            ];
        },
        headerItems () {
            return [
                {text: 'H1', value: 'h1'},
                {text: 'H2', value: 'h2'},
                {text: 'H3', value: 'h3'},
                {text: 'H4', value: 'h4'},
                {text: 'H5', value: 'h5'},
                {text: 'H6', value: 'h6'},
            ];
        },
        linkTargetItems () {
            return [
                {text: 'Self', value: '_self'},
                {text: 'Blank', value: '_blank'},
            ];
        }
    },
    created () {
        this.setValue(this.dataValue);
    },
    watch: {
        dataValue (value) {
            this.setValue(value);
        },
        wrapperValue (value) {
            this.headerType = false;
            this.linkUrl = false;
            this.linkTarget = false;
            switch (value) {
                case 'header':
                    this.headerType = true;
                    break;
                case 'link':
                    this.linkUrl = true;
                    this.linkTarget = true;
                    break;
            }

            this.valueChanged();
        },
        headerValue () {
            this.valueChanged();
        },
        linkUrlValue () {
            this.valueChanged();
        },
        linkTargetValue () {
            this.valueChanged();
        },
    },
    methods: {
        valueChanged () {
            this.dataValue = {
                wrapper: this.wrapperValue,
                header: this.headerValue,
                linkUrl: this.linkUrlValue,
                linkTarget: this.linkTargetValue,
            };
        },
        setValue (value) {
            if (value.wrapper) {
                this.wrapperValue = value.wrapper;
            }

            if (value.header) {
                this.headerValue = value.header;
            }

            if (value.linkUrl) {
                this.linkUrlValue = value.linkUrl;
            }

            if (value.linkTarget) {
                this.linkTargetValue = value.linkTarget;
            }
        }
    }
}
</script>
<style scoped lang="scss"></style>
