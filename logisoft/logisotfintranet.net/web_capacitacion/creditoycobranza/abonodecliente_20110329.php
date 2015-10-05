<?	session_start();	
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/ClaseMensajes.js"></script>
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
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
#form1 table tr td div table tr td table tr td table tr td table tr td .Tablas {
	font-weight: normal;
}
#form1 table tr td div table tr td table tr td table tr td table tr td br {
	text-align: right;
}
-->
</style>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
</head>
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	var mens = new ClaseMensajes();
	mens.iniciar("../javascript/");
	
	tabla1.setAttributes({
		nombre:"tabladetalle",
		campos:[
			{nombre:"FACTURA", medida:100, onClick:"pregPago", alineacion:"center", datos:"factura"},
			{nombre:"GUIA", medida:100, alineacion:"center", datos:"folios"},
			{nombre:"FECHA", medida:100, alineacion:"center", datos:"fecha"},
			{nombre:"FECHA_VENC", medida:100, alineacion:"center", datos:"fechavencimiento"},
			{nombre:"SALDO", medida:100, alineacion:"right", tipo:"moneda", datos:"total"},
			{nombre:"APLICACION", medida:100, alineacion:"center", tipo:"checkbox", datos:"aplicacion"},
			{nombre:"PAGADO", medida:100, alineacion:"right", tipo:"moneda", datos:"totalpagado"}
		],
		filasInicial:30,
		alto:250,
		seleccion:false,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		consultaTexto("mostrarDetalles", "abonodecliente_con.php?accion=6&idpagina="+u.idpagina.value+"&valram="+Math.random());
	}
	function mostrarDetalles(datos){
		var objeto = eval("("+convertirValoresJson(datos)+")");
			u.sucursal.value	= objeto.sucursal;
			u.fecha.value 		= objeto.fecha;
			u.folio.value 		= objeto.folio;
	}
	
	function Limpiar(){
		u.sucursal.value	="";
		u.fecha.value		="";
		u.folio.value		="";
		u.idcliente.value	="";
		u.cliente.value		="";
		u.descripcion.value	="";
		u.factura.value		="";
		u.guia.value		="";
		u.abonar.value		="";
		u.saldocon.value	="0.00";
		u.sandoantes.value	="0.00";
		u.guardar.style.visibility="visible";
		document.getElementById('botones').style.display='';
		document.getElementById('guardar').style.display='';
		consultaTexto("mostrarDetalles", "abonodecliente_con.php?accion=6&idpagina="+u.idpagina.value+"&valram="+Math.random());
		tabla1.clear();
	}
	
	function pregPago(factura){
		if(document.getElementById('botones').style.display=='none')
			return false;
		mens.show("C","¿Desea Cancelar el pago?","¡ATENCION!",null,"quitarPago('"+factura+"')");
	}
	function quitarPago(factura){
		consultaTexto("resQuitar","abonodecliente_con.php?accion=5&idpagina="+u.idpagina.value+"&factura="+factura+'&rdnm='+Math.random());
	}
	function resQuitar(datos){
		if(datos.indexOf('ok')>-1){
			var obj = Object();	
			for(var i=0; i<tabla1.getRecordCount(); i++){
				if(document.all["tabladetalle_FACTURA"][i].value==datos.split("xxSEPAxx")[1]){
					obj.factura				= document.all["tabladetalle_FACTURA"][i].value;
					obj.folios				= document.all["tabladetalle_GUIA"][i].value;
					obj.fecha				= document.all["tabladetalle_FECHA"][i].value;
					obj.fechavencimiento	= document.all["tabladetalle_FECHA_VENC"][i].value;
					obj.total				= document.all["tabladetalle_SALDO"][i].value.replace("$ ","").replace(/,/g,"");
					obj.aplicacion			= 1;
					obj.totalpagado			= 0;
					
					tabla1.updateRowById("tabladetalle_id"+i, obj);
					break;
				}
			}
		}else{
			mens.show("A",datos);
		}
		sumarPagadas();
	}
	
	function sumarPagadas(){
		var total = 0;
		var saldog = parseFloat(u.saldocon.value.replace("$ ","").replace(",",""));
		var descripcion = "";
		
		for(i=0; i<tabla1.getRecordCount(); i++){
			if(document.all['tabladetalle_PAGADO'][i].value != "$ 0.00"){
				total += parseFloat(document.all['tabladetalle_PAGADO'][i].value.replace("$ ","").replace(",",""))
				descripcion += ((descripcion!="")?",":"")+document.all['tabladetalle_FACTURA'][i].value;
			}
		}
		
		u.descripcion.value = "PAGO FACTURAS "+descripcion;
		u.abonar.value		= convertirMoneda(total);
		u.sandoantes.value	= convertirMoneda(saldog-total);
	}
	
	function tabular(e,obj) {
				tecla=(document.all) ? e.keyCode : e.which;
				if(tecla!=13) return;
				frm=obj.form;
				for(i=0;i<frm.elements.length;i++) 
					if(frm.elements[i]==obj) 
	
					{ 
						if (i==frm.elements.length-1) 
							i=-1;
						break 
					}
	
				if (frm.elements[i+1].disabled ==true )    
					tabular(e,frm.elements[i+1]);
				else if (frm.elements[i+1].readOnly ==true )    
					tabular(e,frm.elements[i+1]);
				else frm.elements[i+1].focus();
				return false;
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
	
	function limpiarCliente(){
		u.idcliente.value 	= "";
		u.cliente.value 	= "";
		u.descripcion.value = "";
		u.abonar.value = "$ 0.00";
		//tabla1.clear();
	}
	
	
	function pedirCliente(valor){
		consultaTexto("mostrarCliente", "abonodecliente_con.php?accion=1&idpagina="+u.idpagina.value+"&valor="+valor+"&cliente="+valor+"&valram="+Math.random());
	}
	function mostrarCliente(datos){
		var objeto = eval(convertirValoresJson(datos));
		limpiarCliente();
		if(objeto.cliente.id!=undefined){
			u.idcliente.value	= objeto.cliente.id;
			u.cliente.value 	= objeto.cliente.ncliente;
			u.descripcion.value = "PAGO";
			
			tabla1.clear();
			tabla1.setJsonData(objeto.facturas);
			var total = 0;
			for(i=0; i<tabla1.getRecordCount(); i++){
				total += parseFloat(document.all['tabladetalle_SALDO'][i].value.replace("$ ","").replace(",",""))
			}
			
			u.sandoantes.value 	= convertirMoneda(total);
			u.saldocon.value 	= convertirMoneda(total);
			
			
			u.factura.value="";
			u.abonar.value ="";
		}else{
			mens.show("A","el código del cliente no se encuentra registrado", "¡Atencion!");
		}
	}
	
	function pagar(){
		var cantsel = tabla1.getSelCountField("APLICACION");
		if(cantsel>1){
			mens.show("A","Para asignar un pago solo puede seleccionar un registro","&iexcl;ATENCION!");
			return false;
		}
		if(cantsel<1){
			mens.show("A","No ha seleccionado facturas a pagar","&iexcl;ATENCION!");
			return false;
		}
		var arr = new Object();
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(document.all["tabladetalle_APLICACION"][i].checked){
				arr.factura = document.all["tabladetalle_FACTURA"][i].value;
				arr.cliente = document.all["idcliente"].value;
				break;
			}
		}
		mens.popup("formapago_abono.php?funcion=modificarPago&factura="+arr.factura+'&cliente='+arr.cliente, 525, 418, "ventana", "FORMA DE PAGO");
	}
	
	function modificarPago(factura){
		var arr = new Array();
		arr[0] = (u.efectivo.value != "")? u.efectivo.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[1] = (u.cheque.value != "")? u.cheque.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[2] = (u.banco.value != "")? u.banco.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[3] = (u.ncheque.value != "")? u.ncheque.value : "";
		arr[4] = (u.tarjeta.value != "")? u.tarjeta.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[5] = (u.transferencia.value !="")? u.transferencia.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[6] = (u.nc.value != "")? u.nc.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[7] = (u.nc_folio.value !="")? u.nc_folio.value.replace("$ ","").replace(/,/g,"") : "0";	
		consultaTexto("resModificacion","abonodecliente_con.php?accion=4&idpagina="+u.idpagina.value+"&arre="+arr+"&factura="+factura+'&rdnm='+Math.random());
	}
	
	function resModificacion(datos){
		if(datos.indexOf('ok')>-1){
			var obj = Object();	
			for(var i=0; i<tabla1.getRecordCount(); i++){
				if(document.all["tabladetalle_APLICACION"][i].checked){
					obj.factura				= document.all["tabladetalle_FACTURA"][i].value;
					obj.folios				= document.all["tabladetalle_GUIA"][i].value;
					obj.fecha				= document.all["tabladetalle_FECHA"][i].value;
					obj.fechavencimiento	= document.all["tabladetalle_FECHA_VENC"][i].value;
					obj.total				= document.all["tabladetalle_SALDO"][i].value.replace("$ ","").replace(/,/g,"");
					obj.aplicacion			= 1;
					obj.totalpagado			= document.all["tabladetalle_SALDO"][i].value.replace("$ ","").replace(/,/g,"");
					
					tabla1.updateRowById("tabladetalle_id"+i, obj);
					break;
				}
			}
		}else{
			mens.show("A",datos);
		}
		sumarPagadas();
	}
	
	function agrupar(){
		var facturas = String();
		
		if(tabla1.getSelCountField("APLICACION")<2){
			mens.show("A","Seleccione mas de una factura para agrupar","¡ATENCION!");
			return false;
		}
		
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(document.all["tabladetalle_APLICACION"][i].checked){
				facturas += ((facturas!="")?",":"")+document.all["tabladetalle_FACTURA"][i].value;
			}
		}
		consultaTexto("resAgrupar","abonodecliente_con.php?accion=2&idpagina="+u.idpagina.value+"&facturas="+facturas+"&rand="+Math.random());
	}
	function resAgrupar(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
			return false;
		}
		tabla1.setJsonData(obj.registrosc);
		
		sumarPagadas();
	}
	function desagrupar(){
		var facturas = String();
		
		if(tabla1.getSelCountField("APLICACION")<1){
			mens.show("A","Seleccione un registro para desagrupar","¡ATENCION!");
			return false;
		}
		
		if(tabla1.getSelCountField("APLICACION")>1){
			mens.show("A","Seleccione un registro a la vez","¡ATENCION!");
			return false;
		}
		
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(document.all["tabladetalle_APLICACION"][i].checked){
				if(document.all["tabladetalle_FACTURA"][i].value.indexOf(",")<0){
					mens.show("A","Seleccione facturas agrupadas","¡ATENCION!");
					return false;
				}
				facturas += ((facturas!="")?",":"")+document.all["tabladetalle_FACTURA"][i].value;
			}
		}
		consultaTexto("resDesagrupar","abonodecliente_con.php?accion=3&idpagina="+u.idpagina.value+"&facturas="+facturas+"&rand="+Math.random());
	}
	function resDesagrupar(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
			return false;
		}
		tabla1.setJsonData(obj.registrosc);
		
		sumarPagadas();
	}
	
	function guardarPagos(){
		if(u.cliente.value==""){
			mens.show('A','Debe capturar Cliente','¡Atención!','cliente');
			return false;
		}
		if(u.descripcion.value==""){
			mens.show('A','Debe capturar Descripcion','¡Atención!','descripcion');
			return false;
		}
		
		var total = 0;
		for(i=0; i<tabla1.getRecordCount(); i++){
			if(document.all['tabladetalle_PAGADO'][i].value != "$ 0.00")
				total += parseFloat(document.all['tabladetalle_PAGADO'][i].value.replace("$ ","").replace(",",""))
		}
		if(total==0){
			mens.show("A","Realice un pago para poder guardar","¡ATENCION!");
			return false;
		}
		
		consultaTexto("resGuardar", "abonodecliente_con.php?accion=7&idpagina="+u.idpagina.value
							+"&folio="+u.folio.value
							+"&idcliente="+u.idcliente.value
							+"&descripcion="+u.descripcion.value
							+"&abonar="+parseFloat(u.abonar.value.replace("$ ","").replace(/,/,""))
							+"&saldocon="+parseFloat(u.saldocon.value.replace("$ ","").replace(/,/,""))
							+"&sandoantes="+parseFloat(u.sandoantes.value.replace("$ ","").replace(/,/,""))
							+"&valram="+Math.random());
		
	}
	
	function resGuardar(datos){
		if(datos.indexOf('ok')>-1){
			mens.show("I","Datos guardados correctamente","¡ATENCION!");
			document.getElementById('guardar').style.display='none';
		}else if(datos.indexOf('Ya fue liquidada')>-1){
			mens.show("A",datos,"¡ATENCION!");
			document.getElementById('guardar').style.display='none';
		}else{
			mens.show("A",datos,"");
		}
	}
	
	function OptenerAbonoCliente(id){
		consultaTexto("mostrarAbonoCliente", "abonodecliente_con.php?accion=8&idpagina="+u.idpagina.value+"&id="+id+"&valram="+Math.random());
	}
	function mostrarAbonoCliente(datos){
			try{
				var objeto = eval("("+convertirValoresJson(datos)+")");
			}catch(e){
				mens.show("A",datos);
			}
			tabla1.clear();
			u.folio.value		=objeto.cliente.folio;
			u.fecha.value 		=objeto.cliente.fecharegistro;
			u.sucursal.value 	=objeto.cliente.sucursal;
			u.idcliente.value 	=objeto.cliente.idcliente;
			u.cliente.value		=objeto.cliente.cliente;
			u.descripcion.value =objeto.cliente.descripcion;
			u.abonar.value 		=convertirMoneda(objeto.cliente.abonar);
			u.saldocon.value 	=convertirMoneda(objeto.cliente.saldocon);
			u.sandoantes.value 	=convertirMoneda(objeto.cliente.saldoantesdeaplicar);
			tabla1.setJsonData(objeto.datos);
			u.guardar.style.visibility="hidden";
			document.getElementById('botones').style.display='none';
	}
	
	function obtenerFactura(factura){
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(document.all["tabladetalle_FACTURA"][i].value==factura){
				try{
					document.all["tabladetalle_APLICACION"][i].checked = true;
					return false;
				}catch(e){
					e = 0;
					return false;
				}
			}
		}
		mens.show("A","No se encontro la factura","¡ATENCION!");
	}
	
	function obtenerGuia(guia){
		if(guia.length!=13){
			mens.show("A","La guia debe tener 13 caracteres","¡ATENCION!");	
		}
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(document.all["tabladetalle_GUIA"][i].value.indexOf(guia)>-1){
				try{
					document.all["tabladetalle_APLICACION"][i].checked = true;
					return false;
				}catch(e){
					e = 0;
					return false;
				}
			}
		}
		mens.show("A","No se encontro la factura","¡ATENCION!");
	}
	
