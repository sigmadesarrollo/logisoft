<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></link>
<script>
	var u = document.all;
	var mensaje = "";
	var mens = new ClaseMensajes();
	
	mens.iniciar("../javascript");
	
	window.onload = function(){
		obtenerCantidades();
		u.efectivo.focus();
	}
	
	function obtenerCantidades(){
		consultaTexto("mostrarCantidades","cierrecaja_con.php?accion=1&random="+Math.random());
	}
	
	function mostrarCantidades(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A","Error "+datos,"");
		}
		u.tefectivo.value = obj.principal.tefectivo;
		u.ttarjeta.value = obj.principal.ttarjeta;
		u.ttransferencia.value = obj.principal.ttransferencia;
		u.tcheque.value = obj.principal.tcheque;
		u.tretiros.value = obj.principal.tretiros;
		u.tnotascredito.value = obj.principal.notascredito;
		u.parcial.value = obj.principal.tipocierre;
		u.gcredito.value = obj.otros.tguiacredito; 
		u.gcanceladas.value = obj.otros.tguiacancelada;
		u.facturas.value = obj.otros.tfacturas;		
		u.iniciocaja.value = obj.otros.iniciocaja;
		u.iniciocaja.value = obj.otros.iniciocaja;
	}
	
	function validar(cierre){
		if(cierre=='definitivo'){
			<?=$cpermiso->verificarPermiso(446,$_SESSION[IDUSUARIO]);?>
		}	
		if(u.iniciocaja.value==0){
			mens.show('A','Debe Iniciar Caja antes de Cerrar Caja','¡Atención!','efectivo');			
			return false;
		}else if(u.parcial.value=="definitivo"){
			mens.show('A','Ya no puede Cerrar Caja por que ya se realizo el Cierre definitivo','¡Atención!','efectivo');
			return false;
		}else if(u.efectivo.value == ""){	
			mens.show('A','Debe Capturar Monto Efectivo','¡Atención!','efectivo');
			return false;
		}else if(u.efectivo.value < 0){	
			mens.show('A','Monto Efectivo debe ser mayor a Cero','¡Atención!','efectivo');
			return false;
		}else if(u.tarjeta.value == ""){		
			mens.show('A','Debe Capturar Monto Tarjeta','¡Atención!','efectivo');
			return false;
		}else if(u.tarjeta.value < 0){	
			mens.show('A','Monto Tarjeta debe ser mayor a Cero','¡Atención!','tarjeta');		
			return false;
		}else if(u.transferencia.value == ""){		
			mens.show('A','Debe Capturar Monto Transferencia','¡Atención!','transferencia');
			return false;
		}else if(u.transferencia.value < 0){
			mens.show('A','Monto Transferencia debe ser mayor a Cero','¡Atención!','transferencia');
			return false;
		}else if(u.cheque.value == ""){		
			mens.show('A','Debe Capturar Monto Cheque','¡Atención!','cheque');
			return false;
		}else if(u.cheque.value < 0){		
			mens.show('A','Monto Cheque debe ser mayor a Cero','¡Atención!','cheque');
			return false;
		}else if(u.retiros.value == ""){
		
			mens.show('A','Debe Capturar Monto Retiros','¡Atención!','retiros');
			return false;
		}else if(u.retiros.value < 0){
			mens.show('A','Monto Retiros debe ser mayor a Cero','¡Atención!','cheque');
			return false;
		}else if(u.gcredito.value == ""){
			mens.show('A','Debe Capturar Numero de Guias Credito','¡Atención!','gcredito');		
			return false;
		}else if(u.gcanceladas.value == ""){
			mens.show('A','Debe Capturar Numero de Guias Canceladas','¡Atención!','gcanceladas');
			return false;
		}else if(u.facturas.value == ""){
			mens.show('A','Debe Capturar Numero de Facturas Canceladas','¡Atención!','facturas');
			return false;
		}
		
		msg ="";
		var msg = parent.checarParaCierreDia();
		if(msg!=""){
			mens.show("A","Para poder hacer el cierre principal debe terminar lo siguiente:<br>"+msg,"¡Atención!");
			return false;
		}
		
	mensaje ="";
		if(parseFloat(u.efectivo.value)<parseFloat(u.tefectivo.value)){
			mensaje = "Efectivo, ";
		}   
		if(parseFloat(u.tarjeta.value)<parseFloat(u.ttarjeta.value)){
			mensaje += "Tarjeta, ";
		}
		if(parseFloat(u.transferencia.value)<parseFloat(u.ttransferencia.value)){
			mensaje += "Transferencia, ";
		}
		if(parseFloat(u.cheque.value)<parseFloat(u.tcheque.value)){
			mensaje += "Cheque, ";
		}
		if(parseFloat(u.retiros.value)<parseFloat(u.tretiros.value)){
			mensaje += "Retiros, ";	
		}
		if(parseFloat(u.notascredito.value)<parseFloat(u.tnotascredito.value)){
			mensaje += "Notas Credito, ";	
		}
		
		
		
		if(mensaje!=""){
			if(cierre=="parcial"){
				mens.show('A','Existen diferencias en '+mensaje.substring(0,mensaje.length-2),'¡Atención!','efectivo');
			}else{
				mens.show('C','Existen diferencias en '+mensaje.substring(0,mensaje.length-2)+' ¿Desea continuar?','','','cierreCaja(\'definitivo\')');
			}
		}else{
			if(cierre=="parcial"){
				mens.show('C','Se realizara el cierre de caja parcial, ¿Desea continuar?','', '', 'cierreCaja(\'parcial\');');
			}else{
				mens.show('C','Se realizara el cierre de caja definitivo, ¿Desea continuar?','', '', 'cierreCaja(\'definitivo\');');
			}
		}
	}
	
	function cierreCaja(cierre){
		if(cierre=="parcial"){
			mens.show('I','Se ha realizado el cierre Parcial correctamente', 'Operación realizada correctamente');
		}else{
			consultaTexto("registro","cierrecaja_con.php?accion=2&efectivo="+u.efectivo.value
			+"&tarjeta="+u.tarjeta.value+"&transferencia="+u.transferencia.value+"&cheque="+u.cheque.value
			+"&retiros="+u.retiros.value+"&guiacredito="+u.gcredito.value+"&guiacancelada="+u.gcanceladas.value
			+"&facturacancelada="+u.facturas.value
			+"&difefectivo="+(parseFloat(u.tefectivo.value) - parseFloat(u.efectivo.value))
			+"&diftarjeta="+(parseFloat(u.ttarjeta.value) - parseFloat(u.tarjeta.value))
			+"&diftransferencia="+(parseFloat(u.ttransferencia.value) - parseFloat(u.transferencia.value))
			+"&difcheque="+(parseFloat(u.tcheque.value) - parseFloat(u.cheque.value))
			+"&cambiafecha="+u.cambiafecha.value+"&fecha="+u.fecha.value+"&iniciocaja="+u.iniciocaja.value
			+"&refectivo="+u.tefectivo.value+"&rtarjeta="+u.ttarjeta.value+"&rtransferencia="+u.ttransferencia.value
			+"&rcheque="+u.tcheque.value
			+"&difretiros=0&val="+Math.random());
		}
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			var r = datos.split(",");
			mens.show("I","Se ha realizado el cierre Definitivo correctamente","");
			u.parcial.value = "definitivo";
			u.codigo.value = r[1]; 
		}else{
			mens.show("A","Hubo un error al guadar "+datos,"¡Atención!");
		}
	}
	
	function cambioFecha(fecha){
		<?=$cpermiso->verificarPermiso(447,$_SESSION[IDUSUARIO]);?>
		var f1 = u.h_fecha.value.split("/");
		var f2 = fecha.split("/");
		
		if(f1[0].substr(0,1)=="0"){
			f1[0] = f1[0].substr(1,1);
		}
		if(f1[1].substr(0,1)=="0"){
			f1[1] = f1[1].substr(1,1);
		}
		
		if(f2[0].substr(0,1)=="0"){
			f2[0] = f2[0].substr(1,1);
		}
		if(f2[1].substr(0,1)=="0"){
			f2[1] = f2[1].substr(1,1);
		}
		
		f1 = new Date(f1[2],f1[1],f1[0]);
		f2 = new Date(f2[2],f2[1],f2[0]);
		
		if(f2 > f1){
			mens.show("A","La Fecha de cierre debe ser menor a la fecha actual","¡Atención!");
			return false;
		}
		
		if(f2 >= f1 && u.cambiafecha.value!=""){
			u.tefectivo.value = 0;
			u.ttarjeta.value = 0;
			u.ttransferencia.value = 0;
			u.tcheque.value = 0;
			u.tretiros.value = 0;			
			u.gcredito.value = 0; 
			u.gcanceladas.value = 0;
			u.facturas.value = 0;		
			u.iniciocaja.value = "";			
			u.parcial.value = "";
			u.cambiafecha.value = "";
		}
		
		consultaTexto("mostrarDatosFechaAnterior","cierrecaja_con.php?accion=3&cambiafecha=si&fecha="+u.fecha.value+"&ss="+Math.random());
	}
	
	function mostrarDatosFechaAnterior(datos){
		if(datos.indexOf("diafestivo")>-1){
			mens.show("A","La fecha seleccionada es un dia configurado como festivo","¡Atención!");
			u.fecha.value = '<?=date('d/m/Y'); ?>';
			return false;
		}
		
		if(datos.indexOf("domingo")>-1){
			mens.show("A","La fecha seleccionada es un dia no laboral","¡Atención!");
			u.fecha.value = '<?=date('d/m/Y'); ?>';
			return false;
		}
		
		if(datos.indexOf("noiniciocaja")>-1){
			mens.show("A","No ha iniciado caja con la fecha seleccionada","¡Atención!");
			u.fecha.value = '<?=date('d/m/Y'); ?>';
			return false;
		}
		
		if(datos.indexOf("yacerro")>-1){
			mens.show("A","La Fecha de cierre seleccionada ya fue registrada","¡Atención!");
			return false;
		}
		
		var obj = eval(datos);
		u.tefectivo.value = obj.principal.tefectivo;
		u.ttarjeta.value = obj.principal.ttarjeta;
		u.ttransferencia.value = obj.principal.ttransferencia;
		u.tcheque.value = obj.principal.tcheque;
		u.tretiros.value = obj.principal.tretiros;
		
		u.gcredito.value = obj.otros.tguiacredito; 
		u.gcanceladas.value = obj.otros.tguiacancelada;
		u.facturas.value = obj.otros.tfacturas;		
		u.iniciocaja.value = obj.otros.iniciocaja;
		
		u.parcial.value = "";
		u.cambiafecha.value = "SI";
	}
	
