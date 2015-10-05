// JavaScript Document

function ClaseTabla(nombre, campos, tamano){
	//variables de reconocimiento
	this.attributes;
	this.filasInicial		= 5;
	this.indiceAgregados	= 0;
	this.jasonData			= new Object();
	this.jasonData.datos	= new Array();
	
	this.create				=	f_create;
	this.setAttributes		=	f_setAttributes;
	
	this.add				=	f_add;
	this.setXML   			=	f_setXML;
	this.setSelectedById	=	f_setSelectedById;
	
	this.getSelectedIdRow	=	f_getSelectedIdRow;
	this.getSelectedRow		= 	f_getSelectedRow;
	this.getFieldValue		=	f_getFieldValue;
	this.getRecordCount		=   f_getRecordCount;
	this.getNameFields		=	f_getNameFields;
	
	this.deleteSelected		=	f_deleteSelected;
	this.clear				=	f_clear;
	
	function f_create(){
		var objeto = this.attributes;
		var tbody = document.all[objeto.nombre];
		var tr = tbody.insertRow(tbody.rows.length);
		var todasmedidas = 0;
		if(!objeto.filasInicial)
			objeto.filasInicial = this.filasInicial;
			
		var td = tr.insertCell(tr.cells.length);
		td.style.width 		= 6;
		td.className 		= "formato_columnas_izq";
		td.height			= 16;
		td.innerHTML		= "<input type='hidden' name='"+objeto.nombre+"_estiloseleccionado' />"
								+"<input type='hidden' name='"+objeto.nombre+"_idseleccionado' />";
		todasmedidas 		+= 6;
		
		for(var i=0; i<objeto.campos.length; i++){
			var td = tr.insertCell(tr.cells.length);
			td.style.width 		= objeto.campos[i].medida+"px";
			td.innerHTML 		= objeto.campos[i].nombre;
			td.align	 		= objeto.campos[i].alineacion;
			td.className 		= "formato_columnas";
			todasmedidas		+= objeto.campos[i].medida;
		}	
		
		var td = tr.insertCell(tr.cells.length);
		td.style.width 		= 18;
		td.className 		= "formato_columnas";
		todasmedidas 		+=18;
		
		var td = tr.insertCell(tr.cells.length);
		td.style.width 		= 6;
		td.className 		= "formato_columnas_der";
		todasmedidas 		+= 6;
		
		var tr = tbody.insertRow(tbody.rows.length);
		var td = tr.insertCell(tr.cells.length);
		var td = tr.insertCell(tr.cells.length);
		td.colSpan			= objeto.campos.length+2;
		td.height			= objeto.alto-16;
		
		td.innerHTML		= "<div id='"+objeto.nombre+"_div' style='overflow:auto; width:"+todasmedidas+"px; height:"+(objeto.alto-16)+"px'>"
		+"</div>";
		f_clear(this.attributes);
	}
	
	function f_setAttributes(attributes){
		this.attributes = attributes;		
	}
	
	function f_add(valores){
		alert(valores);
	}
	function f_setXML(datos){
		var objeto = this.attributes;
		this.jasonData.datos = new Array();
		try{
			var cantidad = parseInt(datos.getElementsByTagName("datos").item(0).childNodes.length)/objeto.campos.length;
			
			for(var i=0; i<cantidad; i++){
				arregloDatos= new Array();
				for(var j=0; j<objeto.campos.length; j++){
					arregloDatos[objeto.campos[j].datos.toLocaleString()] = datos.getElementsByTagName(objeto.campos[j].datos.toLocaleString()).item(i).firstChild.data;
					if(!document.getElementById(objeto.nombre + "_" + objeto.campos[j].nombre + "_" + this.indiceAgregados)){
						f_addRow(objeto,this.indiceAgregados);
					}
					valor_datos = datos.getElementsByTagName(objeto.campos[j].datos.toLocaleString()).item(i).firstChild.data;
					document.getElementById(objeto.nombre + "_" + objeto.campos[j].nombre + "_" + this.indiceAgregados).value = f_convertirTipo(valor_datos,objeto.campos[j].tipo);
					
				}
				this.jasonData.datos[this.indiceAgregados] = arregloDatos;
				this.indiceAgregados++;
			}
		}catch(e){
			alert("hubo un error al cargar el xml");
		}
	}
	function f_setSelectedById(){
		alert('');
	}
	
	function f_getSelectedIdRow(){
		var objeto = this.attributes;
		return document.all[objeto.nombre+"_idseleccionado"].value;
	}
	function f_getSelectedRow(){
		alert('');
	}
	function f_getFieldValue(){
		alert("f_dameValorCampo");
	}
	function f_getRecordCount(){
		return this.jasonData.datos.length;
	}
	function f_getNameFields(){
		return this.campos;
	}
	
	function f_deleteSelected(){
		alert("se limpio");
	}
	function f_clear(valor){
		var objeto = (valor == undefined)?this.attributes:valor;
		var div = document.all[objeto.nombre+"_div"];
		div.innerHTML = "<table id='"+objeto.nombre+"_tabla' border='0' cellpadding='0' cellspacing='0'></table>";
		
		var tbody = document.all[objeto.nombre+"_tabla"];
		
		for(var j = 0; j < objeto.filasInicial; j++){
			var tr = tbody.insertRow(tbody.rows.length);
			tr.id = objeto.nombre + "_id" + j;
			f_asignarEventosTR(tr,j,objeto);
			
			for(var i=0; i<objeto.campos.length; i++){
				var td = tr.insertCell(tr.cells.length);
				td.style.width 		= objeto.campos[i].medida+"px";

				td.innerHTML 		= "<input type='text' value='' readonly" 
					+ " style='background:none; border:none; width:" + objeto.campos[i].medida 
					+ "px; text-align:" + objeto.campos[i].alineacion + "' id='" 
					+ objeto.nombre + "_" + objeto.campos[i].nombre + "_" + j + "' >";
			}
		}
	}
	
	function f_addRow(objeto,j){
		var tbody = document.all[objeto.nombre+"_tabla"];
		var tr = tbody.insertRow(tbody.rows.length);
		tr.id = objeto.nombre + "_id" + j;
		f_asignarEventosTR(tr,j,objeto);
		
		for(var i=0; i<objeto.campos.length; i++){
			var td = tr.insertCell(tr.cells.length);
			td.style.width 		= objeto.campos[i].medida+"px";

			td.innerHTML 		= "<input type='text' value='' readonly" 
				+ " style='background:none; border:none; width:" + objeto.campos[i].medida 
				+ "px; text-align:" + objeto.campos[i].alineacion + "' id='" 
				+ objeto.nombre + "_" + objeto.campos[i].nombre + "_" + j + "' >";
		}
	}
	function f_formatNumber(num,prefix){
		prefix = prefix || '';
		num += '';
		var splitStr = num.split('.');
		var splitLeft = splitStr[0];
		var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '';
		var regx = /(\d+)(\d{3})/;
		while (regx.test(splitLeft)) {
			splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
		}
		return prefix + splitLeft + splitRight;
	}
	function f_convertirTipo(valor,tipo){
		if(tipo=='moneda'){
			return f_formatNumber(valor,'$ ');
		}else{
			return valor;
		}
	}
	function f_unformatNumber(num) {
		return num.replace(/([^0-9\.\-])/g,'')*1;
	} 
	function f_asignarEventosTR(tr,j,objeto){
		tr.className 	= (j%2==0)?"fila1":"fila2";
		tr.onmouseover 	= Function("if(this.className!='seleccionarFila'){this.className='sobreFila';}");
		tr.onmouseout 	= Function("if(this.className!='seleccionarFila'){this.className='"+((j%2==0)?"fila1":"fila2")+"';}");
		tr.onclick		= Function("if(document.all."+objeto.nombre+"_idseleccionado.value!=''){document.all[document.all."+objeto.nombre+"_idseleccionado.value].className = document.all."+objeto.nombre+"_estiloseleccionado.value} "
								+"document.all."+objeto.nombre+"_idseleccionado.value=this.id; "
								+"document.all."+objeto.nombre+"_estiloseleccionado.value='"+((j%2==0)?"fila1":"fila2")+"'; this.className='seleccionarFila'");
	}
}