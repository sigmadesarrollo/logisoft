<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT IFNULL(SUM(f.efectivo),0) AS efectivo, IFNULL(SUM(f.tarjeta),0) AS tarjeta,
	IFNULL(SUM(f.transferencia),0) AS transferencia, IFNULL(SUM(f.cheque),0) AS cheque,
	IFNULL(SUM(f.notacredito),0) AS notacredito FROM formapago f
	INNER JOIN solicitudguiasempresariales s ON f.guia = s.factura
	WHERE f.procedencia = 'F' AND f.fecha=CURRENT_DATE AND f.sucursal=".$_SESSION[IDSUCURSAL]." 
	AND (f.fechacancelacion IS NULL OR f.fechacancelacion='0000-00-00')";
	$r = mysql_query($s,$l) or die($s); $fact = mysql_fetch_object($r);
	
	$factentregado = $fact->efectivo + $fact->tarjeta + $fact->transferencia + $fact->cheque + $fact->notacredito;
	
	$e = mysql_query("SELECT IFNULL(SUM(f.efectivo),0) AS efectivo, IFNULL(SUM(f.tarjeta),0) AS tarjeta,
	IFNULL(SUM(f.transferencia),0) AS transferencia, IFNULL(SUM(f.cheque),0) AS cheque,
	IFNULL(SUM(f.notacredito),0) AS notacredito FROM formapago f
	INNER JOIN guiasempresariales g ON f.guia = g.id
	WHERE f.procedencia ='G' AND f.tipo='E' AND f.fecha=CURRENT_DATE AND f.sucursal=".$_SESSION[IDSUCURSAL]." 
	AND (f.fechacancelacion IS NULL OR f.fechacancelacion='0000-00-00') AND g.tipoguia = 'CONSIGNACION'",$l);
	$emp = mysql_fetch_object($e);
	
	$totalemp = $emp->efectivo + $emp->tarjeta + $emp->transferencia + $emp->cheque;
	
	$s = "SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta,
	IFNULL(SUM(transferencia),0) AS transferencia, IFNULL(SUM(cheque),0) AS cheque,
	IFNULL(SUM(notacredito),0) AS notacredito FROM formapago";
	
	$criterioventanilla = " WHERE procedencia ='G' AND tipo='V' AND fecha=CURRENT_DATE AND sucursal=".$_SESSION[IDSUCURSAL]." 
	AND (fechacancelacion IS NULL OR fechacancelacion='0000-00-00')"; 
	
	$r = mysql_query($s.$criterioventanilla,$l) or die($s);
	$v = mysql_fetch_object($r);
	
	$v->entregarventanilla = ($v->efectivo + $emp->efectivo) + ($v->tarjeta + $emp->tarjeta) + ($v->transferencia + $emp->transferencia) + ($v->cheque + $emp->cheque) + $v->notacredito;
			
	$v->efectivo = $v->efectivo + $emp->efectivo;
	$v->tarjeta = $v->tarjeta + $emp->tarjeta;
	$v->transferencia = $v->transferencia + $emp->transferencia;
	$v->cheque = $v->cheque + $emp->cheque;
	
	
	$criterioead = " WHERE procedencia='M' AND fecha=CURRENT_DATE AND sucursal=".$_SESSION[IDSUCURSAL]; 
	$r = mysql_query($s.$criterioead,$l) or die($s);
	$f = mysql_fetch_object($r);
	$f->entregaread = $f->efectivo + $f->tarjeta + $f->transferencia + $f->cheque + $f->notacredito;	
	
	$criterioead = " WHERE procedencia='C' AND fecha=CURRENT_DATE AND sucursal=".$_SESSION[IDSUCURSAL]; 
	$r = mysql_query($s.$criterioead,$l) or die($s);
	$c = mysql_fetch_object($r);	
	$c->entregarcobranza = $c->efectivo + $c->tarjeta + $c->transferencia + $c->cheque + $c->notacredito;		
	
	$criterioocurre = " WHERE procedencia='O' AND fecha=CURRENT_DATE AND sucursal=".$_SESSION[IDSUCURSAL]; 
	$d = mysql_query($s.$criterioocurre,$l) or die($s.$criterioocurre);
	$co= mysql_fetch_object($d);
	
	$co->entregarocurre = $co->efectivo + $co->tarjeta + $co->transferencia + $co->cheque + $co->notacredito;
	
	$criterioocurre = " WHERE procedencia='A' AND fecha=CURRENT_DATE AND sucursal=".$_SESSION[IDSUCURSAL]; 
	$d = mysql_query($s.$criterioocurre,$l) or die($s.$criterioocurre);
	$abo= mysql_fetch_object($d);
	
	$abo->entregarabono = $abo->efectivo + $abo->tarjeta + $abo->transferencia + $abo->cheque + $abo->notacredito;
	
	$s = "SELECT * FROM cierreprincipal WHERE estado ='CERRADA' AND fechacierre=CURDATE() AND sucursal = ".$_SESSION[IDSUCURSAL];
	$t = mysql_query($s,$l) or die($s);
	$tt = mysql_fetch_object($t);
	
	$s = "SELECT cobrador, diferencia FROM liquidacioncobranza 
	WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechaliquidacion = CURDATE() AND diferencia > 0";
	$rr= mysql_query($s,$l) or die($s);
	$empleadoscobranza = "";
	if(mysql_num_rows($rr)>0){
		while($fr = mysql_fetch_object($rr)){
			$empleadoscobranza .= $fr->cobrador.",".$fr->diferencia.":";
		}
		
		$empleadoscobranza = substr($empleadoscobranza,0,strlen($empleadoscobranza)-1);
	}
	
	$s = "SELECT r.conductor1, l.diferencia FROM repartomercanciaead r
	INNER JOIN liquidacionead l ON r.folio = l.idreparto AND r.sucursal = l.sucursal
	WHERE l.sucursal = ".$_SESSION[IDSUCURSAL]." AND r.fecha = CURDATE() AND l.diferencia > 0";
	$rr= mysql_query($s,$l) or die($s);
	$empleadosead = "";
	if(mysql_num_rows($rr)>0){
		while($fr = mysql_fetch_object($rr)){
			$empleadosead .= $fr->conductor1.",".$fr->diferencia.":";			
		}
		
		$empleadosead = substr($empleadosead,0,strlen($empleadosead)-1);
	}
	
	$s = "SELECT usuariocaja, (difefectivo + diftarjeta + difcheque + diftransferencia + difretiros) AS diferencia 
	FROM cierrecaja WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechacierre = CURDATE()";
	$rr= mysql_query($s,$l) or die($s);
	$empleadosventanilla = "";
	if(mysql_num_rows($rr)>0){
		while($fr = mysql_fetch_object($rr)){
			if($fr->diferencia > 0){
				$empleadosventanilla .= $fr->usuariocaja.",".$fr->diferencia.":";
			}
		}		
		$empleadosventanilla = substr($empleadosventanilla,0,strlen($empleadosventanilla)-1);				
	}	
	
	$s = "SELECT(SELECT COUNT(*) FROM guiasventanilla
	WHERE fecha=CURDATE() AND estado='CANCELADO' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."') AS cancelada,
	(SELECT COUNT(*) FROM guiasventanilla
	WHERE fecha=CURDATE() AND condicionpago='1'	AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."') AS credito,
	(SELECT COUNT(*) FROM facturacion
	WHERE fecha=CURDATE() AND facturaestado='CANCELADO' AND idsucursal='".$_SESSION[IDSUCURSAL]."') AS fcanceladas";
	$r = mysql_query($s,$l) or die($s);
	$o = mysql_fetch_object($r);
	
	$s = "SELECT ifnull(efectivo,0) AS efectivo, ifnull(tarjeta,0) AS tarjeta, ifnull(transferencia,0) AS transferencia, 
	ifnull(cheque,0) AS cheque FROM cierrecaja
	WHERE fechacierre = CURDATE() AND tipocierre = 'definitivo' AND sucursal = ".$_SESSION[IDSUCURSAL];
	$r = mysql_query($s,$l) or die($s); 
	
	$efectivo = 0; $tarjeta= 0; $transferencia= 0; $cheque= 0; $entregado = 0;
	if(mysql_num_rows($r)>0){
		while($te = mysql_fetch_object($r)){				
			$efectivo 		= ((empty($te->efectivo))?0:$te->efectivo) + $efectivo;
			$tarjeta 		= ((empty($te->tarjeta))?0:$te->tarjeta) + $tarjeta;
			$transferencia 	= ((empty($te->transferencia))?0:$te->transferencia) + $transferencia;
			$cheque 		= ((empty($te->efectivo))?0:$te->cheque) + $cheque;
		}
		
		$te->efectivo = $efectivo; $te->tarjeta = $tarjeta; $te->transferencia = $transferencia; $te->cheque = $cheque;		
		$te->entregado = $te->efectivo + $te->tarjeta + $te->transferencia + $te->cheque;
	}else{
		$te->efectivo = 0; $te->tarjeta= 0; $te->transferencia= 0; $te->cheque= 0; $te->entregado = 0;
	}
	
	$s = "SELECT * FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = CURDATE()";
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){$existeiniciodia="SI";}else{$existeiniciodia="NO";}
	
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funcionesDrag.js"></script>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script >
	var u = document.all;
	var mens = new ClaseMensajes();
	var v_empleados = "";
	mens.iniciar('../javascript',false);
	
	window.onload = function(){
		ponerTipoMoneda();
		obtenerGeneral();
	}
	function ponerTipoMoneda(){
		u.h_efectivoead.value		= "$ "+numcredvar(u.h_efectivoead.value);
		u.h_transferenciaead.value	= "$ "+numcredvar(u.h_transferenciaead.value);
		u.h_tarjetaead.value		= "$ "+numcredvar(u.h_tarjetaead.value);
		u.h_chequeead.value			= "$ "+numcredvar(u.h_chequeead.value);
		u.h_efectivocob.value		= "$ "+numcredvar(u.h_efectivocob.value);
		u.h_transferenciacob.value	= "$ "+numcredvar(u.h_transferenciacob.value);
		u.h_notacob.value			= "$ "+numcredvar(u.h_notacob.value);
		u.h_tarjetacob.value		= "$ "+numcredvar(u.h_tarjetacob.value);
		u.h_chequecob.value			= "$ "+numcredvar(u.h_chequecob.value);
		u.h_efectivocaja.value		= "$ "+numcredvar(u.h_efectivocaja.value);
		u.h_transferenciacaja.value	= "$ "+numcredvar(u.h_transferenciacaja.value);
		u.h_notacaja.value			= "$ "+numcredvar(u.h_notacaja.value);
		u.h_tarjetacaja.value		= "$ "+numcredvar(u.h_tarjetacaja.value);
		u.h_chequecaja.value		= "$ "+numcredvar(u.h_chequecaja.value);
		u.h_efectivoocu.value		= "$ "+numcredvar(u.h_efectivoocu.value);
		u.h_transferenciaocu.value	= "$ "+numcredvar(u.h_transferenciaocu.value);
		u.h_notaocu.value			= "$ "+numcredvar(u.h_notaocu.value);
		u.h_tarjetaocu.value		= "$ "+numcredvar(u.h_tarjetaocu.value);
		u.h_chequeocu.value			= "$ "+numcredvar(u.h_chequeocu.value);		
		u.h_efectivoabono.value		= "$ "+numcredvar(u.h_efectivoabono.value);
		u.h_transferenciaabono.value= "$ "+numcredvar(u.h_transferenciaabono.value);
		u.h_notaabono.value			= "$ "+numcredvar(u.h_notaabono.value);
		u.h_tarjetaabono.value		= "$ "+numcredvar(u.h_tarjetaabono.value);
		u.h_chequeabono.value		= "$ "+numcredvar(u.h_chequeabono.value);
		u.entregaread.value			= "$ "+numcredvar(u.entregaread.value);
		u.entregarocurre.value		= "$ "+numcredvar(u.entregarocurre.value);
		u.entregarcobranza.value	= "$ "+numcredvar(u.entregarcobranza.value);
		u.entregarventanilla.value	= "$ "+numcredvar(u.entregarventanilla.value);
		u.entregarabono.value		= "$ "+numcredvar(u.entregarabono.value);
		u.h_notaead.value			= "$ "+numcredvar(u.h_notaead.value);
		
		u.efectivoentregado.value		= "$ "+numcredvar(u.efectivoentregado.value);
		u.tarjetaentregado.value		= "$ "+numcredvar(u.tarjetaentregado.value);
		u.transferenciaentregado.value	= "$ "+numcredvar(u.transferenciaentregado.value);
		u.chequeentregado.value			= "$ "+numcredvar(u.chequeentregado.value);
		u.totalentregado.value			= "$ "+numcredvar(u.totalentregado.value);
		
		u.factefectivo.value			= "$ "+numcredvar(u.factefectivo.value);
		u.facttransferencia.value		= "$ "+numcredvar(u.facttransferencia.value);
		u.factnotacredito.value			= "$ "+numcredvar(u.factnotacredito.value);
		u.facttarjeta.value				= "$ "+numcredvar(u.facttarjeta.value);
		u.factcheque.value				= "$ "+numcredvar(u.factcheque.value);
		u.factentregado.value			= "$ "+numcredvar(u.factentregado.value);
		
		u.totalgral.value			= "$ "+numcredvar(u.totalgral.value);
	}
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","cierrePrincipal_con.php?accion=3&s="+Math.random());
	}
	function mostrarGeneral(datos){
		var row = datos.split("%");
		u.folio.value = row[0];
		u.fecha.value = row[1];
		u.cierre.value= row[2];		
		if(row[3].replace("&#32;","")!=""){
			v_empleados = row[3];
			mens.show("A","El o Los siguientes empleados no han hecho el cierre de caja definitivo: "+row[3],"¡Atención!");
		}
	}
	
	function obtenerLiquidacionDepositos(cierre){
		consultaTexto("validar","cierrePrincipal_con.php?accion=7&cierre="+cierre);
	}
	
	function validar(datos){
		var obj = eval(datos);
		var cierre = obj.cierre;		
		if(obj.cierre=="cierre"){
			<?=$cpermiso->verificarPermiso(449,$_SESSION[IDUSUARIO]);?>			
		}
		<?=$cpermiso->verificarPermiso(448,$_SESSION[IDUSUARIO]);?>
		if(u.existeiniciodia.value=="NO"){
			mens.show("A","No se puede cerrar caja principal, por que no se ha iniciado dia","¡Atención!");
			return false;
		}
		
		if(v_empleados!=""){
			mens.show("A","No se puede cerrar caja principal, por que el o los siguientes empleados no han hecho el cierre de caja definitivo: "+v_empleados,"¡Atención!");
			return false;
		}
		
		/*if(obj.liquidaciones==1){
			mens.show("A","Para poder hacer el cierre principal debe terminar las liquidaciones ead pendientes","¡Atención!");
			return false;
		}*/
		
		/*if(obj.depositos==1){
			mens.show("A","Para poder hacer el cierre principal debe hacer los depositos del dia","¡Atención!");
			return false;
		}*/
		
		mensaje ="";
		var msg = parent.checarParaCierreDia();
		if(msg!=""){
			mens.show("A","Para poder hacer el cierre principal debe terminar lo siguiente:<br>"+msg,"¡Atención!");
			return false;
		}

		/*if(u.cierre.value=="NO"){
			mens.show("A","No se puede cerrar caja principal, por que no se ha realizado el cierre de caja definitivo","¡Atención!");
			return false;
		}*/
		if(u.estado.value=="CERRADA"){
			mens.show("A","La caja principal del dia actual ya fue cerrada","¡Atención!");
			return false;
		}
		
		var ead = u.empleadoseadCierre.value.split(":");
		var eadd = "";
		var empleado = "";
		empleados = "";		
		for(var i=0; i<ead.length; i++){
			eadd = ead[i].split(",");
			for(var k=0; k<eadd.length/2; k++){
				if(eadd[1]>0){
					empleado += eadd[0] + ",";
					empleados += eadd[0] + "," + eadd[1] + ":";
				}
			}
		}
		
		var cob = u.empleadoscobranzaCierre.value.split(":");
		var cobb= "";
		
		for(var i=0; i<cob.length; i++){
			cobb = cob[i].split(",");
			for(var k=0; k<cobb.length/2; k++){
				if(cobb[1]>0){
					empleado += cobb[0] + ",";
					empleados += cobb[0] + "," + cobb[1] + ":";
				}
			}
		}
		
		var ven	= u.empleadosventanillaCierre.value.split(":");
		var venn= "";
		
		for(var i=0; i<ven.length; i++){
			venn = ven[i].split(",");
			for(var k=0; k<venn.length/2; k++){
				if(venn[1]>0){
					empleado += venn[0] + ",";
					empleados += venn[0] + "," + venn[1] + ":";
				}
			}
		}
		
		if(cierre=="1"){
			mens.show('C','¿Desea guardar la información capturada?','','', 'cierreCaja(\'1\');');
		}else{
			if(empleado!=""){
				empleado = empleado.substring(0,empleado.length -1);
				mens.show('C','Los siguientes empleados cuentan con faltantes, se realizara un vale por cada uno:<br>'+empleado
				+', ¿Desea continuar?','','','cierreCajaVale(\'cierre\');');
			}else{
				mens.show('C','Se realizara el cierre de caja principal, ¿Desea continuar?','','','cierreCaja(\'cierre\');');
			}
		}
	}
	
	function cierreCajaVale(tipo){
		empleados = empleados.substring(0,empleados.length -1);
		if(empleados.indexOf(":")>-1){
			var nolleva = 1;
		}else{
			var nolleva = 0;
		}
		
		if(document.URL.indexOf("web/")>-1){		
			window.open("http://www.pmmintranet.net/web/fpdf/reportes/valeDinero.php?gerente=<?=$_SESSION[IDUSUARIO] ?>&empleados="+empleados+"&fecha="+u.fecha.value+"&nolleva="+nolleva,null, "toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0");
			
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/valeDinero.php?gerente=<?=$_SESSION[IDUSUARIO] ?>&empleados="+empleados+"&fecha="+u.fecha.value+"&nolleva="+nolleva,null, "toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0");
			
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/valeDinero.php?gerente=<?=$_SESSION[IDUSUARIO] ?>&empleados="+empleados+"&fecha="+u.fecha.value+"&nolleva="+nolleva,null, "toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0");
		}
		if(u.dia_anterior.value == ""){
			consultaTexto("registro","cierrePrincipal_con.php?accion=1&tipo=cierre&folio="+((u.accion.value=="")? 'guardar' : 'modificar')
			+"&cierre="+u.folio.value+"&s="+Math.random());
		}else{
			consultaTexto("registro","cierrePrincipal_con.php?accion=6&tipo=cierre&folio="+((u.accion.value=="")? 'guardar' : 'modificar')
			+"&cierre="+u.folio.value+"&fecha="+u.fecha.value+"&s="+Math.random());
		}
	}
	
	function cierreCaja(tipo){
		if(u.dia_anterior.value == ""){
			consultaTexto("registro","cierrePrincipal_con.php?accion=1&tipo="+((tipo=='1')? 'guardar' : 'cierre')
			+"&folio="+((u.accion.value=="")? 'guardar' : 'modificar')+"&cierre="+u.folio.value+"&s="+Math.random());
		}else{
			consultaTexto("registro","cierrePrincipal_con.php?accion=6&tipo="+((tipo=='1')? 'guardar' : 'cierre')
			+"&folio="+((u.accion.value=="")? 'guardar' : 'modificar')+"&cierre="+u.folio.value+"&fecha="+u.fecha.value
			+"&s="+Math.random());
		}
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){		
			var row = datos.split(",");
			if(row[1]=="guardar"){
				u.estado.value = "GUARDADO";
				if(row[2]=="guardar"){
					u.folio.value = row[3];
					u.accion.value = "modificar";
					mens.show("I","Los datos han sido guardados satisfactoriamente","");
				}else{
					mens.show("I","Los cambios han sido guardados satisfactoriamente","");
				}
			}else if(row[1]=="cierre"){
				u.estado.value = "CERRADA";
				if(row[2]=="guardar"){
					u.folio.value = row[3];
					mens.show("I","El cierre principal se ha realizado satisfactoriamente","");
				}else{
					u.folio.value = row[3];
					mens.show("I","El cierre principal se ha realizado satisfactoriamente","");
				}
			}
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atencion!");
		}
	}
	function solonumeros(evnt,obj,caja){
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode!=46) {
				return false;
			}
			if(evnt.keyCode == 13 && obj!=""){
				document.getElementById(caja).value = numcredvar(document.getElementById(caja).value);
				document.getElementById(obj).focus();
			}
			return true;
		}
	}
	function numcredvar(cad){

		var flag = false; 
		if(cad.indexOf('.') == cad.length - 1) flag = true; 
		var num = cad.split(',').join(''); 
		cad = Number(num).toLocaleString();
		if(cad!="0.00"){
			if(flag) cad += '.'; 
		}
		return cad;
	}
	function obtenerCierre(folio){
		u.folio.value = folio;
		consultaTexto("mostarCierre","cierrePrincipal_con.php?accion=2&cierre="+folio+"&s="+Math.random());
	}
	function obtenerCierre2(folio){
		folio=u.folio.value;
		consultaTexto("mostarCierre","cierrePrincipal_con.php?accion=2&cierre="+folio+"&s="+Math.random());
	}
	function mostarCierre(datos){
			var obj = eval(datos);
			u.fecha.value				= obj.principal.fechacierre;
			
			u.h_efectivocaja.value		= obj.ventanilla.efectivo;
			u.h_efectivocaja.value		= "$ "+numcredvar(u.h_efectivocaja.value);
			u.h_transferenciacaja.value	= obj.ventanilla.transferencia;
			u.h_transferenciacaja.value	= "$ "+numcredvar(u.h_transferenciacaja.value);
			u.h_notacaja.value			= ((obj.ventanilla.notacredito!=0)?obj.ventanilla.notacredito:0);
			u.h_notacaja.value			= "$ "+numcredvar(u.h_notacaja.value);
			u.h_tarjetacaja.value		= obj.ventanilla.tarjeta;
			u.h_tarjetacaja.value		= "$ "+numcredvar(u.h_tarjetacaja.value);
			u.h_chequecaja.value		= obj.ventanilla.cheque;
			u.h_chequecaja.value		= "$ "+numcredvar(u.h_chequecaja.value);			
			u.entregarventanilla.value	= obj.ventanilla.entregarventanilla;
			u.entregarventanilla.value	= "$ "+numcredvar(u.entregarventanilla.value);
			
			u.h_efectivoead.value		= obj.liquidacion.efectivo;
			u.h_efectivoead.value		= "$ "+numcredvar(u.h_efectivoead.value);			
			u.h_transferenciaead.value	= obj.liquidacion.transferencia;
			u.h_transferenciaead.value	= "$ "+numcredvar(u.h_transferenciaead.value);
			u.h_tarjetaead.value		= obj.liquidacion.tarjeta;
			u.h_tarjetaead.value		= "$ "+numcredvar(u.h_tarjetaead.value);
			u.h_chequeead.value			= obj.liquidacion.cheque;
			u.h_chequeead.value			= "$ "+numcredvar(u.h_chequeead.value);
			u.h_notaead.value			= obj.liquidacion.notacredito;
			u.h_notaead.value			= "$ "+numcredvar(u.h_notaead.value);
			
			u.h_efectivocob.value		= obj.cobranza.efectivo;
			u.h_efectivocob.value		= "$ "+numcredvar(u.h_efectivocob.value);
			u.h_transferenciacob.value	= obj.cobranza.transferencia;
			u.h_transferenciacob.value	= "$ "+numcredvar(u.h_transferenciacob.value);
			u.h_notacob.value			= obj.cobranza.notacredito;
			u.h_notacob.value			= "$ "+numcredvar(u.h_notacob.value);
			u.h_tarjetacob.value		= obj.cobranza.tarjeta;
			u.h_tarjetacob.value		= "$ "+numcredvar(u.h_tarjetacob.value);
			u.h_chequecob.value			= obj.cobranza.cheque;
			u.h_chequecob.value			= "$ "+numcredvar(u.h_chequecob.value);		
			
			u.h_efectivoocu.value		= obj.ocurre.efectivo;
			u.h_transferenciaocu.value	= obj.ocurre.transferencia;
			u.h_notaocu.value			= obj.ocurre.notacredito;
			u.h_tarjetaocu.value		= obj.ocurre.tarjeta;
			u.h_chequeocu.value			= obj.ocurre.cheque;
			
			u.h_efectivoocu.value		= "$ "+numcredvar(u.h_efectivoocu.value);
			u.h_transferenciaocu.value	= "$ "+numcredvar(u.h_transferenciaocu.value);
			u.h_notaocu.value			= "$ "+numcredvar(u.h_notaocu.value);
			u.h_tarjetaocu.value		= "$ "+numcredvar(u.h_tarjetaocu.value);
			u.h_chequeocu.value			= "$ "+numcredvar(u.h_chequeocu.value);
			
			u.h_efectivoabono.value		= obj.abono.efectivo;
			u.h_transferenciaabono.value= obj.abono.transferencia;
			u.h_notaabono.value			= obj.abono.notacredito;
			u.h_tarjetaabono.value		= obj.abono.tarjeta;
			u.h_chequeabono.value		= obj.abono.cheque;
			
			u.h_efectivoabono.value		= "$ "+numcredvar(u.h_efectivoabono.value);
			u.h_transferenciaabono.value= "$ "+numcredvar(u.h_transferenciaabono.value);
			u.h_notaabono.value			= "$ "+numcredvar(u.h_notaabono.value);
			u.h_tarjetaabono.value		= "$ "+numcredvar(u.h_tarjetaabono.value);
			u.h_chequeabono.value		= "$ "+numcredvar(u.h_chequeabono.value);
			
			u.entregaread.value			= obj.liquidacion.entregaread;
			u.entregarocurre.value		= obj.ocurre.entregarocurre;
			u.entregarcobranza.value	= obj.cobranza.entregarcobranza;			
			u.entregarabono.value		= obj.abono.entregarabono;
			
			u.entregaread.value			= "$ "+numcredvar(u.entregaread.value);
			u.entregarocurre.value		= "$ "+numcredvar(u.entregarocurre.value);
			u.entregarcobranza.value	= "$ "+numcredvar(u.entregarcobranza.value);			
			u.entregarabono.value		= "$ "+numcredvar(u.entregarabono.value);
			
			u.gcredito.value			= obj.otros.credito;
			u.fcancelada.value			= obj.otros.fcanceladas;			
			u.gcancelada.value			= obj.otros.cancelada;
			
			u.efectivoentregado.value		= obj.entregado.efectivo;
			u.tarjetaentregado.value		= obj.entregado.tarjeta;
			u.transferenciaentregado.value	= obj.entregado.transferencia;
			u.chequeentregado.value			= obj.entregado.cheque;
			u.totalentregado.value			= obj.entregado.entregado;
			
			u.efectivoentregado.value		= "$ "+numcredvar(u.efectivoentregado.value);
			u.tarjetaentregado.value		= "$ "+numcredvar(u.tarjetaentregado.value);
			u.transferenciaentregado.value	= "$ "+numcredvar(u.transferenciaentregado.value);
			u.chequeentregado.value			= "$ "+numcredvar(u.chequeentregado.value);
			u.totalentregado.value			= "$ "+numcredvar(u.totalentregado.value);
			
			u.factefectivo.value			= obj.facturado.efectivo;
			u.facttransferencia.value		= obj.facturado.transferencia;
			u.factnotacredito.value			= obj.facturado.notacredito;
			u.facttarjeta.value				= obj.facturado.tarjeta;
			u.factcheque.value				= obj.facturado.cheque;
			u.factentregado.value			= obj.facturado.factentregado;
			
			u.factefectivo.value			= "$ "+numcredvar(u.factefectivo.value);
			u.facttransferencia.value		= "$ "+numcredvar(u.facttransferencia.value);
			u.factnotacredito.value			= "$ "+numcredvar(u.factnotacredito.value);
			u.facttarjeta.value				= "$ "+numcredvar(u.facttarjeta.value);
			u.factcheque.value				= "$ "+numcredvar(u.factcheque.value);
			u.factentregado.value			= "$ "+numcredvar(u.factentregado.value);
			
			u.totalgral.value=(obj.liquidacion.entregaread+obj.ocurre.entregarocurre+obj.cobranza.entregarcobranza+obj.abono.entregarabono
				+obj.ventanilla.entregarventanilla+obj.facturado.factentregado);
			u.totalgral.value="$ "+numcredvar(u.totalgral.value);
	}
	function limpiar(){		
		u.accion.value	= "";
		u.dia_anterior.value = "";
		u.nota.innerHTML = "&nbsp;"
		obtenerGeneral();
	}
	
	function esNumeric(valor){
		valor = valor.replace("$ ","").replace(/,/g,"").replace(".","");
		var log	=	valor.length;
		var sw	=	"S"; 
		for (x=0; x<log; x++){
			v1	=	valor.substr(x,1);
			v2	= 	parseFloat(v1);
			//Compruebo si es un valor numérico
			if (isNaN(v2)){
				sw	= "N";
			} 
		} 
		if (sw=="S"){			
			return true;
		}else{			
			return false;
		}
	}	
	
	function obtenerEmpleado(id){
		u.empleado.value = id;
		consultaTexto("mostrarEmpleado","cierrePrincipal_con.php?accion=4&empleado="+id
		+"&cambiafecha="+u.dia_anterior.value+"&fecha="+u.fecha.value);
	}	
	
	function mostrarEmpleado(datos){
		f_limpiar2();
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.nombre.value = obj.empleado.empleado;			
			
			u.h_efectivocaja.value		= obj.ventanilla.efectivo;
			u.h_efectivocaja.value		= "$ "+numcredvar(u.h_efectivocaja.value);
			u.h_transferenciacaja.value	= obj.ventanilla.transferencia;
			u.h_transferenciacaja.value	= "$ "+numcredvar(u.h_transferenciacaja.value);
			u.h_notacaja.value			= ((obj.ventanilla.notacredito!=0)?obj.ventanilla.notacredito:0);
			u.h_notacaja.value			= "$ "+numcredvar(u.h_notacaja.value);
			u.h_tarjetacaja.value		= obj.ventanilla.tarjeta;
			u.h_tarjetacaja.value		= "$ "+numcredvar(u.h_tarjetacaja.value);
			u.h_chequecaja.value		= obj.ventanilla.cheque;
			u.h_chequecaja.value		= "$ "+numcredvar(u.h_chequecaja.value);			
			u.entregarventanilla.value	= obj.ventanilla.entregarventanilla;
			u.entregarventanilla.value	= "$ "+numcredvar(u.entregarventanilla.value);
			
			u.h_efectivoead.value		= obj.liquidacion.efectivo;
			u.h_efectivoead.value		= "$ "+numcredvar(u.h_efectivoead.value);			
			u.h_transferenciaead.value	= obj.liquidacion.transferencia;
			u.h_transferenciaead.value	= "$ "+numcredvar(u.h_transferenciaead.value);
			u.h_tarjetaead.value		= obj.liquidacion.tarjeta;
			u.h_tarjetaead.value		= "$ "+numcredvar(u.h_tarjetaead.value);
			u.h_chequeead.value			= obj.liquidacion.cheque;
			u.h_chequeead.value			= "$ "+numcredvar(u.h_chequeead.value);
			u.h_notaead.value			= obj.liquidacion.notacredito;
			u.h_notaead.value			= "$ "+numcredvar(u.h_notaead.value);
			
			u.h_efectivocob.value		= obj.cobranza.efectivo;
			u.h_efectivocob.value		= "$ "+numcredvar(u.h_efectivocob.value);
			u.h_transferenciacob.value	= obj.cobranza.transferencia;
			u.h_transferenciacob.value	= "$ "+numcredvar(u.h_transferenciacob.value);
			u.h_notacob.value			= obj.cobranza.notacredito;
			u.h_notacob.value			= "$ "+numcredvar(u.h_notacob.value);
			u.h_tarjetacob.value		= obj.cobranza.tarjeta;
			u.h_tarjetacob.value		= "$ "+numcredvar(u.h_tarjetacob.value);
			u.h_chequecob.value			= obj.cobranza.cheque;
			u.h_chequecob.value			= "$ "+numcredvar(u.h_chequecob.value);		
			
			u.h_efectivoocu.value		= obj.ocurre.efectivo;
			u.h_transferenciaocu.value	= obj.ocurre.transferencia;
			u.h_notaocu.value			= obj.ocurre.notacredito;
			u.h_tarjetaocu.value		= obj.ocurre.tarjeta;
			u.h_chequeocu.value			= obj.ocurre.cheque;
			
			u.h_efectivoocu.value		= "$ "+numcredvar(u.h_efectivoocu.value);
			u.h_transferenciaocu.value	= "$ "+numcredvar(u.h_transferenciaocu.value);
			u.h_notaocu.value			= "$ "+numcredvar(u.h_notaocu.value);
			u.h_tarjetaocu.value		= "$ "+numcredvar(u.h_tarjetaocu.value);
			u.h_chequeocu.value			= "$ "+numcredvar(u.h_chequeocu.value);
			
			u.h_efectivoabono.value		= obj.abono.efectivo;
			u.h_transferenciaabono.value= obj.abono.transferencia;
			u.h_notaabono.value			= obj.abono.notacredito;
			u.h_tarjetaabono.value		= obj.abono.tarjeta;
			u.h_chequeabono.value		= obj.abono.cheque;
			
			u.h_efectivoabono.value		= "$ "+numcredvar(u.h_efectivoabono.value);
			u.h_transferenciaabono.value= "$ "+numcredvar(u.h_transferenciaabono.value);
			u.h_notaabono.value			= "$ "+numcredvar(u.h_notaabono.value);
			u.h_tarjetaabono.value		= "$ "+numcredvar(u.h_tarjetaabono.value);
			u.h_chequeabono.value		= "$ "+numcredvar(u.h_chequeabono.value);
			
			u.entregaread.value			= obj.liquidacion.entregaread;
			u.entregarocurre.value		= obj.ocurre.entregarocurre;
			u.entregarcobranza.value	= obj.cobranza.entregarcobranza;			
			u.entregarabono.value		= obj.abono.entregarabono;
			
			u.entregaread.value			= "$ "+numcredvar(u.entregaread.value);
			u.entregarocurre.value		= "$ "+numcredvar(u.entregarocurre.value);
			u.entregarcobranza.value	= "$ "+numcredvar(u.entregarcobranza.value);			
			u.entregarabono.value		= "$ "+numcredvar(u.entregarabono.value);
			
			u.gcredito.value			= obj.otros.credito;
			u.fcancelada.value			= obj.otros.fcanceladas;			
			u.gcancelada.value			= obj.otros.cancelada;
			
			u.efectivoentregado.value		= obj.entregado.efectivo;
			u.tarjetaentregado.value		= obj.entregado.tarjeta;
			u.transferenciaentregado.value	= obj.entregado.transferencia;
			u.chequeentregado.value			= obj.entregado.cheque;
			u.totalentregado.value			= obj.entregado.entregado;
			
			u.efectivoentregado.value		= "$ "+numcredvar(u.efectivoentregado.value);
			u.tarjetaentregado.value		= "$ "+numcredvar(u.tarjetaentregado.value);
			u.transferenciaentregado.value	= "$ "+numcredvar(u.transferenciaentregado.value);
			u.chequeentregado.value			= "$ "+numcredvar(u.chequeentregado.value);
			u.totalentregado.value			= "$ "+numcredvar(u.totalentregado.value);
			
			u.factefectivo.value			= obj.facturado.efectivo;
			u.facttransferencia.value		= obj.facturado.transferencia;
			u.factnotacredito.value			= obj.facturado.notacredito;
			u.facttarjeta.value				= obj.facturado.tarjeta;
			u.factcheque.value				= obj.facturado.cheque;
			u.factentregado.value			= obj.facturado.factentregado;
			
			u.factefectivo.value			= "$ "+numcredvar(u.factefectivo.value);
			u.facttransferencia.value		= "$ "+numcredvar(u.facttransferencia.value);
			u.factnotacredito.value			= "$ "+numcredvar(u.factnotacredito.value);
			u.facttarjeta.value				= "$ "+numcredvar(u.facttarjeta.value);
			u.factcheque.value				= "$ "+numcredvar(u.factcheque.value);
			u.factentregado.value			= "$ "+numcredvar(u.factentregado.value);
			
			u.nota.innerHTML = "*Dar click en las cantidades para ver su detalle";
		}else{
			mens.show("A","El numero empleado no existe o no pertenece a la sucursal","¡Atención!","empleado");
			u.empleado.value = "";
			u.nombre.value = "";
		}
	}
	function obtenerDetalleLiquidacion(tipo){
		if(u.empleado.value!="" && u.nombre.value!=""){
			abrirVentanaFija('detalleLiquidacion.php?tipo='+tipo
			+'&empleado='+u.empleado.value
			+"&cambiafecha="+u.dia_anterior.value+"&fecha="+u.fecha.value, 730, 500, 'ventana', 'Detalle Cierre Caja');
		}else{
			mens.show("A","Debe capturar empleado para ver su detalle","¡Atención!","empleado");
		}
	}
	
	function obtenerDetalleVentanilla(tipo){
		if(u.empleado.value!="" && u.nombre.value!=""){
		abrirVentanaFija('detalleVentanilla.php?tipo='+tipo
		+'&empleado='+u.empleado.value
		+"&cambiafecha="+u.dia_anterior.value+"&fecha="+u.fecha.value, 670, 500, 'ventana', 'Detalle Cierre Caja');
		}else{
			mens.show("A","Debe capturar empleado para ver su detalle","¡Atención!","empleado");
		}
	}
	
	function imprimirReporte(){
		<?=$cpermiso->verificarPermiso(451,$_SESSION[IDUSUARIO]);?>
		if(document.URL.indexOf("web/")>-1){		
			window.open("http://www.pmmintranet.net/web/Caja/reporteCaja.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>",null, "toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0");
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/Caja/reporteCaja.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>",null, "toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0");
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("http://www.pmmintranet.net/web_pruebas/Caja/reporteCaja.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>",null, "toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0");
		}
	}
	
	function obtenerDatosFechaAnterior(fecha){
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
		
		if(f2 >= f1 && u.dia_anterior.value!=""){
			f_limpiar();
		}
		
		consultaTexto("mostrarDatosFechaAnterior","cierrePrincipal_con.php?accion=5&fecha="+u.fecha.value+"&ss="+Math.random());
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
		
		if(datos.indexOf("noiniciodia")>-1){
			mens.show("A","No ha iniciado día con la fecha seleccionada","¡Atención!");
			u.fecha.value = '<?=date('d/m/Y'); ?>';
			return false;
		}
		
		if(datos.indexOf("yacerro")>-1){
			mens.show("A","La Fecha de cierre seleccionada ya fue registrada","¡Atención!");
			return false;
		}
		
		if(datos.indexOf("GUARDADO")>-1){
			var obj = eval(datos);
			u.folio.value = obj.principal.folio;
			u.fecha.value = obj.principal.fecha;
			u.estado.value = obj.principal.estado;
			u.accion.value = "modificar";
		}else{
			var obj = eval(datos);
			u.estado.value = "";
		}	
		
		if(obj.usuarios.replace("&#32;","")!=""){
			v_empleados = obj.usuarios;
			mens.show("A","El o Los siguientes empleados no han hecho el cierre de caja definitivo: "+obj.usuarios,"¡Atención!");
		}
			u.h_efectivocaja.value		= obj.ventanilla.efectivo;
			u.h_efectivocaja.value		= "$ "+numcredvar(u.h_efectivocaja.value);
			u.h_transferenciacaja.value	= obj.ventanilla.transferencia;
			u.h_transferenciacaja.value	= "$ "+numcredvar(u.h_transferenciacaja.value);
			u.h_notacaja.value			= ((obj.ventanilla.notacredito!=0)?obj.ventanilla.notacredito:0);
			u.h_notacaja.value			= "$ "+numcredvar(u.h_notacaja.value);
			u.h_tarjetacaja.value		= obj.ventanilla.tarjeta;
			u.h_tarjetacaja.value		= "$ "+numcredvar(u.h_tarjetacaja.value);
			u.h_chequecaja.value		= obj.ventanilla.cheque;
			u.h_chequecaja.value		= "$ "+numcredvar(u.h_chequecaja.value);			
			u.entregarventanilla.value	= obj.ventanilla.entregarventanilla;
			u.entregarventanilla.value	= "$ "+numcredvar(u.entregarventanilla.value);
			
			u.h_efectivoead.value		= obj.liquidacion.efectivo;
			u.h_efectivoead.value		= "$ "+numcredvar(u.h_efectivoead.value);			
			u.h_transferenciaead.value	= obj.liquidacion.transferencia;
			u.h_transferenciaead.value	= "$ "+numcredvar(u.h_transferenciaead.value);
			u.h_tarjetaead.value		= obj.liquidacion.tarjeta;
			u.h_tarjetaead.value		= "$ "+numcredvar(u.h_tarjetaead.value);
			u.h_chequeead.value			= obj.liquidacion.cheque;
			u.h_chequeead.value			= "$ "+numcredvar(u.h_chequeead.value);
			u.h_notaead.value			= obj.liquidacion.notacredito;
			u.h_notaead.value			= "$ "+numcredvar(u.h_notaead.value);
			
			u.h_efectivocob.value		= obj.cobranza.efectivo;
			u.h_efectivocob.value		= "$ "+numcredvar(u.h_efectivocob.value);
			u.h_transferenciacob.value	= obj.cobranza.transferencia;
			u.h_transferenciacob.value	= "$ "+numcredvar(u.h_transferenciacob.value);
			u.h_notacob.value			= obj.cobranza.notacredito;
			u.h_notacob.value			= "$ "+numcredvar(u.h_notacob.value);
			u.h_tarjetacob.value		= obj.cobranza.tarjeta;
			u.h_tarjetacob.value		= "$ "+numcredvar(u.h_tarjetacob.value);
			u.h_chequecob.value			= obj.cobranza.cheque;
			u.h_chequecob.value			= "$ "+numcredvar(u.h_chequecob.value);		
			
			u.h_efectivoocu.value		= obj.ocurre.efectivo;
			u.h_transferenciaocu.value	= obj.ocurre.transferencia;
			u.h_notaocu.value			= obj.ocurre.notacredito;
			u.h_tarjetaocu.value		= obj.ocurre.tarjeta;
			u.h_chequeocu.value			= obj.ocurre.cheque;
			
			u.h_efectivoocu.value		= "$ "+numcredvar(u.h_efectivoocu.value);
			u.h_transferenciaocu.value	= "$ "+numcredvar(u.h_transferenciaocu.value);
			u.h_notaocu.value			= "$ "+numcredvar(u.h_notaocu.value);
			u.h_tarjetaocu.value		= "$ "+numcredvar(u.h_tarjetaocu.value);
			u.h_chequeocu.value			= "$ "+numcredvar(u.h_chequeocu.value);
			
			u.h_efectivoabono.value		= obj.abono.efectivo;
			u.h_transferenciaabono.value= obj.abono.transferencia;
			u.h_notaabono.value			= obj.abono.notacredito;
			u.h_tarjetaabono.value		= obj.abono.tarjeta;
			u.h_chequeabono.value		= obj.abono.cheque;
			
			u.h_efectivoabono.value		= "$ "+numcredvar(u.h_efectivoabono.value);
			u.h_transferenciaabono.value= "$ "+numcredvar(u.h_transferenciaabono.value);
			u.h_notaabono.value			= "$ "+numcredvar(u.h_notaabono.value);
			u.h_tarjetaabono.value		= "$ "+numcredvar(u.h_tarjetaabono.value);
			u.h_chequeabono.value		= "$ "+numcredvar(u.h_chequeabono.value);
			
			u.entregaread.value			= obj.liquidacion.entregaread;
			u.entregarocurre.value		= obj.ocurre.entregarocurre;
			u.entregarcobranza.value	= obj.cobranza.entregarcobranza;			
			u.entregarabono.value		= obj.abono.entregarabono;
			
			u.entregaread.value			= "$ "+numcredvar(u.entregaread.value);
			u.entregarocurre.value		= "$ "+numcredvar(u.entregarocurre.value);
			u.entregarcobranza.value	= "$ "+numcredvar(u.entregarcobranza.value);			
			u.entregarabono.value		= "$ "+numcredvar(u.entregarabono.value);
			
			u.gcredito.value			= obj.otros.credito;
			u.fcancelada.value			= obj.otros.fcanceladas;			
			u.gcancelada.value			= obj.otros.cancelada;
			u.dia_anterior.value		= "SI";
			
			u.empleadosead.value		= obj.empleadosead;
			u.empleadoscobranza.value	= obj.empleadoscobranza;
			u.empleadosventanilla.value	= obj.empleadosventanilla;
			u.cierre.value				= obj.cierre;
			
			u.efectivoentregado.value		= obj.entregado.efectivo;
			u.tarjetaentregado.value		= obj.entregado.tarjeta;
			u.transferenciaentregado.value	= obj.entregado.transferencia;
			u.chequeentregado.value			= obj.entregado.cheque;
			u.totalentregado.value			= obj.entregado.entregado;
			
			u.efectivoentregado.value		= "$ "+numcredvar(u.efectivoentregado.value);
			u.tarjetaentregado.value		= "$ "+numcredvar(u.tarjetaentregado.value);
			u.transferenciaentregado.value	= "$ "+numcredvar(u.transferenciaentregado.value);
			u.chequeentregado.value			= "$ "+numcredvar(u.chequeentregado.value);
			u.totalentregado.value			= "$ "+numcredvar(u.totalentregado.value);
			
			u.factefectivo.value			= obj.facturado.efectivo;
			u.facttransferencia.value		= obj.facturado.transferencia;
			u.factnotacredito.value			= obj.facturado.notacredito;
			u.facttarjeta.value				= obj.facturado.tarjeta;
			u.factcheque.value				= obj.facturado.cheque;
			u.factentregado.value			= obj.facturado.factentregado;
			
			u.factefectivo.value			= "$ "+numcredvar(u.factefectivo.value);
			u.facttransferencia.value		= "$ "+numcredvar(u.facttransferencia.value);
			u.factnotacredito.value			= "$ "+numcredvar(u.factnotacredito.value);
			u.facttarjeta.value				= "$ "+numcredvar(u.facttarjeta.value);
			u.factcheque.value				= "$ "+numcredvar(u.factcheque.value);
			u.factentregado.value			= "$ "+numcredvar(u.factentregado.value);
	}
	
	function obtenerLiquidacionDepositosAnterior(cierre){
		consultaTexto("validarDiaAnterior","cierrePrincipal_con.php?accion=7&dia_anterior=si&fecha="+u.fecha.value+"&cierre="+cierre+"&m="+Math.random());
	}
	
	function validarDiaAnterior(cierre){
	
		
	
		/*var obj = eval(datos);
		var cierre = obj.cierre;
		
		if(obj.cierre=="cierre"){
			<? //$cpermiso->verificarPermiso(449,$_SESSION[IDUSUARIO]);?>
			return false;
		}
		<? //$cpermiso->verificarPermiso(448,$_SESSION[IDUSUARIO]);?>
		
		if(obj.liquidaciones==1){
			mens.show("A","Para poder hacer el cierre principal debe terminar las liquidaciones ead pendientes","¡Atención!");
			return false;
		}
		
		if(obj.depositos==1){
			mens.show("A","Para poder hacer el cierre principal debe hacer los depositos del dia","¡Atención!");
			return false;
		}*/
		
		if(cierre=="cierre"){
			<?=$cpermiso->verificarPermiso(449,$_SESSION[IDUSUARIO]);?>
		}
		<?=$cpermiso->verificarPermiso(448,$_SESSION[IDUSUARIO]);?>
		/*if(u.cierre.value=="NO"){
			mens.show("A","No se puede cerrar caja principal, por que no se ha realizado el cierre de caja definitivo","¡Atención!");
			return false;
		}*/
		
		var msg = parent.checarParaCierreDia();
		if(msg!=""){
			mens.show("A","Para poder hacer el cierre principal debe terminar lo siguiente:<br>"+msg,"¡Atención!");
			return false;
		}
		
		if(v_empleados!=""){
			mens.show("A","No se puede cerrar caja principal, por que el o los siguientes empleados no han hecho el cierre de caja definitivo: "+v_empleados,"¡Atención!");
			return false;
		}
		
		if(u.estado.value=="CERRADA"){
			mens.show("A","La caja principal del dia ya fue cerrada","¡Atención!");
			return false;
		}
		
		var ead = u.empleadosead.value.split(":");
		var eadd = "";
		var empleado = "";
		empleados = "";		
		for(var i=0; i<ead.length; i++){
			eadd = ead[i].split(",");
			for(var k=0; k<eadd.length/2; k++){
				if(eadd[1]>0){
					empleado += eadd[0] + ",";
					empleados += eadd[0] + "," + eadd[1] + ":";
				}
			}
		}
		
		var cob = u.empleadoscobranza.value.split(":");
		var cobb= "";
		
		for(var i=0; i<cob.length; i++){
			cobb = cob[i].split(",");
			for(var k=0; k<cobb.length/2; k++){
				if(cobb[1]>0){
					empleado += cobb[0] + ",";
					empleados += cobb[0] + "," + cobb[1] + ":";
				}
			}
		}
		
		var ven	= u.empleadosventanilla.value.split(":");
		var venn= "";
		
		for(var i=0; i<ven.length; i++){
			venn = ven[i].split(",");
			for(var k=0; k<venn.length/2; k++){
				if(venn[1]>0){
					empleado += venn[0] + ",";
					empleados += venn[0] + "," + venn[1] + ":";
				}
			}
		}
		
		if(cierre=="1"){
			mens.show('C','¿Desea guardar la información capturada?','','', 'cierreCaja(\'1\');');
		}else{
			if(empleado!=""){
				empleado = empleado.substring(0,empleado.length -1);
				mens.show('C','Los siguientes empleados cuentan con faltantes, se realizara un vale por cada uno:<br>'+empleado
				+', ¿Desea continuar?','','','cierreCajaVale(\'cierre\');');
			}else{
				mens.show('C','Se realizara el cierre de caja principal, ¿Desea continuar?','','','cierreCaja(\'cierre\');');
			}
		}
	}
	
	function f_limpiar(){
		u.fecha.value = "";
		u.estado.value = "";
		u.accion.value = "";
		
		u.h_efectivocaja.value = 0;
		u.h_transferenciacaja.value = 0;
		u.h_notacaja.value = 0;
		u.h_tarjetacaja.value = 0;
		u.h_chequecaja.value		= 0;
		u.entregarventanilla.value	= 0;
		u.h_efectivoead.value		= 0;

		u.h_transferenciaead.value	= 0;
		u.h_tarjetaead.value		= 0;
		u.h_chequeead.value			= 0;
		u.h_notaead.value			= 0;
		
		u.h_efectivocob.value		= 0;
		u.h_transferenciacob.value	= 0;
		u.h_notacob.value			= 0;
		u.h_tarjetacob.value		= 0;
		u.h_chequecob.value			= 0;
		
		u.h_efectivoocu.value		= 0;
		u.h_transferenciaocu.value	= 0;
		u.h_notaocu.value			= 0;
		u.h_tarjetaocu.value		= 0;
		u.h_chequeocu.value			= 0;

		u.h_efectivoabono.value		= 0;
		u.h_transferenciaabono.value= 0;
		u.h_notaabono.value			= 0;
		u.h_tarjetaabono.value		= 0;
		u.h_chequeabono.value		= 0;

		u.entregaread.value			= 0;
		u.entregarocurre.value		= 0;
		u.entregarcobranza.value	= 0;		
		u.entregarabono.value		= 0;

		u.gcredito.value			= 0;
		u.fcancelada.value			= 0;			
		u.gcancelada.value			= 0;
		u.dia_anterior.value		= "";
			
		u.empleadosead.value		= 0;
		u.empleadoscobranza.value	= 0;
		u.empleadosventanilla.value	= 0;
		u.cierre.value				= "";
		
		u.efectivoentregado.value		= 0;
		u.tarjetaentregado.value		= 0;
		u.transferenciaentregado.value	= 0;
		u.chequeentregado.value			= 0;
		u.totalentregado.value			= 0;
		
		u.factefectivo.value			= 0;
		u.facttransferencia.value		= 0;
		u.factnotacredito.value			= 0;
		u.facttarjeta.value				= 0;
		u.factcheque.value				= 0;
		u.factentregado.value			= 0;		
		
		ponerTipoMoneda();
	}
	
	function f_limpiar2(){
		u.h_efectivocaja.value = 0;
		u.h_transferenciacaja.value = 0;
		u.h_notacaja.value = 0;
		u.h_tarjetacaja.value = 0;
		u.h_chequecaja.value		= 0;
		u.entregarventanilla.value	= 0;
		u.h_efectivoead.value		= 0;

		u.h_transferenciaead.value	= 0;
		u.h_tarjetaead.value		= 0;
		u.h_chequeead.value			= 0;
		u.h_notaead.value			= 0;
		
		u.h_efectivocob.value		= 0;
		u.h_transferenciacob.value	= 0;
		u.h_notacob.value			= 0;
		u.h_tarjetacob.value		= 0;
		u.h_chequecob.value			= 0;
		
		u.h_efectivoocu.value		= 0;
		u.h_transferenciaocu.value	= 0;
		u.h_notaocu.value			= 0;
		u.h_tarjetaocu.value		= 0;
		u.h_chequeocu.value			= 0;

		u.h_efectivoabono.value		= 0;
		u.h_transferenciaabono.value= 0;
		u.h_notaabono.value			= 0;
		u.h_tarjetaabono.value		= 0;
		u.h_chequeabono.value		= 0;

		u.entregaread.value			= 0;
		u.entregarocurre.value		= 0;
		u.entregarcobranza.value	= 0;		
		u.entregarabono.value		= 0;

		u.gcredito.value			= 0;
		u.fcancelada.value			= 0;			
		u.gcancelada.value			= 0;
		u.dia_anterior.value		= "";
			
		u.empleadosead.value		= 0;
		u.empleadoscobranza.value	= 0;
		u.empleadosventanilla.value	= 0;
		u.cierre.value				= "";
		
		u.efectivoentregado.value		= 0;
		u.tarjetaentregado.value		= 0;
		u.transferenciaentregado.value	= 0;
		u.chequeentregado.value			= 0;
		u.totalentregado.value			= 0;
		
		u.factefectivo.value			= 0;
		u.facttransferencia.value		= 0;
		u.factnotacredito.value			= 0;
		u.facttarjeta.value				= 0;
		u.factcheque.value				= 0;
		u.factentregado.value			= 0;		
		
		ponerTipoMoneda();
	}
	
	function imprimirDetalle(){	
		<?=$cpermiso->verificarPermiso(455,$_SESSION[IDUSUARIO]);?>
		if(document.URL.indexOf("web/")>-1){		
			window.open("http://www.pmmintranet.net/web/fpdf/reportes/cierreCajaDetalle.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&fecha="+u.fecha.value,null, "toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0");
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/cierreCajaDetalle.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&fecha="+u.fecha.value,null, "toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0");
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/cierreCajaDetalle.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&fecha="+u.fecha.value,null, "toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0");
		}
	}
	
