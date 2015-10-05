<? 	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');

	/*$s = "SELECT * FROM consultaconvenios WHERE fecha=CURDATE()";
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)==0){
		$s = "INSERT INTO consultaconvenios (fecha) VALUES (CURDATE())";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE generacionconvenio SET estadoconvenio = 'EXPIRADO' WHERE CURRENT_DATE > vigencia";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE propuestaconvenio SET estadopropuesta='EXPIRADO' WHERE CURDATE() > vigencia";
		mysql_query($s,$l) or die($s);
	}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	<link rel="stylesheet" href="../javascript/estilosjs/form.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="css/reseter.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.2.custom.css" />
	<link href="../css/styles.css" rel="stylesheet" type="text/css" />	
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
	<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
	<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
    <script type="text/javascript" src="../../javascript/ajax.js"></script>
    <script type="text/javascript" src="../javascript/estilosjs/custom-form-elements.js"></script>
    <link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
    <link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
	<script>
		var historial	= Array();
		var idsasolicitar = "";
		var var_pagina;
		var var_script;
        function mostrarPagina(pagina,script){
            if(pagina=='ACCESO DENEGADO'){
                alerta3("Usted no tiene permiso para acceder a este modulo","Atencion!");
			}else{
				var_pagina = pagina;
				var_script = script;
				consultaTexto('resVerificacionInicio','verificarDia.php');
			}
        }
    	
		
		function validarPaginas(){
			var paginaframe = devolverFrame().location.href;
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
			if((var_pagina != paginaframe || var_pagina == paginaframe) && var_pagina.indexOf(paginaframe)>-1 && var_script!="" && paginaframe!=""){
				eval(var_script);
			}else{
				if(document.all.modificando.value==1){
					confirmar("Al cambiar de m&oacute;dulo se perder&aacute;n los datos no guardados \n&iquest;Des&aacute;a continuar?","&iexcl;Atenci&oacute;n!","mostrarLaPagina()","");
				}else{	
					asignarPagina(var_pagina);						
				}
			}
		}
		
		function resVerificacionInicio(res){		
			var obj = eval(res);
			//alert(obj.iniciodia+"-"+obj.iniciocaja);		
			/*if(obj.iniciodia==0 && obj.iniciocaja==0){
				if(var_pagina.indexOf("../../Caja/")>-1){
					validarPaginas();
				}else{
					alerta3("Para poder trabajar estos modulos, debe iniciar el Dia","&iexcl;Atencion!");
				}
			}else */
						
			/*
			if(obj.iniciodia==1 && obj.iniciocaja==0){				
				if((var_pagina.indexOf("../../guias/guia.php")>-1 ||  
					var_pagina.indexOf("../../guiasempresariales/guiasempresariales.php")>-1) && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja","&iexcl;Atencion!");
				}else{
					validarPaginas();
				}
			}else{
				if((var_pagina.indexOf("../../guias/guia.php")>-1 ||  
					var_pagina.indexOf("../../guiasempresariales/guiasempresariales.php")>-1) && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja","&iexcl;Atencion!");
				}else{
					
				}
			}
			
			*/
			validarPaginas();
			/*if(obj.iniciodia==0 && obj.iniciocaja==0){
				if(var_pagina.indexOf("../../Caja/")>-1){
					validarPaginas();
				}else{
					alerta3("Para poder trabajar estos modulos, debe iniciar el Dia","&iexcl;Atencion!");
				}
			}else if(obj.iniciodia==1){				
				if((var_pagina.indexOf("../../guias/guia.php")>-1 ||  
					var_pagina.indexOf("../../guiasempresariales/guiasempresariales.php")>-1) && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja","&iexcl;Atencion!");
				}else{
					validarPaginas();
				}
			}*/
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
			var stilos = new Custom.init;
			consultaTexto("ponerDatos","index_con.php?accion=1&ids="+idsasolicitar+"&azar="+Math.random());
		}
		
		function ponerDatos(datos){
			//alerta3(datos,"");
			var arreDatos = eval(datos);
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
			if(valor.substring(0,3)=="999"){//GUIAS EMPRESARIALES
				if(devolverIframe().src.indexOf('/guiasempresariales.php')>-1){
					devolverFrame().buscarUnaGuia(valor);	
				}else{
					asignarPagina("../../guiasempresariales/guiasempresariales.php?funcion2=buscarUnaGuia('"+valor+"')");
				}
			
			}else if(valor.substring(0,3)=="888"){//GUIAS CORREO INTERNO
				if(devolverIframe().src.indexOf('/correointerno.php')>-1){
					devolverFrame().buscarUnaGuia(valor);	
				}else{
					asignarPagina("../../guias/correointerno.php?funcion2=buscarUnaGuia('"+valor+"')");
				}
			}else if(valor.substring(0,3)=="777"){//DEVOLUCION GUIA
				if(devolverIframe().src.indexOf('/guia.php')>-1){
					devolverFrame().buscarUnaGuia(valor);	
				}else{
					asignarPagina("../../guias/guia.php?funcion2=buscarUnaGuia('"+valor+"')");
				}			
			}else{//GUIAS EMPRESARIALES
				if(devolverIframe().src.indexOf('/guia.php')>-1){
					devolverFrame().buscarUnaGuia(valor);	
				}else{
					asignarPagina("../../guias/guia.php?funcion2=buscarUnaGuia('"+valor+"')");
				}
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
		
		$(document).ready(function(){
			$("#accordion").accordion({
				header: "h3",
				autoHeight: false,
				collapsible: true
			});
			
			setTimeout(function(){
				$(".mensajeflash").fadeOut("slow", function () {
						$(".flash").remove();
				});
			}, 3000);
		});
			
		function seleccionarTab(index){
			var npagina = "pagina";
			var ntab	= "pest";
			var dlab	= "textoPestana";
			var pestanas= 3;
			
			for(var i=0; i<pestanas; i++){
				if(index!=i){
					document.all[npagina+i].style.display='none';
					document.all[ntab+i].className='desseleccionada';
				}
			}
			
			document.all[npagina+index].style.display='';
			document.all[ntab+index].className='seleccionada';
		}
		
		function asignarPagina(direccion){
			var npagina = "pagina";
			var pestanas= 3;
			
			for(var i=0; i<pestanas;i++){
				if(document.all[npagina+i].style.display!='none'){
						document.all[npagina+i].src=direccion;
					break;
				}
			}
		}
		
		function devolverFrame(){
			var npagina = "pagina";
			var pestanas= 3;
			for(var i=0; i<pestanas;i++){
				if(document.all[npagina+i].style.display!='none'){
					return frames[i];
				}
			}
		}
		
		function devolverIframe(){
			var npagina = "pagina";
			var pestanas= 3;
			for(var i=0; i<pestanas;i++){
				if(document.all[npagina+i].style.display!='none'){
					return document.all[npagina+i];
				}
			}
		}
		
		function devolverFrameScript(scripts){
			var npagina = "pagina";
			var pestanas= 3;
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
			(0) Foraneas Autorizaciones para sustituir 		-- 105
			(0) Foraneas Autorizadas para cancelar 			-- 106
			(0) Locales Autorizadas para Cancelar 			-- 103
			(0) Liquidaciones EAD					--  92
			(1) Evaluaciones Pendientes de Generar Guía		-- 128
			(2) Guias Empresariales Aplicadas pendientes Evaluar	-- 270
			(3) Evaluaciones Pendientes de Generar Guía 		-- 246
			(4) Guias Autorizadas para Sustituir 			-- 244
			(5) Guias Pendientes Por Cancelar 			-- 242
			(6) Guias Pendientes por Sustituir			-- 243
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
			if(document.getElementById("ocu_con92") != null && document.getElementById("ocu_con92").value!=0 && document.getElementById("ocu_con92").value!=""){
				mensajes += "("+document.getElementById("ocu_con92").value+") Liquidaciones EAD<br>";
			}
			if(document.getElementById("ocu_con128") != null && document.getElementById("ocu_con128").value!=0 && document.getElementById("ocu_con128").value!=""){
				mensajes += "("+document.getElementById("ocu_con128").value+") Evaluaciones Pendientes de Generar Guía [Ventanilla]<br>";
			}
			if(document.getElementById("ocu_con270") != null && document.getElementById("ocu_con270").value!=0 && document.getElementById("ocu_con270").value!=""){
				mensajes += "("+document.getElementById("ocu_con270").value+") Guias Empresariales Aplicadas pendientes Evaluar<br>";
			}
			if(document.getElementById("ocu_con246") != null && document.getElementById("ocu_con246").value!=0 && document.getElementById("ocu_con246").value!=""){
				mensajes += "("+document.getElementById("ocu_con246").value+") Evaluaciones Pendientes de Generar Guía [Empresarial]<br>";
			}
			if(document.getElementById("ocu_con244") != null && document.getElementById("ocu_con244").value!=0 && document.getElementById("ocu_con244").value!=""){
				mensajes += "("+document.getElementById("ocu_con244").value+") Guias Autorizadas para Sustituir<br>";
			}
			if(document.getElementById("ocu_con242") != null && document.getElementById("ocu_con242").value!=0 && document.getElementById("ocu_con242").value!=""){
				mensajes += "("+document.getElementById("ocu_con242").value+") Guias Pendientes Por Cancelar<br>";
			}
			if(document.getElementById("ocu_con243") != null && document.getElementById("ocu_con243").value!=0 && document.getElementById("ocu_con243").value!=""){
				mensajes += "("+document.getElementById("ocu_con243").value+") Guias Pendientes por Sustituir<br>";
			}
			return "Para poder cerrar el dia debe terminar lo siguiente:<br>"+mensajes;
		}
   </script>
<title>PMM</title>
<style>
/*estilos para mostrar diferentes fondos*/
body{
	background: url(../img/fondo1_tablero.jpg) #303030 no-repeat center top fixed;
}
#parafila{
	background:url(../img/fondomenu.gif) repeat-x;
}
#paracombo{
	text-align:center;
	vertical-align:middle;
}
#paraboton{
	text-align:center;
	vertical-align:middle;
}
#textoCentrado{
	vertical-align:middle;
	text-align:left;
	font-size:14px; 
	font-family:Verdana, Geneva, sans-serif;
	text-decoration:underline;
	padding-left:5px;
}
.seleccionada{
	width:150px;
	height:30px;
	background:url(../img/seleccionada.png);
	text-align:center;
	vertical-align:middle;
	cursor:default;
}
.desseleccionada{
	width:150px;
	height:30px;
	background: url(../img/sinseleccionar.png);
	text-align:center;
	vertical-align:middle;
	cursor:pointer;
}
#textoPestana{
	width:150px; 
	height:25px; 
	overflow:hidden; 
	padding-top:5px;
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
}
</style>
</head>
<body link="#BA410D" vlink="#BA410D" alink="#BA410D" bgcolor="#ffffff">

