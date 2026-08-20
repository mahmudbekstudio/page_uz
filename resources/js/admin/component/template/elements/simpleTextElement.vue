<template>
    <component
        :is="tag"
        :id="params.id"
        :class="params.class"
        :title="$t(params.title)"
        v-html="$t(params.content.value)"
    />
</template>
<script>
import mixins from '../../../mixin';

export default {
    mixins: [mixins.get('templateElement')],
    data () {
        return {
            linkUrl: null,
            linkTarget: null,
        };
    },
    computed: {
        tag() {
            if (this.params.wrapper) {
                if (this.params.wrapper.wrapper === 'paragraph') {
                    return 'p';
                } else if (this.params.wrapper.wrapper === 'header') {
                    return this.params.wrapper.header;
                } else if (this.params.wrapper.wrapper === 'link') {
                    this.linkUrl = this.params.wrapper.linkUrl;
                    this.linkTarget = this.params.wrapper.linkTarget;
                    return 'a';
                }
            }

            return 'div';
        }
    }
}
</script>
<style scoped lang="scss"></style>
