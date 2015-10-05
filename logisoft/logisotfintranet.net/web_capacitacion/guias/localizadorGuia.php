
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../javascript/ajaxlist/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	var mens = new ClaseMensajes();
	
	mens.iniciar('../javascript',false);
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"GUIA", medida:110, onClick:"obtenerGuia", alineacion:"left", datos:"guia"},
			{nombre:"ORIGEN", medida:100, alineacion:"center", datos:"origen"},
			{nombre:"DESTINO", medida:100, alineacion:"center", datos:"destino"},
			{nombre:"FECHA", medida:100, alineacion:"center", datos:"fecha"},
			{nombre:"ESTADO", medida:100, alineacion:"center", datos:"estado"}
		],
		filasInicial:10,
		alto:100,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.busqueda.focus();
	}
	
	function filtroBusqueda(){
		if(u.busqueda.value==""){
			mens.show("A","Debe capturar un Nombre, una Guia, Numero de Rastreo o Numero de Recolección","¡Atención!","busqueda");
			return false;
		}
		if(u.filtro[0].checked == true){
			consultaTexto("mostrarBusqueda","localizadorGuia_con.php?accion=1&nombre="+u.busqueda.value+
						  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&vl="+Math.random());
		}else if(u.filtro[1].checked == true){
			consultaTexto("mostrarBusqueda","localizadorGuia_con.php?accion=1&guia="+u.busqueda.value+"&vl="+Math.random());
		}else if(u.filtro[2].checked == true){
			consultaTexto("mostrarBusqueda","localizadorGuia_con.php?accion=1&rastreo="+u.busqueda.value+"&vl="+Math.random());
		}else if(u.filtro[3].checked == true){			
			if(parseFloat(document.getElementById("busqueda").value) > 0 && document.getElementById("busqueda").value!=""){
				consultaTexto("mostrarBusqueda","localizadorGuia_con.php?accion=1&recoleccion="+u.busqueda.value+"&vl="+Math.random());
			}else{
				if(document.getElementById("busqueda").value == ""){
					mens.show("A","Debe capturar Numero de Recolección","¡Atención!","busqueda");
					return false;
				}
				
				if(parseFloat(document.getElementById("busqueda").value) <= 0){
					mens.show("A","El Numero de Recolección debe ser mayor a Cero","¡Atención!","busqueda");
					return false;
				}			
			}
		}
	}
	
	function mostrarBusqueda(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
		}else{
			mens.show("A","No se pudo realizar la busqueda con el filtro seleccionado","¡Atención!","busqueda");
		}
	}
	
	function obtenerGuia(guia){
		parent.buscarlaguia(guia);
		parent.VentanaModal.cerrar();
	}
	
	function devolverCliente(){
		if(u.busqueda_hidden.value==""){
			setTimeout("devolverCliente()",500);
		}
	}	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:240px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:1em;
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

</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="515" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Localizador Guias </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2">
            <table width="510">
              <tr>
                <td width="122">
                  
                  <input name="filtro" type="radio" style="width:13px" checked="checked" onclick="document.all.busqueda.value=''; document.all.busqueda.focus(); document.getElementById('lasfechas').style.display=''"/>
                  Nombre 
                  </td>
                <td width="123">
                  <input name="filtro" type="radio" style="width:13px" onclick="document.all.busqueda.value=''; document.all.busqueda.focus(); document.getElementById('lasfechas').style.display='none'"/>
                  Guia
                  </td>
                <td width="123">
                  <input name="filtro" type="radio" style="width:13px" onclick="document.all.busqueda.value=''; document.all.busqueda.focus(); document.getElementById('lasfechas').style.display='none'"/>
                  No. Rastreo
                  </td>
                <td width="122">
                  <input name="filtro" type="radio" style="width:13px" onclick="document.all.busqueda.value=''; document.all.busqueda.focus(); document.getElementById('lasfechas').style.display='none'"/>
                  No. Recolecci&oacute;n
                  </td>
                </tr>
              </table>
            </td>
        </tr>
        <tr>
          <td width="134" height="20"></td>
          <td width="377"><label></label></td>
        </tr>
        <tr>
          <td>&nbsp;Valor buscado:</td>
          <td><input class="Tablas" name="busqueda" type="text" id="busqueda" style="width:230px" onkeyup="if(document.all.filtro[0].checked==true){ajax_showOptions(this,'getCountriesByLetters',event,'../recoleccion/ajax-list-cliente.php')}" onkeypress="if(document.all.filtro[3].checked==true){return solonumeros(event)}else{if(document.all.filtro[0].checked==true){if(event.keyCode==13){devolverCliente();}}}" />
            <input name="busqueda_hidden" type="hidden" id="busqueda_hidden" /></td>
        </tr>
        <tr>
          <td height="22" colspan="2">
          	<table width="510" id="lasfechas">
            	<tr>
                	<td width="74">Fecha Inicio</td>
                    <td width="113">
                    	<input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=date('d/m/Y')?>" onkeypress="if(event.keyCode==13){document.all.fecha2.focus();}"/></td>
                    <td width="45"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
                    <td width="59">Fecha Fin</td>
                  <td width="121">
                    
                    <input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=date('d/m/Y') ?>" onkeypress="if(event.keyCode==13){obtenerDetalle();}"/></td>
                    <td width="70"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>
                </tr>
            </table>
          </td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center"> <img src="../img/Boton_Buscar.gif" width="67" height="20" style="cursor:pointer" onclick="filtroBusqueda()" /></td>
          </tr>
        
        <tr>
          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
