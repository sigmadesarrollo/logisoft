var xmlHttp;

function mmos(str){ 
alert("Entro js" + " " + str);
}
/*
function ConsultarPoblacion(poblacion,tipo){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="poblacionresul.php";
	url=url+"?poblacion="+poblacion+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateMun;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function ObtenerDestino(Destino,tipo){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="poblacionresul.php";
	url=url+"?destino="+Destino+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function ConsultarDescripcion(codigo){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="descripcionresul.php";
	url=url+"?codigo="+codigo;
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
	var url="catalogosucursalresult.php";
	url=url+"?cp="+cp+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateCP;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function stateChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
	} 
}
function stateMun(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtMun").innerHTML=xmlHttp.responseText;
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