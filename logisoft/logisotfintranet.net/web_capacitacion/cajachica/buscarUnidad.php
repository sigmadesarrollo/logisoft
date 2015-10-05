<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	
	$link = Conectarse("webpmm");//********
	function cerrarcon($resultado, $conexion)
	{
		mysql_free_result($resultado);
		mysql_close($conexion);
	}
	$sucursal	=$_GET['IDSUCURSAL'];
	
	if($_GET[tiporuta]!=""){
		$and = " AND tiporuta = '$_GET[tiporuta]' AND sucursal = '$_GET[sucursal]' ";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
var sucursalorigen = 0;
function ObtenerSucursalOrigen(){
	if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
	sucursalorigen = '<?=$_SESSION[IDSUCURSAL]?>';
	document.all.sucursalorigen.value = sucursalorigen;
	}
}
</script>

<script src="select.js"></script>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form name="buscar" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="7%" class="FondoTabla">Folio</td>
      <td width="85%" class="FondoTabla">N&uacute;mero econ&oacute;mico
      <input name="sucursalorigen" type="hidden" id="sucursalorigen" value="<?=$sucursalorigen ?>"></td>
    </tr>
    <tr>
      <td colspan="2"><div id="txtHint" style="width:510px; height:300px; overflow: scroll;"><table width="96%" border="0" align="center">
          <?
		  /*switch ($tipo) {				
			case "evaluacion":*/
			$cad = 'select id, numeroeconomico 
			from catalogounidad 
			where fueradeservicio=0 '.$and.'
			order by numeroeconomico';
		$get =@mysql_query($cad,$link);			
				/*break;			
		}	*/
			
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="10%" class="Tablas" >
<span onClick="window.parent.ObtenerUnidad('<?=$row[0];?>', '<?=$row[1];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>
            <td width="79%" class="Tablas"><?=$row[1]; ?></td>
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