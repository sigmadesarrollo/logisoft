<? session_start();
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	
	if($_GET[cestado]!="TODOS"){
		if($_GET[cestado] == "EN AUTORIZACION"){
			$estadoconvenio = " where estadoconvenio LIKE '$_GET[cestado]%' ";
		}else{
			$estadoconvenio = " where estadoconvenio = '$_GET[cestado]' ";
		}
	}
	
	$get=@mysql_query("SELECT count(*) FROM generacionconvenio
		$estadoconvenio");
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
</head>

<body>
<form name="buscar" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="17%" class="FondoTabla">Folio</td>
      <td width="16%" class="FondoTabla">Fecha</td>
      <td class="FondoTabla">Nombre</td>
</tr>
    <tr>
      <td height="300px" colspan="3" valign="top" class="Tablas"><table width="100%" border="0" align="center" class="Tablas">
          <?
		$get =@mysql_query("SELECT folio, estadoconvenio as estado, 
		CONCAT_WS(' ', nombre, apaterno, amaterno) AS nombrec,
		DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha FROM generacionconvenio
		$estadoconvenio limit $st,$pp",$link);		
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="76" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[folio];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[folio];?></span></td>
            <td width="77" class="Tablas"><?=$row[fecha]; ?></td>
            <td><?=$row[nombrec]; ?></td>
</tr>	
		<?	}
		
		?>

      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarConveniosPorEstado.php?funcion=$_GET[funcion]&cestado=$_GET[cestado]&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>