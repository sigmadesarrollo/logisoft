<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT guia, accion, tipo FROM historial_cancelacionysustitucion
	WHERE fecha=CURDATE() 
	AND((tipo='LOCAL') OR (tipo='FORANEA' AND accion='SUSTITUCION REALIZADA'))
	AND sucursal=".$_SESSION[IDSUCURSAL];
	$c = mysql_query($s,$l) or die($s);
		while($cc = mysql_fetch_object($c)){
			if($cc->tipo == "LOCAL"){
				$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
				o.descripcion AS origen,
				CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
				d.descripcion AS destino,
				CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS remitente,
				IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete,gv.total,
				DATE_FORMAT(can.fecha,'%d/%m/%Y') AS fechacancelacion,
				cm.descripcion AS motivo, 
				CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS usuario
				FROM guiasventanilla gv
				INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
				INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
				INNER JOIN catalogocliente re ON gv.idremitente = re.id
				INNER JOIN catalogocliente des ON gv.idremitente = des.id
				INNER JOIN cancelacionguiasventanilla can ON gv.id = can.guia
				INNER JOIN catalogomotivos cm ON can.motivocancelacion = cm.id
				INNER JOIN catalogoempleado ce ON can.usuario = ce.id
				WHERE gv.id='".$cc->guia."' AND gv.idsucursalorigen=".$_SESSION[IDSUCURSAL];
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				
			}else if($cc->tipo == "FORANEA"){
				$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
				o.descripcion AS origen,
				CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
				d.descripcion AS destino,
				CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS remitente,
				IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, gv.total,
				DATE_FORMAT(h.fecha,'%d/%m/%Y') AS fechacancelacion,
				cs.motivocancelacion, 
				CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS usuario
				FROM historial_cancelacionysustitucion h
				INNER JOIN guiasventanilla gv ON h.guia = gv.id
				INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
				INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
				INNER JOIN catalogocliente re ON gv.idremitente = re.id
				INNER JOIN catalogocliente des ON gv.idremitente = des.id
				INNER JOIN guiasventanilla_cs cs ON h.guia = cs.folioguia
				INNER JOIN catalogoempleado ce ON cs.idusuario = ce.id
				WHERE h.guia='".$cc->guia."' AND h.sucursal=".$_SESSION[IDSUCURSAL];
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				
				
			}
			
				$cc->fechaemision	= $f->fecha;
				$cc->origen 		= cambio_texto($f->origen);
				$cc->remitente 		= ($f->remitente);
				$cc->destinatario 	= ($f->destinatario);
				$cc->destino 		= ($f->destino);
				$cc->remitente 		= ($f->remitente);
				$cc->tipoflete 		= ($f->tipoflete);
				$cc->importe 			= $f->total;
				$cc->fechacancelacion 	= $f->fechacancelacion;
				$cc->motivocancelacion 	= $f->motivocancelacion;
				$cc->usuario 			= $f->usuario;
				$registros[] 			= $cc;
				
		}
	
	$json = json_encode($registros);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();	
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"# GUIA", medida:50, alineacion:"center",  datos:"guia"},
			{nombre:"FECHA EMISION", medida:80, alineacion:"center",  datos:"fechaemision"},
			{nombre:"ORIGEN", medida:70, alineacion:"center", datos:"origen"},
			{nombre:"REMITENTE", medida:70, alineacion:"center", datos:"remitente"},
			{nombre:"DESTINO", medida:60, alineacion:"center", datos:"destino"},
			{nombre:"DESTINATARIO", medida:50, alineacion:"center", datos:"destinatario"},
			{nombre:"TIPO FLETE", medida:60, alineacion:"center", datos:"tipoflete"},
			{nombre:"IMPORTE", medida:60, alineacion:"center", datos:"importe"},
			{nombre:"FECHA CANCELACION", medida:85, alineacion:"center", datos:"fechacancelacion"},
			{nombre:"MOTIVO CANCELACION", medida:85, alineacion:"center", datos:"motivocancelacion"},
			{nombre:"USUARIO CANCELACION", medida:85, alineacion:"center", datos:"usuario"},
			{nombre:"CANCELACION AUTORIZADA", medida:85, alineacion:"center", datos:"cancelacionautorizada"}			
		],
		filasInicial:15,
		alto:150,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtener();
	}
	
	function obtener(){
		var obj = <?=$json?>;
		if(obj != null)
			tabla1.setJsonData(obj);
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">       
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
