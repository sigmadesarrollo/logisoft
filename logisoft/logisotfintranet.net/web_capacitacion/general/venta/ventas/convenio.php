<?
//	echo "convenio";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../../javascript/ClaseTabla.js"></script>
<script src="../../../javascript/ajax.js"></script>
<script src="../../../javascript/funciones.js"></script>
<script language="javascript1.1" src="../../../javascript/ClaseMensajes.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();	
	mens.iniciar('../../../javascript');
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
		{nombre:"SUCURSAL", medida:130, alineacion:"left", datos:"sucursal"},
		{nombre:"NORMALES", medida:130, onDblClick:"obtenerNormal", tipo:"moneda", alineacion:"center",  datos:"normales"},
		{nombre:"PREPAGADAS", medida:130, onDblClick:"obtenerPrepagada", tipo:"moneda", alineacion:"center",  datos:"prepagadas"},
		{nombre:"CONSIGNACION", medida:130, onDblClick:"obtenerConsignacion", tipo:"moneda", alineacion:"center", datos:"consignacion"},
		{nombre:"TOTAL", medida:130, tipo:"moneda", alineacion:"center", datos:"total"}
		],
		filasInicial:30,
		alto:320,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"verRecoleccion()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerDetalle();
	}
	function obtenerDetalle(){
		consultaTexto("mostrarDetalle","consultasVentas.php?accion=2&sucursal=<?=$_GET[sucursal]; ?>&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&m="+Math.random());
	}
	function mostrarDetalle(datos){		
		if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);		
		}else{
			var obj = new Object();
			obj.sucursal		= "";
			obj.normales		= "0";
			obj.prepagadas		= "0";
			obj.consignacion	= "0";
			obj.total			= "0";
			tabla1.add(obj);
		}
	}
	
	function obtenerNormal(){
		var arr = tabla1.getSelectedRow();	
		parent.document.all.barratabs_contenedor_id2.disabled=false;	
		parent.document.all.iframe_id2.src="sinconvenio.php?&tipo=1&sucursal="+arr.sucursal+"&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>";
		parent.tabs.seleccionar(2);
		parent.cn.agregarDireccion(1);
	}
	
	function obtenerPrepagada(){
		var arr = tabla1.getSelectedRow();		
		parent.document.all.barratabs_contenedor_id3.disabled=false;	
		parent.document.all.iframe_id3.src="../ventasprepagado/prepagadas.php?sucursal="+arr.sucursal+"&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>";
		parent.tabs.seleccionar(3);
		parent.cn.agregarDireccion(2);
	}
	
	function obtenerConsignacion(){
		var arr = tabla1.getSelectedRow();	
		parent.document.all.barratabs_contenedor_id4.disabled=false;	
		parent.document.all.iframe_id4.src="../ventasconsignacion/consignacion.php?sucursal="+arr.sucursal		+"&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>";
		parent.tabs.seleccionar(4);
		parent.cn.agregarDireccion(3);
	}
	
	function tipoImpresion(valor){
		if(valor=="Archivo"){			
			window.open("http://www.pmmentuempresa.com/web/general/venta/generarExcelVenta.php?accion=2&sucursal=<?=$_GET[sucursal]; ?>&fechainicio=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&m="+Math.random()+"&titulo=REPORTE POR TIPO DE VENTA");			
		}
	}
	
</script>
<script src="../../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../../FondoTabla.css" rel="stylesheet" type="text/css" />
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
<link href="../../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="680" border="0" align="center" cellpadding="0" cellspacing="0">
  
    <tr>
      <td>
        <table width="310" id="detalle" border="0" cellpadding="0" cellspacing="0">
        </table>
        
      </td>
    </tr>
    <tr>
      <td align="right"><table width="74" align="center">
        <tr>
          <td width="66" ><div class="ebtn_imprimir" onClick="abrirVentanaFija('../../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>