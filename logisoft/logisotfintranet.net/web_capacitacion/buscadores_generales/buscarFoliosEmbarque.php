<?	session_start();
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	if($_GET[tipo]=="1"){
		$s = "SELECT count(*) FROM embarquedemercancia 
		WHERE idsucursal = '$_SESSION[IDSUCURSAL]' and tipo='NORMAL'";
	}
	if($_GET[tipo]=="2"){
		$s = "SELECT count(*) FROM embarquedemercancia 
		WHERE idsucursal = '$_SESSION[IDSUCURSAL]' and tipo='AUTOMATICO'";
	}
	if($_GET[tipo]=="3"){
		$s = "SELECT count(*) FROM embarquedemercancia 
		WHERE idsucursal = ".$_GET[sucursal]."";
	}
	
	$get = @mysql_query($s);
	
	$total = @mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 15;
?>

<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<form name="buscar" >
  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="11%" class="FondoTabla">ID</td>
      <td width="21%" class="FondoTabla">FECHA</td>
      <td width="15%" class="FondoTabla">UNIDAD</td>
      <td width="53%" class="FondoTabla">RUTA</td>
    </tr>
    <tr>
      <td height="300px" colspan="4" valign="top" class="Tablas"><table width="100%" border="0" align="center" class="Tablas">
          <?	
		if($_GET[tipo]=="1"){
			$s = "SELECT em.folio, DATE_FORMAT(em.fecha, '%d/%m/%Y'), em.unidad, cr.descripcion
				FROM embarquedemercancia em
				INNER JOIN catalogoruta cr ON em.ruta = cr.id
			WHERE em.idsucursal = '$_SESSION[IDSUCURSAL]' AND em.tipo='NORMAL'";
		}
		if($_GET[tipo]=="2"){
			$s = "SELECT em.folio, DATE_FORMAT(em.fecha, '%d/%m/%Y'), em.unidad, cr.descripcion
				FROM embarquedemercancia em
				INNER JOIN catalogoruta cr ON em.ruta = cr.id
			WHERE em.idsucursal = '$_SESSION[IDSUCURSAL]' AND em.tipo='AUTOMATICO'";
		}
		if($_GET[tipo]=="3"){
			$s = "SELECT em.folio, DATE_FORMAT(em.fecha, '%d/%m/%Y'), em.unidad, cr.descripcion
				FROM embarquedemercancia em
				INNER JOIN catalogoruta cr ON em.ruta = cr.id
			WHERE em.idsucursal = '$_SESSION[IDSUCURSAL]'";
		}
		$get = mysql_query("$s limit $st,$pp",$link);		
		while($row=@mysql_fetch_array($get)){
		
	?> 
          <tr >
       <td width="47" class="Tablas" >
<span onclick="parent.<?=$_GET[funcion]?>('<?= $row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>
            <td width="99" class="Tablas"><?=$row[1]; ?></td>
            <td width="71"><?=$row[2]; ?></td>
            <td width="261"><?=$row[3]; ?></td>
          </tr>
          <? } ?>
      </table>
          <p class="Tablas">&nbsp;</p>
	  </td>
    </tr>
    <tr>
      <td colspan="4" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFoliosEmbarque.php?funcion=$_GET[funcion]&sucursal=$_GET[sucursal]&tipo=$_GET[tipo]&st="); ?></font></td>
    </tr>
  </table> 
</form>
<? //} ?>