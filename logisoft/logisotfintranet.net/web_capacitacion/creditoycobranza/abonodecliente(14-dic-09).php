<?

	session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	require_once("../Conectar.php");

	$l = Conectarse("webpmm");

	

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />

<script language="javascript" src="../javascript/ClaseTabla.js"></script>

<script language="javascript" src="../javascript/ajax.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

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

-->

</style>

</head>

<script>

	

//**************************

var u = document.all;

var tabla1 = new ClaseTabla();



tabla1.setAttributes({

	nombre:"tabladetalle",

	campos:[

		{nombre:"GUIA", medida:80, alineacion:"center", datos:"guia"},

		{nombre:"FECHA", medida:60, alineacion:"center", datos:"fecha"},

		{nombre:"FECHA_VENC", medida:60, alineacion:"center", datos:"fecha_venc"},

		{nombre:"FACTURA", medida:50, alineacion:"center", datos:"factura"},

		{nombre:"IMPORTE", medida:100, alineacion:"right", tipo:"moneda", datos:"importe"},

		{nombre:"SALDO", medida:100, alineacion:"right", tipo:"moneda", datos:"saldo"},

		{nombre:"APLICACION", medida:50,onDblClick:"seleccionar", alineacion:"center", datos:"aplicacion"}

	],

	

	filasInicial:8,

	alto:100,

	seleccion:true,

	ordenable:false,

	nombrevar:"tabla1"

});



	window.onload = function(){

		tabla1.create();	

		obtenerDetalles();

	}

	

	function obtenerDetalles(){

		consultaTexto("mostrarDetalles", "abonodecliente_con.php?accion=2&valram="+Math.random());

	}

	

	function mostrarDetalles(datos){

		var objeto = eval("("+convertirValoresJson(datos)+")");

			u.idsucursal.value	= objeto.idsucursal;

			u.sucursal.value	= objeto.sucursal;

			u.fecha.value 		= objeto.fecha;

			u.folio.value 		= objeto.folio;

	}

	

	// funciones para limpiar

	function limpiarCliente(){

		u.idcliente.value 	= "";

		u.cliente.value 	= "";

		u.descripcion.value = "";

		u.abonar.value ="";

		tabla1.clear();

	}



	function mostrarSucursales(){

		abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 600, 550, 'ventana', 'Forma de Pago');

	}

	

	function obtenerSucursal(id,sucursal){

		u.idsucursal.value	=id;

		u.sucursal.value	=sucursal;

	}

	

	function pedirCliente(valor){

		consultaTexto("mostrarCliente", "abonodecliente_con.php?accion=1&valor="+valor+"&valram="+Math.random());

	}

	

	function mostrarCliente(datos){

		var objeto = eval("("+convertirValoresJson(datos)+")");

		limpiarCliente();

		if(objeto.id!=undefined){

			u.idcliente.value	= objeto.id;

			u.cliente.value 	= objeto.ncliente;

			u.descripcion.value = "PAGO";

			u.sandoantes.value 	= convertirMoneda(objeto.importe);

		}else{

			alerta3("el código del cliente no se encuentra registrado", "¡Atencion!");

		}

	}

	

/**/	

function obtenerGuia(guia){

	var cliente = u.idcliente.value;

	if(cliente==""){

		alerta('Capture el # Cliente','¡Atención!','idcliente');

		return false;

	}

	consultaTexto("mostrarGuia","abonodecliente_con.php?accion=3&guia="+guia+"&cliente="+cliente+"&valram="+Math.random());

}	



