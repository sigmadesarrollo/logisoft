<? 	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');	
	
$sql="SELECT DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,sucursal,referenciacargo,referenciaabono,cargo,abono,saldo,descripcion FROM 

(

	/*GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM guiasempresariales gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]' and gv.tipopago='CREDITO'

UNION

	/*GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM facturacion f

	INNER JOIN guiasempresariales gv ON f.folio=gv.factura

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND */ gv.factura<>0 AND f.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]' and gv.tipopago='CREDITO' AND f.total>0

	GROUP BY f.folio

UNION

	/*GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM guiasventanilla gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ 		

	ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	AND gv.condicionpago = 1

UNION

	/*GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, 

	f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM facturacion f

	INNER JOIN guiasventanilla gv ON f.folio=gv.factura

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	WHERE /*gv.fecha	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  

	AND */ gv.factura<>0 AND f.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	AND gv.condicionpago = 1 AND f.total>0

	GROUP BY f.folio	

UNION

	/*VENTA DE PREPAGADAS*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' PREP') AS referenciacargo,0 AS referenciaabono, 

	pg.total-(f.sobmontoafacturar+f.otrosmontofacturar) AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN solicitudguiasempresariales sf ON f.folio=sf.factura

	INNER JOIN pagoguias pg on f.folio = pg.guia and pg.tipo = 'FACT'

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]'

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO'  AND pg.total-(f.sobmontoafacturar+f.otrosmontofacturar)>0

UNION

	/*VALORES DECLARADOS Y SOBREPESO*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' SP.VD') AS referenciacargo,0 AS referenciaabono, 

	f.sobmontoafacturar AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN guiasempresariales ge ON f.folio=ge.factura AND (ge.texcedente>0 OR ge.tseguro>0)

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]' and f.sobmontoafacturar>0

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO' 

UNION

	/*VALORES OTROS*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' OTRO') AS referenciacargo,0 AS referenciaabono, 

	f.otrosmontofacturar AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]' and f.otrosmontofacturar>0

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO'

UNION

	/*ABONOS CLIENTE*/

	SELECT a.fecharegistro AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',a.folio) AS referenciaabono, 0 AS cargo, SUM(a.abonar) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(a.efectivo>0,'EFECTIVO',' '),',',IF(a.banco>0,cb.descripcion,' '),',',IF(a.cheque>0,concat('CHEQUE: ',a.cheque),' '),',',IF(a.tarjeta>0,'TARJETA',' '),',',IF(a.transferencia>0,'TRANSFERENCIA',' '))AS descripcion FROM abonodecliente a

	INNER JOIN catalogosucursal cs ON a.idsucursal=cs.id 

	LEFT JOIN catalogobanco as cb ON a.banco = cb.id

	WHERE /*a.fecharegistro BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ 

	a.idcliente='" .$_GET[cliente]. "' and a.idsucursal='$_GET[sucursal]' 

	GROUP BY a.factura

UNION

	/*ABONOS GUIAS A CONTADO removido para el reporte de cobranza*/

	/*SELECT fp.fecha AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',fp.guia) AS referenciaabono, 0 AS cargo, SUM(fp.total) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(fp.efectivo>0,'EFECTIVO',' '),',',IF(fp.banco>0,'BANCO',' '),',',IF(fp.cheque>0,'CHEQUE',' '),',',IF(fp.tarjeta>0,'TARJETA',' '),',',IF(fp.transferencia>0,'TRANSFERENCIA',' '))AS descripcion 

	FROM formapago fp

	INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 

	INNER JOIN pagoguias pg ON fp.guia=pg.guia

	WHERE  fp.procedencia='G' AND pg.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	GROUP BY fp.guia

UNION*/

	/*LIQUIDACION COBRANZA*/

	SELECT lc.fechaliquidacion AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',lc.folio) AS referenciaabono, 0 AS cargo, SUM(lcd.importe) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(lcd.efectivo>0,'EFECTIVO',' '),',',IF(lcd.banco>0,'BANCO',' '),',',IF(lcd.cheque>0,'CHEQUE',' '),',',IF(lcd.tarjeta>0,'TARJETA',' '),',',IF(lcd.transferencia>0,'TRANSFERENCIA',' '))AS descripcion 

	FROM liquidacioncobranza lc

	INNER JOIN liquidacioncobranzadetalle lcd ON lc.folio=lcd.folioliquidacion

	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id

	WHERE /*lc.fechaliquidacion BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */  lcd.cliente='" .$_GET[cliente]. "' AND lcd.cobrar='SI' and lc.sucursal='$_GET[sucursal]'

	GROUP BY lcd.factura