<div id="wrap" style="width:1000px">
<table border="0" cellpadding="0" cellspacing="0" style="background-color:none" width="100%">
<tr>
	<td height="10%" width="100%" align="center" valign="top">
    <?
		$s = "SELECT pt.id
		FROM permisos_grupos AS pt
		INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
		WHERE ce.id = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l)or die($s);
		$f = mysql_fetch_object($r);
		switch ($f->id){
			case 1: //ADMINISTRACION
				$flash = "unidadAdministrativa";
				break;
			case 2: //COBRANZA
				$flash = "cobranza";
				break;
			case 3: //CORM
				$flash = "corm";
				break;
			case 4: //DIRECCION GENERAL
				$flash = "directorGeneral";
				break;
			case 5: //GERENTE SUCURSAL
				$flash = "gerenteGeneral";
				break;
			case 6: //OPERACIONES Y SERVICIOS
				$flash = "centroOperaciones";
				break;
			case 8: //PUNTO DE VENTA
				$flash = "puntoVenta";
				break;
			case 9: //VENTAS
				$flash = "ventas";
				break;
			case 10: //ADMINISTRADOR GENERAL
				$flash = "directorGeneral";
				break;
		}
	?>
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="1000" height="150" id="Header" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="../css/<?=$flash?>.swf" /><param name="quality" value="high"/><param name="bgcolor" value="#ffffff" /><embed src="../css/<?=$flash?>.swf" quality="high" bgcolor="#ffffff" width="1000" height="150" name="Header" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</td>
</tr>
</table>

