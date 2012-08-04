
define("admin/Nbmc",
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

    sf.Nbmc = {
        gridEvent : null,

        gridLayouts : {
            nbmc : [
                {
                    noscroll : true,
                    cells :[
                        {field: 'nbmcId', width: '50px', name: 'Id'},
                        {field: 'invoiceNumber', width: '100px', name: 'Invoice No.'}
                    ]
                },
                {
                    cells : [
                        {field: 'firstname', width: '100px', name: 'Forename'},
                        {field: 'lastname', width: '100px', name: 'Surname'},
                        {field: 'email', width: '210px', name: 'Email'},
                        {field: 'telephone', width: '200px', name: 'Telephone', hidden: true},
                        {field: 'company', width: '200px', name: 'Company', hidden: true},
                        {field: 'address1', width: '200px', name: 'Address 1', hidden: true},
                        {field: 'address2', width: '200px', name: 'Address 2', hidden: true},
                        {field: 'town', width: '200px', name: 'Town/City', hidden: true},
                        {field: 'postcode', width: '100px', name: 'Postcode', hidden: true},
                        {field: 'numberAttending', width: '50px', name: 'No.'},
                        {field: 'proFormaInvoiceSent', width: '50px', name: 'PFIS'},
                        {field: 'recieiedPayment', width: '50px', name: 'Paid'},
                        {field: 'invoiceSent', width: '100px', name: 'Inv Sent'}
                    ]
                }
            ]
        },

        init : function()
        {
            core.addDataStore('nbmcStore', core.storeUrls.nbmc);

            core.addGrid({
                id : 'nbmcGrid',
                store : core.dataStores.nbmcStore,
                structure : this.gridLayouts.nbmc,
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

            con.connect(core.grids.nbmcGrid, 'onRowContextMenu', function(e){
                sf.Nbmc.gridEvent = e;

                selectedItem = e.grid.getItem(e.rowIndex);
                id = e.grid.store.getValue(selectedItem, 'invoiceNumber');

                registry.byId("nbmcView").set('label', 'View Invoice ' + id);
                registry.byId("nbmcEdit").set('label', 'Edit Invoice ' + id);
                registry.byId("nbmcDelete").set('label', 'Delete Invoice ' + id)
            });

            con.connect(registry.byId("nbmcView"), "onClick", function(){
                e = sf.Nbmc.gridEvent;
                selectedItem = e.grid.getItem(e.rowIndex);
                id = e.grid.store.getValue(selectedItem, 'nbmcId');
                sf.Nbmc.gridRowDblClick(id);
            });

            con.connect(registry.byId("nbmcAdd"), "onClick", function(){
                sf.Nbmc.gridMenuClick(this.id)
            });

            con.connect(registry.byId("nbmcEdit"), "onClick", function(){
                sf.Nbmc.gridMenuClick(this.id)
            });

            con.connect(registry.byId("nbmcDelete"), "onClick", function(){
                sf.Nbmc.delMenuClick(this.id)
            });

            con.connect(registry.byId("nbmcExport"), "onClick", function(){
                newWin = window.open('', 'print meter', "height=200,width=600,modal=yes,alwaysRaised=yes,scrollbars=yes");

                xhr.post({
                    url: baseUrl+'/admin/nbmc/csv-file',
                    handleAs: 'text',
                    preventCache: true,
                    load: function(data) {
                        newWin.document.write(data);
                    }
                });
            })
        },

        gridRowDblClick : function(id)
        {
            if(!id) {
                grid = core.grids.nbmcGrid;
                selectedIndex = grid.focus.rowIndex;
                selectedItem = grid.getItem(selectedIndex);
                id = grid.store.getValue(selectedItem, 'nbmcId');
            }

            var cp= dijit.byId('gridRowDetails');
            cp.set('href', baseUrl+'/admin/nbmc/details/nbmcId/'+id);
        },

        delMenuClick : function(type)
        {
            e = sf.Nbmc.gridEvent;
            selectedItem = e.grid.getItem(e.rowIndex);
            id = e.grid.store.getValue(selectedItem, 'nbmcId');
            inv = e.grid.store.getValue(selectedItem, 'invoiceNumber');
            console.log(id);

            comfirmDelete = new Dialog({
                title: "Delete Invoice " + inv,
                content: DeleteMessage,
                style: "width: 300px",
                onShow : function(){
                    con.connect(YesButton, 'onClick', function(){
                        comfirmDelete.hide();
                        //sf.Nbmc.processRequest(type);
                        xhr.post({
                            url: baseUrl+'/admin/nbmc/delete',
                            handleAs: 'text',
                            content: {
                                type :  type,
                                nbmcId : id
                            },
                            preventCache: true,
                            load: function(data) {
                                console.log(data);
                                core.grids.nbmcGrid._refresh();
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
        },

        gridMenuClick : function(type)
        {
            e = sf.Nbmc.gridEvent;
            selectedItem = e.grid.getItem(e.rowIndex);
            id = e.grid.store.getValue(selectedItem, 'nbmcId');
            action = type.replace('nbmc', '').toLowerCase();

            if (!dom.byId('nbmcForm')) {
                sf.openFormDialog({
                    url: baseUrl+'/admin/nbmc/'+action,
                    content: {
                    	type :  action,
                        nbmcId : id
                    },
                    dialog: 'nbmcForm'
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
                url: baseUrl+'/admin/nbmc/save',
                content: values,
                handleAs: 'json',
                preventCache: true,
                load: function(data) {
                    dom.byId('dialog').innerHTML = data.html;
                    parser.parse('dialog');

                    if (data.error) {
                        error.show();
                    } else if (data.saved > 0) {
                        registry.byId('nbmcGrid')._refresh();

                        //if (sf.config.confirmBox) {
                            confirm.show();
                        //}
                    } else {
                        sf.setupDialog(nbmcForm);
                        nbmcForm.show();
                    }
                }
            });
        }
    };

    return sf.Nbmc;

});
