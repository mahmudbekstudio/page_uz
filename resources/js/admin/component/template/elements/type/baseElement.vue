<template>
    <component
        :is="wrapperTag"
        :id="wrapperId"
        :class="wrapperClass"
        :href="linkUrl"
        :target="linkTarget"
    >
        <component
            :is="subTag"
            :id="params.id"
            :class="classes"
            :title="$t(params.title)"
            v-html="innerText"
        />
    </component>
</template>
<script>
import mixins from '../../../../mixin';

export default {
    computed: {
        innerText () {
            return '{ $' + this.name + ' }';
        },
        wrapperTag () {
            let tag = 'div';
            if (this.params.wrapper) {
                if (this.params.wrapper.wrapper === 'none') {
                    return 'Fragment';
                } else if (this.params.wrapper.wrapper === 'paragraph') {
                    tag = 'p';
                } else if (this.params.wrapper.wrapper === 'header') {
                    tag = this.params.wrapper.header;
                }
            }
            return tag;
        },
        subTag () {
            let tag = 'span';

            if (this.params.wrapper && this.params.wrapper.wrapper === 'link') {
                tag = 'a';
            }

            return tag
        },
        linkUrl () {
            if (this.params.wrapper && this.params.wrapper.wrapper === 'link') {
                return this.params.wrapper.linkUrl;
            }

            return null;
        },
        linkTarget () {
            if (this.params.wrapper && this.params.wrapper.wrapper === 'link') {
                return this.params.wrapper.linkTarget;
            }

            return null;
        },
        wrapperId () {
            if (this.params.wrapper.id) {
                return this.params.wrapper.id;
            }
            return null;
        },
        wrapperClass () {
            if (this.params.wrapper.id) {
                return this.params.wrapper.class;
            }
            return null;
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
    components: {
        Fragment: {
            functional: true,
            render(h, context) {
                return context.children;
            }
        }
    }
}
</script>
<style scoped lang="scss"></style>
