<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	include('../../Conectar.php');

	$link=Conectarse('webpmm');

	$tipo=$_GET['tipo'];

	$valor=$_GET['valor'];

if ($valor!=""){

	switch ($tipo) {

		case "nick":

			$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cn.nick like '".$valor."%'",$link);			

			break;

		case "rfc":

			$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cc.rfc like '".$valor."%'",$link);

			break;

		case "id":

			$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cc.id='".$valor."'",$link);

			break;

		case "nombre":

			$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cc.nombre like '".$valor."%'",$link);

			break;

		case "paterno":

			$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cc.paterno like '".$valor."%'",$link);

			break;

		case "materno":

			$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cc.materno like '".$valor."%'",$link);

			break;

	}	

}

				$total =@mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

</head>



<body>

<table width="100%" border="0" align="center">

  <?	

if ($valor!=""){

  switch ($tipo) {

		case "nick":

			$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cn.nick like '".$valor."%' limit ".$st.",".$pp."",$link);

			break;

		case "rfc":

		$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cc.rfc like '".$valor."%' limit ".$st.",".$pp."",$link);				

			break;

		case "id":

		$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cc.id='".$valor."' limit ".$st.",".$pp."",$link);	

			break;

		case "nombre":

			$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cc.nombre like '".$valor."%' limit ".$st.",".$pp."",$link);

			break;

		case "paterno":

			$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cc.paterno like '".$valor."%' limit ".$st.",".$pp."",$link);	

			break;

		case "materno":

			$get = @mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente WHERE cc.materno like '".$valor."%'limit ".$st.",".$pp."",$link);

			break;

	}

		while($row=@mysql_fetch_array($get)){

		

	?>

  <tr >

    <td width="89" class="Tablas" >

        <input class="Tablas" name="nick" readonly="" type="text" id="nick" value="<?=$row[0] ?>" size="10" style="border:none; cursor:pointer" onClick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>

    <td width="100" class="Tablas"><input readonly="" name="rfc" type="text" id="rfc" value="<?=$row[1] ?>" class="Tablas" size="12" style="border:none; cursor:pointer" onClick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>

    <td width="48" class="Tablas"><input readonly="" name="id3" type="text" id="id3" value="<?=$row[2] ?>" class="Tablas" size="4" style="border:none; cursor:pointer" onClick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>

    <td width="110" class="Tablas"><input readonly="" name="nombre" type="text" id="nombre" value="<?=$row[3] ?>" class="Tablas" size="16" style="border:none; cursor:pointer" onClick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>

    <td width="101" class="Tablas"><input readonly="" name="paterno" type="text" id="paterno" value="<?=$row[4] ?>" class="Tablas" size="15" style="border:none; cursor:pointer" onClick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>

    <td width="82" class="Tablas"><input readonly="" name="materno" type="text" id="materno" value="<?=$row[5] ?>" class="Tablas" size="15" style="border:none; cursor:pointer" onClick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>

    <td width="36"></td>

  </tr>

  <? }

  } ?>

</table>

</body>

</html>

