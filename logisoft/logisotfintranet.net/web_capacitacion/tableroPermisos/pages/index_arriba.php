<?
	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
	<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
    <script type="text/javascript" src="../../javascript/ajax.js"></script>
    <link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
    <link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
	<link  href="../css/estilosclaseacordeon.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../javascript/ClaseAcordeon.js"></script>
    <script type="text/javascript" src="../javascript/estilosjs/custom-form-elements.js"></script>
	<link rel="stylesheet" href="../javascript/estilosjs/form.css" media="screen" />
	<script>
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
    
		function resVerificacionInicio(res){
			var obj = eval(res);
			
			if(obj.iniciodia==1 && obj.iniciocaja==1){
				if(var_pagina != document.all.pagina.src && var_pagina.indexOf(document.all.pagina.src)>-1 && var_script!="" && document.all.pagina.src!=""){
					eval(var_script);
            	}else{
					if(document.all.modificando.value==1){
						confirmar("Al cambiar de modulo se perderán los datos \n¿Deséa continuar?","¡Atencion!","mostrarLaPagina()","");
					}else{
                		document.all.pagina.src = var_pagina;	
					}
            	}
			}else{
				if(obj.iniciodia==1 && obj.iniciocaja==0 && (var_pagina=="../../Caja/iniciocaja.php" || var_pagina=="../../Caja/cierrecaja.php")){
					if(document.all.modificando.value==1){
						if(var_pagina.indexOf(document.all.pagina.src)<0){
							confirmar("Al cambiar de modulo se perderán los datos no guardados \n¿Deséa continuar?","¡Atencion!","mostrarLaPagina()","");
						}else{
							document.all.pagina.src = var_pagina;	
						}
					}else{
                		document.all.pagina.src = var_pagina;	
					}
				}else if(obj.iniciodia==0 && obj.iniciocaja==0 && (var_pagina=="../../Caja/iniciodia.php" || var_pagina=="../../Caja/cierrededia.php")){
					if(document.all.modificando.value==1){
						if(var_pagina.indexOf(document.all.pagina.src)<0){
							confirmar("Al cambiar de modulo se perderán los datos no guardados \n¿Deséa continuar?","¡Atencion!","mostrarLaPagina()","");
						}else{
							document.all.pagina.src = var_pagina;	
						}
					}else{
                		document.all.pagina.src = var_pagina;	
					}
				}else if(var_pagina=="../../evaluacion/EvaluacionDeMercancia.php" || var_pagina.indexOf("../../guias/guia.php")>-1 || 
						 var_pagina.indexOf("../../Caja/")>-1 || var_pagina=="../../entregas/liquidaciondemercancia.php" || 
						 var_pagina.indexOf("../../guiasempresariales/guiasempresariales.php")>-1 || 
						 var_pagina=="../../entregas/entregaocurre.php" || var_pagina=="../../creditoycobranza/abonodecliente.php"){
					alerta3("Para poder trabajar estos modulos, debe iniciar el dia y la caja","¡Atencion!");
				}else{
					if(document.all.modificando.value==1){
						if(var_pagina.indexOf(document.all.pagina.src)<0){
							confirmar("Al cambiar de modulo se perderán los datos no guardados \n¿Deséa continuar?","¡Atencion!","mostrarLaPagina()","");
						}else{
							document.all.pagina.src = var_pagina;	
						}
					}else{
                		document.all.pagina.src = var_pagina;	
					}
				}
			}
		}
		
		function mostrarLaPagina(){
			document.all.pagina.src = var_pagina;
			document.all.modificando.value=0;
		}
    </script>
	<link href="../css/styles.css" rel="stylesheet" type="text/css" />	
	<link href="../css/generalStyles.css" rel="stylesheet" type="text/css" />
<title>PMM</title>

</head>
<body link="#BA410D" vlink="#BA410D" alink="#BA410D" bgcolor="#ffffff">
<div id="wrap" style="width:1000px">
<table border="0" cellpadding="0" cellspacing="0" style="background-color:none" width="100%">
<tr>
	<td colspan="3" height="10%" width="100%" align="center" valign="top">
	<!--
		AREA BANNER, PANEL DE CONTROL 
	-->
	<!-- saved from url=(0013)about:internet -->
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="1000" height="150" id="Header" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="../css/todosHeader.swf" /><param name="quality" value="high"/><param name="bgcolor" value="#ffffff" /><embed src="../css/todosHeader.swf" quality="high" bgcolor="#ffffff" width="1000" height="150" name="Header" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
	<!-- 
		FIN AREA BANNER
	-->
    <script>
		function buscarlaguia(valor){
			if(valor.substring(0,3)=="999"){
				if(document.all.pagina.src.indexOf('guiasempresariales.php')>-1){
					frames[0].buscarUnaGuia(valor);	
				}else{
					document.all.pagina.src="../../guiasempresariales/guiasempresariales.php?funcion2=buscarUnaGuia('"+valor+"')"
				}
			}else{
				if(document.all.pagina.src.indexOf('guia.php')>-1){
					frames[0].buscarUnaGuia(valor);	
				}else{
					document.all.pagina.src="../../guias/guia.php?funcion2=buscarUnaGuia('"+valor+"')"
				}
			}
		}
		
		function mostrarMonitor(){
			document.all.pagina.src="sucursalc.php";
		}
		
		function cambiarSucursal(valor){
			consultaTexto("cambioSucursal","cambiarSucursal.php?sucursal="+valor+"&val="+Math.random());
		}
		
		function cambioSucursal(datos){
			if(datos.indexOf("ok")>-1){
			}
		}
		
		function cerrarVentana(){
			confirmar("¿Desea cerrar el tablero?","¡Atencion!","cerrar()","");
		}
		
		function cerrar(){
			window.close();
		}
	</script>
	</td>
