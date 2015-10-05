<?

	require_once("../Conectar.php");

	$l = Conectarse("webpmm");

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Principal</title>

<link href="puntovta.css" rel="stylesheet" type="text/css">

<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="estilo_guia.css" rel="stylesheet" type="text/css" />

<!-- estilos y funciones para ventana modal -->

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">

<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>

<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>

<!-- funciones para tablas -->

<script type="text/javascript" src="../javascript/funciones_tablas.js"></script>

<!-- funciones para ajax -->

<script type="text/javascript" src="../javascript/ajax.js"></script>

<script>

	var tabla_valt1 	= "";

	var valt1 			= agregar_una_tabla("tablaevaluacion", "te_", 5, "Balance2└Balance","");

	ordenamiento_tabla(valt1,"center,left,left,right,right,right,right");

	var sucursalorigen 	= 0;

	function paracelda(valor, tamano, alineacion){

		return '<input type="text" readonly="true" style=" width:'+tamano+'px;font:tahoma; font-size:9px; text-align:'+alineacion+'; font-weight:bold; border:none;background:none" value="'+valor+'" />';

	}



	function numcredvar(cadena){ 

		var flag = false; 

		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 

		var num = cadena.split(',').join(''); 

		cadena = Number(num).toLocaleString(); 

		if(flag) cadena += '.'; 

		return cadena;

	}



	function limpiar_remitente(){

		u = document.all;

		u.idremitente.value 	= "";

		u.rem_rfc.value 		= "";

		u.rem_cliente.value 	= "";

		u.rem_numero.value		= "";

		if(document.getElementById("rem_calle"))

			u.rem_calle.value		= "";

		u.rem_cp.value			= "";

		u.rem_colonia.value		= "";

		u.rem_poblacion.value	= "";

		u.rem_telefono.value	= "";

	}

	function limpiar_destinatario(){

		u = document.all;

		u.iddestinatario.value 	= "";

		u.des_rfc.value 		= "";

		u.des_cliente.value 	= "";

		u.des_numero.value		= "";

		u.des_cp.value			= "";

		if(document.getElementById("des_calle"))

			u.des_calle.value		= "";

		u.des_colonia.value		= "";

		u.des_poblacion.value	= "";

		u.des_telefono.value	= "";

	}

	function limpiar_evaluacion(){

			u = document.all;

			

			if(tabla_valt1==""){

				tabla_valt1 = u.detalle.innerHTML;

			}

			u.detalle.innerHTML = tabla_valt1;

			reiniciar_indice(valt1);

			

			u.estado.value 				= "";

			u.chocurre.checked			= false;

			u.destino.value 			= "";

			u.destino_hidden.value		= "";

			u.sucdestino.value 			= "";

			u.sucdestino_hidden.value 	= "";

			u.txtocu.value 				= "";

			u.txtead.value 				= "";

			

			u.totalpaquetes.value		= "";

			u.totalpeso.value			= "";

			u.totalvolumen.value		= "";

			

			u.chkemplaye.checked 		= false;

			u.chkbolsaempaque.checked 	= false;

			u.chkavisocelular.checked 	= false;

			u.chkvalordeclarado.checked = false;

			u.chkacuserecibo.checked 	= false;

			u.chkcod.checked 			= false;

			u.chkrecoleccion.checked 	= false;

			u.chkemplaye.readOnly 		= false;

			u.chkbolsaempaque.readOnly 	= false;

			u.chkavisocelular.readOnly 	= false;

			u.chkvalordeclarado.readOnly= false;

			u.chkacuserecibo.readOnly 	= false;

			u.chkcod.readOnly 			= false;

			u.chkrecoleccion.readOnly 	= false;

			

			u.txtavisocelular1.value 	= "";

			u.txtavisocelular2.value 	= "";

			u.txtacuserecibo.value 		= "";

			u.txtcod.value 				= "";

			u.txtemplaye.value 			= "";

			u.txtbolsaempaque1.value 	= "";

			u.txtbolsaempaque2.value 	= "";

			u.txtdeclarado.value 		= "";

			u.txtobservaciones.value	= "";

			u.txtrestrinccion.value		= "";

			

			u.txtavisocelular1h.value 	= "";

			u.txtavisocelular2h.value 	= "";

			u.txtacusereciboh.value 	= "";

			u.txtcodh.value 			= "";

			u.txtemplayeh.value 		= "";

			u.txtbolsaempaque1h.value 	= "";

			u.txtbolsaempaque2h.value 	= "";

			u.txtbolsaempaque3h.value 	= "";

			

			u.txtrecoleccion.value		= "";

			u.txtrecoleccionh.value		= "";

			

			u.flete.value				= "";

			u.t_txtdescuento1.value		= "";

			u.t_txtdescuento2.value		= "";

			u.t_txtead.value			= "";

			u.t_txteadh.value			= "";

			u.t_txtrecoleccion.value	= "";

			u.t_txtseguro.value			= "";

			u.t_txtotros.value			= "";

			u.t_txtexcedente.value		= "";

			u.t_txtcombustible.value	= "";

			u.t_txtsubtotal.value		= "";

			u.t_txtiva.value			= "";

			u.t_txtivaretenido.value	= "";

			u.t_txttotal.value			= "";

			u.pagoregistrado.value		= 0;

			u.efectivo.value			= "";

			u.cheque.value				= "";

			u.ncheque.value				= "";

			u.banco.value				= "";

			u.tarjeta.value				= "";

			u.transferencia.value		= "";

			

	}

	function mostrarEvaluaciones(){

		document.location.href = "../guias/guia.php?funcion2=mostrarEvaluaciones()";

	}

	

	function mostrarGuias(){

		abrirVentanaFija('../buscadores_generales/buscarGuiasGen.php?funcion=solicitarGuia&tipo=evaluacion', 650, 450, 'ventana', 'Busqueda')

	}

	function buscarUnaGuia(folioguia){

		solicitarGuia(folioguia);

	}

	function solicitarGuia(folio){

		consulta("respuestaGuia","guia_consulta.php?accion=5&folio="+folio);

	}

	function respuestaGuia(datos){

		u = document.all;

		

		limpiar_evaluacion();

		limpiar_remitente();

		limpiar_destinatario();

		

		var encon = datos.getElementsByTagName('encontrados').item(0).firstChild.data;

		if(encon>0){

			var id						= datos.getElementsByTagName('id').item(0).firstChild.data;

			var evaluacion				= datos.getElementsByTagName('evaluacion').item(0).firstChild.data;

			var fecha					= datos.getElementsByTagName('fecha').item(0).firstChild.data;

			var fechaentrega			= datos.getElementsByTagName('fechaentrega').item(0).firstChild.data;

			var factura					= datos.getElementsByTagName('factura').item(0).firstChild.data;

			var estado					= datos.getElementsByTagName('estado').item(0).firstChild.data;

			var tipoflete				= datos.getElementsByTagName('tipoflete').item(0).firstChild.data;

			var ocurre					= datos.getElementsByTagName('ocurre').item(0).firstChild.data;

			var idsucursalorigen		= datos.getElementsByTagName('idsucursalorigen').item(0).firstChild.data;

			var ndestino				= datos.getElementsByTagName('ndestino').item(0).firstChild.data;

			var nsucdestino				= datos.getElementsByTagName('nsucdestino').item(0).firstChild.data;

			var condicionpago			= datos.getElementsByTagName('condicionpago').item(0).firstChild.data;

			

			var idremitente				= datos.getElementsByTagName('idremitente').item(0).firstChild.data;

			var rncliente				= datos.getElementsByTagName('rncliente').item(0).firstChild.data;

			var rrfc					= datos.getElementsByTagName('rrfc').item(0).firstChild.data;

			var rcelular				= datos.getElementsByTagName('rcelular').item(0).firstChild.data;

			var rcalle					= datos.getElementsByTagName('rcalle').item(0).firstChild.data;

			var rnumero					= datos.getElementsByTagName('rnumero').item(0).firstChild.data;

			var rcp						= datos.getElementsByTagName('rcp').item(0).firstChild.data;

			var rpoblacion				= datos.getElementsByTagName('rpoblacion').item(0).firstChild.data;

			var rtelefono				= datos.getElementsByTagName('rtelefono').item(0).firstChild.data;

			var rcolonia				= datos.getElementsByTagName('rcolonia').item(0).firstChild.data;

			

			var iddestinatario			= datos.getElementsByTagName('iddestinatario').item(0).firstChild.data;

			var dncliente				= datos.getElementsByTagName('dncliente').item(0).firstChild.data;

			var drfc					= datos.getElementsByTagName('drfc').item(0).firstChild.data;

			var dcelular				= datos.getElementsByTagName('dcelular').item(0).firstChild.data;

			var dcalle					= datos.getElementsByTagName('dcalle').item(0).firstChild.data;

			var dnumero					= datos.getElementsByTagName('dnumero').item(0).firstChild.data;

			var dcp						= datos.getElementsByTagName('dcp').item(0).firstChild.data;

			var dpoblacion				= datos.getElementsByTagName('dpoblacion').item(0).firstChild.data;

			var dtelefono				= datos.getElementsByTagName('dtelefono').item(0).firstChild.data;

			var dcolonia				= datos.getElementsByTagName('dcolonia').item(0).firstChild.data;

			

			var entregaocurre			= datos.getElementsByTagName('entregaocurre').item(0).firstChild.data;

			var entregaead				= datos.getElementsByTagName('entregaead').item(0).firstChild.data;

			var restrinccion			= datos.getElementsByTagName('restrinccion').item(0).firstChild.data;

			var totalpaquetes			= datos.getElementsByTagName('totalpaquetes').item(0).firstChild.data;

			var totalpeso				= datos.getElementsByTagName('totalpeso').item(0).firstChild.data;

			var totalvolumen			= datos.getElementsByTagName('totalvolumen').item(0).firstChild.data;

			var emplaye					= datos.getElementsByTagName('emplaye').item(0).firstChild.data;

			var bolsaempaque			= datos.getElementsByTagName('bolsaempaque').item(0).firstChild.data;

			var totalbolsaempaque		= datos.getElementsByTagName('totalbolsaempaque').item(0).firstChild.data;

			var avisocelular			= datos.getElementsByTagName('avisocelular').item(0).firstChild.data;

			var celular					= datos.getElementsByTagName('celular').item(0).firstChild.data;

			var valordeclarado			= datos.getElementsByTagName('valordeclarado').item(0).firstChild.data;

			var acuserecibo				= datos.getElementsByTagName('acuserecibo').item(0).firstChild.data;

			var cod						= datos.getElementsByTagName('cod').item(0).firstChild.data;

			var recoleccion				= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;

			var observaciones			= datos.getElementsByTagName('observaciones').item(0).firstChild.data;

			var tflete					= datos.getElementsByTagName('tflete').item(0).firstChild.data;

			var tdescuento				= datos.getElementsByTagName('tdescuento').item(0).firstChild.data;

			var ttotaldescuento			= datos.getElementsByTagName('ttotaldescuento').item(0).firstChild.data;

			var tcostoead				= datos.getElementsByTagName('tcostoead').item(0).firstChild.data;

			var trecoleccion			= datos.getElementsByTagName('trecoleccion').item(0).firstChild.data;

			var tseguro					= datos.getElementsByTagName('tseguro').item(0).firstChild.data;

			var totros					= datos.getElementsByTagName('totros').item(0).firstChild.data;

			var texcedente				= datos.getElementsByTagName('texcedente').item(0).firstChild.data;

			var tcombustible			= datos.getElementsByTagName('tcombustible').item(0).firstChild.data;

			var subtotal				= datos.getElementsByTagName('subtotal').item(0).firstChild.data;

			var tiva					= datos.getElementsByTagName('tiva').item(0).firstChild.data;

			var ivaretenido				= datos.getElementsByTagName('ivaretenido').item(0).firstChild.data;

			var total					= datos.getElementsByTagName('total').item(0).firstChild.data;

			var efectivo				= datos.getElementsByTagName('efectivo').item(0).firstChild.data;

			var cheque					= datos.getElementsByTagName('cheque').item(0).firstChild.data;

			var banco					= datos.getElementsByTagName('banco').item(0).firstChild.data;

			var ncheque					= datos.getElementsByTagName('ncheque').item(0).firstChild.data;

			var tarjeta					= datos.getElementsByTagName('tarjeta').item(0).firstChild.data;

			var trasferencia			= datos.getElementsByTagName('trasferencia').item(0).firstChild.data;

			

			parent.frames[4].document.all.folioSeleccionado.innerHTML = id;

			u.fecha.value				= fecha;

			u.estado.value				= estado;

			u.lstflete.value			= tipoflete;

			if(ocurre==1)

				u.chocurre.checked		= true;

			else

				u.chocurre.checked		= false;

			

			u.destino.value			= ndestino;

			u.sucdestino.value	= nsucdestino;

			

			u.sltpago.value	= condicionpago;

			u.idremitente.value	= idremitente;

			u.rem_rfc.value	= rrfc;

			u.rem_cliente.value	= rncliente;

			u.rem_calle.value	= rcalle;

			u.rem_numero.value	= rnumero;

			u.rem_cp.value	= rcp;

			u.rem_colonia.value	= rcolonia;

			u.rem_poblacion.value	= rpoblacion;

			u.rem_telefono.value	= rtelefono;

			u.des_rfc.value	= drfc;

			u.iddestinatario.value	= iddestinatario;

			u.des_cliente.value	= dncliente;

			u.des_calle.value	= dcalle;

			u.des_numero.value	= dnumero;

			u.des_cp.value	= dcp;

			u.des_colonia.value	= dcolonia;

			u.des_poblacion.value	= dpoblacion;

			u.des_telefono.value	= dtelefono;

			

			u.txtocu.value = entregaocurre;

			u.txtead.value = entregaead;

			u.txtrestrinccion.value = restrinccion;

			

			u.totalpeso.value = totalpeso;

			u.totalpaquetes.value = totalpaquetes;

			u.totalvolumen.value = totalvolumen;

			

			u.txtemplaye.value = (emplaye=="0")?"":"$ "+numcredvar(emplaye);

			u.txtacuserecibo.value = (acuserecibo=="0")?"":"$ "+numcredvar(acuserecibo);

			u.txtbolsaempaque1.value = (bolsaempaque=="0")?"":"$ "+numcredvar(bolsaempaque);

			u.txtbolsaempaque2.value = (totalbolsaempaque=="0")?"":"$ "+numcredvar(totalbolsaempaque);

			u.txtcod.value = (cod=="0")?"":"$ "+numcredvar(cod);

			u.txtavisocelular1.value = (avisocelular=="0")?"":"$ "+numcredvar(avisocelular);

			u.txtavisocelular2.value = celular;

			u.txtrecoleccion.value = (recoleccion=="0")?"":"$ "+numcredvar(recoleccion);

			u.txtdeclarado.value = (valordeclarado=="0")?"":"$ "+numcredvar(valordeclarado);

			

			u.flete.value = tflete;

			u.t_txtdescuento1.value = (tdescuento=="0")?"":tdescuento+" %";

			u.t_txtdescuento2.value = (ttotaldescuento=="0")?"":"$ "+numcredvar(ttotaldescuento);

			u.t_txtead.value = (tcostoead=="0")?"":"$ "+numcredvar(tcostoead);

			u.t_txtrecoleccion.value = (trecoleccion=="0")?"":"$ "+numcredvar(trecoleccion);

			u.t_txtseguro.value = (tseguro=="0")?"":"$ "+numcredvar(tseguro);

			u.t_txtotros.value = (totros=="0")?"":"$ "+numcredvar(totros);

			u.t_txttotal.value = total;

			u.txtobservaciones.value = observaciones;

			

			u.t_txtexcedente.value = (texcedente=="0")?"":"$ "+numcredvar(texcedente);

			u.t_txtcombustible.value = (tcombustible=="0")?"":"$ "+numcredvar(tcombustible);

			u.t_txtsubtotal.value = "$ "+numcredvar(subtotal);

			u.t_txtiva.value = (tiva=="0")?"":"$ "+numcredvar(tiva);

			u.t_txtivaretenido.value = (ivaretenido=="0")?"":"$ "+numcredvar(ivaretenido);

		}else{

			alerta("No se encontro la guia buscada", "¡Atencion!","fecha");

		}

	}

