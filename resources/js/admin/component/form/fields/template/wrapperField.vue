<template>
    <div>
        <v-text-field
            v-if="showId"
            v-model="id"
            :label="$t('words.wrapper_id')"
            :placeholder="$t('words.wrapper_id')"
        ></v-text-field>
        <v-text-field
            v-if="showClass"
            v-model="classList"
            :label="$t('words.wrapper_class')"
            :placeholder="$t('words.wrapper_class')"
        ></v-text-field>
        <v-select
            :items="wrapperItems"
            v-model="wrapperValue"
            :label="$t(params.label)"
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
            id: '',
            classList: '',
            wrapperValue: 'none',
            headerValue: 'h1',
            linkUrlValue: '',
            linkTargetValue: '_self',
            headerType: false,
            linkUrl: false,
            linkTarget: false,
            wrappers: [
                {text: 'none', value: 'none'},
                {text: 'container', value: 'container'},
                {text: 'header', value: 'header'},
                {text: 'link', value: 'link'},
                {text: 'paragraph', value: 'paragraph'},
            ],
        }
    },
    computed: {
        showId () {
            return !this.params.hideId;
        },
        showClass () {
            return !this.params.hideClass;
        },
        wrapperItems () {
            return this.wrappers.filter(item => {
                if (this.params.acceptedWrappers) {
                    return this.params.acceptedWrappers.indexOf(item.value) > -1;
                }

                return true;
            });
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
        if (this.dataValue) {
            this.setValue(this.dataValue);
        } else {
            this.valueChanged();
        }
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

            if (value !== 'link') {
                this.linkUrlValue = null;
                this.linkTargetValue = null;
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
        id () {
            this.valueChanged();
        },
        classList () {
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
                class: this.classList,
                id: this.id,
            };
        },
        setValue (value) {
            if (value.wrapper) {
                let wrapperExist = false;
                for (const item in this.wrapperItems) {
                    if (item.value === value.wrapper) {
                        wrapperExist = true;
                    }
                }

                if (!wrapperExist) {
                    this.wrapperValue = this.wrapperItems[0].value;
                } else {
                    this.wrapperValue = value.wrapper;
                }
            }

            if (value.header) {
                this.headerValue = value.header;
            }

            if (value.linkUrl) {
                this.linkUrlValue = value.linkUrl;
            }

            if (value.linkTarget) {
                this.linkTargetValue = value.linkTarget === '_blank' ? '_blank' : '_self';
            }

            if (value.id) {
                this.id = value.id;
            }

            if (value.class) {
                this.classList = value.class;
            }
        }
    }
}
</script>
<style scoped lang="scss"></style>
