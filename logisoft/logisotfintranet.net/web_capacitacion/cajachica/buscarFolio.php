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
	$tipogasto	=$_GET['tipogasto'];
	$tipogasto > 0 ? ' AND tipogastoindex = "'.$tipogasto.'"' : '';
	$sucursal	=$_GET['sucursal'];
	
	/*switch ($tipo) {
		case "evaluacion":
			$get=@mysql_query('select count(*) from evaluacionmercancia where sucursal="'.$sucursal.'" AND estado<>"ENGUIA"');			
			break;
	}
	$total =@mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;*/
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
      <td colspan="2"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;"><table width="100%" border="0" align="center">
          <?
		  /*switch ($tipo) {				
			case "evaluacion":*/
			$cad = 'select folio, keysucursal, prefijosucursal, DATE_FORMAT(fecha,"%d/%m/%Y") as fecha, 
			tipogastoindex, tipogastodesc,
			tipopagoindex, tipopagodesc, keyunidad, unidadnumeconomico, factura, DATE_FORMAT(fechafacturavale,"%d/%m/%Y"),
			keyproveedor, nombreproveedor, keyconcepto, descripcionconcepto, subtotal, iva, total,
			descripcion, autorizado, folioautorizacion, motivonoautorizacion, sustituir 
			from capturagastoscajachica 
			where keysucursal="'.$sucursal.'" AND tipogastoindex = "'.$tipogasto.'"';
			//echo $cad;
		$get =@mysql_query($cad,$link);			
				/*break;			
		}	*/
			
			$numcolumns = mysql_num_fields($get);
			$separador = '#.-';
			while($row=@mysql_fetch_array($get)){
				$cadenasend = "";
				for($i=0; $i <$numcolumns; $i++)
				{
					$cadenasend .= $row[$i].$separador;
				}
			?>
				<tr >
       <td width="10%" class="Tablas" >
<span onClick="window.parent.ObtenerFolio('<?=$row[0] ?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>
            <td width="79%" class="Tablas"><?=$row[3]; ?></td>
            <td width="19px"></td>
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