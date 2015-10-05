<? 	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
$sql="SELECT clavecliente,cliente,sucursal,nombresucursal,guiafactura,DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,DATE_FORMAT(fechavencimiento,'%d/%m/%Y')AS fechavencimiento,diasvencimiento,corriente,
dias1,dias2,dias3,dias4,saldo,factura,contrarecibo 
FROM (
	/*GUIAS VENTANILLA NO FACTURADAS*/
	(SELECT cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.id AS guiafactura,
	gv.fecha AS fecha,DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento, 
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',gv.fecha)<=cc.diascredito,gv.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',gv.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',gv.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',gv.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>60,gv.total,0)AS dias4,
	gv.total AS saldo,
	0 AS factura,
	0 AS contrarecibo 
	FROM guiasventanilla gv
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	WHERE /*pg.fechacreo BETWEEN '2010-01-01' and '".cambiaf_a_mysql($_GET[fecha2])."' AND*/ pg.pagado='N'
	AND ISNULL(gv.factura) AND sc.estado='ACTIVADO'  AND cs.id='$_GET[sucursal]')
UNION
	/*GUIAS EMPRESARIALES NO FACTURADAS*/
	(SELECT cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.id AS guiafactura,
	gv.fecha AS fecha,DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento, 
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',gv.fecha)<=cc.diascredito,gv.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',gv.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',gv.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',gv.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>60,gv.total,0)AS dias4,
	gv.total AS saldo,
	0 AS factura,
	0 AS contrarecibo 
	FROM guiasempresariales gv
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	WHERE /*pg.fechacreo BETWEEN '2010-01-01' and '".cambiaf_a_mysql($_GET[fecha2])."' AND */ pg.pagado='N'
	AND ISNULL(gv.factura) AND sc.estado='ACTIVADO'  AND cs.id='$_GET[sucursal]')
UNION
	/*GUIAS VENTANILLA FACTURADAS*/
	(SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,CONCAT('FAC-',f.folio) AS guiafactura,
	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,
	f.total-ifnull(fpg.total,0) AS saldo,
	f.folio AS factura,
	IFNULL(cr.contrarecibo,0) AS contrarecibo  
	FROM facturacion f
	INNER JOIN guiasventanilla gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	LEFT JOIN pagoguias as fpg on f.folio = fpg.guia and fpg.tipo = 'FACT'
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	LEFT JOIN 
	(SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 
	INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 
	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id
	WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO'  AND cs.id='$_GET[sucursal]' 
	GROUP BY lcd.factura)cr 
	ON f.folio=cr.factura
	WHERE /*pg.fechacreo BETWEEN '2010-01-01' and '".cambiaf_a_mysql($_GET[fecha2])."' AND*/ pg.pagado='N' 
	AND sc.estado='ACTIVADO'
	AND gv.factura<>0  AND cs.id='$_GET[sucursal]'
	GROUP BY f.folio)
UNION
	/*GUIAS EMPRESARIALES FACTURADAS*/
	(SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,CONCAT('FAC-',f.folio) AS guiafactura,
	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,
	f.total-ifnull(fpg.total,0) AS saldo,
	f.folio AS factura,
	IFNULL(cr.contrarecibo,0) AS contrarecibo  
	FROM facturacion f
	INNER JOIN guiasempresariales gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	LEFT JOIN pagoguias as fpg on f.folio = fpg.guia and fpg.tipo = 'FACT'
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	LEFT JOIN 
	(SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 
	INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 
	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id
	WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO'  AND cs.id='$_GET[sucursal]' 
	GROUP BY lcd.factura
	)cr 
	ON f.folio=cr.factura
	WHERE pg.pagado='N' 
	AND sc.estado='ACTIVADO'
	AND not isnull(gv.factura) AND cs.id='$_GET[sucursal]'
	GROUP BY f.folio)
