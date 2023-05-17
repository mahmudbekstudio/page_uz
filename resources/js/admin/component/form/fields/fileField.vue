<template>
    <div class="file-field">
        <v-input
            v-bind="params"
            :hint="params?.hint ? $t(params.hint) : null"
            :label="labelText"
        >
            <div>
                <file-select
                    v-bind="params"
                    v-on="events"
                    v-model="dataValue"
                    :disabled="disabled"
                ></file-select>
            </div>
        </v-input>
    </div>
</template>
<script>
import mixins from "../../../mixin";

export default {
    name: 'file-field',
    mixins: [mixins.get('formField'), mixins.get('fileField')],
}
</script>
<style>
.file-field .v-input__slot {
    display: block;
}
</style>
