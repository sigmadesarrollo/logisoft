<?	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reportecierrediadetallado.xls");
	//header("Pragma: no-cache");
	header("Expires: 0"); 
	
	require_once("../Conectar.php");
	$l = Conectarse('webpmm');
	$s = "SELECT descripcion FROM catalogosucursal WHERE id=".$_GET[sucursal];
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	$sucursalNombre = utf8_decode($f->descripcion);
	
	$s = "SELECT gv.id as guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') as fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) as empleado
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id	
	LEFT JOIN catalogoempleado ce ON gv.idusuario = ce.id";
	
	//1) GUIAS CONTADO
	$criterioguiascontado = " INNER JOIN catalogocliente cc ON gv.idremitente = cc.id 
	LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
	LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA' 
	WHERE gv.id like '%A' AND gv.tipoflete = 0 AND gv.condicionpago = 0 AND 
	IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = ".$_GET[sucursal]." ,IF(gv.idsucursalorigen=".$_GET[sucursal]." AND SUBSTRING(gv.id,1,3)=cs.idsucursal,gv.idsucursalorigen = ".$_GET[sucursal].",hc2.sucursal = ".$_GET[sucursal]."))  
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND (gv.estado<>'CANCELADO' OR (gv.estado='CANCELADO' AND NOT ISNULL(hc.id))) ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiascontado,"GUIAS CONTADO","SI",$l,$sucursalNombre);
	
	//2) GUIAS CREDITO
	$criterioguiascredito = " INNER JOIN catalogocliente cc ON gv.idremitente = cc.id 
	LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
	LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA' 
	WHERE gv.id like '%A' AND  gv.tipoflete = 0 AND gv.condicionpago = 1 AND 
	IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = ".$_GET[sucursal]." ,IF(gv.idsucursalorigen=".$_GET[sucursal]." AND SUBSTRING(gv.id,1,3)=cs.idsucursal,gv.idsucursalorigen = ".$_GET[sucursal].",hc2.sucursal = ".$_GET[sucursal]."))  
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND (gv.estado<>'CANCELADO' OR (gv.estado='CANCELADO' AND NOT ISNULL(hc.id))) ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiascredito,"GUIAS CREDITO","SI",$l,$sucursalNombre);
	
	//3) GUIAS POR COBRAR CONTADO
	$criterioguiascobrarcontado = " INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id 
	LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
	LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA' 
	WHERE gv.id like '%A' AND  gv.tipoflete = 1 AND gv.condicionpago = 0 AND 
	IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = ".$_GET[sucursal]." ,IF(gv.idsucursalorigen=".$_GET[sucursal]." AND SUBSTRING(gv.id,1,3)=cs.idsucursal,gv.idsucursalorigen = ".$_GET[sucursal].",hc2.sucursal = ".$_GET[sucursal]."))  
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND (gv.estado<>'CANCELADO' OR (gv.estado='CANCELADO' AND NOT ISNULL(hc.id))) ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiascobrarcontado,"GUIAS POR COBRAR CONTADO","SI",$l,$sucursalNombre);
	
	//4) GUIAS POR COBRAR CREDITO
	$criterioguiascobrarcredito = " INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id 
	LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
	LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA' 
	WHERE gv.id like '%A' AND  gv.tipoflete = 1 AND gv.condicionpago = 1 AND 
	IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = ".$_GET[sucursal]." ,IF(gv.idsucursalorigen=".$_GET[sucursal]." AND SUBSTRING(gv.id,1,3)=cs.idsucursal,gv.idsucursalorigen = ".$_GET[sucursal].",hc2.sucursal = ".$_GET[sucursal]."))  
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND (gv.estado<>'CANCELADO' OR (gv.estado='CANCELADO' AND NOT ISNULL(hc.id))) ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiascobrarcredito,"GUIAS POR COBRAR CREDITO","SI",$l,$sucursalNombre);
	
	//5) FACTURACION DE VENTAS GUIAS PREPAGADAS CONTADO
	$criterioprepagadascontado = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalle fd ON f.folio=fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE fd.tipoguia='PREPAGADA' AND
	date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND f.credito='NO' AND f.idsucursal = ".$_GET[sucursal]." 
	GROUP BY f.folio ORDER BY f.idusuario";	
	obtenerDatos($criterioprepagadascontado,"FACTURACION DE VENTAS GUIAS PREPAGADAS CONTADO","SI",$l,$sucursalNombre);
	
	//6) FACTURACION DE VENTAS GUIAS PREPAGADAS CREDITO
	$criterioprepagadascredito = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalle fd ON f.folio=fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE fd.tipoguia='PREPAGADA' AND 
	date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND f.credito='SI' AND f.idsucursal = ".$_GET[sucursal]."
	GROUP BY f.folio ORDER BY f.idusuario";		
	obtenerDatos($criterioprepagadascredito,"FACTURACION DE VENTAS GUIAS PREPAGADAS CREDITO","SI",$l,$sucursalNombre);
	
	//7) FACTURACION DE VENTAS GUIAS CONSIGNACION CONTADO
	$criterioconsignacioncontado = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, IFNULL(SUM(fd.total),0) AS importe,
	f.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalle fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.tipoguia='empresarial' AND f.credito = 'NO' AND f.tipofactura='NORMAL'
	AND fd.tipoguia='CONSIGNACION' AND f.idsucursal = ".$_GET[sucursal]."
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criterioconsignacioncontado,"FACTURACION DE VENTAS GUIAS CONSIGNACION CONTADO","SI",$l,$sucursalNombre);	
	
	//8) FACTURACION DE VENTAS GUIAS CONSIGNACION CREDITO
	$criterioconsignacioncredito = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, IFNULL(SUM(fd.total),0) AS importe,
	f.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalle fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.tipoguia='empresarial' AND f.credito = 'SI' AND f.tipofactura='NORMAL' AND fd.tipoguia='CONSIGNACION' 
	AND f.idsucursal = ".$_GET[sucursal]."
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criterioconsignacioncredito,"FACTURACION DE VENTAS GUIAS CONSIGNACION CREDITO","SI",$l,$sucursalNombre);
	
	//09)FACTURACION DE SOBREPESO CONTADO
	$criteriofacturacionsobrepesocontado = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalleguias fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.credito = 'NO' AND f.tipofactura='NORMAL' AND f.idsucursal = ".$_GET[sucursal]." 
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND fd.concepto LIKE '%EXCEDENTE%'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionsobrepesocontado,"FACTURACION DE SOBREPESO CONTADO","SI",$l,$sucursalNombre);
	
	//10)FACTURACION DE SOBREPESO CREDITO
	$criteriofacturacionsobrepesocredito = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalleguias fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.credito = 'SI' AND f.tipofactura='NORMAL' AND f.idsucursal = ".$_GET[sucursal]."
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND fd.concepto LIKE '%EXCEDENTE%'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionsobrepesocredito,"FACTURACION DE SOBREPESO CREDITO","SI",$l,$sucursalNombre);
	
	//11)FACTURACION DE VALOR DECLARADO CONTADO
	$criteriofacturacionvalordeclaradocontado = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalleguias fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.credito = 'NO' AND f.tipofactura='NORMAL' AND f.idsucursal = ".$_GET[sucursal]." 
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND fd.concepto LIKE '%VALOR DECLARADO%'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionvalordeclaradocontado,"FACTURACION DE VALOR DECLARADO CONTADO","SI",$l,$sucursalNombre);
	
	//12)FACTURACION DE VALOR DECLARADO CREDITO
	$criteriofacturacionvalordeclaradocredito = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalleguias fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.credito = 'SI' AND f.tipofactura='NORMAL' AND f.idsucursal = ".$_GET[sucursal]." 
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND fd.concepto LIKE '%VALOR DECLARADO%'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionvalordeclaradocredito,"FACTURACION DE VALOR DECLARADO CREDITO","SI",$l,$sucursalNombre);
	
	//13)FACTURACION DE OTROS CONCEPTOS CONTADO
	$criteriofacturacionotrosconceptoscontado = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, f.otrosmontofacturar AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado FROM facturacion f
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.idsucursal = ".$_GET[sucursal]." AND f.tipofactura='NORMAL' AND f.credito = 'NO' AND f.otrosmontofacturar > 0
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionotrosconceptoscontado,"FACTURACION DE OTROS CONCEPTOS CONTADO","SI",$l,$sucursalNombre);
	
	//13)FACTURACION DE OTROS CONCEPTOS CREDITO
	$criteriofacturacionotrosconceptoscredito = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, f.otrosmontofacturar AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado FROM facturacion f
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.idsucursal = ".$_GET[sucursal]." AND f.tipofactura='NORMAL' AND f.credito = 'SI' AND f.otrosmontofacturar > 0
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionotrosconceptoscredito,"FACTURACION DE OTROS CONCEPTOS CREDITO","SI",$l,$sucursalNombre);
	
	//6) CORREO INTERNO
	$criterioguiascorreointerno = "SELECT c.guia, DATE_FORMAT(fechacorreo,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ',de.nombre,de.apellidopaterno,de.apellidomaterno) AS destinatario,
	cs.prefijo AS destino, 0 AS importe, c.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM correointerno c
	LEFT JOIN catalogoempleado de ON c.destintario = de.id
	INNER JOIN catalogosucursal cs ON c.sucdestino = cs.id
	LEFT JOIN catalogoempleado ce ON c.idusuario = ce.id
	WHERE c.sucorigen = ".$_GET[sucursal]."
	AND c.fechacorreo BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND c.estado='GUARDADO' ORDER BY c.idusuario";	
	obtenerDatos($criterioguiascorreointerno,"CORREO INTERNO","SI",$l,$sucursalNombre);	
	
	//12) CORREO INTERNO FORANEO RECIBIDO
	$criterioguiasforaneacorreointerno = "SELECT c.guia, DATE_FORMAT(fechacorreo,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ',de.nombre,de.apellidopaterno,de.apellidomaterno) AS destinatario,
	cs.prefijo AS destino, 0 AS importe, c.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM correointerno c
	LEFT JOIN catalogoempleado de ON c.destintario = de.id
	INNER JOIN catalogosucursal cs ON c.sucdestino = cs.id
	LEFT JOIN catalogoempleado ce ON c.idusuario = ce.id
	WHERE c.sucdestino = ".$_GET[sucursal]." 
	AND c.fechacorreo BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND c.estado='GUARDADO' order by ce.id";
	obtenerDatos($criterioguiasforaneacorreointerno,"CORREO INTERNO FORANEO RECIBIDO","",$l,$sucursalNombre);
	
	//13) INGRESOS POR COBRANZA DE GUIAS A CREDITO
	$criterioingresocobranzacredito = "SELECT * FROM(
	SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(a.fecharegistro,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente, cs.prefijo AS destino,
	IFNULL(f.total + f.sobmontoafacturar + f.otrosmontofacturar,0) AS importe,
	a.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM abonodecliente a
	INNER JOIN abonodecliente_facturas af ON a.id = af.folioabono and a.idsucursal = af.sucursal
	INNER JOIN facturacion f ON af.factura = f.folio
	INNER JOIN catalogocliente cc ON a.idcliente = cc.id
	INNER JOIN catalogosucursal cs ON a.idsucursal = cs.id
	LEFT JOIN catalogoempleado ce ON a.idusuario = ce.id
	WHERE a.fecharegistro BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND a.idsucursal = ".$_GET[sucursal]." AND f.facturaestado = 'GUARDADO'
	UNION
	SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(a.fechaliquidacion,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente, cs.prefijo AS destino,
	IFNULL(f.total + f.sobmontoafacturar + f.otrosmontofacturar,0) AS importe,
	a.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM liquidacioncobranza a
	INNER JOIN liquidacioncobranza_facturas af ON a.id = af.folioliquidacion
	INNER JOIN facturacion f ON af.factura = f.folio
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	INNER JOIN catalogosucursal cs ON a.sucursal = cs.id
	LEFT JOIN catalogoempleado ce ON a.idusuario = ce.id
	WHERE a.fechaliquidacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND a.sucursal = ".$_GET[sucursal]." AND f.facturaestado = 'GUARDADO') t ORDER BY t.idusuario";	
	obtenerDatos($criterioingresocobranzacredito,"INGRESOS POR COBRANZA DE GUIAS A CREDITO","",$l,$sucursalNombre);
	
	//14) GUIAS FORANEAS COBRAR-CONTADO ENTREGADAS
	$criterioingresoguiasforaneacobrarcontadoentregadas = " SELECT gv.id as guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') as fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) as empleado,
	CONCAT('ENTREGA ',IF(gv.ocurre=1,'Ocurre','EAD'),IF(gv.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id	
	LEFT JOIN catalogoempleado ce ON gv.idusuario = ce.id
	INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id 
	LEFT JOIN seguimiento_guias sg ON gv.id = sg.guia AND sg.fecha = gv.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
	WHERE gv.tipoflete = 1 AND gv.condicionpago = 0 AND (gv.estado = 'ENTREGADA' OR gv.estado = 'POR ENTREGAR') AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	order by gv.ocurre,ce.id";
	obtenerDatos($criterioingresoguiasforaneacobrarcontadoentregadas,"GUIAS FORANEAS COBRAR-CONTADO ENTREGADAS","",$l,$sucursalNombre,"SI");
	
	//15) GUIAS FORANEAS COBRAR-CREDITO ENTREGADAS
	$criterioingresoguiasforaneacobrarcreditoentregadas = " SELECT gv.id as guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') as fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) as empleado,
	CONCAT('ENTREGA ',IF(gv.ocurre=1,'Ocurre','EAD'),IF(gv.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id	
	LEFT JOIN catalogoempleado ce ON gv.idusuario = ce.id
	INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id 
	LEFT JOIN seguimiento_guias sg ON gv.id = sg.guia AND sg.fecha = gv.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
	WHERE gv.tipoflete = 1 AND gv.condicionpago = 1 AND (gv.estado = 'ENTREGADA' OR gv.estado = 'POR ENTREGAR') AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	order by gv.ocurre,ce.id";
	obtenerDatos($criterioingresoguiasforaneacobrarcreditoentregadas,"GUIAS FORANEAS COBRAR-CREDITO ENTREGADAS","",$l,$sucursalNombre,"SI");
	
	//16) GUIAS FORANEAS PAGADAS-CONTADO ENTREGADAS
	$criterioguiasforaneapagadacontadoentregadas = "SELECT * FROM(
	SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado,
	CONCAT('ENTREGA ',IF(gv.ocurre=1,'Ocurre','EAD'),IF(gv.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
	LEFT JOIN catalogoempleado ce ON gv.idusuario = ce.id
	LEFT JOIN seguimiento_guias sg ON gv.id = sg.guia AND sg.fecha = gv.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
	WHERE gv.tipoflete = 0 AND gv.condicionpago = 0 AND (gv.estado = 'ENTREGADA' OR gv.estado = 'POR ENTREGAR') 
	AND gv.idsucursaldestino = ".$_GET[sucursal]."
	AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	UNION
	SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, ge.total AS importe, ge.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado,
	CONCAT('ENTREGA ',IF(ge.ocurre=1,'Ocurre','EAD'),IF(ge.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
	FROM guiasempresariales ge
	INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
	LEFT JOIN catalogoempleado ce ON ge.idusuario = ce.id
	LEFT JOIN seguimiento_guias sg ON ge.id = sg.guia AND sg.fecha = ge.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
	WHERE ge.tipoflete='PAGADA' AND ge.tipopago='CONTADO' AND (ge.estado = 'ENTREGADA' OR ge.estado = 'POR ENTREGAR') 
	AND ge.idsucursaldestino = ".$_GET[sucursal]."
	AND ge.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."')t
	ORDER BY ocurre desc,idusuario";	
	obtenerDatos($criterioguiasforaneapagadacontadoentregadas,"GUIAS FORANEAS PAGADAS-CONTADO ENTREGADAS","",$l,$sucursalNombre,"SI");
	
	//17) GUIAS FORANEAS PAGADAS-CREDITO ENTREGADAS
	$criterioguiasforaneapagadacreditoentregadas = "SELECT * FROM(
	SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado,
	CONCAT('ENTREGA ',IF(gv.ocurre=1,'Ocurre','EAD'),IF(gv.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
	LEFT JOIN catalogoempleado ce ON gv.idusuario = ce.id
	LEFT JOIN seguimiento_guias sg ON gv.id = sg.guia AND sg.fecha = gv.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
	WHERE gv.tipoflete = 0 AND gv.condicionpago = 1 AND (gv.estado = 'ENTREGADA' OR gv.estado = 'POR ENTREGAR') 
	AND gv.idsucursaldestino = ".$_GET[sucursal]."
	AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	UNION
	SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, ge.total AS importe, ge.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado,
	CONCAT('ENTREGA ',IF(ge.ocurre=1,'Ocurre','EAD'),IF(ge.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
	FROM guiasempresariales ge
	INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
	LEFT JOIN catalogoempleado ce ON ge.idusuario = ce.id
	LEFT JOIN seguimiento_guias sg ON ge.id = sg.guia AND sg.fecha = ge.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
	WHERE ge.tipoflete='PAGADA' AND ge.tipopago='CREDITO' AND (ge.estado = 'ENTREGADA' OR ge.estado = 'POR ENTREGAR') 
	AND ge.idsucursaldestino = ".$_GET[sucursal]."
	AND ge.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."')t
	ORDER BY ocurre desc,idusuario";
	obtenerDatos($criterioguiasforaneapagadacreditoentregadas,"GUIAS FORANEAS PAGADAS-CREDITO ENTREGADAS","",$l,$sucursalNombre,"SI");
	
	//18) RELACION DE GUIAS CANCELADAS
	/*$criterioguiasforaneapagadacreditoentregadas = " INNER JOIN catalogocliente cc ON gv.idremitente = cc.id 
	WHERE gv.estado = 'CANCELADO' AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	ORDER BY gv.idusuario";*/
	
	$relguiascance = "SELECT gv.id AS guia, DATE_FORMAT(IF(ISNULL(hc.id),gv.fecha,hc.fecha),'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado, IF(gv.ocurre=1,'Ocurre','EAD') ocurre
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id	
	INNER JOIN catalogocliente cc ON gv.idremitente = cc.id 
	LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
	LEFT JOIN catalogoempleado ce ON IF(ISNULL(hc.id),gv.idusuario,hc.usuario) = ce.id
	WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = '".$_GET[sucursal]."', hc.sucursal='".$_GET[sucursal]."')
	AND IF(ISNULL(hc.id),gv.fecha,hc.fecha) 
		BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	GROUP BY gv.id
	ORDER BY gv.idusuario";
	
	obtenerDatos($relguiascance,"RELACION DE GUIAS CANCELADAS","SI",$l,$sucursalNombre);
	
	//FACTURAS CANCELADAS
	$facturascanceladas = "SELECT f.folio, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente, cs.prefijo AS destino,
	IFNULL(f.total + f.sobmontoafacturar + f.otrosmontofacturar,0) AS importe, 
	f.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado FROM facturacion f
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id 
	WHERE f.fechacancelacion 
	BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND f.facturaestado='CANCELADO' AND f.idsucursal = ".$_GET[sucursal]." AND f.tipoguia <> 'ventanilla'";
	obtenerDatos($facturascanceladas,"FACTURAS CANCELADAS DEL DIA","SI",$l,$sucursalNombre);
	
	//RELACION DE GUIAS CON AUTORIZACION PARA CANCELAR
	$criterioguiasforaneapagadacreditoentregadas = " INNER JOIN catalogocliente cc ON gv.idremitente = cc.id 
	WHERE gv.estado = 'AUTORIZACION PARA CANCELAR' AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiasforaneapagadacreditoentregadas,"RELACION DE GUIAS CON AUTORIZACION PARA CANCELAR","SI",$l,$sucursalNombre);
	
	function obtenerDatos($criterio,$tituloreporte,$llevausuario,$l,$sucursalNombre,$entregadas=null){
		$r = mysql_query($criterio,$l) or die($s);
		if(mysql_num_rows($r)>0){
			echo '<table width="800" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">';
			echo '<tr>';
			echo '<td>';
				echo '<table width="100%" border="0" cellpadding="1" cellspacing="0">';
				echo '<tr>';
				echo '<td colspan="5" align="center" style="font-weight:bold">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td width="97" style="font-weight:bold">REPORTE:</td>';
				echo '<td colspan="5" >CORTE DIARIO DETALLADO </td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td style="font-weight:bold">SUCURSAL:</td>';
				echo '<td colspan="5" align="left">'.$sucursalNombre.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td style="font-weight:bold">FECHA:</td>';
				echo '<td colspan="5" align="left">DEL '.$_GET[fechainicio].' AL '.$_GET[fechafin].'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td height="9px" colspan="5" style="font-weight:bold">'.$tituloreporte.'</td>';
				echo '<td width="93"></td>';
				echo '<td width="93"></td>';
				echo '<td width="190"></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="8" align="center" class="cabecera"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
				echo '<tr>';
				echo '<td align="center" style="font-weight:bold">GUIA</td>';
				echo '<td align="center" style="font-weight:bold">FECHA</td>';
				echo '<td align="center" style="font-weight:bold">CLIENTE</td>';
				echo '<td align="center" style="font-weight:bold">DESTINO</td>';
				echo '<td align="center" style="font-weight:bold">IMPORTE</td>';
				echo '</tr>';
				
				$tguias = 0; $tvalor = 0;				
					while($f = mysql_fetch_array($r)){
/*						$f[0] = utf8_decode($f[0]);
						$f[2] = utf8_decode($f[2]);
						$f[3] = utf8_decode($f[3]);
						$f[6] = utf8_decode($f[6]);*/
						$tguias++;
						$tvalor = $tvalor + $f[4];			
						$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>strtoupper($f[2]),'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6],'7'=>$f[7]);
					}
					
					$usuario = "";
					$v_total = 0;
					$v_guias = 0;
					$v_empleado = "";
					$v_ocurre = "";
					#contador debe ser 45 guias
					$conreg = 0;
					for($i=0;$i<count($data);$i++){
						
						if($entregadas=="SI" && ($v_ocurre=="" || $v_ocurre!=$data[$i][7])){
							$v_ocurre=$data[$i][7];
							echo '<tr>';
							echo '<td align="center"></td>';
							echo '<td align="center"></td>';
							echo '<td align="left" style="font-weight:bold">'.$v_ocurre.'</td>';
							echo '<td align="center"></td>';
							echo '<td align="right"></td>';
							echo '</tr>';
						}
						
						if(!empty($llevausuario)){
							if($usuario!= $data[$i][5] && $usuario!=""){
								echo '<tr>';
								echo '<td align="center">-------------------</td>';
								echo '<td align="center">-------------------</td>';
								echo '<td align="left">---------------------------------------------</td>';
								echo '<td align="center">-------------------</td>';
								echo '<td align="right">---------------------------------------------</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td align="left" style="font-weight:bold">TOTAL DEL USUARIO</td>';
								echo '<td align="right"></td>';
								echo '<td align="left" style="font-weight:bold">'.$v_empleado.'</td>';
								echo '<td align="right" style="font-weight:bold">'.$v_guias.'</td>';
								echo '<td align="right" style="font-weight:bold">$'.number_format($v_total,2).'</td>';
								echo '</tr>';
								$v_total = 0;
								$v_guias = 0;
								$v_empleado = "";
								echo '<tr>';
								echo '<td align="right"></td>';
								echo '<td align="right"></td>';
								echo '<td align="right"></td>';
								echo '<td align="right"></td>';
								echo '<td align="right"></td>';
								echo '</tr>';
							}
						}				
						echo '<tr>';
						echo '<td align="center">'.$data[$i][0].'</td>';
						echo '<td align="center">'.$data[$i][1].'</td>';
						echo '<td align="left">'.$data[$i][2].'</td>';
						echo '<td align="center">'.$data[$i][3].'</td>';
						echo '<td align="right">'.$data[$i][4].'</td>';
						echo '</tr>';				
						$usuario = $data[$i][5];
						$v_total += $data[$i][4];
						$v_empleado = $data[$i][6];
						$v_guias++;
					}
						if(!empty($llevausuario)){
							echo '<tr>';
							echo '<td align="center">-------------------</td>';
							echo '<td align="center">-------------------</td>';
							echo '<td align="left">---------------------------------------------</td>';
							echo '<td align="center">-------------------</td>';
							echo '<td align="right">---------------------------------------------</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td align="left" style="font-weight:bold">TOTAL DEL USUARIO</td>';
							echo '<td align="right"></td>';
							echo '<td align="left" style="font-weight:bold">'.$v_empleado.'</td>';
							echo '<td align="right">'.$v_guias.'</td>';
							echo '<td align="right" style="font-weight:bold">$'.number_format($v_total,2).'</td>';
							echo '</tr>';
							$v_total = 0;
							$v_guias = 0;
							$v_empleado = "";
							echo '<tr>';
							echo '<td align="right"></td>';
							echo '<td align="right"></td>';
							echo '<td align="right"></td>';
							echo '<td align="right"></td>';
							echo '<td align="right"></td>';
							echo '</tr>';
						}
						
			
					echo '<tr>';
					echo '<td align="right">&nbsp;</td>';
					echo '<td align="right">&nbsp;</td>';
					echo '<td align="right" style="font-weight:bold">TOTALES:</td>';
					echo '<td align="right" style="font-weight:bold">'.$tguias.'</td>';
					echo '<td align="right" style="font-weight:bold">$'.number_format($tvalor,2).'</td>';
					echo '</tr>';
					echo '</table>';
					echo '</td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td align="right"></td>';
					echo '<td align="right"></td>';
					echo '<td align="right"></td>';
					echo '<td align="right"></td>';
					echo '<td align="right"></td>';
					echo '</tr>';
					echo '</table>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				//return true;
			}
	}


		
?>
