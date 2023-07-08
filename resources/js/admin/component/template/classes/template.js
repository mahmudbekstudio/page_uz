import hElement from './hElement';
import pElement from './pElement';
import divElement from "./divElement";
import hrElement from "./hrElement";
import { TEMPLATE } from '../../../constants';
import * as _ from 'lodash';
import validation from "../../../config/validation";

export class Template {
    children = [];
    isConstructor = false;

    constructor(params = {}, isConstructor = false) {
        this.isConstructor = isConstructor;

        if(!Object.keys(params).length) {
            this.addRow({}, {});
        } else {
            this.json = params;
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
        {
            type: 'editor',
            name: 'content',
            params: {label: 'words.content', type: 'simple'}
        },
    ];

    constructor(elementObj) {
        switch (elementObj.tag) {
            case 'h':
                this.element = new hElement(elementObj);
                break;
            case 'p':
                this.element = new pElement(elementObj);
                break;
            case 'div':
                this.element = new divElement(elementObj);
                break;
            case 'hr':
                this.element = new hrElement(elementObj);
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

        return Object.values(mergeObj).filter(item => !item.hide);
    }

    get tag() {
        return this.element.tag || 'tag';
    }

    set tag(val) {
        this.element.tag = val || 'tag';
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
