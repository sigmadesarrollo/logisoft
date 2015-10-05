// JavaScript Document
function ClaseTabla(nombre, campos, tamano){
	//variables de reconocimiento	
	this.create				=	f_create;
	this.setAttributes		=	f_setAttributes;
	
	this.add				=	f_add;
	this.setXML   			=	f_setXML;
	this.setJsonData		=	f_setJsonData;
	this.setSelectedById	=	f_setSelectedById;
	this.setSelectedByIndex	=	f_setSelectedByIndex;
	this.setColorById		=	f_setColorById;
	this.setColorByIndex	=	f_setColorByIndex;
	this.setFilter			= 	f_setFilter;
	
	this.getSelectedIndex	=	f_getSelectedIndex;
	this.getSelectedIdRow	=	f_getSelectedIdRow;
	this.getSelectedRow		= 	f_getSelectedRow;
	this.getRecordCount		=   f_getRecordCount;
	this.getRowByIndex		=   f_getRowByIndex;
	this.getRowById			=   f_getRowById;
	this.getValuesFromField = 	f_getValuesFromField;
	this.getValSelFromField = 	f_getValSelFromField;
	this.getValUnselFromField = f_getValUnselFromField;
	this.getSelCountField 	= 	f_getSelCountField;
	this.getUnselCountField = 	f_getUnselCountField;
	
	this.deleteById			=	f_deleteById;
	this.deleteByIndex		=	f_deleteByIndex;
	this.updateRowById		=	f_updateRowById;
	this.updateRowByIndex	=	f_updateRowByIndex;
	this.clear				=	f_clear;
	this.sortRows			=	f_sortRows;
	this.creada				=	f_creada;
	
	this.v_creada 			= false;
	
	function f_create(){
		var objeto = this.attributes;
		
		this.v_creada = true;
		var tbody = document.all[objeto.nombre];
		//alert("-"+objeto.nombre+"-"+document.getElementById(objeto.nombre));
		var tr = tbody.insertRow(tbody.rows.length);
		var todasmedidas = 0;
		//valores default
		if(!objeto.filasInicial)
			objeto.filasInicial = this.filasInicial;

		var td = tr.insertCell(tr.cells.length);
		td.style.width 		= "6px";
		td.className 		= "formato_columnas_izq";
		td.height			= 16;
		td.innerHTML		= "<input type='hidden' name='"+objeto.nombre+"_estiloseleccionado' />"
								+"<input type='hidden' name='"+objeto.nombre+"_idseleccionado' />";
		todasmedidas 		+= 6;
		
		for(var i=0; i<objeto.campos.length; i++){
			var td = tr.insertCell(tr.cells.length);
			td.style.width 		= objeto.campos[i].medida+"px";
			if(objeto.campos[i].tipo != "oculto")                              
				td.innerHTML 		= "&nbsp;"+objeto.campos[i].nombre+"&nbsp;";
			td.style.textAlign	= objeto.campos[i].alineacion;
			td.className 		= "formato_columnas";
			td.style.cursor		= "default";
			if(objeto.ordenable){
				td.onclick = Function(objeto.nombrevar+".sortRows('"+objeto.campos[i].datos+"')");
			}
			todasmedidas		+= objeto.campos[i].medida;
		}	
		
		var td = tr.insertCell(tr.cells.length);
		td.style.width 		= "13px";
		td.className 		= "formato_columnas";
		todasmedidas 		+=13;
		
		var td = tr.insertCell(tr.cells.length);
		td.style.width 		= "6px";
		td.className 		= "formato_columnas_der";
		todasmedidas 		+= 6;
		
		var tr = tbody.insertRow(tbody.rows.length);
		var td = tr.insertCell(tr.cells.length);
		var td = tr.insertCell(tr.cells.length);
		td.colSpan			= objeto.campos.length+3;
		td.height			= objeto.alto-16;
		
		tbody.width = todasmedidas+"px";
		
		td.innerHTML		= "<div id='"+objeto.nombre+"_div' style='overflow:auto; width:100%; height:"+(objeto.alto-16)+"px'>"
		+"</div>";
		f_clear(this.attributes);
	}
	function f_setAttributes(attributes){
		this.attributes;
		this.filasInicial		= 5;
		this.indiceAgregados	= 0;
		this.jsonData			= new Object();
		this.jsonData.datos		= new Array();
		this.attributes = attributes;		
	}
	
	function f_add(datos){
		var objeto = this.attributes;
		var arregloDatos= new Object();
		for(var j=0; j<objeto.campos.length; j++){
			arregloDatos[objeto.campos[j].datos.toLocaleString()] = datos[objeto.campos[j].datos.toLocaleString()];
			if(document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados]==undefined){
				f_addRow(objeto,this.indiceAgregados);
			}
			if(this.attributes.campos[j].tipo=="checkbox"){
				document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.visibility = 'visible';
				document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].checked = false;
			}else{
				valor_datos = datos[objeto.campos[j].datos.toLocaleString()];
				if(objeto.campos[j].onClick!= null || objeto.campos[j].onDblClick!= null ){
				document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.cursor = "pointer";
				document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.textDecoration = "underline";
					}
				document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].value = f_convertirTipo(valor_datos,objeto.campos[j].tipo);
			}
		}
		this.jsonData.datos[this.indiceAgregados] = arregloDatos;
		this.indiceAgregados++;
	}
	function f_setXML(datos){
		var objeto = this.attributes;
		this.jsonData.datos = new Array();
		this.indiceAgregados = 0;
		f_clear(objeto);
		f_limpiarSeleccion(objeto);
		
		try{
			var cantidad = datos.getElementsByTagName(objeto.campos[0].datos.toLocaleString()).length;
			for(var i=0; i<cantidad; i++){
				var arregloDatos= new Object();
				for(var j=0; j<objeto.campos.length; j++){
					arregloDatos[objeto.campos[j].datos.toLocaleString()] = datos.getElementsByTagName(objeto.campos[j].datos.toLocaleString()).item(i).firstChild.data;
					if(document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados]==undefined){
						f_addRow(objeto,this.indiceAgregados);
					}
					if(this.attributes.campos[j].tipo=="checkbox"){
						document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.visibility = 'visible';
						document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].checked = false;
						if(datos.getElementsByTagName(objeto.campos[j].datos.toLocaleString()).item(i).firstChild.data==0){
							document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.visibility='hidden';
						}
					}else{
					valor_datos = datos.getElementsByTagName(objeto.campos[j].datos.toLocaleString()).item(i).firstChild.data;
					//alert(f_convertirTipo(valor_datos,objeto.campos[j].tipo)+"---"+valor_datos);
					if(objeto.campos[j].onClick!= null || objeto.campos[j].onDblClick!= null ){
				document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.cursor = "pointer";
				document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.textDecoration = "underline";
					}
					document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].value = f_convertirTipo(valor_datos,objeto.campos[j].tipo);
					
					}
					
				}
				this.jsonData.datos[this.indiceAgregados] = arregloDatos;
				this.indiceAgregados++;
			}
		}catch(e){
			alert("hubo un error al cargar el xml");
		}
	}
	function f_setJsonData(datos, vobjeto){
		var objeto = (vobjeto==undefined)?this.attributes:vobjeto;
		var campos = objeto.campos.length;
		this.jsonData.datos = new Array();
		this.indiceAgregados = 0;
		f_clear(objeto);
		f_limpiarSeleccion(objeto);
		
		try{
			var cantidad = datos.length;
			
			for(var i=0; i<cantidad; i++){
				for(var j=0; j<objeto.campos.length; j++){
					if(document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados]==undefined){
						f_addRow(objeto,this.indiceAgregados);
					}
					if(this.attributes.campos[j].tipo=="checkbox"){
						document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.visibility = 'visible';
						document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].checked = false;
						if(datos[i][objeto.campos[j].datos.toLocaleString()]==0){
							document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.visibility='hidden';
						}
					}else{
					valor_datos = datos[i][objeto.campos[j].datos.toLocaleString()];
					if(objeto.campos[j].onClick!= null || objeto.campos[j].onDblClick!= null ){
				document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.cursor = "pointer";
				document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].style.textDecoration = "underline";
					}
					document.all[objeto.nombre + "_" + objeto.campos[j].nombre][this.indiceAgregados].value = f_convertirTipo(valor_datos,objeto.campos[j].tipo);
					}
					
				}
				this.jsonData.datos[this.indiceAgregados] = datos[i];
				this.indiceAgregados++;
			}
		}catch(e){
			alert("hubo un error al cargar el xml");
		}
	}
	function f_setSelectedById(id){
		var objeto = this.attributes;
		if(id==""){
			if(document.all[objeto.nombre+"_idseleccionado"].value!=""){
				document.all[document.all[objeto.nombre+"_idseleccionado"].value].className = document.all[objeto.nombre+"_estiloseleccionado"].value;
			}
			document.all[objeto.nombre+"_estiloseleccionado"].value = "";
			document.all[objeto.nombre+"_idseleccionado"].value = "";
		}else{
			if(document.getElementById(id)){
				if(document.all[objeto.nombre+"_idseleccionado"].value!=""){
					document.all[document.all[objeto.nombre+"_idseleccionado"].value].className = document.all[objeto.nombre+"_estiloseleccionado"].value;
				}
				document.all[objeto.nombre+"_estiloseleccionado"].value = document.all[id].className;
				document.all[id].className = "seleccionarFila";
				document.all[objeto.nombre+"_idseleccionado"].value = id;
			}
		}
	}
	function f_setSelectedByIndex(id){
		var objeto = this.attributes;
		if(id==""){
			if(document.all[objeto.nombre+"_idseleccionado"].value!=""){
				document.all[document.all[objeto.nombre+"_idseleccionado"].value].className = document.all[objeto.nombre+"_estiloseleccionado"].value;
			}
			document.all[objeto.nombre+"_estiloseleccionado"].value = "";
			document.all[objeto.nombre+"_idseleccionado"].value = "";
		}else{
			if(document.getElementById(objeto.nombre+"_id"+id)){
				if(document.all[objeto.nombre+"_idseleccionado"].value!=""){
					document.all[document.all[objeto.nombre+"_idseleccionado"].value].className = document.all[objeto.nombre+"_estiloseleccionado"].value;
				}
				document.all[objeto.nombre+"_estiloseleccionado"].value = document.all[objeto.nombre+"_id"+id].className;
				document.all[objeto.nombre+"_id"+id].className = "seleccionarFila";
				document.all[objeto.nombre+"_idseleccionado"].value = objeto.nombre+"_id"+id;
			}
		}
	}
	
	function f_setColorById(color,ids){
		var objeto = this.attributes;
		var tbody = document.all[objeto.nombre+"_tabla"];
		var indice = ids.replace(objeto.nombre+"_id","");	
		
		for(var i=0; i<objeto.campos.length; i++){
			document.all[objeto.nombre + "_" + objeto.campos[i].nombre][indice].style.color = color;
		}
	}
	function f_setColorByIndex(color,indice){
		var objeto = this.attributes;
		var tbody = document.all[objeto.nombre+"_tabla"];
			
		for(var i=0; i<objeto.campos.length; i++){
			document.all[objeto.nombre + "_" + objeto.campos[i].nombre][indice].style.color = color;
		}
	}
	function f_setFilter(campo,valor,validacion){
		var objeto		= this.attributes;
		var tbody		= document.all[objeto.nombre + "_tabla"];
		var cadena		= "";
		var datos		= this.jsonData.datos;
		if(isNaN(parseFloat(valor))){
			for (var i=0;i<tbody.rows.length;i++){
				if(valor!="none"){
					cadena = ((datos[i]==undefined)?"''":datos[i][campo].toLocaleString().toUpperCase());
					if(validacion==null){
						if(cadena.indexOf(valor.toUpperCase())<0){
							tbody.rows[i].style.display="none";	
						}else{
							tbody.rows[i].style.display="";
						}
					}else{
						if(eval("'"+cadena.toUpperCase()+"'"+validacion+"'"+valor.toUpperCase()+"'")){
							tbody.rows[i].style.display="";	
						}else{
							tbody.rows[i].style.display="none";
						}
					}
				}else{
					tbody.rows[i].style.display="";
				}
			}
		}else{
			numero = parseFloat(valor);
			for (var i=0;i<tbody.rows.length;i++){				
				numerocampo = ((datos[i]==undefined)?"''":datos[i][campo]);
				if(eval(numero+validacion+numerocampo)){
					tbody.rows[i].style.display="none";
				}else{
					tbody.rows[i].style.display="";
				}
			}
		}
	}
	function f_updateByIndex(indice,valor){
		objeto = this.attributes;
		this.jsonData.datos[indice] = valor;
		for(var i=0; i<objeto.campos.length; i++){
			document.all[objeto.nombre+"_"+objeto.campos[i].nombre][indice].value = valor[objeto.campos[i].datos];
		}
	}
	function f_updateById(id,valor){
		//this.jsonData.datos[indice] = valor;
	}
	
	function f_getSelectedIdRow(){
		var objeto = this.attributes;
		return document.all[objeto.nombre+"_idseleccionado"].value;
	}
	function f_getSelectedIndex(){
		var objeto = this.attributes;
		return document.all[objeto.nombre+"_idseleccionado"].value.replace(objeto.nombre+"_id","");
	}
	function f_getSelectedRow(){
		var objeto  = this.attributes;
		var idsel	= document.all[objeto.nombre+"_idseleccionado"].value;
		if(idsel=="")
			return null;
		else
			return this.jsonData.datos[idsel.replace(objeto.nombre+"_id","")];
	}
	function f_getRecordCount(){
		return this.indiceAgregados;
	}
	function f_getRowByIndex(index){
		return this.jsonData.datos[index];
	}
	function f_getRowById(id){
		var objeto = this.attributes;
		var indice = id.replace(objeto.nombre+"_id");
		return this.jsonData.datos[indice];
	}
	function f_getValuesFromField(campo,separador){
		separador = (separador==undefined)?",":separador;
		var objeto 		= this.attributes;
		var resultado	= "";
		
		for(var i=0; i<this.jsonData.datos.length;i++){
			if(resultado!="")
				resultado+=separador;
			resultado += this.jsonData.datos[i][campo];
		}
		return resultado;
	}
	function f_getValSelFromField(campoValor,campoObjetivo,separador){
		separador 		= (separador==undefined)?",":separador;
		campoObjetivo 	= (campoObjetivo==undefined)?",":campoObjetivo;
		var objeto 		= this.attributes;
		var resultado	= "";
		
		if(objeto.seleccion){
			resultado =	document.all[objeto.nombre+"_"+campoObjetivo][document.all[objeto.nombre+"_idseleccionado"].value.replace(objeto.nombre+"_id","")].value;
		}else{
			for(var i=0; i<this.jsonData.datos.length;i++){
				if(document.all[objeto.nombre+"_"+campoObjetivo][i].checked==true){
					if(resultado!="")
						resultado+=separador;
					resultado += this.jsonData.datos[i][campoValor];
				}
			}
		}
		return resultado;
	}
	function f_getValUnselFromField(campoValor,campoObjetivo,separador){
		separador = (separador==undefined)?",":separador;
		var objeto 		= this.attributes;
		var resultado	= "";
		
		for(var i=0; i<this.jsonData.datos.length;i++){
			if(document.all[objeto.nombre+"_"+campoObjetivo][i].checked==false && document.all[objeto.nombre+"_"+campoObjetivo][i].style.visibility=='visible'){
				if(resultado!="")
					resultado+=separador;
				resultado += this.jsonData.datos[i].noguia;
			}
		}
		return resultado;	
	}
	function f_getSelCountField(campoObjetivo){
		var objeto 		= this.attributes;
		var resultado	= 0;
		
		for(var i=0; i<this.jsonData.datos.length;i++){
			if(document.all[objeto.nombre+"_"+campoObjetivo][i].checked==true){
				resultado++;
			}
		}
		return resultado;
	}
	function f_getUnselCountField(campoObjetivo){
		var objeto 		= this.attributes;
		var resultado	= 0;
		
		for(var i=0; i<this.jsonData.datos.length;i++){
			if(document.all[objeto.nombre+"_"+campoObjetivo][i].checked==false && document.all[objeto.nombre+"_"+campoObjetivo][i].style.visibility=='visible'){
				resultado++;
			}
		}
		return resultado;
	}	
	
	function f_deleteByIndex(index){
		var objeto 		= this.attributes;
		var tbody 		= document.all[objeto.nombre+"_tabla"];
		var ndatos		= new Array();
		var indice		= 0;
		var nfilas		= tbody.rows.length;
		var fborrar		= 0;
		
		if(document.all[objeto.nombre+"_idseleccionado"].value!=""){
			for (var i=0;i<nfilas;i++){
				if (i!=index) { 
					ndatos[indice]	=	this.jsonData.datos[i];
					indice++;
				}
			}
			tbody.deleteRow(index);
			
			this.jsonData.datos	=	ndatos;
			
			f_reacomodarIds(index, objeto);
			f_reacomodarColores(objeto);
			f_limpiarSeleccion(objeto);
			this.indiceAgregados--;
			if(tbody.rows.length<objeto.filasInicial){
				//f_addRow(objeto,this.indiceAgregados);
			}
		}
	}
	function f_deleteById(idn){
		var objeto 		= this.attributes;
		var tbody 		= document.all[objeto.nombre+"_tabla"];
		var ndatos		= new Array();
		var indice		= 0;
		var nfilas		= tbody.rows.length;
		var fborrar		= 0;
		
		if(document.all[objeto.nombre+"_idseleccionado"].value!=""){
			for (var i=0;i<nfilas;i++){
				if (tbody.rows[i].id==idn) { 
					fborrar = i;
				}else{
					if(this.jsonData.datos[i]!=undefined){
						ndatos[indice]	=	this.jsonData.datos[i];
						indice++;
					}
				}
			}
			tbody.deleteRow(fborrar);
			
			this.jsonData.datos	=	ndatos;
			f_reacomodarIds(idn, objeto);
			f_reacomodarColores(objeto);
			f_limpiarSeleccion(objeto)
			this.indiceAgregados--;
			if(tbody.rows.length<objeto.filasInicial){
				
				var j = objeto.filasInicial-1;
				var tr = tbody.insertRow(tbody.rows.length);
				tr.id = objeto.nombre + "_id" + j;
				f_asignarEventosTR(tr,j,objeto);
				
				for(var i=0; i<objeto.campos.length; i++){
					
					var onclick = "";
					if(objeto.campos[i].onClick != undefined){
						onclick = "onclick='"+objeto.campos[i].onClick+((objeto.campos[i].tipo=="checkbox")?"(this.checked)'":"(this.value)'");
					}
					var ondblclick = "";
					if(objeto.campos[i].onDblClick != undefined){
						ondblclick = "ondblclick='"+objeto.campos[i].onDblClick+((objeto.campos[i].tipo=="checkbox")?"(this.checked)'":"(this.value)'");
					}
					
					var td = tr.insertCell(tr.cells.length);
					td.style.width 		= objeto.campos[i].medida+"px";
					if(objeto.campos[i].tipo=="checkbox"){
						td.innerHTML 		= "<input style='visibility:hidden' type='checkbox' class='formato_chk' id='" 
							+ objeto.nombre + "_" + objeto.campos[i].nombre 
							+ "' name='"+objeto.nombre + "_" + objeto.campos[i].nombre+"[]' "+onclick+" "+ondblclick+" >";
						td.align		= "center";
					}else if(objeto.campos[i].tipo=="oculto"){
						td.innerHTML 		= "<input type='hidden' id='" 
							+ objeto.nombre + "_" + objeto.campos[i].nombre 
							+ "' name='"+objeto.nombre + "_" + objeto.campos[i].nombre+"[]' >";
					}else{
						td.innerHTML 		= "<input type='text' value='' readonly class='formato_cajagrid'" 
							+ " style='background:none; cursor:default; border:none; width:" + (parseInt(objeto.campos[i].medida)-2) 
							+ "px; text-align:" + objeto.campos[i].alineacion + "' id='" 
							+ objeto.nombre + "_" + objeto.campos[i].nombre + "'" 
							+" name='"+objeto.nombre + "_" + objeto.campos[i].nombre+"[]' "+onclick+" "+ondblclick+" >";
					}
				}
			}
		}
	}
	function f_updateRowById(id, fila){
		var objeto 		= this.attributes;
		
		var indice = id.replace(objeto.nombre+"_id","");
		
		if(indice<=this.jsonData.datos.length){
			for(var i=0; i<objeto.campos.length; i++){
				if(objeto.campos[i].tipo=="checkbox"){
					document.all[objeto.nombre + "_" + objeto.campos[i].nombre][indice].style.visibility = (fila[objeto.campos[i].datos]==1 || fila[objeto.campos[i].datos]==2)?"visible":"hidden";
					document.all[objeto.nombre + "_" + objeto.campos[i].nombre][indice].checked = (fila[objeto.campos[i].datos]==2)?true:false;
				}else{
					document.all[objeto.nombre + "_" + objeto.campos[i].nombre][indice].value = f_convertirTipo(fila[objeto.campos[i].datos],objeto.campos[i].tipo);
				}
				this.jsonData.datos[indice][objeto.campos[i].datos] = fila[objeto.campos[i].datos];
			}
		}
	}
	function f_updateRowByIndex(indice, fila){
		var objeto 		= this.attributes;
		
		if(indice<=this.jsonData.datos.length){
			for(var i=0; i<objeto.campos.length; i++){
				if(objeto.campos[i].tipo=="checkbox"){
					document.all[objeto.nombre + "_" + objeto.campos[i].nombre][indice].style.visibility = (fila[objeto.campos[i].datos]==1 || fila[objeto.campos[i].datos]==2)?"visible":"hidden";
					document.all[objeto.nombre + "_" + objeto.campos[i].nombre][indice].checked = (fila[objeto.campos[i].datos]==2)?true:false;
				}else{
					document.all[objeto.nombre + "_" + objeto.campos[i].nombre][indice].value = f_convertirTipo(fila[objeto.campos[i].datos],objeto.campos[i].tipo);
				}
				this.jsonData.datos[indice][objeto.campos[i].datos] = fila[objeto.campos[i].datos];
			}
		}
	}
	function f_clear(valor){
		var objeto = (valor == undefined)?this.attributes:valor;
		var div = document.all[objeto.nombre+"_div"];
		div.innerHTML = "<table id='"+objeto.nombre+"_tabla' border='0' cellpadding='0' cellspacing='0'></table>";
		try{
		if(valor == undefined){
			this.jsonData.datos = new Array();
			this.indiceAgregados = 0;
		}
		
		var tbody = document.all[objeto.nombre+"_tabla"];
		
		for(var j = 0; j < objeto.filasInicial; j++){
			var tr = tbody.insertRow(tbody.rows.length);
			tr.id = objeto.nombre + "_id" + j;
			f_asignarEventosTR(tr,j,objeto);
			
			for(var i=0; i<objeto.campos.length; i++){
				var td = tr.insertCell(tr.cells.length);
				td.style.width 		= objeto.campos[i].medida+"px";
				
				var onclick = "";
				if(objeto.campos[i].onClick != undefined){
					onclick = "onclick='"+objeto.campos[i].onClick+((objeto.campos[i].tipo=="checkbox")?"(this.checked)'":"(this.value)'");
				}
				var ondblclick = "";
				if(objeto.campos[i].onDblClick != undefined){
					ondblclick = "ondblclick='"+objeto.campos[i].onDblClick+((objeto.campos[i].tipo=="checkbox")?"(this.checked)'":"(this.value)'");
				}
				
				if(objeto.campos[i].tipo=="checkbox"){
					td.innerHTML 		= "<input style='visibility:hidden' type='checkbox' class='formato_chk' id='" 
						+ objeto.nombre + "_" + objeto.campos[i].nombre 
						+ "' name='"+objeto.nombre + "_" + objeto.campos[i].nombre+"[]' "+onclick+" "+ondblclick+" >";
					td.align		= "center";
				}else if(objeto.campos[i].tipo=="oculto"){
					td.innerHTML 		= "<input type='hidden' id='" 
						+ objeto.nombre + "_" + objeto.campos[i].nombre 
						+ "' name='"+objeto.nombre + "_" + objeto.campos[i].nombre+"[]' >";
				}else{
					td.innerHTML 		= "<input type='text' value='' readonly class='formato_cajagrid'" 
					+ " style='background:none; cursor:default; border:none; width:" + (parseInt(objeto.campos[i].medida)-8) 
					+ "px; text-align:" + objeto.campos[i].alineacion + "' id='" 
					+ objeto.nombre + "_" + objeto.campos[i].nombre + "'"
					+" name='"+objeto.nombre + "_" + objeto.campos[i].nombre+"[]' "+onclick+" "+ondblclick+" >";
				}
			}
		}
		}catch(e){
			alert("error");
		}
	}
	function f_sortRows(campo){
		var objeto = new Object();
		var campos = new Array();
		var ndatos = new Array();
		var nobjeto = new Object();
		//String.fromCharCode()  
		for(var i=0; i<this.jsonData.datos.length; i++){
			objeto[this.jsonData.datos[i][campo]+String.fromCharCode(i+65)] = i;
			campos[i] = this.jsonData.datos[i][campo]+String.fromCharCode(i+65);
		}
		campos.sort();
		for(var i=0; i<this.jsonData.datos.length; i++){
			ndatos[i] = this.jsonData.datos[objeto[campos[i]]];
		}
		f_clear(this.attributes);
		this.jsonData.datos = ndatos;
		var objetoAtr 	= this.attributes;
		var campos 		= objetoAtr.campos.length;
		this.indiceAgregados	=	0;
		
		for(var j=0; j<this.jsonData.datos.length; j++){
			for(var i=0; i<campos; i++){
				if(document.all[objetoAtr.nombre + "_" + objetoAtr.campos[i].nombre][this.indiceAgregados]==undefined){
					f_addRow(objetoAtr,this.indiceAgregados);
				}
				valor_datos = this.jsonData.datos[j][this.attributes.campos[i].datos];
				document.all[objetoAtr.nombre + "_" + objetoAtr.campos[i].nombre][this.indiceAgregados].value = f_convertirTipo(valor_datos,objetoAtr.campos[i].tipo);
			}
			this.indiceAgregados++;
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
			
			var onclick = "";
			if(objeto.campos[i].onClick != undefined){
				onclick = "onclick='"+objeto.campos[i].onClick+((objeto.campos[i].tipo=="checkbox")?"(this.checked)'":"(this.value)'");
			}
			var ondblclick = "";
			if(objeto.campos[i].onDblClick != undefined){
				ondblclick = "ondblclick='"+objeto.campos[i].onDblClick+((objeto.campos[i].tipo=="checkbox")?"(this.checked)'":"(this.value)'");
			}
			
			if(objeto.campos[i].tipo=="checkbox"){
					td.innerHTML 		= "<input style='visibility:hidden' type='checkbox' class='formato_chk' id='" 
						+ objeto.nombre + "_" + objeto.campos[i].nombre 
						+ "' name='"+objeto.nombre + "_" + objeto.campos[i].nombre+"[]' "+onclick+" "+ondblclick+" >";
					td.align		= "center";
				}else if(objeto.campos[i].tipo=="oculto"){
					td.innerHTML 		= "<input type='hidden' id='" 
						+ objeto.nombre + "_" + objeto.campos[i].nombre 
						+ "' name='"+objeto.nombre + "_" + objeto.campos[i].nombre+"[]' >";
				}else{
				td.innerHTML 		= "<input type='text' value='' readonly class='formato_cajagrid'" 
					+ " style='background:none; cursor:default; border:none; width:" + (parseInt(objeto.campos[i].medida)-8) 
					+ "px; text-align:" + objeto.campos[i].alineacion + "' id='" 
					+ objeto.nombre + "_" + objeto.campos[i].nombre + "'"
					+" name='"+objeto.nombre + "_" + objeto.campos[i].nombre+"[]' "+onclick+" "+ondblclick+" >";
			}
		}
	}
	function f_formatNumber(num,prefix){
		prefix = prefix || '';
		num += '';
		if(num.indexOf('.')<0){
			num += ".00";
		}
		var splitStr = num.split('.');
		var splitLeft = splitStr[0];
		var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '';
		var regx = /(\d+)(\d{3})/;
		while (regx.test(splitLeft)) {
			splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
		}
		return prefix + splitLeft + parseFloat(splitRight).toFixed(2).toString().substr(1,3);
	}
	
	function f_convertirTipo(valor,tipo){
		if(tipo=='moneda'){
			if(valor == "" || valor == 0)
				return "$ 0.00";
			else
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
		var funciones = "";
		var funciones2 = "";
		if(objeto.eventoClickFila){
			funciones = objeto.eventoClickFila;
		}
		if(objeto.eventoDblClickFila){
			funciones2 = objeto.eventoDblClickFila;
		}
		
		if(objeto.seleccion){
			tr.onmouseover 	= Function("if(this.className!='seleccionarFila'){this.className='sobreFila';}");
			tr.onmouseout 	= Function("if(this.className!='seleccionarFila'){this.className='"+((j%2==0)?"fila1":"fila2")+"';}");
			tr.onclick		= Function("if(document.all."+objeto.nombre+"_idseleccionado.value!=''){document.all[document.all."+objeto.nombre
							+"_idseleccionado.value].className = document.all."+objeto.nombre+"_estiloseleccionado.value} "
							+"document.all."+objeto.nombre+"_idseleccionado.value=this.id; "
							+"document.all."+objeto.nombre+"_estiloseleccionado.value='"
							+((j%2==0)?"fila1":"fila2")+"'; this.className='seleccionarFila'; "+funciones);
			tr.ondblclick	= Function(funciones2);
			
		}else{
			if(objeto.eventoClickFila){
				tr.onclick		= Function(objeto.eventoClickFila);
				tr.ondblclick	= Function(funciones2);
			}
			
		}
	}
	function f_reacomodarColores(objeto){
		var tbody 		= document.all[objeto.nombre+"_tabla"];
		
		for (var i=0;i<tbody.rows.length;i++){
			tbody.rows[i].className 	= (i%2==0)?"fila1":"fila2";
			
			var funciones = "";
			var funciones2 = "";
			if(objeto.eventoClickFila){
				funciones = objeto.eventoClickFila;
			}
			if(objeto.eventoDblClickFila){
				funciones2 = objeto.eventoDblClickFila;
			}
			
			if(objeto.seleccion){
				tbody.rows[i].onmouseout 	= Function("if(this.className!='seleccionarFila'){this.className='"+((i%2==0)?"fila1":"fila2")+"';}");
				tbody.rows[i].onclick		= Function("if(document.all."+objeto.nombre+"_idseleccionado.value!=''){document.all[document.all."
					+objeto.nombre
					+"_idseleccionado.value].className = document.all."+objeto.nombre+"_estiloseleccionado.value} "
					+"document.all."+objeto.nombre+"_idseleccionado.value=this.id; "
					+"document.all."+objeto.nombre+"_estiloseleccionado.value='"+((i%2==0)?"fila1":"fila2")+"'; this.className='seleccionarFila'; "+funciones);
				tbody.rows[i].ondblclick	= Function(funciones2);
			}else{
				if(objeto.eventoClickFila){
					tr.onclick		= Function(objeto.eventoClickFila);
					tr.ondblclick	= Function(funciones2);
				}
			}
		}
	}
	function f_reacomodarIds(id, objeto){
		if(isNaN(parseInt(id))){
			var indice 	= parseInt(id.replace(objeto.nombre+"_id",""));
		}else{
			var indice 	= parseInt(id);
		}
		var tbody	= document.all[objeto.nombre+"_tabla"];
		for (var i=(indice+1);i<tbody.rows.length+1;i++){
			document.all[objeto.nombre + "_id" + i].id = objeto.nombre + "_id" + (i-1);
		}
	}
	function f_limpiarSeleccion(objeto){
		document.all[objeto.nombre+"_estiloseleccionado"].value="";
		document.all[objeto.nombre+"_idseleccionado"].value="";
	}
	function f_creada(){
		return this.v_creada;
	}
}