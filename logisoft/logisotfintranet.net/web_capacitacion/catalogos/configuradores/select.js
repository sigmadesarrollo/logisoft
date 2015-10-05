var xmlHttp;

function ModificarServicio(tipo,id){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="configuradorservicioresult.php";
	url=url+"?tipo="+tipo+"&id="+id;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function EliminarServicio(tipo,id){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}	
	var url="configuradorservicioresult.php";
	url=url+"?tipo="+tipo+"&id="+id;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateGrid;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}
function stateChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
		Verificar()
	} 
}
function stateGrid(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtGrid").innerHTML=xmlHttp.responseText;
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