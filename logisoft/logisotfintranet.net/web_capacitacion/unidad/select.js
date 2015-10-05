var xmlHttp;

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

//catalogo carga y descarga
function ObtenerCarga(unidad){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="resultado.php";
	url=url+"?unidad="+unidad;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

//catalogo tipo unidad
function CargaTipoUnidad(codigo){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="restipounidad.php";
	url=url+"?codigo="+codigo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function stateChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
	} 
}
//Evaluacion De Mercancia
function ObtenerCosto(tipo,cantidad){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="GridEvaluacionMercancia_procesos.php";
	url=url+"?tipo="+tipo+"&cantidad="+cantidad;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateCosto;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}

function stateCosto(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		var contenido = xmlHttp.responseText
		document.getElementById("TotalEmpaque").value=contenido;
		
	} 
}
function ObtenerCosto2(tipo,cantidad){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="evaluacionmercanciaresult.php";
	url=url+"?tipo="+tipo+"&cantidad="+cantidad;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateCosto2;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
function stateCosto2(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		
					document.getElementById("txtHint3").innerHTML=xmlHttp.responseText;
	} 
}


function SucDestino(sudestino){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}

	var url="GridEvaluacionMercancia_procesos.php";
	url=url+"?sudestino="+sudestino;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=StatesucDestino;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
function StatesucDestino(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		var contenido = xmlHttp.responseText
		document.getElementById("SucDestino").value=contenido;
	} 
}
//Cataloga Empleado buscar por codigo
function CalogoEmpleadoCP(cp,tipo){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="CatalogoEmpleadoResult.php";
	url=url+"?cp="+cp+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=MostrarEmpleadoCP;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}


//Mostrar empleado
function MostrarEmpleadoCP(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("CodigoPostalEmpleado").innerHTML=xmlHttp.responseText;
	} 
}

//mostrar colonia empleado
function ConsultaColoniaEmpleado(cp,tipo){	
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="CatalogoEmpleadoBuscarColoniaResult.php";
	url=url+"?colonia="+colonia+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateColoniaEmpleado;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateColoniaEmpleado(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("divColonia").innerHTML=xmlHttp.responseText;
	} 
}



