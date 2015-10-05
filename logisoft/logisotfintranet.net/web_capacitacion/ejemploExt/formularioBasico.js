// JavaScript Document

Ext.ns('paqueteria');

Ext.BLANK_IMAGE_URL = '../ext/resources/images/default/s.gif';

paqueteria.FormTutorial = {
	init: function(){
		
		//creamos una instancia del textfield
		var name = new Ext.form.TextField({
			fieldLabel:'Name',
			name:'txt-name',
			emptyText:'Your name...',
			id:"id-name"
		});
		
		//creamos un grupo de checkboxes
		var checkboxes = new Ext.form.CheckboxGroup({
			fieldLabel:'Interests',
			columns:2,//mostrar dos columnas de checkboxes
			items:[
				{boxLabel: 'JavaScript', name: 'cb-js', checked: true}, //campo marcado desde el principio
				{boxLabel: 'HTML', name: 'cb-html'},
				{boxLabel: 'CSS', name: 'cb-css'},
				{boxLabel: 'Otros', name: 'cb-otros'}
			]
		});
		
		//creamos un grupo de opciones con radiobuttons
		var radios = new Ext.form.RadioGroup({
			fieldLabel: 'Favorite Framework',
			 columns: 2, //muestra los radiobuttons en dos columnas
			 items: [
				  {boxLabel: 'Ext Js', name: 'framework', inputValue: 'Ext js', checked: true},
				  {boxLabel: 'Dojo', name: 'framework', inputValue: 'Dojo'},
				  {boxLabel: 'Mootools', name: 'framework', inputValue: 'Mootools'},
				  {boxLabel: 'jQuery', name: 'framework', inputValue: 'jQUery'},
				  {boxLabel: 'prototype', name: 'framework', inputValue: 'prototype'},
				  {boxLabel: 'YIU', name: 'framework', inputValue: 'yui'}
			 ]
		});
		
		////creamos un formulario		
		this.form= new Ext.FormPanel({
			//VAMOS A USAR UNA VENTANA Y TENEMOS QUE QUITAR LO SIGUIENTE COMENTADO
			//DEJAMOS bodyStyle:'padding: 10px' y agregamos border:false,
			
			title:'New Developer',
			renderTo: 'frame',
			defaults:{xtype:'textfield'},	//componente por defecto del formulario
			//border:false,
			bodyStyle:'padding: 10px', //alejamos los componentes del formulario de los bordes
			
			items:[
				name, // le asignamos la instancia que creamos anteriormente
				{
					fieldLabel:'Email', // creamos un campo
					name:'txt-email', // a partir de una
					value:'default@quizzpot.com', //configuración
					id:"id-email"
				},{
					xtype: 'checkbox', //definimos el tipo de componente
					fieldLabel: 'Active',// le asignamos un label
					name: 'chk-active', //y un "name" para que lo recojamos en el servidor...
					id: 'id-active'// ...cuando el formulario sea enviado
				},
				checkboxes,//grupo de checkboxe
				radios,//grupo de radiobutton
				{
					xtype:'hidden',//<-- campo oculto (hidden)
					name:'h-type', //el nombre con que se envia al servidor
					value:'developer'//el valor que contendrá
				}
			],
			buttonAling: 'right',
			buttons:[{text:'Save'},{text:'Cancel'}] //<-- botones del formulario
		});
		
		/*var win = new Ext.Window({
			title: 'New Developer',
			width:300,
			height:300,
			bodyStyle:'background-color:#fff;padding: 10px',
			items:this.form,
			buttonAlign: 'right', //botones alineados a la derecha
			buttons:[{text:'Save'},{text:'Cancel'}] //botones del formulario
		});
		
		win.show();*/
	}	

}

Ext.onReady(paqueteria.FormTutorial.init,paqueteria.FormTutorial);