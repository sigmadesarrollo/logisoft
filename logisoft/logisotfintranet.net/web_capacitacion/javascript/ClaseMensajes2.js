// JavaScript Document
function ClaseMensajes(){
	this.show			= f_show;
	this.popup			= f_popup;
	this.iniciar		= f_iniciar;
	this.retroceder		= f_retroceder;
	this.cerrar			= f_cerrar;
	this.getMensaje		= f_getMensaje;
	
	function f_iniciar(direccion, movible){
		this.cm=0;
		this.direccion = direccion;
		if(direccion!="" && direccion!=undefined)
			this.direccion += "/"
		else
			this.direccion = "";
		
		this.movible = (movible == true)?true:false;
	}
	
	function f_retroceder(){
		this.cm--;
	}
	
	function f_cerrar(){
		document.body.removeChild(document.getElementById('msg_bloqueador'+(this.cm)));
		this.cm--;
	}
	
	function f_getMensaje(){
		return this.cm-1;
	}
	
	function f_show(tipo, mensaje, titulo, enfoque, accion1, accion2){
		if(tipo=="" || tipo==undefined){
			tipo="Alerta";
		}
		
		var vaccion1 = "";
		var vaccion2 = "";
		
		if(accion1!="" && accion1 != undefined){
			vaccion1 = accion1+";";
		}
		if(accion2!="" && accion2 != undefined){
			vaccion2 = accion2+";";
		}
		
		var enfcerrar = "";
		if(enfoque!="" && enfoque!=undefined){
			if(this.cm==0){
				enfcerrar = "document.all."+enfoque+".focus();";
			}
		}
		
		var botonselec = "";
		switch(tipo){
			case "A":
				var imagen = "alerta";
				var botones = '<input type="image" name="btnaceptar_'+this.cm+'" src="'+this.direccion+'imgmsg/aceptar.gif" alt="Aceptar" title="Aceptar" style="cursor: pointer;"'
				+' onclick=" '+enfcerrar+' document.body.removeChild(document.getElementById(\'msg_bloqueador'+this.cm+'\'));  mens.retroceder(); return false;" />';
				botonselec = "btnaceptar_";
				break;
			case "I":
				var imagen = "info";
				var botones = '<input type="image" name="btnaceptar_'+this.cm+'" src="'+this.direccion+'imgmsg/aceptar.gif" alt="Aceptar" title="Aceptar" style="cursor: pointer;"'
				+' onclick=" '+enfcerrar+' document.body.removeChild(document.getElementById(\'msg_bloqueador'+this.cm+'\')); mens.retroceder(); return false;" />';
				botonselec = "btnaceptar_";
				break;
			case "C":
				var imagen = "confirmar";
				var botones = '<input type="image" name="btnaceptar_'+this.cm+'" src="'+this.direccion+'imgmsg/aceptar.gif" alt="Aceptar" title="Aceptar" style="cursor: pointer;"'
				+' onclick="'+enfcerrar+vaccion1+' document.body.removeChild(document.getElementById(\'msg_bloqueador'+this.cm+'\')); mens.retroceder(); return false;" />'
				+'<input type="image" name="btncancelar_'+this.cm+'" src="'+this.direccion+'imgmsg/cancelar.gif" alt="Aceptar" title="Aceptar" style="cursor: pointer;"'
				+' onclick="'+enfcerrar+vaccion2+' document.body.removeChild(document.getElementById(\'msg_bloqueador'+this.cm+'\'));  mens.retroceder(); return false;" />';
				botonselec = "btncancelar_";
				break;
		}
		
		try{
			var capa;
			capa=document.createElement('div');    
			capa.setAttribute('id','msg_bloqueador'+this.cm);        
			document.body.appendChild(capa);
			capa = document.getElementById('msg_bloqueador'+this.cm); 
			
			var Tamano = new Array();
			Tamano[0]="";
			Tamano[1]="";
			if (typeof window.innerWidth != 'undefined')  {   
				Tamano[0]=window.innerWidth;
				Tamano[1]=window.innerHeight;  
			}  else if (typeof document.documentElement != 'undefined' && 
			typeof document.documentElement.clientWidth != 'undefined' && 
			document.documentElement.clientWidth != 0)  { 
				Tamano[0]=document.documentElement.clientWidth;
				Tamano[1]=document.documentElement.clientHeight;    
			}  else   {    
				Tamano[0]=document.getElementsByTagName('body')[0].clientWidth;
				Tamano[1]=document.getElementsByTagName('body')[0].clientHeight; 
			}  
			var pvw = Tamano[0];
			var pvh = Tamano[1];
			/*			
				top = ((Tamano[1]-(50))/2);
				left = ((Tamano[0]-(350))/2);
			*/
			with(capa.style){
				position = "fixed";
				float = 'none';
				top = 0;
				left = 0;				
				width = pvw+"px";  
				height = pvh+"px";
				zIndex = "50";        
				backgroundImage = "url("+this.direccion+"imgmsg/b1.png)";
			}			
			
			capa.align = "center";
			var styleTitulo = 'text-align:left; font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #000000;';
			var styleMensaje = 'text-align:left; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #000000; width:220px; vertical-align: top;';
			capa.innerHTML = '<table border="0" cellspacing="0" cellpadding="0" width="95%" height="95%"><tr><td style="vertical-align:middle;">'
			+'<table border="0" cellspacing="0" cellpadding="0" align="center">'
			+'<tr>'
			+'<td style="background:url('+this.direccion+'imgmsg/superior-izquierda.png);  height: 15px; width: 15px; font-size: 8px;"></td>'
			+'<td style="background:url('+this.direccion+'imgmsg/superior.png);  height: 15px; width:  (ancho - 30) px; font-size: 8px;"></td>'
			+'<td style="background:url('+this.direccion+'imgmsg/superior-derecha.png);  width: 15px; height: 15px; font-size: 8px;"></td>'
			+'</tr><tr>'
			+'<td style="background:url('+this.direccion+'imgmsg/izquierda.png);  width: 15px; height:  (alto - 30) px; font-size: 8px;">&nbsp;</td>'
			+'<td style="background-color: #ffffff;"><table width="100%" border="0" cellspacing="5" cellpadding="0">'
			+'<tr><td style="width: 55px; height: 55px;"><img src="'+this.direccion+'imgmsg/'+imagen+'.gif" alt="Alerta"></td>'
			+'<td style="'+styleTitulo+'"> '+titulo+' </td></tr>'
			+'<tr><td>&nbsp;</td>'
			+'<td style="'+styleMensaje+'"> '+mensaje+' </td></tr>'
			+'<tr><td colspan="2" align="center" style="text-align:center">'
			+botones
			+'</td></tr></table>'
			+'</td><td style="background:url('+this.direccion+'imgmsg/derecha.png);  width: 15px; height:  (alto - 30) px; font-size: 8px;">&nbsp;</td>'
			+'</tr><tr><td style="background:url('+this.direccion+'imgmsg/inferior-izquierda.png);  width: 15px; height: 15px; font-size: 8px;"></td>'
			+'<td style="background:url('+this.direccion+'imgmsg/inferior.png);  height: 15px; width:  (ancho - 30) px; font-size: 8px;"></td>'
			+'<td style="background:url('+this.direccion+'imgmsg/inferior-derecha.png);  width: 15px; height: 15px; font-size: 8px;"></td>'
			+'</tr></table>'
			+'</td></tr></table>'; 
			document.all[botonselec+this.cm].focus();
			this.cm++;
		}catch(e){
			alert(e.name + " - " + e.message);
		}
	}
	
	function f_popup(direccion, ancho, alto, nombre, titulo, accion){
		var vaccion = "";
		
		if(accion!="" && accion != undefined){
			vaccion = accion+";";
		}
		
		try{
			var capa;
			capa=document.createElement('div');    
			capa.setAttribute('id','msg_bloqueador'+this.cm);        
			document.body.appendChild(capa);
			capa = document.getElementById('msg_bloqueador'+this.cm); 
			
			var Tamano = new Array();
			Tamano[0]="";
			Tamano[1]="";
			if (typeof window.innerWidth != 'undefined')  {   
				Tamano[0]=window.innerWidth;
				Tamano[1]=window.innerHeight;  
			}  else if (typeof document.documentElement != 'undefined' && 
			typeof document.documentElement.clientWidth != 'undefined' && 
			document.documentElement.clientWidth != 0)  { 
				Tamano[0]=document.documentElement.clientWidth;
				Tamano[1]=document.documentElement.clientHeight;    
			}  else   {    
				Tamano[0]=document.getElementsByTagName('body')[0].clientWidth;
				Tamano[1]=document.getElementsByTagName('body')[0].clientHeight; 
			}  
			var pvw = Tamano[0];
			var pvh = Tamano[1];
			
			with(capa.style){
				position = "fixed";
				float = 'none';
				top = 0;
				left = 0;
				width = pvw+"px";  
				height = pvh+"px";      
				zIndex = "50";        
				backgroundImage = "url("+this.direccion+"imgmsg/b.png)";
			}
			
			//capa.align = "center";
			
			//var styleTitulo = 'text-align:left; font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #000000;';
			//var styleMensaje = 'text-align:left; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #000000; width:220px; vertical-align: top;';
			capa.innerHTML = '<div id="dragable'+this.cm+'" style="position:absolute; float:none; left: '+((Tamano[0]-(ancho+35))/2)+'; '+
			'top: '+((Tamano[1]-(alto+50))/2)+'; width: '+(ancho+35)+'px; height: '+(alto+50)+'px; >'+
			'<table border="0" cellspacing="0" cellpadding="0">'+
    	'<tr>'+
        	'<td>'+
            	'<table border="0" cellspacing="0" cellpadding="0">'+
                	'<tr>'+
                    	'<td style="width: 15px; height: 35px; cursor:default; background-image:url('+this.direccion+'imgmsg/ventanafija/superior-izquierda.png);">&nbsp;</td>'+
                        '<td '+((this.movible)?'onmousedown="dragStart(event, \'dragable'+this.cm+'\')"':'')+' style="width: '+(ancho-17)+'px; '+
						'height: 35px; cursor: default; background-image: url('+this.direccion+'imgmsg/ventanafija/superior.png); font-family: '
						+'Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #ffffff;" align="center">'+
						'<input type="text" id="enfoqueventana'+this.cm+'" readonly="true" style="width:2px; background:none; '+
						'border:0;font-family: tahoma; font-size:8px; font-style: normal; font-weight: bold; color:#000000; vertical-align:middle; ">'+titulo+'</td>'+
                        '<td style="width: 15px; height: 35px; background-image: url('+this.direccion+'imgmsg/ventanafija/superior.png); font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #ffffff; vertical-align:middle; ">'+
                        	'<img src="'+this.direccion+'imgmsg/ventanafija/cerrar.gif" alt="Cerrar" title="Cerrar ventana" style="cursor: pointer;" '+
				'onclick="document.body.removeChild(document.getElementById(\'msg_bloqueador'+this.cm+'\'));'+vaccion+' mens.retroceder(); return false;"'+
							'onMouseOver="this.src=\''+this.direccion+'imgmsg/ventanafija/cerrarover.gif\'" onMouseOut="this.src=\''+this.direccion+'imgmsg/ventanafija/cerrar.gif\'" />'+
                        '</td>'+
                        '<td style="width: 20px; height: 35px; cursor: default; background-image:url('+this.direccion+'imgmsg/ventanafija/superior-derecha.png);">&nbsp;</td>'+
                     '</tr>'+
                '</table>'+
            '</td>'+
        '</tr>'+
        '<tr>'+
            '<td>'+
            	'<table  border="0" cellspacing="0" cellpadding="0">'+
                	'<tr>'+
                    	'<td style="width: 13px; height: 225px; cursor: default; background-image: url('+this.direccion+'imgmsg/ventanafija/izquierda.png);">&nbsp;</td>'+
                        '<td style="width: 472px; height: 225px; background-color: #ffffff;">'+
                        	'<iframe style="width: '+ancho+'px; height: '+alto+'px;" src="'+direccion+'" frameborder="0"></iframe>'+
                        '</td>'+
                        '<td style="width: 20px; height: 225px; cursor: default; background-image:url('+this.direccion+'imgmsg/ventanafija/derecha.png);">&nbsp;</td>'+
                    '</tr>'+
                '</table>'+
            '</td>'+
       '</tr>'+
        '<tr>'+
        	'<td>'+
            	'<table  border="0" cellspacing="0" cellpadding="0">'+
                	'<tr>'+
                    	'<td style="width: 15px; height: 40px; cursor: default; background-image:url('+this.direccion+'imgmsg/ventanafija/inferior-izquierda.png);">&nbsp;</td>'+
                        '<td style="width: '+(ancho-2)+'px; height: 40px; cursor: default; background-image:url('+this.direccion+'imgmsg/ventanafija/inferior.png);">&nbsp;</td>'+
                        '<td style="width: 20px; height: 40px; cursor: default;  background-image:url('+this.direccion+'imgmsg/ventanafija/inferior-derecha.png);">&nbsp;</td>'+
                    '</tr>'+
                '</table>'+
            '</td>'+
        '</tr>'+
	'</table>'+
	'</div>'; 
			document.getElementById('enfoqueventana'+this.cm).focus();
			this.cm++;
		}catch(e){
			alert(e.name + " - " + e.message);
		}
	}
}