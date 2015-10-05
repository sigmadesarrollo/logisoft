<?
	session_start();
	require("../Conectar.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ajax.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ajax.js"></script>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<script>
	var btn_Cancelar = '<div id="btnCancelar" class="ebtn_cancelar" onclick="confirmar(\'¿Esta seguro de cancelar la solicitud de folios?\', \'\', \'Cancelar();\', \'\')"></div>';
	var btn_Guardar = '<div id="Guardar" class="ebtn_guardar" onclick="Validar()"></div>';
	var u = document.all;
	window.onload= function(){
	obtenerDetalles();	
	
	<?
		$_GET[funcion2] = str_replace("\'","'",$_GET[funcion2]);
		if($_GET[funcion2]!=""){
			echo 'setTimeout("'.$_GET[funcion2].'",1500);';
		}
		?>
	}

	function obtenerDetalles(){
		consultaTexto("mostrarDetalles","solicitudguiasempresariales_con.php?accion=8&valram="+Math.random());
	}
	
	function mostrarDetalles(datos){
			var objeto = eval("("+convertirValoresJson(datos)+")");
				u.fecha.value 		= objeto.fecha;
				u.folio.value 		= objeto.folio;
	}

	function pedirCliente(id){
		consultaTexto("mostrarCliente","solicitudguiasempresariales_con.php?accion=9&idcliente="+id+"&valram="+Math.random());
	}

	function mostrarCliente(datos){
		var objeto = eval("("+convertirValoresJson(datos)+")");
		u.preocon[0].disabled = true;
		u.preocon[1].disabled = true;
		u.preocon[0].checked = false;
		u.preocon[1].checked = false;
		if(objeto.id!=undefined){
			u.ncliente.value 	=objeto.id;
			u.nombre.value		=objeto.nombre;
			u.paterno.value		=objeto.paterno;
			u.materno.value		=objeto.materno;
			u.vigencia.value	=objeto.estadoconvenio;
			if(objeto.prepagadas==0 && objeto.consignacion==0){
				alerta("El cliente no cuenta con convenio para guias empresariales","¡Atención!","ncliente");
				u.preocon[0].checked=false;				
				u.preocon[1].checked=false;				
				u.cantidad.disabled=false;
				u.cantidad.value="";
				u.vigencia.value = "";
				return false;
			}			
			if(objeto.prepagadas==1 && objeto.consignacion==1){
				u.preocon[0].checked=true;
				u.preocon[0].disabled=false;
				u.preocon[1].disabled=false;
			}else if(objeto.prepagadas==1){
				u.preocon[0].checked=true;
				u.preocon[0].disabled=false;
				u.preocon[1].disabled=true;
			}else if(objeto.consignacion==1){
				u.preocon[1].checked=true;
				u.preocon[1].disabled=false;
				u.preocon[0].disabled=true;
			}else{
				u.preocon[1].checked=false;
				u.preocon[1].disabled=true;
				u.preocon[0].checked=false;
				u.preocon[0].disabled=true;
			}
			
			if(objeto.consignacion!=1 && objeto.prepagadas!=1){
				u.cantidad.disabled=true;
			}else{
				u.cantidad.disabled=false;
				u.cantidad.value="";
			}
		}else{
			alerta("El cliente no cuenta con convenio","¡Atención!","ncliente");
			u.ncliente.value 	="";
			u.nombre.value		="";
			u.paterno.value		="";
			u.materno.value		="";
			u.preocon[0].checked=false;			
			u.preocon[1].checked=false;			
			u.cantidad.disabled=false;
			u.cantidad.value="";
			u.vigencia.value = "";
		}
	}
	
	function valida(){
		if(u.fecha.value > u.vigencia.value){
			alerta3("El cliente "+u.nombre.value+" tiene vencido su convenio","¡Atención!");
			return false;
		}
	}	
	function Validar(){
		<?=$cpermiso->verificarPermiso("347,286",$_SESSION[IDUSUARIO]);?>
		if(u.ncliente.value==""){
			alerta("Debe capturar cliente","¡Atencion!","ncliente");
			return false;
		}else if(u.nombre.value==""){
			alerta("Debe capturar cliente","¡Atencion!","ncliente");
			return false;
		}else if(u.vigencia.value == "EXPIRADO"){
			alerta3("El cliente "+u.nombre.value+" tiene vencido su convenio","¡Atención!");
			return false;			
		}else if(u.preocon[0].checked== false && u.preocon[1].checked==false ){
			alerta("El cliente no tiene convenio","¡Atencion!","ncliente");
			return false;
		}else if(u.cantidad.value==""){
			alerta("Debe capturar cantidad","¡Atencion!","cantidad");
			return false;
		}
		if(u.preocon[0].checked== true){
				var preocon='PREPAGADA';
		}else if(u.preocon[1].checked==true){
				var preocon='CONSIGNACION';
		}

		consultaTexto("mostrarResult","solicitudguiasempresariales_con.php?accion=10&cliente="+u.ncliente.value
			+"&nombre="+u.nombre.value
			+"&paterno="+u.paterno.value
			+"&materno="+u.materno.value
			+"&preocon="+preocon
			+"&cantidad="+u.cantidad.value
			+"&valram="+Math.random());	
	}

	function mostrarResult(datos){
		if(datos.indexOf("1")>-1){
			info("La solicitud de guias empresariales ha sido guardada", "¡Atencion!");
			u.Guardar.style.visibility = "hidden";
			u.btnCancelar.style.visibility = "visible";
		}
	}

	function Limpiar(){
		u.ncliente.value="";
		u.nombre.value  ="";
		u.paterno.value	="";
		u.materno.value	="";
		u.cantidad.value="";
		u.preocon[0].checked=false;
		u.preocon[0].disabled=true;
		u.preocon[1].checked=false;
		u.preocon[1].disabled=true;
		u.cantidad.disabled=false;
		u.btnCancelar.style.visibility = "hidden";		
		u.btncliente.style.visibility="visible";
		u.ncliente.disabled=false;
		u.btnAutorizar.style.visibility="hidden";
		u.Guardar.style.visibility="visible";
		u.vigencia.value = "";
		obtenerDetalles();
		
	}


	function obtenerSolicitud(id){
		u.btnAutorizar.style.visibility="hidden";
		consultaTexto("mostrarSolicitud","solicitudguiasempresariales_con.php?accion=11&folio="+id+"&valram="+Math.random());
	}
	function mostrarSolicitud(datos){
//		return false;
		var objeto = eval("("+convertirValoresJson(datos)+")");
			u.fecha.value 		=objeto.fecha;
			u.folio.value 		=objeto.folio;
			u.ncliente.value 	=objeto.id;
			u.nombre.value		=objeto.nombre;
			u.paterno.value		=objeto.paterno;
			u.materno.value		=objeto.materno;
			u.cantidad.value	=objeto.cantidad;
			if(objeto.preocon=='PREPAGADA'){
				u.preocon[0].checked=true;
				u.preocon[0].disabled=false;
				u.preocon[1].disabled=true;
			}else if(objeto.preocon=='CONSIGNACION'){
				u.preocon[1].checked=true;
				u.preocon[1].disabled=false;
				u.preocon[0].disabled=true;
			}else{
				u.preocon[1].checked=false;
				u.preocon[1].disabled=true;
				u.preocon[0].checked=false;
				u.preocon[0].disabled=true;
			}
			u.cantidad.disabled=true;
			u.Guardar.style.visibility="hidden";
			u.btnCancelar.style.visibility="visible";
			u.btncliente.style.visibility="hidden";
			u.ncliente.disabled=true;
			
	}

	function Cancelar(){
		
		consultaTexto("mostrarCancelar","solicitudguiasempresariales_con.php?accion=12&folio="+u.folio.value+"&valram="+Math.random());
	}

	function mostrarCancelar(datos){
		if(datos.indexOf('1')>0){
			info("La solicitud ha sido cancelada", "¡Atencion!");
			u.Guardar.style.visibility="hidden";
			u.btnAutorizar.style.visibility="hidden";
		}
	}


function mostrarSoliGuiasEmp(tipo){
	switch(tipo){
		case 1:
			abrirVentanaFija('../buscadores_generales/BuscarSolicitudGuasEmpresariales_Gen_New.php?funcion=obtenerSolicitudAut&con=1', 625, 430, 'ventana', 'Buscar Solicitud');
			break;	
		case 2:
			abrirVentanaFija('../buscadores_generales/BuscarSolicitudGuasEmpresariales_Gen_New.php?funcion=obtenerSolicitudAut&con=2', 625, 430, 'ventana', 'Buscar Solicitud');
			break; 
	
	}
	
}

	function Autorizar(){
		consultaTexto("mostrarAutorizar","solicitudguiasempresariales_con.php?accion=13&folio="+u.folio.value+"&valram="+Math.random());
	}

	function mostrarAutorizar(datos){
		if(datos.indexOf('1')>0){
			info("La solicitud ha sido Autorizada", "¡Atencion!");
			u.Guardar.style.visibility="hidden";
			u.btnAutorizar.style.visibility="hidden";
		}
	}


function obtenerSolicitudAut(id){	
	consultaTexto("mostrarSolicitud","solicitudguiasempresariales_con.php?accion=11&folio="+id+"&valram="+Math.random());
	u.btnAutorizar.style.visibility="visible";
}

	function AbrirAsignacionGuiasEmp(){	
		consultaTexto("autorizar","solicitudguiasempresariales_con.php?accion=14&folio="+u.folio.value);	
	}
	function autorizar(datos){
		if(u.preocon[0].checked== true){
			var preocon='SI';
		}else if(u.preocon[1].checked==true){
			var preocon='NO';
		}	
		if(datos.indexOf("autorizo")>-1){
			abrirVentanaFija('asignacionguiasempresariales.php?ncliente='+u.ncliente.value+'&cantidad='+u.cantidad.value+'&preocon='+preocon+'&folio='+u.folio.value+'&essolicitud=SI', 625, 590, 'ventana', 'Buscar Solicitud');
		}
	}
</script>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="650" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla"><span class="FondoTabla Estilo4">SOLICITUD DE GU&Iacute;AS EMPRESARIALES</span></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="Tablas">
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="Tablas">
                <tr>
                  <td width="9%">Folio:</td>
                  <td width="11%"><input name="folio" class="Tablas" type="text" id="folio" style="width:50px;background:#FFFF99" readonly="" /></td>
                  <td width="8%"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/BuscarSolicitudGuasEmpresariales_Gen_New.php?funcion=obtenerSolicitud&con=1', 625, 430, 'ventana', 'Buscar Solicitud')"></div></td>
                  <td width="7%">Fecha:</td>
                  <td width="65%"><input name="fecha" class="Tablas" type="text" id="fecha" style="background:#FFFF99; width:80px"/></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td class="FondoTabla">Cliente</td>
          </tr>
          <tr>
            <td><table width="100%" height="12" border="0" cellpadding="0" cellspacing="0" class="Tablas">
              <tr>
                <td width="11%"><span style="width:70px"># Cliente:</span></td>
                <td width="13%"><input name="ncliente" class="Tablas" type="text" id="ncliente" style="width:70px"  onkeypress="if(event.keyCode==13){pedirCliente(this.value);}"/></td>
                <td width="6%"><div id="btncliente" class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=pedirCliente', 625, 430, 'ventana', 'Buscar Cliente')"></div></td>
                <td width="8%"><span style="width:70px">Nombre:</span></td>
                <td width="62%"><input name="nombre" class="Tablas" type="text" id="nombre" style="width:350px;background:#FFFF99"  readonly=""/></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="2" class="Tablas">
              <tr>
                <td width="67">Ap. Paterno:</td>
                <td colspan="2"><input name="paterno" class="Tablas" type="text" id="paterno" style="width:200px;background:#FFFF99" readonly=""/></td>
                <td width="85">Ap. Materno:</td>
                <td width="250"><input name="materno" class="Tablas" type="text" id="materno" style="width:200px;background:#FFFF99" readonly=""/></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td class="FondoTabla">Asignaci&oacute;n</td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="2" class="Tablas">
              <tr>
                <td width="219"><input name="preocon" type="radio" value="1"  />
                  Pre-Pagadas
                    <input name="preocon" type="radio" value="2" />
                  Consignación</td>
                <td width="421"><span style="width:70px">Cantidad:
                  <input name="cantidad" class="Tablas" type="text" id="cantidad" />
                  <label>
                  <input name="vigencia" type="hidden" id="vigencia" />
                  </label>
                </span></td>
              </tr>
              
            </table></td>
          </tr>
          <tr>
            <td class="Tablas"><table width="373" border="0" align="right" cellpadding="0" cellspacing="2">
              <tr>
                <td width="125" align="right" ><div id="btnAutorizar" class="ebtn_autorizar" onclick="confirmar('¿Desea realizar la asignacion de guias empresariales?','¡Atencion!','AbrirAsignacionGuiasEmp()','Autorizar()')" style="visibility:hidden"></div></td>
                <td width="79" ><div id="btnCancelar" class="ebtn_cancelar" onclick="confirmar('¿Esta seguro de cancelar la solicitud de folios?', '', 'Cancelar();', '')" style="visibility:hidden"></div></td>
                <td width="78" id="td_guardar" ><div id="Guardar" class="ebtn_guardar" onclick="Validar()"></div></td>
                <td width="81"><div class="ebtn_nuevo" onclick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'Limpiar();', '')"></div></td>
              </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>