<table width="1000" border="0" cellpadding="0" cellspacing="0" id="posicionPagina">
	<tr id="parafila">	
    	<td width="218" height="30px" id="paracombo">
        
        <?
		$s = "SELECT pt.nombre, ce.sucursal
		FROM permisos_grupos AS pt
		INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
		WHERE ce.id = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if($f->nombre=="DIRECCION GENERAL" || $f->nombre=="ADMINISTRADOR GRAL" || $f->sucursal == 1){
		?>
		  <select name="sucursal" style="width:203px; font-family:Verdana, Geneva, sans-serif; font-size:12px" class="styled" alcambiar="cambiarSucursal(this.value); var pg = devolverIframe().src;  devolverIframe().src='';  devolverIframe().src=pg;">
		<?
			$s = "select * from catalogosucursal order by descripcion";
			$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
		?>
				<option <? if($_SESSION[IDSUCURSAL]==$f->id){echo "selected";}?> value="<?=$f->id?>"><?=strtoupper($f->descripcion)?></option>		
		<?
			}
			
		?>
			</select>
	 <? }else{
			$s = "select descripcion from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			echo strtoupper($f->descripcion);
		}
		
	?>
        
        </td>
    <td width="542" valign="middle">
    	<table border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td id="pest0" class="seleccionada" onclick="seleccionarTab(0)"><div id="textoPestana">Pest 1</div></td>
                <td>&nbsp;</td>
                <td id="pest1" class="desseleccionada" onclick="seleccionarTab(1)"><div id="textoPestana">Pest 2</div></td>
                <td>&nbsp;</td>
                <td id="pest2" class="desseleccionada" onclick="seleccionarTab(2)"><div id="textoPestana">Pest 3</div></td>
            </tr>
       	</table>
    </td>
        <td width="240" id="paraboton">
        <table>
        <tr>
        <td>
        <table width="133" cellpadding="0" cellspacing="0" border="0" align="center">
          <tr onclick="abrirVentanaFija('configuracion.php', 400, 400, 'ventana', 'Configuraci&oacute;n');" style="cursor:hand">
            <td width="25"><span style="font-size:14px; font-family:Verdana, Geneva, sans-serif">
              <input type="hidden" name="modificando" value="0" />
            </span><img src="../img/configuracion.png" /></td>
            <td width="108" align="left" id="textoCentrado">Configuración            </td>
          </tr>
        </table>
        </td>
        <td>
        <table width="133" cellpadding="0" cellspacing="0" border="0" align="center">
          <tr onclick='cerrarVentana()' style="cursor:hand">
            <td width="25"><span style="font-size:14px; font-family:Verdana, Geneva, sans-serif">
              <input type="hidden" name="modificando" value="0" />
            </span><img src="../../img/salir8.gif" /></td>
            <td width="108" align="left" id="textoCentrado">Cerrar Sesi&oacute;n            </td>
          </tr>
        </table>
        </td>
        </tr>
        </table>
        </td>
    </tr>
    <tr>	
    	<td rowspan="2"><div id="accordion" style="width:210px; margin:0px 0px 0px 5px">
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
                            <h3><a href="#"><?=$f->grupo?></a></h3>
                          <div class="menu_acord_cont" id="accordions" style="overflow:hidden">
                                <ul>
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
								if($fx->tipo=="C"){
									$idsconsulta .= ($idsconsulta!="")?",":"";
									$idsconsulta .= $fx->id;
								}
						?>
                                  <li><a href="#posicionPagina" onclick="mostrarPagina('<?=$fx->vinculo?>','<?=$fx->script?>')"><input type="hidden" id="ocu_con<?=$fx->id?>" value=""/>
                                  <? if($fx->tipo=="C"){ ?>(<strong id='con<?=$fx->id?>'></strong>)<? } ?> <?=str_replace("()","",$fx->nombre)?></a></li>
                        <?
							}
						?></ul>
                          </div>
              <?
				}
			  ?>
                        </div>

    </div>    	  &nbsp;</td>
    	<script>
		var idsasolicitar = "<?=$idsconsulta?>";
	</script>
        <td colspan="2" valign="top" height="0px"></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><?
			$s = "SELECT ce.grupo

			FROM permisos_grupos AS pt
			INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
			WHERE ce.id = $_SESSION[IDUSUARIO]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			
			if($f->grupo == 4 || $f->grupo == 5 || $f->grupo == 10)
				$pagina = "../tableros/tablero/Tablero.html";
			else
				$pagina = "../../guias/guia.php";
		?>        <iframe onblur="document.all.modificando.value=1;" name="pagina0" id="pagina0" scrolling="auto" 
        			align="top" width="770" height="600" src="<?=$pagina?>" frameborder="0" style="display:''"></iframe>
                  <iframe onblur="document.all.modificando.value=1;" name="pagina1" id="pagina1" scrolling="auto" 
        			align="top" width="770" height="600" src="pestana.php" frameborder="0" style="display:none"></iframe>
                  <iframe onblur="document.all.modificando.value=1;" name="pagina2" id="pagina2" scrolling="auto" 
        			align="top" width="770" height="600" src="pestana.php" frameborder="0" style="display:none"></iframe>
      				
      </td>
    </tr>
  </table>
</div>

</body>
</html>