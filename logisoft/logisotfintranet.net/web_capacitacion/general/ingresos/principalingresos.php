<?	session_start();
	$fechaini=date('d/m/Y');
	$fechafin=date('d/m/Y');
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
	var	u			= document.all;
	var mens = new ClaseMensajes();
	mens.iniciar('../../javascript',true);
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:40, alineacion:"left", datos:"nombresucursal"},
			{nombre:"EFECTIVO", medida:80, tipo:"moneda",alineacion:"right",  datos:"efectivo"},
			{nombre:"CHEQUES BANCOMER", medida:100, tipo:"moneda", alineacion:"right",  datos:"cheques"},
			{nombre:"CHEQUES OTROS", medida:90, tipo:"moneda",alineacion:"right",  datos:"otros"},
			{nombre:"TRANSF. ELECTR", medida:90, tipo:"moneda",alineacion:"right",  datos:"transferencia"},
			{nombre:"PAGO TARJETA", medida:90, tipo:"moneda",alineacion:"right",  datos:"tarjeta"},
			{nombre:"NOTAS CREDITO", medida:90, tipo:"moneda",alineacion:"right",  datos:"nc"},			
			{nombre:"TOTAL", medida:90, tipo:"moneda",alineacion:"right", datos:"total"}
		],
		filasInicial:30,
		alto:230,
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
		PonerCeroTotales();
		parent.tabs.agregarTabs("CONCILIACION DE INGRESOS",1,"total.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=1");
		parent.document.all.barratabs_contenedor_id1.disabled=true;	
	
		parent.tabs.agregarTabs("INGRESOS POR GUIAS DE CONTADO",2,"contado.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&sucursal=0&inicio=1");
		parent.document.all.barratabs_contenedor_id2.disabled=true;	
		
		parent.tabs.agregarTabs("INGRESOS POR COBRANZA",3,"cobranza.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&sucursal=0&inicio=1");
		parent.document.all.barratabs_contenedor_id3.disabled=true;	
		
		parent.tabs.agregarTabs("INGRESOS POR GUIAS ENTREGADAS",4,"entregadas.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&sucursal=0&inicio=1");
		parent.document.all.barratabs_contenedor_id4.disabled=true;	
		
		parent.tabs.seleccionar(0);
	}
	
	function PonerCeroTotales(){
		u.total.value=0.00;
		u.total1.value=0.00;
		u.total2.value=0.00;
		u.total3.value=0.00;
		u.total4.value=0.00;
		u.total5.value=0.00;
		u.total6.value=0.00;
	}
	
	function ObtenerDetalle(){
		if(u.fecha.value=="" || u.fecha2.value==""){
			mens.show("A","Debe capturar "+((u.fecha.value=="")? " fecha inicio" : "fecha fin"),"¡Atención!",((u.fecha.value=="")? "" : "" ));	 	
		}else if (u.fecha2.value < u.fecha.value){
			mens.show("A","La fecha final debe ser mayor ala fecha de inicial","¡Atención!","");
		}else{
			PonerCeroTotales();
			consultaTexto("mostrardetalle","principal_con.php?accion=1&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value);
		}
	}
	
	function mostrardetalle(datos){	
		if (datos!=0) {
			$total=0;
			$total1=0;
			$total2=0;
			$total3=0;
			$total4=0;
			$total5=0;
			$total6=0;
				tabla1.clear();
				var objeto = eval(convertirValoresJson(datos));
				for(var i=0;i<objeto.length;i++){
					var obj		 	   		= new Object();
					obj.nombresucursal 		= objeto[i].nombresucursal;
					obj.efectivo	 		= objeto[i].efectivo;
					obj.cheques	 	   		= objeto[i].cheques;
					obj.otros   			= objeto[i].otros;
					obj.transferencia		= objeto[i].transferencia;
					obj.tarjeta				= objeto[i].tarjeta;
					obj.nc					= objeto[i].nc;
					obj.total				= objeto[i].total;
					
					$total += parseFloat(objeto[i].efectivo);
					$total1 += parseFloat(objeto[i].cheques);
					$total2 += parseFloat(objeto[i].otros);
					$total3 += parseFloat(objeto[i].transferencia);
					$total4 += parseFloat(objeto[i].tarjeta);
					$total5 += parseFloat(objeto[i].nc);
					$total6 += parseFloat(objeto[i].total);
					tabla1.add(obj);
				}	
		
				u.total.value=convertirMoneda($total);
				u.total1.value=convertirMoneda($total1);
				u.total2.value=convertirMoneda($total2);
				u.total3.value=convertirMoneda($total3);
				u.total4.value=convertirMoneda($total4);
				u.total5.value=convertirMoneda($total5);
				u.total6.value=convertirMoneda($total6);
			}else{
				tabla1.clear();
				mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");
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
	
	function agregatotal(){
		setTimeout("agregatotal2()",300);
	}
	
	function agregatotal2(){
		if (parseFloat(u.total6.value.replace("$ ","").replace(/,/,""))!=0){
			parent.document.all.barratabs_contenedor_id1.disabled=false;	
			parent.document.all.iframe_id1.src="total.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value;
			parent.tabs.seleccionar(1);

			parent.cn.agregarDireccion(0);

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
			
		window.open(v_dir+"generarExcelIngresos.php?accion=1&titulo=REPORTE DE INGRESOS&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value);
	}
	
</script>

</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="680" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="785"><table width="544" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="66">Fecha Inicio: </td>
        <td width="104"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=$fechaini ?>"  /></td>
        <td width="37"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
        <td width="62">Fecha Final:</td>
        <td width="108"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=$fechafin ?>" /></td>
        <td width="59"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>
        <td width="108"><div align="center"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><div id="txtDir" style=" height:290px; width:680px; overflow:auto" align="left">
      <table width="300" id="detalle" border="0" cellpadding="0" cellspacing="0">
      </table>
      <table width="633" height="16" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
          <td width="97"><div align="left">Total Efect: </div></td>
          <td width="97" align="center"><div align="left"> Total Cheques:</div></td>
          <td width="97" align="center"><div align="left">Total Otros: </div></td>
          <td width="97" align="center"><div align="left">Trasf.Elect:</div></td>
          <td width="90" align="center"><div align="left">Total Tarjeta: </div></td>
          <td width="90" align="center"><div align="left">Total NC:</div></td>
          <td width="118" align="center"><div align="left">Total Gral: </div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:90px;background:#FFFF99" value="<?=$total ?>
                " readonly="" align="right" /></td>
          <td align="center"><input name="total1" type="text" class="Tablas" id="total1" style="text-align:right;width:90px;background:#FFFF99" value="<?=$total1 ?>
                " readonly="" align="right" /></td>
          <td align="center"><input name="total2" type="text" class="Tablas" id="total2" style="text-align:right;width:90px;background:#FFFF99" value="<?=$total2 ?>
                " readonly="" align="right" /></td>
          <td align="center"><input name="total3" type="text" class="Tablas" id="total3" style="text-align:right;width:90px;background:#FFFF99" value="<?=$total3 ?>
                " readonly="" align="right" /></td>
          <td align="center"><input name="total4" type="text" class="Tablas" id="total4" style="text-align:right;width:90px;background:#FFFF99" value="<?=$total4 ?>
                " readonly="" align="right" /></td>
          <td align="center"><input name="total5" type="text" class="Tablas" id="total5" style="text-align:right;width:90px;background:#FFFF99" value="<?=$total5 ?>
                " readonly="" align="right" /></td>
          <td align="center"><input name="total6" type="text" class="Tablas" id="total6" style="text-align:right;width:90px;background:#FFFF99; cursor:pointer" value="<?=$total6 ?>
                " readonly="" align="right" ondblclick="agregatotal()" title="Conciliación de Ingresos"/></td>
        </tr>
        <tr>
          <td width="14"><div align="right"></div></td>
          <td width="97"><div align="left"></div></td>
          <td width="97" align="center"><div align="left"></div></td>
          <td width="97" align="center"><div align="left"></div></td>
          <td width="97" align="center"><div align="left"></div></td>
          <td width="90" align="center"><div align="left"></div></td>
          <td width="90" align="center"><div align="left"></div></td>
          <td width="118" align="center"><div align="center"><a onclick="agregatotal()" href="#">Ver Conciliaci&oacute;n</a href></div>          </td>
        </tr>
      </table>
    </div></td>
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
</table>
</form>
</body>
</html>