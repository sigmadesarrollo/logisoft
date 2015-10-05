var xmlHttp;

/*function ObtenerNumeroEconomico(economico,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogounidadresult.php";
	url=url+"?economico="+economico+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
	
}*/

/*function obtenerTipoUnidad(unidad,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="catalogounidadresult.php";
	url=url+"?unidad="+unidad+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateUnidad;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}*/

//MOSTRAR CONSULTA MOTIVOS
/*function ConsultaCatalogoMotivo(id,tipo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="CatalogoMotivosResult.php";
	url=url+"?id="+id+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateCatalogoMotivo;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function stateCatalogoMotivo(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("DivCatalogoMotivos").innerHTML=xmlHttp.responseText;
	} 
}*/

/*function stateChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
	} 
}*/

/*function stateUnidad(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtUnidad").innerHTML=xmlHttp.responseText;
	} 
}*/
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