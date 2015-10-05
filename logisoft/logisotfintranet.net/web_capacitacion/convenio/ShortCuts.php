<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../../javascript/shortcut.js"></script>

<script>

function foco(nombrecaja){

	if(nombrecaja=="codigo"){

		document.getElementById('oculto').value="1";

	}else if(nombrecaja=="colonia"){

		document.getElementById('oculto').value="2";

	}

}

shortcut.add("Ctrl+b",function() {

	if(document.form1.oculto.value=="1"){

	abrirVentanaFija('buscarsucursal.php', 550, 430, 'ventana', 'Busqueda')

	}else if(document.form1.oculto.value=="2"){

abrirVentanaFija('CatalogoSucursalBuscarColonia.php', 570, 350, 'ventana', 'Busqueda')

	}

});

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

</head>



<body>

<input name="codigo" type="text" readonly="" id="codigo" size="20"  value="<?= $codigo; ?>" style="font:tahoma;font-size:9px; background:#FFFF99" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''"/>

<input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />

</body>

</html>