</script>
<body>
<form id="form1" name="form1" method="post" action="">
    <?
	list($Mili, $bot) = explode(" ", microtime());
	$DM=substr(strval($Mili),2,2);
	?>
  <input type="hidden" name="idpagina" id="idpagina" value="<?=$_SESSION[IDUSUARIO].date("ymdHis").$DM?>" />
  <br>
  <table width="690" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="426" class="FondoTabla Estilo4">ABONO DEL CLIENTE</td>
  </tr>
  <tr>
    <td height="142"><div align="center">
      <table width="259" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="352"><table width="398" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="271" height="11"><table width="690" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="497"><table width="690" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="80">Sucursal:</td>
                      <td width="164">
                      <input name="sucursal" type="text" id="sucursal" class="Tablas" value="<?=$sucurasl ?>" style="width:150px;background:#FFFF99" onKeyPress="return tabular(event,this)" readonly=""/></td>
                      <td width="35">&nbsp;</td>
                      <td width="33">Fecha:</td>
                      <td width="164"><span class="Tablas">
                        <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly="" onKeyPress="return tabular(event,this)"/>
                      </span></td>
                      <td width="65"> <div align="right">Folio:</div></td>
                      <td width="102"><span class="Tablas">
                        <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio?>" readonly="" onKeyPress="return tabular(event,this)"/>
                      </span></td>
                      <td width="47"><div class="ebtn_buscar" onClick="mens.popup('../buscadores_generales/buscarAbonoDeClienteGen.php?funcion=OptenerAbonoCliente', 625, 550, 'ventana', 'Buscar Guia')"></div></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="469" colspan="4" class="FondoTabla"> Datos del Abono</td>
                      </tr>
                    
                  </table></td>
                </tr>
                <tr>
                  <td><table width="690" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><span style="width:62px">Cliente:</span></td>
                      <td><table width="463" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td style="width:140px"><table width="91%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td style="width:101px"><span class="Tablas">
                                  <input name="idcliente" type="text" class="Tablas" id="idcliente" style="width:100px" value="<?=$idcliente ?>" onKeyPress="if(event.keyCode=='13'){pedirCliente(this.value);};return tabular(event,this)"/>
                                </span></td>
                                <td><div class="ebtn_buscar" onClick="mens.popup('../buscadores_generales/buscarClienteGen2.php?funcion=pedirCliente', 625, 450, 'ventana', 'Buscar Cliente')"></div></td>
                              </tr>
                          </table>                            <div align="left"></div></td>
                          <td><label><span class="Tablas">
                            <input name="cliente" type="text" class="Tablas" id="cliente" style="width:320px;background:#FFFF99" value="<?=$cliente ?>" onKeyPress="return tabular(event,this)" readonly=""/>
                          </span></label></td>
                          </tr>
                      </table></td>
                    </tr>
                    
                    <tr>
                      <td width="63" height="27" style="width:55px" >Descripci&oacute;n:</td>
                      <td width="454"><span class="Tablas">
                        <input name="descripcion" type="text" class="Tablas" id="descripcion" style="width:460px" value="<?=$descripcion ?>" onKeyPress="return tabular(event,this)"/>
                      </span></td>
                    </tr>
