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
		var paraConvertidor = {'ACP':'100','AGS':'120','CDO':'160','CLN':'140','CMO':'222','COR':'111','DGO':'220','EMP':'999','ESC':'240','FRE':'260','GDL':'280','GMC':'300','GPV':'360','GSV':'320','GYM':'340','HMO':'380','LCR':'420','LE1':'401','LEO':'400','LMO':'440','MEX':'480','MRA':'570','MTY':'560','MXL':'490','MY1':'561','MZT':'460','NGL':'600','NVJ':'580','OPE':'0000','PNS':'620','PTV':'640','QRO':'650','RSR':'660','SFR':'410','SLP':'710','SLT':'700','STA':'680','TIJ':'780','TLC':'770','TLQ':'760','TPC':'720','TRN':'740','ZAC':'800','ZPN':'810','ZPT':'820'};
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
			
			if(document.getElementById("ocu_con105") != null && document.getElementById("ocu_con105").value!=0 && document.getElementById("ocu_con105").value!="" && document.all.sucursal.value==1){
				mensajes += "("+document.getElementById("ocu_con105").value+") Foraneas Autorizaciones para sustituir<br>";
			}
			if(document.getElementById("ocu_con106") != null && document.getElementById("ocu_con106").value!=0 && document.getElementById("ocu_con106").value!="" && document.all.sucursal.value==1){
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
			mensajes = "";
			return mensajes;
		}
		
		function verNoticias(){
			asignarPagina("https://pmmnews.pmmintranet.net/?usuario=4 ");
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
        <span class="fecha" style="text-align:right">Sabado 18 de Junio del 2011</span>
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
          <input type="text" id="laguia" style="width:115px; text-transform:uppercase" onkeypress="if(event.keyCode==13){buscarlaguia(this.value)}" value="" maxlength="13" />
        </div>
    </div>
    	<div class="canvas">
     <div class="opt-menu"> 
     			  <select name="sucursal" style="width:150px; font-family:Verdana, Geneva, sans-serif; font-size:12px" onchange="cambiarSucursal(this.value); var pg = devolverIframe().href;  devolverIframe().href='';  devolverIframe().href=pg;">
						<option  value="22">ACAPONETA</option>		
						<option  value="30">AGUASCALIENTES</option>		
						<option  value="5">CD. OBREGON</option>		
						<option  value="46">CENTRO DE MANTENIMIENTO MAZATLAN</option>		
						<option  value="45">CENTRO OPERATIVO REGIONAL MAZATLAN</option>		
						<option  value="3">CULIACAN</option>		
						<option  value="34">DURANGO</option>		
						<option  value="21">ESCUINAPA</option>		
						<option  value="37">FRESNILLO</option>		
						<option  value="25">GUADALAJARA</option>		
						<option  value="35">GUADALUPE VICTORIA</option>		
						<option  value="15">GUAMUCHIL</option>		
						<option  value="16">GUASAVE</option>		
						<option  value="18">GUAYMAS</option>		
						<option  value="6">HERMOSILLO</option>		
						<option  value="14">LA CRUZ</option>		
						<option  value="23">LAS PENAS</option>		
						<option  value="10">LEON</option>		
						<option  value="28">LEON 1</option>		
						<option  value="4">LOS MOCHIS</option>		
						<option  value="2">MAZATLAN</option>		
						<option  value="43">MEXICALI</option>		
						<option  value="13">MEXICO</option>		
						<option  value="12">MONTERREY</option>		
						<option  value="39">MONTERREY 1</option>		
						<option  value="42">MORELIA</option>		
						<option  value="17">NAVOJOA</option>		
						<option  value="7">NOGALES</option>		
						<option  value="1">OFICINA MATRIZ</option>		
						<option  value="47">OPERADORES</option>		
						<option  value="24">PUERTO VALLARTA</option>		
						<option  value="29">QUERETARO</option>		
						<option  value="20">ROSARIO</option>		
						<option  value="36">SALTILLO</option>		
						<option  value="40">SAN FRANCISCO DEL RINCON</option>		
						<option  value="33">SAN LUIS POTOSI</option>		
						<option  value="19">SANTA ANA</option>		
						<option  value="8">TEPIC</option>		
						<option  value="44">TIJUANA</option>		
						<option selected value="9">TLAQUEPAQUE</option>		
						<option  value="41">TOLUCA</option>		
						<option  value="11">TORREON</option>		
						<option  value="38">ZACATECAS</option>		
						<option  value="26">ZAPOPAN</option>		
						<option  value="27">ZAPOTLANEJO</option>		
					</select>
	      
     <div id="accordion">
                        <div>
                                            <h3><a href="#" style="font-size:60%;">AGENDA DE TRABAJO</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../carteraMorosa/actividadesUsuario.php','', 'AGENDA DE TRABAJO / Actividades del Usuario','261')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con261" value=""/>
                                   - Actividades del Usuario</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/bitacoraDeActividades.php','', 'AGENDA DE TRABAJO / Bitacora del Empleado','323')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con323" value=""/>
                                   - Bitacora del Empleado</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">ALMACEN</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../almacen/entradaysalidasdealmacen.php','', 'ALMACEN / Entrada/Salida Mercancia de Almacén','72')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con72" value=""/>
                                   - Entrada/Salida Mercancia de Almacén</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../almacen/traspasodemercanciaentrealmacenes.php','', 'ALMACEN / Traspasar Mercancia Almacén','75')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con75" value=""/>
                                   - Traspasar Mercancia Almacén</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../almacen/traspasarmercancia.php','', 'ALMACEN / Traspasar Mercancia CORM','76')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con76" value=""/>
                                   - Traspasar Mercancia CORM</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../almacen/mercanciadetraspasopendienteporrecibir.php','', 'ALMACEN / Mercancía Traspaso Pendiente por Recibir','77')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con77" value=""/>
                                   - Mercancía Traspaso Pendiente por Recibir</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../almacen/entregademercanciaocurre.php','', 'ALMACEN / Entregas Ocurre Almacén','74')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con74" value=""/>
                                   - Entregas Ocurre Almacén</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../carteraMorosa/inventarioMoroso.php','', 'ALMACEN / Inventario Moroso','71')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con71" value=""/>
                                   - Inventario Moroso</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../almacen/inventarioMercancia.php','', 'ALMACEN / Reporte Inventario','69')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con69" value=""/>
                                   - Reporte Inventario</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../almacen/auditoriasucursalnuevo.php','', 'ALMACEN / Auditoría Almacén','70')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con70" value=""/>
                                   - Auditoría Almacén</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">BITACORA</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../corm/bitacoraSalida.php','', 'BITACORA / Bitacora Salida','65')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con65" value=""/>
                                   - Bitacora Salida</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../corm/PreLiquidaciondeBitacora.php','', 'BITACORA / Preliquidacion Bitacora','66')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con66" value=""/>
                                   - Preliquidacion Bitacora</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../corm/ComprobantedeLiquidaciondeBitacora.php','', 'BITACORA / Liquidacion Bitacora','67')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con67" value=""/>
                                   - Liquidacion Bitacora</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">CAJA</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/iniciodia.php','', 'CAJA / Inicio Dia','82')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con82" value=""/>
                                   - Inicio Dia</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/iniciocaja.php','', 'CAJA / Inicio Caja','238')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con238" value=""/>
                                   - Inicio Caja</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/cierrePrincipal.php','', 'CAJA / Caja Principal','234')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con234" value=""/>
                                   - Caja Principal</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/cierrecaja.php','', 'CAJA / Cierre Caja','239')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con239" value=""/>
                                   - Cierre Caja</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/cierrePrincipal.php','', 'CAJA / Cierre de Caja Principal','235')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con235" value=""/>
                                   - Cierre de Caja Principal</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/cierredia.php','', 'CAJA / Cierre Dia','83')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con83" value=""/>
                                   - Cierre Dia</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/reporteVerificarCaja.php?titulo=REPORTE CIERRE DE CAJA','', 'CAJA / Reporte Cierre de Caja','258')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con258" value=""/>
                                   - Reporte Cierre de Caja</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/reporteDiasNoCerrados.php','', 'CAJA / Modulo de Cierres','303')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con303" value=""/>
                                   - Modulo de Cierres</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/reporteCierreCaja.php','', 'CAJA / Reporte Ingresado vs Registrado','304')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con304" value=""/>
                                   - Reporte Ingresado vs Registrado</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../creditoycobranza/depositos.php','', 'CAJA / Depositos','306')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con306" value=""/>
                                   - Depositos</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">CAJA CHICA</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cajachica/gastospendientesaautorizar.php','', 'CAJA CHICA / Gastos Pendientes Autorizar','85')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con85" value=""/>
                                   - Gastos Pendientes Autorizar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cajachica/reporteconsolidadogastosgastoschica.php','', 'CAJA CHICA / Reporte Consolidado Caja Chica','232')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con232" value=""/>
                                   - Reporte Consolidado Caja Chica</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cajachica/reportecheques.php','', 'CAJA CHICA / Reporte de Cheques para Imprimir','233')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con233" value=""/>
                                   - Reporte de Cheques para Imprimir</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cajachica/reportesgastosprepagados.php','', 'CAJA CHICA / Reporte de Gastos Prepagados','231')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con231" value=""/>
                                   - Reporte de Gastos Prepagados</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cajachica/reportargastos.php','', 'CAJA CHICA / Reporte Gastos','225')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con225" value=""/>
                                   - Reporte Gastos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cajachica/reportegastoscajachica.php','', 'CAJA CHICA / Reporte Gastos Caja Chica','226')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con226" value=""/>
                                   - Reporte Gastos Caja Chica</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cajachica/reportegastoscredito.php','', 'CAJA CHICA / Reporte Gastos Crédito','227')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con227" value=""/>
                                   - Reporte Gastos Crédito</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cajachica/reportesgastosprepagados.php','', 'CAJA CHICA / Reporte Gastos Prepagado Caja Chica','228')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con228" value=""/>
                                   - Reporte Gastos Prepagado Caja Chica</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">CANCELACIONES</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/guiasCanceladas.php','', 'CANCELACIONES / Guias Canceladas','108')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con108" value=""/>
                                   -(<strong id='con108'></strong>) Guias Canceladas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/guia.php?funcion2=mostrarGuiasPendientesCancelar()','frames[0].mostrarGuiasPendientesCancelar()', 'CANCELACIONES / Locales Autorizadas para Cancelar','103')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con103" value=""/>
                                   -(<strong id='con103'></strong>) Locales Autorizadas para Cancelar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/guia.php?funcion2=mostrarGuiasPCS()','', 'CANCELACIONES / Cancelaciones Foraneas Pendientes Autorizar','105')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con105" value=""/>
                                   -(<strong id='con105'></strong>) Cancelaciones Foraneas Pendientes Autorizar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/guia.php?funcion2=mostrarGuiasAPS()','', 'CANCELACIONES / Sustituciones Autorizadas Pendientes Guardar','106')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con106" value=""/>
                                   -(<strong id='con106'></strong>) Sustituciones Autorizadas Pendientes Guardar</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">CAT</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cat/centroAtencionTelefonica.php','', 'CAT / Registro CAT','42')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con42" value=""/>
                                   - Registro CAT</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cat/bitacoraQuejas.php','', 'CAT / CAT Bitacora Quejas','43')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con43" value=""/>
                                   - CAT Bitacora Quejas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cat/moduloQuejasDanosFaltantes.php','', 'CAT / Quejas Daños y Faltantes','46')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con46" value=""/>
                                   - Quejas Daños y Faltantes</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cat/bitacoraQuejasDanosFaltantes.php','', 'CAT / Bitacora Quejas Daños y Faltantes','45')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con45" value=""/>
                                   - Bitacora Quejas Daños y Faltantes</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recepcion/historicoDanosFaltantes.php','', 'CAT / Historico de daños y faltantes','44')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con44" value=""/>
                                   - Historico de daños y faltantes</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/cancelacionesPendienteAutorizar.php','', 'CAT / Cancelaciones de Guias','50')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con50" value=""/>
                                   -(<strong id='con50'></strong>) Cancelaciones de Guias</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/guiasExtraviadas.php','', 'CAT / Guias Extraviadas','51')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con51" value=""/>
                                   -(<strong id='con51'></strong>) Guias Extraviadas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cat/reporteCat.php','', 'CAT / Reporte CAT','279')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con279" value=""/>
                                   - Reporte CAT</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">CATALOGOS</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/configuradores/asignarPrecintos.php','', 'CATALOGOS / Asignacion Precintos','68')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con68" value=""/>
                                   - Asignacion Precintos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/cliente/client.php','', 'CATALOGOS / Cliente ','192')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con192" value=""/>
                                   - Cliente </a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/cliente/tipocliente.php','', 'CATALOGOS / Tipo Cliente','193')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con193" value=""/>
                                   - Tipo Cliente</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/cliente/prospecto.php','', 'CATALOGOS / Prospecto','194')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con194" value=""/>
                                   - Prospecto</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/empleado/catalogoempleado.php','', 'CATALOGOS / Empleado','195')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con195" value=""/>
                                   - Empleado</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/sucursal/catalogosucursal.php','', 'CATALOGOS / Sucursal','196')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con196" value=""/>
                                   - Sucursal</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/unidad/catalogotipounidad.php','', 'CATALOGOS / Tipo Unidad','197')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con197" value=""/>
                                   - Tipo Unidad</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/unidad/catalogounidad.php','', 'CATALOGOS / Unidad','198')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con198" value=""/>
                                   - Unidad</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/sucursal/catalogoservicio.php','', 'CATALOGOS / Servicios','199')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con199" value=""/>
                                   - Servicios</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/sucursal/catalogodescripcion.php','', 'CATALOGOS / Descripción','200')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con200" value=""/>
                                   - Descripción</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/sucursal/catalogodestino.php','', 'CATALOGOS / Destinos','201')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con201" value=""/>
                                   - Destinos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/colonias/catalogopais.php','', 'CATALOGOS / País','202')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con202" value=""/>
                                   - País</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/colonias/catalogoestado.php','', 'CATALOGOS / Estados','203')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con203" value=""/>
                                   - Estados</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/colonias/catalogomunicipio.php','', 'CATALOGOS / Municipio/Delegación','204')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con204" value=""/>
                                   - Municipio/Delegación</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/colonias/catalogopoblacion.php','', 'CATALOGOS / Población','205')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con205" value=""/>
                                   - Población</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/colonias/catalogocolonias.php','', 'CATALOGOS / Colonias','206')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con206" value=""/>
                                   - Colonias</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/colonias/catalogocodigopostal.php','', 'CATALOGOS / Código Postal','207')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con207" value=""/>
                                   - Código Postal</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/motivos/CatalogoMotivos.php','', 'CATALOGOS / Motivos','208')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con208" value=""/>
                                   - Motivos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/conceptos/CatalogoConceptos.php','', 'CATALOGOS / Concepto gastos','209')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con209" value=""/>
                                   - Concepto gastos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/proveedores/catalogoproveedores.php','', 'CATALOGOS / Proveedor','210')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con210" value=""/>
                                   - Proveedor</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/proveedores/catalogotipoproveedor.php','', 'CATALOGOS / Tipo Proveedor','211')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con211" value=""/>
                                   - Tipo Proveedor</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/empleado/Catalogopuesto.php','', 'CATALOGOS / Puestos','212')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con212" value=""/>
                                   - Puestos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/sector/CatalogoSector.php','', 'CATALOGOS / Sector','213')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con213" value=""/>
                                   - Sector</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/rutas/catalogoRutas.php','', 'CATALOGOS / Rutas','214')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con214" value=""/>
                                   - Rutas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/conceptos/catalogogastos_liquidacionbitacora.php','', 'CATALOGOS / Gastos Liquidación Bitácora','215')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con215" value=""/>
                                   - Gastos Liquidación Bitácora</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/bancos/catalogoBanco.php','', 'CATALOGOS / Bancos','257')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con257" value=""/>
                                   - Bancos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/configuradores/asignarPapeletas.php','', 'CATALOGOS / Asignacion Papeletas de Recolección','264')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con264" value=""/>
                                   - Asignacion Papeletas de Recolección</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/configuradores/asignarBolsas.php','', 'CATALOGOS / Asignación Bolsas de Empaque','266')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con266" value=""/>
                                   - Asignación Bolsas de Empaque</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/presupuesto/presupuesto.php','', 'CATALOGOS / Presupuesto','278')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con278" value=""/>
                                   - Presupuesto</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/ajuste/tipoajuste.php','', 'CATALOGOS / Tipo de Ajuste','281')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con281" value=""/>
                                   - Tipo de Ajuste</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">CLIENTES</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/cliente/client.php','', 'CLIENTES / Directorio de clientes','5')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con5" value=""/>
                                   - Directorio de clientes</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/colonias/catalogocodigopostal.php','', 'CLIENTES / Agregar CP','6')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con6" value=""/>
                                   - Agregar CP</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/colonias/catalogocolonias.php','', 'CLIENTES / Agregar Colonias','7')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con7" value=""/>
                                   - Agregar Colonias</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guiasempresariales/solicitudguiasempresariales.php','', 'CLIENTES / Solicitud Guia Empresarial','8')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con8" value=""/>
                                   - Solicitud Guia Empresarial</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/generacionconvenio.php','', 'CLIENTES / Convenios','12')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con12" value=""/>
                                   - Convenios</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/propuestaconvenio.php?funcion=mostrarPropuestasPendientesAceptar()','frames[0].mostrarPropuestasPendientesAceptar()', 'CLIENTES / Propuestas Convenios Pend. Autorizar','13')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con13" value=""/>
                                   -(<strong id='con13'></strong>) Propuestas Convenios Pend. Autorizar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/generacionconvenio.php?funcion=mostrarPropuestasPendientes()','frames[0].mostrarPropuestasPendientes()', 'CLIENTES / Convenios Pendientes Autorizar','14')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con14" value=""/>
                                   -(<strong id='con14'></strong>) Convenios Pendientes Autorizar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/generacionconvenio.php?funcion=mostrarConveniosPendientesAct()','frames[0].mostrarConveniosPendientesAct()', 'CLIENTES / Convenios Pendientes Activar','15')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con15" value=""/>
                                   -(<strong id='con15'></strong>) Convenios Pendientes Activar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/conveniosporVencer.php','', 'CLIENTES / Convenios por Vencer','16')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con16" value=""/>
                                   -(<strong id='con16'></strong>) Convenios por Vencer</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../facturacion/Facturacion.php','', 'CLIENTES / Facturacion','24')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con24" value=""/>
                                   - Facturacion</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cat/centroAtencionTelefonica.php','', 'CLIENTES / CAT','27')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con27" value=""/>
                                   - CAT</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">COBRANZA</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../creditoycobranza/abonodecliente.php','', 'COBRANZA / Abono Cliente','144')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con144" value=""/>
                                   - Abono Cliente</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../creditoycobranza/relaciondecobranzaaldia.php','', 'COBRANZA / Relacion Cobranza','141')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con141" value=""/>
                                   - Relacion Cobranza</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../creditoycobranza/liquidacionCobranza.php','', 'COBRANZA / Liquidacion Cobranza','140')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con140" value=""/>
                                   - Liquidacion Cobranza</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../carteraMorosa/carteraMorosa.php','', 'COBRANZA / Cartera Morosa','260')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con260" value=""/>
                                   - Cartera Morosa</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/cobranza30dias.php','', 'COBRANZA / Cobranza > 30 Días','142')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con142" value=""/>
                                   - Cobranza > 30 Días</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/cobranza60dias.php','', 'COBRANZA / Cobranza > 60 Días','143')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con143" value=""/>
                                   - Cobranza > 60 Días</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/facturasCobranza.php','', 'COBRANZA / Facturas para Cobranza','139')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con139" value=""/>
                                   -(<strong id='con139'></strong>) Facturas para Cobranza</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tareasProgramadas/facturasarevision.php','', 'COBRANZA / Facturas a Revisión','138')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con138" value=""/>
                                   -(<strong id='con138'></strong>) Facturas a Revisión</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/relacionCobranzaPendienteLiquidar.php','', 'COBRANZA / Relación Cobranza Pendiente de Liquidar','302')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con302" value=""/>
                                   -(<strong id='con302'></strong>) Relación Cobranza Pendiente de Liquidar</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">CONFIGURADORES</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../cajachica/configuradordepositoscajachica.php','', 'CONFIGURADORES / Deposito Caja Chica','216')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con216" value=""/>
                                   - Deposito Caja Chica</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/configuradores/configuradorFoliosPrecintos.php','', 'CONFIGURADORES / Precintos','217')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con217" value=""/>
                                   - Precintos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/configuradores/configuradorgeneral.php','', 'CONFIGURADORES / General','218')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con218" value=""/>
                                   - General</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recoleccion/configuradorRecolecciones.php','', 'CONFIGURADORES / Configurador Recolecciones Programadas','219')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con219" value=""/>
                                   - Configurador Recolecciones Programadas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/configuradores/configuradorservicio.php','', 'CONFIGURADORES / Servicios','221')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con221" value=""/>
                                   - Servicios</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recoleccion/configuradorRecolecciones.php','', 'CONFIGURADORES / Recolecciones Programadas','222')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con222" value=""/>
                                   - Recolecciones Programadas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/configuradores/configuradorFoliosPapeletas.php','', 'CONFIGURADORES / Papeletas de Recolección','265')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con265" value=""/>
                                   - Papeletas de Recolección</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/configuradores/configuradorFoliosBolsas.php','', 'CONFIGURADORES / Bolsas de Empaque','267')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con267" value=""/>
                                   - Bolsas de Empaque</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">CONTABILIDAD</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../creditoycobranza/generarPoliza.php','', 'CONTABILIDAD / Generación de Poliza','319')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con319" value=""/>
                                   - Generación de Poliza</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../creditoycobranza/generarDepositos.php','', 'CONTABILIDAD / Generación de Depositos','320')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con320" value=""/>
                                   - Generación de Depositos</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">CONVENIOS</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/propuestaconvenio.php','', 'CONVENIOS / Propuestas de Convenios','28')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con28" value=""/>
                                   - Propuestas de Convenios</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/generacionconvenio.php','', 'CONVENIOS / Convenio','32')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con32" value=""/>
                                   - Convenio</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/generacionconvenio.php?funcion=mostrarConveniosPendientesAct()','frames[0].mostrarConveniosPendientesAct()', 'CONVENIOS / Pendientes de Activar','37')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con37" value=""/>
                                   -(<strong id='con37'></strong>) Pendientes de Activar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/generacionconvenio.php?funcion=mostrarConveniosPendientesImp()','frames[0].mostrarConveniosPendientesImp()', 'CONVENIOS / Pendientes por Imprimir','36')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con36" value=""/>
                                   -(<strong id='con36'></strong>) Pendientes por Imprimir</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/conveniosporVencer.php','', 'CONVENIOS / Pendientes por Vencer','38')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con38" value=""/>
                                   -(<strong id='con38'></strong>) Pendientes por Vencer</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/propuestaconvenio.php?funcion=mostrarPropuestasPendientesAceptar()','frames[0].mostrarPropuestasPendientesAceptar()', 'CONVENIOS / Propuesta Pendientes de Autorizar','30')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con30" value=""/>
                                   -(<strong id='con30'></strong>) Propuesta Pendientes de Autorizar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/generacionconvenio.php?funcion=mostrarPropuestasPendientes()','frames[0].mostrarPropuestasPendientes()', 'CONVENIOS / Propuestas Pend. de Generar Convenio','31')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con31" value=""/>
                                   -(<strong id='con31'></strong>) Propuestas Pend. de Generar Convenio</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/bitacoraPropuestasRenovacionB.php','', 'CONVENIOS / Bitacora de Propuestas Renovada','276')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con276" value=""/>
                                   - Bitacora de Propuestas Renovada</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/registrarusuariosclientes.php','', 'CONVENIOS / Asignar usuario PMM en tu empresa','307')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con307" value=""/>
                                   - Asignar usuario PMM en tu empresa</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">CREDITO</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/solicitudContratoAperturaCredito.php','', 'CREDITO / Solicitud apertura credito','240')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con240" value=""/>
                                   - Solicitud apertura credito</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/traspasarCreditoConCre.php','', 'CREDITO / Traspaso Credito','249')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con249" value=""/>
                                   - Traspaso Credito</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/traspasarCargo.php','', 'CREDITO / Traspaso Cargo','248')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con248" value=""/>
                                   - Traspaso Cargo</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/creditoLineaSaturada.php','', 'CREDITO / Créditos con Limites Saturados','113')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con113" value=""/>
                                   -(<strong id='con113'></strong>) Créditos con Limites Saturados</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/solicitudContratoAperturaCredito.php?funcion2=mostrarSolicitudCreditoPendientes()','parent.frames[0].mostrarSolicitudCreditoPendientes()', 'CREDITO / Solicitud de Crédito Pendiente por Autorizar','111')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con111" value=""/>
                                   -(<strong id='con111'></strong>) Solicitud de Crédito Pendiente por Autorizar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/solicitudContratoAperturaCredito.php?funcion2=mostrarSolicitudCreditoPendientesActivar()','parent.frames[0].mostrarSolicitudCreditoPendientesActivar()', 'CREDITO / Solicitud de Crédito Pendiente por Activar','112')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con112" value=""/>
                                   -(<strong id='con112'></strong>) Solicitud de Crédito Pendiente por Activar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/reporteTraspasosPendientes.php','', 'CREDITO / Traspaso de cargo pendientes de autorizar','282')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con282" value=""/>
                                   -(<strong id='con282'></strong>) Traspaso de cargo pendientes de autorizar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../creditoycobranza/reporte_TraspasoCargo.php','', 'CREDITO / Reporte de Traspaso de Cargo','321')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con321" value=""/>
                                   - Reporte de Traspaso de Cargo</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">EMBARQUES</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../embarque/embarquedemercanciaautomatico.php','', 'EMBARQUES / Embarque Automático','61')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con61" value=""/>
                                   - Embarque Automático</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../embarque/embarquedemercancia.php','', 'EMBARQUES / Embarque Manual','62')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con62" value=""/>
                                   - Embarque Manual</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../embarque/programacionEmbarqueDiaria.php','', 'EMBARQUES / Salida de unidad','60')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con60" value=""/>
                                   - Salida de unidad</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/guiasNoEmbarcadas.php','', 'EMBARQUES / Guias no Embarcadas','63')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con63" value=""/>
                                   -(<strong id='con63'></strong>) Guias no Embarcadas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tareasProgramadas/guiasportrasbordar.php','', 'EMBARQUES / Guias por Trasbordar','64')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con64" value=""/>
                                   -(<strong id='con64'></strong>) Guias por Trasbordar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../embarque/embarquedemercanciaEspecial.php','', 'EMBARQUES / Embarque Contingencias','316')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con316" value=""/>
                                   - Embarque Contingencias</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">ENTREGAS EAD</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/clienteCorporativo.php','', 'ENTREGAS EAD / Cliente Corporativo','89')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con89" value=""/>
                                   - Cliente Corporativo</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../entregas/repartoMercanciaEadAutomatico.php','', 'ENTREGAS EAD / Reparto EAD Automatico','94')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con94" value=""/>
                                   - Reparto EAD Automatico</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../entregas/repartoMercanciaEad_30.php','', 'ENTREGAS EAD / Reparto EAD Manual','95')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con95" value=""/>
                                   - Reparto EAD Manual</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../entregas/devoluciondemercanciaaalmacen.php','', 'ENTREGAS EAD / Devolucion Mercancia EAD','96')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con96" value=""/>
                                   - Devolucion Mercancia EAD</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../entregas/liquidaciondemercancia.php','', 'ENTREGAS EAD / Liquidacion EAD','236')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con236" value=""/>
                                   - Liquidacion EAD</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/guiasSalidaAlmanceReparto.php','', 'ENTREGAS EAD / Guias con Salida Almacen Sin Reparto','90')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con90" value=""/>
                                   - Guias con Salida Almacen Sin Reparto</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tareasProgramadas/guiasparaentregasadomicilio.php','', 'ENTREGAS EAD / Guias para EAD','97')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con97" value=""/>
                                   - Guias para EAD</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/entregasAtrasadas.php','', 'ENTREGAS EAD / Entregas Atrasadas','93')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con93" value=""/>
                                   -(<strong id='con93'></strong>) Entregas Atrasadas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tareasProgramadas/liquidacionesead.php','', 'ENTREGAS EAD / Liquidaciones EAD','92')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con92" value=""/>
                                   -(<strong id='con92'></strong>) Liquidaciones EAD</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../entregas/entregasEspeciales.php','', 'ENTREGAS EAD / Entregas especiales EAD','273')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con273" value=""/>
                                   - Entregas especiales EAD</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../entregas/devyliqautomatica.php','', 'ENTREGAS EAD / Dev y Liq Automática','274')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con274" value=""/>
                                   - Dev y Liq Automática</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/guiassinrutaclientescorporativo.php','', 'ENTREGAS EAD / Guias sin Ruta Cliente corporativo','109')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con109" value=""/>
                                   - Guias sin Ruta Cliente corporativo</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/entregasEspeciales.php','', 'ENTREGAS EAD / Reporte Entregas Especiales','296')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con296" value=""/>
                                   -(<strong id='con296'></strong>) Reporte Entregas Especiales</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">ENTREGAS OCURRE</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../entregas/entregaocurre.php','', 'ENTREGAS OCURRE / Entregas ocurre en Ventanilla','98')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con98" value=""/>
                                   - Entregas ocurre en Ventanilla</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../almacen/entregademercanciaocurre.php','', 'ENTREGAS OCURRE / Entregas ocurre en Almacen','99')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con99" value=""/>
                                   - Entregas ocurre en Almacen</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/entregasAtrasadasOcurre.php','', 'ENTREGAS OCURRE / Entregas Atrasadas','100')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con100" value=""/>
                                   -(<strong id='con100'></strong>) Entregas Atrasadas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tareasProgramadas/guiasparaentregasocurre.php','', 'ENTREGAS OCURRE / Guias para Entregas Ocurre','101')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con101" value=""/>
                                   -(<strong id='con101'></strong>) Guias para Entregas Ocurre</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">FACTURACION</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../facturacion/Facturacion.php','', 'FACTURACION / Facturación','78')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con78" value=""/>
                                   - Facturación</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../facturacion/FacturacionGen.php','', 'FACTURACION / Facturación General','263')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con263" value=""/>
                                   - Facturación General</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/facturasCanceladas.php','', 'FACTURACION / Facturas Canceladas','79')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con79" value=""/>
                                   -(<strong id='con79'></strong>) Facturas Canceladas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/guiaspendientesfacturar.php','', 'FACTURACION / Guias Ventanilla Pend. de Facturar','80')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con80" value=""/>
                                   -(<strong id='con80'></strong>) Guias Ventanilla Pend. de Facturar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../facturacion/reporteMensualFacturacion.php','', 'FACTURACION / Reporte Mensual de Facturacion','313')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con313" value=""/>
                                   - Reporte Mensual de Facturacion</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../facturacion/Facturacion_paginado.php','', 'FACTURACION / Facturación Paginado','324')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con324" value=""/>
                                   - Facturación Paginado</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">GUIA EMPRESARIAL</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../evaluacion/EvaluacionDeMercanciaCliente.php?funcion=mostrarGuias()','frames[0].mostrarGuias()', 'GUIA EMPRESARIAL / Guias Empresariales Web Aplicadas pendientes Evaluar','270')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con270" value=""/>
                                   -(<strong id='con270'></strong>) Guias Empresariales Web Aplicadas pendientes Evaluar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../evaluacion/EvaluacionDeMercancia.php?vieneempresarial=1','', 'GUIA EMPRESARIAL / Evaluación de Mercancia','295')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con295" value=""/>
                                   - Evaluación de Mercancia</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guiasempresariales/guiasempresariales.php?funcion2=mostrarEvaluaciones()','frames[0].mostrarEvaliaciones()', 'GUIA EMPRESARIAL / Evaluaciones Pendientes de Generar Guía','246')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con246" value=""/>
                                   -(<strong id='con246'></strong>) Evaluaciones Pendientes de Generar Guía</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guiasempresariales/solicitudguiasempresariales.php','', 'GUIA EMPRESARIAL / Solicitud Guía Empresarial','130')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con130" value=""/>
                                   - Solicitud Guía Empresarial</a></li>
                                                          <li style="margin:0px;">

                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guiasempresariales/asignacionguiasempresariales.php','', 'GUIA EMPRESARIAL / Asignacion Folios Guias Empresariales','133')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con133" value=""/>
                                   - Asignacion Folios Guias Empresariales</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guiasempresariales/imprecionguiasempresariales.php','', 'GUIA EMPRESARIAL / Impresión de Folios','134')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con134" value=""/>
                                   - Impresión de Folios</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guiasempresariales/liberacionguiasempresariales.php','', 'GUIA EMPRESARIAL / Liberación Folios Guías Empresariales','135')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con135" value=""/>
                                   - Liberación Folios Guías Empresariales</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guiasempresariales/liberacionguiasempresarialesnoutilizadas.php','', 'GUIA EMPRESARIAL / Liberación Folios Guías Empresariales No Utiliz','136')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con136" value=""/>
                                   - Liberación Folios Guías Empresariales No Utiliz</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guiasempresariales/asignacionguiasempresariales.php?funcion=mostrarClienteSolicitud(1)','frames[0].mostrarClienteSolicitud(1)', 'GUIA EMPRESARIAL / Solicitud Guías Empresariales Pend. Asig. Folio','132')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con132" value=""/>
                                   -(<strong id='con132'></strong>) Solicitud Guías Empresariales Pend. Asig. Folio</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guiasempresariales/solicitudguiasempresariales.php?funcion2=mostrarSoliGuiasEmp(2)','frames[0].mostrarSoliGuiasEmp(2)', 'GUIA EMPRESARIAL / Solicitud Guías Empresariales Pend. Autorizar','131')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con131" value=""/>
                                   -(<strong id='con131'></strong>) Solicitud Guías Empresariales Pend. Autorizar</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">GUIAS</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../evaluacion/EvaluacionDeMercancia.php?vieneempresarial=1','', 'GUIAS / Evaluación de Mercancia','127')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con127" value=""/>
                                   - Evaluación de Mercancia</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/guia.php?funcion2=mostrarEvaluaciones()','frames[0].mostrarEvaluaciones()', 'GUIAS / Evaluaciones Pendientes de Generar Guía','128')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con128" value=""/>
                                   -(<strong id='con128'></strong>) Evaluaciones Pendientes de Generar Guía</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/cotizarguia.php','', 'GUIAS / Cotizador de Guías','114')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con114" value=""/>
                                   - Cotizador de Guías</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/correointerno.php','', 'GUIAS / Correo Interno','245')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con245" value=""/>
                                   - Correo Interno</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../almacen/devolucionGuia.php','', 'GUIAS / Devolución de Guías','116')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con116" value=""/>
                                   - Devolución de Guías</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/guia.php?funcion2=mostrarGuiasPendientesCancelar()','frames[0].mostrarGuiasPendientesCancelar()', 'GUIAS / Guias Pendientes Por Cancelar','242')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con242" value=""/>
                                   -(<strong id='con242'></strong>) Guias Pendientes Por Cancelar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/guia.php?funcion2=mostrarGuiasPCS()','frames[0].mostrarGuiasPCS()', 'GUIAS / Cancelaciones Foraneas Pendientes Autorizar','243')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con243" value=""/>
                                   -(<strong id='con243'></strong>) Cancelaciones Foraneas Pendientes Autorizar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/guia.php?funcion2=mostrarGuiasAPS()','frames[0].mostrarGuiasAPS()', 'GUIAS / Sustituciones Autorizadas Pendientes Guardar','244')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con244" value=""/>
                                   -(<strong id='con244'></strong>) Sustituciones Autorizadas Pendientes Guardar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/historialGuiasCanceladas.php','', 'GUIAS / Historial de Guías Canceladas','122')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con122" value=""/>
                                   - Historial de Guías Canceladas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/historialGuiasTransito.php','', 'GUIAS / Historial de Guías en Tránsito','121')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con121" value=""/>
                                   - Historial de Guías en Tránsito</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/historialGuiasEntregadas.php','', 'GUIAS / Historial de Guías Entregadas','120')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con120" value=""/>
                                   - Historial de Guías Entregadas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/reporteHistorialGuias.php?titulo=REPORTE HISTORIAL DE GUIAS AL DIA','', 'GUIAS / Historial Guías al Día','223')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con223" value=""/>
                                   - Historial Guías al Día</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../notadecredito/notacredito.php','', 'GUIAS / Nota Crédito','126')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con126" value=""/>
                                   - Nota Crédito</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../declarado/valordeclarado.php','', 'GUIAS / Valor Declarado','125')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con125" value=""/>
                                   - Valor Declarado</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../reportes/cancelacionYsustitucion.php','', 'GUIAS / Reporte de Cancelacion y Sustitucion','271')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con271" value=""/>
                                   - Reporte de Cancelacion y Sustitucion</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../guias/guia.php?funcion2=ponerSobre()','', 'GUIAS / Guia envio sobre','317')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con317" value=""/>
                                   - Guia envio sobre</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">HISTORIAL DE CLIENTE</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../historialCliente/pages/indexNuevo.php','', 'HISTORIAL DE CLIENTE / Historial De Cliente','275')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con275" value=""/>
                                   - Historial De Cliente</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">PROSPECTO</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../catalogos/cliente/prospecto.php','', 'PROSPECTO / Directorio de Prospectos','1')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con1" value=""/>
                                   - Directorio de Prospectos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../convenio/propuestaconvenio.php?funcion=mostrarPropuestasPendientesAceptar()','frames[0].mostrarPropuestasPendientesAceptar()', 'PROSPECTO / Propuestas Pendientes Autorizar','2')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con2" value=""/>
                                   -(<strong id='con2'></strong>) Propuestas Pendientes Autorizar</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">RECEPCIONES</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recepcion/programacionRecepcionDiaria.php','', 'RECEPCIONES / Llegada de unidad','52')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con52" value=""/>
                                   - Llegada de unidad</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recepcion/recepcionMercancia.php','', 'RECEPCIONES / Recepcion de Mercancia Manual','53')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con53" value=""/>
                                   - Recepcion de Mercancia Manual</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recepcion/recepcionMercanciaAutomatico.php','', 'RECEPCIONES / Recepcion de Mercancia Automatica','54')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con54" value=""/>
                                   - Recepcion de Mercancia Automatica</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recepcion/historicoDanosFaltantes.php','', 'RECEPCIONES / Reporte Historico Daños y Faltantes','55')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con55" value=""/>
                                   - Reporte Historico Daños y Faltantes</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/guiasConDanos.php','', 'RECEPCIONES / Guias con Daños','57')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con57" value=""/>
                                   -(<strong id='con57'></strong>) Guias con Daños</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/guiasFaltantes.php','', 'RECEPCIONES / Guias con Faltantes','56')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con56" value=""/>
                                   -(<strong id='con56'></strong>) Guias con Faltantes</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/guiasSobrantes.php','', 'RECEPCIONES / Guias con Sobrantes','58')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con58" value=""/>
                                   -(<strong id='con58'></strong>) Guias con Sobrantes</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tareasProgramadas/guiasporrecibir.php','', 'RECEPCIONES / Guias por Recibir','59')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con59" value=""/>
                                   -(<strong id='con59'></strong>) Guias por Recibir</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/mercanciaRecibirSinEmbarcar.php','', 'RECEPCIONES / Mercancía por recibir sin embarcar','269')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con269" value=""/>
                                   -(<strong id='con269'></strong>) Mercancía por recibir sin embarcar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recepcion/recepcionMercanciaEspecial.php','', 'RECEPCIONES / Recepcion de contingencias','315')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con315" value=""/>
                                   - Recepcion de contingencias</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">RECOLECCIONES</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recoleccion/rec.php?obtenerSesion=si&accion=','', 'RECOLECCIONES / Registrar Recoleccion','39')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con39" value=""/>
                                   - Registrar Recoleccion</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recoleccion/recoleccionMercancia.php','', 'RECOLECCIONES / Agenda de Recolecciones','40')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con40" value=""/>
                                   - Agenda de Recolecciones</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/recoleccionesAtrasadas.php','', 'RECOLECCIONES / Recolecciones Atrasadas','41')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con41" value=""/>
                                   -(<strong id='con41'></strong>) Recolecciones Atrasadas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../alerta/recoleccionesSinReprogramar.php','', 'RECOLECCIONES / Recolecciones sin reprogramar','262')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con262" value=""/>
                                   -(<strong id='con262'></strong>) Recolecciones sin reprogramar</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../recoleccion/reporteProductividad.php','', 'RECOLECCIONES / Reporte de Productividad','277')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con277" value=""/>
                                   - Reporte de Productividad</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">REPORTES</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../almacen/inventarioMercancia.php','', 'REPORTES / Inventario','289')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con289" value=""/>
                                   - Inventario</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/reporteCorteDiario.php','', 'REPORTES / Corte Diario','290')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con290" value=""/>
                                   - Corte Diario</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../reportesWeb/relacionEnvios.php','', 'REPORTES / Relacion de Envios por Cliente','291')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con291" value=""/>
                                   - Relacion de Envios por Cliente</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../reportesWeb/antiguedadSaldo.php','', 'REPORTES / Antigüedad de Saldo','292')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con292" value=""/>
                                   - Antigüedad de Saldo</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../reportesWeb/estadocuenta.php','', 'REPORTES / Estado de Cuenta','293')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con293" value=""/>
                                   - Estado de Cuenta</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../reportesWeb/relacionEmbarque.php','', 'REPORTES / Relación de Embarque','294')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con294" value=""/>
                                   - Relación de Embarque</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../Caja/reporteCorteDiarioDetallado.php','', 'REPORTES / Corte Diario Detallado','305')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con305" value=""/>
                                   - Corte Diario Detallado</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../reportesWeb/guiasceladas.php','', 'REPORTES / Reporte de guias Canceladas','308')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con308" value=""/>
                                   - Reporte de guias Canceladas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../FirmasEmpleado.php','', 'REPORTES / Reporte de Firmas PMMNEWS','309')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con309" value=""/>
                                   - Reporte de Firmas PMMNEWS</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../reportesWeb/reporteProductividad.php','', 'REPORTES / Reporte de productividad','310')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con310" value=""/>
                                   - Reporte de productividad</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../reportesWeb/enviadoyrecibido.php','', 'REPORTES / Reporte de lo enviado y recibido','311')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con311" value=""/>
                                   - Reporte de lo enviado y recibido</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../corm/bitacoraUnidades.php','', 'REPORTES / Tablero de Unidades','318')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con318" value=""/>
                                   - Tablero de Unidades</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">REPORTES MAESTROS</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../general/venta/reporteVentas.php','', 'REPORTES MAESTROS / RM Ventas','145')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con145" value=""/>
                                   - RM Ventas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../general/clientes/reporteClientes.php','', 'REPORTES MAESTROS / RM Clientes','154')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con154" value=""/>
                                   - RM Clientes</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../general/ingresos/principal.php','', 'REPORTES MAESTROS / RM Ingresos','166')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con166" value=""/>
                                   - RM Ingresos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../general/logistica/reporteLogistica.php','', 'REPORTES MAESTROS / RM Logística','175')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con175" value=""/>
                                   - RM Logística</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../general/cobranza/reporteCobranza.php','', 'REPORTES MAESTROS / RM Cobranza','181')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con181" value=""/>
                                   - RM Cobranza</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../general/operaciones/reporteOperaciones.php','', 'REPORTES MAESTROS / RM Operaciones','186')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con186" value=""/>
                                   - RM Operaciones</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../general/vendedores/reporteVendedores.php','', 'REPORTES MAESTROS / RM Vendedores','247')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con247" value=""/>
                                   - RM Vendedores</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../general/concesiones/concesiones.php','', 'REPORTES MAESTROS / Concesiones o Franquicias','284')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con284" value=""/>
                                   - Concesiones o Franquicias</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../general/historial/historialMovimientos.php','', 'REPORTES MAESTROS / Historial de Movimientos','283')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con283" value=""/>
                                   - Historial de Movimientos</a></li>
                        </ul>
                          </div>
                                          <h3><a href="#" style="font-size:60%;">TABLEROS CONTROL</a></h3>
                          <div class="menu_acord_cont" id="accordions" style="padding-left:5px; padding-right:5px;">
                                <ul style="list-style-type:circle; list-style-image:inherit; list-style-position:outside;">
                                                           <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tableroPermisos/tableros/TableroVentas/TableroVentas.html','', 'TABLEROS CONTROL /  Ventas','285')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con285" value=""/>
                                   -  Ventas</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tableroPermisos/tableros/TableroCobranza/TableroCobranza.html','', 'TABLEROS CONTROL /  Cobranza','286')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con286" value=""/>
                                   -  Cobranza</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tableroPermisos/tableros/TableroIngresos/TableroIngresos.html','', 'TABLEROS CONTROL /  Ingresos','287')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con287" value=""/>
                                   -  Ingresos</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tableroPermisos/tableros/tableroPresupuesto/tableroPresupuesto.html','', 'TABLEROS CONTROL /  Ventas diarias contra Presupuesto','288')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con288" value=""/>
                                   -  Ventas diarias contra Presupuesto</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tableroPermisos/tableros/TableroGerenteSucursal/GerenteSucursal.html','', 'TABLEROS CONTROL /  Gerente Sucursal','297')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con297" value=""/>
                                   -  Gerente Sucursal</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tableroPermisos/tableros/creditoCobranza/credito_y_cobranza.html','', 'TABLEROS CONTROL /  Credito y Cobranza','298')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con298" value=""/>
                                   -  Credito y Cobranza</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tableroPermisos/tableros/TableroOperacionesServicios/nuevo.html','', 'TABLEROS CONTROL /  Operaciones y Servicios','299')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con299" value=""/>
                                   -  Operaciones y Servicios</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tableroPermisos/tableros/VentasServiciosCliente/ventas_y_servicio_al_cliente.html','', 'TABLEROS CONTROL /  Ventas y servicio al cliente','300')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con300" value=""/>
                                   -  Ventas y servicio al cliente</a></li>
                                                          <li style="margin:0px;">
                                  <a href="#posicionPagina" onclick="mostrarPagina('../../tableroPermisos/tableros/TableroComparativaAnual/TableroComparativaAnual.html','', 'TABLEROS CONTROL /  Comparativo Anual','301')" 
                                  style="color:#FFF; font-size:60%; font-weight:normal"><input type="hidden" id="ocu_con301" value=""/>
                                   -  Comparativo Anual</a></li>
                        </ul>
                          </div>
                                      </div>
                        <script>
		var idsasolicitar = "108,103,105,106,50,51,13,14,15,16,139,138,302,37,36,38,30,31,113,111,112,282,63,64,93,92,296,100,101,79,80,270,246,132,131,128,242,243,244,2,57,56,58,59,269,41,262";
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
             <iframe onblur="document.all.modificando.value=1;" name="pagina0" id="pagina0" scrolling="auto" 
        align="top" width="850" height="770" src="pestana.php" frameborder="0" style="display:''"></iframe>
      <iframe onblur="document.all.modificando.value=1;" name="pagina1" id="pagina1" scrolling="auto" 
        align="top" width="850" height="770" src="pestana.php" frameborder="0" style="display:none"></iframe>
      <iframe onblur="document.all.modificando.value=1;" name="pagina2" id="pagina2" scrolling="auto" 
        align="top" width="850" height="770" src="pestana.php" frameborder="0" style="display:none"></iframe>
      <iframe onblur="document.all.modificando.value=1;" name="pagina3" id="pagina3" scrolling="auto" 
        align="top" width="850" height="770" src="pestana.php" frameborder="0" style="display:none"></iframe>
      <iframe onblur="document.all.modificando.value=1;" name="pagina4" id="pagina4" scrolling="auto" 
        align="top" width="850" height="770" src="pestana.php" frameborder="0" style="display:none"></iframe>
     
     </div>
    </div>
    </div>
         <p style="text-align:center; color:#fff; font-size:10px;">PMM &copy; 2010 Powered by Empresaria</p>
</div>
 
</body>
</html>
