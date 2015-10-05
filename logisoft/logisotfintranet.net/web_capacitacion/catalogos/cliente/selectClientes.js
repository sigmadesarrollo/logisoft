var xmlHttp;

function ConsultaColoniaClientes(colonia,ciudad){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	var url="buscarcoloniaresult.php";
	url=url+"?colonia="+colonia+"&ciudad="+ciudad;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateDir;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateDir(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
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