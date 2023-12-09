<template>
    <website-render
        v-if="!rendering"
        :website-html="htmlDocument"
        :timeout="10"
    />
</template>
<script>
import mainConfig from '../../../config/main';
import websiteRender from "../../website-render/website-render.vue";
import featureContentTypes from './content-types';

export default {
    data () {
        return {
            rendering: false,
            htmlDocument: '',
        }
    },
    props: {
        domsList: {
            type: Array,
            default () {
                return [];
            }
        }
    },
    methods: {
        getHtmlDocument (bodyTags) {
            let html = '<!doctype html>';
            html += '<html lang="en">';
            html += '<head>';
            html += '<meta charset="utf-8">';
            html += '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
            html += this.getCssTags();
            html += '</head>';
            html += '<body>';
            html += bodyTags;
            html += this.getJsTags();
            html += '</body>';
            html += '</html>';

            return html;
        },
        getCssTags () {
            let html = '';

            for (const cssItem of mainConfig.app.website.css) {
                html += '<link rel="stylesheet" href="' + cssItem['href'] + '"';

                if (cssItem['integrity']) {
                    html += ' integrity="' + cssItem['integrity'] + '"';
                }

                if (cssItem['crossorigin']) {
                    html += ' crossorigin="' + cssItem['crossorigin'] + '"';
                }

                html += ">\n";
            }

            return html;
        },
        getJsTags () {
            let html = '';

            for (const jsItem of mainConfig.app.website.js) {
                html += '<script src="' + jsItem['src'] + '"';

                if (jsItem['integrity']) {
                    html += ' integrity="' + jsItem['integrity'] + '"';
                }

                if (jsItem['crossorigin']) {
                    html += ' crossorigin="' + jsItem['crossorigin'] + '"';
                }

                html += "></" + "script>\n";
            }

            return html;
        },
        getBodyTags (list) {
            let html = '';

            for (const domItem of list) {
                if (domItem.domType === 'container') {
                    html += this.getContainer(domItem.value, domItem.children);
                } else {
                    html += this.getContent(domItem.contentType, domItem.contentDomValue, domItem.value);
                }
            }

            return html;
        },
        getContainer (value, children) {
            let html = '<' + value.wrapper;

            if (value.id) {
                html += ' id="' + value.id + '"';
            }

            if (value.class) {
                html += ' class="' + value.class + '"';
            }

            if (value.title) {
                html += ' title="' + this.$t(value.title) + '"';
            }

            if (value.style) {
                html += ' style="' + value.style + '"';
            }

            html += '>';
            html += this.getBodyTags(children);
            html += '</' + value.wrapper + '>';
            return html;
        },
        getContent (type, domValue, value) {
            type = type + 'ContentType';
            type = featureContentTypes[type] ? type : 'mainContentType';

            return featureContentTypes[type](domValue, value);
        }
    },
    watch: {
        domsList: {
            handler (newValue) {
                this.rendering = true;
                this.htmlDocument = this.getHtmlDocument(this.getBodyTags(newValue));
                this.rendering = false;
            },
            deep: true,
        }
    },
    components: {
        websiteRender
    }
}
</script>
<style scoped lang="scss">
iframe {
    overflow: hidden;
    border: 0;
    width: 100%;
}
</style>
