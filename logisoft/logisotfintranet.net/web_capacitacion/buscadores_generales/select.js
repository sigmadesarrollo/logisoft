var xmlHttp;

function ExisteCodigoPostal(tipo,codigopostal){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="clienteresult.php";
	url=url+"?tipo="+tipo+"&codigopostal="+codigopostal;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateExiste;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
function ConsultaFacturacion(tipo,usuario,fechahora){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="clienteresult.php";
	url=url+"?tipo="+tipo+"&usuario="+usuario+"&fechahora="+fechahora;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
function InsertarDireccion(arreglo,fecha,modificar,id,usuario,tipo,codigo){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="mostrar.php";
	url=url+"?arreglo="+arreglo+"&fecha="+fecha+"&id="+id+"&modificar="+modificar+"&usuario="+usuario+"&tipo="+tipo+"&codigo="+codigo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateDir;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function direccion(calle){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="direccionresul.php";
	url=url+"?calle="+calle+"&colonia="+colonia+"&numero="+numero;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function BorrarTablaTemporal(usuario,fecha,tipo){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="clienteresult.php";
	url=url+"?usuario="+usuario+"&fechahora="+fecha+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
/*function consultaprospecto(prospecto,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="prospectoresult.php";
	url=url+"?prospecto="+prospecto+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}*/
function consultaCliente(cliente,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="clienteresult.php";
	url=url+"?cliente="+cliente+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function consultaClienteProspecto(cliente,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="clienteprospectoresult.php";
	url=url+"?cliente="+cliente+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
function ObtenerDescripcion(tipocliente){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="tipoclienteresul.php";
	url=url+"?tipocliente="+tipocliente;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function consultacodigopostal(cp,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="prospectoresult.php";
	url=url+"?cp="+cp+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateCP;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
//MODIFICACION BUSQUEDA POR COLONIA 
function ConsultaColonia(colonia,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="prospectoresult.php";
	url=url+"?colonia="+colonia+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateColonia;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function ConsultaCodigoPostalCliente(cp,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="clienteresult.php";
	url=url+"?cp="+cp+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateCP;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function ConsultaColoniaCliente(colonia,tipo,ciudad,municipio,estado,pais,cp){	
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="clienteresult.php";
	url=url+"?colonia="+colonia+"&tipo="+tipo+"&ciudad="+ciudad+"&municipio="+municipio+"&estado="+estado+"&pais="+pais+"&cp="+cp;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateCP;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
	
}
function ConsultaColoniaClientes(colonia){	
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="buscarcoloniaresult.php";
	url=url+"?colonia="+colonia;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateDir;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

//filtro Colonia 
function ConsultaColoniaProspecto(colonia,tipo){	
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="BuscarColonia_result.php";
	url=url+"?colonia="+colonia+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateColoniaProspecto;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

//muestra colonia prospecto
function MuestraColoniaProspecto(cp,colonia,poblacion,municipio,estado,pais,tipo){	
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="prospectoresult.php";
	url=url+"?cp="+cp+"&colonia="+colonia+"&poblacion="+poblacion+"&municipio="+municipio+"&estado="+estado+"&pais="+pais+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateColoniaProspectoMostrar;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function ConsultaColoniaDireccion(url,colonia){	
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	url=url+"?"+colonia;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateDir;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function ConsultarClienteFiltro(tipo,valor){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="buscarclienteresult.php";
	url=url+"?tipo="+tipo+"&valor="+escape(valor);
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
function mostrarEvaluacion(valor,funcion,ands){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="buscarEvaluacionGen_result.php";
	url=url+"?funcion="+funcion+"&ands="+ands+"&folio="+escape(valor);
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
function stateCP(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtCP").innerHTML=xmlHttp.responseText;
		existeCP();
	} 
}
//modificacion de busqueda por colonia
function stateColonia(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtCP").innerHTML=xmlHttp.responseText;
	} 
}

function stateChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
	} 
}
function stateExiste(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtExiste").innerHTML=xmlHttp.responseText;
	} 
}
function stateDir(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		//document.getElementById("txtDir").innerHTML=xmlHttp.responseText;
		if(xmlHttp.responseText.indexOf('No se encontro ninguna colonia')>-1){
			try{
				alerta3("No se encontro ninguna colonia","¡Atencion!");
			}catch(e){
				try{
					mens.show("A","No se encontro la colonia","¡Atencion!");
				}catch(e){
					alert("No se encontró la colonia");
				}
			}
			document.getElementById("txtDir").innerHTML="";
		}else{
			document.getElementById("txtDir").innerHTML=xmlHttp.responseText;
		}
	} 
}


//Buscar filtro por colonia en prospecto
function stateColoniaProspecto(){ 
	if(xmlHttp.readyState==1){
		document.getElementById('txtDir').innerHTML = "Cargando...";
 	} else	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtDir").innerHTML=xmlHttp.responseText;
	} 
}
//MOSTRAR COLONIA PROSPECTO
function stateColoniaProspectoMostrar(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtCP").innerHTML=xmlHttp.responseText;
	} 
}

function GetXmlHttpObject(){
	var xmlHttp=null;

	try {
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}catch (e) {
		//Internet Explorer
		try{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}catch (e){
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}