<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 

   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

<script type="text/javascript">

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

 

function detectkey(evt,obj) {

keycode = (evt.keyCode==0) ? evt.which : evt.keyCode;

if (keycode!=8) {

	cadena=obj.value + String.fromCharCode(keycode);

    pagina='filtra.php?cadena='+cadena;

	

}else {

    obj.value="";

    pagina='filtra.php';

}

    divcontenido = document.getElementById('tabla_usuarios');

    ajax=objetoAjax();

    ajax.open("POST", pagina, true);

    ajax.onreadystatechange=function() {

      if (ajax.readyState==4) {

        divcontenido.innerHTML = ajax.responseText

      }

    }

    ajax.send(null);

}

</script>

</head>

<body>

 

<input type="text" name="nom" id="nom" value="" size="30" maxlength="30" onkeypress="detectkey(event,this)">

<br />

<div id="tabla_usuarios"></div>

</body>

</html>