function mostrarGuia(datos){

		var objeto = eval("("+convertirValoresJson(datos)+")");

		var g=tabla1.getValuesFromField('guia');

		var w=tabla1.getRecordCount();

		var fila;

			if(objeto!=""){

			for(var h=0;h<w;h++){

				fila = tabla1.getRowByIndex(h);

				fila.aplicacion = "";

				tabla1.updateRowByIndex(h,fila);

			}

			for(var i=0;i<objeto.length;i++){

				if(g.indexOf(objeto[i].guia)==-1){

					var obj		 	= new Object();

					obj.guia 		= objeto[i].guia;

					obj.fecha	   	= objeto[i].fecha;

					obj.fecha_venc	= objeto[i].fechavencimiento;

					obj.factura	   	= objeto[i].foliofactura;

					obj.importe  	= objeto[i].importe;

					obj.saldo 	   	= objeto[i].saldoactual;

					obj.aplicacion 	= "X";

					tabla1.add(obj);

				}

			}

			u.guia.value="";

			u.abonar.value ="";

			totalAbonar();

		}else{

			//alerta('El # guia no existe','¡Atención!','guia');

			return false;

		}

}

/**/

function obtenerFactura(factura){

	var cliente = u.idcliente.value;

	if(cliente==""){

		alerta('Capture el # Cliente','¡Atención!','idcliente');

		return false;

	}

	consultaTexto("mostrarFactura", "abonodecliente_con.php?accion=4&factura="+factura+"&cliente="+cliente+"&valram="+Math.random());

}



function mostrarFactura(datos){

		var objeto = eval("("+convertirValoresJson(datos)+")");

		var g=tabla1.getValuesFromField('guia');

		var w=tabla1.getRecordCount();

		var fila = new Object();

	

		if(objeto!=""){

			for(var h=0;h<w;h++){

				if(g.indexOf(objeto[h].guia)==-1){

					fila = tabla1.getRowByIndex(h);

					fila.aplicacion = "";

					tabla1.updateRowByIndex(h,fila);

				}

			}

			for(var i=0;i<objeto.length;i++){

				if(g.indexOf(objeto[i].guia)==-1){

					var obj		 	= new Object();

					obj.guia 		= objeto[i].guia;

					obj.fecha	   	= objeto[i].fecha;

					obj.fecha_venc	= objeto[i].fechavencimiento;

					obj.factura	   	= objeto[i].foliofactura;

					obj.importe  	= objeto[i].importe;

					obj.saldo 	   	= objeto[i].saldoactual;

					obj.aplicacion 	= "X";

					tabla1.add(obj);

					

				}

			}

			u.factura.value="";

			u.abonar.value ="";

			totalAbonar();

		}else{

			//alerta('El # factura no existe','¡Atención!','factura');

			return false;

		}

}

/***/

function totalAbonar(){

	$total=0;

	var cont	= tabla1.getRecordCount();

	for(var i=0;i<cont;i++){

		var fact 			= tabla1.getRowByIndex(i)['aplicacion'];

		if(fact == "X"){

			$total+=parseFloat(tabla1.getRowByIndex(i)['importe']);

		}

	}

	u.abonar.value=convertirMoneda(parseFloat($total).toFixed(2));

	var saldocon=parseFloat(u.sandoantes.value.replace("$ ","").replace(/,/,""))-parseFloat($total);

	

	u.saldocon.value=convertirMoneda(parseFloat(saldocon).toFixed(2));

}







function Limpiar(){

	u.sucursal.value	="";

	u.fecha.value		="";

	u.folio.value		="";

	u.idcliente.value	="";

	u.cliente.value		="";

	u.descripcion.value	="";

	u.cobrador.value	=0;

	u.factura.value		="";

	u.guia.value		="";

	u.abonar.value		="";

	u.saldocon.value	="0.00";

	u.sandoantes.value	="0.00";

	u.guardar.style.visibility="visible";

	obtenerDetalles();

	tabla1.clear();

}





