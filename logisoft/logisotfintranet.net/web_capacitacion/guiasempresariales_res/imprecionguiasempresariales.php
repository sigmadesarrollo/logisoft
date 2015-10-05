<?	session_start();
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id
	ORDER BY descripcion";	
	$r = mysql_query($s,$link) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".cambio_texto($f[0])."'".','.$desc;
			$ori= "'".cambio_texto($f[0])."'".','.$ori;
		}
		$desc = "'VARIOS:0',".$desc;		
		$desc = substr($desc, 0, -1);
		$ori  = "'VARIOS:0',".$ori;		
		$ori  = substr($ori, 0, -1);			
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">

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
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript',false);
	
	function pedirFolios(id){
		consultaTexto("resPedirFolios","imprecionguiasempresariales_con.php?accion=2&idF="+id);
	}
	
	function resPedirFolios(valor){
		var obj = eval(convertirValoresJson(valor));
		u.ventas.value		= obj[0].id;
		u.rfc.value			= obj[0].rfc;
		u.ncliente.value	= obj[0].idC;
		u.ncliente.value	= obj[0].idC;
		u.nick.value		= obj[0].nick;
		u.nombre.value		= obj[0].nombre;
		u.Apaterno.value	= obj[0].paterno;
		u.Amaterno.value	= obj[0].materno;
		u.calle.value		= obj[0].calle;
		u.numero.value		= obj[0].numero;
		u.colonia.value		= obj[0].colonia;
		u.cp.value			= obj[0].cp;
		u.poblacion.value	= obj[0].poblacion;
		u.estado.value		= obj[0].estado;
		u.telefono.value	= obj[0].telefono;
		u.finicial.value	= obj[0].desdefolio;
		u.fondo2.value		= obj[0].hastafolio;
	}
	
	function obtenerRemitente(id){
		consultaTexto("mostrarRemitente","imprecionguiasempresariales_con.php?accion=1&idC="+id);
	}
	
	function obtenerDestinatario(id){
		consultaTexto("mostrarDestinatario","imprecionguiasempresariales_con.php?accion=1&idC="+id);
	}
	
	function mostrarRemitente(datos){
		if(datos.indexOf("no encontro")<0){			
			var obj = eval(convertirValoresJson(datos));
			u.nick.value		= obj[0].nick;
			u.rfc.value			= obj[0].rfc;
			u.ncliente.value	= obj[0].id;
			u.nombre.value		= obj[0].nombre;
			u.Apaterno.value	= obj[0].paterno;
			u.Amaterno.value	= obj[0].materno;
			u.calle.value		= obj[0].calle;
			u.numero.value		= obj[0].numero;
			u.colonia.value		= obj[0].colonia;
			u.cp.value			= obj[0].cp;
			u.poblacion.value	= obj[0].poblacion;
			u.estado.value		= obj[0].estado;
			u.telefono.value	= obj[0].telefono;
		}else{
			mens.show("A","El numero del Remitente no existe","¡Atención!","ncliente");
			u.nick.value		= "";
			u.rfc.value			= "";
			u.ncliente.value	= "";
			u.nombre.value		= "";
			u.Apaterno.value	= "";
			u.Amaterno.value	= "";
			u.calle.value		= "";
			u.numero.value		= "";
			u.colonia.value		= "";
			u.cp.value			= "";
			u.poblacion.value	= "";
			u.estado.value		= "";
			u.telefono.value	= "";
		}
	}
	function mostrarDestinatario(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.nick2.value		= obj[0].nick;
			u.rfc2.value		= obj[0].rfc;
			u.ncliente2.value	= obj[0].id;
			u.nombre2.value		= obj[0].nombre;
			u.Apaterno2.value	= obj[0].paterno;
			u.Amaterno2.value	= obj[0].materno;
			u.calle2.value		= obj[0].calle;
			u.numero2.value		= obj[0].numero;
			u.colonia2.value	= obj[0].colonia;
			u.cp2.value			= obj[0].cp;
			u.poblacion2.value	= obj[0].poblacion;
			u.estado2.value		= obj[0].estado;
			u.telefono2.value	= obj[0].telefono;
		}else{
			mens.show("A","El numero del Destinatario no existe","¡Atención!","ncliente2");
			u.nick2.value		= "";
			u.rfc2.value		= "";
			u.ncliente2.value	= "";
			u.nombre2.value		= "";
			u.Apaterno2.value	= "";
			u.Amaterno2.value	= "";
			u.calle2.value		= "";
			u.numero2.value		= "";
			u.colonia2.value	= "";
			u.cp2.value			= "";
			u.poblacion2.value	= "";
			u.estado2.value		= "";
			u.telefono2.value	= "";
		}
	}
	var desc = new Array(<?php echo $desc; ?>);
	var ori = new Array(<?php echo $ori; ?>);