UNION
	(SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,CONCAT('FAC.OTROS-',f.folio) AS guiafactura,
	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,
	pg.total AS saldo,
	f.folio AS factura,
	IFNULL(cr.contrarecibo,0) AS contrarecibo  
	FROM facturacion f
	INNER JOIN solicitudguiasempresariales sg ON f.folio=sg.factura
	INNER JOIN pagoguias pg ON f.folio=pg.guia and pg.tipo='FACT'
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	LEFT JOIN (
		SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 
		INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 
		INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id
		WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO' AND cs.id='$_GET[sucursal]' 
		GROUP BY lcd.factura
	)cr 
	ON f.folio=cr.factura
	WHERE pg.pagado='N' 
	AND sc.estado='ACTIVADO'
	AND not isnull(sg.factura) AND cs.id='$_GET[sucursal]'
	GROUP BY f.folio)
)Tabla ORDER BY clavecliente,fecha LIMIT 0,30";
	$r=mysql_query($sql,$l)or die($sql);
	
	$tdes = mysql_num_rows($r);
	$inicio=$_GET[inicio];
	$fechaini=$_GET[fecha];
	$fechafin=$_GET[fecha2];
	$idsucursal=$_GET[sucursal];
	$tipo=0;
	$registros= array();	
	if (mysql_num_rows($r)>0)
			{
			while ($f=mysql_fetch_object($r))
			{
				$f->cliente=cambio_texto($f->cliente);
				$f->nombresucursal=cambio_texto($f->nombresucursal);
				$registros[]=$f;	
			}
		$datos= str_replace('null','""',json_encode($registros));
	}else{
		$datos= str_replace('null','""',json_encode(0));
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.js?random=20060118"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<link href="../../estilos_estandar.css" />
<script src="../../javascript/ajax.js"></script>
<script language="javascript" src="../../javascript/funcionesDrag.js"></script>
<script language="javascript" src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/shortcut.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../../javascript/jquery.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u		= document.all;
	var inicio		= 30;
	var sepasods	= 0;
	var mens = new ClaseMensajes();
	mens.iniciar('../../javascript',true);
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:50, alineacion:"left", datos:"nombresucursal"},
			{nombre:"CLIENTE", medida:180, alineacion:"left", datos:"cliente"},
			{nombre:"GUIA/FACTURA", medida:90, alineacion:"left",  datos:"guiafactura"},
			{nombre:"FECHA", medida:60, alineacion:"left",  datos:"fecha"},	
			{nombre:"FECHA VTO", medida:80, alineacion:"left",  datos:"fechavencimiento"},
			{nombre:"DIAS VENC", medida:70, alineacion:"right",  datos:"diasvencimiento"},
			{nombre:"AL CORRIENTE", medida:100, tipo:"moneda",alineacion:"right",  datos:"corriente"},
			{nombre:"1-15 DIAS", medida:100, tipo:"moneda",alineacion:"right",  datos:"dias1"},
			{nombre:"16-30 DIAS", medida:100, tipo:"moneda",alineacion:"right",  datos:"dias2"},
			{nombre:"31-60 DIAS", medida:100, tipo:"moneda",alineacion:"right",  datos:"dias3"},
			{nombre:"> 60- DIAS", medida:100, tipo:"moneda",alineacion:"right",  datos:"dias4"},
			{nombre:"SALDO", medida:100, tipo:"moneda",alineacion:"right",  datos:"saldo"},
			{nombre:"FACTURA", medida:50, alineacion:"center",  datos:"factura"},
			{nombre:"CONTRARECIBO", medida:70, alineacion:"center",  datos:"contrarecibo"}
		],
		filasInicial:30,
		alto:240,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"verRecoleccion()",
		nombrevar:"tabla1"
	});
	jQuery(function($){
		$('#fecha').mask("99/99/9999");
		$('#fecha2').mask("99/99/9999");
	});
	
	window.onload = function(){
		tabla1.create();
		u.total.value=0;
		mostrardetalle('<?=$datos ?>');
		u.fecha.value='<?=$fechaini ?>' ;
		u.fecha2.value='<?=$fechafin ?>' ;
		u.sucursal.value='<?=$idsucursal ?>' ;
		u.d_atrasdes.style.visibility = "hidden";
		if(parseInt(u.mostrardes2.value) <= 30){
			u.d_sigdes.style.visibility  = "hidden";
		}
	}
	
	function mostrardetalle(datos){	
		if (datos!=0) {
			var subtotal=0;
				tabla1.clear();
				var objeto = eval(convertirValoresJson(datos));
				for(var i=0;i<objeto.length;i++){
					var obj		 	   		= new Object();
					obj.cliente 			= objeto[i].cliente;
					obj.nombresucursal 		= objeto[i].nombresucursal;
					obj.guiafactura 		= objeto[i].guiafactura;
					obj.fecha				= objeto[i].fecha;
					obj.fechavencimiento	= objeto[i].fechavencimiento;
					obj.diasvencimiento	 	= objeto[i].diasvencimiento;
					obj.corriente	 		= objeto[i].corriente;
					obj.dias1	 			= objeto[i].dias1;
					obj.dias2	 			= objeto[i].dias2;
					obj.dias3	 			= objeto[i].dias3;
					obj.dias4	 			= objeto[i].dias4;
					obj.saldo	 			= objeto[i].saldo;
					obj.factura	 			= objeto[i].factura;
					obj.contrarecibo	 	= objeto[i].contrarecibo;
					subtotal += parseFloat(objeto[i].saldo);
					tabla1.add(obj);
				}	
					u.total.value=convertirMoneda(subtotal);
			}else{
				if (u.inicio.value!="1"){
					tabla1.clear();
					u.total.value=0;
					mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");
				}
			}
		}
	
		
	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		valor2 = "$ "+numcredvar(valor1.toLocaleString());
		return valor2;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
	function pedirCliente(id){
		u.idcliente.value=id;
		if (u.idcliente.value!=""){
			consultaTexto("mostrarCliente", "principal_con.php?accion=4&cliente="+u.idcliente.value+"&valram="+Math.random());
		}else{
			alerta3("Debe Capturar el Codigo del Cliente", "¡Atencion!","idcliente");
		}
	}
	
	function mostrarCliente(datos){
		if(datos!=0){
			var obj = eval(convertirValoresJson(datos));
			u.idcliente.value	= obj[0].id;
			u.cliente.value 	= obj[0].cliente;
		}else{
			alerta3("No Existen Datos Con Este Cliente", "¡Atencion!","idcliente");
			limpiarCliente();
		}
	}
	
	function limpiarCliente(){
		u.idcliente.value	= "";
		u.cliente.value 	= "";
	}
	
	function ObtenerDetalle(){
		if(u.fecha.value==""){
			mens.show("A","Debe capturar la fecha","¡Atención!","");	
		}else if(u.fecha2.value==""){
			mens.show("A","Debe capturar la fecha","¡Atención!","");
		}else if (u.fecha2.value < u.fecha.value){
			mens.show("A","La fecha final debe ser mayor ala fecha de inicio","¡Atención!","");
		}else{
			consultaTexto("mostrarcontador","principal_con.php?accion=8&fecha="+u.fecha.value
			+"&fecha2="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&cliente="+u.idcliente.value);
			
		}
	}
	
	function mostrarcontador(datos){
		row = datos.split(",");
		tdes = row[0];
		u.mostrardes2.value=tdes;
		u.contadordes.value=tdes;
		u.d_atrasdes.style.visibility = "hidden";
		if(parseInt(u.mostrardes2.value) > 30){
			u.d_sigdes.style.visibility  = "visible";
		}		
		u.tipo.value=1;
		consultaTexto("mostrardetalle","principal_con.php?accion=5&fecha="+u.fecha.value
			+"&fecha2="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&cliente="+u.idcliente.value+"&inicio=0&tipo="+u.tipo.value);
	}
	
	function mostrarDescuento(tipo){
		if(tipo == "atras"){
			u.d_sigdes.style.visibility = "visible";
			u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
			if(parseFloat(u.totaldes.value) <= "1"){				
				u.totaldes.value = "01";
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				u.d_atrasdes.style.visibility = "hidden";
				
				consultaTexto("mostrardetalle2","principal_con.php?accion=5&fecha="+u.fecha.value
				+"&fecha2="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&cliente="+u.idcliente.value+"&inicio=0&tipo="+u.tipo.value);
					
			}else{
				if(sepasods!=0){
					u.mostrardes.value = sepasods;
					sepasods = 0;
				}
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
			
				consultaTexto("mostrardetalle2","principal_con.php?accion=5&fecha="+u.fecha.value
			+"&fecha2="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&cliente="+u.idcliente.value+"&inicio="+u.totaldes.value+"&tipo="+u.tipo.value);
			}			
		}else{
			u.d_atrasdes.style.visibility = "visible";
			u.totaldes.value = inicio + parseFloat(u.totaldes.value);		
			if(parseFloat(u.totaldes.value) > parseFloat(u.contadordes.value)){
				
				u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					u.mostrardes.value = u.contadordes.value;
				}
				u.d_sigdes.style.visibility = "hidden";
			}else{
				
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					sepasods	=	u.mostrardes.value;
					u.mostrardes.value = u.contadordes.value;
				}
					consultaTexto("mostrardetalle2","principal_con.php?accion=5&fecha="+u.fecha.value
			+"&fecha2="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&cliente="+u.idcliente.value+"&inicio="+u.totaldes.value+"&tipo="+u.tipo.value);
			}			
		}	
	}
	
	function mostrardetalle2(datos){
		if(datos.indexOf("nada")<0){
			var obj = eval(datos);
			tabla1.setJsonData(obj);
		}		
	}
	
	function tipoImpresion(valor){
		if(valor=="Archivo"){
			window.open("http://www.pmmentuempresa.com/web/general/cobranza/generarExcelAntiguedadSaldos.php?tipo="+u.tipo.value+"&fecha="+((u.fecha.value=='<?=$_GET[fecha] ?>')?'<?=$_GET[fecha] ?>':u.fecha.value)+"&fecha2="+((u.fecha2.value=='<?=$_GET[fecha2] ?>')?'<?=$_GET[fecha2] ?>':u.fecha2.value)+"&sucursal="+((u.sucursal.value=='<?=$_GET[sucursal] ?>')?'<?=$_GET[sucursal] ?>':u.sucursal.value)+"&cliente="+((u.idcliente.value!='')?u.idcliente.value:''));
		}
	}
</script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
</style>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="../Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="680" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr>
    <td colspan="2"><table width="499" border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td width="73">Fecha Inicial: </td>
        <td width="100"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=$fecha ?>"  /></td>
        <td width="36"><img src="../../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" /></td>
        <td width="68">Fecha Final: </td>
        <td width="100"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=$fecha2 ?>" /></td>
        <td width="48"><img src="../../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)" /></td>
        <td width="74"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="39">Cliente:</td>
    <td width="522"><span class="Tablas">
      <input name="idcliente" type="text" class="Tablas" id="idcliente" style="width:100px" value="<?=$idcliente ?>" onkeypress="if(event.keyCode=='13'){pedirCliente(this.value);};"/>
      <img src="../../img/Buscar_24.gif" width="24" height="23" align="absbottom" onclick="abrirVentanaFija('../../buscadores_generales/buscarClienteGen2.php?funcion=pedirCliente', 625, 450, 'ventana', 'Buscar Cliente')" />
      <input name="cliente" type="text" class="Tablas" id="cliente" style="width:300px;background:#FFFF99" value="<?=$cliente ?>" readonly=""/>
      <input name="sucursal" type="hidden" class="Tablas" id="sucursal" style="width:100px" value="<?=$sucursal ?>" />
      <span class="Estilo4">
        <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
        </span><span class="Estilo4">
          <input name="totaldes" type="hidden" id="totaldes" value="1" />
          <strong>
          <input name="inicio" type="hidden" class="Tablas" id="inicio" style="width:100px" value="<?=$inicio ?>"/>
&nbsp;
<input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />
          <input name="tipo" type="hidden" class="Tablas" id="tipo" style="width:100px" value="<?=$tipo ?>" />
          </strong></span></span></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
      <table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">
      </table>    </td>
  </tr>
  <tr>
    <td colspan="2"><table width="549" height="15" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="3">&nbsp;</td>
        <td width="908"><div align="right">Total Gral:<strong><strong>
          <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />
        </strong></strong></div></td>
        <td width="90" align="center"><input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:100px;background:#FFFF99;text-align:right;" value="<?=$total ?>" readonly="" align="right" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>
        <td width="302" align="center"><table width="74" align="center">
            <tr>
              <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>
            </tr>
          </table>
          <strong><strong><span style="color:#FF0000"></span></strong></strong></td>
        <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
<script>
	//parent.frames[1].document.getElementById('titulo').innerHTML = '';
</script>
</html>