import Field from "./fields/class/Field";
import {Element} from '../template/classes/template';
import mainConfig from '../../config/main';

export class WebsiteRender {
    templateConfig = null;
    themeConfig = null;
    constructor(templateConfig, themeConfig = null) {
        this.templateConfig = templateConfig;
        this.themeConfig = themeConfig;
    }

    get styleFiles() {
        return this.templateConfig.styleFiles;
    }

    get scriptFiles() {
        return this.templateConfig.scriptFiles;
    }

    get blocks() {
        return this.templateConfig?.blocks?.filter(item => !item.hide) || [];
    }

    get styles() {
        return this.templateConfig?.styles;
    }

    getFields(blockType, layoutId) {
        const blockFields = this.getBlockFields(blockType) || [];
        const layoutFields = this.getLayoutFields(blockType, layoutId) || [];
        return [...this.templateConfig.fields, ...blockFields, ...layoutFields];
    }

    getBlock(blockType) {
        for (const block of this.templateConfig.blocks) {
            if (block.type === blockType) {
                return block;
            }
        }

        return null;
    }

    getBlockLayout(blockType, layoutId) {
        const block = this.getBlock(blockType);

        if (block) {
            for (const layout of block.layout) {
                if (parseInt(layout.id) === parseInt(layoutId)) {
                    // if extend another layout
                    if (layout.structure.layout) {
                        layoutId = layout.structure.layout;
                        for (let childLayout of block.layout) {
                            if (parseInt(childLayout.id) === parseInt(layoutId)) {
                                childLayout = JSON.parse(JSON.stringify(childLayout));
                                childLayout.structure = {...childLayout.structure, ...layout.structure}
                                return childLayout;
                            }
                        }
                    }

                    return layout;
                }
            }
        }

        return null;
    }

    getBlockFields(blockType) {
        const block = this.getBlock(blockType);
        return block ? block.fields : {};
    }

    getLayoutFields(blockType, layoutId) {
        const layout = this.getBlockLayout(blockType, layoutId);
        return layout ? layout.fields : {};
    }

    getTemplate(block, sample) {
        const template = {
            hide: !!block.hide,
            type: block.type,
            canHasChild: block.canHasChild,
            fields: this.getFields(block.type, sample.layout),
        };
        //template.type = block.type;
        //template.fields = this.getFields(block.type, sample.layout);
        template.values = sample.values;
        template.styles = {...this.styles, ...block.styles};
        template.styleFiles = this.styleFiles;
        template.scriptFiles = this.scriptFiles;
        const item = this.getBlockLayout(block.type, sample.layout);
        template.structure = item.structure;
        template.children = item.children;

        return template;
    }
}

export class WebsiteHtml {
    blocks = [];
    hasNewline = true;
    isFresh = true;
    fieldsStyles = {};
    idIncrement = 0;
    isSample = false;
    contentHtml = '';
    themeConfig = null;

    constructor(blocks = null, themeConfig = null) {
        this.setThemeConfig(themeConfig);

        if (Array.isArray(blocks)) {
            this.blocks = blocks.map(block => this.initBlock(block));
        }
    }

    initBlock(block) {
        this.idIncrement++;
        block.id = block.type + '-' + this.idIncrement;
        block.structure.attributes.id = block.id;

        if(!block.title) {
            block.title = block.type;
        }

        block.customStyles = block.customStyles || {'': []};

        return block;
    }

    setSample(isSample) {
        this.isSample = isSample;
    }

    addBlock(block, innerList = null) {
        block = this.initBlock(block);
        const list = innerList ? innerList : this.blocks;

        list.push(block);
    }

    get structureStyles() {
        let styles = this.generateStyles(this.fieldsStyles);

        for (const block of this.blocks) {
            styles += this.generateStyles(block.styles, block);
            if (block.type === 'grid' && block?.children) {
                for (const blockChild of block.children) {
                    if (blockChild?.children) {
                        for (const blockChild2 of blockChild.children) {
                            styles += this.generateStyles(blockChild2.styles, blockChild2);
                        }
                    }
                }
            }
        }

        return styles;
    }

    get contentStructureStyles() {
        let styles = this.generateStyles(this.fieldsStyles);

        for (const block of this.blocks) {
            if (block.type === 'content') {
                styles += this.generateStyles(block.styles, block);
                if (block.type === 'grid' && block?.children) {
                    for (const blockChild of block.children) {
                        if (blockChild?.children) {
                            for (const blockChild2 of blockChild.children) {
                                styles += this.generateStyles(blockChild2.styles, blockChild2);
                            }
                        }
                    }
                }
                break;
            }
        }

        return styles;
    }

