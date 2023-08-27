<template>
    <component
        :is="wrapperTag"
        :id="params.id"
        :class="classes"
        :title="$t(params.title)"
    >
        <component :is="subTag" :href="linkUrl" :target="linkTarget">{{innerText}}</component>
    </component>
</template>
<script>
import mixins from '../../../../mixin';

export default {
    data () {
        return {
            href: null,
            target: undefined,
        }
    },
    computed: {
        innerText () {
            return '{ $' + this.name + ' }';
        },
        wrapperTag () {
            return this.params.wrapper || 'div';
        },
        subTag () {
            return this.params.link_url ? 'a' : 'span'
        },
        linkUrl () {
            return this.params.link_url || null;
        },
        linkTarget () {
            return this.params.link_url ? this.params.link_target : null;
        },
        textStyle () {
            const classes = [];

            if (!this.params.text_style || !Array.isArray(this.params.text_style)) {
                return '';
            }

            for (const style of this.params.text_style) {
                classes.push('text-' + style);
            }

            return classes.join(' ');
        },
        classes () {
            return this.params.class + (this.textStyle ? ' ' : '') + this.textStyle;
        }
    },
    mixins: [mixins.get('templateElement')],
}
</script>
<style scoped lang="scss"></style>
