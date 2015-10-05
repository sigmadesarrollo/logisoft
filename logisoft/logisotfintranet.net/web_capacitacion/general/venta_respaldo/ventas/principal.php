<?
	require_once('../../../Conectar.php');
	$l = Conectarse('webpmm');
	$fecha = date('d/m/Y');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reporte Ventas</title>
<link href="../../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../../estilos_estandar.css" rel="stylesheet" type="text/css" />
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
-->
</style>
<SCRIPT type="text/javascript" src="../../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../../../javascript/ClaseTabla.js"></script>
<script src="../../../javascript/ajax.js"></script>
<script src="../../../javascript/funciones.js"></script>
<script language="javascript1.1" src="../../../javascript/funcionesDrag.js"></script>
<script language="javascript1.1" src="../../../javascript/ClaseMensajes.js"></script>
<script src="../../../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../../../javascript/ajaxlist/ajax.js"></script>
<script src="../../../javascript/ClaseTabs.js"></script>
<script src="../../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../../../javascript/Mascara.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();
	var tabs 		= new ClaseTabs();
	mens.iniciar('../../../javascript',true);
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:140, alineacion:"left", datos:"sucursal"},
			{nombre:"CONVENIO", medida:170, onDblClick:"obtenerConvenio", tipo:"moneda", alineacion:"center",  datos:"convenio"},
			{nombre:"SIN CONVENIO", medida:170, onDblClick:"obtenerSConvenio", tipo:"moneda", alineacion:"center",  datos:"sinconvenio"},
			{nombre:"TOTAL", medida:170, onDblClick:"obtenerTotal", tipo:"moneda", alineacion:"center", datos:"total"}
		],
		filasInicial:20,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();		
		parent.tabs.agregarTabs("TIPO DE VENTA<br>",1,"convenio.php?sucursal=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value);
		parent.document.all.barratabs_contenedor_id1.disabled=true;	
		
		parent.tabs.agregarTabs("CONDICION DE PAGO CONVENIO",2,"sinconvenio.php?sucursal=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value);
		parent.document.all.barratabs_contenedor_id2.disabled=true;	
		parent.tabs.agregarTabs("VENTAS GUIAS PREPAGADAS",3,"../ventasprepagado/prepagadas.php?sucursal=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&inicio=1");
		parent.document.all.barratabs_contenedor_id3.disabled=true;	
		parent.tabs.agregarTabs("VENTAS GUIAS A CONSIGNACION",4,"../ventasconsignacion/consignacion.php?sucursal=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&inicio=1");
		parent.document.all.barratabs_contenedor_id4.disabled=true;	
		
		parent.tabs.agregarTabs("VENTA CONTADO CONVENIO",5,"contado.php?sucursal=0&cliente=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&inicio=1");
		
		parent.document.all.barratabs_contenedor_id5.disabled=true;	
		parent.tabs.agregarTabs("VENTAS CONVENIO CLIENTE",6,"total.php?sucursal=0&cliente=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&inicio=1");
		parent.document.all.barratabs_contenedor_id6.disabled=true;	
		parent.tabs.agregarTabs("(PREP)SERV PEND DE FACTURAR",7,"../ventasprepagado/pendientedefactura.php?sucursal=0&cliente=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value);
		parent.document.all.barratabs_contenedor_id7.disabled=true;	
		parent.tabs.agregarTabs("GUIAS Y SERVICIOS",8,"../ventasconsignacion/total.php?cliente=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value);	
		parent.document.all.barratabs_contenedor_id8.disabled=true;	
		parent.tabs.agregarTabs("ENVIOS X CLIENTE",9,"numerodelcliente.php?cliente=0&guia=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value);
		parent.document.all.barratabs_contenedor_id9.disabled=true;	
		
		parent.tabs.agregarTabs("CONDICION DE PAGO SIN CONVENIO",10,"sinconvenio.php?sucursal=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value);
		parent.document.all.barratabs_contenedor_id10.disabled=true;	
		
		parent.tabs.agregarTabs("VENTAS CONVENIO CLIENTE SIN CONVENIO",11,"total.php?sucursal=0&cliente=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&inicio=1");
		parent.document.all.barratabs_contenedor_id11.disabled=true;	
		
		parent.tabs.agregarTabs("VENTA CONTADO SIN CONVENIO",12,"contado.php?sucursal=0&cliente=0&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&inicio=1");
		parent.document.all.barratabs_contenedor_id12.disabled=true;	
		
		parent.tabs.seleccionar(0);
	
	}
	function generar(){
		var f1 = u.fechainicio.value.split("/");
		var f2 = u.fechafin.value.split("/");
		v_fechaini	= new Date(f1[2],f1[1],f1[0]); 
		v_fechafin	= new Date(f2[2],f2[1],f2[0]);
		
		if(u.fechainicio.value == "" || u.fechafin.value == ""){
			mens.show("A","Debe capturar "+((u.fechainicio.value=="")? "Fecha inicio" : "Fecha fin"),"¡Atención!",((u.fechainicio.value=="")? "fechainicio" : "fechafin"));
		}else if(v_fechaini > v_fechafin){
			mens.show("A","La fecha fin debe ser mayor a fecha inicio", "¡Atención!", "fechafin");
		}else{
			var arr = new Array();
			arr[0] = u.fechainicio.value;
			arr[1] = u.fechafin.value;
			consultaTexto("mostrarDetalle","consultasVentas.php?accion=1&arre="+arr+"&m="+Math.random());
		}
	}
	function mostrarDetalle(datos){	
	 if(datos.indexOf("no encontro")<0){
		var obj = eval(convertirValoresJson(datos));
		tabla1.setJsonData(obj);
		var vig = ""; var ven = ""; var tot = "";
		v_vig = 0; v_ven = 0; v_tot = 0; 
		
		vig = tabla1.getValuesFromField("convenio",",").split(",");
		ven = tabla1.getValuesFromField("sinconvenio",",").split(",");
		tot= tabla1.getValuesFromField("total",",").split(",");
		
		for(var i=0;i<vig.length;i++){
			v_vig = parseFloat(vig[i]) + parseFloat(v_vig);
		}
		u.convenio.value = v_vig;
		u.convenio.value = "$ "+numcredvar(u.convenio.value);
		esNan('convenio');
		
		for(var i=0;i<ven.length;i++){
			v_ven = parseFloat(ven[i]) + parseFloat(v_ven);			
		}
		u.sinconvenio.value = v_ven;
		u.sinconvenio.value = "$ "+numcredvar(u.sinconvenio.value);
		esNan('sinconvenio');
		
		for(var i=0;i<tot.length;i++){
			v_tot = parseFloat(tot[i]) + parseFloat(v_tot);		
		}
		u.total.value = v_tot;
		u.total.value = "$ "+numcredvar(u.total.value);
		esNan('total');
	}else{
		var obj = new Object();
		obj.sucursal	= "";
		obj.convenio 	= "0";
		obj.sinconvenio = "0";
		obj.total 		= "0";
		tabla1.add(obj);
		u.total.value = "$ 0.00";
		u.sinconvenio.value = "$ 0.00";
		u.convenio.value = "$ 0.00";
	}
	}
	function esNan(caja){	
		if(document.getElementById(caja).value.replace("$ ","").replace(/,/g,"")=="NaN"){
			document.getElementById(caja).value = "";
		}
	}	
	
	function obtenerConvenio(){
		var arr = tabla1.getSelectedRow();
		parent.document.all.barratabs_contenedor_id1.disabled=false;	
		parent.document.all.iframe_id1.src="convenio.php?sucursal="+arr.sucursal+"&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value;
		parent.tabs.seleccionar(1);
		parent.cn.agregarDireccion(0);
	}
	
	function obtenerSConvenio(){
		var arr = tabla1.getSelectedRow();	
		parent.document.all.barratabs_contenedor_id10.disabled=false;	
		parent.document.all.iframe_id10.src="sinconvenio.php?sucursal="+arr.sucursal+"&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&tipo=2";
		parent.tabs.seleccionar(10);
		parent.cn.agregarDireccion(1);
		parent.tabs.moverManual(-850);
	}
	
	function obtenerTotal(){
		var arr = tabla1.getSelectedRow();	
		parent.document.all.barratabs_contenedor_id1.disabled=false;	
		parent.document.all.iframe_id1.src="convenio.php?sucursal="+arr.sucursal+"&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value;
		parent.tabs.seleccionar(1);
		parent.cn.agregarDireccion(0);
	}
	
	function tipoImpresion(valor){
		if(valor=="Archivo"){			
			window.open("http://www.pmmentuempresa.com/web/general/venta/generarExcelVenta.php?accion=1&titulo=REPORTE DE VENTAS&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value);			
		}
	}
</script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">

  <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="426"><table width="454" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="33">Fecha:</td>
            <td width="100"><input name="fechainicio" type="text" class="Tablas" id="fechainicio" style="width:100px" value="<?=$fecha ?>" onKeyUp="mascara(this,'/',patron,true)"/></td>
            <td width="32"><div class="ebtn_calendario" onClick="displayCalendar(document.all.fechainicio,'dd/mm/yyyy',this)"></div></td>
            <td width="15">Al:</td>
            <td width="100"><input name="fechafin" type="text" class="Tablas" id="fechafin" style="width:100px" value="<?=$fecha ?>" onKeyUp="mascara(this,'/',patron,true)"/></td>
            <td width="43"><div class="ebtn_calendario" onClick="displayCalendar(document.all.fechafin,'dd/mm/yyyy',this)"></div></td>
            <td width="103"><img src="../../../img/Boton_Generar.gif" width="74" height="20" align="absbottom" style="cursor:pointer" onClick="generar()" /></td>
          </tr>
      </table></td>
    </tr>
  <td width="426"><table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">
  </table>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="620" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="151" align="center">Total Convenio </td>
        <td width="198" align="center">Total Sin Convenio </td>
        <td width="108" align="center">Total</td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td width="143"><div align="center">Total Gral:</div></td>
        <td width="151" align="center"><span class="style31">
          <input name="convenio" type="text" class="Tablas" id="convenio" readonly="" style="text-align:right;background-color:#FFFF99; width:100px"  />
        </span></td>
        <td width="198" align="center"><span class="style31">
          <input name="sinconvenio" type="text" class="Tablas" id="sinconvenio" readonly="" style="text-align:right;background-color:#FFFF99; width:100px"/>
        </span></td>
        <td width="108" align="center"><span class="style31">
          <input name="total" type="text" class="Tablas" id="total" readonly="" style="text-align:right;background-color:#FFFF99; width:100px"  />
        </span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="right"><table width="74" align="center">
      <tr>
        <td width="66" ><div class="ebtn_imprimir" onClick="abrirVentanaFija('../../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  </table>
</form>
</body>
</html>