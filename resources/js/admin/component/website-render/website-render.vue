<template>
    <iframe ref="websitecontainer"/>
</template>
<script>
export default {
    created() {
        if (this.websiteHtml) {
            this.$nextTick(() => {
                this.changeHtml(this.websiteHtml);
            });
        }
    },
    props: {
        websiteHtml: {
            type: String,
            default() {
                return '';
            }
        },
        timeout: {
            type: Number,
            default() {
                return 400;
            }
        }
    },
    methods: {
        changeHtml(html) {
            const websitehtmlcontainer = this.$refs.websitecontainer;
            const doc = websitehtmlcontainer.contentWindow.document;
            doc.open();
            doc.write(html);
            doc.close();


            websitehtmlcontainer.height = 0;
            (function (websitehtmlcontainer, doc, timeout) {
                const callback = () => {
                    if ((websitehtmlcontainer.height - 5) !== doc.body.scrollHeight) {
                        websitehtmlcontainer.height = (doc.body.scrollHeight + 5) + "px";
                    }

                };
                setTimeout(callback, timeout);
            })(websitehtmlcontainer, doc, this.timeout);
        }
    },
    watch: {
        websiteHtml(newVal) {
            this.changeHtml(newVal);
        }
    }
}
</script>
<style scoped lang="scss">
iframe {
    border: #000 1px solid;
    overflow: auto;
    width: 100%;
}
</style>