    get customStyles() {
        const classStyles = [];
        for (const block of this.blocks) {
            let styleItem = '';

            for (const selector in block.customStyles) {
                if (!block.customStyles[selector].length) continue;

                styleItem += '#' + block.id + (selector ? ' ' : '') + selector + ' {';

                for (let selectorStyle of block.customStyles[selector]) {
                    selectorStyle = selectorStyle ? selectorStyle.trim() : '';

                    if (!selectorStyle) {
                        continue;
                    }

                    if (selectorStyle.endsWith(';')) {
                        selectorStyle = selectorStyle.substring(0, selectorStyle.length - 1);
                    }

                    styleItem += selectorStyle + ' !important;';
                }

                styleItem += '}'
            }

            classStyles.push(styleItem);
        }

        return classStyles.join('');
    }

    generateStyles(stylesList, block) {
        let styles = '';
        const id = block?.id || '';

        for (const classKey in stylesList) {
            const classItem = stylesList[classKey];
            if (classKey.startsWith('@media')) {
                styles += classKey + '{' + this.generateStyles(classItem, block) + "}\n";
            } else if (Object.keys(classItem).length) {
                styles += classKey.replaceAll('#id', '#' + id) + "{\n";
                const classStyles = [];

                for (const styleKey in classItem) {
                    if (styleKey === 'field') {
                        if (typeof classItem[styleKey] === 'string') {
                            const field = this.getField(classItem[styleKey], block);
                            classStyles.push(field.css);
                        } else if(typeof classItem[styleKey] === 'object') {
                            for (const fieldKey in classItem[styleKey]) {
                                const field = this.getField(classItem[styleKey][fieldKey], block);
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
    }

    get scriptFiles() {
        const files = [];

        for (const block of this.blocks) {
            if (block.scriptFiles) {
                for (const scriptFile of block.scriptFiles) {
                    if (files.indexOf(scriptFile) === -1) {
                        files.push(scriptFile);
                    }
                }
            }

            if (block.type === 'grid' && block?.children) {
                for (const blockChild of block.children) {
                    if (blockChild?.children) {
                        for (const blockChild2 of blockChild.children) {
                            if (blockChild2.scriptFiles) {
                                for (const scriptFile of blockChild2.scriptFiles) {
                                    if (files.indexOf(scriptFile) === -1) {
                                        files.push(scriptFile);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return files;
    }

    get styleFiles() {
        const files = [];

        for (const block of this.blocks) {
            if (block.styleFiles) {
                for (const styleFile of block.styleFiles) {
                    if (files.indexOf(styleFile) === -1) {
                        files.push(styleFile);
                    }
                }
            }

            if (block.type === 'grid' && block?.children) {
                for (const blockChild of block.children) {
                    if (blockChild?.children) {
                        for (const blockChild2 of blockChild.children) {
                            if (blockChild2.styleFiles) {
                                for (const styleFile of blockChild2.styleFiles) {
                                    if (files.indexOf(styleFile) === -1) {
                                        files.push(styleFile);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return files;
    }

    getTimerParam() {
        return this.isFresh ? '?t=' + (new Date()).getTime() : '';
    }

    getContentBlockStructureHtml() {
        for (const block of this.blocks) {
            if (block.type === 'content') {
                return this.getStructureHtml(block.structure, block, false, null, false, true);
            }
        }

        return '';
    }

    getStructureHtml(structure, block, withBorder = false, lang = null, isGray = false, withAllTranslations = false) {
        if (structure.tag) {
            let html = '<' + structure.tag;

            if (!structure.attributes) {
                structure.attributes = {};
            }

            if (!structure.attributes.class) {
                structure.attributes.class = '';
            }

            for (const attrKey in structure.attributes) {
                html+=
                    ' ' +
                    attrKey +
                    '="' +
                    structure.attributes[attrKey] +
                    (withBorder && attrKey === 'class' ? ' template-block-border' : '') +
                    (withBorder && block.isActive && attrKey === 'class' ? ' template-block-border-active' : '') +
                    (attrKey === 'class' && isGray ? ' template-block-gray' : '') +
                    '"'
            }

            if (structure.children && structure.children.length) {
                html += '>';

                if (withBorder) {
                    html += '<div class="template-block-title">' + block.title + '</div>';
                }

                for (const childElement of structure.children) {
                    html += this.getStructureHtml(childElement, block, false, lang, false, withAllTranslations);
                }

                html += '</' + structure.tag + '>';
            } else {
                html += '/>';
            }

            return html;
        } else if (structure.field && block.fields && block.values[structure.field]) {
            let blockHtml = '';
            if (block?.children) {
                if (structure.field === 'content') {
                    blockHtml = this.generateContent(block.children, lang, withAllTranslations);
                } else {
                    for (const blockChild of block.children) {
                        if (blockChild.name === structure.field && blockChild?.children) {
                            for (const blockChildItem of blockChild.children) {
                                blockHtml += this.getStructureHtml(blockChildItem.structure, blockChildItem, false, lang, false, withAllTranslations);
                            }
                        }
                    }
                }
            }

            const field = this.getField(structure.field, block, blockHtml, lang);
            field.typeField.withAllTranslations = withAllTranslations;
            this.fieldsStyles = {...this.fieldsStyles, ...field.classes};

            return field.html;
        } else if (structure.text) {
            return structure.text;
        } else if (structure.html) {
            return structure.html;
        }

        return '';
    }

    getField(fieldName, block, params, lang = null) {
        const typeField = this.getFieldByName(fieldName, block);
        return new Field(typeField.type, block.values[fieldName], this.isSample, params, lang);
    }

    getFieldByName(fieldName, block) {
        for (const field in block.fields) {
            if (block.fields[field].name === fieldName) {
                return block.fields[field];
            }
        }
        return null;
    }

    setThemeConfig(config) {
        this.themeConfig = config;
    }

    htmlDocument(
        withBorder = false,
        lang = null,
        greyTypeExcept = null,
        withAllTranslations = false
    ) {
        this.fieldsStyles = {};
        this.contentHtml = '';

        for (const block of this.blocks) {
            let isGray = false;

            if (Array.isArray(greyTypeExcept) && greyTypeExcept.length) {
                isGray = true;

                if (greyTypeExcept.indexOf(block.type) > -1) {
                    isGray = false;
                }
            }

            this.contentHtml += this.getStructureHtml(block.structure, block, withBorder, lang, isGray, withAllTranslations);
        }

        let html = ['<!doctype html>'];
        html.push('<html lang="en">');
        html.push('<head>');
        html.push('<meta charset="utf-8">');
        html.push('<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">');

        if (this.themeConfig && this.themeConfig.css) {
            for (const css of this.themeConfig.css) {
                html.push('<link rel="stylesheet" href="' + css + '">');
            }
        }

        for (const styleFile of this.styleFiles) {
            html.push('<link rel="stylesheet" href="' + styleFile + this.getTimerParam() + '">');
        }
        html.push('<style>' + this.structureStyles + '</style>');
        html.push('<style>' + this.customStyles + '</style>');
        html.push('</head>');
        html.push('<body>');
        html.push(this.contentHtml);

        if (this.themeConfig && this.themeConfig.js) {
            for (const js of this.themeConfig.js) {
                html.push('<script src="' + js + '"><\/script>');
            }
        }

        for (const scriptFile of this.scriptFiles) {
            html.push('<script src="' + scriptFile + this.getTimerParam() + '"><\/script>');
        }

        html.push('</body>');
        html.push('</html>');

        return html.join(this.hasNewline ? "\n" : '');
    }

    getBlockById(id, list = null) {
        if (list === null) {
            list = this.blocks;
        }

        for (const block of list) {
            if (block.id && block.id === id) {
                return block;
            }

            if (block.children && block.children.length) {
                const childBlock = this.getBlock(id, block.children);

                if (childBlock && childBlock.id === id) {
                    return childBlock;
                }
            }
        }

        return null;
    }

    generateContent(list, lang, forSave = false) {
        if (Array.isArray(list) && !list.length) {
            return forSave ? '{ $content }' : 'Content';
        }

        let contentHtml = '';
        for (const itemRow of list) {
            contentHtml += '<div class="row">';
            for (const itemCol of itemRow.children) {
                contentHtml += '<div class="col-md-' + itemCol.size + '">';
                for (const itemColContent of itemCol.children) {
                    const element = new Element(itemColContent, lang, forSave);
                    contentHtml += element.element.html;
                }
                contentHtml += '</div>';
            }
            contentHtml += '</div>';
        }

        return contentHtml;
    }
}
