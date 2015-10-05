<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	require_once('../Conectar.php');

	$link=Conectarse('webpmm');	

	$folio=$_GET['folio'];		

	$sqldetalle=@mysql_query("SELECT e.cantidad, cd.descripcion, e.contenido, e.peso, e.largo, e.ancho, e.alto, e.volumen, e.pesototal FROM evaluacionmercanciadetalle e INNER JOIN catalogodescripcion cd ON e.descripcion=cd.id WHERE e.evaluacion='$folio'",$link);	

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<style type="text/css">

<!--

.style2 {color: #464442;

	font-size:9px;

	border: 0px none;

	background:none

}

.style31 {font-size: 9px;

	color: #464442;

}

.style31 {font-size: 9px;

	color: #464442;

}

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

-->

</style>

</head>



<body>

<? $line = 0; ?>

<table width="570" border="0" cellspacing="0" cellpadding="0">

  <?

			$line=@mysql_num_rows($sqldetalle);

			while($res=@mysql_fetch_array($sqldetalle)){ ?>

  <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >

    <td height="16" width="17" ><input name="id" type="hidden" id="id" value="<?=$row[id] ?>" /></td>

    <td width="45" align="center" class="style31"  >&nbsp;</td>

    <td width="32" align="center" class="style31"  ><input name="cantidad" type="text" class="style2" id="cantidad" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" value="<?=$res[cantidad]?>" size="8" /></td>

    <td width="95" align="center" class="style31"><input name="descripcion" type="text" class="style2" id="descripcion" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[descripcion] ?>" readonly="" size="20" /></td>

    <td width="128" align="center" class="style31"><input name="contenido" type="text" class="style2" id="contenido" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[contenido] ?>" readonly="" size="20" /></td>

    <td width="119" class="style31" align="center"><input name="peso" type="text" class="style2" id="peso" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[pesototal] ?>" readonly="" size="8" /></td>

    <td width="43" class="style31" align="center"><input name="largo" type="text" readonly="" class="style2" id="largo" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[largo] ?>" size="5" /></td>

    <td width="29" class="style31" align="center"><input name="ancho" type="text" readonly="" class="style2" id="ancho" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[ancho] ?>" size="5" />

    </td>

    <td width="22" align="center" class="style31" ><input name="alto" type="text" class="style2" id="alto" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[alto] ?>" size="5" /></td>

    <td width="40" align="center" class="style31"><input name="volumen" type="text" class="style2" id="volumen" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[volumen] ?>" size="8" /></td>

  </tr>

  <?

		$line ++ ; }			

	?>

</table>

</body>

</html>

