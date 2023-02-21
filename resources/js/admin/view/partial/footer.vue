<template>
    <v-footer
            :inset="footerInset"
            app
    >
        <span class="px-4">&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
</template>
<script>
    import {mapGetters} from 'vuex';

    export default {
        computed: {
            ...mapGetters({
                footerInset: 'view/footerInset'
            })
        }
    }
</script>
