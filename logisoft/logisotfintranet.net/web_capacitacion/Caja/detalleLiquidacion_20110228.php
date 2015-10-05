<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[tipo]=="ead"){		
		$s = "SELECT led.guia, g.origen, g.destino, IFNULL(fp.efectivo,0) AS efectivo, IFNULL(fp.tarjeta,0) AS tarjeta, 
		IFNULL(fp.cheque,0) AS cheque, IFNULL(fp.transferencia,0) AS transferencia, 
		IFNULL(fp.notacredito,0) AS notacredito FROM liquidacionead le
		INNER JOIN liquidacion_detalleead led ON le.id = led.idliquidacion
		INNER JOIN (
		SELECT gv.id AS guia, ori.prefijo AS origen, des.prefijo AS destino FROM guiasventanilla gv
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON gv.idsucursalorigen = des.id
		UNION
		SELECT ge.id AS guia, ori.prefijo AS origen, des.prefijo AS destino FROM guiasempresariales ge
		INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON ge.idsucursalorigen = des.id) AS g ON g.guia = led.guia
		INNER JOIN formapago fp ON led.guia = fp.guia
		WHERE fp.procedencia = 'M' 
		".((!empty($_GET[cambiafecha]))? " AND fp.fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" AND fp.fecha = CURRENT_DATE")."
		AND fp.usuario=".$_GET[empleado]." AND le.sucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY led.guia";
		
	}else if($_GET[tipo]=="cobranza"){
		$s = "SELECT lcd.guia, g.origen, g.destino, IFNULL(fp.efectivo,0) AS efectivo,
		IFNULL(fp.tarjeta,0) AS tarjeta, IFNULL(fp.transferencia,0) AS transferencia,
		IFNULL(fp.notacredito,0) AS notacredito, IFNULL(fp.cheque,0) AS cheque
		FROM relacioncobranza rc
		INNER JOIN relacioncobranzadetalle rcd ON rc.folio = rcd.relacioncobranza AND rc.sucursal = rcd.sucursal AND rc.sucursal = ".$_SESSION[IDSUCURSAL]."
		INNER JOIN(
		SELECT gv.id AS guia, ori.prefijo AS origen, d.prefijo AS destino 
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		UNION
		SELECT ge.id AS guia, ori.prefijo AS origen, d.prefijo AS destino FROM guiasempresariales ge
		INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		)AS g ON rcd.guia = g.guia
		INNER JOIN liquidacioncobranza lc ON rc.folio = lc.foliocobranza AND lc.sucursal = ".$_SESSION[IDSUCURSAL]."
		INNER JOIN liquidacioncobranzadetalle lcd ON lc.id = lcd.folioliquidacion
		INNER JOIN formapago fp ON lcd.factura = fp.guia
		WHERE fp.procedencia='C' AND fp.usuario=".$_GET[empleado]." AND lc.estado = 'LIQUIDADO' AND lc.idusuario = ".$_GET[empleado]."
		".((!empty($_GET[cambiafecha]))? " AND fp.fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" AND fp.fecha = CURRENT_DATE")."
		GROUP BY lcd.guia";
		
	}else if($_GET[tipo]=="ocurre"){
		$s = "SELECT e.nguia AS guia, g.origen, g.destino, IFNULL(fp.efectivo,0) AS efectivo, IFNULL(fp.tarjeta,0) AS tarjeta, 
		IFNULL(fp.cheque,0) AS cheque, IFNULL(fp.transferencia,0) AS transferencia, 
		IFNULL(fp.notacredito,0) AS notacredito FROM entregasocurre e
		INNER JOIN (
		SELECT gv.id AS guia, ori.prefijo AS origen, des.prefijo AS destino FROM guiasventanilla gv
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON gv.idsucursalorigen = des.id
		UNION
		SELECT ge.id AS guia, ori.prefijo AS origen, des.prefijo AS destino FROM guiasempresariales ge
		INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON ge.idsucursalorigen = des.id) AS g ON e.nguia = g.guia
		INNER JOIN formapago fp ON e.folio = fp.guia
		WHERE fp.procedencia = 'O' ".((!empty($_GET[cambiafecha]))? " AND fp.fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" AND fp.fecha = CURRENT_DATE")."
		AND fp.usuario=".$_GET[empleado]." AND e.idsucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY e.nguia";
		
	}else if($_GET[tipo]=="abono"){
		$s = "SELECT g.guia, g.origen, g.destino, IFNULL(fp.efectivo,0) AS efectivo, IFNULL(fp.tarjeta,0) AS tarjeta, 
		IFNULL(fp.cheque,0) AS cheque, IFNULL(fp.transferencia,0) AS transferencia, 
		IFNULL(fp.notacredito,0) AS notacredito FROM abonodecliente a
		INNER JOIN abonodecliente_facturas f ON a.id = f.folioabono AND f.sucursal = ".$_SESSION[IDSUCURSAL]."
		INNER JOIN (
		SELECT gv.factura, gv.id AS guia, ori.prefijo AS origen, des.prefijo AS destino FROM guiasventanilla gv
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON gv.idsucursalorigen = des.id
		UNION
		SELECT ge.factura, ge.id AS guia, ori.prefijo AS origen, des.prefijo AS destino FROM guiasempresariales ge
		INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON ge.idsucursalorigen = des.id) AS g ON f.factura = g.factura
		INNER JOIN formapago fp ON a.folio = fp.guia
		WHERE fp.procedencia = 'A' ".((!empty($_GET[cambiafecha]))? " AND fp.fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" AND fp.fecha = CURRENT_DATE")."
		AND fp.usuario=".$_GET[empleado]." AND a.idsucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY g.guia";
	}

	$r = mysql_query($s,$l) or die($s);
	$registros = array();
	$detalle = "";
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$registros[] = $f;
		}
		$detalle = str_replace('null','""',json_encode($registros));
	}else{
		$detalle = "no encontro";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ajax.js"></script>
<script>
	var tabla1 	= new ClaseTabla();
	var u 		= document.all;
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"GUIA", medida:80, alineacion:"left", datos:"guia"},
			{nombre:"ORIGEN", medida:80, alineacion:"center", datos:"origen"},
			{nombre:"DESTINO", medida:80, alineacion:"center", datos:"destino"},
			{nombre:"EFECTIVO", medida:90, tipo:"moneda", alineacion:"right", datos:"efectivo"},
			{nombre:"TARJETA", medida:90, tipo:"moneda", alineacion:"right", datos:"tarjeta"},
			{nombre:"TRANSFERENCIA", medida:90, tipo:"moneda", alineacion:"right", datos:"transferencia"},
			{nombre:"CHEQUE", medida:90, tipo:"moneda", alineacion:"right",  datos:"cheque"},
			{nombre:"NOTA CREDITO", medida:90, tipo:"moneda", alineacion:"right",  datos:"notacredito"}			
		],
		filasInicial:30,
		alto:300,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	window.onload = function(){
		tabla1.create();
		mostrarDetalle('<?=$detalle ?>');
	}
	
	function mostrarDetalle(datos){
		if(datos.indexOf("no encontro")<0){
			var efectivo = 0; var tarjeta = 0; var transferencia = 0; var cheque = 0; var notacredito = 0;				
			var obj = eval(convertirValoresJson(datos));
			var ob = new Object();
			for(var i=0;i<obj.length;i++){
				ob.guia 		= obj[i].guia;
				ob.origen 		= obj[i].origen;
				ob.destino 		= obj[i].destino;
				ob.efectivo 	= obj[i].efectivo;
				ob.tarjeta 		= obj[i].tarjeta;
				ob.transferencia= obj[i].transferencia;
				ob.cheque 		= obj[i].cheque;
				ob.notacredito	= obj[i].notacredito;
				tabla1.add(ob);
				efectivo 		= parseFloat(obj[i].efectivo) + efectivo;
				tarjeta 		= parseFloat(obj[i].tarjeta) + tarjeta;
				transferencia 	= parseFloat(obj[i].transferencia) + transferencia;
				cheque 			= parseFloat(obj[i].cheque) + cheque;
				notacredito		= parseFloat(obj[i].notacredito) + notacredito;
			}
			u.efectivo.value 		= efectivo;
			u.efectivo.value 		= "$ "+numcredvar(u.efectivo.value);
			u.tarjeta.value 		= tarjeta;
			u.tarjeta.value 		= "$ "+numcredvar(u.tarjeta.value);
			u.transferencia.value 	= transferencia;
			u.transferencia.value 	= "$ "+numcredvar(u.transferencia.value);
			u.cheque.value 			= cheque;
			u.cheque.value 			= "$ "+numcredvar(u.cheque.value);
			u.notacredito.value 	= notacredito;
			u.notacredito.value 	= "$ "+numcredvar(u.notacredito.value);
		}
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="611" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td colspan="6" class="FondoTabla">CIERRE CAJA DETALLE </td>
    </tr>
    <tr>
      <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table id="detalle" width="610" border="0" align="center" cellpadding="0" cellspacing="0">
      </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
        <td>T. Efectivo:</td>
        <td>T. Tarjeta:</td>
        <td>T. Transferencia:</td>
        <td>T. Cheque:</td>
        <td>T. Nota Credito: </td>
      </tr>
      <tr>
        <td width="73">&nbsp;</td>
        <td width="64"><input class="Tablas" readonly="" name="efectivo" type="text" id="efectivo" style="width:100px; text-align:right"/></td>
        <td width="104"><input class="Tablas" readonly="" name="tarjeta" type="text" id="tarjeta" style="width:100px; text-align:right"/></td>
        <td width="100"><input class="Tablas" readonly="" name="transferencia" type="text" id="transferencia" style="width:100px; text-align:right"/></td>
        <td width="90"><input class="Tablas" readonly="" name="cheque" type="text" id="cheque" style="width:100px; text-align:right" /></td>
        <td width="59"><input class="Tablas" readonly="" name="notacredito" type="text" id="notacredito" style="width:100px; text-align:right" /></td>
      </tr>
    </table></td>
  </tr>
</table>
</td>
    </tr>
  </table>
  </form>
</body>
</html>
