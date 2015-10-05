// JavaScript Document
function ClaseBarraProgreso(){
	this.setAttributes 	= f_setAttributes;
	this.create 		= f_create;
	this.setPorcent	 	= f_setPorcent;
	this.setValue	 	= f_setValue;
	this.setStart	 	= f_setStart;
	this.setEnd	 		= f_setEnd;
	this.setTimeFill	= f_setTimeFill;
	this.startFill		= f_startFill;
	
	this.getPorcent		= f_getPorcent;
	this.getValue	 	= f_getValue;
	
	function f_setAttributes(attributes){
		attributes.ancho = 15;
		attributes.largo = (attributes.largo == undefined)?100:attributes.largo;
		attributes.inicio = (attributes.inicio == undefined)?0:attributes.inicio;
		attributes.fin = (attributes.fin == undefined)?attributes.largo:attributes.fin;
		attributes.porcentaje=0;
		attributes.valor=0;
		this.attributes = attributes;
	}
	
	function f_create(){
		var objeto = this.attributes;	
		var u = document.all;
		
		var barraprogreso = u[objeto.nombre];
		with (barraprogreso){
			style.width=objeto.largo+50;
			style.height=objeto.ancho+2;
			//style.borderStyle="solid";
			//style.borderWidth="thin";
			//style.borderColor="#999";
			//style.backgroundColor = "#FFFFFF";
		}
		
		var contenedor = document.createElement('div');
		contenedor.setAttribute('id',objeto.nombre+'_contenedor'); 
		barraprogreso.appendChild(contenedor);
		contenedor = document.getElementById(objeto.nombre+'_contenedor');
		
		with (contenedor){
			style.width=objeto.largo;
			style.height=objeto.ancho;
			style.borderStyle="solid";
			style.borderWidth="thin";
			style.borderColor="#999";
			style.backgroundColor = "#FFFFFF";
		}
		
		//crear relleno
		var relleno = document.createElement('div');
		relleno.setAttribute('id',objeto.nombre+'_relleno'); 
		contenedor.appendChild(relleno);
		relleno = document.getElementById(objeto.nombre+'_relleno');
		
		with (relleno){
			style.width=0;
			style.height=objeto.ancho;
			style.backgroundImage = "url(http://www.pmmentuempresa.com/web/javascript/fondoprogreso.gif)";
		}
		
		//crear etiqueta
		/*var etiqueta = document.createElement('div');
		etiqueta.setAttribute('id',objeto.nombre+'_etiqueta'); 
		barraprogreso.appendChild(etiqueta);
		etiqueta = document.getElementById(objeto.nombre+'_etiqueta');
		etiqueta.innerHTML="25%";*/
		
		
	}
	function f_setPorcent(valor){
		var u = document.all;
		var objeto = this.attributes;
		var relleno = document.getElementById(objeto.nombre+'_relleno');
		if(valor>100){
			relleno.style.backgroundImage = "url(http://www.pmmentuempresa.com/web/javascript/fondoprogreso2.gif)";
			relleno.style.width = (objeto.largo/100)*101;
		}else{
			relleno.style.width = (objeto.largo/100)*parseFloat(valor);
			relleno.style.backgroundImage = "url(http://www.pmmentuempresa.com/web/javascript/fondoprogreso.gif)";
		}
		this.attributes.porcentaje = parseFloat(valor);
		this.attributes.valor = parseFloat(valor)/100*(objeto.fin-objeto.inicio);
		document.all[objeto.nombre+'_contenedor'].title = "Capacidad: "+objeto.fin +"\nCargado: "+this.attributes.valor;
	}
	function f_setValue(valor){
		var u = document.all;
		var objeto = this.attributes;
		
		var porcentaje = (parseFloat(valor)/(objeto.fin-objeto.inicio))*100;
		this.setPorcent(porcentaje);
	}
	function f_setStart(valor){
		this.attributes.inicio = valor;
		this.setValue(this.attributes.valor);
	}
	function f_setEnd(valor){
		this.attributes.fin = valor;
		this.setValue(this.attributes.valor);
	}
	function f_setTimeFill(milisegundos){
		var objeto = this.attributes;
		//100000
		this.llenado = Math.round((100-objeto.porcentaje)/parseFloat(milisegundos/1000)*100)/100;
	}
	function f_startFill(){
		if(this.getPorcent()<100){
			if((100-this.getPorcent())>this.llenado)
				this.setPorcent(this.getPorcent()+this.llenado);
			else
				this.setPorcent(this.getPorcent()+(100-this.getPorcent()));
			setTimeout("barra.startFill()",1000);
		}
	}
	
	function f_getPorcent(){
		return Math.round(this.attributes.porcentaje*100)/100;
	}
	function f_getValue(){
		return Math.round(this.attributes.valor*100)/100;
	}
}