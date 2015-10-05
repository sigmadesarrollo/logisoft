<?	session_start();



	require_once('../../Conectar.php');



	$l = Conectarse('webpmm');



	$s = "SELECT YEAR(CURDATE()) AS fecha";



	$r = mysql_query($s,$l) or die($s);



	$f = mysql_fetch_object($r);



	$fecha = $f->fecha;



?>



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<link  href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>



<SCRIPT src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>



<script src="../../javascript/ClaseTabla.js"></script>



<script src="../../javascript/ajax.js"></script>



<script src="../../javascript/funciones.js"></script>



<script src="../../javascript/ClaseMensajes.js"></script>



<script src="../../javascript/funcionesDrag.js"></script>



<script src="../../javascript/ClaseTabs.js"></script>



<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>



<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>



<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>



<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">



<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">



<script>



	var tabla1 		= new ClaseTabla();



	var	u			= document.all;



	var mens 		= new ClaseMensajes();



	var tabs 		= new ClaseTabs();



	mens.iniciar('../../javascript',true);



	



	tabla1.setAttributes({



		nombre:"detalle",



		campos:[



		{nombre:"SUCURSAL", medida:120, alineacion:"left", datos:"sucursal"},



		{nombre:"CONVENIOS_VIGENTES", medida:130, onDblClick:"obtenerVigentesDblclick", alineacion:"center",  datos:"vigentes"},



		{nombre:"CONVENIOS_VENCIDOS", medida:130, onDblClick:"obtenerVencidosDblclick", alineacion:"center",  datos:"vencidos"},



		{nombre:"TOTALES_CONVENIOS", medida:130, onDblClick:"obtenerTotalConveniosDblclick", alineacion:"center", datos:"total"},			



		{nombre:"IMPORTE", medida:130,  tipo:"moneda", alineacion:"right", datos:"importe"},



		{nombre:"IDSUCURSAL", medida:4,  tipo:"oculto", alineacion:"right", datos:"idsucursal"}



		],



		filasInicial:30,



		alto:195,



		seleccion:true,



		ordenable:false,



		//eventoDblClickFila:"verRecoleccion()",



		nombrevar:"tabla1"



	});



	



	window.onload = function(){



		tabla1.create();



		u.sucursal_hidden.value = "0";



		u.sucursal.value = "TODAS";



		



		parent.tabs.agregarTabs('VENTAS POR CONVENIOS',1,"importe.php?sucursal=0&fecha="+u.fecha.value);



		parent.document.all.barratabs_contenedor_id1.disabled=true;	



		parent.tabs.agregarTabs('CONVENIOS VIGENTES',2,"reporteConvenioVigentes.php?sucursal=0&fecha="+u.fecha.value);



		parent.document.all.barratabs_contenedor_id2.disabled=true;	



		parent.tabs.agregarTabs('TIPO DE CONVENIOS',3,"reporteConveniosTotales.php?sucursal=0&fecha="+u.fecha.value);



		parent.document.all.barratabs_contenedor_id3.disabled=true;	



		parent.tabs.agregarTabs('CONVENIOS VENCIDOS',4,"reporteConvenioVencido.php?sucursal=0&fecha="+u.fecha.value);



		parent.document.all.barratabs_contenedor_id4.disabled=true;	



		parent.tabs.agregarTabs('VENTAS COVENIO FACTURADAS',5,"facturado.php?sucursal=0&fecha="+u.fecha.value+"&inicio=1");



		parent.document.all.barratabs_contenedor_id5.disabled=true;	



		parent.tabs.agregarTabs('VENTAS CONVENIO SIN FACTURAR',6,"nofacturado.php?sucursal=0&fecha="+u.fecha.value);



		parent.document.all.barratabs_contenedor_id6.disabled=true;	



		parent.tabs.agregarTabs('DESGLOZE CONVENIO',7,"mostrarDesglozeConvenio.php?cliente=0&tipo=paquete");



		parent.document.all.barratabs_contenedor_id7.disabled=true;	



		parent.tabs.agregarTabs('HISTORIAL',8,"reporteHistorialCliente.php?cliente=0&fecha="+u.fecha.value);



		parent.document.all.barratabs_contenedor_id8.disabled=true;	



		parent.tabs.agregarTabs('PREPAGADAS SIN FACTURAR',9,"pagadassinfacturar.php?sucursal=0&fecha="+u.fecha.value+"&inicio=1");



		parent.document.all.barratabs_contenedor_id9.disabled=true;	



		parent.tabs.agregarTabs('CONSIGNACION SIN FACTURAR',10,"consignacionsinfacturas.php?sucursal=0&fecha="+u.fecha.value+"&inicio=1");



		parent.document.all.barratabs_contenedor_id10.disabled=true;	



		parent.tabs.agregarTabs('FACTURADO',11,"sinfactura.php?sucursal=0&fecha="+u.fecha.value+"&inicio=1");



		parent.document.all.barratabs_contenedor_id11.disabled=true;	



		parent.tabs.seleccionar(0);



	}



	



	function devolverSucursal(){



		if(u.sucursal_hidden.value==""){



			setTimeout("devolverSucursal()",500);



		}



		obtenerDetalle();



	}



	function obtenerDetalle(){



		if(u.sucursal.value == ""){



			alerta("Debe capturar Sucursal","¡Atención!","sucursal");



		}else{



			consultaTexto("mostrarDetalle","consultasClientes.php?accion=1&sucursal="+u.sucursal_hidden.value+"&fecha="+u.fecha.value);	



		}				



	}



	function mostrarDetalle(datos){



		if(datos.indexOf("nada")<0){



			var obj = eval(convertirValoresJson(datos));			



			tabla1.setJsonData(obj);



			var vig = ""; var ven = ""; var tot = ""; var imp = "";



			v_vig = 0; v_ven = 0; v_tot = 0; v_imp = 0;



			



			vig = tabla1.getValuesFromField("vigentes",",").split(",");



			ven = tabla1.getValuesFromField("vencidos",",").split(",");



			tot = tabla1.getValuesFromField("total",",").split(",");



			imp = tabla1.getValuesFromField("importe",",").split(",");		



			



			for(var i=0;i<vig.length;i++){



				v_vig = parseFloat(vig[i]) + parseFloat(v_vig);



			}



			u.vigentes.value = v_vig;



			esNan('vigentes');



			



			for(var i=0;i<ven.length;i++){



				v_ven = parseFloat(ven[i]) + parseFloat(v_ven);		



			}



			u.vencidos.value = v_ven;



			esNan('vencidos');



	



			for(var i=0;i<tot.length;i++){



				v_tot = parseFloat(tot[i]) + parseFloat(v_tot);		



			}



			u.totales.value = v_tot;



			esNan('totales');



			



			for(var i=0;i<imp.length;i++){



				v_imp = parseFloat(imp[i]) + parseFloat(v_imp);			



			}



			u.importes.value = v_imp;	



			u.importes.value = "$ "+numcredvar(u.importes.value);



			esNan('importes');



		}else{



			var obj = new Object();



			obj.sucursal	=	"";



			obj.vigentes	=	"0";



			obj.vencidos	=	"0";



			obj.total		=	"0";



			obj.importe		=	"0";



			tabla1.add(obj);



			u.vigentes.value =	"0";



			u.vencidos.value =	"0";



			u.totales.value  =	"0";



			u.importes.value =	"$ 0.00";



		}



	}



	



	function esNan(caja){



		if(document.getElementById(caja).value.replace("$ ","").replace(/,/g,"")=="NaN"){



			document.getElementById(caja).value = "";



		}



	}



	



	function obtenerImporte(){



		parent.document.all.barratabs_contenedor_id1.disabled=false;	



		parent.document.all.iframe_id1.src="importe.php?sucursal="+u.sucursal_hidden.value+"&fecha="+u.fecha.value;



		parent.tabs.seleccionar(1);



	}



	



	function obtenerVigentes(){

			parent.document.all.barratabs_contenedor_id2.disabled=false;	

			alert(u.sucursal_hidden.value);

			alert(u.fecha.value);

			parent.document.all.iframe_id2.src="reporteConvenioVigentes.php?sucursal="+u.sucursal_hidden.value+"&fecha="+u.fecha.value;

		parent.tabs.seleccionar(2);

	}



	



	function obtenerVigentesDblclick(){



		var arr = tabla1.getSelectedRow();



		parent.document.all.barratabs_contenedor_id2.disabled=false;	



		parent.document.all.iframe_id2.src="reporteConvenioVigentes.php?sucursal="+arr.idsucursal+"&fecha="+u.fecha.value;



		parent.tabs.seleccionar(2);



	}



	



	function obtenerVencidos(){



			parent.document.all.barratabs_contenedor_id4.disabled=false;	



			parent.document.all.iframe_id4.src="reporteConvenioVencido.php?sucursal="+u.sucursal_hidden.value+"&fecha="+u.fecha.value;



		parent.tabs.seleccionar(4);



	}



	



	function obtenerVencidosDblclick(){



		var arr = tabla1.getSelectedRow();



		parent.document.all.barratabs_contenedor_id4.disabled=false;	



		parent.document.all.iframe_id4.src="reporteConvenioVencido.php?sucursal="+arr.idsucursal+"&fecha="+u.fecha.value;



		parent.tabs.seleccionar(4);



	}



	



	function obtenerTotalConvenios(){



		parent.document.all.barratabs_contenedor_id3.disabled=false;	



		parent.document.all.iframe_id3.src="reporteConveniosTotales.php?sucursal="+u.sucursal_hidden.value+"&fecha="+u.fecha.value;



		parent.tabs.seleccionar(3);



	}



	



	function obtenerTotalConveniosDblclick(){



	var arr = tabla1.getSelectedRow();



	parent.document.all.barratabs_contenedor_id3.disabled=false;	



	parent.document.all.iframe_id3.src="reporteConveniosTotales.php?sucursal="+arr.idsucursal+"&fecha="+u.fecha.value;



		parent.tabs.seleccionar(3);



	}



	



	function mostrarFecha(fecha){



		var mes = fecha.split("/");



		switch (mes[1]){



			case "01" || "1":



				fecha = "AL: "+mes[0]+" DE ENERO DE "+mes[2];



				return fecha;



			break;



			case "02" || "2":



				fecha = "AL: "+mes[0]+" DE FEBRERO DE "+mes[2];



				return fecha;



			break;



			case "03" || "3":



				fecha = "AL: "+mes[0]+" DE MARZO DE "+mes[2];



				return fecha;



			break;



			case "04" || "4":



				fecha = "AL: "+mes[0]+" DE ABRIL DE "+mes[2];



				return fecha;



			break;



			case "05" || "5":



				fecha = "AL: "+mes[0]+" DE MAYO DE "+mes[2];



				return fecha;



			break;



			case "06" || "6":



				fecha = "AL: "+mes[0]+" DE JUNIO DE "+mes[2];



				return fecha;



			break;



			case "07" || "7":



				fecha = "AL: "+mes[0]+" DE JULIO DE "+mes[2];



				return fecha;



			break;



			case "08" || "8":



				fecha = "AL: "+mes[0]+" DE AGOSTO DE "+mes[2];



				return fecha;



			break;



			case "09" || "9":



				fecha = "AL: "+mes[0]+" DE SEPTIEMBRE DE "+mes[2];



				return fecha;



			break;



			case "10":



				fecha = "AL: "+mes[0]+" DE OCTUBRE DE "+mes[2];



				return fecha;



			break;



			case "11":



				fecha = "AL: "+mes[0]+" DE NOVIEMBRE DE "+mes[2];



				return fecha;



			break;



			case "12":



				fecha = "AL: "+mes[0]+" DE DICIEMBRE DE "+mes[2];



				return fecha;



			break;



		}



	}



	function tipoImpresion(valor){



		if(valor=="Archivo"){



			window.open("http://www.pmmentuempresa.com/web/general/clientes/generarExcel.php?accion=1&titulo=CONVENIOS POR SUCURSAL&sucursal="+u.sucursal_hidden.value+"&fecha="+u.fecha.value);



		}



	}







