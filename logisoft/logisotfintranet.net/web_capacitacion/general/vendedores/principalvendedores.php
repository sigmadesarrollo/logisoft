<?
	$fechaini=date('d/m/Y');
	$fechafin=date('d/m/Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<link href="../../estilos_estandar.css" />
<script src="../../javascript/ajax.js"></script>
<script language="javascript" src="../../javascript/funcionesDrag.js"></script>
<script language="javascript" src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/jquery.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>
<script>

	jQuery(function($){
		$('#fecha2').mask("99/99/9999");
		$('#fecha').mask("99/99/9999");
	});

	var tabla1 		= new ClaseTabla();
	var	u		= document.all;
	var datosFecha = "";
	var mens = new ClaseMensajes();
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:100, alineacion:"left", datos:"sucursal"},
			{nombre:"CLAVE VENDEDOR", tipo:"oculto",medida:4, alineacion:"center", datos:"clavevendedor"},
			{nombre:"VENDEDOR", medida:250, onDblClick:"agregaventasvendedor",alineacion:"left",  datos:"vendedor"},
			{nombre:"VENTAS", medida:150, onDblClick:"agregaventas" ,alineacion:"right",tipo:"moneda",  datos:"ventas"},			
			{nombre:"VENTAS COBRADAS", onDblClick:"agregaventascobradas",medida:150, alineacion:"right",tipo:"moneda", datos:"ventascobradas"}
		],
		filasInicial:30,
		alto:280,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.cant.value=0;
		u.ventas.value=0.00;
		u.ventasc.value=0.00;
		mens.iniciar('../../javascript',true);
			
		parent.tabs.agregarTabs("GENERADOS POR CONVENIO",1,"ventas.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&vendedor=0&ano=0&mes=0&idvendedor=0&inicio=1");
		parent.document.all.barratabs_contenedor_id1.disabled=true;	

		parent.tabs.agregarTabs("COBRADAS POR VENDEDOR",2,"ventascobradas.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&vendedor=0&ano=0&mes=0&clavevendedor=0&inicio=1");
		parent.document.all.barratabs_contenedor_id2.disabled=true;	

		parent.tabs.agregarTabs("DETALLADA POR VENDEDOR",3,"vendedor.php?clavevendedor=0&vendedor=0&fecha="+u.fecha2.value+"&mes1=0&mes2=0&mes3=0&inicio=1");
		parent.document.all.barratabs_contenedor_id3.disabled=true;	
		
		parent.tabs.agregarTabs("COMISIÓN POR VENDEDOR",4,"totalventascobradas.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=1");	
		parent.document.all.barratabs_contenedor_id4.disabled=true;	
	parent.tabs.seleccionar(0);
	}
	
	function ObtenerDetalle(){
		var f1 = u.fecha.value.split("/");
		var f2 = u.fecha2.value.split("/");
		v_fechaini	= new Date(f1[1]+"/"+f1[0]+"/"+f1[2]);
		v_fechafin	= new Date(f2[1]+"/"+f2[0]+"/"+f2[2]);
	
		if(u.fecha.value=="" || u.fecha2.value==""){
			mens.show("A","Debe capturar "+((u.fecha.value=="")? " fecha inicio" : "fecha fin"),"¡Atención!",((u.fecha.value=="")? "" : "" ));	 	
		}else if(v_fechaini > v_fechafin){
			mens.show("A","La fecha final debe ser mayor a la fecha de inicial","¡Atención!","");
		}else{
			u.cant.value=0;
			u.ventas.value=0.00;
			u.ventasc.value=0.00;
			consultaTexto("mostrardetalle","principal_con.php?accion=1&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value);
		}
	}
	
	function mostrardetalle(datos){	
		if (datos!=0) {
			var Cant=0;
			var ventas=0;
			var ventascobradas=0;
				tabla1.clear();
				var objeto = eval(convertirValoresJson(datos));
				for(var i=0;i<objeto.length;i++){
					var obj		 	   	= new Object();
					obj.sucursal 			= objeto[i].sucursal;
					obj.clavevendedor	 	= objeto[i].clavevendedor;
					obj.vendedor	 	   	= objeto[i].vendedor;
					obj.ventas   			= objeto[i].ventas;
					obj.ventascobradas		= objeto[i].ventascobradas;
					Cant += parseFloat(1) ;
					ventas += parseFloat(objeto[i].ventas);
					ventascobradas += parseFloat(objeto[i].ventascobradas);
					tabla1.add(obj);
				}	
				u.cant.value= Cant;
				u.ventas.value= convertirMoneda(ventas);
				u.ventasc.value= convertirMoneda(ventascobradas);
			}else{
				tabla1.clear();
				u.cant.value=0;
				u.ventas.value=0;
				u.ventasc.value=0;
				mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");
			}
		}
		
	function agregaventas(){
		setTimeout("mostrarAno()",300);
	}	
		
	function mostrarAno(){
		var arr = tabla1.getSelectedRow();
		row = u.fecha2.value.split("/");	
		parent.document.all.barratabs_contenedor_id1.disabled=false;	
		parent.document.all.iframe_id1.src="ventas.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&vendedor="+arr.vendedor+"&ano="+row[2]+"&mes="+row[1]+"&idvendedor="+arr.clavevendedor;
		parent.tabs.seleccionar(1);


		parent.cn.agregarDireccion(0);
	}
		
	function agregaventascobradas(){
		setTimeout("mostrarAno2()",300);
	}
	
	function mostrarAno2(){
		var arr = tabla1.getSelectedRow();
		row = u.fecha2.value.split("/");
		parent.document.all.barratabs_contenedor_id2.disabled=false;	
		parent.document.all.iframe_id2.src="ventascobradas.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&vendedor="+arr.vendedor+"&ano="+row[2]+"&mes="+row[1]+"&clavevendedor="+arr.clavevendedor;
		parent.tabs.seleccionar(2);


		parent.cn.agregarDireccion(1);
}
	
	function agregaventasvendedor(){
	setTimeout("agregaventasvendedor2()",300);
	}
	
	function agregaventasvendedor2(){
			var fecha 	 = "&fecha="+u.fecha2.value;
			consultaTexto("mostrarmeses","principal_con.php?accion=5"+fecha);
	}
	
	function mostrarmeses(datos){
		var arr = tabla1.getSelectedRow();
		if (datos!=0){
			var obj		= eval(convertirValoresJson(datos));
			u.mes1.value	= obj[0].mes1;
			u.mes2.value	= obj[0].mes2;
			u.mes3.value	= obj[0].mes3;
		}			
		parent.document.all.barratabs_contenedor_id3.disabled=false;	


			parent.document.all.iframe_id3.src="vendedor.php?clavevendedor="+arr.clavevendedor+"&vendedor="+arr.vendedor+"&fecha="+u.fecha2.value+"&mes1="+u.mes1.value+"&mes2="+u.mes2.value+"&mes3="+u.mes3.value;


		parent.tabs.seleccionar(3);


		parent.cn.agregarDireccion(2);


	}
	function agregatotalventascobradas(){


		setTimeout("agregatotalventascobradas2()",300);


	}
	function agregatotalventascobradas2(){	


		parent.document.all.barratabs_contenedor_id4.disabled=false;	


		parent.document.all.iframe_id4.src="totalventascobradas.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value;


		parent.tabs.seleccionar(4);


		parent.cn.agregarDireccion(3);


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
	
	function tipoImpresion(valor){
		if(valor=="Archivo"){			
			window.open("http://www.pmmentuempresa.com/web/general/vendedores/generarExcelVendedor.php?accion=1&titulo=REPORTE DE VENDEDORES&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value);			
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
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">


  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="578"><table width="436" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="18">De</td>
        <td width="100"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=$fechaini ?>" /></td>
        <td width="34"><img src="../../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" /></td>
        <td width="17">Al</td>
        <td width="100"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=$fechafin ?>"/></td>
        <td width="34"><img src="../../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)" /></td>
        <td width="133"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Generar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></td>


      </tr>
    </table></td>


  </tr>
  <tr>
    <td width="578"></td>


  </tr>
  <tr align="center">
    <td></td>


  </tr>
  
  <tr>
    <td>
      <table width="300" id="detalle" border="0" cellpadding="0" cellspacing="0">


      </table>
      </td>


  </tr>
  <tr>


    <td align="right"><table width="532" height="16" border="0" cellpadding="0" cellspacing="0">


      <tr>


        <td width="3">&nbsp;</td>


        <td width="264"><div align="right">


          <input name="ano" type="hidden" id="ano" />


          <input name="mes" type="hidden" id="mes" />


          <input name="mes1" type="hidden" id="mes1" />


          <input name="mes2" type="hidden" id="mes2" />


          <input name="mes3" type="hidden" id="mes3" />


          Totales:</div></td>


        <td width="2" align="center"><div align="right"></div></td>


        <td width="110" align="center"><div align="right">


            <input name="cant" type="text" class="Tablas" id="cant" style="text-align:right;background-color:#FFFF99; width:100px" value="<?=$cant ?>
                " readonly=""/>


        </div></td>


        <td width="110" align="center"><div align="center">


            <input name="ventas" type="text" class="Tablas" id="ventas" style="text-align:right;background-color:#FFFF99; width:100px" value="<?=$ventas ?>" readonly="" />


        </div></td>


        <td width="110" align="center"><div align="center">


            <input name="ventasc" type="text" class="Tablas" id="ventasc" style="text-align:right;background-color:#FFFF99; cursor:pointer" value="<?=$ventasc ?>" readonly="" align="right" ondblclick="agregatotalventascobradas()"/>


        </div></td>


      </tr>


    </table></td>


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