</script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></link>
<title>Documento sin t&iacute;tulo</title>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <p>&nbsp;</p>
  <table width="620" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
      <tr>
        <td class="FondoTabla">CIERRE PRINCIPAL</td>
      </tr>
      
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="Tablas">
          <tr>
            <td>Folio:</td>
            <td width="35%">
			<input name="folio" type="text" id="folio" class="Tablas" style="width:80px" onkeypress="if(event.keyCode==13){obtenerCierre2(this.value);}" />
            <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarCierrePrincipal.php?funcion=obtenerCierre', 550, 500, 'ventana', 'Busqueda')"/></td>
            <td >Fecha:<span style="width:200px">
            <input name="fecha" type="text" class="Tablas" id="fecha" readonly="" style="width:100px; background-color:#FFFF99" value="<?=$fecha ?>" onchange="obtenerDatosFechaAnterior(this.value)" />
            <img src="../img/calendario.gif" width="20" height="20" align="absbottom" style="cursor:pointer" onclick="if(<?=$cpermiso->checarPermiso(450,$_SESSION[IDUSUARIO]);?>==false){mens.show('A','Usted no tiene los permisos para ejecutar esta acción','¡Atención!');}else{displayCalendar(document.forms[0].fecha,'dd/mm/yyyy',this);}"/></span>&nbsp;&nbsp;</td>
          </tr>
          <tr>
            <td>Empleado:</td>
            <td colspan="2"><label>
              <input name="empleado" type="text" id="empleado" style="width:60px" onkeypress="if(event.keyCode==13){obtenerEmpleado(this.value);}" class="Tablas" />
              <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarEmpleadoGen.php?funcion=obtenerEmpleado', 500, 450, 'ventana', 'Busqueda')"/>
              <input name="nombre" type="text" id="nombre" style="width:250px" class="Tablas" />
            </label></td>
          </tr>
          <tr>
            <td width="10%"><span style="width:200px">
              <input name="accion" type="hidden" id="accion" />
              <input name="estado" type="hidden" id="estado" value="<?=$tt->estado ?>" />
              <input name="dia_anterior" type="hidden" id="dia_anterior" />
            </span></td>
            <td><label><span style="width:200px">
              <input name="cierre" type="hidden" id="cierre" />
              <input name="h_fecha" type="hidden" id="h_fecha" value="<?=date('d/m/Y') ?>" />
              <input name="existeiniciodia" type="hidden" id="existeiniciodia" value="<?=$existeiniciodia ?>" />
            </span></label></td>
            <td align="right">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="FondoTabla">Liquidaci&oacute;n EAD</td>
            <td width="55%"><label></label></td>
          </tr>
          <tr>
            <td colspan="3"><table width="623" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="70" style="width:70px">Efectivo :</td>
                <td width="103"  style="width:100px"><span style="width:70px">
                  <input name="h_efectivoead" class="Tablas" type="text" id="h_efectivoead" value="<?=$f->efectivo ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ead');}" />
                </span></td>
                <td width="100" style="width:100px">Transferencia :</td>
                <td width="103" style="width:100px" ><span style="width:200px">
                  <input name="h_transferenciaead" class="Tablas" type="text" id="h_transferenciaead" value="<?=$f->transferencia ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ead');}"/>
                </span></td>
                <td width="147" style="width:80px">Nota Cr&eacute;dito:</td>
                <td width="100" ><span style="width:200px">
                  <input name="h_notaead" class="Tablas" type="text" id="h_notaead" value="<?=$f->notacredito ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ead');}"/>
                </span></td>
              </tr>
              
              <tr>
                <td>Tarjeta :</td>
                <td><span style="width:200px">
                  <input name="h_tarjetaead" class="Tablas" type="text" id="h_tarjetaead" value="<?=$f->tarjeta ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ead');}"/>
                </span></td>
                <td>Cheque :</td>
                <td><span style="width:200px">
                  <input name="h_chequeead" class="Tablas" type="text" id="h_chequeead" value="<?=$f->cheque ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ead');}"/>
                </span></td>
                <td>Total a Entregar:</td>
                <td><span style="width:200px">
                  <input name="entregaread" class="Tablas" type="text" id="entregaread" value="<?=$f->entregaread ?>" style="width:100px; text-align:right; background-color:#FFFF99" readonly="" />
                </span></td>
              </tr>
              
            </table></td>
          </tr>
          
          <tr>
            <td colspan="2"><span style="width:200px">
              <input name="empleadosead" type="hidden" id="empleadosead" value="<?=$empleadosead ?>" />
              <input name="empleadoseadCierre" type="hidden" id="empleadoseadCierre" value="<?=$empleadosead ?>" />
            </span></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="FondoTabla">Liquidaci&oacute;n Cobranza</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><table width="623" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="76" style="width:70px">Efectivo :</td>
                  <td width="112"  style="width:100px"><span style="width:70px"><span style="width:200px">
                    <input name="h_efectivocob" class="Tablas" type="text" id="h_efectivocob" value="<?=$c->efectivo ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('cobranza');}"/>
                  </span></span></td>
                  <td width="109" style="width:100px">Transferencia :</td>
                  <td width="112" style="width:100px" ><span style="width:200px">
                    <input name="h_transferenciacob" class="Tablas" type="text" id="h_transferenciacob" value="<?=$c->transferencia ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('cobranza');}"/>
                  </span></td>
                  <td width="114" style="width:80px">Nota Cr&eacute;dito:</td>
                  <td width="100" ><span style="width:80px"><span style="width:200px">
                    <input name="h_notacob" class="Tablas" type="text" id="h_notacob" value="<?=$c->notacredito ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('cobranza');}"/>
                  </span></span></td>
                </tr>
                
                <tr>
                  <td>Tarjeta :</td>
                  <td><span style="width:200px">
                    <input name="h_tarjetacob" class="Tablas" type="text" id="h_tarjetacob" value="<?=$c->tarjeta ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('cobranza');}"/>
                  </span></td>
                  <td>Cheque :</td>
                  <td><span style="width:200px">
                    <input name="h_chequecob" class="Tablas" type="text" id="h_chequecob" value="<?=$c->cheque ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('cobranza');}"/>
                  </span></td>
                  <td>Total a Entregar:</td>
                  <td><span style="width:200px">
                    <input name="entregarcobranza" class="Tablas" type="text" id="entregarcobranza" value="<?=$c->entregarcobranza ?>" style="width:100px; text-align:right; background-color:#FFFF99" readonly="" />
                  </span></td>
                </tr>
                
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><span style="width:200px">
              <input name="empleadoscobranza" type="hidden" id="empleadoscobranza" value="<?=$empleadoscobranza ?>" />
              <input name="empleadoscobranzaCierre" type="hidden" id="empleadoscobranzaCierre" value="<?=$empleadoscobranza ?>" />
            </span></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="FondoTabla">Liquidaci&oacute;n Cajas Ventanilla</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><table width="623" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="72" style="width:70px">Efectivo :</td>
                  <td width="106"  style="width:100px"><span style="width:70px"><span style="width:200px">
                    <input name="h_efectivocaja" class="Tablas" type="text" id="h_efectivocaja" value="<?=$v->efectivo ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleVentanilla('ventanilla');}"/>
                  </span></span></td>
                  <td width="103" style="width:100px">Transferencia :</td>
                  <td width="106" style="width:100px" ><span style="width:200px">
                    <input name="h_transferenciacaja" class="Tablas" type="text" id="h_transferenciacaja" value="<?=$v->transferencia ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleVentanilla('ventanilla');}"/>
                  </span></td>
                  <td width="136" style="width:80px">Nota Cr&eacute;dito:</td>
                  <td width="100" ><span style="width:80px"><span style="width:200px">
                    <input name="h_notacaja" class="Tablas" type="text" id="h_notacaja" value="<?=$v->notacredito ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleVentanilla('ventanilla');}"/>
                  </span></span></td>
                </tr>
                
                <tr>
                  <td>Tarjeta :</td>
                  <td><span style="width:200px">
                    <input name="h_tarjetacaja" class="Tablas" type="text" id="h_tarjetacaja" value="<?=$v->tarjeta ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleVentanilla('ventanilla');}"/>
                  </span></td>
                  <td>Cheque :</td>
                  <td><span style="width:200px">
                    <input name="h_chequecaja" class="Tablas" type="text" id="h_chequecaja" value="<?=$v->cheque ?>" style="width:100px; text-align:right; background-color:#FFFF99; cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleVentanilla('ventanilla');}"/>
                  </span></td>
                  <td>Total a Entregar:</td>
                  <td><span style="width:200px">
                    <input name="entregarventanilla" class="Tablas" type="text" id="entregarventanilla" value="<?=$v->entregarventanilla ?>" style="width:100px; text-align:right; background-color:#FFFF99" readonly="" />
                  </span></td>
                </tr>
                
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><span style="width:200px">
              <input name="empleadosventanilla" type="hidden" id="empleadosventanilla" value="<?=$empleadosventanilla ?>" />
              <input name="empleadosventanillaCierre" type="hidden" id="empleadosventanillaCierre" value="<?=$empleadosventanilla ?>" />
            </span></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="FondoTabla">Liquidaci&oacute;n Cajas Ocurre</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" ><table width="623" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="70" style="width:70px">Efectivo :</td>
                <td width="103"  style="width:100px"><span style="width:70px"><span style="width:200px">
                  <input name="h_efectivoocu" class="Tablas" type="text" id="h_efectivoocu" value="<?=$co->efectivo ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ocurre');}"/>
                </span></span></td>
                <td width="100" style="width:100px">Transferencia :</td>
                <td width="103" style="width:100px" ><span style="width:200px">
                  <input name="h_transferenciaocu" class="Tablas" type="text" id="h_transferenciaocu" value="<?=$co->transferencia ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ocurre');}"/>
                </span></td>
                <td width="147" style="width:80px">Nota Cr&eacute;dito:</td>
                <td width="100" ><span style="width:80px"><span style="width:200px">
                  <input name="h_notaocu" class="Tablas" type="text" id="h_notaocu" value="<?=$co->notacredito ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ocurre');}"/>
                </span></span></td>
              </tr>
              
              <tr>
                <td>Tarjeta :</td>
                <td><span style="width:200px">
                  <input name="h_tarjetaocu" class="Tablas" type="text" id="h_tarjetaocu" value="<?=$co->tarjeta ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ocurre');}"/>
                </span></td>
                <td>Cheque :</td>
                <td><span style="width:200px">
                  <input name="h_chequeocu" class="Tablas" type="text" id="h_chequeocu" value="<?=$co->cheque ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ocurre');}"/>
                </span></td>
                <td>Total a Entregar:</td>
                <td><span style="width:200px">
                  <input name="entregarocurre" class="Tablas" type="text" id="entregarocurre" value="<?=$co->entregarocurre ?>" style="width:100px; text-align:right; background-color:#FFFF99" readonly="" />
                </span></td>
              </tr>
              
            </table></td>
          </tr>
          <tr>
            <td colspan="3" >&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="FondoTabla">Abono Cliente</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="Tablas">
              <tr>
                <td colspan="2"><table width="623" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="70" style="width:70px">Efectivo :</td>
                      <td width="103"  style="width:100px"><span style="width:70px"><span style="width:200px">
                        <input name="h_efectivoabono" class="Tablas" id="h_efectivoabono" value="<?=$abo->efectivo ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('abono');}"/>
                      </span></span></td>
                      <td width="100" style="width:100px">Transferencia :</td>
                      <td width="103" style="width:100px" ><span style="width:200px">
                        <input name="h_transferenciaabono" class="Tablas" type="text" id="h_transferenciaabono" value="<?=$abo->transferencia ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('abono');}"/>
                      </span></td>
                      <td width="147" style="width:80px">Nota Cr&eacute;dito:</td>
                      <td width="100" ><span style="width:80px"><span style="width:200px">
                        <input name="h_notaabono" class="Tablas" type="text" id="h_notaabono" value="<?=$abo->notacredito ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('abono');}"/>
                      </span></span></td>
                    </tr>
                    <tr>
                      <td>Tarjeta :</td>
                      <td><span style="width:200px">
                        <input name="h_tarjetaabono" class="Tablas" type="text" id="h_tarjetaabono" value="<?=$abo->tarjeta ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('abono');}"/>
                      </span></td>
                      <td>Cheque :</td>
                      <td><span style="width:200px">
                        <input name="h_chequeabono" class="Tablas" type="text" id="h_chequeabono" value="<?=$abo->cheque ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('abono');}"/>
                      </span></td>
                      <td>Total a Entregar:</td>
                      <td><span style="width:200px">
                        <input name="entregarabono" class="Tablas" type="text" id="entregarabono" value="<?=$abo->entregarabono ?>" style="width:100px; text-align:right; background-color:#FFFF99" readonly="" />
                      </span></td>
                    </tr>
                </table></td>
              </tr>
              
              <tr>
                <td width="300" align="right" style="width:300px">&nbsp;</td>
                <td width="323">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
		   <tr>
            <td colspan="2" class="FondoTabla">Venta  Guias  Prepagadas </td>
            <td>&nbsp;</td>
          </tr>
		  <tr>
            <td colspan="3"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="Tablas">
              <tr>
                <td colspan="2"><table width="623" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="70" style="width:70px">Efectivo :</td>
                      <td width="103"  style="width:100px"><span style="width:70px"><span style="width:200px">
                        <input name="factefectivo" class="Tablas" id="factefectivo" value="<?=$fact->efectivo ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ventas');}"/>
                      </span></span></td>
                      <td width="100" style="width:100px">Transferencia :</td>
                      <td width="103" style="width:100px" ><span style="width:200px">
                        <input name="facttransferencia" class="Tablas" type="text" id="facttransferencia" value="<?=$fact->transferencia ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('abono');}"/>
                      </span></td>
                      <td width="147" style="width:80px">Nota Cr&eacute;dito:</td>
                      <td width="100" ><span style="width:80px"><span style="width:200px">
                        <input name="factnotacredito" class="Tablas" type="text" id="factnotacredito" value="<?=$fact->notacredito ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ventas');}"/>
                      </span></span></td>
                    </tr>
                    <tr>
                      <td>Tarjeta :</td>
                      <td><span style="width:200px">
                        <input name="facttarjeta" class="Tablas" type="text" id="facttarjeta" value="<?=$fact->tarjeta ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ventas');}"/>
                      </span></td>
                      <td>Cheque :</td>
                      <td><span style="width:200px">
                        <input name="factcheque" class="Tablas" type="text" id="factcheque" value="<?=$fact->cheque ?>" style="width:100px; text-align:right; background-color:#FFFF99;cursor:pointer" readonly="" onclick="if(this.value!='$ 0.00'){obtenerDetalleLiquidacion('ventas');}"/>
                      </span></td>
                      <td>Total a Entregar:</td>
                      <td><span style="width:200px">
                        <input name="factentregado" class="Tablas" type="text" id="factentregado" value="<?=$factentregado ?>" style="width:100px; text-align:right; background-color:#FFFF99" readonly="" />
                      </span></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td width="300" align="right" style="width:300px">&nbsp;</td>
                <td width="323">&nbsp;</td>
              </tr>
            </table></td>
		</tr>
          <tr>
            <td colspan="2" class="FondoTabla">Otros Movimientos </td>
            <td align="right">Total gral a entregar
            <input name="totalgral" class="Tablas" id="totalgral" style="width:100px; text-align:right; background-color:#FFFF99;" readonly
			value="<? echo $f->entregaread+$c->entregarcobranza+$v->entregarventanilla+$co->entregarocurre+$abo->entregarabono+$factentregado; ?>"/></td>
          </tr>
          <tr>
            <td colspan="3"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="Tablas">
              <tr>
                <td colspan="2"><table width="623" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="124">#Gu&iacute;as Cr&eacute;dito:</td>
                      <td width="136" >
                        <input name="gcredito" class="Tablas" id="gcredito" value="<?=$o->credito ?>" style="width:100px; text-align:right; background-color:#FFFF99;" readonly="" />                      </td>
                      <td width="136" >&nbsp;</td>
                      <td width="227" >&nbsp;</td>
                    </tr>
                    <tr>
                      <td># Gu&iacute;as Canceladas:</td>
                      <td>
                        <input name="gcancelada" class="Tablas" type="text" id="gcancelada" value="<?=$o->cancelada ?>" 
						style="width:100px; text-align:right; background-color:#FFFF99;" readonly="" /> </td>
                      <td width="136" >Facturas Canceladas:</td>
                      <td width="227" >
                        <input name="fcancelada" class="Tablas" type="text" id="fcancelada" value="<?=$o->fcanceladas ?>" style="width:100px; text-align:right; background-color:#FFFF99;" readonly="" />                      </td>
                    </tr>
                </table></td>
              </tr>
              
              <tr>
                <td width="300" align="right" style="width:300px">&nbsp;</td>
                <td width="323">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2" class="FondoTabla">Entregado por el Cajero(a)</td>
			<td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><table width="623" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="72">Efectivo:</td>
                <td width="106" ><input name="efectivoentregado" class="Tablas" id="efectivoentregado" value="<?=$te->efectivo ?>" style="width:100px; text-align:right; background-color:#FFFF99;" readonly="" />                </td>
                <td width="103" ><span style="width:100px">Transferencia :</span></td>
                <td colspan="3" ><input name="transferenciaentregado" class="Tablas" type="text" id="transferenciaentregado" value="<?=$te->transferencia ?>" style="width:100px; text-align:right; background-color:#FFFF99;" readonly="" />                </td>
              </tr>
              <tr>
                <td>Tarjeta:</td>
                <td><input name="tarjetaentregado" class="Tablas" type="text" id="tarjetaentregado" value="<?=$te->tarjeta ?>" style="width:100px; text-align:right; background-color:#FFFF99;" readonly="" />                </td>
                <td width="102">Cheque :</td>
                <td width="112"><input name="chequeentregado" class="Tablas" type="text" id="chequeentregado" value="<?=$te->cheque ?>" style="width:100px; text-align:right; background-color:#FFFF99;" readonly="" /></td>
                <td width="93">Total Entregado: </td>
                <td width="111"><input name="totalentregado" class="Tablas" type="text" id="totalentregado" value="<?=$te->entregado ?>" style="width:100px; text-align:right; background-color:#FFFF99;" readonly="" /></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" style="width:300px; font-size:14px; color:#000000;" id="nota">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td width="154" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte()"></div></td>
                <td width="143" align="right"><img src="../img/detallado.png" style="cursor:pointer" onclick="imprimirDetalle()" /></td>
                <td width="92" align="right"><div class="ebtn_guardar" onclick="if(document.getElementById('dia_anterior').value=='SI'){validarDiaAnterior('1');}else{obtenerLiquidacionDepositos('1')}"></div></td>
                <td width="133" align="right"><div class="ebtn_Cierre_Definitivo" onclick="if(document.getElementById('dia_anterior').value=='SI'){validarDiaAnterior('cierre');}else{obtenerLiquidacionDepositos('cierre')}"></div></td>
                <td width="101" align="right"><div class="ebtn_nuevo" onclick="mens.show('C','perdera la informacion capturada ¿Desea continuar?','','','document.form1.submit();')"></div></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
  </table>
</form>
</body>
</html>