<tr>
                      <td><span style="width:62px">Factura:</span></td>
                      <td><table width="422" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td style="width:140px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td style="width:101px"><span class="Tablas">
                                  <input name="factura" type="text" class="Tablas" id="factura" style="width:100px" value="<?=$factura ?>"  onKeyPress="if(event.keyCode=='13'){obtenerFactura(this.value)};return tabular(event,this)" />
                                </span></td>
                                <td><div class="ebtn_buscar" onClick="mens.popup('../buscadores_generales/buscarFacturasGen.php?funcion=obtenerFactura&modulo=abonocliente&cliente='+document.all.idcliente.value, 625, 450, 'ventana', 'Buscar Factura')" ></div></td>
                              </tr>
                          </table></td>
                          <td style="width:30px">Guia</td>
                          <td width="76"><span class="Tablas">
                            <input name="guia" type="text" class="Tablas" id="guia" style="width:135px" value="<?=$guia?>" onKeyPress="if(event.keyCode=='13'){obtenerGuia(this.value)};"/>
                            </span>
                              <label></label></td>
                          <td  ><div class="ebtn_buscar" onClick="mens.popup('../buscadores_generales/buscarGuiasEmpresariales_VentanillaGen.php?funcion=obtenerGuia&tipo=3&cliente='+document.all.idcliente.value,625, 450, 'ventana', 'Buscar Guia')" ></div></td>
                        </tr>
                      </table></td>
                    </tr>
                    

                  </table></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <td><table id="tabladetalle" border="0" cellspacing="0" cellpadding="0">

                  </table></td>
                </tr>
                <tr>
                	<td>
                    <table width="300" align="center" border="0" cellpadding="0" cellspacing="0" id="botones">
                        <tr>
                            <td align="center"><img src="../img/Boton_Pagar.gif" onClick="pagar()" style="cursor:pointer"></td>
                            <td align="center"><img src="../img/Boton_Desagrupar.gif" onClick="desagrupar()" style="cursor:pointer"></td>
                            <td align="center"><img src="../img/Boton_Agrupar.gif" onClick="agrupar()" style="cursor:pointer"></td>
                        </tr>
                    </table>
                    </td>
                </tr>
                <tr>
                  <td class=""><table border="0" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="94"> Total a Abonar:<br></td>
                      <td width="142"><span class="Tablas">
                        <input name="abonar" type="text" class="Tablas" id="abonar" style="text-align:right;width:130px;background:#FFFF99" value="<?=$abonar ?>" readonly="" onKeyPress="return tabular(event,this)"/>
                      </span></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td class="FondoTabla"> Informaci&oacute;n Adicional</td>
                </tr>
                <tr>
                  <td><label>Saldo con:<span class="Tablas">
                    <input name="saldocon" type="text" class="Tablas" id="saldocon" style="text-align:right;width:100px;background:#FFFF99" value="<?=$saldocon ?>" readonly="" onKeyPress="return tabular(event,this)"/>
                  </span> Saldo Antes de Aplicar:  <span class="Tablas">
                  <input name="sandoantes" type="text" class="Tablas" id="sandoantes" style="text-align:right;width:100px;background:#FFFF99" value="<?=$sandoantes ?>" readonly=""  onKeyPress="return tabular(event,this)"/>
                  </span></label></td>
                </tr>
<tr>
                  <td><div align="center"><a href="../menu/webministator.php"></a>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="52%">
						<input name="efectivo" type="hidden" id="efectivo" />
                        <input name="cheque" type="hidden" id="cheque" />
                        <input name="banco" type="hidden" id="banco" />
                        <input name="ncheque" type="hidden" id="ncheque" />
                        <input name="tarjeta" type="hidden" id="tarjeta" />
                        <input name="transferencia" type="hidden" id="transferencia" />
                        <input name="nc" type="hidden" value="">
                        <input name="nc_folio" type="hidden" value="">
                         </td>
                        <td width="48%"><table width="34%" height="12" border="0" align="right" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="38%"><div id="guardar" class="ebtn_guardar" onClick="guardarPagos()"></div></td>
                            <td width="62%"><div class="ebtn_nuevo" onClick="Limpiar()"></div></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table>
                  </div></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>
</form>
</body>
</html>