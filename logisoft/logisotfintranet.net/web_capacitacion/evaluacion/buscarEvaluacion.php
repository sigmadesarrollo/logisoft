<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	require_once('../Conectar.php');

	$link=Conectarse('webpmm');

	$tipo		=$_GET['tipo'];

	$sucursal	=$_GET['sucursal'];

	switch ($tipo) {

		case "evaluacion":

			$get=@mysql_query('select count(*) from evaluacionmercancia where sucursal="'.$sucursal.'" AND estado<>"ENGUIA"');

			break;

	}

	$total =@mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="select.js"></script>

<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<script>

var sucursalorigen = 0;

function ObtenerSucursalOrigen(){

	if('<?=$_SESSION[IDSUCURSAL]?>'!=""){

	sucursalorigen = '<?=$_SESSION[IDSUCURSAL]?>';

	document.all.sucursalorigen.value = sucursalorigen;

	}

}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

</head>

<body>

<form name="buscar" >

<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="10%" class="FondoTabla">Folio</td>

      <td width="31%" class="FondoTabla">Fecha

      <input name="sucursalorigen" type="hidden" id="sucursalorigen" value="<?=$sucursalorigen ?>"></td>

	  

      <td width="59%" class="FondoTabla">Destino</td>

    </tr>

    <tr>

      <td colspan="3" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">

          <?

		  switch ($tipo) {

			case "evaluacion":

		$get =@mysql_query('SELECT e.folio,DATE_FORMAT(e.fechaevaluacion,"%d/%m/%Y") AS fechaevaluacion,

							cd.descripcion AS destino FROM evaluacionmercancia e

							INNER JOIN catalogodestino cd ON e.destino = cd.id 

							where e.sucursal="'.$sucursal.'" AND e.estado<>"ENGUIA" limit '.$st.','.$pp,$link);

				break;

		}

			while($row=@mysql_fetch_array($get)){

			?>

				<tr >

       <td width="49" class="Tablas" >

<span onClick="window.parent.Obtener('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>

            <td width="151" class="Tablas"><input name="folio" type="text" value="<?=$row[1] ?>" readonly="true" style="border:none; cursor:pointer" class="Tablas"></td>

            <td width="240" class="Tablas"><input name="destino" type="text" value="<?=cambio_texto($row[2]) ?>" readonly="true" style="border:none; cursor:pointer; width:200px" class="Tablas"></td>

            <td width="56"></td>

          </tr>	

		<?	} ?>

      </table>	  </td>

    </tr>

    <tr>

      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarEvaluacion.php?tipo='.$tipo.'&sucursal='.$sucursal.'&st='); ?></font></td>

    </tr>

  </table> 

</form>

</body>

</html>