</script>

</head>



<body>

<form id="form1" name="form1" method="post" action="">

<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">

  <tr>

    <td colspan="2"><input name=DetalleGrip type=hidden id=DetalleGrip value="<?=$DetalleGrip ?>"></td>

  </tr> 

  <tr>

    <td colspan="2">

      <table width="654" border="0" align="center" cellpadding="0" cellspacing="0">

      <tr>

        <td width="5%" class="Tablas">Fecha:</td>

        <td width="16%">

		<?

			$s = "select date_format(current_date, '%d/%m/%Y') as fecha";

			$r = mysql_query($s,$l) or die($s);

			$f = mysql_fetch_object($r);

		?>

            &nbsp;&nbsp;<input name="fecha" readonly="true" type="text" id="fecha" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$f->fecha ?>" size="13" align="top" /></td>

        <td width="7%" class="Tablas">Estado:</td>

        <td colspan="2"><input name="estado" readonly="true" type="text" id="estado" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$estado ?>" size="20" align="top" /> <input type="hidden" name="folioevaluacion" value=""></td>

        <td width="4%">&nbsp;</td>

        <td width="7%"></td>

        <td width="8%" class="Tablas">&nbsp;</td>

        <td width="23%">&nbsp;</td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td colspan="2" ><table width="654" border="0" align="center" cellpadding="0" cellspacing="0">

      <tr>

        <td width="7%" class="Tablas">T. Flete:</td>

        <td width="14%"><select name="lstflete" disabled id="lstflete" style="width:77px; font-size:9px">

            <option value="0">Pagado</option>

            <option value="1">Por Cobrar</option>

        </select></td>

        <td width="3%"><input disabled name="chocurre" onClick="if(this.checked==false){document.all.t_txtead.value = document.all.t_txteadh.value}else{document.all.t_txtead.value = ''} calculartotales();" type="checkbox" id="chocurre" style="width:8px; height:8px" value="SI" /></td>

        <td width="6%"><span class="Tablas">Ocurre

        </span></td>

        <td width="7%" class="Tablas">Destino:</td>

        <td width="16%">

        <input type="text" name="destino" readonly="true" id="destino" style="background:#FFFF99;width:100px; font-size:9px" 

        onkeyup="ajax_showOptions(this,'getCountriesByLetters',event,'buscarSucursales.php')" 

        onChange="devolverDestino()" onKeyPress="if(event.keyCode==13){devolverDestino(); document.all.idremitente.focus();}"

        onBlur="devolverDestino()"> 

        <input type="hidden" name="destino_hidden">

               </td>

        <td width="10%"><span class="Tablas">Suc. Destino:</span></td>

        <td width="13%"><input name="sucdestino" type="text" id="sucdestino" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$destino?>" poblacion="" size="20" /><input type="hidden" name="sucdestino_hidden"></td>

        <td width="9%"><span class="Tablas">Cond. Pago:</span></td>

        <td width="13%">&nbsp;

            <select name="sltpago" disabled id="sltpago" style="width:70px; font-size:9px">

              <option value="0">Contado</option>

              <option value="1">Credito</option>

          </select></td>

        <td width="2%">&nbsp;</td>

      </tr>

    </table></td>

  </tr>

  <tr>

  	<td colspan="2" align="center">    </td>

  </tr>

  <tr>

    <td colspan="2"><table width="654" border="0" align="center">

      <tr>

        <td width="655"><table width="644" border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#016193">

            <tr>

              <td width="290" class="FondoTabla">Remitente</td>

              <td width="290" class="FondoTabla">Destinatario</td>

            </tr>

            <tr>

              <td><table width="100%" border="0" cellpadding="0" cellspacing="1">

                  <tr>

                    <td width="16%"><span class="Tablas"># Cliente: </span></td>

                    <td><input name="idremitente" type="text" readonly onKeyPress="if(event.keyCode==13){devolverRemitente(this.value)}" style="font:tahoma; font-size:9px;background:#FFFF99;" value="<?=$remitente ?>" size="4" />

                      &nbsp;&nbsp;<img src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverRemitente', 625, 418, 'ventana', 'Busqueda')" /></td>

                    <td width="55%" colspan="3">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>

                        <input name="rem_rfc" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="24" /></td>

                  </tr>

                  <tr>

                    <td><span class="Tablas">Cliente:</span></td>

                    <td colspan="4">

                    <table border="0" cellpadding="0" cellspacing="0">

                    <tr>

                    <td>

                    <input name="rem_cliente" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" 

                    value="<?=$rcliente ?>" size="54" />

                    </td>

                    <td align="right" valign="middle">

                    <img src="../img/Boton_Agregarchico.gif" alt="Agregar Dirección" style="cursor:hand" onClick="if(document.all.idremitente.value==''){ alerta('Proporcione el id del remitente','¡Atencion!','idremitente') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverRemitente('+document.all.idremitente.value+')&idcliente='+document.all.idremitente.value, 460, 395, 'ventana', 'DATOS DIRECCION')}">

                    </td>

                    </table>

                    </td>

                  </tr>

                  <tr>

                    <td><span class="Tablas">Calle:</span></td>

                    <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">

                      <tr>

                        <td width="153" height="16" id="celda_rem_calle"><input name="rem_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" /><input type="hidden" name="rem_direcciones"></td>

                        <td width="97"><span class="Tablas">Numero: </span><span class="Tablas">

                          <input name="rem_numero" type="text" readonly="true" style=" width:50px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rnumero ?>" />

                        </span></td>

                      </tr>

                    </table></td>

                  </tr>

                  <tr>

                    <td><span class="Tablas">CP:</span></td>

                    <td width="29%"><input name="rem_cp" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcp ?>" size="15" /></td>

                    <td colspan="3"><span class="Tablas">Colonia:&nbsp;&nbsp;

                          <input name="rem_colonia" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcolonia ?>" size="24" />

                    </span></td>

                  </tr>

                  <tr>

                    <td><span class="Tablas">Poblaci&oacute;n:</span></td>

                    <td colspan="4"><input name="rem_poblacion" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rpoblacion ?>" size="25" />

                        <span class="Tablas">&nbsp;Tel&eacute;fono:

                          <input name="rem_telefono" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rtelefono ?>" size="14" />

                      </span></td>

                  </tr>

              </table></td>

              <td><table width="95%" border="0" cellpadding="0" cellspacing="1">

                <tr>

                  <td><input name="iddestinatario" readonly onKeyPress="if(event.keyCode==13){devolverDestinatario(this.value)}" type="text" style="font:tahoma; font-size:9px;background:#FFFF99;" value="<?=$remitente ?>" size="4" />

                    &nbsp;&nbsp;<img src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverDestinatario', 625, 418, 'ventana', 'Busqueda')"/></td>

                  <td width="55%" colspan="3">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>

                      <input name="des_rfc" type="text" readonly="true" id="rrfc22" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="24" /></td>

                </tr>

                <tr>

                  <td colspan="4">

                  <table border="0" cellpadding="0" cellspacing="0">

                  <tr>

                  <td>

                  <input name="des_cliente" readonly="true" type="text" 

                  style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcliente ?>" size="54" />

                  </td>

                    <td align="right" valign="middle">

                    <img src="../img/Boton_Agregarchico.gif" alt="Agregar Dirección" style="cursor:hand" onClick="if(document.all.iddestinatario.value==''){ alerta('Proporcione el id del remitente','¡Atencion!','iddestinatario') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverDestinatario('+document.all.iddestinatario.value+')&idcliente='+document.all.iddestinatario.value, 460, 395, 'ventana', 'DATOS DIRECCION')}">

                    </td>

                    </table>

                  </td>

                </tr>

                <tr>

                  <td colspan="4">

                  <table border="0" cellpadding="0" cellspacing="0">

                      <tr>

                        <td width="153" height="16" id="celda_des_calle"><input name="des_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" /><input type="hidden" name="des_direcciones"></td>

                        <td width="97"><span class="Tablas">Numero: </span><span class="Tablas">

                          <input name="des_numero" type="text" readonly="true" style=" width:50px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rnumero ?>" />

                        </span></td>

                      </tr>

                    </table>

                  </td>

                </tr>

                <tr>

                  <td width="29%"><input name="des_cp" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcp ?>" size="15" /></td>

                  <td colspan="3"><span class="Tablas">Colonia:&nbsp;&nbsp;

                        <input name="des_colonia" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcolonia ?>" size="24" />

                  </span></td>

                </tr>

                <tr>

                  <td colspan="4"><input name="des_poblacion" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rpoblacion ?>" size="25" />

                      <span class="Tablas">&nbsp;Tel&eacute;fono:

                        <input name="des_telefono" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rtelefono ?>" size="14" />

                    </span></td>

                </tr>

              </table></td>

            </tr>

        </table></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td colspan="2"><table width="654" border="0" align="center">

      <tr>

        <td width="440"><table width=435 border=0 cellspacing=0 cellpadding=0>

            <tr>

              <td width="7" height=16   background="../img/borde1_1.jpg" style="background-repeat:no-repeat; background-position:right"><img src="../img/space.gif" alt="d" ></td>

              <td width="43" background="../img/borde1_2.jpg" class="style5">Cant</td>

              <td width="110" background="../img/borde1_2.jpg" class="style5">Descripci&oacute;n</td>

              <td width="97" background="../img/borde1_2.jpg" class="style5">Contenido</td>

              <td width="42" background="../img/borde1_2.jpg" class="style5" align="right">Peso</td>

              <td width="46" background="../img/borde1_2.jpg" class="style5" align="right">Vol</td>

              <td width="68" background="../img/borde1_2.jpg" class="style5" align="right">Importe</td>

              <td width="12" background="../img/borde1_2.jpg" class="style5"><img src="../img/space.gif" alt="d"></td>

              <td width="10"  background="../img/borde1_3.jpg" style="background-repeat:no-repeat"><img src="../img/space.gif" alt="d" ></td>

            </tr>

            <tr>

              <td colspan=9 align=right>

              	<div id=detalle name="detalle" style=" height:80px; overflow:auto" align=left>

                  <table width=415 border=0 cellspacing=0 cellpadding=0 id="tablaevaluacion" style="font:tahoma; font-size:9px;" alagregar="" alborrar="">

                  	<tr>

                      <td width="45" class="style5" ></td>

                      <td width="111" class="style5"></td>

                      <td width="101" class="style5"></td>

                      <td width="41" class="style5"></td>

                      <td width="45" class="style5"></td>

                      <td width="71" class="style5"></td>

                      <td width="1" class="style5"></td>

                    </tr>

                    <?

					$line = 0;

					while($line<6){ 

					?>

                    <tr id="te_<?=$line?>" class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>">

                      <td height=16 class="style3" align="center" ><input type="text" readonly="true" border="0"  class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>" style=" width:40px;font:tahoma; font-size:9px; text-align:center" /></td>

					  <td class="style3" align="left"><input type="text" readonly="true" border="0"  class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>" style=" width:104px;font:tahoma; font-size:9px; text-align:left" /></td>

                      <td class="style3" align="left"><input type="text" readonly="true" border="0"  class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>" style=" width:95px;font:tahoma; font-size:9px; text-align:left" /></td>

                      <td class="style3" align="right"><input type="text" readonly="true" border="0"  class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>" style=" width:40px;font:tahoma; font-size:9px; text-align:right" /></td>

                      <td class="style3" align="right"><input type="text" readonly="true" border="0"  class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>" style=" width:40px;font:tahoma; font-size:9px; text-align:right" /></td>

                      <td align="right" class="style3" ><input type="text" readonly="true" border="0"  class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>" style=" width:60px;font:tahoma; font-size:9px; text-align:right" /></td>

                      <td align="right" class="style3" ></td>

</tr>

                    <?  

						$line ++ ; 

					} 

					?>

                  </table>

              	</div>

              </td>

            </tr>

        </table></td>

        <td width="204"><table width="180" height="90" border="0" align="center">

            <tr>

              <td width="194"><table width="185" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">

                  <tr>

                    <td width="172" class="FondoTabla">Tiempo de Entrega </td>

                  </tr>

                  <tr>

                    <td><table width="163" height="0" align="center" bordercolor="#016193">

                        <tr>

                          <td width="41" class="Tablas">Ocurre:</td>

                          <td width="40"><input name="txtocu" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$drfc ?>" size="5" /></td>

                          <td width="28" class="Tablas">EAD:</td>

                          <td width="34"><input name="txtead" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$drfc ?>" size="5" /></td>

                        </tr>

                    </table></td>

                  </tr>

              </table></td>

            </tr>

            <tr>

              <td><table width="185" height="0" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">

                  <tr>

                    <td width="200" class="FondoTabla">Restricciones</td>

                  </tr>

                  <tr>

                    <td><label>

                      <textarea name="txtrestrinccion" readonly style="width:180px; font-size:9px; background:#FFFF99;"></textarea>

                    </label></td>

                  </tr>

              </table></td>

            </tr>

        </table></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td><table width="325" border="0" align="right" cellpadding="0" cellspacing="0">

      <tr>

        <td width="56" class="Tablas">T. Paquetes: </td>

        <td width="43" class="Tablas"><input name="totalpaquetes" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:40px" value="<?=$rcp ?>"/></td>

        <td width="51" class="Tablas">T. Peso Kg: </td>

        <td width="48" class="Tablas"><input name="totalpeso" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:40px" value="<?=$rcp ?>" /></td>

        <td width="61" class="Tablas">T. Volumen: </td>

        <td width="66" class="Tablas"><input name="totalvolumen" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:60px" value="<?=$rcp ?>" /></td>

      </tr>

    </table></td>

    <td width="49%" rowspan="2"><table width="310" border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#74B051">

      <tr>

        <td width="257" bgcolor="#74B051"><span class="Estilo2">TOTALES</span>

        <input type="hidden" name="pc_ead">

        <input type="hidden" name="pc_recoleccion">

        <input type="hidden" name="pc_porcada">

        <input type="hidden" name="pc_costo">

        <input type="hidden" name="pc_tarifacombustible">

        <input type="hidden" name="pc_iva">

        <input type="hidden" name="pc_ivaretenido">

        <input type="hidden" name="pc_maximodescuento">

        </td>

      </tr>

      <tr>

        <td height="140"><table width="100%" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td class="Tablas">&nbsp;&nbsp;Flete:</td>

              <td class="Tablas"><input name="flete" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td class="Tablas">Excedente:</td>

              <td class="Tablas"><input name="t_txtexcedente" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td class="Tablas">&nbsp;&nbsp;Descuento:</td>

              <td class="Tablas"><input readonly="true" name="t_txtdescuento1" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="3" onKeyPress="if(event.keyCode==13 && this.readOnly==false){calcularDescuento()}else{return solonumeros(event);}" />

                  <input name="t_txtdescuento2" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="4" />                  <img src="../img/update.gif" onClick="if(validarDescuento()){ abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=GuiaVentanilla&usuario=Admin&funcion=permitirDescuento', 370, 500, 'ventana', 'Inicio de Sesión Secundaria');}" style="cursor:hand"></td>

