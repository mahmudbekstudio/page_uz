import textareaField from './textareaField';
import textField from './textField';
import passwordField from './passwordField';
import selectField from './selectField';
import fileField from './fileField';
import switchField from "./switchField";
import dividerField from "./dividerField";
import datetimeField from "./datetimeField";
import dateField from "./dateField";
import timeField from "./timeField";
import radioField from "./radioField";
import checkboxField from "./checkboxField";
import editorField from "./editorField";
import { FORM } from '../../../constants';
import * as _ from 'lodash';
import i18n from "../../../plugin/i18n";

export class Form {
    children = [];

    constructor(params = {}) {
        if(!Object.keys(params).length) {
            this.addTab({title: i18n.t('words.main')}, {}, {});
        } else {
            this.json = params;
        }
    }

    getTab(tab = 0) {
        return this.children[tab];
    }

    addTab(params, rowParams = {}, colParams = {}) {
        const tab = new Tab(params);
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

    setFieldValue(key, val) {
        const keys = key.split(FORM.fieldKeySplitter);
        const field = keys.length === 4 ? this.getField(keys[3], keys[2], keys[1], keys[0]) : null;

        if(field) {
            field.value = val;
        }
    }

    getFields() {
        const result = [];

        _.forEach(
            this.children,
            tab => _.forEach(
                tab.children,
                row => _.forEach(
                    row.children,
                    col => _.forEach(
                        col.children,
                        field => result.push(field)
                    )
                )
            )
        );

        return result;
    }

    getFieldValues() {
        const fields = this.getFields();
        const result = {};

        _.forEach(fields, item => result[item.name] = item.value);

        return result;
    }

    set json(val) {
        for(let i = 0; i < val.length; i++) {
            this.children.push(new Tab(val[i]));
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

    constructor(params) {
        //this.addRow(rowParams, colParams);
        this.json = params;
    }

    getChild(index = 0) {
        return this.children[index];
    }

    addRow(params = {}, colParams = {}) {
        const row = new Row(params);
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

    set json(val) {
        val.children = val.children || [];
        for(let i = 0; i < val.children.length; i++) {
            this.children.push(new Row(val.children[i]));
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

    constructor(params) {
        //this.addCol(colParams)
        this.json = params;
    }

    getChild(index = 0) {
        return this.children[index];
    }

    addCol(params) {
        const col = new Col(params);
        this.children.push(col);
        return col;
    }

    addField(fieldObj, col = 0) {
        return this.getChild(col).addField(fieldObj);
    }

    set json(val) {
        val.children = val.children || [];
        for(let i = 0; i < val.children.length; i++) {
            this.children.push(new Col(val.children[i]));
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

    constructor(params) {
        this.json = params;
    }

    getChild(index = 0) {
        return this.children[index];
    }

    addField(fieldObj) {
        const field = new Field(fieldObj);
        this.children.push(field);
        return field;
    }

    set json(val) {
        val.children = val.children || [];
        for(let i = 0; i < val.children.length; i++) {
            this.children.push(new Field(val.children[i]));
        }

        this.type = this.type || 'col';
        this.size = this.size || '12';
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

    constructor(fieldObj) {
        switch (fieldObj.type) {
            case 'text':
                this.field = new textField(fieldObj);
                break;
            case 'password':
                this.field = new passwordField(fieldObj);
                break;
            case 'textarea':
                this.field = new textareaField(fieldObj);
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
        }
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

    get disabled() {
        return this.field.disabled || false;
    }

    set disabled(val) {
        this.field.disabled = val;
    }

    get value() {
        return typeof this.field.value !== 'undefined' ? this.field.value : null;
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
