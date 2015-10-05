<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd 
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id
	ORDER BY descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".cambio_texto($f[0])."'".','.$desc; 	
		}
		$desc = substr($desc, 0, -1);
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>
</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../javascript/ajaxlist/ajax.js"></script>
<script src="../javascript/moautocomplete.js"></script>
	<style type="text/css">
			
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:175px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
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
	
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />

<script>
	
	var btnimprimir = '<img src="../img/Boton_Imprimir.gif" onclick="imprimir()" style="cursor:hand">';	
	var enviar = '<img src="../img/Boton_Email.gif" id="btn_enviar" width="70" height="20" style="cursor:pointer" onclick="enviarCorreo()" />';
	var tabla1 	= new ClaseTabla();
	var u 		= document.all;
	tabla1.setAttributes({
		nombre:"tablaguias",
		campos:[
			{nombre:"CANT", medida:45, alineacion:"right", datos:"cantidad"},
			{nombre:"ID", medida:2, alineacion:"right", tipo:"oculto", datos:"id"},
			{nombre:"DESCRIPCION", medida:150, alineacion:"left", datos:"descripcion"},
			{nombre:"CONTENIDO", medida:150, alineacion:"left", datos:"contenido"},
			{nombre:"PESO_KG", medida:45, alineacion:"right", datos:"peso"},
			{nombre:"LARGO", medida:40, alineacion:"right",  datos:"largo"},
			{nombre:"ANCHO", medida:40, alineacion:"right",  datos:"ancho"},
			{nombre:"ALTO", medida:40, alineacion:"right",  datos:"alto"},
			{nombre:"P_TOTAL", medida:4, alineacion:"right", tipo:"oculto", datos:"pesototal"},			
			{nombre:"P_VOLU", medida:40, alineacion:"right", datos:"volumen"},
			{nombre:"UNIT", medida:4, alineacion:"right", tipo:"oculto", datos:"pesounit"},
			{nombre:"FECHA", medida:4, alineacion:"right", tipo:"oculto", datos:"fecha"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerGeneral();
		u.img_eliminar.style.visibility="hidden";
		<?
			$_GET[funcion2] = str_replace("\'","'",$_GET[funcion2]);
			if($_GET[funcion2]!=""){
				echo 'setTimeout("'.$_GET[funcion2].'",1500);';
			}
		?>
	}	

	function buscarUnaGuia(guia){
		consultaTexto("mostrarCorreo","correointerno_con.php?accion=7&guia="+guia);
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","correointerno_con.php?accion=0");
	}
	function mostrarGeneral(datos){
		var row = datos.split(",");
		u.folio.value = row[0];
		u.fecha.value = row[1];
	}
	function ModificarFila(){
		if(u.estado.value == ""){
			var obj = tabla1.getSelectedRow();		
			if(tabla1.getValSelFromField("cantidad","CANT")!=""){
			u.indice.value = tabla1.getSelectedIdRow();
			abrirVentanaFija('correoInternoAgregarFilas.php?&cantidad='+obj.cantidad
					+'&id='+obj.id
					+'&descripcion='+obj.descripcion
					+'&contenido='+obj.contenido
					+'&peso='+obj.peso
					+'&largo='+obj.largo
					+'&ancho='+obj.ancho
					+'&alto='+obj.alto
					+'&pesototal='+obj.pesototal
					+'&volumen='+obj.volumen
					+'&pesounit='+obj.pesounit
					+'&fechahora='+obj.fecha
					+'&funcion=agregarDatos&eliminar=1&esmodificar=si', 460, 410, 'ventana', 'Datos Correo Interno');	
			}
		}
	}
	
	function obtenerDestino(id,sucursal,descripcion){
		document.getElementById('sucursal').value = sucursal;
		document.getElementById('sucursaldestino').value = id;
	}
	
	function devolverDestino(){		
		if(u.sucursaldestino.value==""){
			setTimeout("devolverDestino()",500);
		}else{
			consultaTexto("mostrarDestino", "correointerno_con.php?accion=4&destino="+u.sucursaldestino.value);
		}
	}
	
	function mostrarDestino(datos){
		row = datos.split(",");
		u.sucursaldestino.value = row[1];
		u.idremitente.focus();
	}
	
	function buscarEmpleado1(valor){
		consultaTexto("resBuscarEmpleado1","correointerno_con.php?accion=1&idempleado="+valor);
	}
	
	function buscarEmpleado2(valor){
		consultaTexto("resBuscarEmpleado2","correointerno_con.php?accion=1&idempleado="+valor);
	}
	
	function resBuscarEmpleado1(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			document.getElementById('idremitente').value = obj.id;
			document.getElementById('rem_rfc').value = obj.rfc;
			document.getElementById('rem_empleado').value = obj.nempleado;
			u.iddestinatario.focus();
		}else{
			alerta("El codigo del Empleado no existe","�Atenci�n!","idremitente");
			document.getElementById('idremitente').value = "";
			document.getElementById('rem_rfc').value = "";
			document.getElementById('rem_empleado').value = "";
		}
	}
	
	function resBuscarEmpleado2(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			document.getElementById('iddestinatario').value = obj.id;
			document.getElementById('des_rfc').value = obj.rfc;
			document.getElementById('des_empleado').value = obj.nempleado;
		}else{
			alerta("El codigo del Empleado no existe","�Atenci�n!","idremitente");
			document.getElementById('idremitente').value = "";
			document.getElementById('rem_rfc').value = "";
			document.getElementById('rem_empleado').value = "";
		}
	}
	function borrarFila(){
		if(tabla1.getValSelFromField('cantidad','CANT')==""){
			alerta3('Debe Seleccionar la fila a eliminar','�Atenci�n!');
		}else{
			confirmar('�Esta seguro de Eliminar la mercancia seleccionada?','','eliminarFila()','');	
		}
	}
	function eliminarFila(){		
		var obj = tabla1.getSelectedRow();
		consultaTexto("elimino","correointerno_con.php?accion=8&fecha="+obj.fecha);			
	}
	
	function elimino(datos){
		if(datos.indexOf("ok")>-1){
			tabla1.deleteById(tabla1.getSelectedIdRow());
			if(tabla1.getRecordCount()==0){
				u.img_eliminar.style.visibility="hidden";
				return false;
			}
		}else{
			alerta3("Hubo un error al eliminar la fila "+datos,"�Atenci�n!");
		}
	}
	
	function agregarDatos(variable){
		if(u.indice.value==""){			
			tabla1.add(variable);			
		}else{
			tabla1.updateRowById(tabla1.getSelectedIdRow(), variable);
			u.indice.value = "";
		}		
		u.img_eliminar.style.visibility="visible";
	}
	function devolverRemitente(id){
		consultaTexto("resBuscarEmpleado1","correointerno_con.php?accion=1&idempleado="+id);
	}
	function devolverDestinatario(id){
		consultaTexto("resBuscarEmpleado2","correointerno_con.php?accion=1&idempleado="+id);
	}
	function enviarCorreo(){
		if(u.sucursaldestino.value == "" || u.sucursal.value == ""){			
			alerta("Debe capturar Sucursal destino","�Atenci�n!","sucursal");
			return false;
		}else if(u.idremitente.value == "" || u.iddestinatario.value == ""){
			alerta("Debe capturar "+((u.idremitente.value == "")?'Remitente':'Destinatario'),"�Atenci�n!",((u.idremitente.value == "")?'idremitente':'iddestinatario'));
			return false;
		}else if(tabla1.getRecordCount()==0){
			alerta3("Debe capturar la mercancia en el detalle","�Atenci�n!");
			return false;
		}else{
			var tot = ""; v_tot = 0;
			tot = tabla1.getValuesFromField("cantidad",",").split(",");		
			for(var i=0;i<tot.length;i++){
				v_tot = parseFloat(tot[i]) + parseFloat(v_tot);
			}
			u.totalpaquetes.value = v_tot;			
			var peso = ""; var vol = "";
			v_peso = 0; v_vol = 0;			
			peso = tabla1.getValuesFromField("pesototal",",").split(",");
			vol = tabla1.getValuesFromField("volumen",",").split(",");
			for(var i=0;i<peso.length;i++){
				v_peso = parseFloat(peso[i]) + parseFloat(v_peso);
			}
			u.totalpeso.value = v_tot;
			
			for(var i=0;i<vol.length;i++){
				v_vol = parseFloat(vol[i]) + parseFloat(v_vol);						
			}
			u.totalvolumen.value = v_vol;
			
			var arr = new Array();
			arr[0] = u.fecha.value;
			arr[1] = u.sucursaldestino.value;
			arr[2] = u.idremitente.value;
			arr[3] = u.iddestinatario.value;
			arr[4] = u.sucursaldestino.value;
			consultaTexto("registro","correointerno_con.php?accion=3&arre="+arr+"&totalpaquetes="+u.totalpaquetes.value+"&totalpeso="+u.totalpeso.value+"&totalvolumen="+u.totalvolumen.value);
		}
	}
	
	function registro(datos){
		if(datos.indexOf("guardado")>-1){
			row = datos.split(",");
			u.folio.value = row[1];
			u.estado.value= "GUARDADO";
			info("Los datos han sido guardados correctamente","");
			u.folioguia.innerHTML = row[2];
			u.td_imprimir.innerHTML = btnimprimir;
			u.img_eliminar.style.visibility = "hidden";
			u.btnAgregar.style.visibility = "hidden";
		}else{
			alerta3("Hubo un error al guardar "+datos,"�Atenci�n!");
		}
	}
	function obtenerCorreo(id){
		u.folio.value = id;
		consultaTexto("mostrarCorreo","correointerno_con.php?accion=5&correo="+id);
	}
	function obtenerCorreoEnter(id){		
		consultaTexto("mostrarCorreo","correointerno_con.php?accion=5&correo="+id);
	}
	function mostrarCorreo(datos){
		if(datos.indexOf("no encontro")<0){			
			var obj = eval(convertirValoresJson(datos));
			u.btn_enviar.style.visibility 	= "hidden";
			u.folio.value			= obj.principal.folio;
			u.fecha.value			= obj.principal.fecha;
			u.estado.value			= obj.principal.estado;
			u.sucursaldestino.value	= obj.principal.destino;
			u.sucursal.value		= obj.principal.ddestino;
			u.idremitente.value 	= obj.principal.remitente;
			u.rem_empleado.value	= obj.principal.rem;
			u.rem_rfc.value			= obj.principal.remrfc;			
			u.iddestinatario.value 	= obj.principal.destintario;
			u.des_empleado.value	= obj.principal.des;
			u.des_rfc.value			= obj.principal.desrfc;
			u.folioguia.innerHTML 	= obj.principal.guia;
			
			u.btnAgregar.style.visibility 	= "hidden";
			u.img_eliminar.style.visibility = "hidden";
			tabla1.setJsonData(obj.detalle);
		}else{
			alerta("El folio de Correo interno no existe","�Atenci�n!","folio");
			nuevo("1");
		}
	}
	function nuevo(tipo){
		u.estado.value			= "";
		u.sucursaldestino.value	= "";
		u.sucursal.value		= "";
		u.idremitente.value		= "";
		u.iddestinatario.value	= "";
		u.des_rfc.value			= "";		
		u.des_empleado.value	= "";
		u.rem_empleado.value	= "";
		u.rem_rfc.value			= "";
		u.totalpaquetes.value	= "";
		u.totalvolumen.value 	= "";
		u.totalpeso.value 		= "";
		u.folioguia.innerHTML 	= "";				
		if(tipo==""){
			u.td_imprimir.innerHTML = enviar;
			u.img_eliminar.style.visibility = "visible";
			u.btnAgregar.style.visibility 	= "visible";
			obtenerGeneral();
		}
		tabla1.clear();
	}
	function imprimir(){
		<? 
			$s = "SELECT impetiquetasguias FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
			$rxy = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rxy)>0){
				$fxy = mysql_fetch_object($rxy);
				echo "var met = new ActiveXObject('Impresion.Metodos');
				met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetasguias)."');";
			}else{
				$s = "SELECT impetiquetasguias FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
				$rxy = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rxy)>0){
					$fxy = mysql_fetch_object($rxy);
					echo "var met = new ActiveXObject('Impresion.Metodos');
					met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetasguias)."');";
				}
			}
		?>
		window.open("imprimiretiquetaguia.php?tipo=3&codigo="+u.folioguia.innerHTML,"1","width=500,height=500");
	}
	function cambiarImpresora1(){
		<? 
			$s = "SELECT impetiquetaspaquetes FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
			$rxy = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rxy)>0){
				$fxy = mysql_fetch_object($rxy);
				
				echo "var met = new ActiveXObject('Impresion.Metodos');
				met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetaspaquetes)."');";
			}else{
				$s = "SELECT impetiquetaspaquetes FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
				$rxy = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rxy)>0){
					$fxy = mysql_fetch_object($rxy);
					echo "var met = new ActiveXObject('Impresion.Metodos');
					met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetaspaquetes)."');";
				}
			}
		?>
		window.open("imprimiretiquetapaquete.php?tipo=3&codigo="+u.folioguia.innerHTML+"&correo="+u.folio.value,"2","width=500,height=500");
	}
	function cambiarImpresora2(){
		<? 
			$s = "SELECT impdefault FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
			$rxy = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rxy)>0){
				$fxy = mysql_fetch_object($rxy);
				
				echo "var met = new ActiveXObject('Impresion.Metodos');
				met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impdefault)."');";
			}else{
				$s = "SELECT impdefault FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
				$rxy = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rxy)>0){
					$fxy = mysql_fetch_object($rxy);
					echo "var met = new ActiveXObject('Impresion.Metodos');
					met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impdefault)."');";
				}
			}
		?>
	}
	
	var desc = new Array(<?php echo $desc; ?>);
	
