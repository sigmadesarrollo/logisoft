<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	
	$link = Conectarse("webpmm");
	
	function cerrarcon($resultado, $conexion)
	{
		mysql_free_result($resultado);
		mysql_close($conexion);
	}
	$sucursal	=$_GET['sucursal'];

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="select.js"></script>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="buscar" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="7%" class="FondoTabla">ID</td>
      <td width="25%" class="FondoTabla">Prefijo</td>
      <td width="68%" class="FondoTabla">Descripci&oacute;n</td>
    </tr>
    <tr>
      <td colspan="3" class="Tablas"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
			$cad = 'select id, prefijo, descripcion from catalogosucursal limit '.$st.',"'.$pp;
			$get =@mysql_query($cad,$link);
			
			while($row=@mysql_fetch_array($get)){
		 ?>  
				<tr >
       <td width="10%" class="Tablas" >
<span onClick="window.parent.ObtenerSucursal('<?=$row[0];?>', '<?=$row[1];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>
            <td width="25%" class="Tablas"><?=$row[1]; ?></td>
            <td width="65%"><?=$row[2]; ?></td>
          </tr>	
		<?	}
		
		?>

      </table></div></td>
    </tr>
  </table> 
</form>
</body>
</html>
<? //} ?>