</script>



<script src="../../javascript/ajaxlist/ajax-dynamic-list.js" ></script>



<script src="../../javascript/ajaxlist/ajax.js"></script>



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Documento sin t&iacute;tulo</title>



<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />



<style type="text/css">



/* Big box with list of options */



	#ajax_listOfOptions{



		position:absolute;	/* Never change this one */



		width:175px;	/* Width of box */



		height:250px;	/* Height of box */



		overflow:auto;	/* Scrolling features */



		border:1px solid #317082;	/* Dark green border */



		background-color:#FFF;	/* White background color */



		text-align:left;



		font-size:0.9em;



		z-index:100;



	}



	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */



		margin:1px;		



		padding:1px;



		cursor:pointer;



		font-size:0.9em;



	}



	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */



		



	}



	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */



		background-color:#317082;



		color:#FFF;



	}



	#ajax_listOfOptions_iframe{



		background-color:#F00;



		position:absolute;



		z-index:5;



	}



	



	form{



		display:inline;



	}



<!--



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



@import url("../../creditoycobranza/Tablas.css");



.Estilo4 {font-size: 12px}



.Balance {background-color: #FFFFFF; border: 0px none}



.Balance2 {background-color: #DEECFA; border: 0px none;}



-->



</style>



</head>



<body>



<form id="form1" name="form1" method="post" action="">



<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">



<tr>



  <td><table width="426" border="0" cellpadding="0" cellspacing="0">



    <tr>



      <td width="63"> A&ntilde;o:</td>



      <td width="162"><select name="fecha" class="Tablas" id="fecha" style="width:100px">



          <?	$s = "SELECT MIN(YEAR(fecha)) AS primera, YEAR(CURDATE())AS actual FROM generacionconvenio";



						$ss = mysql_query($s,$l) or die($s);



						$fs = mysql_fetch_object($ss);



					



						for($i=$fs->primera;$i<=$fs->actual;$i++){



							?>



          <option value="<?=$i ?>"<? if($fecha==$i){ echo 'selected';} ?>>



          <?=$i ?>



          </option>



          <?	} ?>



      </select></td>



      <td width="162">Sucursal:



        <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$_GET[sucursal] ?>"></td>



      <td width="200"><span class="Tablas">



        <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:150px" value="<?=$f->sucursal ?>" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'ajax-list-sucursal.php')" onKeyPress="if(event.keyCode==13){devolverSucursal();}"



        />



      </span></td>



      <td width="200"><div class="ebtn_Generar" onClick="obtenerDetalle()"></div></td>



    </tr>



  </table></td>



</tr>



<tr>



      <td width="426">&nbsp;</td>



    </tr>



  <td width="426"><table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">



  </table>



  <tr>



    <td>&nbsp;</td>



  </tr>



  <tr>



    <td><table width="515" border="0" cellspacing="0" cellpadding="0">



      <tr>



        <td>&nbsp;</td>



        <td>&nbsp;</td>



        <td align="center">Total Con. Vigentes </td>



        <td align="center">Total Con.Vencidos </td>



        <td align="center">Totales Convenios </td>



        <td align="center">Total Importe </td>



      </tr>



      <tr>



        <td>&nbsp;</td>



        <td><div align="right">Totales</div></td>



        <td align="center"><input name="vigentes" onDblClick="" type="text" class="Tablas" style="cursor:pointer;  text-align:center;background-color:#FFFF99; width:90px" id="vigentes" readonly="" title="Convenios Vigentes"/></td>



        <td align="center"><input name="vencidos" onDblClick="" type="text" class="Tablas" style="cursor:pointer; background-color:#FFFF99; width:90px; text-align:center;" id="vencidos" readonly="" title="Convenios Vencidos" /></td>



        <td align="center"><input name="totales" onDblClick="" type="text" class="Tablas" style="cursor:pointer; background-color:#FFFF99; width:90px; text-align:center;" id="totales" readonly="" title="Tipo de Convenio" /></td>



        <td align="center"><input name="importes" onDblClick="" style="cursor:pointer; background-color:#FFFF99; width:90px; text-align:right;" type="text" class="Tablas" id="importes" readonly="" title="Ventas Por Convenio" /></td>



      </tr>



      <tr>



        <td width="15">&nbsp;</td>



        <td width="104">&nbsp;</td>



        <td width="100" align="center"><a onClick="obtenerVigentes()" href="#">Ver Convenios Vigentes</a href></td>



        <td width="100" align="center"><a onClick="obtenerVencidos()" href="#">Ver Convenios Vencidos</a href></td>



        <td width="100" align="center"><a onClick="obtenerTotalConvenios()" href="#">Ver Tipo de Convenios</a href></td>



        <td width="100" align="center"><a onClick="obtenerImporte()" href="#">Ver Ventas Por Convenio</a href></td>



      </tr>



    </table></td>



  </tr>



  <tr>



    <td align="right"><label></label>



        <table width="74" align="center">



          <tr>



            <td width="66" ><div class="ebtn_imprimir" onClick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>



          </tr>



        </table>



        <label></label></td>



  </tr>



  </table>



<p>&nbsp;</p>



</form>



</body>



<script>



	//parent.frames[1].document.getElementById('titulo').innerHTML = 'RM CLIENTES';



</script>



</html>