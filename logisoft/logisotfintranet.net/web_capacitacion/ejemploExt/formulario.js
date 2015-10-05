
/*Ext.ns('sistema');

sistema.FormTutorial = {
	init: function(){
		//creamos una instancia del textfield
		var nombre = new Ext.form.TextField({
			fieldLabel:'Nombre',
			name:'txtname',
			//emptyText:'Your name...',
			id:"id-name"
		}); 
		var descripcion = new Ext.form.TextField({
			fieldLabel:'Descripción',
			name:'txtdesc',
			//emptyText:'Your name...',
			id:"txtdesc"
		});
		//creamos un grupo de opciones con radiobuttons
		var radios = new Ext.form.RadioGroup({
			fieldLabel: 'Favorite Framework',		                
			 columns: 2, //muestra los radiobuttons en dos columnas
			 items: [
				  {boxLabel: 'Ext Js', name: 'framework', inputValue: 'Ext js', checked: true},
				  {boxLabel: 'Dojo', name: 'framework', inputValue: 'Dojo'}
			 ]
		});
		
		//creamos un formulario
		this.form= new Ext.FormPanel({
			border:false,
			//layout: 'form',
			defaults:{xtype:'textfield'},	//componente por default del formulario
			items:[
				nombre, // le asignamos la instancia que creamos anteriormente
				descripcion,
				{
					fieldLabel:'Email', // creamos un campo
					name:'txt-email', // a partir de una
					value:'default@quizzpot.com', //configuración
					id:"id-email",
					columns: 2,
					items:[nombre]
				},{
					xtype: 'checkbox', //definimos el tipo de componente
					fieldLabel: 'Active',// le asignamos un label
					name: 'chk-active',//y un "name" para que lo recojamos en el servidor...
					id: 'id-active'// ...cuando el formulario sea enviado
				},				
				radios, // grupo de radios
				{
						xtype:'hidden',//campo oculto (hidden)
						name:'h-type', //el nombre con que se envia al servidor
						value:'developer'//el valor que contendrá
				}
			]
		});
		
			var win = new Ext.Window({
			title: 'New Developer',
			width:300,
			height:300,
			bodyStyle:'background-color:#fff;padding: 10px',
			items:this.form,
			buttonAlign: 'right', //botones alineados a la derecha
			buttons:[{text:'Save'},{text:'Cancel'}] //botones del formulario
		});
		
		win.show();
	}	
}
Ext.onReady(sistema.FormTutorial.init,sistema.FormTutorial);*/

Ext.onReady(function(){

    Ext.QuickTips.init();
	
	var top = new Ext.FormPanel({
        labelAlign: 'top',
		url: 'registro.php',
		id:'mywin',
        frame:true,		
        title: 'Multi Column, Nested Layouts and Anchoring',
        bodyStyle:'padding:5px 5px 0',
        width: 600,
        items: [{
            layout:'column',
            items:[{
                columnWidth:.5,
                layout: 'form',
                items: [{
                    xtype:'textfield',
                    fieldLabel: 'Nombre',
                    name: 'nombre',
                    anchor:'95%'
                }, {
                    xtype:'textfield',
                    fieldLabel: 'Ap. Materno',
                    name: 'materno',
                    anchor:'95%'
                }]
            },{
                columnWidth:.5,
                layout: 'form',
                items: [{
                    xtype:'textfield',
                    fieldLabel: 'Ap. Paterno',
                    name: 'paterno',
                    anchor:'95%'
                },{
                    xtype:'textfield',
                    fieldLabel: 'Email',
                    name: 'email',
                    vtype:'email',
                    anchor:'95%'
                }]
            }]
        }],

        buttons: [{
            text: 'Guardar',handler:function(){
				var mask = new Ext.LoadMask(Ext.get('mywin'), {msg:'Registrando. Favor de Esperar...'});
				mask.show();
				
				//Ext.Msg.alert("sdfsd","dfsd"+top.getForm().getValues(true));
				//top.getForm().submit();
				top.getForm().submit({
					method: 'get',
					params: {
						extraParam: 'Extra params!',
						param2: 'Param 2'
						},
					success: function(top,action){
						mask.hide();
						Ext.Msg.alert('Success',action.result.msg);
					},
					failure: function(top,action){
						mask.hide();
						switch (action.failureType) {
							  case Ext.form.Action.CLIENT_INVALID:
								 Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
								 break;
							  case Ext.form.Action.CONNECT_FAILURE:
								 Ext.Msg.alert('Failure', 'Ajax communication failed');
								 break;
							  case Ext.form.Action.SERVER_INVALID:
								Ext.Msg.alert('Failure', action.result.msg);
								break;
							  default:
								Ext.Msg.alert('Failure',action.result.msg);
						  }
					}
				});			
				
			}
        },{
            text: 'Limpiar'
        }]		
		
    });	

    top.render(document.body);
	
});