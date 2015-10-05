// JavaScript Document
/*
	@uthor: Lic. Papayo
*/

Ext.onReady(function(){
    Ext.QuickTips.init();
    // turn on validation errors beside the field globally
    Ext.form.Field.prototype.msgTarget = 'side';

		var txtNombre = new Ext.form.TextField({
				name: 'nombre',
				fieldLabel:'Nombre',
				width: 250,
				x: 70,
				y: 5,
				allowBlank: false,
				blankText: 'Debe capturar Nombre.',
				enableKeyEvents: true,
				selectOnFocus: true,
				listeners: {
					keypress: function(t,e){
						if(e.getKey()==13){
							txtPaterno.focus();
						}
					}
				}
		});
		
		var txtPaterno = new Ext.form.TextField({
				name: 'paterno',
				fieldLabel:'Ap. Paterno',				
				width: 250,
				x: 70,
				y: 35,
				allowBlank: false,
				blankText: 'Debe capturar Apellido Paterno.',
				enableKeyEvents: true,
				selectOnFocus: true,
				listeners: {
					keypress: function(t,e){
						if(e.getKey()==13){
							txtMaterno.focus();
						}
					}
				}
		});
		
		var txtMaterno = new Ext.form.TextField({
				name: 'materno',
				fieldLabel:'Ap. Materno',				
				width: 250,
				x: 70,
				y: 35,
				allowBlank: false,
				blankText: 'Debe capturar Apellido Materno.',
				enableKeyEvents: true,
				selectOnFocus: true,
				listeners: {
					keypress: function(t,e){
						if(e.getKey()==13){
							txtRfc.focus();
						}
					}
				}
		});
		
		var txtRfc = new Ext.form.TextField({
				name: 'rfc',
				fieldLabel:'R.F.C.',
				width: 250,
				x: 70,
				y: 35,
				allowBlank: false,
				blankText: 'Debe capturar RFC.',
				enableKeyEvents: true,
				selectOnFocus: true,
				listeners: {
					keypress: function(t,e){
						if(e.getKey()==13){
							txtEmail.focus();
						}
					}
				}
		});
		
		var txtEmail = new Ext.form.TextField({
				name: 'email',
				fieldLabel:'Email',
				width: 250,
				x: 70,
				y: 35,
				//allowBlank: false,
				//blankText: 'Debe capturar RFC.',
				enableKeyEvents: true,
				selectOnFocus: true,
				listeners: {
					keypress: function(t,e){
						if(e.getKey()==13){
							txtCelular.focus();
						}
					}
				}
		});
		
		var txtCelular = new Ext.form.TextField({
				name: 'celular',
				fieldLabel:'Celular',
				width: 250,
				x: 70,
				y: 35,
				//allowBlank: false,
				//blankText: 'Debe capturar RFC.',
				enableKeyEvents: true,
				selectOnFocus: true,
				listeners: {
					keypress: function(t,e){
						if(e.getKey()==13){
							txtSitio.focus();
						}
					}
				}
		});
		
		var txtSitio = new Ext.form.TextField({
				name: 'sitio',
				fieldLabel:'Sitio Web',
				width: 250,
				x: 70,
				y: 35,
				//allowBlank: false,
				//blankText: 'Debe capturar RFC.',
				enableKeyEvents: true,
				selectOnFocus: true,
				listeners: {
					keypress: function(t,e){
						if(e.getKey()==13){
							btnAceptar.focus();
						}
					}
				}
		});	

		// botones
		var btnAceptar = new Ext.Button({
		    id: 'btnAceptar',
			x: 85,
			y: 75,
			text: 'Aceptar',
			icon: 'entrar.png',
			iconCls: 'x-btn-text-icon',
			minWidth: 80,
			handler:function(){
				top.validarRegistros();
			} 
		});
		
		var btnLimpiar = new Ext.Button({
		    id: 'btnLimpiar',
			x: 170,
			y: 75,
			text: 'Limpiar',
			icon: 'limpiar.png',
			iconCls: 'x-btn-text-icon',
			minWidth: 80,
			handler:function(){
				var frm = top.getForm();
				frm.reset();
				frm.clearInvalid();
				txtNombre.focus(true, 100);
			} 
		});
		
		var top = new Ext.FormPanel({
			labelAlign: 'top',
			frame:true,
			title: 'Catálogo de Clientes',
			bodyStyle:'padding:5px 5px 0',
			width: 600,
			items: [{
				layout:'column',
				items:[{
					columnWidth:.5,
					layout: 'form',
					items: [txtNombre,txtMaterno]
				},{
					columnWidth:.5,
					layout: 'form',
					items: [txtPaterno,txtRfc]
				},{
					columnWidth:.5,
					layout: 'form',
					items: [txtEmail,txtSitio]
				},{
					columnWidth:.5,
					layout: 'form',
					items: [txtCelular]
				}]
			}],
			buttons:[btnAceptar,btnLimpiar],
			
			validarRegistros: function(){				
				if (top.getForm().isValid()) {
					top.getForm().submit({
						url: 'catalogoCliente.php',
						method: 'POST',
						waitTitle: 'Registrando',
						waitMsg: 'Espere Por Favor..',
						success: function(form, action){
							Ext.Msg.alert('Catálogo de Clientes', action.result.msg, function(){
								txtNombre.focus(true);
							});							
						},
						failure: function(form, action){
							Ext.Msg.alert('Catálogo de Clientes', action.result.msg);
							/*if (action.failureType == 'server') {
								var data = Ext.util.JSON.decode(action.response.responseText);
								Ext.Msg.alert('Catálogo de Clientes', data.errors.reason, function(){
									txtNombre.focus(true, 100);
								});
							}*/							
							//top.getForm().reset();
						}
					});
				}
			}
    });

    top.render(document.body);
		
});