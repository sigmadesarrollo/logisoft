Ext.ns('com.quizzpot.tutorial');

Ext.BLANK_IMAGE_URL = '../ext/resources/images/default/s.gif';

com.quizzpot.tutorial.SubmitFormTutorial = {
	init: function(){
		this.form = new Ext.form.FormPanel({
			//standardSubmit: true, // traditional submit
			url: 'submitform.php',
			border:false,
			labelWidth: 80,
			defaults: {
				xtype:'textfield',
				width: 150
			},
			items:[
				{fieldLabel:'Title',name:'title', allowBlank:false},
				{xtype:'combo',fieldLabel:'Year',name:'year',triggerAction:'all',store:[2009,2008,2007,2006]},
				{xtype:'numberfield',fieldLabel:'Revenues',name:'revenues'},
				{xtype:'textarea',fieldLabel:'Comment',name:'comment'},
				{xtype:'checkbox',fieldLabel:'',labelSeparator:'',boxLabel:'Available',name:'available'}
			]
		});

		this.win = new Ext.Window({
			id:'mywin',
			title: 'Submit data to the Server',
			bodyStyle: 'padding:10px;background-color:#fff;',
			width:300,
			height:270,
			items:[this.form],
			buttons: [{text:'Save',handler:this.sendData,scope:this},{text:'Cancel'}]
		});

		this.win.show();
		
	},
			
	sendData: function(){
		//submit the form
		var mask = new Ext.LoadMask(Ext.get('mywin'), {msg:'Saving. Please wait...'});
		mask.show();
		this.form.getForm().submit({
			method: 'get',
			params: {
				extraParam: 'Extra params!',
				param2: 'Param 2'
				},
			success: function(form,action){
				mask.hide();
				Ext.Msg.alert('Success',action.result.msg);
			},
			failure: function(form,action){
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
}

Ext.onReady(com.quizzpot.tutorial.SubmitFormTutorial.init,com.quizzpot.tutorial.SubmitFormTutorial);
