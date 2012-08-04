/*
 * Core.js
 *
 * Copyright (c) 2012 Shaun Freeman <shaun@shaunfreeman.co.uk>.
 *
 * This file is part of BBA NBMC.
 *
 * BBA NBMC is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * BBA NBMC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BBA NBMC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   BBA_NBMC
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

define("admin/Core",
[
    "dojo/dom",
    "dojo/_base/fx",
    "dojo/dom-construct",
    "dojo/ready",
    "dojo/parser",
    "dojo/_base/connect",
    "dojo/_base/xhr",
    "dojo/_base/lang",
    "dijit/registry",
    "dijit/WidgetSet",
    "dijit/Dialog",
    //"dojox/grid/DataGrid",
    "dojox/grid/EnhancedGrid",
    //"dojo/data/ItemFileWriteStore",
    "dojox/data/QueryReadStore",
    "dojox/grid/enhanced/plugins/Menu",
    "dojox/grid/enhanced/plugins/IndirectSelection",
    "dojox/grid/enhanced/plugins/exporter/CSVWriter",
    "dojox/grid/cells/dijit",

    "dijit/layout/BorderContainer",
    "dijit/layout/TabContainer",
    "dijit/layout/ContentPane",
    "dijit/layout/AccordionContainer",
    "dijit/layout/LinkPane",

    "dijit/Menu",
    "dijit/MenuBar",
    "dijit/MenuBarItem",
    "dijit/MenuItem",
    "dijit/MenuSeparator",
    "dijit/PopupMenuBarItem",
    "dijit/PopupMenuItem",
    "dojox/widget/PlaceholderMenuItem",
    "dijit/form/Form",
    "dijit/form/Button",
    "dijit/form/TextBox",
    "dijit/form/SimpleTextarea",
    "dijit/form/FilteringSelect"
],
    function(dom, fx, domConstruct, ready, parser, connect, xhr, lang, registry, WidgetSet, Dialog, DataGrid, QueryReadStore) {

    
    ready(function () {
    	sf.init();
    });

    sf = {
        gridMessage : '<span class="dojoxGridNoData">No records found matching query</span>',

        deferredFunction : function() {},

        storeUrls : {
            nbmc : baseUrl+'/admin/nbmc/data-store',
            user : baseUrl+'/admin/user/data-store'
        },

        dataStores : {},

        grids : {},

        init : function()
        {
            if (lang.isFunction(sf[sfModule].init)) {
                this[sfModule].init();
            }

            dom.byId(sfModule.toLowerCase()).focus();

            this.gridSearch(
                registry.byId('Search'),
                registry.byId(sfModule.toLowerCase() + 'Grid')
            );

            this.pageLoaded();
        },

        pageLoaded : function()
        {
            setTimeout(function(){
                var loader = dom.byId("loader");
                fx.fadeOut({
                    node: loader,
                    duration: 250,
                    onEnd: function(){
                        loader.style.display = "none";
                    }
                }).play();
            }, 500);

            if (registry.byId('error')) {
                error.show();
            }
        },

        menuClick : function(e)
        {
            dojo.stopEvent(e);
            console.log(e.target.href)
            location.href = e.target.href;
        },

        addDataStore : function(id, url)
        {
            this.dataStores[id] = new QueryReadStore({
                url : url,
                requestMethod : 'post'
            });
        },

        addGrid : function(options)
        {
            this.grids[options.id] = new DataGrid(lang.mixin({
                noDataMessage : this.gridMessage
            }, options), dom.byId(options.id));

            this.grids[options.id].startup();

        },

        gridSearch : function(form, grid)
        {
            connect.connect(form, 'onSubmit', function(e) {
                e.preventDefault();
                var values = form.getValues();
                delete values.reset;
                delete values.submit;
                grid.setQuery(values);
            });
        },

        openFormDialog : function(options)
        {
            def = xhr.post({
                url: options.url,
                content: options.content,
                handleAs: 'text',
                preventCache: true,
                load: function(data) {
                    dom.byId('dialog').innerHTML = data;
                    parser.parse('dialog');
                    dialog = registry.byId(options.dialog);

                    if (dialog) {
                        sf.setupDialog(dialog);
                    } else if (!registry.byId('login')) {
                        dialog = sf.errorDialog(data);
                    } else {
                        dialog = registry.byId('login');
                    }

                    dialog.show();
                },
                error: function(error) {
                    sf.showXhrError(error);
                }
            });

            def.then(options.deferredFunction);
        },

        setupDialog : function(dialog)
        {
            ws = new WidgetSet();
            ws.add(dialog);
            selects = registry.byClass("dijit.form.FilteringSelect");
            selects.forEach(function(widget){
                connect.connect(widget, 'onClick', widget._startSearchAll);
            });
            connect.connect(dialog, 'onHide', function() {
                sf.closeDialog(dialog);
            });
        },

        closeDialog : function(dialog, funct)
        {
            dialog.hide();
            dialog.destroyRecursive();

            if (funct) funct;
        },

        errorDialog : function(data)
        {
            data = domConstruct.create("pre", {innerHTML: data});
            return new Dialog({
                title : 'BBA System Error',
                content : data,
                onHide : function() {
                    sf.closeDialog(dialog);
                }
            });
        },

        dataStoreError : function(requestUrl, query)
        {
            xhr.post({
                url: requestUrl,
                content: query,
                handleAs: 'text',
                preventCache: true,
                load: function(data) {
                    dom.byId('errorDialog').innerHTML = data;
                    parser.parse('errorDialog');

                    pattern = /Fatal error/;
                    if (pattern.test(data)) {
                        dialog = sf.errorDialog(data);
                    } else {
                        dialog = (registry.byId("login")) ? registry.byId("login") : registry.byId("error");
                    }

                    dialog.show();
                },
                error: function(error) {
                    sf.showXhrError(error);
                }
            });
        },

        showXhrError : function(data)
        {
            dom.byId('errorDialog').innerHTML = data.responseText;
            parser.parse('errorDialog');
            error.show();
        }
    };

    return sf;

});


