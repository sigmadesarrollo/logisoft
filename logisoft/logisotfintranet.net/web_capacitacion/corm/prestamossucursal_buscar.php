<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}

	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
	$usuario=$_SESSION[NOMBREUSUARIO];
	$tipo=$_GET['tipo'];
		
	switch ($tipo){
		case 1:
			$get = @mysql_query('SELECT count(*) FROM bitacorasalida');
			break;
	}
	$total = @mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<? if($tipo==1){?>
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr class="FondoTabla">
    <td width="12%">Folio </td>
    <td width="88%">Descripción</td>
  </tr>
<tr>
    <td colspan="2" class="Tablas"><div id="txt" style="width:100%; height:300px; ">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
        <?	
		$get = mysql_query('SELECT BS.folio ,EMP.nombre FROM bitacorasalida AS BS INNER JOIN catalogoempleado AS EMP ON  BS.conductor1=EMP.id ORDER BY EMP.nombre ASC limit '.$st.','.$pp,$link);		
		while($row=@mysql_fetch_array($get)){
		
	?>
        <tr >
          <td width="54" class="Tablas" ><span onClick="window.parent.OptenerIdBitacora('<?=$row['0'];?>','<?=$row['1'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">
            <?=$row['0'];?>
          </span></td>
          <td width="377" class="Tablas"><?=$row['1'];?></td>
          <td width="51"></td>
        </tr>
        <? } ?>
      </table>
    </div></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'prestamossucursal_buscar.php?tipo=1&st='); ?></font></td>
  </tr>
</table>
<p> 
  <? } ?>
</p>
</body>
</html>
