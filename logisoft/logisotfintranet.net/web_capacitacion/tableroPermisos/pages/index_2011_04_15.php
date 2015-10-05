<? 	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT if(prefijo='MAT','EMP',prefijo) prefijo, idsucursal FROM catalogosucursal ORDER BY prefijo";
	$r = mysql_query($s,$l) or die($s);
	$paraConvertidor="";
	while($f=mysql_fetch_object($r)){
		$paraConvertidor .= (($paraConvertidor!="")?",":"")."'".$f->prefijo."':'".$f->idsucursal."'";
	}
	
	$s = "SELECT * FROM consultaconvenios WHERE fecha=CURDATE()";
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)==0){
		$s = "INSERT INTO consultaconvenios (fecha) VALUES (CURDATE())";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE generacionconvenio SET estadoconvenio = 'EXPIRADO' WHERE CURRENT_DATE > vigencia";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE propuestaconvenio SET estadopropuesta='EXPIRADO' WHERE CURDATE() > vigencia";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE entregasespecialesead SET estado = 0 WHERE CURDATE() > fechaespecial";
		mysql_query($s,$l) or die($s);
		
		
		#actualizar la tabla de los clientes
		$s = "UPDATE losclientes lc
		SET lc.convenio = 'NO';";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE losclientes lc
		INNER JOIN generacionconvenio gc ON lc.id = gc.idcliente AND gc.estadoconvenio = 'ACTIVADO'
		SET lc.convenio = 'SI';";
		mysql_query($s,$l) or die($s);
		
		#para cambiar la comision de los clientes cada ves que se cumpla un año a la anual segun lo configurado
		$s = "UPDATE catalogocliente cc
		INNER JOIN configuradorgeneral cg
		SET cc.comision = cg.comisionanual, cambiado = 'S'
		WHERE DATEDIFF(CURRENT_DATE,cc.fecharegistro)>=730 AND cambiado = 'N'";
		mysql_query($s,$l) or die($s);
	}
	
	switch (date('w')){
		case 0: $dia = "Domingo "; break; 
		case 1: $dia = "Lunes "; break; 
		case 2:	$dia = "Martes "; break; 
		case 3:	$dia = "Miercoles "; break; 
		case 4:	$dia = "Jueves "; break; 
		case 5:	$dia = "Viernes "; break; 
		case 6: $dia = "Sabado "; break; 
	}
	
	switch (date('n')){		
		case 1: $mes = "Enero"; break; 
		case 2:	$mes = "Febrero"; break; 
		case 3: $mes = "Marzo"; break; 
		case 4: $mes = "Abril"; break; 
		case 5: $mes = "Mayo"; break; 
		case 6: $mes = "Junio"; break; 
		case 7: $mes = "Julio"; break; 
		case 8: $mes = "Agosto"; break; 
		case 9: $mes = "Septiembre"; break; 
		case 10: $mes = "Octubre"; break;
		case 11: $mes = "Noviembre"; break; 
		case 12: $mes = "Diciembre"; break;  
	}
	
	$fecha = $dia.date('d')." de ".$mes." del ".date('Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Guía ventanilla | PMM</title>
	<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.1.custom.css" />
    <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
    <link href="css_nm/reseter.css" rel="stylesheet" type="text/css" />
    <link href="css_nm/style.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
	<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
	<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
    <link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
    
    <script type="text/javascript" src="../../javascript/ajaxIndex.js"></script>
<!--[if lte IE 6]><link href="css/styleie6.css" rel="stylesheet" type="text/css" /><![endif]--> 
<script>
		//valores para convertir la guia anterior en la nueva
		var paraConvertidor = {<?=$paraConvertidor?>};
		String.prototype.pad = function(l, s, t){
			return s || (s = " "), (l -= this.length) > 0 ? (s = new Array(Math.ceil(l / s.length)
				+ 1).join(s)).substr(0, t = !t ? l : t == 1 ? 0 : Math.ceil(l / 2))
				+ this + s.substr(0, l - t) : this;
		};
		//*****************************************************
		var v_pestanas = Array(true,false,false,false,false);
		
		
		function mostrarModulo(modulo){
			if(modulo==1){
				abrirVentanaFija('../../guias/localizadorGuia.php', 580, 370, 'ventana', 'Localizador Guías');				
			}else if(modulo==2){
				abrirVentanaFija('../../catalogos/colonias/consultaColonia.php', 700, 500, 'ventana', 'Buscador de Colonias');
			}
		}		
		
		function cerrarVentana(){
			confirmar("Desea cerrar el tablero?","Atencion!","cerrar()","");
		}
		
		function cerrar(){
			consultaTexto('respuestaCerrar','index_con.php?accion=2');
		}
		
		function respuestaCerrar(){
			window.close();
		}

		$(document).ready(function(){
			$("#accordion").accordion({
				header: "h3",
				autoHeight: false,
				collapsible: true,
				animated: 'bounceslide',
				icons: { 'header': '', 'headerSelected': '' }
			});
			
			$("span[class='ui-icon']").attr("class","");
		});
		
		function seleccionarTab(index){
			var npagina = "pagina";
			var ntab	= "pest";
			var dlab	= "textoPestana";
			var pestanas= 5;
			
			if(v_pestanas[index]==false){			
				v_pestanas[index] = true;
				var_dir = document.all[npagina+index].src;
				document.all[npagina+index].src = '';
				document.all[npagina+index].src = var_dir;
			}
			
			for(var i=0; i<pestanas; i++){
				if(index!=i){
					document.all[npagina+i].style.display='none';
					document.all[ntab+i].className='';
				}
			}
			
			document.all[npagina+index].style.display='';
			document.all[ntab+index].className='active';
			
		}
		
		/****************************/
		
		var historial	= Array();
		var idsasolicitar = "";
		var var_pagina;
		var var_script;
		var var_nombre;
		//modulos que ocupan inicio de dia y cierre de dia
		var var_idmodulos = ",74,103,106,24,144,140,236,274,98,78,246,128,126,82,238,239,235,83,";
		var var_idmodulo="";
		
        function mostrarPagina(pagina,script,nombre,idmodulo){
            if(pagina=='ACCESO DENEGADO'){
                alerta3("Usted no tiene permiso para acceder a este modulo","Atencion!");
			}else{
				var_pagina 		= pagina;
				var_script 		= script;
				var_nombre 		= nombre;
				var_idmodulo 	= idmodulo;
				consultaTexto('resVerificacionInicio','verificarDia.php');
			}
        }
    	
		
		function validarPaginas(){
			var paginaframe = String();
			try{
				if( devolverFrame().location.href.indexOf("&mirandom")>-1){
					paginaframe = devolverFrame().location.href.split("&mirandom")[0];
				}else if( devolverFrame().location.href.indexOf("?mirandom")>-1){
					paginaframe = devolverFrame().location.href.split("?mirandom")[0];
				}else{
					paginaframe = devolverFrame().location.href;
				}
			}catch(e){
				asignarPagina("about:blank");
			}
			
			if(paginaframe.indexOf('web_pruebas')>-1){
				var pos = paginaframe.indexOf('web_pruebas')+11;
			}else if(paginaframe.indexOf('web_capacitacion')>-1){
				var pos = paginaframe.indexOf('web_capacitacion')+16;
			}else{
				var pos = paginaframe.indexOf('web')+3;
			}
			
			if(pos == 2){
				paginaframe="";
			}else{
				paginaframe = "../.."+paginaframe.substring(pos,paginaframe.length);
			}			
			
			//alert(paginaframe+"-"+var_pagina);
			document.getElementById('div_ubicacion').innerHTML = "";
			document.getElementById('div_ubicacion').innerHTML = "Estas en: " + var_nombre;
			
			if((var_pagina != paginaframe || var_pagina == paginaframe) && var_pagina.split("?")[0].indexOf(paginaframe.split("?")[0])>-1 && var_script!="" && paginaframe!=""){
				try{
					ejecutarScriptPagina(var_script);
				}catch(e){
					asignarPagina(var_pagina);
				}
			}else{
				if(document.all.modificando.value==1){
					confirmar("Al cambiar de m&oacute;dulo se perder&aacute;n los datos no guardados \n&iquest;Des&eacute;a continuar?","&iexcl;Atenci&oacute;n!","mostrarLaPagina()","");
				}else{	
					asignarPagina(var_pagina);						
				}
			}
		}
		
		function resVerificacionInicio(res){
			//alert(res);
			if(var_idmodulos.indexOf(","+var_idmodulo+",")<0){
				validarPaginas();
			}else{
				var obj = eval(res);
				if(obj.sucursal!=1){
					if(var_pagina.indexOf("/iniciocaja.php")>-1 && obj.iniciodia==0){
						alerta3("Debe iniciar Dia para iniciar la caja","&iexcl;Atencion!");
						return false;
					}
					if(var_pagina.indexOf("../../Caja/")>-1){
						validarPaginas();
						return false;
					}else{
						if(obj.cerrarondia==1){
							alerta3(((obj.usuarios!="")? "El o Los siguientes empleados no han hecho el cierre de caja definitivo: "+obj.usuarios +", no se ha cerrado el dia anterior, "+((obj.cerraronprincipal==1)?"ni caja principal, ":"")+"es necesario cerrarlos para poder continuar. Dias sin cerrar: "+obj.dias :"No se ha cerrado el dia anterior, "+((obj.cerraronprincipal==1)?"ni caja principal, ":"")+"es necesario cerrarlos para poder continuar. Dias sin cerrar: "+obj.dias ),"&iexcl;Atencion!");
							ponerPestanas('pestana.php');
							return false;
						}
						
						if(obj.cerraronprincipal==1){
							alerta3("No se ha cerrado la caja principal del dia anterior, es necesario cerrarlos para poder continuar.","&iexcl;Atencion!");
							ponerPestanas('pestana.php');
							return false;
						}				
						
						if(obj.usuarios!=""){						
							alerta3("El o Los siguientes empleados no han hecho el cierre de caja definitivo: "+obj.usuarios,"&iexcl;Atencion!");
							ponerPestanas('pestana.php');
							return false;
						}
						
						if(obj.cierredia==1){
							alerta3("El dia actual ya ha sido cerrado","&iexcl;Atencion!");
							ponerPestanas('pestana.php');
							return false;
						}
					}
				}
				if(var_pagina.indexOf("/iniciocaja.php")>-1 && obj.iniciodia==0){
					alerta3("Debe iniciar Dia para iniciar la caja","&iexcl;Atencion!");
					return false;
				}
				if(obj.iniciodia==0 && obj.iniciocaja==0){
					if(var_pagina.indexOf("../../Caja/")>-1){
						validarPaginas();
					}else{
						alerta3("Para poder trabajar estos modulos, debe iniciar el Dia","&iexcl;Atencion!");
					}
				}else if(obj.iniciodia==1 && obj.iniciocaja==0){				
					if((var_pagina.indexOf("../../guias/guia.php")>-1 ||  
						var_pagina.indexOf("../../guiasempresariales/guiasempresariales.php")>-1 ||
					   var_pagina.indexOf("../../creditoycobranza/abonodecliente.php")>-1 ||
					   var_pagina.indexOf("../../creditoycobranza/liquidacionCobranza.php")>-1 ||
					   var_pagina.indexOf("../../entregas/entregaocurre.php")>-1 ||
					   var_pagina.indexOf("../../entregas/liquidaciondemercancia.php")>-1 ||
					   var_pagina.indexOf("../../entregas/devyliqautomatica.php")>-1)
					   && obj.iniciocaja==0){
						alerta3("Para poder trabajar estos modulos, debe iniciar la caja","&iexcl;Atencion!");
					}else{
						validarPaginas();
					}
				}else{
					validarPaginas();
				}
			}
		}
		/*
		}else if(var_pagina.indexOf("../../entregas/entregaocurre.php")>-1  && obj.iniciocajaocurre==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja Ocurre","&iexcl;Atencion!");
				}else if(var_pagina.indexOf("../../creditoycobranza/abonodecliente.php")>-1 && obj.iniciocajaabonocliente==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja Abono Cliente","&iexcl;Atencion!");
		*/
		function mostrarLaPagina(){
			asignarPagina(var_pagina);
			document.all.modificando.value=0;
		}
		
		window.onload = function (){
			consultaTexto("ponerDatos","index_con.php?accion=1&ids="+idsasolicitar+"&azar="+Math.random());
			<?
				$s = "select password from catalogoempleado where id = '$_SESSION[IDUSUARIO]'";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				if($f->password==1234){
					?>
					alerta3("Por favor actualice su contraseña por seguridad","Atencion!");
					<?
				}
			?>
		}
		
		function ponerDatos(datos){
			//alerta3(datos,"");
			try{
				var arreDatos = eval(datos);
			}catch(e){
				alerta3(datos,"¡ATENCION!");
				return false;
			}
			for(var i=0; i<arreDatos.length;i++){
				//alert(arreDatos[i].campoid+" - "+arreDatos[i].valor);
				document.getElementById(arreDatos[i].campoid).innerHTML = arreDatos[i].valor;
				document.getElementById("ocu_"+arreDatos[i].campoid).value = arreDatos[i].valor;
			}
			setTimeout('buscar()',60000);
		}
		
		function buscar(){
			consultaTexto("ponerDatos","index_con.php?accion=1&ids="+idsasolicitar+"&azar="+Math.random());
		}
		
		function buscarlaguia(valor){
			if(valor.length<=10){
				valor = paraConvertidor[valor.substring(0,3)]+valor.substring(3,valor.length).pad(9,"0",0)+"A";
				$('#laguia').val(valor);
			}
			
			if(valor.substring(0,3)=="999"){//GUIAS EMPRESARIALES
				if(devolverIframe().href.indexOf('/guiasempresariales.php')>-1){
					devolverFrame().buscarUnaGuia(valor);	
				}else{
					asignarPagina("../../guiasempresariales/guiasempresariales.php?funcion2=buscarUnaGuia('"+valor+"')");
				}
			
			}else if(valor.substring(0,3)=="888"){//GUIAS CORREO INTERNO
				if(devolverIframe().href.indexOf('/correointerno.php')>-1){
					devolverFrame().buscarUnaGuia(valor);	
				}else{
					asignarPagina("../../guias/correointerno.php?funcion2=buscarUnaGuia('"+valor+"')");
				}
			}else if(valor.substring(0,3)=="777"){//DEVOLUCION GUIA
				consultaTexto("cargarDev","index_con.php?accion=3&folio="+valor);			
			}else{//GUIAS EMPRESARIALES
				if(devolverIframe().href.indexOf('/guia.php')>-1){
					devolverFrame().buscarUnaGuia(valor);	
				}else{
					asignarPagina("../../guias/guia.php?funcion2=buscarUnaGuia('"+valor+"')");
				}
			}
		}
		
		function cargarDev(datos){
			if(datos.split(",")[0].indexOf('V')>-1){
				var pag = "/guia.php";
				var pag2 = "../../guias/guia.php?funcion2=buscarUnaGuia('"+datos.split(",")[1]+"')";
			}else{
				var pag = "/guiasempresariales.php";
				var pag2 = "../../guiasempresariales/guiasempresariales.php?funcion2=buscarUnaGuia('"+datos.split(",")[1]+"')";
			}
			
			if(devolverIframe().href.indexOf(pag)>-1){
				devolverFrame().buscarUnaGuia(datos.split(",")[1]);	
			}else{
				asignarPagina(pag2);
			}
		}
		
		
		function mostrarMonitor(){
			asignarPagina("../tableros/tablero/Tablero.html");
		}
		
		function cambiarSucursal(valor){
			consultaTexto("cambioSucursal","cambiarSucursal.php?sucursal="+valor+"&val="+Math.random());
		}
		
		function cambioSucursal(datos){
			if(datos.indexOf("ok")>-1){
			}
		}
		
		function cerrarVentana(){
			confirmar("Desea cerrar el tablero?","Atencion!","cerrar()","");
		}
		
		function cerrar(){
			consultaTexto('respuestaCerrar','index_con.php?accion=2');
		}
		
		function respuestaCerrar(){
			window.close();
		}
		
		function ponerPestanas(direccion){
			var npagina = "pagina";
			var pestanas= 5;
			
			for(var i=0; i<pestanas;i++){
				document.all[npagina+i].src=direccion+((direccion.indexOf("?")>-1)?"&":"?")+"mirandom="+Math.random();				
			}
		}
		
		function asignarPagina(direccion){
			var npagina = "pagina";
			var pestanas= 5;
			
			for(var i=0; i<pestanas;i++){
				if(document.all[npagina+i].style.display!='none'){
					document.all[npagina+i].src=direccion+((direccion.indexOf("?")>-1)?"&":"?")+"mirandom="+Math.random();
					break;
				}
			}
		}
		
		function ejecutarScriptPagina(script){
			for(var i=0; i<pestanas;i++){
				if(document.all[npagina+i].style.display!='none'){
					script.replace("frames[0]","frames["+i+"]");
					eval(script);
					break;
				}
			}
		}
		
		function asignarSiguientePagina(direccion){
			var npagina = "pagina";
			var pestanas= 5;
			
			for(var i=0; i<pestanas;i++){
				if(document.all[npagina+i].style.display!='none'){
						if(i==4)
							var idpagina = 0;
						else 
							var idpagina = i+1;
						document.all[npagina+idpagina].src=direccion+((direccion.indexOf("?")>-1)?"&":"?")+"mirandom="+Math.random();
						seleccionarTab(idpagina);
					break;
				}
			}
		}
		
		function devolverFrame(){
			var npagina = "pagina";
			var pestanas= 5;
			for(var i=0; i<pestanas;i++){
				if(document.all[npagina+i].style.display!='none'){
					return frames[i];
				}
			}
		}
		
		function devolverIframe(){
			var npagina = "pagina";
			var pestanas= 5;
			for(var i=0; i<pestanas;i++){
				if(document.all[npagina+i].style.display!='none'){
					return frames[i].document.location;
				}
			}
		}
		
		function devolverFrameScript(scripts){
			var npagina = "pagina";
			var pestanas= 5;
			for(var i=0; i<pestanas;i++){
				if(document.all[npagina+i].style.display!='none'){
					return scripts.replace("frames[0]","frames["+i+"]");
				}
			}
		}
		
		function moverHistorial(pagina){
			historial[7]=historial[6];
			historial[6]=historial[5];
			historial[5]=historial[4];
			historial[4]=historial[3];
			historial[3]=historial[2];
			historial[2]=historial[1];
			historial[1]=pagina;
		}
		
		function checarParaCierreDia(){
			/*
			(0) Foraneas Autorizaciones para sustituir 				-- 105
			(0) Foraneas Autorizadas para cancelar 					-- 106
			(0) Locales Autorizadas para Cancelar 					-- 103
			//(0) Liquidaciones EAD									--  92
			(1) Evaluaciones Pendientes de Generar Guía				-- 128
			(2) Guias Empresariales Aplicadas pendientes Evaluar	-- 270
			(3) Evaluaciones Pendientes de Generar Guía 			-- 246
			(4) Guias Autorizadas para Sustituir 					-- 244
			(5) Guias Pendientes Por Cancelar 						-- 242
			(6) Guias Pendientes por Sustituir						-- 243
			(0)Relación Cobranza Pendiente de Liquidar				-- 302
			*/
			var mensajes = "";			
			
			if(document.getElementById("ocu_con105") != null && document.getElementById("ocu_con105").value!=0 && document.getElementById("ocu_con105").value!=""){
				mensajes += "("+document.getElementById("ocu_con105").value+") Foraneas Autorizaciones para sustituir<br>";
			}
			if(document.getElementById("ocu_con106") != null && document.getElementById("ocu_con106").value!=0 && document.getElementById("ocu_con106").value!=""){
				mensajes += "("+document.getElementById("ocu_con106").value+") Foraneas Autorizadas para cancelar<br>";
			}
			if(document.getElementById("ocu_con103") != null && document.getElementById("ocu_con103").value!=0 && document.getElementById("ocu_con103").value!=""){
				mensajes += "("+document.getElementById("ocu_con103").value+") Locales Autorizadas para Cancelar<br>";
			}
			/*if(document.getElementById("ocu_con92") != null && document.getElementById("ocu_con92").value!=0 && document.getElementById("ocu_con92").value!=""){
				mensajes += "("+document.getElementById("ocu_con92").value+") Liquidaciones EAD<br>";
			}*/
			/*if(document.getElementById("ocu_con128") != null && document.getElementById("ocu_con128").value!=0 && document.getElementById("ocu_con128").value!=""){
				mensajes += "("+document.getElementById("ocu_con128").value+") Evaluaciones Pendientes de Generar Guía [Ventanilla]<br>";
			}*/
			if(document.getElementById("ocu_con270") != null && document.getElementById("ocu_con270").value!=0 && document.getElementById("ocu_con270").value!=""){
				mensajes += "("+document.getElementById("ocu_con270").value+") Guias Empresariales Aplicadas pendientes Evaluar<br>";
			}
			/*if(document.getElementById("ocu_con246") != null && document.getElementById("ocu_con246").value!=0 && document.getElementById("ocu_con246").value!=""){
				mensajes += "("+document.getElementById("ocu_con246").value+") Evaluaciones Pendientes de Generar Guía [Empresarial]<br>";
			}*/
			if(document.getElementById("ocu_con244") != null && document.getElementById("ocu_con244").value!=0 && document.getElementById("ocu_con244").value!=""){
				mensajes += "("+document.getElementById("ocu_con244").value+") Guias Autorizadas para Sustituir<br>";
			}
			if(document.getElementById("ocu_con242") != null && document.getElementById("ocu_con242").value!=0 && document.getElementById("ocu_con242").value!=""){
				mensajes += "("+document.getElementById("ocu_con242").value+") Guias Pendientes Por Cancelar<br>";
			}
			if(document.getElementById("ocu_con243") != null && document.getElementById("ocu_con243").value!=0 && document.getElementById("ocu_con243").value!=""){
				mensajes += "("+document.getElementById("ocu_con243").value+") Guias Pendientes por Sustituir<br>";
			}
			if(document.getElementById("ocu_con302") != null && document.getElementById("ocu_con302").value!=0 && document.getElementById("ocu_con302").value!=""){
				mensajes += "("+document.getElementById("ocu_con302").value+") Relación Cobranza Pendiente de Liquidar<br>";
			}
			
			return mensajes;
		}
		
		function verNoticias(){
			asignarPagina("https://pmmnews.pmmintranet.net/?usuario=<?=$_SESSION['IDUSUARIO'] ?> ");
		}
		
		function mostrarPaginaTablero(pagina){
			var dir = "";
			switch(pagina){
				case "GeReCo": //generar relacioncobranza
					dir = "../../creditoycobranza/relaciondecobranzaaldia.php";
					break;
				case "CaPr": //Caja Principal
					dir = "../../Caja/cierrePrincipal.php";
					break;
				case "ReMaCo": //Reporte Maestro de Cobranza
					dir = "../../general/cobranza/reporteCobranza.php";
					break;
				case "DeBa": //Depositos Bancos
					dir = "../../creditoycobranza/depositos.php";
					break;
				case "CaCh": //Caja Chica
					dir = "../../Caja/cierrecaja.php";
					break;
				case "LiCo": //Liquidacion Cobranza
					dir = "../../creditoycobranza/liquidacionCobranza.php";
					break;
				case "Fact": //Facturacion
					dir = "../../facturacion/Facturacion.php";
					break;
				case "ReMe": //Recepcion Mercancia
					dir = "../../recepcion/recepcionMercancia.php";
					break;
				case "EmMe": //Embarque Mercancia
					dir = "../../embarque/embarquedemercancia.php";
					break;
				case "ReEad": //Reparto EAD
					dir = "../../entregas/repartoMercanciaEad.php";
					break;
				case "Reco": //Recoleccion
					dir = "../../recoleccion/rec.php?obtenerSesion=si&accion=";
					break;
				case "GuVe": //Guia Ventanilla
					dir = "../../guias/guia.php?funcion2=mostrarEvaluaciones()";
					break;
				case "GuEm": //Guia Empresarial
					dir = "../../guiasempresariales/guiasempresariales.php?funcion2=mostrarEvaluaciones()";
					break;
				case "EnOc": //Entrega Ocurre
					dir = "../../almacen/entregademercanciaocurre.php";
					break;
				case "GeCo": //Generacion Convenio
					dir = "../../convenio/generacionconvenio.php";
					break;
				case "CoGu": //Cotizador Guia
					dir = "../../guias/cotizarguia.php";
					break;
				case "Caja": // Caja
					dir = "../../Caja/cierrecaja.php";
					break;
				case "MePoRe": // Mercancia por recibir
					dir = "../../alerta/mercanciaRecibirSinEmbarcar.php";
					break;
			}
			asignarSiguientePagina(dir);
			//alert(pagina);
		}
		
		function verAyuda(){
			window.open("../../manual/MANUAL_PMM.pdf");
		}
		
		
</script>
</head>

<body>
    <div id="layout" style="height:1100px">
    	<div class="tl-corn"></div><div class="tr-corn"></div><div class="b2-corn"></div>
    	<div class="header"><img src="img_nm/logo.jpg" alt="PMM - Paqueteria y mensajeria en movimiento"/>
        <span class="noticias" style="text-align:right" onclick="verNoticias()">PMM News....</span>
        <span class="fecha" style="text-align:right"><?=$fecha?></span>
        <div class="mnu">
<ul>
            <li class="active" id="pest0" onclick="seleccionarTab(0)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#">Pest 1</a></li>
            <li id="pest1" onclick="seleccionarTab(1)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#">Pest 2</a></li>
            <li id="pest2" onclick="seleccionarTab(2)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#">Pest 3</a></li>
            <li id="pest3" onclick="seleccionarTab(3)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#">Pest 4</a></li>
            <li id="pest4" onclick="seleccionarTab(4)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#">Pest 5</a></li>
        </ul>
        </div>
        <div style="width:211px; position:absolute; left: 796px; top: 36px; height: 23px;">
        Buscar guia: 
          <input type="text" value="" style="width:115px; text-transform:uppercase" id="laguia" onkeypress="if(event.keyCode==13){buscarlaguia(this.value)}" /></div>
    </div>
    	<div class="canvas">
     <div class="opt-menu"> 
     	<?
		$s = "SELECT pt.nombre, ce.sucursal
		FROM permisos_grupos AS pt
		INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
		WHERE ce.id = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if($f->nombre=="DIRECCION GENERAL" || $f->nombre=="ADMINISTRADOR GRAL" || $f->sucursal == 1){
		?>
		  <select name="sucursal" style="width:150px; font-family:Verdana, Geneva, sans-serif; font-size:12px" onchange="cambiarSucursal(this.value); var pg = devolverIframe().href;  devolverIframe().href='';  devolverIframe().href=pg;">
		<?
			$s = "select * from catalogosucursal order by descripcion";
			$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
		?>
				<option <? if($_SESSION[IDSUCURSAL]==$f->id){echo "selected";}?> value="<?=$f->id?>"><?=strtoupper(utf8_encode($f->descripcion))?></option>		
		<?
			}
			
		?>
			</select>
	 <? }else{
			$s = "select descripcion from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			echo strtoupper(utf8_encode($f->descripcion));
		}
		
	?>
     
     <div id="accordion">
                        <div>
                <?
			  	$idsconsulta = "";
			  
			  	$s = "SELECT permisos_tablerogpo.nombre AS grupo, permisos_tablerogpo.id
				FROM permisos_tablerogpo
				INNER JOIN permisos_modulos ON permisos_tablerogpo.id = permisos_modulos.grupo and permisos_modulos.status=1
				INNER JOIN permisos_permisos AS pp ON permisos_modulos.id = pp.idmodulo
				INNER JOIN permisos_grupospermisos AS pgp ON pp.id = pgp.idpermiso
				INNER JOIN catalogoempleado AS ce ON pgp.idgrupo = ce.grupo
				WHERE ce.id = $_SESSION[IDUSUARIO]
				GROUP BY permisos_tablerogpo.id
				ORDER BY grupo ASC";
				$r = mysql_query($s,$l) or die($s);
				while($f = mysql_fetch_object($r)){
			  	?>
                            <h3><a href="#" style="font-size:60%;"><?=$f->grupo?></a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                         <?
							$s = "SELECT pm.id, SUBSTRING(pm.nombre,4) AS nombre, 
							IF(ISNULL(pep.idempleado),'ACCESO DENEGADO',pm.vinculo) AS vinculo, pm.script, pm.tipo
							FROM permisos_modulos AS pm
							INNER JOIN permisos_permisos AS pp ON pm.id = pp.idmodulo AND pp.descripcion='Acceso' and pm.status=1
							INNER JOIN permisos_grupospermisos AS pgp ON pp.id = pgp.idpermiso
							LEFT JOIN permisos_empleadospermisos AS pep ON pp.id = pep.idpermiso AND pep.idempleado = $_SESSION[IDUSUARIO]
							INNER JOIN catalogoempleado AS ce ON pgp.idgrupo = ce.grupo
							WHERE pm.grupo = $f->id AND ce.id = $_SESSION[IDUSUARIO] order by pm.nombre";
							$rx = mysql_query($s,$l) or die($s);
							while($fx = mysql_fetch_object($rx)){
								$fx->nombre = utf8_decode($fx->nombre);
								if($fx->tipo=="C"){
									$idsconsulta .= ($idsconsulta!="")?",":"";
									$idsconsulta .= $fx->id;
								}
						?>
                                  <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('<?=$fx->vinculo?>','<?=$fx->script?>', '<?=$f->grupo?> / <?=str_replace("()","",utf8_encode($fx->nombre))?>','<?=$fx->id?>')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con<?=$fx->id?>" value=""/>
                                   -<? if($fx->tipo=="C"){ ?>(<strong id='con<?=$fx->id?>'></strong>)<? } ?> <?=str_replace("()","",utf8_encode($fx->nombre))?></a></li>
                        <?
							}
						?></ul>
                          </div>
              <?
				}
			  ?>
                        </div>
                        <script>
		var idsasolicitar = "<?=$idsconsulta?>";
	</script>

    </div>
     </div>
     <div class="content" style="width:830px">
        <input type="hidden" name="modificando2" value="0" />
         <input type="hidden" name="modificando" value="0" />
        <div class="breadcrumbs" id="div_ubicacion" style="width:660px">Est&aacute;s en:</div>
        <ul class="tools" style="width:126px">
        <li><a href="#" class="buscar" title="Buscar" onclick="abrirVentanaFija('buscador.php?funcion=mostrarModulo', 400, 200, 'ventana', 'Localizador Guías');">Buscar</a></li>
        <li><a href="#" class="configurar" title="Configurar" onclick="abrirVentanaFija('configuracion.php', 400, 400, 'ventana', 'Configuraci&oacute;n');">Configurar</a></li>
        <li><a href="#" class="ayuda" title="Ayuda" onclick="verAyuda()">Salir</a></li>
        <li><a href="#" class="salir" title="Salir" onclick="cerrarVentana()">Salir</a></li>
        </ul>
     </div>
     <div style="float:left; margin:10px 0px 10px 0px; height:770px; width:845px;">
     <?
			/*$s = "SELECT ce.grupo FROM permisos_grupos AS pt
			INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
			WHERE ce.id = $_SESSION[IDUSUARIO]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			
			if($f->grupo == 4 || $f->grupo == 5 || $f->grupo == 10){
				$pagina1 = "tableroDireccion.php";
				$pagina2 = "../tableros/TableroVentas/TableroVentas.html";
				$pagina3 = "../tableros/TableroCobranza/TableroCobranza.html";
				$pagina4 = "../tableros/TableroIngresos/TableroIngresos.html";
				$pagina5 = "pestana.php";
			}else{
				$pagina1 = "../../guias/guia.php";
				$pagina2 = "pestana.php";
				$pagina3 = "pestana.php";
				$pagina4 = "pestana.php";
				$pagina5 = "pestana.php";
			}*/
			
			$s = "SELECT IFNULL(MAX(id),0) as id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = CURDATE()";
			$ri = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($ri);
			
			$s = "SELECT iniciodia FROM cierredia WHERE iniciodia='".$f->id."'";
			$rc= mysql_query($s,$l) or die($s);
			
			$s = "SELECT * FROM cierrecaja WHERE tipocierre='definitivo' AND fechacierre=CURDATE() AND usuariocaja=$_SESSION[IDUSUARIO] and sucursal = $_SESSION[IDSUCURSAL] ";
			$t = mysql_query($s,$l) or die($s);
			
			$s = "SELECT * FROM cierreprincipal WHERE fechacierre = CURDATE() AND sucursal = $_SESSION[IDSUCURSAL] AND estado = 'CERRADA'";
			$p = mysql_query($s,$l) or die($s);
			
			if(mysql_num_rows($rc)>0 || mysql_num_rows($t)>0 || mysql_num_rows($p)>0){			
				$f->pestana1 = 'pestana.php';
				$f->pestana2 = 'pestana.php';
				$f->pestana3 = 'pestana.php';
				$f->pestana4 = 'pestana.php';
				$f->pestana5 = 'pestana.php';
			}else{
				$s = "SELECT pestana1,pestana2,pestana3,pestana4,pestana5 FROM configuradorpestanas WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);	
				}else{
					$f->pestana1 = 'pestana.php';
					$f->pestana2 = 'pestana.php';
					$f->pestana3 = 'pestana.php';
					$f->pestana4 = 'pestana.php';
					$f->pestana5 = 'pestana.php';
				}
			}
		?>
        <iframe onblur="document.all.modificando.value=1;" name="pagina0" id="pagina0" scrolling="auto" 
        align="top" width="850" height="770" src="<?=$f->pestana1?>" frameborder="0" style="display:''"></iframe>
      <iframe onblur="document.all.modificando.value=1;" name="pagina1" id="pagina1" scrolling="auto" 
        align="top" width="850" height="770" src="<?=$f->pestana2?>" frameborder="0" style="display:none"></iframe>
      <iframe onblur="document.all.modificando.value=1;" name="pagina2" id="pagina2" scrolling="auto" 
        align="top" width="850" height="770" src="<?=$f->pestana3?>" frameborder="0" style="display:none"></iframe>
      <iframe onblur="document.all.modificando.value=1;" name="pagina3" id="pagina3" scrolling="auto" 
        align="top" width="850" height="770" src="<?=$f->pestana4?>" frameborder="0" style="display:none"></iframe>
      <iframe onblur="document.all.modificando.value=1;" name="pagina4" id="pagina4" scrolling="auto" 
        align="top" width="850" height="770" src="<?=$f->pestana5?>" frameborder="0" style="display:none"></iframe>
     
     </div>
    </div>
    </div>
         <p style="text-align:center; color:#fff; font-size:10px;">PMM &copy; 2010 Powered by Empresaria</p>
</div>

</body>
</html>