</script>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="623" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">IMPRESI&Oacute;N DE GU&Iacute;AS EMPRESARIALES</td>
  </tr>
  <tr>
    <td><table width="622" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="136"><label>
          <input class="Tablas"  name="seleccionradio" type="radio" value="radiobutton" checked="checked"/>
          Pre-Pagadas</label></td>
        <td width="101" ><input class="Tablas"  name="seleccionradio" type="radio" value="radiobutton" />
Consignación</td>
        <td width="385" colspan="3">No.Ventas<span class="Tablas">
        <input name="ventas" type="text" class="Tablas" id="ventas" style="width:100px;background:#FFFF99" readonly=""/>
        </span><img id="b_remitente" src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarImpGuiEmpVentas.php?funcion=pedirFolios&prepagada='+((document.all.seleccionradio[0].checked==true)?'SI':'NO'), 625, 418, 'ventana', 'Busqueda')" /></td>
        </tr>
      <tr>
        <td height="21" colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="22%"><input class="Tablas"  type="checkbox" name="checkbox" value="checkbox" />
Imprimir Remitente</td>
            <td width="9%">Origen:</td>
            <td width="33%"><label>
              <input name="origen" class="Tablas" type="text" id="origen" style="width:180px" autocomplete="array:ori" onblur="document.all.h_origen.value = this.codigo" />
            </label></td>
            <td width="10%">Folio Inicial:</td>
            <td width="26%"><span class="Tablas">
              <input name="finicial" type="text" class="Tablas" id="finicial" style="width:100px" />
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="21" colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="22%"><input class="Tablas"  type="checkbox" name="checkbox2" value="checkbox" />
Imprimir Destinatario</td>
            <td width="9%">Destino:</td>
            <td width="33%"><input name="destino" class="Tablas" type="text" id="destino" style="width:180px" autocomplete="array:desc" onblur="document.all.h_destino.value = this.codigo"/></td>
            <td width="10%">Folio Final:</td>
            <td width="26%"><span class="Tablas">
              <input name="fondo2" type="text" class="Tablas" id="fondo2" style="width:100px" />
            </span></td>
          </tr>
        </table></td>
        </tr>
      

      <tr>
        <td colspan="5"><table width="610" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="5" class="FondoTabla Estilo4">Remitente</td>
            <td colspan="5" class="FondoTabla Estilo4">Destinatario</td>
          </tr>
          <tr>
            <td width="58"><label></label>
              <label>#Cliente</label></td>
            <td width="86"><input class="Tablas"  name="ncliente" type="text" id="ncliente" style="width:80px" onkeypress="if(event.keyCode==13){obtenerRemitente(this.value)}"/></td>
            <td width="40"><img id="b_remitente" src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerRemitente', 625, 418, 'ventana', 'Busqueda')" /></td>
            <td width="39">RFC </td>
            <td width="87"><input class="Tablas"  name="rfc" type="text" id="rfc" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px"   readonly=""/></td>
            <td width="67"><label></label><label>#Cliente</label></td>
            <td width="80"><input class="Tablas"  name="ncliente2" type="text" id="ncliente2" style="width:80px" onkeypress="if(event.keyCode==13){obtenerDestinatario(this.value)}"/></td>
            <td width="40"><img id="b_remitente" src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerDestinatario', 625, 418, 'ventana', 'Busqueda')" /></td>
            <td width="39">RFC </td>
            <td width="80"><input class="Tablas"  name="rfc2" type="text" id="rfc2" style="width:80px;font:tahoma; font-size:9px"/></td>
          </tr>
          <tr>
            <td><label></label>
                <label>Nick</label></td>
            <td><input class="Tablas"  name="nick" type="text" id="nick" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px"  readonly=""/></td>
            <td colspan="2">Nombre</td>
            <td><input class="Tablas"  name="nombre" type="text" id="nombre" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px"  readonly=""/></td>
            <td><label></label>
                <label>Nick</label>
                <label></label></td>
            <td><input class="Tablas"  name="nick2" type="text" id="nick2" style="width:80px;font:tahoma;font-size:9px"/></td>
            <td colspan="2">Nombre</td>
            <td><input class="Tablas"  name="nombre2" type="text" id="nombre2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" readonly=""/></td>
          </tr>
          <tr>
            <td><label>Ap. Paterno</label>
                <label></label></td>
            <td><input name="Apaterno" type="text" class="Tablas" id="Apaterno" style="width:80px;background:#FFFF99"  readonly=""/></td>
            <td colspan="2">Ap. Materno </td>
            <td><input name="Amaterno" type="text" class="Tablas" id="Amaterno" style="width:80px;background:#FFFF99"  readonly=""/></td>
            <td><label>Ap. Paterno</label>
                <label></label></td>
            <td><input class="Tablas"  name="Apaterno2" type="text" id="Apaterno2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" readonly=""/></td>
            <td colspan="2">Ap. Materno </td>
            <td><input class="Tablas"  name="Amaterno2" type="text" id="Amaterno2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" readonly=""/></td>
          </tr>
          <tr>
            <td><label>Calle</label>
                <label></label></td>
            <td colspan="2"><input class="Tablas"  name="calle" type="text" id="calle" style="width:110px;background:#FFFF99;font:tahoma; font-size:9px"  readonly=""/></td>
            <td>N&uacute;mero</td>
            <td><input class="Tablas"  name="numero" type="text" id="numero" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px"  readonly=""/></td>
            <td><label>Calle</label>
                <label></label></td>
            <td colspan="2"><input class="Tablas"  name="calle2" type="text" id="calle2" style="width:120px;background:#FFFF99;font:tahoma; font-size:9px" readonly=""/></td>
            <td>N&uacute;mero</td>
            <td><input class="Tablas"  name="numero2" type="text" id="numero2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" readonly=""/></td>
          </tr>
          <tr>
            <td><label>Colonia</label>
                <label></label></td>
            <td colspan="2"><input class="Tablas"  name="colonia" type="text" id="colonia" style="width:120px;background:#FFFF99;font:tahoma; font-size:9px"  readonly=""/></td>
            <td><label> </label>
                <label>CP </label></td>
            <td><input class="Tablas"  name="cp" type="text" id="cp" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px"  readonly=""/></td>
            <td><label>Colonia</label>
                <label></label></td>
            <td colspan="2"><input class="Tablas"  name="colonia2" type="text" id="colonia2" style="width:120px;background:#FFFF99;font:tahoma; font-size:9px" readonly=""/></td>
            <td><label> </label>
                <label>CP </label></td>
            <td><input class="Tablas"  name="cp2" type="text" id="cp2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" readonly=""/></td>
          </tr>
          <tr>
            <td><label>Poblacion</label></td>
            <td colspan="4"><input class="Tablas"  name="poblacion" type="text" id="poblacion" style="width:238px;background:#FFFF99;font:tahoma; font-size:9px"  readonly=""/></td>
            <td><label>Poblacion</label></td>
            <td colspan="4"><input class="Tablas"  name="poblacion2" type="text" id="poblacion2" style="width:238px;background:#FFFF99;font:tahoma; font-size:9px" readonly=""/></td>
          </tr>
          <tr>
            <td><label>Estado</label>
                <label></label></td>
            <td><input class="Tablas"  name="estado" type="text" id="estado" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px"  readonly=""/></td>
            <td colspan="2">Telefono </td>
            <td><input class="Tablas"  name="telefono" type="text" id="telefono" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px"  readonly=""/></td>
            <td><label>Estado</label>
                <label></label></td>
            <td><input class="Tablas"  name="estado2" type="text" id="estado2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" readonly=""/></td>
            <td colspan="2">Telefono </td>
            <td><input class="Tablas"  name="telefono2" type="text" id="telefono2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" readonly=""/></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="5" class="FondoTabla Estilo4">Observaciones</td>
      </tr>
      <tr>
        <td colspan="5"><label>
        <input class="Tablas"  name="observaciones" type="text" id="observaciones" style="width:605px;background:#FFFF99;font:tahoma; font-size:9px"  readonly=""/>
        </label></td>
      </tr>
      <tr>
        <td colspan="5"><input name="h_origen" type="hidden" id="h_origen" />
          <input name="h_destino" type="hidden" id="h_destino" /></td>
      </tr>
    </table>
    <table width="619" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
      </tr>
    </table>
      </td>
  </tr>
</table>
</form>
</body>
</html>