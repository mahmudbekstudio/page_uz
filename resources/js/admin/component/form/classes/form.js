import textareaField from './textareaField';
import optionsField from './optionsField';
import textField from './textField';
import numberField from './numberField';
import passwordField from './passwordField';
import selectField from './selectField';
import fileField from './fileField';
import switchField from "./switchField";
import dividerField from "./dividerField";
import datetimeField from "./datetimeField";
import dateField from "./dateField";
import dateRangeField from "./dateRangeField";
import dateMultipleField from "./dateMultipleField";
import timeField from "./timeField";
import radioField from "./radioField";
import checkboxField from "./checkboxField";
import editorField from "./editorField";
import colorField from "./colorField";
import advancedParentField from './advanced/advancedParentField';
import advancedChildOfField from './advanced/advancedChildOfField';
import requiredPublishStartField from './required/requiredPublishStartField';
import requiredPublishEndField from './required/requiredPublishEndField';
import requiredRouteNameField from './required/requiredRouteNameField';
import requiredSeoKeywordField from './required/requiredSeoKeywordField';
import requiredSeoDescriptionField from './required/requiredSeoDescriptionField';
import requiredStatusField from './required/requiredStatusField';
import requiredTemplateField from './required/requiredTemplateField';
import requiredTitleField from './required/requiredTitleField';
import featureContentField from './featureContentField';
import validationField from './validationField';
import { FORM } from '../../../constants';
import * as _ from 'lodash';
import i18n from "../../../plugin/i18n";
import validation from "../../../config/validation";
import store from "../../../plugin/store";
import {translationObject} from "../../../helper";

export class Form {
    children = [];
    isConstructor = false;

    constructor(params = {}, isConstructor = false) {
        this.isConstructor = isConstructor;

        if(!Object.keys(params).length) {
            this.addTab({title: translationObject('words.main', i18n)}, {}, {});
        } else {
            this.json = params;
        }
    }

    getTab(tab = 0) {
        return this.children[tab];
    }

    addTab(params, rowParams = {}, colParams = {}) {
        const tab = new Tab(params, this.isConstructor);
        tab.addRow(rowParams, colParams);
        this.children.push(tab);
        return tab;
    }

    getRow(row = 0, tab = 0) {
        return this.getTab(tab).getChild(row);
    }

    addRow(params, tab = 0) {
        return this.getTab(tab).addRow(params);
    }

    getCol(col = 0, row = 0, tab = 0) {
        return this.getRow(row, tab).getChild(col);
    }

    addCol(params, tab = 0, row = 0) {
        return this.getRow(row, tab).addCol(params);
    }

    getField(field = 0, col = 0, row = 0, tab = 0) {
        return this.getCol(col, row, tab).getChild(field);
    }

    addField(fieldObj, tab = 0, row = 0, col = 0) {
        return this.getCol(col, row, tab).addField(fieldObj);
    }

    removeFieldByName(name) {
        for (const tab of this.children) {
            if (tab.removeFieldByName(name)) {
                return true;
            }
        }

        return false;
    }

    getFieldByName(name) {
        for (const tab of this.children) {
            const result = tab.getFieldByName(name);
            if (result) {
                return true;
            }
        }

        return null;
    }

    getFieldBykey(key) {
        const keys = key.split(FORM.fieldKeySplitter);
        return keys.length === 4 ? this.getField(keys[3], keys[2], keys[1], keys[0]) : null;
    }

    setFieldValue(key, val, lang) {
        const field = this.getFieldBykey(key);

        if(field) {
            if(lang) {
                if (JSON.stringify(field.value)[0] !== '{')  {
                    field.value = {};
                }
                const fieldValue = field.value;
                fieldValue[lang] = val;
                field.value = fieldValue;
            } else {
                field.value = val;
            }
        }

        return field;
    }

    getFields() {
        let result = [];

        _.forEach(
            this.children,
            tab => result = [...result, ...tab.getFields()]/*_.forEach(
                tab.children,
                row => _.forEach(
                    row.children,
                    col => _.forEach(
                        col.children,
                        field => result.push(field)
                    )
                )
            )*/
        );

        return result;
    }

    getFieldValues() {
        const fields = this.getFields();
        const result = {};

        _.forEach(fields, item => {
            result[item.name] = item.value;
        });

        return result;
    }

