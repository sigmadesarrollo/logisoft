// JavaScript Document
var xmlHttp;



//filtro Colonia Prospecto
function ConsultaColoniaProspecto(colonia,tipo){	
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="prospectoresult.php";
	url=url+"?colonia="+colonia+"&tipo="+tipo;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateColoniaProspecto;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

//Buscar filtro por colonia en prospecto
function stateColoniaProspecto(){ 
	if(xmlHttp.readyState==1){
		document.getElementById('txtDir').innerHTML = "Cargando...";
 	} else	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtDir").innerHTML=xmlHttp.responseText;
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