<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion
	FROM catalogosucursal cs ORDER BY cs.descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}
		$desc = substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
<link href="../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
<link href="../javascript/estiloclasetablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/jquery-1.4.2.min.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/DataSet.js"></script>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">
<script>
	var tabla1 	= new ClaseTabla();
	var	u		= document.all;
	var pag1_cantidadporpagina = 10;
	//var DS1 	= new DataSet();
	var mens	= new ClaseMensajes();
	var desc	= new Array(<?php echo $desc; ?>);
	
	mens.iniciar('../javascript');
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"ENT. EL MISMO DÍA", medida:70, alineacion:"center", datos:"undiaead"},
			{nombre:"1 DIA %", medida:30, alineacion:"center", datos:"undiaporc"},
			{nombre:"ENT. MAS DE 2 DÍAS", medida:70, alineacion:"center", datos:"dosdiaead"},
			{nombre:"2 DIAS %", medida:30, alineacion:"center", datos:"dosdiaporc"},
			{nombre:"GUÍAS PEND. ENTREGA", medida:70, alineacion:"center", datos:"faltanteead"},
			{nombre:"FALTANTE %", medida:30, alineacion:"center", datos:"faltanteporc"},
			{nombre:"ENT_EL MISMO DÍA", medida:70, alineacion:"center", datos:"undiarec"},
			{nombre:"% 1 DIA", medida:30, alineacion:"center", datos:"undiaporc2"},
			{nombre:"ENT_MAS DE 2 DÍAS", medida:70, alineacion:"center", datos:"dosdiasrec"},
			{nombre:"% 2 DIAS", medida:30, alineacion:"center", datos:"dosdiaporc2"},
			{nombre:"GUÍAS_PEND. ENTREGA", medida:70, alineacion:"center", datos:"faltanterec"},
			{nombre:"% FALTANTE", medida:30, alineacion:"center", datos:"faltanteporc2"}
		],
		filasInicial:15,
		alto:180,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
	/*	DS1.crear({
			'paginasDe':30,
			'objetoTabla':tabla1,
			'objetoPaginador':document.getElementById('detalle_pag'),
			'nombreVariable':'DS1',
			'ubicacion':'../',
			'funcionOrdenar':function(a,b){
				return parseInt(a.guia.toString().substring(0,12)+a.guia.toString().charCodeAt(12))-
				parseInt(b.guia.toString().substring(0,12)+b.guia.toString().charCodeAt(12))
			}
		});*/
		if(<? echo $_SESSION[IDSUCURSAL]?>==1){
			document.getElementById('mostrarsucursal').style.visibility = 'visible';
		}
		u.sucursal_hidden.value=<? echo $_SESSION[IDSUCURSAL]?>;
	}
	
	function obtenerDetalle(){
		if(u.checktodas.checked){
			var mas = "&sucursal=0";
		}else{
			var mas = "&sucursal="+u.sucursal_hidden.value;
		}
		consultaTexto("resTabla5","reporteProductividad_con.php?accion=1&fecha="+u.fecha.value+mas+"&mat"+Math.random());
	}
	
	function resTabla5(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		tabla1.setJsonData(obj);
	}
	
	function bloquearSucursal(valor){
		u.sucursal.readOnly=valor;
		u.sucursal.style.backgroundColor=(valor)?"#FFFF99":"";
		u.sucursal.value = "";
		u.sucursal_hidden.value = "";
		document.getElementById('imagenBuscarSucursal').style.display = (valor)?'none':'';
	}
	
	function limpiar(){
		u.fecha.value = "";
		bloquearSucursal(true);
	}
	
	function imprimirDetalle(){
		if(u.checktodas.checked){
			var mas = "&sucursal=0";
		}else{
			var mas = "&sucursal="+u.sucursal_hidden.value;
		}
		if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("https://www.pmmintranet.net/web_capacitacion/reportesWeb/productividad_Excel.php?fecha="+u.fecha.value+mas+"&mat"+Math.random());
		}
		//window.open("localhost/index/web_capacitacion/reportesWeb/productividad_Excel.php?fecha="+u.fecha.value+mas+"&mat"+Math.random());
		
	}
</script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="603" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Reporte de Productividad</td>
    </tr>
    <tr>
    	<td>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td><div id="mostrarsucursal" style="visibility:hidden;">
        <table border="0" cellpadding="0" cellspacing="0">
        	  <tr class="Tablas">
        	    <td width="56">Sucursal:
        	      <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="" /></td>
        	    <td width="22"><input type="checkbox" name="checktodas" onclick="bloquearSucursal(this.checked);" /></td>
        	    <td width="48">Todas</td>
        	    <td width="251"><input name="sucursal" type="text" id="sucursal" style="width:200px; background:#FFFF99" readonly="readonly" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value='';}}" onblur="document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value=''; }" />
        	      <img src="../img/Buscar_24.gif" id="imagenBuscarSucursal" width="24" height="23" align="absbottom" style="cursor:pointer; display:none" onclick="mens.show('../buscadores_generales/buscarsucursal.php', 600, 450, 'ventana', 'Busqueda');" /></td>
        	    <td width="50">&nbsp;</td>
        	    <td width="117">&nbsp;</td>
        	    <td width="82">&nbsp;</td>
      	    </tr>
      	  </table></div>
          <table border="0" cellpadding="0" cellspacing="0">
        	  <tr class="Tablas">
        	    <td width="21">&nbsp;</td>
        	    <td width="55">Fecha:</td>
        	    <td width="47"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=date('d/m/Y')?>" onkeypress="if(event.keyCode==13){obtenerDetalle();}"/></td>
        	    <td width="19">&nbsp;</td>
        	    <td width="77"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
        	    <td width="300">&nbsp;</td>
        	    <td width="81"><img src="../img/Boton_Generar.gif" onclick="obtenerDetalle()" /></td>
      	    </tr>
      	  </table>
        </td>
    </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
			<td align="center" width="220" style="font-family: tahoma; font-size: 12px; font-weight: bold;">EAD</td>
			<td align="center" width="220" style="font-family: tahoma; font-size: 12px; font-weight: bold;">RECOLECCION</td>
		 </tr>
		</table>
	</td>
  </tr>
  <tr>
    <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">              
            </table></td>
          </tr>          
        </table>
	</div>
	</td>
  </tr>
  <tr>
    <td align="right"><img src="../img/Boton_Imprimir.gif" onclick="imprimirDetalle()" />&nbsp;&nbsp;
	<img style="cursor:pointer" src="../img/Boton_Nuevo.gif" onclick="limpiar()" /></td>
  </tr>
</table>
</td>
</tr>
</table>
</form>
<?
	$raiz = "../";
	$funcion = "traerAlcliente";
	$nombreBuscador = "buscadorClientes";
	$funcionMostrar = "mostrarBuscador";
	$funcionOcultar = "ocultarBuscador";
	include("../buscadores_generales/buscadorIncrustado.php");
?>
</body>
</html>