</tr>
</table>
 <table border="0" cellpadding="0" cellspacing="0" width="1000px" align="center">
 <tr>
 	<td height="29px" style="padding-left:15px; font-size:12px; font-weight:bold; color:#154080">
    <?
		$s = "SELECT pt.nombre, ce.sucursal
		FROM permisos_grupos AS pt
		INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
		WHERE ce.id = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if($f->nombre=="DIRECCION GENERAL" || $f->nombre=="ADMINISTRADOR GRAL" || $f->sucursal == 1){
		?>
			<select name="sucursal" style="width:203px;" class="styled" alcambiar="cambiarSucursal(this.value)">
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
		<?
		}else{
			$s = "select descripcion from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			echo strtoupper($f->descripcion);
		}
		
	?>
    </td>
 	<td height="25px" style="padding-left:15px; font-size:12px; font-weight:bold; color:#154080" align="right" class="boton">
    	<table width="74" cellpadding="0" cellspacing="0" border="0">
        	<tr onclick='cerrarVentana()' style="cursor:hand">
            	<td width="25"><img src="../../img/salir8.gif" /></td>
                <td width="49" align="left" style="font-size:18px">&nbsp;Salir</td>
            </tr>
        </table><input type="hidden" name="modificando" value="0" />
    </td>
 </tr>
<tr>
	<td width="231" valign="top" style="overflow:auto">	
	<!--
		PARTE IZQUIERDA DEL SITIO
	-->
	
    <ul class="acc" id="acc">
              <?
			  	$s = "SELECT permisos_tablerogpo.nombre AS grupo, permisos_tablerogpo.id
				FROM permisos_tablerogpo
				INNER JOIN permisos_modulos ON permisos_tablerogpo.id = permisos_modulos.grupo
				INNER JOIN permisos_permisos AS pp ON permisos_modulos.id = pp.idmodulo
				INNER JOIN permisos_grupospermisos AS pgp ON pp.id = pgp.idpermiso
				INNER JOIN catalogoempleado AS ce ON pgp.idgrupo = ce.grupo
				WHERE ce.id = $_SESSION[IDUSUARIO]
				GROUP BY permisos_tablerogpo.id
				ORDER BY grupo ASC";
				$r = mysql_query($s,$l) or die($s);
				while($f = mysql_fetch_object($r)){
			  ?>
			  <li>
				<h3><?=$f->grupo?></h3>
				<div class="acc-section">
					<div class="acc-content">
                    	<?
							$s = "SELECT pm.nombre, IF(ISNULL(pep.idempleado),'ACCESO DENEGADO',pm.vinculo) AS vinculo, pm.script
							FROM permisos_modulos AS pm
							INNER JOIN permisos_permisos AS pp ON pm.id = pp.idmodulo AND pp.descripcion='Acceso'
							INNER JOIN permisos_grupospermisos AS pgp ON pp.id = pgp.idpermiso
							LEFT JOIN permisos_empleadospermisos AS pep ON pp.id = pep.idpermiso AND pep.idempleado = $_SESSION[IDUSUARIO]
							INNER JOIN catalogoempleado AS ce ON pgp.idgrupo = ce.grupo
							WHERE pm.grupo = $f->id AND ce.id = $_SESSION[IDUSUARIO] order by pm.nombre";
							$rx = mysql_query($s,$l) or die($s);
							while($fx = mysql_fetch_object($rx)){
						?>
						<table width="100%" class="<?=($cf)?'fila1':'fila2'; $cf=!$cf;?>" border="0" cellpadding="0" cellspacing="0">
							<tr>
							  <td align="left" onclick="mostrarPagina('<?=$fx->vinculo?>','<?=$fx->script?>')"><?=$fx->nombre?></td>
							</tr>
						</table>
                        <?
							}
						?>
					</div>
				</div>
			  </li>
              <?
			  	}
			  ?>
			</ul>	
    
	<!--
		FIN PARTE IZQUIERDA DEL SITIO
	--></td>
	<td width="769" align="center" valign="top" >		
	<!-- PARTE CENTRO -->
		<?
			$s = "SELECT ce.grupo
			FROM permisos_grupos AS pt
			INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
			WHERE ce.id = $_SESSION[IDUSUARIO]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			
			if($f->grupo == 4 || $f->grupo == 5)
				$pagina = "sucursalc.php";
			else
				$pagina = "../../guias/guia.php";
		?>
		
			<iframe onblur="document.all.modificando.value=1;" name="pagina" id="pagina" scrolling="auto" width="754" height="600" src="<?=$pagina?>" frameborder="0" align="left"></iframe>
	
	<!-- PARTE CENTRO -->
	
	<!--
	 	PARTE DERECHA DEL SITIO
	-->
	<!--
		FIN PARTE DERECHA DEL SITIO
	--></td>	
	</tr>
<tr>
	<td colspan="2" class="textfooter">
	<a href="../pages/contenidos/port1.html" target="cont">Facturaci&oacute;n</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="../pages/contenidos/port2.html" target="cont">Cr&eacute;dito y cobranza</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="../php/form0.php?arch=1" target="cont">Caja</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="../php/form0.php" target="cont">CAT</a>
	<br /><br />
	 PMM &copy; <?=date('Y'); ?> - Todos los derechos reservados.<br /></td>
</tr>
</table>
</div>

</body>
</html>
<script>
		var parentAccordion=new TINY.accordion.slider("parentAccordion");
		parentAccordion.init("acc","h3",1,-1);
		
		var nestedAccordion=new TINY.accordion.slider("nestedAccordion");
		nestedAccordion.init("nested","h3",1,-1);
</script>
