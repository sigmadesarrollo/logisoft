function objetoAjax(){

	var xmlhttp=false;

	try {

		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");

	} catch (e) {

		try {

		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

		} catch (E) {

			xmlhttp = false;

  		}

	}



	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {

		xmlhttp = new XMLHttpRequest();

	}

	return xmlhttp;

}





//FUNCION QUE VA Y CONSULTA EL PRODUCTO PARA SER INGRESADO A LA TEMPORAL

function consulta(respuesta,datos){

	crearLoading();

	var consult = datos;

	

	var ajax=objetoAjax();
	ajax.open("POST", consult);
	

	ajax.onreadystatechange=function() {

		if (ajax.readyState==4) {
			ocultarLoading();
			eval(respuesta+"(ajax.responseXML)");
		}

	}

	ajax.send(null)

}

function consultaTexto(respuesta,datos){

	crearLoading();

	var consult = datos;

	

	var ajax=objetoAjax();

	ajax.open("POST", consult);

	

	ajax.onreadystatechange=function() {

		if (ajax.readyState==4) {

			ocultarLoading();

			eval(respuesta+"(ajax.responseText)");

		}

	}

	ajax.send(null)

}



function crearLoading(){

	if(!document.getElementById("ajax_loading_img")){

		try{

			var capa;

			capa=document.createElement('div');    

			capa.setAttribute('id','ajax_loading_img');        

			document.body.appendChild(capa);

			capa = document.getElementById('ajax_loading_img'); 

			capa.style.backgroundColor='#FFFFFF'; 

			capa.style.width='124px'; 

			capa.style.height='46px'; 

			capa.style.position="absolute";

			capa.style.top = 5;

			capa.style.left = 5;

			capa.innerHTML="<table align='center' style='width:124px; height:46px'  background='http://pmmintranet.net/web_capacitacion/javascript/fondo_loading.gif'><tr><td valign='middle' align='center'><img src='http://pmmintranet.net/web_capacitacion/javascript/loading.gif'></td><td valign='middle' align='center'>Cargando...</td></tr></table>"; 

		}catch(e){

			alert(e.name + " - " + e.message);

		}

	}else{

		document.getElementById("ajax_loading_img").style.visibility="visible";

	}

}



function ocultarLoading(){

	document.getElementById("ajax_loading_img").style.visibility="hidden";

}



function convertirValoresJson(valor){

	valor = valor.replace(/&#224;/g,'á');

	valor = valor.replace(/&#233;/g,"é");

	valor = valor.replace(/&#237;/g,"í");

	valor = valor.replace(/&#243;/g,"ó");

	valor = valor.replace(/&#250;/g,"ú");

	

	valor = valor.replace(/&#193;/g,"Á");

	valor = valor.replace(/&#201;/g,"É");

	valor = valor.replace(/&#205;/g,"Í");

	valor = valor.replace(/&#211;/g,"Ó");

	valor = valor.replace(/&#218;/g,"Ú");

	

	valor = valor.replace(/&#241;/g,"ñ");

	valor = valor.replace(/&#209;/g,"Ñ");

	valor = valor.replace(/&#191;/g,"¿");

	valor = valor.replace(/&#32;/g,"");
	
	valor = valor.replace(/&#38;/g,"&");

	

	return valor;

}