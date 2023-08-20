<template>
    <img
        v-if="imageSrc"
        v-bind="attributes"
    />
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
    }
}
</script>
<style scoped lang="scss"></style>
