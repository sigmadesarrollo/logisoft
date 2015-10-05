<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

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

			{nombre:"SUCURSAL", medida:100,  alineacion:"center", datos:"sucursal"},

			{nombre:"CONTADO", medida:110, onDblClick:"obtenerContado", tipo:"moneda", alineacion:"center", datos:"contado"},

			{nombre:"CREDITO", medida:110, tipo:"moneda", alineacion:"center",  datos:"credito"},

			{nombre:"COB-CONTADO", medida:110, tipo:"moneda", alineacion:"center",  datos:"cobcontado"},

			{nombre:"COB-CREDITO", medida:110, tipo:"moneda", alineacion:"center", datos:"cobcredito"},

			{nombre:"TOTAL", medida:110, onDblClick:"obtenerTotal", tipo:"moneda", alineacion:"center", datos:"total"}

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

		consultaTexto("mostrarDetalle","consultasVentas.php?accion=3&sucursal=<?=$_GET[sucursal];?>&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&tipo=<?=$_GET[tipo] ?>&m="+Math.random());

	}

	function mostrarDetalle(datos){

		if(datos.indexOf("no encontro")>-1){

			var obj = eval(convertirValoresJson(datos));

			var obje= new Object();

			for(var i=0;i<obj.length;i++){

				obje.contado	=	obj[i].contados;

				obje.credito	=	obj[i].credito;

				obje.cobcontado	=	obj[i].cobcontado;

				obje.cobcredito	=	obj[i].cobcredito;

				obje.sucursal	=	obj[i].sucursal;

				obje.total		=	parseFloat(obje.contado) + parseFloat(obje.credito) + parseFloat(obje.cobcontado) + parseFloat(obje.cobcredito); 

				tabla1.add(obje);

			}

		}else{

			var obj = new Object();

			obj.contado		= "0";

			obj.credito		= "0";

			obj.cobcontado	= "0";

			obj.cobcredito	= "0";

			obj.total		= "0";

			tabla1.add(obj);

		}

	}

	function obtenerContado(){

		var arr = tabla1.getSelectedRow();

		if ("<?=$_GET[tipo] ?>"=="1"){

		parent.document.all.barratabs_contenedor_id5.disabled=false;	

		parent.document.all.iframe_id5.src="contado.php?sucursal="+arr.sucursal+"&cliente="+arr.cliente+"&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&tipo=<?=$_GET[tipo] ?>";

		/*parent.tabs.seleccionar(5);

		parent.cn.agregarDireccion(4);

		parent.tabs.moverManual(-600);*/

		

		}else if ("<?=$_GET[tipo] ?>"=="2"){

			parent.document.all.barratabs_contenedor_id12.disabled=false;	

			parent.document.all.iframe_id12.src="contado.php?sucursal="+arr.sucursal+"&cliente="+arr.cliente+"&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&tipo=<?=$_GET[tipo] ?>";

/*		parent.tabs.seleccionar(12);

		parent.cn.agregarDireccion(11);

		parent.tabs.moverManual(-600);*/

		}	

	}

	

	function obtenerTotal(){

		var arr = tabla1.getSelectedRow();

		if ("<?=$_GET[tipo] ?>"=="1"){

			parent.document.all.barratabs_contenedor_id6.disabled=false;	

		parent.document.all.iframe_id6.src="total.php?sucursal="+arr.sucursal+"&cliente="+arr.cliente+"&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&tipo=<?=$_GET[tipo] ?>";

		/*parent.tabs.seleccionar(6);

		parent.cn.agregarDireccion(5);

		parent.tabs.moverManual(-600);*/

		}else{		

		parent.document.all.barratabs_contenedor_id11.disabled=false;	

		parent.document.all.iframe_id11.src="total.php?sucursal="+arr.sucursal+"&cliente="+arr.cliente+"&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&tipo=<?=$_GET[tipo] ?>";

		/*parent.tabs.seleccionar(11);

		parent.cn.agregarDireccion(10);

		parent.tabs.moverManual(-600);*/

		}				

	}

	

	function tipoImpresion(valor){

		if(valor=="Archivo"){			

			window.open("http://www.pmmentuempresa.com/web/general/venta/generarExcelVenta.php?accion=3&sucursal=<?=$_GET[sucursal]; ?>&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&m="+Math.random()+"&titulo=REPORTE POR CONDICION DE PAGO");			

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

  <table width="690" border="0" align="center" cellpadding="0" cellspacing="0">

    

    <tr>

      <td>

        <table width="310" id="detalle" border="0" cellpadding="0" cellspacing="0">

        </table>

      

     </td>

    </tr>

    <tr>

      <td align="right"><table width="74" align="center">

        <tr>

          <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>

        </tr>

      </table></td>

    </tr>

  </table>

</form>

</body>

</html>