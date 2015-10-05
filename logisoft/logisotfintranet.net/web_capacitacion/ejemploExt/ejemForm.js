// JavaScript Document
Ext.onReady(function() {
	Ext.QuickTips.init();
	/*
     * Creamos el registro de datos
     */
    var categoriesRecord = new Ext.data.Record.create([
        {name: 'cad_id', type: 'int'},
        {name: 'cad_name', type: 'string'},
        {name: 'cad_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'cad_description', type: 'string'}
    ]);

	var categoriesFormReader = new Ext.data.JsonReader(
            {
                root : 'data',
                successProperty : 'success',
                totalProperty: 'total',
                id: 'cad_id'
            },categoriesRecord
    );

	Ext.form.JsonErrorReader = function() {
    Ext.form.JsonErrorReader.superclass.constructor.call(this, {
        root : 'data',
        successProperty : 'success',
        totalProperty: 'total'
     },
     [
        {name: 'id'},
        {name: 'msg'}
     ]);
    };

    Ext.extend(Ext.form.JsonErrorReader, Ext.data.JsonReader);

    var formCategories = new Ext.FormPanel({
        frame : true,
        width : 624,
        height: 346,
        waitMsgTarget : true,
        reader: categoriesFormReader,
        errorReader: new Ext.form.JsonErrorReader(),
        items : [{
                fieldLabel : 'Categoría',
                xtype: 'textfield',
                name : 'cad_name',
                allowBlank:false,
                width : 430
            }, {
                fieldLabel : 'Descripción',
                name : 'cad_description',
                allowBlank:false,
                xtype: 'textarea',
                width : 430,
                height: 225
            }, {
                fieldLabel : 'Fecha',
                name : 'cad_date',
                allowBlank:false,
                xtype: 'datefield',
                renderer: Ext.util.Format.dateRenderer('d/m/Y'),
                width : 430
            }, {
                name : 'cad_id',
                xtype: 'hidden'
            }],
    });

    var submitFormCategories = formCategories.addButton({
        text : 'Guardar',
        disabled : false,
        handler : function() {
            formCategories.getForm().submit({
                url : 'formSaver.php',
                waitMsg : 'Salvando datos...',
                failure: function (form, action) {
                    Ext.MessageBox.show({
                        title: 'Error al salvar los datos',
                        msg: 'Error al salvar los datos.',
                        buttons: Ext.MessageBox.OK,
                        icon: Ext.MessageBox.ERROR
                    });
                },
                success: function (form, request) {
                    Ext.MessageBox.show({
                        title: 'Datos salvados correctamente',
                        msg: 'Datos salvados correctamente',
                        buttons: Ext.MessageBox.OK,
                        icon: Ext.MessageBox.INFO
                    });
                    responseData = Ext.util.JSON.decode(request.response.responseText);
                    formCategories.getForm().load({
                        url : 'formLoader.php',
                        method: 'GET',
                        params: {
                            cat_id: responseData.cat_id
                        },
                        waitMsg : 'Espere por favor'
                    });
                }
            });
        }
    });

    var windowForm = new Ext.Window({
        closable: true,
        resizable: true,
        modal: false,
        border: true,
        plain: true,
        closeAction: 'hide',
        title: 'Layout Tab',
        width: 640,
        height: 380,
        renderTo: 'divRender',
        items: [formCategories]
    });

    formCategories.getForm().load({
        url : 'formLoader.php',
        method: 'GET',
        params: {
            cat_id: 1
        },
        waitMsg : 'Espere por favor'
    });
    windowForm.show();
});