<?	session_start();
	$fecha=date('d/m/Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.js?random=20060118"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<link href="../../estilos_estandar.css" />
<script src="../../javascript/ajax.js"></script>
<script language="javascript" src="../../javascript/funcionesDrag.js"></script>
<script language="javascript" src="../../javascript/ClaseMensajes.js"></script>
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
	var mens = new ClaseMensajes();
	mens.iniciar('../../javascript',true);
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto",alineacion:"left", datos:"sucursal"},
			{nombre:"SUCURSAL", medida:100, alineacion:"left", datos:"nombresucursal"},
			{nombre:"CLIENTES CON CREDITO", medida:150, onDblClick:"agregaclientescredito",alineacion:"center",  datos:"carteracredito"},
			{nombre:"CARTERA VIGENTE", medida:130, tipo:"moneda",onDblClick:"agregaantiguedad",alineacion:"right",  datos:"carteravigente"},	
			{nombre:"CARTERA MOROSA", medida:130, tipo:"moneda",onDblClick:"agregaantiguedad",alineacion:"right",  datos:"carteramorosa"},
			{nombre:"CARTERA TOTAL", medida:130, tipo:"moneda",onDblClick:"agregaantiguedad",alineacion:"right",  datos:"carteratotal"}	
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
	});
	window.onload = function(){
		tabla1.create();
		u.cant.value=0;
		u.total.value=0.00;
		u.total1.value=0.00;
		u.total2.value=0.00;
		parent.tabs.agregarTabs("CLIENTES CON CREDITO",1,"clienteconcredito.php?sucursal=0&mes=0&fechaini="+u.fecha.value+"&fechafin="+u.fecha.value+"&inicio=1");
		parent.document.all.barratabs_contenedor_id1.disabled=true;	
		parent.tabs.agregarTabs("ANTIGÜEDAD DE SALDOS",2,"carteravigente.php?sucursal=0&fecha="+u.fecha.value+"&fecha2="+u.fecha.value+"&inicio=1");
		parent.document.all.barratabs_contenedor_id2.disabled=true;	
		parent.tabs.agregarTabs("MONTO AUTORIZADO",3,"montoautorizado.php?cliente=0&inicio=1");
		parent.document.all.barratabs_contenedor_id3.disabled=true;	
		parent.tabs.agregarTabs("ESTADO DE CUENTA",4,"nombredelcliente.php?fecha="+u.fecha.value+"&fecha2="+u.fecha.value+"&cliente=0&mes=0&nombrecliente=0&inicio=1");
		parent.document.all.barratabs_contenedor_id4.disabled=true;	
		parent.tabs.seleccionar(0);
	}
	
	function ObtenerDetalle(){
		if(u.fecha.value==""){
			mens.show("A","Debe capturar la fecha","¡Atención!","fecha");	
		}else{
			consultaTexto("mostrardetalle","principal_con.php?accion=1&fecha="+u.fecha.value);
		}
	}
	
	function mostrardetalle(datos){	
		if (datos!=0) {
			$Cant=0;
			$total=0;
			$total1=0;
			$total2=0;
				tabla1.clear();
				var objeto = eval(convertirValoresJson(datos));
				for(var i=0;i<objeto.length;i++){
					var obj		 	   	= new Object();
					obj.sucursal 			= objeto[i].sucursal;
					obj.nombresucursal 		= objeto[i].nombresucursal;
					obj.carteracredito	 	= objeto[i].carteracredito;
					obj.carteravigente 	   	= objeto[i].carteravigente;
					obj.carteramorosa		= objeto[i].carteramorosa;
					obj.carteratotal		= objeto[i].carteratotal;
					$Cant += 1;
					$total += parseFloat(objeto[i].carteravigente);
					$total1 += parseFloat(objeto[i].carteramorosa);
					$total2 += parseFloat(objeto[i].carteratotal);
					tabla1.add(obj);
				}	
				u.cant.value= $Cant;
				u.total.value= convertirMoneda($total);
				u.total1.value= convertirMoneda($total1);
				u.total2.value= convertirMoneda($total2);
			}else{
				u.cant.value=0;
				u.total.value=0;
				u.total1.value=0;
				u.total2.value=0;
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
	
	function agregaclientescredito(){
		setTimeout("agregaclientescredito2()",300);
	}
	
	function agregaclientescredito2(){
		if(u.fecha.value==""){
			mens.show("A","Debe capturar la fecha","¡Atención!","");	
		}else{
			consultaTexto("mostrarmesfechainicio","principal_con.php?accion=2&fecha="+u.fecha.value);
		}
	}
	
	function mostrarmesfechainicio(datos){	
		var obj = eval(convertirValoresJson(datos));
		u.fechaini.value		= obj[0].fechaini;
		u.mes.value				= obj[0].mes;
		var arr = tabla1.getSelectedRow();	
			parent.document.all.barratabs_contenedor_id1.disabled=false;	
			parent.document.all.iframe_id1.src="clienteconcredito.php?sucursal="+arr.sucursal+"&mes="+u.mes.value+"&fechaini="+u.fechaini.value+"&fechafin="+u.fecha.value;
			parent.tabs.seleccionar(1);

			parent.cn.agregarDireccion(0);
	}
	
	function agregaantiguedad(){
		setTimeout("agregaantiguedad2()",300);
	}
	
	function agregaantiguedad2(){
		if(u.fecha.value==""){
			mens.show("A","Debe capturar la fecha","¡Atención!","");	
		}else{
			consultaTexto("mostrarmesfechainicioantiguedad","principal_con.php?accion=2&fecha="+u.fecha.value);
		}
	}
	
	function mostrarmesfechainicioantiguedad(datos){	
		var obj = eval(convertirValoresJson(datos));
		u.fechaini.value		= obj[0].fechaini;
		u.mes.value				= obj[0].mes; 	
		var arr = tabla1.getSelectedRow();
		parent.document.all.barratabs_contenedor_id2.disabled=false;	
		parent.document.all.iframe_id2.src="carteravigente.php?sucursal="+arr.sucursal+"&fecha="+u.fechaini.value+"&fecha2="+u.fecha.value;
		parent.tabs.seleccionar(2);

		parent.cn.agregarDireccion(1);
	}
	
	function agregaantiguedadcaja(){
		setTimeout("agregaantiguedadcaja2()",300);
	}
	
	function agregaantiguedadcaja2(){
		if(u.fecha.value==""){
			mens.show("A","Debe capturar la fecha","¡Atención!","");	
		}else{
			consultaTexto("mostrarmesfechainicioantiguedadcaja","principal_con.php?accion=2&fecha="+u.fecha.value);
		}
	}
	
	function mostrarmesfechainicioantiguedadcaja(datos){	
		var obj = eval(convertirValoresJson(datos));
		u.fechaini.value		= obj[0].fechaini;
		u.mes.value				= obj[0].mes; 	
		var sucursal = "";
		if (parseFloat(u.total2.value.replace("$ ","").replace(/,/,""))!=0){
			parent.document.all.barratabs_contenedor_id2.disabled=false;	
			parent.document.all.iframe_id2.src="carteravigente.php?sucursal="+sucursal+"&fecha="+u.fechaini.value+"&fecha2="+u.fecha.value;
			parent.tabs.seleccionar(2);

			parent.cn.agregarDireccion(1);
		}
	}
	
	function tipoImpresion(valor){
		if(valor=="Archivo"){
			window.open("http://www.pmmentuempresa.com/web/general/cobranza/generarExcelCobranza.php?accion=1&titulo=ESTADO DE CUENTAS POR COBRAR&fecha="+u.fecha.value);
		}
	}
	
</script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../../javascript/ajaxlist/ajax-dynamic-list.js" ></script>
<script src="../../javascript/ajaxlist/ajax.js"></script>
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
<link href="../../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="680" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="200"><table width="200" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="64">A La Fecha: </td>
            <td width="104"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=$fecha ?>" /></td>
            <td width="32"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
          </tr>
      </table></td>
      <td width="82"><div align="left"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></div></td>
      <td width="299"><input name="fechaini" type="hidden" class="Tablas" id="fechaini" style="width:100px" value="<?=$fechaini ?>"/>
          <input name="mes" type="hidden" class="Tablas" id="mes" style="width:100px" value="<?=$mes ?>"/></td>
    </tr>
    <tr>
      <td colspan="3"><table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">
      </table></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><table width="577" height="16" border="0" cellpadding="0" cellspacing="0" class="Tablas">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">Cliente Con Credito </td>
            <td align="center">Cartera Vigente </td>
            <td align="center">Cartera Morosa </td>
            <td align="center">Cartera Total </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center">Total General:</td>
            <td align="center"><input name="cant" type="text" class="Tablas" id="cant" style="text-align:right;width:100px;background:#FFFF99" value="<?=$cant ?>
                " readonly="" align="right" /></td>
            <td align="center"><input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total ?>
                " readonly="" align="right" /></td>
            <td align="center"><input name="total1" type="text" class="Tablas" id="total1" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total1 ?>
                " readonly="" align="right" /></td>
            <td align="center"><input name="total2" type="text" class="Tablas" id="total2" onclick="" style="text-align:right;width:100px;background:#FFFF99; cursor:pointer" value="<?=$total2 ?>
                " readonly="" align="right" title="Antigüedad de Saldos"/></td>
          </tr>
          <tr>
            <td width="3">&nbsp;</td>
            <td width="72"><div align="right"></div></td>
            <td width="93" align="center"><div align="right"></div></td>
            <td width="107" align="center">&nbsp;</td>
            <td width="100" align="center">&nbsp;</td>
            <td width="100" align="center">&nbsp;</td>
            <td width="102" align="center"><a onclick="agregaantiguedadcaja()" href="#">Ver Antig&uuml;edad de Saldos</a href></td>
          </tr>
      </table></td>
    </tr>
<tr>
      <td colspan="3" align="right"><table width="74" align="center">
        <tr>
          <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>