var xmlHttp;

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

function stateChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
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