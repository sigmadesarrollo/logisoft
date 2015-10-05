function AJAX(){ 
	var ajax=false;
	if(window.XMLHttpRequest)
		ajax=new XMLHttpRequest();
	else if(window.ActiveXObject){
		try{
			ajax=new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				ajax=new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){
			}
		}
	}else{
		alert("Tu navegador no acepta AJAX");
		return false;
	}
	return ajax;
}
//__________________________
function Load(URL, PARAMETROS, PANEL)//Manda siempre un ampersand en el parámetro URL Ej. var=1&var2=2
{
	var ajax=AJAX();
	var destino=document.getElementById(PANEL);
	ajax.onreadystatechange = function(){
		if(ajax.readyState == 4 && ajax.status==200){
		destino.innerHTML=ajax.responseText;
		} else {
			destino.innerHTML="<br><p><br><p><br><p><p align='Center'><img src='ajax-loader.gif'><p class='style11' align='center'>cargando...</p></p>";
		}
	}
		ajax.open("GET",URL+'?a='+PARAMETROS,true);
	//alert(URL+'?a='+Math.random()+((PARAMETROS=="")?"":"&")+PARAMETROS);
	ajax.send(null);
}


function boton(){
		if(document.getElementById("txtNombreEmp").value == ""){
			document.getElementById("txtNombreEmp").focus();
			alert("Los campos con * son obligatorios");
		}else if(document.getElementById("txtNombreCont").value == ""){
			document.getElementById("txtNombreCont").focus();
			alert("Los campos con * son obligatorios");
		}else if(document.getElementById("txtEmail").value == ""){
			document.getElementById("txtEmail").focus();
			alert("Los campos con * son obligatorios");
		}else if(document.getElementById("txtTelefono").value == ""){
			document.getElementById("txtTelefono").focus();
			alert("Los campos con * son obligatorios");
		}else if(document.getElementById("txtMejorHor").value == ""){
			document.getElementById("txtMejorHor").focus();
			alert("Los campos con * son obligatorios");
		}else{
			document.getElementById("forms").submit();
		}
	}
