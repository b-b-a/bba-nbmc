/*
 * User.js
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

define("admin/User",
[
    "admin/Core",
    "dojo/dom",
    "dojo/parser",
    "dojo/_base/connect",
    "dijit/registry",
    "dojo/_base/xhr",
    "dijit/Dialog",
    "dojo/text!./html/DeleteInvoiceMessage.html"
],
    function(core, dom, parser, con, registry, xhr, Dialog, DeleteMessage) {

    sf.User = {
		gridEvent : null,

        gridLayouts : {
            user : [
                {field: 'userId', width: '50px', name: 'Id'},
                {field: 'firstname', width: '100px', name: 'Forename'},
                {field: 'lastname', width: '100px', name: 'Surname'},
                {field: 'username', width: '100px', name: 'Username'},
                {field: 'role', width: '100px', name: 'Role'}
            ]
        },
        
        gridEvent : null,

        init : function()
        {
            core.addDataStore('userStore', core.storeUrls.user);

            core.addGrid({
                id : 'userGrid',
                store : core.dataStores.userStore,
                structure : this.gridLayouts.user,
                sortInfo : '1',
                plugins: {
                    menus:{
                        headerMenu:"headerMenu",
                        rowMenu:"rowMenu"
                    }
                },
                onRowDblClick : function() {
                     this.gridRowDblClick();
                }.bind(this)
            });
            
            con.connect(core.grids.userGrid, 'onRowContextMenu', function(e){
                sf.User.gridEvent = e;

                selectedItem = e.grid.getItem(e.rowIndex);
                id = e.grid.store.getValue(selectedItem, 'username');

                registry.byId("userView").set('label', 'View User ' + id);
                registry.byId("userEdit").set('label', 'Edit User ' + id);
                registry.byId("userDelete").set('label', 'Delete User ' + id)
            });

            con.connect(registry.byId("userView"), "onClick", function(){
                e = sf.User.gridEvent;
                selectedItem = e.grid.getItem(e.rowIndex);
                id = e.grid.store.getValue(selectedItem, 'userId');
                sf.User.gridRowDblClick(id);
            });

            con.connect(registry.byId("userAdd"), "onClick", function(){
                sf.User.gridMenuClick(this.id)
            });

            con.connect(registry.byId("userEdit"), "onClick", function(){
                sf.User.gridMenuClick(this.id)
            });

            con.connect(registry.byId("userDelete"), "onClick", function(){
                sf.User.delMenuClick(this.id)
            });
        },
        
        gridRowDblClick : function(id)
        {
        	if(!id) {
	            grid = core.grids.userGrid;
	            selectedIndex = grid.focus.rowIndex;
	            selectedItem = grid.getItem(selectedIndex);
	            id = grid.store.getValue(selectedItem, 'userId');
        	}
            
            var cp = registry.byId('gridRowDetails');
            
            cp.set('href', baseUrl+'/admin/user/details/userId/'+id);
        },
        
        gridMenuClick : function(type)
        {
            e = sf.User.gridEvent;
            selectedItem = e.grid.getItem(e.rowIndex);
            id = e.grid.store.getValue(selectedItem, 'userId');

            action = type.replace('user', '').toLowerCase();
            
            if (!dom.byId('userForm')) {
                sf.openFormDialog({
                    url: baseUrl+'/admin/user/'+action,
                    content: { 
                        type :  action,
                        userId : id
                    },
                    dialog: 'userForm'
                });
            } else {
                userForm.show();
            }
            
        },
        
        processForm : function()
        {
            values = arguments[0];
            console.log(values);

            xhr.post({
                url: baseUrl+'/admin/user/save',
                content: values,
                handleAs: 'json',
                preventCache: true,
                load: function(data) {
                    dom.byId('dialog').innerHTML = data.html;
                    parser.parse('dialog');

                    if (data.error) {
                        error.show();
                    } else if (data.saved > 0) {
                        registry.byId('userGrid')._refresh();

                        //if (sf.config.confirmBox) {
                            confirm.show();
                        //}
                    } else {
                        sf.setupDialog(userForm);
                        userForm.show();
                    }
                }
            });
        },
        
        delMenuClick : function(type)
        {
            e = sf.User.gridEvent;
            selectedItem = e.grid.getItem(e.rowIndex);
            id = e.grid.store.getValue(selectedItem, 'userId');
            user = e.grid.store.getValue(selectedItem, 'username');
            console.log(id);

            comfirmDelete = new Dialog({
                title: "Delete Invoice " + user,
                content: DeleteMessage,
                style: "width: 300px",
                onShow : function(){
                    con.connect(YesButton, 'onClick', function(){
                        comfirmDelete.hide();
                        //sf.Nbmc.processRequest(type);
                        xhr.post({
                            url: baseUrl+'/admin/user/delete',
                            handleAs: 'text',
                            content: {
                                type :  type,
                                userId : id
                            },
                            preventCache: true,
                            load: function(data) {
                                console.log(data);
                                core.grids.userGrid._refresh();
                            }
                        });
                    });
                    con.connect(NoButton, 'onClick', function(){
                        comfirmDelete.hide();
                    });
                },
                onHide : function() {
                    sf.closeDialog(comfirmDelete);
                }
            });
            comfirmDelete.show();
        }
    };

    return sf.User;

});