    set json(val) {
        for(let i = 0; i < val.length; i++) {
            this.children.push(new Tab(val[i], this.isConstructor));
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

export class Tab {
    children = [];
    type = 'tab';
    title = '';
    isConstructor = false;
    hasError = false;

    constructor(params, isConstructor = false) {
        this.isConstructor = isConstructor;
        //this.addRow(rowParams, colParams);
        this.json = params;
    }

    getChild(index = 0) {
        return this.children[index];
    }

    addRow(params = {}, colParams = {}) {
        const row = new Row(params, this.isConstructor);
        row.addCol(colParams);
        this.children.push(row);
        return row;
    }

    addCol(params, row = 0) {
        return this.getChild(row).addCol(params);
    }

    addField(fieldObj, row = 0, col = 0) {
        return this.getChild(row).getChild(col).addField(fieldObj);
    }

    removeFieldByName(name) {
        for (const row of this.children) {
            if (row.removeFieldByName(name)) {
                return true;
            }
        }

        return false;
    }

    getFieldByName(name) {
        for (const row of this.children) {
            const result = row.getFieldByName(name);
            if (result) {
                return true;
            }
        }

        return null;
    }

    getFields() {
        const result = [];

        _.forEach(
            this.children,
            row => _.forEach(
                row.children,
                col => _.forEach(
                    col.children,
                    field => result.push(field)
                )
            )
        );

        return result;
    }

    set json(val) {
        val.children = val.children || [];
        for(let i = 0; i < val.children.length; i++) {
            this.children.push(new Row(val.children[i], this.isConstructor));
        }

        this.type = val.type || 'tab';
        this.title = val.title || '';
    }

    get json() {
        const result = {
            children: [],
            type: this.type || 'tab',
            title: this.title || ''
        };

        for(let i = 0; i < this.children.length; i++) {
            result.children.push(this.children[i].json)
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

    addField(fieldObj, col = 0) {
        return this.getChild(col).addField(fieldObj);
    }

    removeFieldByName(name) {
        for (const col of this.children) {
            if (col.removeFieldByName(name)) {
                return true;
            }
        }

        return false;
    }

    getFieldByName(name) {
        for (const col of this.children) {
            const result = col.getFieldByName(name);
            if (result) {
                return true;
            }
        }

        return null;
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

    addField(fieldObj) {
        const field = new Field({...fieldObj, isConstructor: this.isConstructor});
        this.children.push(field);
        return field;
    }

    removeFieldByName(name) {
        for (const fieldIndex in this.children) {
            if (this.children[fieldIndex].name === name) {
                this.children.splice(fieldIndex, 1);
                return true;
            }
        }

        return false;
    }

    getFieldByName(name) {
        for (const fieldIndex in this.children) {
            if (this.children[fieldIndex].name === name) {
                return this.children[fieldIndex];
            }
        }

        return null;
    }

    set json(val) {
        val.children = val.children || [];
        for(let i = 0; i < val.children.length; i++) {
            this.children.push(new Field({...val.children[i], isConstructor: this.isConstructor}));
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

let fieldId = 0;

export class Field {
    field = {};
    fieldTitle = '';
    fillable = [
        {
            type: 'text',
            name: 'name',
            hasLang: false,
            params: {label: 'words.name', rules: [validation.required('words.name')]}
        },
        {
            type: 'text',
            name: 'label',
            params: {label: 'words.label', rules: [validation.required('Label')]}
        },
        {
            type: 'text',
            name: 'value',
            params: {label: 'words.default_value'}
        },
        {
            type: 'validation',
            name: 'validation',
            params: {label: 'words.validation'}
        }
    ];

    constructor(fieldObj) {
        switch (fieldObj.type) {
            case 'text':
                this.field = new textField(fieldObj);
                break;
            case 'number':
                this.field = new numberField(fieldObj);
                break;
            case 'password':
                this.field = new passwordField(fieldObj);
                break;
            case 'textarea':
                this.field = new textareaField(fieldObj);
                break;
            case 'options':
                this.field = new optionsField(fieldObj);
                break;
            case 'select':
                this.field = new selectField(fieldObj);
                break;
            case 'file':
                this.field = new fileField(fieldObj);
                break;
            case 'switch':
                this.field = new switchField(fieldObj);
                break;
            case 'divider':
                this.field = new dividerField(fieldObj);
                break;
            case 'datetime':
                this.field = new datetimeField(fieldObj);
                break;
            case 'date':
                this.field = new dateField(fieldObj);
                break;
            case 'dateRange':
                this.field = new dateRangeField(fieldObj);
                break;
            case 'dateMultiple':
                this.field = new dateMultipleField(fieldObj);
                break;
            case 'time':
                this.field = new timeField(fieldObj);
                break;
            case 'radio':
                this.field = new radioField(fieldObj);
                break;
            case 'checkbox':
                this.field = new checkboxField(fieldObj);
                break;
            case 'editor':
                this.field = new editorField(fieldObj);
                break;
            case 'color':
                this.field = new colorField(fieldObj);
                break;
            case 'validation':
                this.field = new validationField(fieldObj);
                break;
            case 'advancedParent':
                this.field = new advancedParentField(fieldObj);
                break;
            case 'advancedChildOf':
                this.field = new advancedChildOfField(fieldObj);
                break;
            case 'requiredPublishStart':
                this.field = new requiredPublishStartField(fieldObj);
                break;
            case 'requiredPublishEnd':
                this.field = new requiredPublishEndField(fieldObj);
                break;
            case 'requiredRouteName':
                this.field = new requiredRouteNameField(fieldObj);
                break;
            case 'requiredSeoKeyword':
                this.field = new requiredSeoKeywordField(fieldObj);
                break;
            case 'requiredSeoDescription':
                this.field = new requiredSeoDescriptionField(fieldObj);
                break;
            case 'requiredStatus':
                this.field = new requiredStatusField(fieldObj);
                break;
            case 'requiredTemplate':
                this.field = new requiredTemplateField(fieldObj);
                break;
            case 'requiredTitle':
                this.field = new requiredTitleField(fieldObj);
                break;
            case 'featureContent':
                this.field = new featureContentField(fieldObj);
        }
    }

    get fillableFields () {
        const mergeObj = {};

        for (const item of this.fillable) {
            if(item.name === 'value') {
                item.hasLang = this.field.hasLang;
            }
            mergeObj[item.name] = item;
        }

        for (const item of this.field.fillable) {
            mergeObj[item.name] = item;
        }

        return Object.values(mergeObj).filter(item => !item.hide);
    }

    get title() {
        return this.fieldTitle || this.type;
    }

    set title(val) {
        this.fieldTitle = val;
    }

    get type() {
        return this.field.type || 'text';
    }

    set type(val) {
        this.field.type = val || 'text';
    }

    get id() {
        if (!this.field.id) {
            this.field.id = ++fieldId;
        }
        return this.field.id;
    }

    set id(val) {
        this.field.id = val;
    }

    initId() {
        if (!this.field.id) {
            this.newId();
        }
    }

    newId() {
        this.field.id = ++fieldId;
    }

    get disabled() {
        return this.field.disabled || false;
    }

    set disabled(val) {
        this.field.disabled = val;
    }

    get hide() {
        return this.field.hide || false;
    }

    set hide(val) {
        this.field.hide = val;
    }

    get value() {
        let value = typeof this.field.value !== 'undefined' ? this.field.value : null;

        if (typeof value === 'string' && value.length && value[0] === '{' && value[value.length - 1] === '}') {
            value = JSON.parse(value);
        }

        return value;
    }

    set value(val) {
        this.field.value = val;
    }

    get name() {
        return this.field.name || null;
    }

    set name(val) {
        this.field.name = val;
    }

    get params() {
        return this.field.params || {};
    }

    set params(val) {
        this.field.params = val;
    }

    getParams(key) {
        return this.field.params[key] || null;
    }

    setParams(key, val) {
        this.field.params[key] = val;
    }

    get events() {
        return this.field.events || {};
    }

    set events(val) {
        this.field.events = val;
    }

    getEvents(key) {
        return this.field.events[key] || null;
    }

    setEvents(key, val) {
        this.field.events[key] = val;
    }

    set json(val) {
        this.field.json = val
    }

    get json() {
        return this.field.json;
    }
}

export function hasTypeLang(type) {
    const fieldObj = new Field({type});
    return fieldObj.field.hasLang;
}