UNION

	/*CANCELACION GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion FROM guiasventanilla gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' AND gv.estado='CANCELADO' and pg.sucursalacobrar = '$_GET[sucursal]'

UNION

	/*CANCELACION GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion FROM guiasempresariales gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' AND gv.estado='CANCELADO' and pg.sucursalacobrar = '$_GET[sucursal]'

)Tabla ORDER BY fecha, referenciaabono,referenciacargo LIMIT 0,30";
	//die($sql);
	$r=mysql_query($sql,$l)or die($sql); 
	$tdes = mysql_num_rows($r);
	$registros= array();	
	$inicio=$_GET[inicio];
	$fechaini=$_GET[fecha];
	$fechafin=$_GET[fecha2];
	$mes=$_GET[mes];
	$nombrecliente=$_GET[nombrecliente];
	$idcliente=$_GET[cliente];
	$tipo=0;
		if (mysql_num_rows($r)>0)
				{
				while ($f=mysql_fetch_object($r))
				{
					$f->sucursal=cambio_texto($f->sucursal);
					$f->descripcion=cambio_texto($f->descripcion);
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
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reporte Ventas</title>
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../Tablas.css" rel="stylesheet" type="text/css">
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
.Estilo4 {font-size: 12px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
#form1 table tr td table tr td div {
	text-align: right;
}
#form1 table tr td #txtDir table tr td {
	text-align: center;
}
#form1 table tr td #txtDir table {
	text-align: center;
}
#form1 table tr td table tr td strong {
	text-align: right;
}
-->
</style>
<SCRIPT type="text/javascript" src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/funciones.js"></script>
<script language="javascript1.1" src="../../javascript/funcionesDrag.js"></script>
<script language="javascript1.1" src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../../javascript/ajaxlist/ajax.js"></script>
<script src="../../javascript/ClaseTabs.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
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
			{nombre:"FECHA", medida:70, alineacion:"left", datos:"fecha"},
			{nombre:"SUCURSAL", medida:50, alineacion:"left",  datos:"sucursal"},
			{nombre:"REF.CARGO", medida:90, alineacion:"left",  datos:"referenciacargo"},
			{nombre:"REF.ABONO", medida:90, alineacion:"left", datos:"referenciaabono"},			
			{nombre:"CARGOS", medida:100, tipo:"moneda",alineacion:"right",  datos:"cargo"},
			{nombre:"ABONOS", medida:100, tipo:"moneda",alineacion:"right",  datos:"abono"},
			{nombre:"SALDO", medida:100, tipo:"moneda",alineacion:"right",  datos:"saldo"},
			{nombre:"DESCRIPCION", medida:70, alineacion:"left",  datos:"descripcion"}
		],
		filasInicial:30,
		alto:220,
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
		mostrardetalle('<?=$datos ?>');
		u.fecha.value='<?=$fechaini ?>' ;
		u.fecha2.value='<?=$fechafin ?>' ;
		u.mes.value='<?=$mes ?>' ;
		u.idcliente.value='<?=$idcliente ?>' ;
		u.cliente.value='<?=$nombrecliente ?>' ;
		u.d_atrasdes.style.visibility = "hidden";
		if(parseInt(u.mostrardes2.value) <= 30){
			u.d_sigdes.style.visibility  = "hidden";
		}
	}
	
	function mostrardetalle(datos){	
		if (datos!=0) {
			$total=0;
			$cargos=0;
			$abonos=0;
				tabla1.clear();
				var objeto = eval(convertirValoresJson(datos));
				for(var i=0;i<objeto.length;i++){
					var obj		 	   		= new Object();
					obj.fecha 			= objeto[i].fecha;
					obj.sucursal 		= objeto[i].sucursal;
					obj.referenciacargo	= objeto[i].referenciacargo;
					obj.referenciaabono	= objeto[i].referenciaabono;
					obj.cargo	 		= objeto[i].cargo;
					obj.abono	 		= objeto[i].abono;
					
					obj.descripcion	 	= objeto[i].descripcion;
					$cargos+=parseFloat(objeto[i].cargo);
					$abonos+=parseFloat(objeto[i].abono);
					$total+= parseFloat(objeto[i].cargo-objeto[i].abono);	
					obj.saldo=$total;
					tabla1.add(obj);
				}	
					u.cargos.value=convertirMoneda($cargos);
					u.abonos.value=convertirMoneda($abonos);
					u.total.value=convertirMoneda($total);
			}else{
				if (u.inicio.value!="1"){
				tabla1.clear();
				mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");
				}
			}
		}
		
	function mostrarcontador(datos){		
		//document.all.textarea.value = datos;
		row = datos.split(",");
		tdes = row[0];
		u.mostrardes2.value=tdes;
		u.contadordes.value=tdes;
		u.d_atrasdes.style.visibility = "hidden";
		if(parseInt(u.mostrardes2.value) > 30){
			u.d_sigdes.style.visibility  = "visible";
		}		
		

		u.tipo.value=1;

		consultaTexto("mostrardetalle","principal_con.php?accion=3&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&cliente="+u.idcliente.value+"&inicio=0&tipo="+u.tipo.value+"&sucursal=<?=$_GET[sucursal]?>");
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
	
	function ObtenerDetalle(){
		if(u.fecha.value==""){
			mens.show("A","Debe capturar la fecha","¡Atención!","");	
		}if(u.fecha2.value==""){
			mens.show("A","Debe capturar la fecha","¡Atención!","");
		}if(u.idcliente.value==""){
			mens.show("A","Debe escoger el cliente","¡Atención!","");
		}else if (u.fecha2.value < u.fecha.value){
			mens.show("A","La fecha final debe ser mayor ala fecha de inicio","¡Atención!","");
		}else{
			consultaTexto("mostrarcontador","principal_con.php?accion=9&fecha="+u.fecha.value
			+"&fecha2="+u.fecha2.value+"&cliente="+u.idcliente.value+"&sucursal=<?=$_GET[sucursal]?>");
		}
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
				
				consultaTexto("mostrarDetalle2","principal_con.php?accion=3&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&cliente="+u.idcliente.value+"&inicio=0&tipo="+u.tipo.value);			
			}else{
				if(sepasods!=0){
					u.mostrardes.value = sepasods;
					sepasods = 0;
				}
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				consultaTexto("mostrarDetalle2","principal_con.php?accion=3&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&cliente="+u.idcliente.value+"&inicio="+u.totaldes.value+"&tipo="+u.tipo.value);
				
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
		
				consultaTexto("mostrarDetalle2","principal_con.php?accion=3&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&cliente="+u.idcliente.value+"&inicio="+u.totaldes.value+"&tipo="+u.tipo.value);
			}			
		}	
	}
	
	function mostrarDetalle2(datos){
		if(datos.indexOf("nada")<0){
			var obj = eval(datos);
			tabla1.setJsonData(obj);
		}		
	}
	function tipoImpresion(valor){
		if(valor=="Archivo"){
			window.open("http://www.pmmentuempresa.com/web/general/cobranza/generacionCobranzaCliente.php?tipo="+u.tipo.value+"&fecha="+((u.fecha.value=='<?=$_GET[fecha] ?>')?'<?=$_GET[fecha] ?>':u.fecha.value)+"&fecha2="+((u.fecha2.value=='<?=$_GET[fecha2] ?>')?'<?=$_GET[fecha2] ?>':u.fecha2.value)+"&cliente="+((u.idcliente.value=='<?=$_GET[cliente] ?>')?'<?=$_GET[cliente] ?>':u.idcliente.value));
		}
	}
</script>

</head>
<body>
<form id="form1" name="form1" method="post" action="">

  <table width="670" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="4"><table width="499" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="73">Fecha Inicial: </td>
            <td width="100"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=$fecha ?>" /></td>
            <td width="36"><img src="../../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" /></td>
            <td width="68">Fecha Final: </td>
            <td width="100"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=$fecha2 ?>" /></td>
            <td width="48"><img src="../../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)" /></td>
            <td width="74"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></td>

          </tr>
      </table></td>

    </tr>
    <tr>
      <td>Mes:</td>
      <td><input name="mes" type="text" class="Tablas" id="mes" style="width:100px;background:#FFFF99" readonly="" value="<?=$mes ?>"/></td>
      <td>Cliente:</td>
      <td><input name="cliente" type="text" class="Tablas" id="cliente" style="width:300px;background:#FFFF99" readonly="" value="<?=$cliente ?>"/>
        <input name="idcliente" type="hidden" class="Tablas" id="idcliente" style="width:100px" value="<?=$idcliente ?>"/>
        <span class="Estilo4">
        <input name="totaldes" type="hidden" id="totaldes" value="01" />

        </span><span class="Estilo4">
        <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
        <input name="inicio" type="hidden" id="inicio" value="<?=$inicio ?>" />
        <strong><strong>

        <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />

        <input name="tipo" type="hidden" class="Tablas" id="tipo" style="width:100px" value="<?=$tipo ?>"/>

        </strong></strong></span></td>

    </tr>
    <tr>
      <td width="28">&nbsp;</td>
      <td width="106">&nbsp;</td>
      <td width="42">&nbsp;</td>
      <td width="402">&nbsp;</td>

    </tr>
    <tr>
      <td colspan="4"></td>

    </tr>
    <tr>
      <td colspan="4"><table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">
      </table></td>

    </tr>
    <tr>
      <td colspan="4"><table width="416" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="307"><label></label></td>
            <td width="55">&nbsp;</td>
            <td width="54">&nbsp;</td>

          </tr>
      </table></td>

    </tr>
    <tr>
      <td colspan="4"><table width="532" height="16" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center"><div align="left">
                <input name="cargos" type="text" class="Tablas" id="cargos" style="text-align:right;width:100px;background:#FFFF99" value="<?=$cargos ?>
                " readonly=""/>
            </div></td>
            <td align="center"><input name="abonos" type="text" class="Tablas" id="abonos" style="text-align:right;width:100px;background:#FFFF99" value="<?=$abonos ?>" readonly="" align="right"/></td>
            <td align="center"><strong><strong>
              <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />
            </strong></strong></td>

          </tr>
          <tr>
            <td width="3">&nbsp;</td>
            <td width="264"><div align="right">Saldo Contable:</div></td>
            <td width="2" align="center"><div align="right"></div></td>
            <td width="85" align="center"><div align="left">
                <input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:100px;background:#FFFF99; " value="<?=$total ?>" readonly="" align="right" ondblclick="agregatotalventascobradas()"/>
            </div></td>
            <td width="95" align="center"><div align="center"></div></td>
            <td width="83" align="center"><div align="center"></div></td>

          </tr>
      </table></td>

    </tr>
    <tr>
      <td colspan="4"><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>
            <td style="150px" align="right"><table width="74" align="center">

              <tr>

                <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>

              </tr>

            </table></td>
            <td width="99" align="center">&nbsp;</td>
            <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>

          </tr>
      </table></td>

    </tr>

  </table>
</form>
</body>
<script>
//	parent.frames[1].document.getElementById('titulo').innerHTML = '';
</script>
</html>