<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
		
	
	if($_POST['accion']==""){		
		$result=ObtenerFolio('entregasocurre','webpmm');
		$folio=$result[0];
		//Sucursal
		$suc = mysql_query("SELECT descripcion,prefijo FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."",$l); 
		$rsuc = @mysql_fetch_array($suc); 
		$id_sucursal =$rsuc[0];
		$sucursal 	 =$rsuc['prefijo'];
	}else if($_POST['accion']=="limpiar"){
		$result=ObtenerFolio('entregasocurre','webpmm');
		$folio=$result[0];
		//Sucursal
		$suc = mysql_query("SELECT descripcion,prefijo FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."",$l); 
		$rsuc = @mysql_fetch_array($suc); 
		$id_sucursal =$rsuc[0];
		$sucursal 	 =$rsuc['prefijo'];
		
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.Estilo4 {font-size: 12px}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
</style>
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script>
	var u		= document.all;
	var tabla1  = new ClaseTabla();	
	//celdabotones
	var botonesguardar = '<table width="177">'
       	    	+'<tr>'
                	+'<td><div class="ebtn_guardar"></div></td>'
               +' </tr>'
            +'</table>';
	var botonesnuevo = '<table width="177">'
       	    	+'<tr>'
                   + '<td><div class="ebtn_nuevo" ></div></td>'
               +' </tr>'
            +'</table>';
	
	tabla1.setAttributes({
		nombre:"tablalista",
		campos:[
			{nombre:"S", medida:20, alineacion:"center",onClick:"Total", tipo:"checkbox", datos:"sel"},
			{nombre:"No_GUIA", medida:80, alineacion:"center", datos:"guia"},
			{nombre:"ORIGEN", medida:39, alineacion:"left", datos:"origen"},
			{nombre:"FECHA", medida:60, alineacion:"center", datos:"fecha"},
			{nombre:"REMITENTE", medida:85, alineacion:"left", datos:"remitente"},
			{nombre:"DESTINATARIO", medida:85, alineacion:"left", datos:"destinatario"},
			{nombre:"TIPO_FLETE", medida:69, alineacion:"center", datos:"tipoflete"},
			{nombre:"ESTADO", medida:74, alineacion:"left", datos:"estado"},
			{nombre:"IMPORTE", medida:60, tipo:"moneda", alineacion:"right", datos:"importe"}
		],
		filasInicial:15,
		alto:200,
		seleccion:false,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	function limpiarDatos(){
		tabla1.clear();
		u.nombre.value = "";
		u.paterno.value = "";
		u.materno.value = "";
	}
	
	window.onload = function(){
		u.folio.focus();
		tabla1.create();
	}
	
	function pedirGuiasPGuia(tvalor){
		u.cliente.value	="";
		limpiarDatos();
		u.nguia.value	=tvalor;
		consultaTexto("mostrarGuias","entregaocurre_con.php?accion=1&buscar=1&folioguia="+tvalor+"&otros="+Math.random());
	}
	
	function pedirGuiasPCliente(tvalor){
		u.nguia.value	="";
		limpiarDatos();
		u.cliente.value	=tvalor;
		consultaTexto("mostrarGuias","entregaocurre_con.php?accion=1&cliente="+tvalor+"&otros="+Math.random());
	}
	
	function mostrarGuias(datos){
		if(datos.indexOf("SELECT")==-1){
			var objeto = eval(convertirValoresJson(datos));
			if(objeto.guias.length>0){
				tabla1.setJsonData(objeto.guias)
			}
			u.nombre.value = objeto.cliente.nombre;
			u.paterno.value = objeto.cliente.paterno;
			u.materno.value = objeto.cliente.materno;
		}
	}
	
	function validarDatos(){
		if(u.sucursal.value==""){
			alerta3("Seleccione la sucursal para poder continuar","¡Atencion!");
			return false;
		}
		if(u.nombre.value == ""){
			alerta3("Seleccione el cliente para poder continuar","¡Atencion!");
			return false;
		}
		if(!tabla1.getRecordCount()>0){
			alerta3("No hay guias seleccionadas","¡Atencion!");
			return false;
		}
		if(u.precibe.value==""){
			alerta3("Proporcione la persona que recibe","¡Atencion!");
			return false;
		}
		if(u.identificacion.value==""){
			alerta3("Proporcione el tipo de identificacion","¡Atencion!");
			return false;
		}
		if(u.nidentificacion.value==""){
			alerta3("Proporcione el numero de identificacion","¡Atencion!");
			return false;
		}
		if(tabla1.getValSelFromField("guia","S")==""){
			alerta3("No hay guias seleccionadas","¡Atencion!");	
			return false;
		}
		return true;
	}
	

	
	
function Total(){
	var folios = tabla1.getValSelFromField("importe","S");
	folios = folios.split(",");
	total=0.0;
	for(i=0;i<folios.length;i++){
		total += parseFloat(folios[i]);
	}
	if(!isNaN(total)){
		u.total.value=total;
	}else{
		u.total.value=0;
	}
		
	/***/
	u.efectivo.value	="";
	u.cheque.value		="";
	u.banco.value		="";
	u.ncheque.value		="";
	u.tarjeta.value		="";
	u.transferencia.value="";
	/***/
}

function obtenerSucursal(id,sucursal){
	u.id_sucursal.value	= id;
	u.sucursal.value 	= sucursal;
}

function ejecutarSubmit(){
		var folios = tabla1.getValSelFromField("guia","S");
		consultaTexto("resGuardar","entregaocurre_con.php?accion=2&folios="+folios
					 +"&idsucursal="+u.id_sucursal.value
					 +"&sucursal="+u.sucursal.value
					 +"&nguia="+u.nguia.value
					 +"&cliente="+u.cliente.value
					 +"&nombre="+u.nombre.value
					 +"&paterno="+u.paterno.value
					 +"&materno="+u.materno.value
					 +"&total="+u.total.value
					 +"&precibe="+u.precibe.value
					 +"&nidentificacion="+u.nidentificacion.value
					 +"&identificacion="+u.identificacion.value
					 +"&efectivo="+u.efectivo.value.replace("$ ","").replace(/,/,"")
					 +"&cheque="+u.cheque.value
					 +"&banco="+u.banco.value.replace("$ ","").replace(/,/,"")
					 +"&ncheque="+u.ncheque.value
					 +"&tarjeta="+u.tarjeta.value.replace("$ ","").replace(/,/,"")
					 +"&transferencia="+u.transferencia.value
					 +"&mathrand="+Math.random());
}

function resGuardar(datos){
		if(datos.indexOf("guardado")>-1){
			info("La informacion ha sido guardada","");
			confirmar('¿Desea limpiar los datos?','¡Atencion!','limpiar()','')
			u.guardar.style.visibility="hidden";
			//u.guardado.value = 1;
			
		}else{
			alerta3("Hubo un error "+datos,"¡Atencion!");
		}
}

function limpiar(){
	u.folio.value	="";
	u.sucursal.value="";
	u.nguia.value	="";
	u.cliente.value ="";
	u.nombre.value	="";
	u.paterno.value	="";
	u.materno.value	="";
	u.total.value	="";
	u.precibe.value	="";
	u.identificacion.value	="";
	u.nidentificacion.value	="";
	u.efectivo.value="";
	u.cheque.value	="";
	u.banco.value	="";
	u.ncheque.value	="";
	u.tarjeta.value	="";
	u.transferencia.value="";
	u.guardar.style.visibility="visible";
	tabla1.clear(); 
	u.accion.value	="limpiar";
	document.all.form1.submit();
}

function limpiarDatos(){
	u.nguia.value	="";
	u.cliente.value ="";
	u.nombre.value	="";
	u.paterno.value	="";
	u.materno.value	="";
	u.total.value	="";
	u.precibe.value	="";
	u.identificacion.value	="";
	u.nidentificacion.value	="";
	u.efectivo.value="";
	u.cheque.value	="";
	u.banco.value	="";
	u.ncheque.value	="";
	u.tarjeta.value	="";
	u.transferencia.value="";
	u.guardar.style.visibility="visible";
	tabla1.clear(); 
}

</script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="619" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="615" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td><table width="615" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="615" height="21">
        <table width="615" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="399"><div align="right">
              <input name="id_sucursal" type="hidden" id="id_sucursal" />
              Folio              </div></td>
            <td width="70"><input name="folio" type="text" class="Tablas" id="folio" style="width:70px;background:#FFFF99" value="<?=$folio ?>" readonly=""/></td>
            <td width="42">Sucursal</td>
            <td width="80"><input name="sucursal" type="text" id="sucursal" value="<?=$sucursal ?>" style="width:80px" /></td>
            <td width="24"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 600, 400, 'ventana', 'Forma de Pago')"></div></td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td class="FondoTabla Estilo4">Obciones Busqueda </td>
      </tr>
      <tr>
        <td><table width="615" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="49"><label>No. Gu&iacute;as</label></td>
              <td width="96"><input name="nguia" type="text" id="nguia" value="<?=$nguia ?>" onkeypress="if(event.keyCode==13){pedirGuiasPGuia(this.value)}" /></td>
              <td width="463"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarGuiasEmpresariales_VentanillaGen.php?funcion=pedirGuiasPGuia', 600, 400, 'ventana', 'Buscar')"></div></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="615" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="49" class="Tablas"><label>#Cliente </label></td>
              <td width="50" class="Tablas"><input name="cliente" type="text" class="Tablas" id="cliente" style="width:50px;" value="<?=$cliente ?>" onkeypress="if(event.keyCode==13){pedirGuiasPCliente(this.value)}"/></td>
              <td width="24" class="Tablas"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=pedirGuiasPCliente', 600, 400, 'ventana', 'Buscar')"></div></td>
              <td width="51"><span class="Tablas">
                <label>Nombre</label>
                <label></label>
                &nbsp;</span></td>
              <td width="84"><span class="Tablas">
                <input name="nombre" type="text" class="Tablas" id="nombre" style="width:80px;background:#FFFF99" value="<?=$nombre ?>" readonly=""/>
              </span></td>
              <td width="97"><span class="Tablas"> Apellido Paterno</span></td>
              <td width="80"><span class="Tablas">
                <input name="paterno" type="text" class="Tablas" id="paterno" style="width:80px;background:#FFFF99" value="<?=$paterno ?>" readonly=""/>
              </span></td>
              <td width="96"><span class="Tablas">Apellido Materno </span></td>
              <td width="84"><span class="Tablas">
                <input name="materno" type="text" class="Tablas" id="materno" style="width:80px;background:#FFFF99" value="<?=$materno ?>" readonly=""/>
              </span></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td>
        	<table cellpadding="0" cellspacing="0" border="0" id="tablalista"></table>
        </td>
      </tr>
      <tr>
        <td><div align="right">Total
          <input name="total" type="text" class="Tablas" id="total" style="width:70px;background:#FFFF99" value="<?=$total ?>" readonly=""/>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Persona que Recibe
          <input name="precibe" style="width:250px" type="text" id="precibe" value="<?=$precibe ?>" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Tipo Identificaci&oacute;n
          <select name="identificacion" class="Tablas" style="width:160px; text-align:center" >
          	<option value="" selected="selected">.:: IDENTIFICACION ::.</option>
          	<option value="0">CREDENCIAL DE ELECTOR</option>
            <option value="1">LICENCIA DE MANEJO</option>
            <option value="2">PASAPORTE</option>
            </select>
          No. Identificacion 
          <input name="nidentificacion" type="text" style="width:80px" id="nidentificacion" value="<?=$nidentificacion ?>" />
        </div></td>
      </tr>
      <tr>
        <td id="celdabotones" align="center">
        	<input name="efectivo" type="hidden" id="efectivo" />
        	<input name="cheque" type="hidden" id="cheque" />
        	<input name="banco" type="hidden" id="banco" />
        	<input name="ncheque" type="hidden" id="ncheque" />
        	<input name="tarjeta" type="hidden" id="tarjeta" />
        	<input name="transferencia" type="hidden" id="transferencia" />
        	<input name="accion" type="hidden" id="accion" />
        	<table width="161">
       	    	<tr>
                	<td width="160"><table width="155" border="0">
                        <tr>
                          <td width="75" ><div id="guardar" class="ebtn_guardar" onclick="if(validarDatos()){  abrirVentanaFija('formapago.php?total=' + document.all.total.value+'&cliente='+1, 600, 400, 'ventana', 'Forma de Pago');}" ></div></td>
                          <td width="70"><div id="nuevo" class="ebtn_nuevo" onclick="confirmar('¿Desea limpiar los datos?','¡Atencion!','limpiar()','')"></div></td>
                        </tr>
                      </table>
               	  </td>
                </tr>
            </table>
            <label>
            
            </label></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
<script>
	//parent.frames[1].document.getElementById('titulo').innerHTML = 'ENTREGAS OCURRE';
</script>
</html>