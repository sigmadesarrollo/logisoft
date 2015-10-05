<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">





<html xmlns="http://www.w3.org/1999/xhtml">





<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />





<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>





<SCRIPT type="text/javascript" src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>





<script src="../../javascript/ClaseTabla.js"></script>





<script src="../../javascript/ajax.js"></script>





<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />





<script src="../../javascript/ClaseMensajes.js"></script>





<script src="../../javascript/funcionesDrag.js"></script>





<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />





<script>





	var tabla1 		= new ClaseTabla();





	var	u		= document.all;





	tabla1.setAttributes({





		nombre:"detalle",





		campos:[





			{nombre:"GUIA", medida:150,onDblClick:"Danosyfaltantes", alineacion:"center",  datos:"guia"},





			{nombre:"FECHA", medida:100, onDblClick:"Danosyfaltantes",alineacion:"center",  datos:"fecha"},





			{nombre:"UNIDAD ", medida:290, onDblClick:"Danosyfaltantes",alineacion:"center", datos:"unidad"},				





			{nombre:"ESTADO", medida:120, onDblClick:"Danosyfaltantes",alineacion:"center", datos:"estado"}





		],





		filasInicial:30,





		alto:320,





		seleccion:true,





		ordenable:false,





		nombrevar:"tabla1"





	});





	





	window.onload = function(){





		tabla1.create();





		obtenerDetalle();





	}





	





	/*****/





	function obtenerDetalle(){





		consultaTexto("mostrarDetalle","logistica_con.php?accion=7&guia=<?=$_GET[guia]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&valram="+Math.random());





	}





	





	function mostrarDetalle(datos){	





				var objeto = eval(convertirValoresJson(datos));





		for(var i=0;i<objeto.length;i++){





			var obj	   		= new Object();





			obj.guia 		= objeto[i].guia;





			obj.fecha 		= objeto[i].fecha;





			obj.unidad 		= objeto[i].unidad;





			obj.estado		= objeto[i].estado;





			tabla1.add(obj);





		}





	}





	





	function Danosyfaltantes(){





		//var g = tabla1.getValSelFromField('guia','GUIA');





		parent.tabs.agregarTabs("Reporte de Daños",2,'RmDanosFaltantes.php?guia=<?=$_GET[guia]?>');





	}





	





	function tipoImpresion(valor){





		if(valor=="Archivo"){			





			window.open("http://www.pmmentuempresa.com/web/general/logistica/generarExcelLogistica.php?accion=7&titulo=REPORTE POR TIPO DE INCIDENCIAS&fecha=<?=$_GET[fecha] ?>&fecha2=<?=$_GET[fecha2] ?>&guia=<?=$_GET[guia]?>&valram="+Math.random());			





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





.Estilo41 {font-size: 12px}





-->





</style>





</head>





<body>





<form id="form1" name="form1" method="post" action="">





<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">





  





  <tr>





    <td>





      <table width="426" id="detalle" border="0" cellpadding="0" cellspacing="0">





      </table>





   </td>





  </tr>





  <tr>





    <td align="right"><table width="74" align="center">





      <tr>





        <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>





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