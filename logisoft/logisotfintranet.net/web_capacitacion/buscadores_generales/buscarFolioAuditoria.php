<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
	$s = "SELECT count(*)
	FROM reporte_auditoria_principal ra
	INNER JOIN catalogosucursal cs ON ra.sucursal = cs.id
	WHERE ra.sucursal = '$_SESSION[IDSUCURSAL]';";
	$get=@mysql_query($s,$link) or die($s);

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
      <td width="13%" class="FondoTabla">Id <input type="hidden" name="ands" value="<?=$losands?>"></td>
      <td width="21%" class="FondoTabla">Scurusal</td>
      <td width="66%" class="FondoTabla">Fecha</td>
    </tr>
    <tr>
      <td colspan="3" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
			$s = "SELECT folio, DATE_FORMAT(ra.fechafin, '%d/%m/%Y') AS fecha, cs.prefijo sucursal
			FROM reporte_auditoria_principal ra
			INNER JOIN catalogosucursal cs ON ra.sucursal = cs.id
			WHERE ra.sucursal = '$_SESSION[IDSUCURSAL]'";
			$get=@mysql_query($s,$link) or die($s);
			while($row = mysql_fetch_array($get)){
			?>
				<tr >
       <td width="63" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');try{parent.VentanaModal.cerrar();}catch(e){parent.mens.cerrar();}" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>
            <td width="105" class="Tablas"><?=$row[2];?></td>
            <td width="240" class="Tablas"><?=$row[1];?></td>
            <td width="88"></td>
          </tr>	
		<?	}  ?>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFolioRepartos.php?funcion=$_GET[funcion]&tipo=".$tipo."&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>