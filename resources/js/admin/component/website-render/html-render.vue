<template>
    <iframe ref="websitehtmlcontainer"/>
</template>
<script>
import Field from "./fields/class/Field";

export default {
    data () {
        return {
            fieldsStyles: {},
        }
    },
    props: {
        id: {
            type: String,
            default () {
                return '';
            }
        },
        styles: {
            type: Object,
            default () {
                return {};
            }
        },
        styleFiles: {
            type: Object|Array,
            default () {
                return {};
            }
        },
        scriptFiles: {
            type: Object|Array,
            default () {
                return {};
            }
        },
        structure: {
            type: Object,
            default () {
                return {};
            }
        },
        fields: {
            type: Object|Array,
            default () {
                return {};
            }
        },
        values: {
            type: Object,
            default () {
                return {};
            }
        },
        isSample: {
            type: Boolean,
            default () {
                return false;
            }
        },
    },
    created() {
        this.$nextTick(() => {
            const websitehtmlcontainer = this.$refs.websitehtmlcontainer;
            const doc = websitehtmlcontainer.contentWindow.document;
            doc.write(this.htmlDocument);
            websitehtmlcontainer.height = 0;
            (function (websitehtmlcontainer, doc) {
                const callback = () => {
                    if ((websitehtmlcontainer.height - 2) !== doc.body.scrollHeight) {
                        websitehtmlcontainer.height = (doc.body.scrollHeight + 2) + "px";
                    }

                    //setTimeout(callback, 200);
                };
                setTimeout(callback, 400);
            })(websitehtmlcontainer, doc);
        });
    },
    computed: {
        htmlDocument() {
            this.structure.attributes['id'] = this.id;
            const structureHtml = this.getStructureHtml(this.structure, true);
            let html = '<!doctype html>';
            html += '<html lang="en">';
            html += '<head>';
            html += '<meta charset="utf-8">';
            html += '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
            html += '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">';
            html += '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">\n';

            for (const styleFile of this.styleFiles) {
                html += '<link rel="stylesheet" href="' + styleFile + '?t=' + (new Date()).getTime() + '">\n';
            }

            html += '<style>' + this.structureStyles + '</style>';
            html += '</head>';
            html += '<body>';

            html += structureHtml;
            //html += '<div id="website-main-popup"></div>';
            html += "<script src=\"https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js\"><\/script>\n";
            html += "<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js\"><\/script>\n";

            for (const scriptFile of this.scriptFiles) {
                html += "<script src=\"" + scriptFile + "?t=" + (new Date()).getTime() + "\"><\/script>\n";
            }

            html += '</body>';
            html += '</html>';

            return html;
        },

        structureStyles() {
            let styles = "body {overflow: hidden;}\n";
            const stylesList = {...this.styles, ...this.fieldsStyles};
            const attrClass = this.structure.attributes.class || '';
            const classesList = attrClass.split(' ');
            styles += this.generateStyles(stylesList, classesList);

            return styles;
        }
    },
    methods: {
        generateStyles(stylesList, classesList) {
            let styles = '';

            for (const classKey in stylesList) {
                const classItem = stylesList[classKey];
                if (classKey.startsWith('@media')) {
                    styles += classKey + '{' + this.generateStyles(classItem, classesList) + "}\n";
                } else if (Object.keys(classItem).length) {
                    styles += classKey.replaceAll('#id', '#' + this.id) + "{\n";
                    const classStyles = [];

                    for (const styleKey in classItem) {
                        if (styleKey === 'field') {
                            if (typeof classItem[styleKey] === 'string') {
                                const field = this.getField(classItem[styleKey]);
                                classStyles.push(field.css);
                            } else if(typeof classItem[styleKey] === 'object') {
                                for (const fieldKey in classItem[styleKey]) {
                                    const field = this.getField(classItem[styleKey][fieldKey]);
                                    classStyles.push(field.css);
                                }
                            }
                        } else {
                            classStyles.push(styleKey + ':' + classItem[styleKey]);
                        }
                    }

                    styles += classStyles.join(";\n") + "\n}\n";
                }
            }

            return styles;
        },
        getStructureHtml(structure, isFirst = false) {
            if (structure.tag) {
                let html = '<' + structure.tag;

                if (structure.attributes) {
                    for (const attrKey in structure.attributes) {
                        const borderClass = isFirst && attrKey === 'class' ? ' border border-dark' : '';
                        html+= ' ' + attrKey + '="' + structure.attributes[attrKey] + borderClass + '"'
                    }
                }

                if (structure.children && structure.children.length) {
                    html += '>';

                    for (const childElement of structure.children) {
                        html += this.getStructureHtml(childElement);
                    }

                    html += '</' + structure.tag + '>';
                } else {
                    html += '/>';
                }

                return html;
            } else if (structure.field && this.fields && this.values[structure.field]) {
                const field = this.getField(structure.field);
                this.fieldsStyles = {...this.fieldsStyles, ...field.classes};

                return field.html;
            } else if (structure.text) {
                return structure.text;
            } else if (structure.html) {
                return structure.html;
            }

            return '';
        },
        getField(fieldName) {
            const typeField = this.getFieldByName(fieldName);
            return new Field(typeField.type, this.values[fieldName], this.isSample);
        },
        getFieldByName(fieldName) {
            for (const field in this.fields) {
                if (this.fields[field].name === fieldName) {
                    return this.fields[field];
                }
            }
            return null;
        },
    },
}
</script>
<style scoped lang="scss">
iframe {
    overflow: hidden;
    border: 0;
    width: 100%;
}
</style>
