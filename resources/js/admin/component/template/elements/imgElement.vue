<template>
    <component
        :is="wrapperTag"
        :id="params?.wrapper?.id"
        :class="params?.wrapper?.class"
        :href="linkUrl"
        :target="linkTarget"
    >
        <img
            v-if="imageSrc"
            v-bind="attributes"
        />
    </component>
</template>
<script>
import mixins from '../../../mixin';
import {mapGetters} from "vuex";

export default {
    mixins: [mixins.get('templateElement')],
    computed: {
        ...mapGetters({
            website: 'view/website',
        }),
        linkUrl () {
            if (this.params.wrapper && this.params.wrapper.wrapper === 'link') {
                return this.params?.wrapper?.linkUrl;
            }

            return null;
        },
        linkTarget () {
            if (this.params.wrapper && this.params.wrapper.wrapper === 'link') {
                return this.params?.wrapper?.linkTarget;
            }

            return null;
        },
        wrapperTag () {
            if (this.params.wrapper) {
                if (this.params.wrapper.wrapper === 'none') {
                    return 'Fragment';
                } else if (this.params.wrapper.wrapper === 'link') {
                    return 'a';
                }
            }

            return 'div';
        },
        attributes () {
            const attrs = {};

            if (this.imageSrc) {
                attrs['src'] = this.imageSrc;
            }

            if (this.params.id) {
                attrs['id'] = this.params.id;
            }

            if (this.params.class) {
                attrs['class'] = this.params.class;
            }

            if (this.params.height) {
                attrs['height'] = this.params.height;
            }

            if (this.params.width) {
                attrs['width'] = this.params.width;
            }

            if (this.params.alt) {
                attrs['alt'] = this.params.alt;
            }

            return attrs;
        },
        imageSrc () {
            if (!this.params.src || !this.params.src.length) {
                return '';
            }

            const src = this.params.src[0];
            return (src.is_local ? '' : this.website.fileBaseUrl) + src.folderPath + '/' + src.name + '.' + src.extension;
        }
    },
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
