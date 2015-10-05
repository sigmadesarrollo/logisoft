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

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<!-- funciones para tablas -->

<script type="text/javascript" src="../javascript/funciones_tablas.js"></script>

<!-- funciones para ajax -->

<script type="text/javascript" src="../javascript/ajax.js"></script>

<script>

	//declaracion de tablas

	var tabla_valt1 	= "";

	var valt1 			= agregar_una_tabla("tablaevaluacion", "te_", 5, "Balance2+Balance","");

	var sucursalorigen 	= 0;

	// funciones generales

	function numcredvar(cadena){ 

		var flag = false; 

		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 

		var num = cadena.split(',').join(''); 

		cadena = Number(num).toLocaleString(); 

		if(flag) cadena += '.'; 

		return cadena;

	}



	

	function mostrarEvaluaciones(){

		abrirVentanaFija('../buscadores_generales/buscarEvaluacionGen.php?funcion=pedirDatosEvaluacion&tipo=evaluacion', 650, 450, 'ventana', 'Busqueda')

	}

	//funciones limpiar

	function limpiar_remitente(){

		u = document.all;

		u.idremitente.value 	= "";

		u.rem_rfc.value 		= "";

		u.rem_cliente.value 	= "";

		u.rem_numero.value		= "";

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

			u.destino.value 			= "";

			u.sucdestino.value 			= "";

			u.txtocu.value 				= "";

			u.txtead.value 				= "";

			u.chkemplaye.checked 		= false;

			u.chkbolsaempaque.checked 	= false;

			u.chkavisocelular.checked 	= false;

			u.chkvalordeclarado.checked = false;

			u.chkacuserecibo.checked 	= false;

			u.chkcod.checked 			= false;

			u.chkrecoleccion.checked 	= false;

			

			u.txtavisocelular1.value 	= "";

			u.txtacuserecibo.value 		= "";

			u.txtcod.value 				= "";

			u.txtemplaye.value 			= "";

			u.txtbolsaempaque1.value 	= "";

			u.txtbolsaempaque2.value 	= "";

			

			u.txtavisocelular1h.value 	= "";

			u.txtacusereciboh.value 	= "";

			u.txtcodh.value 			= "";

			u.txtocurre.value 			= "";

			u.txtemplayeh.value 		= "";

			u.txtbolsaempaque1h.value 	= "";

			u.txtbolsaempaque2h.value 	= "";

	}

	function calcularservicios(){

		u = document.all;

		var templaye 		= parseFloat((u.txtemplaye.value=="")?0:u.txtemplaye.value);

		var tbolsaempaque	= parseFloat((u.txtbolsaempaque2.value=="")?0:u.txtbolsaempaque2.value);

		var tavisocelular	= parseFloat((u.txtavisocelular1.value=="")?0:u.txtavisocelular1.value);

		var tdeclarado		= parseFloat((u.txtdeclarado.value=="")?0:u.txtdeclarado.value);

		var tacuserecibo	= parseFloat((u.txtacuserecibo.value=="")?0:u.txtacuserecibo.value);

		var tcod			= parseFloat((u.txtcod.value=="")?0:u.txtcod.value);

		

		u.t_txtotros.value		= templaye+tbolsaempaque+tavisocelular+tdeclarado+tacuserecibo+tcod;

	}

	function calculartotales(){

		var u = document.all;

		calcularservicios();

		

		var ptflete 			= parseFloat((u.flete.value=="")?0:u.flete.value);

		var ptdescuento 		= parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value);

		var ptead 				= parseFloat((u.t_txtead.value=="")?0:u.t_txtead.value);

		var ptrecoleccion 		= parseFloat((u.t_txtrecoleccion.value=="")?0:u.t_txtrecoleccion.value);

		var ptseguro	 		= parseFloat((u.t_txtseguro.value=="")?0:u.t_txtseguro.value);

		var ptotros		 		= parseFloat((u.t_txtotros.value=="")?0:u.t_txtotros.value);

		var ptexcedente			= parseFloat((u.t_txtexcedente.value=="")?0:u.t_txtexcedente.value);

		var ptcombustible		= parseFloat((u.t_txtcombustible.value=="")?0:u.t_txtcombustible.value);

		

		var ptsubtotal			= ptflete+ptdescuento+ptead+ptrecoleccion+ptseguro+ptotros+ptexcedente+ptcombustible;

		u.t_txtsubtotal.value	= Math.round(ptsubtotal*100)/100;

		u.t_txtiva.value		= Math.round((ptsubtotal*(parseFloat(u.pc_iva.value)/100))*100)/100;

		u.t_txtivaretenido.value= Math.round( (ptsubtotal*(parseFloat(u.pc_ivaretenido.value)/100))*100) /100;

		u.t_txttotal.value		= Math.round( (ptsubtotal+parseFloat(u.t_txtivaretenido.value)+parseFloat(u.t_txtiva.value) ) *100)/100;

	}

	

	//funciones para ajax	

	function pedirDatosEvaluacion(idevaluacion){

		sucursalorigen 	= '<?=$_SESSION[IDSUCURSAL]?>';

		destinoorigen	= parent.frames[0].document.all.iddestino.value;

		//alert("guia_consulta.php?accion=1&folio="+idevaluacion+"&idsucorigen="+sucursalorigen);

		consulta("devolverDatosEvaluacion", "guia_consulta.php?accion=1&folio="+idevaluacion+"&idsucorigen="+sucursalorigen+"&iddestinoorigen="+destinoorigen+"&valrandom="+Math.random());

	}

	function devolverDatosEvaluacion(datos){

		limpiar_evaluacion();

		

		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		if(encon>0){

			estado 				= datos.getElementsByTagName('estado').item(0).firstChild.data;

			destino 			= datos.getElementsByTagName('ndestino').item(0).firstChild.data;

			iddestino 			= datos.getElementsByTagName('iddestino').item(0).firstChild.data;

			sucursal			= datos.getElementsByTagName('nsucursal').item(0).firstChild.data;

			idsucursal 			= datos.getElementsByTagName('idsucursal').item(0).firstChild.data;

			cantidadbolsa		= datos.getElementsByTagName('cantidadbolsa').item(0).firstChild.data;

			bolsaempaque		= datos.getElementsByTagName('bolsaempaque').item(0).firstChild.data;

			emplaye				= datos.getElementsByTagName('emplaye').item(0).firstChild.data;

			totalbolsaempaque	= datos.getElementsByTagName('totalbolsaempaque').item(0).firstChild.data;

			totalemplaye		= datos.getElementsByTagName('totalemplaye').item(0).firstChild.data;

			ocu					= datos.getElementsByTagName('ocu').item(0).firstChild.data;

			ead					= datos.getElementsByTagName('ead').item(0).firstChild.data;

			

			avisocelular		= datos.getElementsByTagName('avisocelular').item(0).firstChild.data;

			acuserecibo			= datos.getElementsByTagName('acuserecibo').item(0).firstChild.data;

			cod					= datos.getElementsByTagName('cod').item(0).firstChild.data;

			ocurre				= datos.getElementsByTagName('ocurre').item(0).firstChild.data;

			restrinccion		= datos.getElementsByTagName('restrincciones').item(0).firstChild.data;

			

			//para totales

			pt_ead				= datos.getElementsByTagName('pt_ead').item(0).firstChild.data;

			pt_recoleccion		= datos.getElementsByTagName('pt_recoleccion').item(0).firstChild.data;

			pt_iva				= datos.getElementsByTagName('pt_iva').item(0).firstChild.data;

			pt_ivaretenido		= datos.getElementsByTagName('pt_ivaretenido').item(0).firstChild.data;

			por_combustible		= datos.getElementsByTagName('por_combustible').item(0).firstChild.data;

			max_descuento		= datos.getElementsByTagName('max_des').item(0).firstChild.data;

			vporcada			= datos.getElementsByTagName('por_cada').item(0).firstChild.data;

			vscosto				= datos.getElementsByTagName('scosto').item(0).firstChild.data;

			erecoleccion		= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;

			

			u.estado.value = estado;

			u.destino.value = destino;

			u.sucdestino.value = sucursal;

			u.txtemplayeh.value = totalemplaye;

			u.txtbolsaempaque1h.value = cantidadbolsa;

			u.txtbolsaempaque2h.value = totalbolsaempaque;

			u.txtocu.value = ocu;

			u.txtead.value = ead;

			

			u.pc_ead.value					= pt_ead;

			u.pc_recoleccion.value			= pt_recoleccion;

			u.pc_tarifacombustible.value	= por_combustible;

			u.pc_maximodescuento.value		= max_descuento;

			u.pc_porcada.value				= vporcada;

			u.pc_costo.value				= vscosto;

			u.pc_iva.value					= pt_iva;

			u.pc_ivaretenido.value			= pt_ivaretenido;

			u.t_txtexcedente.value			= 0;

			

			u.txtrestrinccion.value = (restrinccion==0)?"":restrinccion;

			if(emplaye==1){

				u.chkemplaye.checked = true;

				u.chkemplaye.disabled = true;

				u.txtemplaye.value = totalemplaye;

			}else{

				u.chkemplaye.checked = false;

			}

			if(bolsaempaque==1){

				u.chkbolsaempaque.checked = true;

				u.chkbolsaempaque.disabled = true;

				u.txtbolsaempaque1.value = cantidadbolsa;

				u.txtbolsaempaque2.value = totalbolsaempaque;

			}else{

				u.chkbolsaempaque.checked = false;

			}

			if(erecoleccion!=0){

				u.chkrecoleccion.checked = true;

				u.chkrecoleccion.disabled = true;

				u.txtrecoleccionh.value		= erecoleccion;	

				u.txtrecoleccion.value		= erecoleccion;	

			}else{

				u.chkrecoleccion.checked = false;

				u.chkrecoleccion.disabled = false;

				u.txtrecoleccionh.value		= "";	

			}

			

			u.txtavisocelular1h.value 	= avisocelular;

			u.txtacusereciboh.value 	= acuserecibo;

			u.txtcodh.value 			= cod;

			u.txtocurre.value 			= ocurre;

			

			//total de evaluacion

			var enconeva = datos.getElementsByTagName('encontroevaluacion').item(0).firstChild.data;

			if(enconeva>0){

				tpaquetes	= 0;

				tpeso		= 0;

				tvolumen	= 0;

				timporte	= 0;

				for(m=0;m<enconeva;m++){	

					cantidad	= datos.getElementsByTagName('cantidad').item(m).firstChild.data;

					descripcion	= datos.getElementsByTagName('descripcion').item(m).firstChild.data;

					contenido	= datos.getElementsByTagName('contenido').item(m).firstChild.data;

					peso		= datos.getElementsByTagName('peso').item(m).firstChild.data;

					volumen		= datos.getElementsByTagName('volumen').item(m).firstChild.data;

					importe		= datos.getElementsByTagName('importe').item(m).firstChild.data;

					tpaquetes	+= parseFloat(cantidad);

					tpeso		+= parseFloat(peso);

					tvolumen	+= parseFloat(volumen);

					timporte	+= parseFloat(importe);

					insertar_en_tabla(valt1,cantidad+"└"+descripcion+"└"+contenido+"└"+peso+"└"+volumen+"└$ "+numcredvar(importe));

				}

				u.totalpaquetes.value 	= tpaquetes;

				u.totalpeso.value 		= tpeso;

				u.totalvolumen.value 	= tvolumen;

				u.flete.value			= timporte;

			}

			//calculo de totales

			if((parseFloat(u.flete.value)*0.10)<parseFloat(u.pc_ead.value)){

				u.t_txtead.value = u.pc_ead.value;

			}else{

				u.t_txtead.value = Math.round(((parseFloat(u.flete.value)-parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value))*.10)*100)/100;

			}

			

			if((parseFloat(u.flete.value)*0.10)<parseFloat(u.pc_recoleccion.value)){

				u.t_txtrecoleccion.value = u.pc_recoleccion.value;

			}else{

				u.t_txtrecoleccion.value = Math.round(((parseFloat(u.flete.value)-parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value))*.10)*100)/100;

			}

			

			if(u.txtdeclarado.value!="" && u.txtdeclarado.value!="0"){

				if(parseFloat(u.txtdeclarado.value)<parseFloat(u.pc_porcada.value)){

					u.t_txtseguro.value = u.pc_costo.value;

				}else{

					u.t_txtseguro.value = Math.round(((parseFloat(u.txtdeclarado.value)/parseFloat(u.pc_porcada.value))*parseFloat(u.pc_costo.value))*100)/100;

				}

			}else{

				u.t_txtseguro.value = u.pc_costo.value;

			}

			u.t_txtcombustible.value = Math.round(((parseFloat(u.flete.value)-parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value))*(parseFloat(u.pc_tarifacombustible.value)/100))*100)/100;

			

			calculartotales();

		}

	}

	function devolverRemitente(valor){

		limpiar_remitente();

		document.all.idremitente.value = valor;

		consulta("mostrarRemitente", "guia_consulta.php?accion=2&idcliente="+valor+"&valrandom="+Math.random());

	}

	function mostrarRemitente(datos){

		var u = document.all;

		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		if(encon>0){

			var endir = datos.getElementsByTagName('encontrodirecciones').item(0).firstChild.data;

			u.rem_rfc.value 			= datos.getElementsByTagName('rfc').item(0).firstChild.data;

			u.rem_cliente.value 		= datos.getElementsByTagName('ncliente').item(0).firstChild.data;

			v_celular 					= datos.getElementsByTagName('celular').item(0).firstChild.data;

			u.txtavisocelular2h.value 	= (v_celular=="0")?"":v_celular;

			if(endir==1){

				document.all.celda_rem_calle.innerHTML ='<input name="rem_calle" readonly="true" type="text" '

				+'style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" /><input type="hidden" name="rem_direcciones">';

				u.rem_direcciones.value	= datos.getElementsByTagName('idcalle').item(i).firstChild.data;

				u.rem_calle.value 		= datos.getElementsByTagName('calle').item(0).firstChild.data;

				u.rem_numero.value 		= datos.getElementsByTagName('numero').item(0).firstChild.data;

				u.rem_cp.value 			= datos.getElementsByTagName('cp').item(0).firstChild.data;

				u.rem_colonia.value 	= datos.getElementsByTagName('colonia').item(0).firstChild.data;

				u.rem_poblacion.value 	= datos.getElementsByTagName('poblacion').item(0).firstChild.data;

				u.rem_telefono.value 	= datos.getElementsByTagName('telefono').item(0).firstChild.data;

				

			}else if(endir>1){

				var comb = "<select name='rem_direcciones' style='width:165px;font:tahoma; font-size:9px' onchange='"

				+"document.all.rem_numero.value=this.options[this.selectedIndex].numero;"

				+"document.all.rem_cp.value=this.options[this.selectedIndex].cp;"

				+"document.all.rem_colonia.value=this.options[this.selectedIndex].colonia;"

				+"document.all.rem_poblacion.value=this.options[this.selectedIndex].poblacion;"

				+"document.all.rem_telefono.value=this.options[this.selectedIndex].telefono;"

				+"'>";

				

				for(var i=0; i<endir; i++){

					v_idcalle		= datos.getElementsByTagName('idcalle').item(i).firstChild.data;

					v_calle 		= datos.getElementsByTagName('calle').item(i).firstChild.data;

					v_numero 		= datos.getElementsByTagName('numero').item(i).firstChild.data;

					v_cp 			= datos.getElementsByTagName('cp').item(i).firstChild.data;

					v_colonia	 	= datos.getElementsByTagName('colonia').item(i).firstChild.data;

					v_poblacion 	= datos.getElementsByTagName('poblacion').item(i).firstChild.data;

					v_telefono 		= datos.getElementsByTagName('telefono').item(i).firstChild.data;

					if(i==0){

						u.rem_numero.value 		= v_numero;

						u.rem_cp.value 			= v_cp;

						u.rem_colonia.value 	= v_colonia;

						u.rem_poblacion.value 	= v_poblacion;

						u.rem_telefono.value 	= v_telefono;	

					}

					

					comb += "<option value='"+v_idcalle+"' numero='"+v_numero+"' cp='"+v_cp+"' colonia='"+v_colonia+"'"

					+"poblacion='"+v_poblacion+"' telefono='"+v_telefono+"'>"

					+v_calle+", "+v_numero+", "+v_colonia+"</option>";

				}

				comb += "</select>";

				document.all.celda_rem_calle.innerHTML = comb;

			}else{

				alerta("El Cliente no tiene direccion","","idremitente");

			}

		}else{

			alerta("El Cliente no existe","","idremitente");

		}

	}

	function devolverDestinatario(valor){

		limpiar_destinatario();

		document.all.iddestinatario.value = valor;

		consulta("mostrarDestinatario", "guia_consulta.php?accion=2&idcliente="+valor+"&valrandom="+Math.random());

	}

	function mostrarDestinatario(datos){

		var u = document.all;

		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		if(encon>0){

			var endir = datos.getElementsByTagName('encontrodirecciones').item(0).firstChild.data;

			u.des_rfc.value 	= datos.getElementsByTagName('rfc').item(0).firstChild.data;

			u.des_cliente.value = datos.getElementsByTagName('ncliente').item(0).firstChild.data;

			if(endir==1){

				document.all.celda_des_calle.innerHTML ='<input name="des_calle" readonly="true" type="text" '

				+'style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" /><input type="hidden" name="des_direcciones">';

				u.des_direcciones.value	= datos.getElementsByTagName('idcalle').item(i).firstChild.data;

				u.des_calle.value 		= datos.getElementsByTagName('calle').item(0).firstChild.data;

				u.des_numero.value 		= datos.getElementsByTagName('numero').item(0).firstChild.data;

				u.des_cp.value 			= datos.getElementsByTagName('cp').item(0).firstChild.data;

				u.des_colonia.value 	= datos.getElementsByTagName('colonia').item(0).firstChild.data;

				u.des_poblacion.value 	= datos.getElementsByTagName('poblacion').item(0).firstChild.data;

				u.des_telefono.value 	= datos.getElementsByTagName('telefono').item(0).firstChild.data;

			}else if(endir>1){

				var comb = "<select name='rem_direcciones' style='width:165px;font:tahoma; font-size:9px' onchange='"

				+"document.all.des_numero.value=this.options[this.selectedIndex].numero;"

				+"document.all.des_cp.value=this.options[this.selectedIndex].cp;"

				+"document.all.des_colonia.value=this.options[this.selectedIndex].colonia;"

				+"document.all.des_poblacion.value=this.options[this.selectedIndex].poblacion;"

				+"document.all.des_telefono.value=this.options[this.selectedIndex].telefono;"

				+"'>";

				

				for(var i=0; i<endir; i++){

					v_idcalle		= datos.getElementsByTagName('idcalle').item(i).firstChild.data;

					v_calle 		= datos.getElementsByTagName('calle').item(i).firstChild.data;

					v_numero 		= datos.getElementsByTagName('numero').item(i).firstChild.data;

					v_cp 			= datos.getElementsByTagName('cp').item(i).firstChild.data;

					v_colonia	 	= datos.getElementsByTagName('colonia').item(i).firstChild.data;

					v_poblacion 	= datos.getElementsByTagName('poblacion').item(i).firstChild.data;

					v_telefono 		= datos.getElementsByTagName('telefono').item(i).firstChild.data;

					if(i==0){

						u.des_numero.value 		= v_numero;

						u.des_cp.value 			= v_cp;

						u.des_colonia.value 	= v_colonia;

						u.des_poblacion.value 	= v_poblacion;

						u.des_telefono.value 	= v_telefono;	

					}

					

					comb += "<option value='"+v_idcalle+"' numero='"+v_numero+"' cp='"+v_cp+"' colonia='"+v_colonia+"'"

					+"poblacion='"+v_poblacion+"' telefono='"+v_telefono+"'>"

					+v_calle+", "+v_numero+", "+v_colonia+"</option>";

				}

				comb += "</select>";

				document.all.celda_des_calle.innerHTML = comb;

			}else{

				alerta("El Cliente no tiene direccion","","iddestinatario");

			}

		}else{

			alerta("El Cliente no existe","","iddestinatario");

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

        <td colspan="2"><input name="estado" readonly="true" type="text" id="estado" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$estado ?>" size="13" align="top" /></td>

        <td width="12%">&nbsp;</td>

        <td width="6%"></td>

        <td width="9%">&nbsp;</td>

        <td width="23%">&nbsp;</td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td colspan="2" ><table width="654" border="0" align="center" cellpadding="0" cellspacing="0">

      <tr>

        <td width="7%" class="Tablas">T. Flete:</td>

        <td width="14%"><select name="lstflete" id="lstflete" style="width:77px; font-size:9px">

            <option>Pagado</option>

            <option>Por Cobrar</option>

        </select></td>

        <td width="3%"><input name="chocurre" type="checkbox" id="chocurre" style="width:8px; height:8px" value="SI" /></td>

        <td width="6%"><span class="Tablas">Ocurre

          <input type="hidden" name="txtocurre">

        </span></td>

        <td width="7%" class="Tablas">Destino:</td>

        <td width="16%">

        <input type="text" name="destino" id="destino" style="width:100px; font-size:9px">        </td>

        <td width="10%"><span class="Tablas">Suc. Destino:</span></td>

        <td width="13%"><input name="sucdestino" type="text" id="sucdestino" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$destino ?>" size="20" /></td>

        <td width="9%"><span class="Tablas">Cond. Pago:</span></td>

        <td width="13%">&nbsp;

            <select name="sltpago" id="sltpago" style="width:70px; font-size:9px">

              <option>Contado</option>

              <option>Credito</option>

          </select></td>

        <td width="2%">&nbsp;</td>

      </tr>

    </table></td>

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

                    <td><input name="idremitente" type="text" onKeyPress="if(event.keyCode==13){devolverRemitente(this.value)}" style="font:tahoma; font-size:9px" value="<?=$remitente ?>" size="4" />

                      &nbsp;&nbsp;<img src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverRemitente', 625, 418, 'ventana', 'Busqueda')" /></td>

                    <td width="55%" colspan="3">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>

                        <input name="rem_rfc" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="24" /></td>

                  </tr>

                  <tr>

                    <td><span class="Tablas">Cliente:</span></td>

                    <td colspan="4"><input name="rem_cliente" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcliente ?>" size="54" /></td>

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

                  <td><input name="iddestinatario" onKeyPress="if(event.keyCode==13){devolverDestinatario(this.value)}" type="text" style="font:tahoma; font-size:9px" value="<?=$remitente ?>" size="4" />

                    &nbsp;&nbsp;<img src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverDestinatario', 625, 418, 'ventana', 'Busqueda')"/></td>

                  <td width="55%" colspan="3">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>

                      <input name="des_rfc" type="text" readonly="true" id="rrfc22" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="24" /></td>

                </tr>

                <tr>

                  <td colspan="4"><input name="des_cliente" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcliente ?>" size="54" /></td>

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

                  <table width=415 border=0 cellspacing=0 cellpadding=0 id="tablaevaluacion" alagregar="" alborrar="">

                  	<tr>

                      <td width="44" class="style5" ></td>

                      <td width="108" class="style5"></td>

                      <td width="98" class="style5"></td>

                      <td width="40" class="style5"></td>

                      <td width="44" class="style5"></td>

                      <td width="58" class="style5"></td>

                      <td width="7" class="style5"></td>

                    </tr>

                    <?

					$line = 0;

					while($line<=5){ 

					?>

                    <tr id="te_<?=$line?>" class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>">

                      <td height=16 class="style3" align="center" ></td>

					  <td class="style3" align="left">&nbsp;</td>

                      <td class="style3" align="left">&nbsp;</td>

                      <td class="style3" align="right">&nbsp;</td>

                      <td class="style3" align="right">&nbsp;</td>

                      <td colspan="2" align="right" class="style3" >&nbsp;</td>

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

                      <textarea name="txtrestrinccion" style="width:180px; font-size:9px"></textarea>

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

              <td class="Tablas">Flete:</td>

              <td colspan="2" class="Tablas"><input name="flete" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td class="Tablas">Excedente:</td>

              <td class="Tablas"><input name="t_txtexcedente" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td class="Tablas">Descuento:</td>

              <td colspan="2" class="Tablas"><input readonly="true" name="t_txtdescuento1" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="3" />

                  <input name="t_txtdescuento2" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="4" /></td>

              <td class="Tablas">Combustible:</td>

              <td class="Tablas"><input name="t_txtcombustible" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td class="Tablas">EAD:</td>

              <td colspan="2" class="Tablas"><input readonly="true" name="t_txtead" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td class="Tablas">Subtotal:</td>

              <td class="Tablas"><input name="t_txtsubtotal" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td class="Tablas">Recolecci&oacute;n:</td>

              <td colspan="2" class="Tablas"><input readonly="true" name="t_txtrecoleccion" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td class="Tablas">IVA:</td>

              <td class="Tablas"><input name="t_txtiva" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td width="24%" class="Tablas">Seguro:</td>

              <td colspan="2" class="Tablas"><input readonly="true" name="t_txtseguro" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td width="23%" class="Tablas">IVA Retenido: </td>

              <td width="17%" class="Tablas"><input name="t_txtivaretenido" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

            <tr>

              <td class="Tablas">Otros:</td>

              <td colspan="2" class="Tablas"><input name="t_txtotros" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td class="Tablas">Total:</td>

              <td class="Tablas"><input name="t_txttotal" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

            </tr>

        </table></td>

      </tr>

    </table></td>

  </tr>

  

  

  <tr>

    <td width="51%" ><table width="325" height="140" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">

      <tr>

        <td width="434" class="FondoTabla">Servicios</td>

      </tr>

      <tr>

        <td valign="top"><table width="100%" height="76" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="6%"><input name="chkemplaye" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtemplaye.value='';}else{document.all.txtemplaye.value = document.all.txtemplayeh.value} calculartotales();" /></td>

              <td class="Tablas">Emplaye

                <input name="txtemplaye" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="10" />

                <input name="txtemplayeh" type="hidden" />

                </td>

              <td class="Tablas"><input name="chkacuserecibo" onClick="if(!this.checked){document.all.txtacuserecibo.value='';}else{document.all.txtacuserecibo.value=document.all.txtacusereciboh.value;} calculartotales();" type="checkbox" style="width:8px; height:9px" value="SI" /></td>

              <td class="Tablas">Acuse Recibo</td>

              <td class="Tablas"><input readonly="true" name="txtacuserecibo" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />

              <input name="txtacusereciboh" type="hidden" />

              </td>

            </tr>

            <tr>

              <td><input name="chkbolsaempaque" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtbolsaempaque1.value = ''; document.all.txtbolsaempaque2.value = ''; }else{ document.all.txtbolsaempaque1.value = document.all.txtbolsaempaque1h.value; document.all.txtbolsaempaque2.value = document.all.txtbolsaempaque2h.value;} calculartotales();" /></td>

              <td width="49%" class="Tablas">Bolsa Empaque

                <input name="txtbolsaempaque1" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="1" />

                  <input name="txtbolsaempaque2" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="6" />

                  <input name="txtbolsaempaque1h" type="hidden" /><input name="txtbolsaempaque2h" type="hidden" />

                  </td>

              <td width="5%" class="Tablas"><input name="chkcod" onClick="if(!this.checked){document.all.txtcod.value='';}else{document.all.txtcod.value=document.all.txtcodh.value;} calculartotales();" type="checkbox" style="width:8px; height:8px" value="SI" />

              </td>

              <td width="19%" class="Tablas">COD</td>

              <td width="21%" class="Tablas"><input readonly="true" name="txtcod" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />

              <input name="txtcodh" type="hidden" />

              </td>

            </tr>

            <tr>

              <td><input name="chkavisocelular" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtavisocelular2.readOnly=true; document.all.txtavisocelular2.style.backgroundColor='#FFFF99'; document.all.txtavisocelular2.value='';document.all.txtavisocelular1.value=''; }else{document.all.txtavisocelular1.value=document.all.txtavisocelular1h.value;document.all.txtavisocelular2.readOnly=false; document.all.txtavisocelular2.style.backgroundColor='#FFFFFF'; document.all.txtavisocelular2.value=document.all.txtavisocelular2h.value;document.all.txtavisocelular2.focus();}  calculartotales();" /></td>

              <td colspan="4" class="Tablas">Aviso Celular

                <input name="txtavisocelular1" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="10" />

                <input name="txtavisocelular1h" type="hidden" />

                  <input name="txtavisocelular2" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="10" /><input name="txtavisocelular2h" type="hidden" /></td>

            </tr>

            <tr>

              <td><input name="chkvalordeclarado" type="checkbox" style="width:8px; height:8px" value="SI"

               onClick="if(!this.checked){document.all.txtdeclarado.value='';document.all.txtdeclarado.readOnly=true; document.all.txtdeclarado.style.backgroundColor='#FFFF99'; document.all.txtdeclarado.readOnly=true;}else{document.all.txtdeclarado.readOnly=false; document.all.txtdeclarado.style.backgroundColor='#FFFFFF'; document.all.txtdeclarado.readOnly=false;}" /></td>

              <td class="Tablas">Valor Declarado

                <input name="txtdeclarado" type="text" readonly="true" onBlur=" calculartotales();" onKeyPress="if(event.keyCode==13){ calculartotales();}" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>

              <td class="Tablas"><input name="chkrecoleccion" type="checkbox" onClick="if(!this.checked){document.all.txtrecoleccion.value='';}else{document.all.txtrecoleccion.value=document.all.txtrecoleccionh.value;} calculartotales();" id="chocurre24" style="width:8px; height:8px" value="SI" /></td>

              <td class="Tablas">Recolecci&oacute;n</td>

              <td class="Tablas"><input readonly="true" name="txtrecoleccion" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right; text-align:right" value="<?=$rrfc ?>" size="8" />

              <input name="txtrecoleccionh" type="hidden" />

              </td>

            </tr>

</table></td>

      </tr>

    </table></td>

    </tr>

  

  <tr>

    <td colspan="2"><table width="654" border="0" align="center" cellpadding="0" cellspacing="0">

      <tr>

        <td width="400"><table width="400" border="0" cellpadding="0" cellspacing="0" bordercolor="#016193">

            <tr>

              <td width="384" class="FondoTabla">Observaciones</td>

            </tr>

            <tr>

              <td><textarea name="textarea2" style="width:400px; font-size:9px; font:tahoma"></textarea></td>

            </tr>

        </table></td>

        <td width="244"><table width="170" border="0" align="left" cellpadding="0" cellspacing="0">

            <tr>

              <td><label><img src="../img/impguias.gif" alt="t" width="212" height="24"></label></td>

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

</script>

</html>

