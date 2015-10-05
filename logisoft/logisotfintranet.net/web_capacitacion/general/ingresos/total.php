<? 	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');


$sql="SELECT  IngresosFormadeCobro.sucursal,cs.prefijo AS nombresucursal,SUM(IngresosFormadeCobro.contado) AS contado,SUM(IngresosFormadeCobro.cobranza)AS cobranza,SUM(IngresosFormadeCobro.entregadas)AS entregadas,(SUM(IngresosFormadeCobro.contado)+SUM(IngresosFormadeCobro.cobranza)+SUM(IngresosFormadeCobro.entregadas))AS total,
0 AS depositado,(SUM(IngresosFormadeCobro.contado)+SUM(IngresosFormadeCobro.cobranza)+SUM(IngresosFormadeCobro.entregadas)) AS saldo FROM (
	/*GUIAS VENTANILLA Y EMPRESARIALES*/
	SELECT sucursal,0 AS cobranza,SUM(total) AS contado,0 AS entregadas 
	FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND procedencia IN('G') 
	and isnull(formapago.fechacancelacion)
	GROUP BY sucursal	
UNION 
	/*LIQUIDACION COBRANZA Y ABONOS*/
	SELECT sucursal,SUM(total)AS cobranza,0 AS contado,0 AS entregadas 
	FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND procedencia IN('A','C') 
	and isnull(formapago.fechacancelacion)
	GROUP BY sucursal	
UNION 
	/*LIQUIDACION MERCANCIA Y ENTREGAS OCURRE (ENTREGADAS)*/
	SELECT sucursal,0 AS cobranza,0 AS contado,SUM(total) AS entregadas 
	FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND procedencia IN('M') 
	and isnull(formapago.fechacancelacion)
	GROUP BY sucursal
UNION 
	/*LIQUIDACION MERCANCIA Y ENTREGAS OCURRE (ENTREGADAS)*/
	SELECT fp.sucursal,0 AS cobranza,0 AS contado, sum(gv.total) AS entregadas 
	FROM formapago fp
	INNER JOIN entregasocurre eo ON fp.guia=eo.folio
	INNER JOIN entregasocurre_detalle ed ON eo.folio=ed.entregaocurre	
	INNER JOIN guiasventanilla gv ON ed.guia=gv.id	
	INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
	WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND fp.procedencia='O' and gv.tipoflete=1 and gv.condicionpago=0
	AND isnull(fp.fechacancelacion) GROUP BY cs.id
UNION 
	/*FACTURACION*/
	SELECT formapago.sucursal, 0 AS cobranza, SUM(if(f.credito='SI',0,formapago.total)) AS contado,0 AS entregadas 
	FROM formapago 
	inner join facturacion f on formapago.guia = f.folio
	WHERE formapago.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND formapago.procedencia IN('F') 
	and isnull(formapago.fechacancelacion)
	GROUP BY formapago.sucursal
)IngresosFormadeCobro 
INNER JOIN catalogosucursal cs ON IngresosFormadeCobro.sucursal=cs.id
WHERE IngresosFormadeCobro.sucursal<>''
GROUP BY IngresosFormadeCobro.sucursal LIMIT 0,30";
	$r=mysql_query($sql,$l)or die($sql); 
	$tdes = mysql_num_rows($r);
	$registros= array();	
	$fechaini=$_GET[fecha];
	$fechafin=$_GET[fecha2];
	$inicio=$_GET[inicio];
	
		if (mysql_num_rows($r)>0)
				{
				while ($f=mysql_fetch_object($r))
				{
					$f->nombrecliente=cambio_texto($f->nombrecliente);
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
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"left", datos:"sucursal"},
			{nombre:"SUCURSAL", medida:60, alineacion:"left", datos:"nombresucursal"},
			{nombre:"CONTADO", medida:100, tipo:"moneda", onDblClick:"agregacontado",alineacion:"right",  datos:"contado"},
			{nombre:"COBRANZA", medida:100, tipo:"moneda", onDblClick:"agregacobranza",alineacion:"right",  datos:"cobranza"},
			{nombre:"ENTREGADAS", medida:100, tipo:"moneda",onDblClick:"agregaentregadas",alineacion:"right",  datos:"entregadas"},			
			{nombre:"TOTAL", medida:100, tipo:"moneda", alineacion:"right", datos:"total"},
			{nombre:"DEPOSITADO", medida:100, tipo:"moneda",alineacion:"right",  datos:"depositado"},
			{nombre:"SALDO", medida:100, tipo:"moneda" ,alineacion:"right",  datos:"saldo"}
		],
		filasInicial:30,
		alto:210,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	jQuery(function($){		
		$('#fecha2').mask("99/99/9999");
		$('#fecha').mask("99/99/9999");
	});

	window.onload = function(){
		tabla1.create();
		mostrardetalle('<?=$datos ?>');
		u.fecha.value='<?=$fechaini ?>' ;
		u.fecha2.value='<?=$fechafin ?>' ;
		u.d_atrasdes.style.visibility = "hidden";
		if(parseInt(u.mostrardes2.value) <= 30){
			u.d_sigdes.style.visibility  = "hidden";
		}
	}
	
	function PonerCeroTotales(){
		u.total.value=0.00;
		u.total1.value=0.00;
		u.total2.value=0.00;
		u.total3.value=0.00;
		u.total4.value=0.00;
		u.total5.value=0.00;
	}
	
	function ObtenerDetalle(){
		if(u.fecha.value=="" || u.fecha2.value==""){
			mens.show("A","Debe capturar "+((u.fecha.value=="")? " fecha inicio" : "fecha fin"),"¡Atención!",((u.fecha.value=="")? "" : "" ));	 	
		}else if (u.fecha2.value < u.fecha.value){
			mens.show("A","La fecha final debe ser mayor ala fecha de inicial","¡Atención!","");
		}else{
			PonerCeroTotales();
			consultaTexto("mostrarcontador","total_con.php?accion=2&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"inicio=0");
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
		consultaTexto("mostrardetalle","total_con.php?accion=1&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=0");
	}
	
	function mostrardetalle(datos){	
		if (datos!=0) {
			var total=0;
			var total1=0;
			var total2=0;
			var total3=0;
			var total4=0;
			var total5=0;
				tabla1.clear();
				var objeto = eval(convertirValoresJson(datos));
				for(var i=0;i<objeto.length;i++){
					var obj		 	   		= new Object();
					obj.sucursal 			= objeto[i].sucursal;
					obj.nombresucursal 		= objeto[i].nombresucursal;
					obj.contado	 			= objeto[i].contado;
					obj.cobranza	 	   	= objeto[i].cobranza;
					obj.entregadas   		= objeto[i].entregadas;
					obj.total				= objeto[i].total;
					obj.depositado			= objeto[i].depositado;
					obj.saldo				= objeto[i].saldo;
					total += parseFloat(objeto[i].contado);
					total1 += parseFloat(objeto[i].cobranza);
					total2 += parseFloat(objeto[i].entregadas);
					total3 += parseFloat(objeto[i].total);
					total4 += parseFloat(objeto[i].depositado);
					total5 += parseFloat(objeto[i].saldo);
					tabla1.add(obj);
				}	
		
				u.total.value=convertirMoneda(total);
				u.total1.value=convertirMoneda(total1);
				u.total2.value=convertirMoneda(total2);
				u.total3.value=convertirMoneda(total3);
				u.total4.value=convertirMoneda(total4);
				u.total5.value=convertirMoneda(total5);
			}else{
				if (u.inicio.value!="1"){
					tabla1.clear();
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
	
	function agregacontado(){
		setTimeout("agregacontado2()",300);
	}
	
	function agregacontado2(){
		var arr = tabla1.getSelectedRow();
		//if (parseFloat(arr.contado.replace("$ ","").replace(/,/,""))!=0){
			
				parent.document.all.barratabs_contenedor_id2.disabled=false;	
				parent.document.all.iframe_id2.src="contado.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&sucursal="+arr.sucursal;
		parent.tabs.seleccionar(2);

		parent.cn.agregarDireccion(1);

		//}
	}
	
	function agregacobranza(){
		setTimeout("agregacobranza2()",300);
	}
	
	function agregacobranza2(){
		var arr = tabla1.getSelectedRow();
			//if (parseFloat(arr.cobranza.replace("$ ","").replace(/,/,""))!=0){
				parent.document.all.barratabs_contenedor_id3.disabled=false;	
				parent.document.all.iframe_id3.src="cobranza.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&sucursal="+arr.sucursal;
		parent.tabs.seleccionar(3);

		parent.cn.agregarDireccion(2);

			//}
		
	}
	
	function agregaentregadas(){
		setTimeout("agregaentregadas2()",300);
	}
	
	function agregaentregadas2(){
		var arr = tabla1.getSelectedRow();
			//if (parseFloat(arr.entregadas.replace("$ ","").replace(/,/,""))!=0){
				parent.document.all.barratabs_contenedor_id4.disabled=false;	
				parent.document.all.iframe_id4.src="entregadas.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&sucursal="+arr.sucursal;
		parent.tabs.seleccionar(4);

parent.cn.agregarDireccion(3);

			//}
		
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
				
			consultaTexto("mostrardetalle2","total_con.php?accion=1&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=0");
		
			}else{
				if(sepasods!=0){
					u.mostrardes.value = sepasods;
					sepasods = 0;
				}
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
			consultaTexto("mostrardetalle2","total_con.php?accion=1&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio="+u.totaldes.value);
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
				consultaTexto("mostrardetalle2","total_con.php?accion=1&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio="+u.totaldes.value);
			}			
		}	
	}
	
	function mostrardetalle2(datos){
		if(datos.indexOf("nada")<0){
			var obj = eval(datos);
			tabla1.setJsonData(obj);
		}		
	}
	
	function imprimirReporte(){
		if(document.URL.indexOf("web/")>-1){		
			var v_dir = "http://www.pmmintranet.net/web/general/ingresos/";
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_capacitacion/general/ingresos/";
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_pruebas/general/ingresos/";
		}
			
		window.open(v_dir+"generarExcelIngresos.php?accion=2&titulo=CONCILIACION DE INGRESOS&fecha="+u.fecha.value
		+"&fecha2="+u.fecha2.value+"&val="+Math.random());
	}
	
</script>


</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="624"><table width="538" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="73">Fecha Inicial: </td>
            <td width="121"><span class="Estilo6 Tablas">
              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=$fecha ?>" />
            </span></td>
            <td width="36"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
            <td width="63">Fecha Final:</td>
            <td width="105"><span class="Estilo6 Tablas">
              <input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=$fecha2 ?>" />
            </span></td>
            <td width="39"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>
            <td width="101"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></td>
          </tr>
        </table>
          <span class="Tablas"><span class="Estilo4">
          <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
          </span><span class="Estilo4">
          <input name="totaldes" type="hidden" id="totaldes" value="1" />
          <input name="inicio" type="hidden" id="inicio" value="<?=$inicio ?>" />
        </span></span></td>
    </tr>
    <tr>
      <td><div id="txtDir" style=" height:300px; width:690px; overflow:auto" align="left">
          <table width="426" id="detalle" border="0" cellpadding="0" cellspacing="0">
          </table>
          <table width="22%" height="16" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td align="left">Contado</td>
              <td align="left">Cobranza</td>
              <td align="left">Entregada</td>
              <td align="left">Total</td>
              <td align="left">Depositado</td>
              <td align="left">Total</td>
            </tr>
            <tr>
              <td width="84">Totales:</td>
              <td width="84"><strong><strong>
                <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:80px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />
              </strong></strong></td>
              <td width="80"><input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:80px;background:#FFFF99" value="<?=$total ?>
                " readonly="" align="right" /></td>
              <td width="80" align="center"><div align="left">
                  <input name="total1" type="text" class="Tablas" id="total1" style="text-align:right;width:80px;background:#FFFF99" value="<?=$total1 ?>
                " readonly="" align="right" />
              </div></td>
              <td width="86" align="center"><div align="left">
                  <input name="total2" type="text" class="Tablas" id="total2" style="text-align:right;width:80px;background:#FFFF99" value="<?=$total2 ?>
                " readonly="" align="right" />
              </div></td>
              <td width="89" align="center"><div align="left">
                  <input name="total3" type="text" class="Tablas" id="total3" style="text-align:right;width:80px;background:#FFFF99" value="<?=$total3 ?>
                " readonly="" align="right" />
              </div></td>
              <td width="80" align="center"><div align="left">
                  <input name="total4" type="text" class="Tablas" id="total4" style="text-align:right;width:80px;background:#FFFF99" value="<?=$total4 ?>
                " readonly="" align="right" />
              </div></td>
              <td width="81" align="center"><div align="left">
                  <input name="total5" type="text" class="Tablas" id="total5" style="text-align:right;width:80px;background:#FFFF99" value="<?=$total5 ?>
                " readonly="" align="right" />
              </div></td>
            </tr>
          </table>
          <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>
              <td width="302" align="center"><strong>&nbsp;
                    <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />
                    <strong><span style="color:#FF0000"></span></strong></strong></td>
              <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>
            </tr>
          </table>
          <p>&nbsp;</p>
      </div></td>
    </tr>
    <tr>
      <td></td>
    </tr>
    <tr>
      <td></td>
    </tr>
<tr>
      <td><table width="74" align="center">
        <tr>
          <td width="66" ><div class="ebtn_imprimir" onclick="imprimirReporte()"></div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>