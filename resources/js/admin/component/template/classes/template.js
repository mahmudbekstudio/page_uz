import simpleTextElement from './simpleTextElement';
import hElement from './hElement';
import pElement from './pElement';
import divElement from "./divElement";
import hrElement from "./hrElement";
import imgElement from "./imgElement";
import { TEMPLATE } from '../../../constants';
import * as _ from 'lodash';
import validation from "../../../config/validation";

import textElement from './type/textElement';
import textareaElement from './type/textareaElement';
import numberElement from './type/numberElement';
import passwordElement from './type/passwordElement';
import selectElement from './type/selectElement';
import fileElement from './type/fileElement';
import switchElement from './type/switchElement';
import dividerElement from './type/dividerElement';
import datetimeElement from './type/datetimeElement';
import dateElement from './type/dateElement';
import dateRangeElement from './type/dateRangeElement';
import dateMultipleElement from './type/dateMultipleElement';
import timeElement from './type/timeElement';
import radioElement from './type/radioElement';
import checkboxElement from './type/checkboxElement';
import editorElement from './type/editorElement';
import advancedParentElement from './type/advancedParentElement';
import advancedChildOfElement from './type/advancedChildOfElement';
import requiredPublishStartElement from './type/requiredPublishStartElement';
import requiredPublishEndElement from './type/requiredPublishEndElement';
import requiredRouteNameElement from './type/requiredRouteNameElement';
import requiredSeoKeywordElement from './type/requiredSeoKeywordElement';
import requiredSeoDescriptionElement from './type/requiredSeoDescriptionElement';
import requiredStatusElement from './type/requiredStatusElement';
import requiredTemplateElement from './type/requiredTemplateElement';
import requiredTitleElement from './type/requiredTitleElement';

export class Template {
    children = [];
    isConstructor = false;

    constructor(params = {}, isConstructor = false) {
        this.isConstructor = isConstructor;

        if(!Object.keys(params).length) {
            this.addRow({}, {});
        } else {
            this.json = params || [];
        }
    }

    getRow(row = 0) {
        return this.children[row];
    }

    addRow(params, colParams = {}) {
        const row = new Row(params, this.isConstructor);
        row.addCol(colParams);
        this.children.push(row);
        return row;
    }

    getCol(col = 0, row = 0) {
        return this.getRow(row).getChild(col);
    }

    addCol(params, row = 0) {
        return this.getRow(row).addCol(params);
    }

    getElement(element = 0, col = 0, row = 0) {
        return this.getCol(col, row).getChild(element);
    }

    addElement(elementObj, row = 0, col = 0) {
        return this.getCol(col, row).addElement(elementObj);
    }

    getElementBykey(key) {
        const keys = key.split(TEMPLATE.elementKeySplitter);
        return keys.length === 4 ? this.getElement(keys[3], keys[2], keys[1], keys[0]) : null;
    }

    getElements() {
        let result = [];

        _.forEach(this.children, row => result = [...result, ...row.getElements()]);

        return result;
    }

    set json(val) {
        for(let i = 0; i < val.length; i++) {
            this.children.push(new Row(val[i], this.isConstructor));
        }
    }

    get json() {
        const result = [];

        for(let i = 0; i < this.children.length; i++) {
            result.push(this.children[i].json)
        }

        return result;
    }
}

export class Row {
    children = [];
    type = 'row';
    isConstructor = false;

    constructor(params, isConstructor = false) {
        this.isConstructor = isConstructor;
        //this.addCol(colParams)
        this.json = params;
    }

    getChild(index = 0) {
        return this.children[index];
    }

    addCol(params) {
        const col = new Col(params, this.isConstructor);
        this.children.push(col);
        return col;
    }

    addElement(elementObj, col = 0) {
        return this.getChild(col).addElement(elementObj);
    }

    getElements() {
        const result = [];

        _.forEach(
            this.children,
            col => _.forEach(
                col.children,
                element => result.push(element)
            )
        );

        return result;
    }

    set json(val) {
        val.children = val.children || [];
        for(let i = 0; i < val.children.length; i++) {
            this.children.push(new Col(val.children[i], this.isConstructor));
        }

        this.type = val.type || 'row';
    }

    get json() {
        const result = {
            children: [],
            type: this.type || 'row'
        };

        for(let i = 0; i < this.children.length; i++) {
            result.children.push(this.children[i].json)
        }

        return result;
    }
}

export class Col {
    children = [];
    type = 'col';
    size = '12';
    isConstructor = false;

    constructor(params, isConstructor = false) {
        this.isConstructor = isConstructor;
        this.json = params;
    }

    getChild(index = 0) {
        return this.children[index];
    }

    addElement(elementObj) {
        const element = new Element({...elementObj, isConstructor: this.isConstructor});
        this.children.push(element);
        return element;
    }

    set json(val) {
        val.children = val.children || [];
        for(let i = 0; i < val.children.length; i++) {
            this.children.push(new Element({...val.children[i], isConstructor: this.isConstructor}));
        }

        this.type = val.type || 'col';
        this.size = val.size || '12';
    }