<td class="Tablas">Combustible:</td>

              <td class="Tablas"><input name="t_txtcombustible" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td class="Tablas">&nbsp;&nbsp;EAD:</td>

              <td class="Tablas"><input readonly="true" name="t_txtead" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /><input name="t_txteadh" type="hidden" /></td>

              <td class="Tablas">Subtotal:</td>

              <td class="Tablas"><input name="t_txtsubtotal" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td class="Tablas">&nbsp;&nbsp;Recolecci&oacute;n:</td>

              <td class="Tablas"><input readonly="true" name="t_txtrecoleccion" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td class="Tablas">IVA:</td>

              <td class="Tablas"><input name="t_txtiva" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td width="24%" class="Tablas">&nbsp;&nbsp;Seguro:</td>

              <td class="Tablas"><input readonly="true" name="t_txtseguro" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td width="23%" class="Tablas">IVA Retenido: </td>

              <td width="17%" class="Tablas"><input name="t_txtivaretenido" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td class="Tablas">&nbsp;&nbsp;Otros:</td>

              <td class="Tablas"><input name="t_txtotros" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td class="Tablas">Total:</td>

              <td class="Tablas"><input name="t_txttotal" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td colspan="2" class="Tablas" valign="middle">

              <input type="hidden" value="0" name="pagoregistrado">

              <input type="hidden" value="" name="efectivo">

              <input type="hidden" value="" name="cheque">

              <input type="hidden" value="" name="ncheque">

              <input type="hidden" value="" name="banco">

              <input type="hidden" value="" name="tarjeta">

              <input type="hidden" value="" name="transferencia"></td>

