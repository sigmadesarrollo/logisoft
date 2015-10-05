<?

	session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	

	require_once("../Conectar.php");

	$l = Conectarse("webpmm");

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Principal</title>

<style type="text/css">

<!--

.Estilo3 {font-size: 12px}

-->

</style>

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<style type="text/css">

<!--

body {

	margin-left: 0px;

	margin-top: 0px;

	margin-right: 0px;

	margin-bottom: 0px;

}

-->

</style></head>



<body>



<form id="form1" name="form1" method="post" action="">

<table width="841" border="0" cellpadding="0" cellspacing="0">

	<tr>
		<td width="250" height="21" align="left" id="folioSeleccionado" style="font-size:14px; color:#F00" ></td>
    	<td width="178" id="tab0" height="21" class="tab_seleccionado" onClick="seleccionarTabs(0)" align="center">GUIA</td>

      <td width="176" id="tab1" class="tab_deseleccionado" onClick="seleccionarTabs(1)" align="center">SUSTITUCIÓN</td>

        <td width="237"><div class="ebtn_motivo" onClick="abrirMotivo()"></div>

        </td>

    </tr>

    <tr>

    	<td height="257" colspan="3">&nbsp;</td>

    </tr>

</table>

<div style="position:absolute; left: 0px; top: 19px; width: 100%; visibility:visible;" id="canvas0">

<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td height="109"><iframe name="guiaoriginal" style="width:100%; height:650px;" frameborder="0"></iframe></td>

  </tr>

</table>

</div>

<div style="position:absolute; left: 0px; top: 19px; width: 100%; visibility:hidden;" id="canvas1">

<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

	<tr>

    	<td height="13">

        	<iframe name="guiasustitucion" style="width:100%; height:650px;" frameborder="0"></iframe>

        </td>

    </tr>

</table>

</div>

</form>

</body>

<script>

	

	<?

		$s = "SELECT MAX(id) AS id FROM guiasventanilla_cs WHERE folioguia = '$_GET[folioguia]'";

		$r = mysql_query($s,$l) or die($s);

		$f = mysql_fetch_object($r);

	?>



	window.onload = function (){

		document.all.guiaoriginal.src = "guia_cargar_original.php?funcion=solicitarGuia('<?=$_GET[folioguia]?>')";

		document.all.guiasustitucion.src = "guia_cargar_sustitucion.php?funcion=solicitarGuia('<?=$f->id?>')";

		document.all.folioSeleccionado.innerHTML = '<?=$_GET[folioguia]?>';

	}

	

	function abrirMotivo(){

		abrirVentanaFija("guiacs_motivo.php?id=<?=$f->id?>", 480, 400, 'ventana', 'Datos Producto');

	}

	

	function seleccionarTabs(seleccion){

		var totaltabs 	= 2;

		var estilosel 	= "tab_seleccionado";

		var estilodesel = "tab_deseleccionado";

		var tabs		= "tab";

		var canvas		= "canvas";

		

		for(var i=0; i<totaltabs; i++){

			if(seleccion==i){

				document.getElementById(tabs+i).className = estilosel;

			}else{

				document.getElementById(tabs+i).className = estilodesel;

			}

		}

		

		for(var i=0; i<totaltabs; i++){

			if(seleccion==i){

				document.getElementById(canvas+i).style.visibility = "visible";

			}else{

				document.getElementById(canvas+i).style.visibility = "hidden";

			}

		}

	}

</script>

</html>