</script>

<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="500" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="275" class="FondoTabla Estilo4">CIERRE DE CAJA</td>
    </tr>
    <tr>
      <td><table width="100%" align="center" cellpadding="0" cellspacing="0" >
          <tr>
            <td colspan="4"><table width="200" align="right" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="66" align="right">Fecha:</td>
                  <td width="132" ><span class="Tablas">
                    <input name="fecha" type="text" class="Tablas" id="fecha" readonly="readonly" style="background:#FF9; text-align:center" value="<?=date('d/m/Y') ?>" size="15" onchange="cambioFecha(this.value)" />
                    <img src="../img/calendario.gif" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="if(<?=$cpermiso->checarPermiso(447,$_SESSION[IDUSUARIO]);?>==false){mens.show('A','Usted no tiene los permisos para ejecutar esta acción','¡Atención!');}else{displayCalendar(document.forms[0].fecha,'dd/mm/yyyy',this);}" /></span></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td width="106">Efectivo:</td>
            <td width="139"><input name="efectivo" type="text" class="Tablas" id="efectivo" style="text-align:right" onkeypress="return tiposMoneda(event,this.value)" onkeydown="return tabular(event,this);" value="0" maxlength="14" onfocus="if(this.value==0){this.value=''}else{this.select()}" onblur="if(this.value==''){this.value=0}"/></td>
            <td width="159">Tarjeta: </td>
            <td width="90"><input name="tarjeta" type="text" class="Tablas" id="tarjeta" style="text-align:right" onkeypress="return tiposMoneda(event,this.value)" onkeydown="return tabular(event,this);" value="0" maxlength="14" onfocus="if(this.value==0){this.value=''}else{this.select()}" onblur="if(this.value==''){this.value=0}"/></td>
          </tr>
          <tr>
            <td>Transferencia:</td>
            <td><input name="transferencia" type="text" class="Tablas" id="transferencia" style="text-align:right" onkeypress="return tiposMoneda(event,this.value)" onkeydown="return tabular(event,this);" value="0" maxlength="14" onfocus="if(this.value==0){this.value=''}else{this.select()}" onblur="if(this.value==''){this.value=0}"/></td>
            <td>Cheque: </td>
            <td><input name="cheque" type="text" class="Tablas" id="cheque" style="text-align:right" onkeypress="return tiposMoneda(event,this.value)" onkeydown="return tabular(event,this);" value="0" maxlength="14" onfocus="if(this.value==0){this.value=''}else{this.select()}" onblur="if(this.value==''){this.value=0}"/></td>
          </tr>
          <tr>
            <td>Retiros:</td>
            <td><input name="retiros" type="text" class="Tablas" id="retiros" style="text-align:right" onkeypress="return tiposMoneda(event,this.value)" onkeydown="return tabular(event,this);" value="0" maxlength="14" onfocus="if(this.value==0){this.value=''}else{this.select()}" onblur="if(this.value==''){this.value=0}"/></td>
            <td>#Gu&iacute;as Cr&eacute;dito:</td>
            <td><input name="gcredito" type="text" class="Tablas" id="gcredito" style="text-align:right" onkeypress="return tiposMoneda(event,this.value)" onkeydown="return tabular(event,this);" value="0" maxlength="5" readonly="" /></td>
          </tr>
          <tr>
            <td># Gu&iacute;as Canceladas:</td>
            <td><input name="gcanceladas" type="text" class="Tablas" id="gcanceladas" style="text-align:right" onkeypress="return tiposMoneda(event,this.value)" onkeydown="return tabular(event,this);" value="0" maxlength="5" readonly="" /></td>
            <td>Facturas Canceladas:</td>
            <td><input name="facturas" type="text" class="Tablas" id="facturas" style="text-align:right" onkeypress="return tiposMoneda(event,this.value)" onkeydown="return tabular(event,this);" value="0" maxlength="5" readonly="" /></td>
          </tr>
          <tr>
            <td>Notas Cr&eacute;dito</td>
            <td><input name="notascredito" type="text" class="Tablas" id="notascredito" style="text-align:right" onkeypress="return tiposMoneda(event,this.value)" onkeydown="return tabular(event,this);" value="0" maxlength="5" onfocus="if(this.value==0){this.value=''}else{this.select()}" onblur="if(this.value==''){this.value=0}" /></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4"><input name="tguiacancelada" type="xxxxx" id="tguiacancelada" />
                <input name="tfactura" type="xxxxx" id="tfactura" readonly=""/>
            <input name="h_fecha" type="xxxxx" id="h_fecha" value="<?=date('d/m/Y') ?>" readonly=""/></td>
          </tr>
          <tr>
            <td colspan="4"><input name="accion" type="xxxxx" id="accion" />
                <input name="codigo" type="xxxxx" id="codigo"  />
                <input name="parcial" type="xxxxx" id="parcial"  />
                <input name="iniciocaja" type="xxxxx" id="iniciocaja"  />
                <input name="tefectivo" type="xxxxx" id="tefectivo"  />
                <input name="ttarjeta" type="xxxxx" id="ttarjeta"  />
                <input name="ttransferencia" type="xxxxx" id="ttransferencia"  />
                <input name="tcheque" type="xxxxx" id="tcheque"  />
                <input name="tretiros" type="xxxxx" id="tretiros"  />
                <input name="tnotascredito" type="xxxxx" id="tretiros"  />
                <input name="cambiafecha" type="xxxxx" id="cambiafecha" /></td>
          </tr>
          <tr>
            <td colspan="4">
                <table width="100" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><div class="ebtn_parcial" onclick="validar('parcial');"></div></td>
                    <td>&nbsp;&nbsp;</td>
                    <td><div class="ebtn_Cierre_Definitivo" onclick="validar('definitivo');"></div></td>
                  </tr>
                </table>            </td>
          </tr>
          <tr>
            <td colspan="4"></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
