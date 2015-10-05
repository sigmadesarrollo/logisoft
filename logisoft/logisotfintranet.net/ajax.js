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

function consultaTexto(respuesta,datos){

	crearLoading();

	var consult = datos;

	

	var ajax=objetoAjax();

	ajax.open("GET", consult);

	

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

			var laUrl = document.URL;
			
			if(laUrl.indexOf('pmmintranet.net')>-1){
				var direccion = laUrl.substr(0,
					laUrl.indexOf('pmmintranet.net')+15+
					((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacion')>-1)?18:1))
				);
			}else{
				var direccion = laUrl.substr(0,
					laUrl.indexOf('pmmintranet.com')+15+
					((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacion')>-1)?18:1))
				);
			}
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

			capa.innerHTML="<table align='center' style='width:124px; height:46px'  background='"+direccion+"images/fondo_loading.gif'><tr><td valign='middle' align='center'><img src='"+direccion+"images/loading.gif'></td><td valign='middle' align='center'>Cargando...</td></tr></table>"; 

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