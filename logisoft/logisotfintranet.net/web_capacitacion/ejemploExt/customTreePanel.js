Ext.BLANK_IMAGE_URL = 'ext/resources/images/default/s.gif';
Ext.namespace('Ext.org.demo');

	//Clase para creacion de los tabs en la parte derecha
	Ext.ux.IFrameComponent = Ext.extend(Ext.BoxComponent, {       
			onRender : function(ct, position){        
			this.el = ct.createChild({tag: 'iframe', id: 'iframe-'+ this.id, frameBorder: 0, src: this.url});       
		}
	});
	//Panel Superior
	Ext.org.demo.PrincipalHeader = function(config) {
		var tree = config.tree;      
		var myRef = this;      
		this.txtFindObject = new Ext.form.TextField({    
			width: 200,    
			emptyText:'Buscar',    
			listeners:{     
			render: function(f){      
			f.el.on('keydown',        
					function (e){        
					tree.filterTree(e);       
					}, 
					f, {buffer: 350});
			} 
		}
	});
	
	this.btnExpandAll = new Ext.Toolbar.Button({
		iconCls: 'icon-expand-all',    
		tooltip: 'Expandir Todo',
		handler: function(){
		tree.root.expand(true);
		}
	});
	
	this.btnCollapseAll = new Ext.Toolbar.Button({
		iconCls: 'icon-collapse-all',
		tooltip: 'Colapsar Todo',
		handler: function(){
			tree.root.collapse(true);
		}
	});
	
	this.toolBar = new Ext.Toolbar({
		cls:'top-toolbar',
		items:[' ',
		this.txtFindObject,' ',' ',this.btnExpandAll,'-',this.btnCollapseAll]});
	
	config.items = [{
		xtype:'box',
		el:'north',
		border:false,
		anchor: 'none -25'
	},
		this.toolBar
	];
	
	Ext.org.demo.PrincipalHeader.superclass.constructor.call(this, config);   
}

	Ext.extend(Ext.org.demo.PrincipalHeader, Ext.Panel, {
		layout:'anchor',
			initComponent: function(){
			Ext.org.demo.PrincipalHeader.superclass.initComponent.apply(this);
		}
	});
	//Tree
	Ext.org.demo.PrincipalTree = function(config){
		var filter = new Ext.tree.TreeFilter(this, {
		clearBlank: true,
		autoClear: true
	});
	
	var hiddenPkgs = [];
	
	this.filterTree = function filterTree(e){
		
		var text = e.target.value;    
		Ext.each(hiddenPkgs, function(n){
			n.ui.show();
		});
		if(!text){
			filter.clear();
			return;
		}
		this.expandAll();
		var re = new RegExp(Ext.escapeRe(text), 'i');
		filter.filterBy(function(n){
			return !n.attributes.leaf || re.test(n.text);
		});
		hiddenPkgs = []; 
		this.root.cascade(function(n){
			if(!n.attributes.leaf && n.ui.ctNode.offsetHeight < 3){
				n.ui.hide();
				hiddenPkgs.push(n);
			}
		});
	}	
		Ext.org.demo.PrincipalTree.superclass.constructor.call(this, config);
	}
	
	Ext.extend(Ext.org.demo.PrincipalTree, Ext.tree.TreePanel, {
		//enableDD: true,
		minSize: 175,
		maxSize: 300,
		width: 200,
		initComponent: function(){
			Ext.org.demo.PrincipalTree.superclass.initComponent.apply(this);
			}
	});
	
	//Vista Principal, agrupa al panel superior, izquierdo, centro      
	Ext.org.demo.PrincipalView = function(config){
		this.north = config.north;
		this.west = config.west;
		this.center = config.center;
		config.items = [this.north,this.west,this.center];
		
	//Funcion para añadir un tab  
	this.addTab = function addTab(e){
		var tabs = this.center;
		var open = !tabs.getItem(e.id);
		if(open){
			var newPanel = new Ext.Panel({
				id : e.id,
				title: e.text,
				loadScripts: true,
				autoScroll: true,
				closable: true,
				iconCls:e.id,
				//style: "background:url("+e.attributes.icon+")!important",
				//icon : e.attributes.icon,
				layout:'fit',
				items: [ new Ext.ux.IFrameComponent({
						id: e.id,
						url: e.attributes.url,
						name: e.id
						})]
				});
			tabs.add(newPanel);
			tabs.setActiveTab(newPanel);
			}else{
				tabs.setActiveTab(e.id);
				}
			}
			
			//Para controlar el load de los items   
			this.west.on('click', function(node, e){
				if(node.isLeaf()){
					e.stopEvent();
					this.addTab(node);
					}
			},this);
			Ext.org.demo.PrincipalView.superclass.constructor.call(this, config);};
			Ext.extend(Ext.org.demo.PrincipalView, Ext.Viewport, {
				layout:'border'
			});
			Ext.onReady(function(){
				Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
			//LADO IZQUIERDO: Arbol
			var tree = new Ext.org.demo.PrincipalTree({
					rootVisible:false,
					lines: true,
					region:'west',
					split: true,
					autoScroll: true,
					title:'Menu de Opciones',
					iconCls:'principal_mainmenu',
					singleExpand: false,
					collapsible: true,
					layoutConfig:{
						animate:true
					},
					loader: new Ext.tree.TreeLoader({}),
					root: new Ext.tree.AsyncTreeNode({													 
						'children':[{
						'text':'Base',
						'children':[{
						'id':'Ordenes_de_Pedido',
						'icon':'urlDelIcono.png',
						'text':'Ordenes de Pedido',
						'leaf':true,
						'url': 'urlDeLaPagina.html'},{
						'id':'Mantenimiento de Empleados',
						'icon':'urlDelIcono.png',
						'text':'Mantenimiento de Empleados',
						'leaf':true},{
						'id':'Empresa_de_Servicio',
						'icon':'urlDelIcono.png',
						'text':'Empresas de Servicio',
						'leaf':true
						}]},{
						'text':'Segundo Modulo',
						'children':[{
									'id':'Empresa2',
									'icon':'urlDelIcono.png',
									'text':'Empresa 2',
									'leaf':true},{
										'id':'ModuloDePruebas1',
										'icon':'urlDelIcono.png',
										'text':'Modulo de Pruebas 1',
										'leaf':true},{
											'id':'ModuloDePruebas2',
											'icon':'urlDelIcono.png',
											'text':'Modulo de Pruebas 2',
											'leaf':true}]}],
						'expanded':true})});
			//PARTE SUPERIOR: Para las busquedas de objetos en el menu    
			var hd = new Ext.org.demo.PrincipalHeader({          
					border: false,
					region:'north',
					cls: 'docs-header',
					height:60,
					tree : tree
			});
			//Tab Panel (Parte derecha)    
			var tabs = new Ext.TabPanel({
					region:'center',
					deferredRender:false,
					activeTab:0,
					items:[{contentEl:'center1',
						   title: 'Home',
						   iconCls: 'principal_tabhome',
						   html : 'Aqui se puede linkear a una imagen o ingresar cualquier fragmento de html',
						   //autoLoad: 'http://www.google.com.pe/',
						   autoScroll:true
						   }]
					}); 
			var principalView = new Ext.org.demo.PrincipalView({
			   north:hd,
			   west:tree,
			   center:tabs
			   });
			});
	
		
	