<td class="Tablas">&nbsp;</td>

<td class="Tablas">&nbsp;</td>

</tr>

        </table></td>

      </tr>

    </table></td>

  </tr>

  

  

  <tr>

    <td width="51%" >

    <table border="0" align="right" cellpadding="0" cellspacing="0">

    	<tr>

        	<td width="320">

    <table width="320" height="140" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">

      <tr>

        <td width="434" class="FondoTabla">Servicios</td>

      </tr>

      <tr>

        <td valign="top"><table width="100%" height="76" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="6%"><input disabled name="chkemplaye" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtemplaye.value='';}else{document.all.txtemplaye.value = document.all.txtemplayeh.value} calculartotales();" /></td>

              <td class="Tablas">Emplaye

                <input name="txtemplaye" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="10" />

                <input name="txtemplayeh" type="hidden" />

                </td>

              <td class="Tablas"><input disabled name="chkacuserecibo" onClick="if(!this.checked){document.all.txtacuserecibo.value='';}else{document.all.txtacuserecibo.value=document.all.txtacusereciboh.value;} calculartotales();" type="checkbox" style="width:8px; height:9px" value="SI" /></td>

              <td class="Tablas">Acuse Recibo</td>

              <td class="Tablas" align="right"><input readonly="true" name="txtacuserecibo" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />

              <input name="txtacusereciboh" type="hidden" />

              </td>

            </tr>

            <tr>

              <td><input  disabled name="chkbolsaempaque" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtbolsaempaque1.value = ''; document.all.txtbolsaempaque2.value = ''; document.all.txtbolsaempaque1.readOnly=true; document.all.txtbolsaempaque1.style.backgroundColor='#FFFF99';}else{ if(document.all.txtbolsaempaque1h.value=='' || document.all.txtbolsaempaque1h.value=='0'){document.all.txtbolsaempaque1.readOnly=false; document.all.txtbolsaempaque1.style.backgroundColor='#FFFFFF';}else{document.all.txtbolsaempaque1.value = document.all.txtbolsaempaque1h.value; document.all.txtbolsaempaque2.value = document.all.txtbolsaempaque2h.value;}} calculartotales();" /></td>

              <td width="49%" class="Tablas">Bolsa Empaque

                <input name="txtbolsaempaque1" readonly="true" onBlur="if(this.readOnly==false){calculartotales();}" onKeyPress="if(this.readOnly==false && event.keyCode==13){document.all.txtbolsaempaque2.value='$ '+numcredvar((parseFloat((document.all.txtbolsaempaque3h.value=='')?'0':document.all.txtbolsaempaque3h.value.replace('$ ', '').replace(/,/g,''))*parseFloat(this.value)).toLocaleString());calculartotales();}else{return solonumeros(event);}" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="1" />

                  <input name="txtbolsaempaque2" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="6" />

                  <input name="txtbolsaempaque1h" type="hidden" /><input name="txtbolsaempaque2h" type="hidden" /><input name="txtbolsaempaque3h" type="hidden" />

                  </td>

              <td width="5%" class="Tablas"><input disabled name="chkcod" onClick="if(!this.checked){document.all.txtcod.value='';}else{document.all.txtcod.value=document.all.txtcodh.value;} calculartotales();" type="checkbox" style="width:8px; height:8px" value="SI" />

              </td>

              <td width="19%" class="Tablas">COD</td>

              <td width="21%" class="Tablas" align="right"><input readonly="true" name="txtcod" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />

              <input name="txtcodh" type="hidden" />

              </td>

            </tr>

            <tr>

              <td><input disabled name="chkavisocelular" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtavisocelular2.readOnly=true; document.all.txtavisocelular2.style.backgroundColor='#FFFF99'; document.all.txtavisocelular2.value='';document.all.txtavisocelular1.value=''; }else{document.all.txtavisocelular1.value=document.all.txtavisocelular1h.value;document.all.txtavisocelular2.readOnly=false; document.all.txtavisocelular2.style.backgroundColor='#FFFFFF'; document.all.txtavisocelular2.value=document.all.txtavisocelular2h.value;document.all.txtavisocelular2.focus();}  calculartotales();" /></td>

              <td colspan="4" class="Tablas">Aviso Celular

                <input name="txtavisocelular1" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="10" />

                <input name="txtavisocelular1h" type="hidden" />

                  <input name="txtavisocelular2" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="10" /><input name="txtavisocelular2h" type="hidden" /></td>

            </tr>

            <tr>

              <td><input disabled name="chkvalordeclarado" type="checkbox" style="width:8px; height:8px" value="SI"

               onClick="if(!this.checked){document.all.txtdeclarado.value='';document.all.txtdeclarado.readOnly=true; document.all.txtdeclarado.style.backgroundColor='#FFFF99'; document.all.txtdeclarado.readOnly=true;}else{document.all.txtdeclarado.readOnly=false; document.all.txtdeclarado.style.backgroundColor='#FFFFFF'; document.all.txtdeclarado.readOnly=false;document.all.txtdeclarado.focus();} calculartotales();" /></td>

              <td class="Tablas">Valor Declarado

                <input name="txtdeclarado" type="text" readonly="true" onBlur="if(this.readOnly==false){this.value=this.value.replace('$ ','').replace(/,/,'');if(this.value==''){this.value='$ 0.00';}else{ this.value='$ '+numcredvar(this.value); calculartotales(); }}" onKeyPress="if(this.readOnly==false){ if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/,'')); calculartotales();}else{return solonumeros(event);}} " style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td class="Tablas"><input disabled name="chkrecoleccion" type="checkbox" onClick="if(!this.checked){document.all.txtrecoleccion.value='';}else{document.all.txtrecoleccion.value=document.all.txtrecoleccionh.value;} calculartotales();" id="chocurre24" style="width:8px; height:8px" value="SI" /></td>

              <td class="Tablas">Recolecci&oacute;n</td>

              <td class="Tablas" align="right"><input readonly="true" name="txtrecoleccion" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right; text-align:right" value="<?=$rrfc ?>" size="8" />

              <input name="txtrecoleccionh" type="hidden" />

              </td>

            </tr>

