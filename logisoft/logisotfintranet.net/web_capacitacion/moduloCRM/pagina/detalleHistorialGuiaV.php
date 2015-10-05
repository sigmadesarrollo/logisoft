<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT gv.id AS guia, cs.descripcion AS origen, cd.descripcion AS destino,
	IF(gv.condicionpago=0,'CONTADO','CREDITO') AS condpago,
	IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega, gv.estado,
	IFNULL(DATE_FORMAT(gv.fechaentrega,'%d/%m/%Y'),'') AS fechaentrega,
	IFNULL(gv.recibio,'') AS recibio FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
	INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
	WHERE gv.id='".$_GET[guia]."'";	
	$r = mysql_query($s,$l) or die($s);
	$registros = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$f->condpago = cambio_texto($f->condpago);
			$f->tipoentrega = cambio_texto($f->tipoentrega);
			$f->estado = cambio_texto($f->estado);
			$f->recibio = cambio_texto($f->recibio);
			$registros[] = $f;
		}
	$datos = str_replace('null','""',json_encode($registros));
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();	
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"GUIA", medida:80, alineacion:"left", datos:"guia"},
			{nombre:"ORIGEN", medida:120, alineacion:"left", datos:"origen"},
			{nombre:"DESTINO", medida:120, alineacion:"left", datos:"destino"},
			{nombre:"COND. PAGO", medida:60, alineacion:"center", datos:"condpago"},
			{nombre:"T. ENTREGA", medida:60, alineacion:"center", datos:"tipoentrega"},
			{nombre:"ESTADO", medida:120, alineacion:"left", datos:"estado"},
			{nombre:"F. ENTREGA", medida:60, alineacion:"center", datos:"fechaentrega"},
			{nombre:"RECIBIO", medida:120, alineacion:"left", datos:"recibio"}
		],
		filasInicial:5,
		alto:100,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"verRecoleccion()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		mostrarDetalle('<?=$datos ?>');
	}
	
	function mostrarDetalle(datos){
		var obj = eval(convertirValoresJson(datos));
		tabla1.setJsonData(obj);
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="550" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">    
    <tr>
      <td><div align="left" style="overflow:auto;height:120px; width:550px">
	  <table width="500" border="0" cellspacing="0" cellpadding="0" id="detalle">        
      </table>
	  </div>
	  </td>
    </tr>
  </table>
</form>

</body>
</html>
