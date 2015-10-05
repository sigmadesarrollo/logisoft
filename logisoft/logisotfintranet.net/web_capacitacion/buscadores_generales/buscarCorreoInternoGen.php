<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	

	require_once('../Conectar.php');

	$link=Conectarse('webpmm');	

	$get=@mysql_query('select count(*) from correointerno where sucorigen='.$_SESSION[IDSUCURSAL].'',$link);

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

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

</head><body>

<form name="buscar" >

<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="11%" class="FondoTabla">Folio </td>

      <td width="25%" class="FondoTabla">Fecha</td>

      <td width="64%" class="FondoTabla">Sucursal Destino</td>

    </tr>

    <tr>

      <td height="300px" colspan="3" valign="top" class="Tablas" ><table width="100%" border="0" align="center" class="Tablas">

          <?

		 

		$get =@mysql_query('SELECT ci.folio, DATE_FORMAT(ci.fechacorreo,"%d/%m/%Y") AS fechacorreo, 

		cs.descripcion as sucursal FROM correointerno ci 

		INNER JOIN catalogodestino cs ON ci.destino = cs.id

		WHERE ci.sucorigen='.$_SESSION[IDSUCURSAL].' limit '.$st.','.$pp,$link);		



			while($row=@mysql_fetch_array($get)){

			?>

				<tr >

       <td width="45" class="Tablas" >

<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>

            <td width="124" class="Tablas"><?=$row[1]; ?></td>

            <td width="313" class="Tablas"><?=cambio_texto($row[2]); ?></td>

          </tr>	

		<?	}

		

		?>      </table></td>

    </tr>

    <tr>

      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarCorreoInternoGen.php?funcion=$_GET[funcion]&st="); ?></font></td>

    </tr>

  </table> 

</form>

</body>

</html>