</script>
</head>
<body>
<table width="618" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="614" class="FondoTabla">Correo Interno </td>
  </tr>
  <tr>
    <td>
    	<table width="616" cellpadding="0" cellspacing="0" border="0">
    <tr>
           	  <td width="42">Fecha:</td>
              <td width="99"><input type="text" name="fecha" class="Tablas" readonly="readonly" style="background:#FFFF99; width:80px" /></td>
                <td width="54">Estado:</td>
                <td colspan="2"><input type="text" name="estado" class="Tablas" readonly="readonly" style="background:#FFFF99; width:150px" /></td>
          <td width="49">Folio:</td>
                <td width="154"><input name="folio" type="text" class="Tablas" style="width:80px" onkeypress="if(event.keyCode==13){obtenerCorreoEnter(this.value);}" maxlength="10" />
                <img src="../img/Buscar_24.gif" alt="Buscar Destino" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarCorreoInternoGen.php?funcion=obtenerCorreo', 600, 500, 'ventana', 'Busqueda')" /></td>
          </tr>
    <tr>
      <td colspan="5">Sucursal Destino:
        <input name="sucursal" type="text" class="Tablas" id="sucursal" style="font-size:9px; text-transform:uppercase" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursaldestino.value=this.codigo; document.all.idremitente.focus();}" onblur="if(this.value!=''){document.all.sucursaldestino.value = this.codigo;}" size="35" maxlength="60" />
        <input type="hidden" name="sucursaldestino" />
        <img src="../img/Buscar_24.gif" alt="Buscar Destino" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../evaluacion/buscarDestinoEvaluacion.php', 600, 500, 'ventana', 'Busqueda')" /></td>
      <td colspan="2" align="center" id="folioguia" style="color:#F00000; font-size:15px; font-weight:bold">&nbsp;</td>
      </tr>
    <tr>
      <td></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td width="160">&nbsp;</td>
      <td width="58">&nbsp;</td>
      <td><input name="indice" type="hidden" id="indice" value="<?=$accion ?>" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7"><table width="615" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
        <tr>
          <td width="324" class="FondoTabla">Remitente</td>
          <td width="280" class="FondoTabla">Destinatario</td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
              <tr>
                <td width="22%"><span class="Tablas"># Empleado: </span></td>
                <td width="24%"><input name="idremitente" type="text" class="Tablas" style="font:tahoma; font-size:9px" onkeypress="if(event.keyCode==13 &amp;&amp; this.readOnly==false){ devolverRemitente(this.value)}else{return solonumeros(event)}" value="<?=$remitente ?>" size="4" maxlength="10" />
                  &nbsp;&nbsp;<img src="../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarEmpleadoGen.php?funcion=buscarEmpleado1', 500, 450, 'ventana', 'Busqueda')" /></td>
                <td width="54%">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>
                    <input name="rem_rfc" readonly="true" type="text" class="Tablas" style="background:#FFFF99;font:tahoma; font-size:9px; width:100px" value="<?=$rrfc ?>"  /></td>
              </tr>
              <tr>
                <td><span class="Tablas">Empleado::</span></td>
                <td colspan="2"><table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="200"><input name="rem_empleado" class="Tablas" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; width:220px" 
                    value="<?=$rcliente ?>" /></td>
                      <td width="37" align="right" valign="middle">&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
              <tr>
                <td width="28%"><input name="iddestinatario" type="text" class="Tablas" style="font:tahoma; font-size:9px" onkeypress="if(event.keyCode==13 &amp;&amp; this.readOnly==false){devolverDestinatario(this.value)}else{return solonumeros(event)}" value="<?=$remitente ?>" size="4" maxlength="10" />
                  &nbsp;&nbsp;<img src="../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarEmpleadoGen.php?funcion=buscarEmpleado2', 500, 450, 'ventana', 'Busqueda')" /></td>
                <td width="72%">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>
                    <input name="des_rfc" type="text" readonly="true" class="Tablas" style="background:#FFFF99;font:tahoma; font-size:9px; width:100px" value="<?=$rrfc ?>"  /></td>
              </tr>
              <tr>
                <td colspan="2"><table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="200"><input name="des_empleado" class="Tablas" readonly="true" type="text" 
                  style="background:#FFFF99;font:tahoma; font-size:9px; width:220px" value="<?=$rcliente ?>" /></td>
                      <td width="65" align="right" valign="middle">&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
        </tr>
      </table></td>
