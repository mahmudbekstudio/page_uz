<template>
    <div class="feature-content-field">
        <component
            :is="typeComponent"
            v-bind="$props"
            @input="componentChanged"
        ></component>
    </div>
</template>
<script>
import mixins from "../../../mixin";
import featureContentComponents from './featureContentComponents';

export default {
    name: 'featureContent',
    mixins: [mixins.get('formField')],
    computed: {
        typeComponent () {
            return this.params.featureType + 'FeatureField';
        }
    },
    methods: {
        componentChanged (value) {
            this.$emit('input', value)
        }
    },
    components: featureContentComponents
}
</script>
