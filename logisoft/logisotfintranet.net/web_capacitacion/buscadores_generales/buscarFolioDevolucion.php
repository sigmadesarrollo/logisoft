<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	
	$get=mysql_query("SELECT count(*) FROM devolucionmercancia WHERE sucursal = $_SESSION[IDSUCURSAL]");
	$total =mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form name="buscar" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="12%" class="FondoTabla">Folio <input type="hidden" name="ands" value="<?=$losands?>"></td>
      <td width="18%" class="FondoTabla">Reparto Ead </td>
      <td width="19%" class="FondoTabla">Fecha</td>
      <td width="51%" class="FondoTabla">Estado</td>
    </tr>
    <tr>
      <td colspan="4" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
			$get =mysql_query("select folio,idreparto,date_format(fecha,'%d/%m/%Y') as fecha,
			if(cerro=0,'NO CERRADO','CERRADO')as estado from devolucionmercancia
			WHERE sucursal = $_SESSION[IDSUCURSAL] limit ".$st.','.$pp,$link);
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="60" class="Tablas" >
         <div align="center"><span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">
           <?=$row[0];?>
         </span></div></td>
            <td width="87" class="Tablas"><?=$row[1]; ?>
              <div align="center"></div>
           </td>
            <td width="96"><?=$row[2]; ?>
              <div align="center"></div></td>
            <td width="253"><?=$row[3]; ?>
              <div align="center"></div></td>
          </tr>	
		<?	}
		
		?>

      </table></td>
    </tr>
    <tr>
      <td colspan="4" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFolioDevolucion.php?funcion=$_GET[funcion]&tipo=".$tipo."&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>