<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if (isset($_SESSION['gvalidar'])!=100){

echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";

	}else{*/

	include('../Conectar.php');

	$link=Conectarse('webpmm');

	//$get=@mysql_query("select count(*) from solicitudguiasempresariales where status = 1 AND prepagada='$_GET[prepagada]'");

	$get=@mysql_query("select count(*) from solicitudguiasempresariales where  prepagada='$_GET[prepagada]'");

	$total =@mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../buscadores_generales/select.js"></script>

<link href="../evaluacion/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

</head>



<body>

<form name="buscar" >

<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="27%" class="FondoTabla">Folio</td>

      <td width="73%" class="FondoTabla">Fecha</td>

    </tr>

    <tr>

      <td colspan="2" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">

          <?

			$get =@mysql_query("SELECT * FROM solicitudguiasempresariales WHERE  prepagada='$_GET[prepagada]'

							limit ".$st.",".$pp,$link);		

			while($row=@mysql_fetch_array($get)){

			?>

				<tr >

       <td width="128" class="Tablas" >

<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[foliotipo];?>','<?=$_GET[prepagada]?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[foliotipo];?></span></td>

            <td width="303" class="Tablas"><?=$row[fecha]; ?></td>

            <td width="51"></td>

          </tr>	

		<?	}

		

		?>



      </table></td>

    </tr>

    <tr>

      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarSolicitudGuias.php?funcion=$_GET[funcion]&prepagada=$_GET[prepagada]&st="); ?></font></td>

    </tr>

  </table> 

</form>

</body>

</html>

<? //} ?>