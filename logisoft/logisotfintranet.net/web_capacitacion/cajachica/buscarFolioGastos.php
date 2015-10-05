<? session_start();
	
	require_once("../Conectar.php");
	
	$link = Conectarse("webpmm");
	function cerrarcon($resultado, $conexion)
	{
		mysql_free_result($resultado);
		mysql_close($conexion);
	}
	$tipopago	=$_GET['tipopago'];
	$idsucursal = $_GET['idsucursal'];

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
      <td width="7%" class="FondoTabla">Folio</td>
      <td width="85%" class="FondoTabla">Fecha
      <input name="sucursalorigen" type="hidden" id="sucursalorigen" value="<?=$sucursalorigen ?>"></td>
    </tr>
    <tr>
      <td colspan="2" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
		  	$s = "SELECT IF(ISNULL(MAX(folio) + 1), 1, MAX(folio) + 1) FROM foliosgastoscajachica WHERE keytipopagoindex = '".$tipopago."' AND keysucursal = '".$idsucursal."'";
			$sq = mysql_query($s) or die($s);
			mysql_num_rows($sq) > 0 ? $foliomayor = mysql_result($sq, 0) : $foliomayor = 0;
			
			$folioaux = $foliomayor - 1;
			$s = "SELECT generada FROM foliosgastoscajachica WHERE keytipopagoindex = '".$tipopago."' AND folio = '".$folioaux."'";
			$sq = mysql_query($s) or die($s);
			mysql_num_rows($sq) > 0 ? $generada = mysql_result($sq, 0) : $generada = '';
			
			$cad = 'SELECT folio, DATE_FORMAT(fechagenerada,"%d-%m-%Y"), generada
					FROM foliosgastoscajachica WHERE  keytipopagoindex =  "'.$tipopago.'" AND keysucursal = "'.$idsucursal.'"';
			$get =@mysql_query($cad,$link);			

			
			$numcolumns = mysql_num_fields($get);
			$separador = '#.-';

			while($row=@mysql_fetch_array($get))
			{
			?>
				<tr >
       <td width="10%" class="Tablas" >
<span onClick="window.parent.ObtenerFolioGastos('<?=$row[0]; ?>', '<?=$row[2]; ?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>
            <td width="79%" class="Tablas"><?=$row[1]; ?></td>
            <td width="19px"></td>
          </tr>	
		<?	}
		
			if($generada == 'S')
			{ ?>
				<tr >
       <td width="10%" class="Tablas" >
<span onClick="window.parent.ObtenerFolioGastos('<?=$foliomayor; ?>', 'N');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $foliomayor;?></span></td>
            <td width="79%" class="Tablas"><?='00-00-0000'; ?></td>
            <td width="19px"></td>
          </tr>
          <?
			}		
		
		?>

      </table>
          <p class="Tablas">&nbsp;</p>
      </td>
    </tr>
    <tr>
      <td colspan="2"></td>
    </tr>
  </table> 
</form>
</body>
</html>
<? //} ?>