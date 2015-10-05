<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	switch ($_GET[mostrar]){
		case "entregasocurre":
			$s = "SELECT * FROM entregasocurre e WHERE e.entregadas=0 AND idsucursal = '$_SESSION[IDSUCURSAL]' AND
			entregadas = 0";
			break;
		case "entregasocurrealmacen":
			$s = "select count(*) from entregasocurre where entregadas=0 and sucursal = '$_SESSION[IDSUCURSAL]'";
			break;
	}
	$get=@mysql_query($s);
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
      <td width="7%" class="FondoTabla">Folio</td>
      <td width="85%" class="FondoTabla">Fecha</td>
    </tr>
    <tr>
      <td colspan="2" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" class="Tablas">
          <?
		  switch ($_GET[mostrar]){
			case "entregasocurre":
				$s = "select e.folio, DATE_FORMAT(e.fecha,'%d/%m/%Y') AS fecha FROM entregasocurre e where entregadas=0 
				and idsucursal = '$_SESSION[IDSUCURSAL]' AND
				entregadas = 0 limit ".$st.','.$pp;
				break;
			case "entregasocurrealmacen":
				$s = "select folio, date_format(fecha,'%d/%m/%Y') as fecha from entregasocurre where entregadas=0 
				and sucursal = '$_SESSION[IDSUCURSAL]'limit ".$st.','.$pp;
				break;
		}
		$get =@mysql_query($s,$link);			
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="10%" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>
            <td width="79%" class="Tablas"><?=$row[1]; ?></td>
            <td width="19px"></td>
          </tr>	
		<?	}
		
		?>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFoliosEntregaOcurre.php?funcion=$_GET[funcion]&mostrar=$_GET[mostrar]&sucorigen=$_GET[sucorigen]&tipo=".$tipo."&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>