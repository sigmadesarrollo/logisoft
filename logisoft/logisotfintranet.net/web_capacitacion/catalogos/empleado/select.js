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
		ValidarCP();
	} 
}

//mostrar colonia empleado
function ConsultaColoniaEmpleado(colonia,tipo){	
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="CatalogoEmpleadoResult.php";
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

//Imprimir Optener Colonia
/*function CalogoEmpleadoColonia(cp,colonia,poblacion,municipio,estado,tipo){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="CatalogoEmpleadoResult.php";
	url=url+"?cp="+cp+"&colonia="+colonia+"&poblacion="+poblacion+"&municipio="+municipio+"&estado="+estado+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=MostrarEmpleadoCP;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}*/

//CATALOGO EMPLEADO (MOSTRAR EMPLEADO)
function MostrarEmpleado(id,tipo){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="CatalogoEmpleadoResult.php";
	url=url+"?id="+id+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateMostrarEmpleado;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateMostrarEmpleado(){
 var preloader;
 preloader=document.getElementById('DivTabla');
	if(xmlHttp.readyState==1){
	document.getElementById('DivTabla').innerHTML = "Cargando...";
	//modificamos el estilo de la div, mostrando una imagen de fondo
	//preloader.style.background = "url('gif-loading.gif') no-repeat"; 
	} else if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById('DivTabla').innerHTML=xmlHttp.responseText;
		//preloader.style.background = "none"; 
	} 
}
