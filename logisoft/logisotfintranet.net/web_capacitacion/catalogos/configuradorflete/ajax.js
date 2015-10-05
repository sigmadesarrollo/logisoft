function objetoAjax(){
	var xmlhttp=false;
	try{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			xmlhttp = false;
  		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function enviarDatosEmpleado(){
	//donde se mostrará lo resultados
	divResultado = document.getElementById('resultado');
	divFormulario = document.getElementById('formulario');
	divResultado.innerHTML= '<img src="loading.gif">';
	
	//valores de los cajas de texto
	id=document.frmempleado.idempleado.value;
	nom=document.frmempleado.nombres.value;
	dep=document.frmempleado.departamento.value;
	suel=document.frmempleado.sueldo.value;
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion ->actualizacion.php
	ajax.open("POST", "actualizacion.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			divResultado.innerHTML = ajax.responseText
			//una vez actualizacion ocultamos formulario
			divFormulario.style.display="none";

		}
	}
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idempleado="+id+"&nombres="+nom+"&departamento="+dep+"&sueldo="+suel)
}

function enviarDatos(renglon,columna,valor){
	//donde se mostrará lo resultados
	divFormulario = document.getElementById('detalle');

	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion ->actualizacion.php

	ajax.open("POST", "actualizar2.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			divFormulario.innerHTML = ajax.responseText                        
			divFormulario.style.display="block";


		}
	}
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("vpvalor="+valor+"&vpcolumna="+columna+"&vprenglon="+renglon);
}


function pedirDatos(zonai,zonaf,intzon){
	//donde se mostrará el formulario con los datos
                 

	divFormulario = document.getElementById('detalle');
        divFormulario.innerHTML= '<img src="loading.gif">';

	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//uso del medotod POST
	ajax.open("POST", "actualizar.php");
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			divFormulario.innerHTML = ajax.responseText                        
			divFormulario.style.display="block";
		}
	}
	//como hacemos uso del metodo POST
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando el codigo del empleado
	ajax.send("vpzonai="+zonai+"&vpzonaf="+zonaf+"&vpintzon="+intzon);
}