    get json() {
        const result = {
            children: [],
            type: this.type || 'col',
            size: this.size || '12'
        };

        for(let i = 0; i < this.children.length; i++) {
            result.children.push(this.children[i].json)
        }

        return result;
    }
}

let elementId = 0;

export class Element {
    element = {};
    withAllTranslations = false;
    fillable = [
        {
            type: 'text',
            name: 'id',
            hasLang: false,
            params: {label: 'words.id'}
        },
        {
            type: 'text',
            name: 'class',
            hasLang: false,
            params: {label: 'words.class'}
        },
        {
            type: 'text',
            name: 'title',
            params: {label: 'words.title'}
        },
        /*{
            type: 'select',
            name: 'wrapper',
            value: 'div',
            params: {
                clearable: false,
                label: 'words.wrapper',
                options: {
                    'div': 'Div',
                    'p': 'P',
                    'h1': 'H1',
                    'h2': 'H2',
                    'h3': 'H3',
                    'h4': 'H4',
                    'h5': 'H5',
                    'h6': 'H6',
                }
            }
        },
        {
            type: 'editor',
            name: 'content',
            params: {label: 'words.content', type: 'simple'}
        },
        {
            type: 'text',
            name: 'link_url',
            hasLang: false,
            params: {label: 'words.link_url'}
        },
        {
            type: 'select',
            name: 'link_target',
            value: '_self',
            params: {clearable: false, label: 'words.link_target', options: {'_self': 'Self', '_blank': 'Blank'}}
        },*/
    ];

    constructor(elementObj, lang = null, withAllTranslations = false) {
        this.withAllTranslations = withAllTranslations;
        switch (elementObj.tag) {
            case 'simpleText':
                this.element = new simpleTextElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'h':
                this.element = new hElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'p':
                this.element = new pElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'div':
                this.element = new divElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'hr':
                this.element = new hrElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'img':
                this.element = new imgElement(elementObj, lang, this.withAllTranslations);
                break;

            // type fields
            case 'text':
                this.element = new textElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'textarea':
                this.element = new textareaElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'number':
                this.element = new numberElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'password':
                this.element = new passwordElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'select':
                this.element = new selectElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'file':
                this.element = new fileElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'switch':
                this.element = new switchElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'divider':
                this.element = new dividerElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'datetime':
                this.element = new datetimeElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'date':
                this.element = new dateElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'dateRange':
                this.element = new dateRangeElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'dateMultiple':
                this.element = new dateMultipleElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'time':
                this.element = new timeElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'radio':
                this.element = new radioElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'checkbox':
                this.element = new checkboxElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'editor':
                this.element = new editorElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'advancedParent':
                this.element = new advancedParentElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'advancedChildOf':
                this.element = new advancedChildOfElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'requiredPublishStart':
                this.element = new requiredPublishStartElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'requiredPublishEnd':
                this.element = new requiredPublishEndElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'requiredRouteName':
                this.element = new requiredRouteNameElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'requiredSeoKeyword':
                this.element = new requiredSeoKeywordElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'requiredSeoDescription':
                this.element = new requiredSeoDescriptionElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'requiredStatus':
                this.element = new requiredStatusElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'requiredTemplate':
                this.element = new requiredTemplateElement(elementObj, lang, this.withAllTranslations);
                break;
            case 'requiredTitle':
                this.element = new requiredTitleElement(elementObj, lang, this.withAllTranslations);
                break;
        }
    }

    get fillableFields () {
        const mergeObj = {};

        for (const elementItem of this.fillable) {
            mergeObj[elementItem.name] = elementItem;
        }

        for (const elementItem of this.element.fillable) {
            mergeObj[elementItem.name] = elementItem;
        }

        if (mergeObj['content'].hide && !mergeObj['text_style']) {
            mergeObj['text_style'] = {
                type: 'select',
                name: 'text_style',
                params: {
                    label: 'words.text_style',
                    multiple: true,
                    options: {
                        'strong': 'Strong',
                        'italic': 'Italic',
                        'strike': 'Strike',
                        'underline': 'Underline',
                    }
                }
            };
        }

        return Object.values(mergeObj).filter(item => !item.hide);
    }

    get tag() {
        return this.element.tag || 'tag';
    }

    set tag(val) {
        this.element.tag = val || 'tag';
    }

    get name() {
        return this.element.name || '';
    }

    set name(val) {
        this.element.name = val || '';
    }

    get id() {
        if (!this.element.id) {
            this.element.id = ++elementId;
        }
        return this.element.id;
    }

    set id(val) {
        this.element.id = val;
    }

    initId() {
        if (!this.element.id) {
            this.newId();
        }
    }

    newId() {
        this.element.id = ++elementId;
    }

    get params() {
        return this.element.params || {};
    }

    set params(val) {
        this.element.params = val;
    }

    getParams(key) {
        return this.element.params[key] || null;
    }

    setParams(key, val) {
        this.element.params[key] = val;
    }

    set json(val) {
        this.element.json = val
    }

    get json() {
        return this.element.json;
    }
}