</tr>
    <tr>
      <td height="5px"></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td height="130" colspan="7">
	 	 <table width="560" border="0" align="center" cellpadding="0">
                <tr>
                  <td><table id="tablaguias" width="560" border="0" cellpadding="0" cellspacing="0"></table></td>
                </tr>
                <tr>
                  <th scope="row"><table width="150" border="0" align="right">
                      <tr>
                        <td width="70"><img  id="img_eliminar" src="../img/Boton_Eliminar.gif" alt="Eliminar" width="70" height="20" style="cursor:pointer" onClick="borrarFila()" /></td>
                        <td width="95"><img id="btnAgregar" src="../img/Boton_Agregari.gif" width="70" height="20" style="cursor:pointer" onClick="abrirVentanaFija('correoInternoAgregarFilas.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluaci&oacute;n')" /></td>
                      </tr>
                    </table>                  </th>
                </tr>
            </table>	  </td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="totalpaquetes" type="hidden" id="totalpaquetes" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="totalpeso" type="hidden" id="totalpeso" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="totalvolumen" type="hidden" id="totalvolumen" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td >&nbsp;</td>
      <td colspan="2" ><table width="166" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="71" id="td_imprimir"><img src="../img/Boton_Email.gif" id="btn_enviar" width="70" height="20" style="cursor:pointer" onclick="enviarCorreo()" /></td>
          <td width="95" align="right"><div class="ebtn_nuevo" onclick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?','','nuevo(\'\')','');"></div></td>
        </tr>
      </table></td>
    </tr>
        </table>    </td>
  </tr>
</table>
</body>
</html>