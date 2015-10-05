var xmlHttp;

function ConsultaEstado(estado){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogoestadoresult.php";
	url=url+"?estado="+estado;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
/*
function ObtenerMunicipio(municipio,tipo){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogomunicipioresult.php";
	url=url+"?municipio="+municipio+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}*/
function ObtenerEstado(estado,tipo){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogomunicipioresult.php";
	url=url+"?estado="+estado+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateEstado;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
function ConsultarDefaultPais(tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="consultas.php";
	url=url+"?tipo="+tipo;
	url=url+"?";
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function PoblacionConsulta(tipo,poblacion){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="consultaHtml.php";
	url=url+"?tipo="+tipo+"&poblacion="+poblacion;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=statePoblacion;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function ColoniaConsulta(tipo,colonia){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="consultaHtml.php";
	url=url+"?tipo="+tipo+"&colonia="+colonia;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateColonia;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
//MOSTRAR BUSQUEDA DE POBLACION EN CATALOGOPOBLACION
/*function ConsultaPoblacion(id,descripcion,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogopoblacionresult.php";
	url=url+"?id="+id+'&descripcion='+descripcion+'&tipo='+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateConsultaPoblacion;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}*/

//FILTRO PARA POBLACION DE CATALOGOPOBLACION
function FiltroPoblacion(poblacion,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogopoblacionresult.php";
	url=url+"?poblacion="+poblacion+'&tipo='+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateFiltroPoblacion;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

//FILTRO POR ESTADO  CATALOGOMUNICIPIO
function FiltroPoblacion_CatMunicipio(estado,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogomunicipioresult.php";
	url=url+"?estado="+estado+'&tipo='+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateFiltroPoblacion;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}


//FILTRO PARA MUNICIPIO DE CATALOGOPOBLACION
function FiltroMunicipio(municipio,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogopoblacionresult.php";
	url=url+"?municipio="+municipio+'&tipo='+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateFiltroMunicipio;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

//FILTRO POR MUNICIPIO DE CATALOGOMUNICIPIO
function FiltroMunicipio_CatMunicipio(municipio,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogomunicipioresult.php";
	url=url+"?municipio="+municipio+'&tipo='+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateFiltroMunicipio;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

//CATALOGO CODIGO POSTAL
function CatalogoCodigoPostal(cp,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogocodigopostalResult.php";
	url=url+"?cp="+cp+'&tipo='+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateCatalogoCodigoPostal;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

//CATALOGO CODIGO POSTAL
function stateCatalogoCodigoPostal(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
	} 
}

//FILTRO PARA MUNICIPIO DE CATALOGOPOBLACION
function stateFiltroMunicipio(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
	} 
}
//FILTRO PARA POBLACION DE CATALOGOPOBLACION
function stateFiltroPoblacion(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
	} 
}
/*
//MOSTRAR BUSQUEDA DE POBLACION EN CATALOGOPOBLACION
function stateConsultaPoblacion(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
	} 
}*/


function stateChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
	} 
}
function stateEstado(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtEstado").innerHTML=xmlHttp.responseText;
	} 
}
function statePoblacion(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtPoblacion").innerHTML=xmlHttp.responseText;
	} 
}
function stateColonia(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtColonia").innerHTML=xmlHttp.responseText;
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