</table></td>

      </tr>

    </table>

    		</td>

        	<td width="5"></td>

        </tr>

        </table>

    </td>

    </tr>

  

  <tr>

    <td colspan="2"><table width="654" border="0" align="center" cellpadding="0" cellspacing="0">

      <tr>

        <td width="400"><table width="400" border="0" cellpadding="0" cellspacing="0" bordercolor="#016193">

            <tr>

              <td width="384" class="FondoTabla">Observaciones</td>

            </tr>

            <tr>

              <td><textarea name="txtobservaciones" style="width:400px; font-size:9px; font:tahoma; background:#FFFF99;" readonly ></textarea></td>

            </tr>

        </table></td>

        <td width="244"><table width="170" border="0" align="left" cellpadding="0" cellspacing="0">

            <tr>

              <td><label><img src="../img/impguias.gif" alt="t" width="212" height="24" onClick="abrirVentanaFija('formapago.php?total=' + document.all.t_txttotal.value, 600, 400, 'ventana', 'Busqueda');"></label></td>

            </tr>

        </table></td>

      </tr>

    </table></td>

  </tr>

  

  <tr>

    <td colspan="2"><table width="650" border="0" align="center">

      <tr>

        <td><table width="650" border="0" align="center" cellpadding="0" cellspacing="0">

          <tr>

            <td colspan="9" class="FondoTabla">Datos Entrega </td>

          </tr>

          <tr>

            <td width="6%" class="Tablas">Recibio:</td>

            <td width="16" colspan="3"><input name="recibio" type="text" id="recibio" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$recibio ?>" size="60" />            </td>

            <td width="11%"><span class="Tablas">F. Entrega:</span></td>

            <td width="9%"><input name="entrega" type="text" id="entrega" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$entrega ?>" size="13" /></td>

            <td width="2%"></td>

            <td width="6%"><span class="Tablas">Factura:</span></td>

            <td width="19%"><input name="factura" type="text" id="factura" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$factura ?>" size="13" /></td>

          </tr>

        </table></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td colspan="2">&nbsp;</td>

  </tr>

  <tr>

    <td colspan="2" ></td>

  </tr>

</table>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'GUIAS';

	<?

		if($_GET[funcion]!=""){

			$funcion = str_replace(")","')",str_replace("(","('",$_GET[funcion]));

			echo $funcion.";";

		}

	?>

</script>

</html>