function seleccionar(){

	var factura = tabla1.getSelectedRow()['factura'];

	var cont	= tabla1.getRecordCount();

	for(var i=0;i<cont;i++){

		var fact 			= tabla1.getRowByIndex(i)['factura'];

		if(fact == factura){

			var obj 			= Object();

			var arr				=tabla1.getRowByIndex(i);

			obj.guia	 		= arr.guia;

			obj.fecha	 	   	= arr.fecha;

			obj.fecha_venc 		= arr.fecha_venc;

			obj.factura		   	= arr.factura;

			obj.importe		  	= arr.importe;

			obj.saldo	 	   	= arr.saldo;

			obj.aplicacion   	= "X";

			tabla1.updateRowByIndex(i, obj);

			

		}else{

			var fila = new Object();	

			fila = tabla1.getRowByIndex(i);

			fila.aplicacion = "";

			tabla1.updateRowByIndex(i,fila);

			

			var obj 			= Object();

			var arr				=tabla1.getRowByIndex(i);

			obj.guia	 		= arr.guia;

			obj.fecha	 	   	= arr.fecha;

			obj.fecha_venc 		= arr.fecha_venc;

			obj.factura		   	= arr.factura;

			obj.importe		  	= arr.importe;

			obj.saldo	 	   	= arr.saldo;

			obj.aplicacion   	= " ";

			tabla1.updateRowByIndex(i, obj);

		}

		

	}totalAbonar();

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

/**/

function ejecutarSubmit(){

	var cont	= tabla1.getRecordCount();

	var factura="";

	for(var i=0;i<cont;i++){

		var fact 			= tabla1.getRowByIndex(i)['aplicacion'];

		if(fact == "X"){

			factura = tabla1.getRowByIndex(i)['factura'];

		}

	}

	

	var nc 						= u.nc.value.replace("$ ","").replace(/,/g,"");

	var nc_folio				= u.nc_folio.value.replace("$ ","").replace(/,/g,"");

	

	consultaTexto("mostrarValidar", "abonodecliente_con.php?accion=5&idsucursal="+u.idsucursal.value

						+"&folio="+u.folio.value

						+"&idcliente="+u.idcliente.value

						+"&descripcion="+u.descripcion.value

						+"&cobrador="+u.cobrador.value

						+"&abonar="+parseFloat(u.abonar.value.replace("$ ","").replace(/,/,""))

						+"&saldocon="+parseFloat(u.saldocon.value.replace("$ ","").replace(/,/,""))

						+"&sandoantes="+parseFloat(u.sandoantes.value.replace("$ ","").replace(/,/,""))

						+"&factura="+factura

						+"&efectivo="+u.efectivo.value.replace("$ ","").replace(/,/,"")

						+"&cheque="+u.cheque.value.replace("$ ","").replace(/,/,"")

						+"&banco="+u.banco.value.replace("$ ","").replace(/,/,"")

						+"&ncheque="+u.ncheque.value

						+"&tarjeta="+u.tarjeta.value.replace("$ ","").replace(/,/,"")

						+"&transferencia="+u.transferencia.value.replace("$ ","").replace(/,/,"")

						+"&nc="+u.nc.value.replace("$ ","").replace(/,/,"")

						+"&nc_folio="+u.nc_folio.value.replace("$ ","").replace(/,/,"")

						+"&tipo="+u.accion.value

						+"&valram="+Math.random());

}

	

	function mostrarValidar(datos){

		if(datos.indexOf("ok")>-1){

			info('Los datos han sido guardados correctamente','');

			u.guardar.style.visibility="hidden";

		}else{

			alerta3('Hubo un error al guardar '+datos,'¡Atención!');

		}

	}



function validarDatos(){

	if(u.sucursal.value==""){

		alerta('Debe capturar Sucursal','¡Atención!','sucursal');

		return false;

	}else if(u.cliente.value==""){

		alerta('Debe capturar Cliente','¡Atención!','cliente');

		return false;

	}else if(u.descripcion.value==""){

		alerta('Debe capturar Descripcion','¡Atención!','descripcion');

		return false;

	}else if(u.cobrador.value==0){

		alerta('Debe capturar Cobrador','¡Atención!','descripcion');

		return false;

	}else if(!tabla1.getRecordCount()>0){

		alerta3('Debe capturar Factura o Guia');

		return false;

	}



limpiarFormasPago();

//parseFloat(u.abonar.value.replace("$ ","").replace(/,/,""))

		abrirVentanaFija('abonocliente_formapago.php?total='+u.abonar.value+'&cliente='+u.idcliente.value, 600, 400, 'ventana', 'Forma de Pago');

}



	function limpiarFormasPago(){

		u.efectivo.value	="";

		u.cheque.value		="";

		u.banco.value		="";

		u.ncheque.value		="";

		u.tarjeta.value		="";

		u.transferencia.value="";

	}





	function OptenerAbonoCliente(id){

		consultaTexto("mostrarAbonoCliente", "abonodecliente_con.php?accion=6&id="+id+"&valram="+Math.random());

	}



	function mostrarAbonoCliente(datos){

			var objeto = eval("("+convertirValoresJson(datos)+")");

			tabla1.clear();

			u.folio.value		=objeto.folio;

			u.fecha.value 		=objeto.fecharegistro;

			u.idsucursal.value	=objeto.idsucursal;

			u.sucursal.value 	=objeto.sucursal;

			u.idcliente.value 	=objeto.idcliente;

			u.cliente.value		=objeto.cliente;

			u.descripcion.value =objeto.descripcion;

			u.cobrador.value 	=objeto.cobrador;

			u.abonar.value 		=convertirMoneda(objeto.abonar);

			u.saldocon.value 	=convertirMoneda(objeto.saldocon);

			u.sandoantes.value 	=convertirMoneda(objeto.saldoantesdeaplicar);

			u.efectivo.value 	="$ "+objeto.efectivo;

			u.banco.value		="$ "+objeto.banco;

			u.cheque.value 		="$ "+objeto.cheque;

			u.ncheque.value 	=objeto.ncheque;

			u.tarjeta.value 	="$ "+objeto.tarjeta;

			u.transferencia.value=objeto.transferencia;

			//u.campo.value =objeto.factura;

			consultaTexto("mostrarFactura", "abonodecliente_con.php?accion=7&factura="+objeto.factura+"&cliente="+objeto.idcliente+"&valram="+Math.random());

			u.accion.value		="modificar";

			u.guardar.style.visibility="hidden";

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



	

</script>

<body>

<form id="form1" name="form1" method="post" action="">

  <br>

  <table width="518" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="426" class="FondoTabla Estilo4">ABONO DEL CLIENTE</td>

  </tr>

  <tr>

    <td height="142"><div align="center">

      <table width="259" border="0" cellpadding="0" cellspacing="0">

        <tr>

          <td width="352"><table width="398" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="271" height="11"><table width="513" border="0" cellpadding="0" cellspacing="0">

                <tr>

                  <td width="497"><table width="278" border="0" cellpadding="0" cellspacing="0">

                    <tr>

                      <td width="42">Sucursal</td>

                      <td width="80">

					  

                      <input name="sucursal" type="text" id="sucursal" value="<?=$sucurasl ?>" style="width:80px;background:#FFFF99"  onKeyPress="return tabular(event,this)" readonly=""/>

                      <input name="idsucursal" type="hidden" value="<?=$idsucursal ?>" style="width:80px" />                      </td>

                      <td width="25"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=GuiaVentanilla&usuario=Admin&funcion=mostrarSucursales', 370, 500, 'ventana', 'Inicio de Sesión Secundaria');"></div></td>

                      <td width="30">Fecha</td>

                      <td width="103"><span class="Tablas">

                        <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly="" onKeyPress="return tabular(event,this)"/>

                      </span></td>

                      <td width="36"> <div align="right">Folio</div></td>

                      <td width="54"><span class="Tablas">

                        <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio?>" readonly="" onKeyPress="return tabular(event,this)"/>

                      </span></td>

                      <td width="55"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarAbonoDeClienteGen.php?funcion=OptenerAbonoCliente', 625, 550, 'ventana', 'Buscar Guia')"></div></td>

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

                  <td><table width="517" border="0" cellpadding="0" cellspacing="0">

                    <tr>

                      <td><span style="width:62px">Cliente</span></td>

                      <td><table width="463" border="0" cellpadding="0" cellspacing="0">

                        <tr>

                          <td style="width:140px"><table width="91%" border="0" cellspacing="0" cellpadding="0">

                              <tr>

                                <td style="width:101px"><span class="Tablas">

                                  <input name="idcliente" type="text" class="Tablas" id="idcliente" style="width:100px" value="<?=$idcliente ?>" onKeyPress="if(event.keyCode=='13'){pedirCliente(this.value);};return tabular(event,this)"/>

                                </span></td>

                                <td><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen2.php?funcion=pedirCliente', 625, 450, 'ventana', 'Buscar Cliente')"></div></td>

                              </tr>

                          </table>                            <div align="left"></div></td>

                          <td><label><span class="Tablas">

                            <input name="cliente" type="text" class="Tablas" id="cliente" style="width:320px;background:#FFFF99" value="<?=$cliente ?>" onKeyPress="return tabular(event,this)" readonly=""/>

                          </span></label></td>

                          </tr>

                      </table></td>

                    </tr>

                    

                    <tr>

                      <td width="63" style="width:55px" >Descripci&oacute;n</td>

                      <td width="454"><span class="Tablas">

                        <input name="descripcion" type="text" class="Tablas" id="descripcion" style="width:460px" value="<?=$descripcion ?>" onKeyPress="return tabular(event,this)"/>

                      </span></td>

                    </tr>

                    <tr>

                      <td>Cobrador</td>

                      <td><select name="cobrador" id="cobrador" style="width:460px">

                        <option value="0">.::Seleccione::.</option>

                        <?

							$s = "select id, concat_ws(' ', nombre, apellidopaterno, apellidomaterno) as nempledao from catalogoempleado where puesto = 22";

							$r = mysql_query($s,$l) or die($s);

							while($f=mysql_fetch_object($r)){

						?>

                        <option value="<?=$f->id?>">

                        <?=$f->nempledao?>

                        </option>

                        <?

							}

						?>

                      </select></td>

                    </tr>

                    <tr>

                      <td><span style="width:62px">Factura</span></td>

                      <td><table width="422" border="0" cellpadding="0" cellspacing="0">

                        <tr>

                          <td style="width:140px"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                              <tr>

                                <td style="width:101px"><span class="Tablas">

                                  <input name="factura" type="text" class="Tablas" id="factura" style="width:100px" value="<?=$factura ?>"  onKeyPress="if(event.keyCode=='13'){obtenerFactura(this.value)};return tabular(event,this)" />

                                </span></td>

                                <td><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarFacturasGen.php?funcion=obtenerFactura', 625, 450, 'ventana', 'Buscar Factura')" ></div></td>

                              </tr>

                          </table></td>

                          <td style="width:30px">Guia</td>

                          <td width="76"><span class="Tablas">

                            <input name="guia" type="text" class="Tablas" id="guia" style="width:135px" value="<?=$guia?>" onKeyPress="if(event.keyCode=='13'){obtenerGuia(this.value)};return tabular(event,this)"/>

                            </span>

                              <label></label></td>

                          <td  ><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarGuiasEmpresariales_VentanillaGen.php?funcion=obtenerGuia', 625, 550, 'ventana', 'Buscar Guia')" ></div></td>

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

                  <td class=""><table border="0" align="right" cellpadding="0" cellspacing="0">

                    <tr>

                      <td width="120">Total a Abonar<br></td>

                      <td width="116"><span class="Tablas">

                        <input name="abonar" type="text" class="Tablas" id="abonar" style="text-align:right;width:100px;background:#FFFF99" value="<?=$abonar ?>" readonly="" onKeyPress="return tabular(event,this)"/>

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

                  <td>&nbsp;</td>

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

                          <input type="hidden" value="" name="nc">

                          <input type="hidden" value="" name="nc_folio">

                          <input name="accion" type="hidden" id="accion">

                        </td>

                        <td width="48%"><table width="34%" height="12" border="0" align="right" cellpadding="0" cellspacing="0">

                          <tr>

                            <td width="38%"><div id="guardar" class="ebtn_guardar" onClick="validarDatos()"></div></td>

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

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'ABONOS DE CLIENTES';

</script>

</html>