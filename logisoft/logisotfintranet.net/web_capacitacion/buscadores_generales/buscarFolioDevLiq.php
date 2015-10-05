<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
	
	
	$get=@mysql_query("SELECT count(*) FROM devyliqautomatica WHERE sucursal = $_SESSION[IDSUCURSAL] AND liquidada = 'N'
	GROUP BY unidad",$link);

	$total =@mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
      <td width="7%" class="FondoTabla">Folio <input type="hidden" name="ands" value="<?=$losands?>"></td>
      <td width="85%" class="FondoTabla">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
					$get=@mysql_query("SELECT unidad FROM devyliqautomatica WHERE sucursal = $_SESSION[IDSUCURSAL] AND liquidada = 'N'
					GROUP BY unidad",$link);
			while($row = mysql_fetch_array($get)){
				
			?>
				<tr >
       <td width="10%" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.mens.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>
            <td width="79%" class="Tablas"></td>
            <td width="19px"></td>
          </tr>	
		<?	}
		
		?>

      </table></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFolioRepartos.php?funcion=$_GET[funcion]&tipo=".$tipo."&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>