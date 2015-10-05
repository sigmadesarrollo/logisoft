var xmlHttp;
var Control;
var contenedor;
var indice ;
//
function LlenarGrip(url,miArray,matrix,div,capa){ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		alert ("El navegador no soporta HTTP Request");
		return;
	}
	url=url+"?&miArray="+miArray;
	url=url+"&sid="+Math.random();
	contenedor= capa ;
	indice = matrix ;
	if (div!=null){
		Control = 1 ;
	}else{
		Control = 0 ;
	}
	xmlHttp.onreadystatechange=stateLlenarGrip;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(miArray);
}

function stateLlenarGrip(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		if (Control==0){
			var contenido = xmlHttp.responseText
			fragmento = contenido.split('&');
			for (i=0; i<=fragmento.length; i++){
				if (fragmento[i]!=undefined){
					varlor = fragmento[i].split('=');
					Propiedad = varlor[0];
					if (indice!=null){ Propiedad = varlor[0]+indice;}
					//alert(Propiedad+'-'+varlor[1]);
					document.getElementById(Propiedad).value=varlor[1];
				
			
				}
			}
		}else{
			document.getElementById(contenedor).innerHTML=xmlHttp.responseText;
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