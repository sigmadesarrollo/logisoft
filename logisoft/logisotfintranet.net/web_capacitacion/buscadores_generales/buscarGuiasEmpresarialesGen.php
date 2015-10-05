<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if (isset($_SESSION['gvalidar'])!=100){
echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{*/
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	$get=@mysql_query('select count(*) from guiasempresariales where estado = "'.$_GET[estado].'"');
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
      <td width="27%" class="FondoTabla">Folio</td>
      <td width="73%" class="FondoTabla">Fecha</td>
    </tr>
    <tr>
      <td height="300px" colspan="2" valign="top" class="Tablas"><table width="100%" border="0" align="center" class="Tablas">
          <?
		$get =@mysql_query('select id, date_format(fecha, "%d/%m/%Y") as fecha from guiasempresariales where estado = "'.$_GET[estado].'" limit '.$st.','.$pp,$link);		
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="128" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[id];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[id];?></span></td>
            <td width="303" class="Tablas"><?=$row[fecha]; ?></td>
            <td width="51"></td>
          </tr>	
		<?	}
		
		?>

      </table></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarGuiasEmpresarialesGen.php?funcion=$_GET[funcion]&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>
<? //} ?>