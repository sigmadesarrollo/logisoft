<? 	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');

	$s = "SELECT * FROM consultaconvenios WHERE fecha=CURDATE()";
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)==0){
		$s = "INSERT INTO consultaconvenios (fecha) VALUES (CURDATE())";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE generacionconvenio SET estadoconvenio = 'EXPIRADO' WHERE CURRENT_DATE > vigencia";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE propuestaconvenio SET estadopropuesta='EXPIRADO' WHERE CURDATE() > vigencia";
		mysql_query($s,$l) or die($s);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	<link rel="stylesheet" href="../javascript/estilosjs/form.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="css/reseter.css" />
    <link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
    <link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.2.custom.css" />
	<link href="../css/styles.css" rel="stylesheet" type="text/css" />	
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
	<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
    <script type="text/javascript" src="../../javascript/ajax.js"></script>
    <script type="text/javascript" src="../javascript/estilosjs/custom-form-elements.js"></script>
	<script>
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
			var paginaframe = frames[0].location.href;
			if(paginaframe.indexOf('web_pruebas')>-1){
				var pos = paginaframe.indexOf('web_pruebas')+11;
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
					document.all.pagina.src = var_pagina;							
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
						
			validarPaginas();

			/*if(obj.iniciodia==1 && obj.iniciocaja==0){				
				if((var_pagina.indexOf("../../guias/guia.php")>-1 ||  
					var_pagina.indexOf("../../guiasempresariales/guiasempresariales.php")>-1) && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja","&iexcl;Atencion!");
				}else if(var_pagina.indexOf("../../entregas/entregaocurre.php")>-1  && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja Ocurre","&iexcl;Atencion!");
				}else if(var_pagina.indexOf("../../creditoycobranza/abonodecliente.php")>-1 && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja Abono Cliente","&iexcl;Atencion!");
				}else{
					validarPaginas();
				}
			}else{
				if((var_pagina.indexOf("../../guias/guia.php")>-1 ||  
					var_pagina.indexOf("../../guiasempresariales/guiasempresariales.php")>-1) && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja","&iexcl;Atencion!");
				}else if(var_pagina.indexOf("../../entregas/entregaocurre.php")>-1  && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja Ocurre","&iexcl;Atencion!");
				}else if(var_pagina.indexOf("../../creditoycobranza/abonodecliente.php")>-1 && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja Abono Cliente","&iexcl;Atencion!");
				}else{
					validarPaginas();
				}
			}*/
			
			/*
			
			if(obj.iniciodia==0 && obj.iniciocaja==0){
				if(var_pagina.indexOf("../../Caja/")>-1){
					validarPaginas();
				}else{
					alerta3("Para poder trabajar estos modulos, debe iniciar el Dia","&iexcl;Atencion!");
				}
			}else if(obj.iniciodia==1){				
				if((var_pagina.indexOf("../../guias/guia.php")>-1 ||  
					var_pagina.indexOf("../../guiasempresariales/guiasempresariales.php")>-1) && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja","&iexcl;Atencion!");
				}else if(var_pagina.indexOf("../../entregas/entregaocurre.php")>-1  && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja Ocurre","&iexcl;Atencion!");
				}else if(var_pagina.indexOf("../../creditoycobranza/abonodecliente.php")>-1 && obj.iniciocaja==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja Abono Cliente","&iexcl;Atencion!");
				}else{
					validarPaginas();
				}
			}
			*/
		}
		/*
		}else if(var_pagina.indexOf("../../entregas/entregaocurre.php")>-1  && obj.iniciocajaocurre==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja Ocurre","&iexcl;Atencion!");
				}else if(var_pagina.indexOf("../../creditoycobranza/abonodecliente.php")>-1 && obj.iniciocajaabonocliente==0){
					alerta3("Para poder trabajar estos modulos, debe iniciar la caja Abono Cliente","&iexcl;Atencion!");
		*/
		function mostrarLaPagina(){
			document.all.pagina.src = var_pagina;
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
			}
			setTimeout('buscar()',60000);
		}
		
		function buscar(){
			consultaTexto("ponerDatos","index_con.php?accion=1&ids="+idsasolicitar+"&azar="+Math.random());
		}
		
		function buscarlaguia(valor){
			if(valor.substring(0,3)=="999"){//GUIAS EMPRESARIALES
				if(document.all.pagina.src.indexOf('/guiasempresariales.php')>-1){
					frames[0].buscarUnaGuia(valor);	
				}else{
					document.all.pagina.src="../../guiasempresariales/guiasempresariales.php?funcion2=buscarUnaGuia('"+valor+"')"
				}
			
			}else if(valor.substring(0,3)=="888"){//GUIAS CORREO INTERNO
				if(document.all.pagina.src.indexOf('/correointerno.php')>-1){
					frames[0].buscarUnaGuia(valor);	
				}else{
					document.all.pagina.src="../../guias/correointerno.php?funcion2=buscarUnaGuia('"+valor+"')"
				}
			}else if(valor.substring(0,3)=="777"){//DEVOLUCION GUIA
				if(document.all.pagina.src.indexOf('/guia.php')>-1){
					frames[0].buscarUnaGuia(valor);	
				}else{
					document.all.pagina.src="../../guias/guia.php?funcion2=buscarUnaGuia('"+valor+"')"
				}			
			}else{//GUIAS EMPRESARIALES
				if(document.all.pagina.src.indexOf('/guia.php')>-1){
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
			confirmar("Desea cerrar el tablero?","Atencion!","cerrar()","");
		}
		
		function cerrar(){
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
			
   </script>
<title>PMM</title>
<style>
/*estilos para mostrar diferentes fondos*/
body{
	background: url(../img/fondo1_tablero.jpg) #303030 no-repeat center top fixed;
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
	<tr>	
    	<td width="230" height="42">
        
        <?
		$s = "SELECT pt.nombre, ce.sucursal
		FROM permisos_grupos AS pt
		INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
		WHERE ce.id = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if($f->nombre=="DIRECCION GENERAL" || $f->nombre=="ADMINISTRADOR GRAL" || $f->sucursal == 1 || $_SESSION[IDUSUARIO] == 1 || $_SESSION[IDUSUARIO] == 8){
		?>
			&nbsp;&nbsp;<select name="sucursal" style="width:203px; font-family:Verdana, Geneva, sans-serif; font-size:12px" class="styled" alcambiar="cambiarSucursal(this.value); var pg = document.all.pagina.src;  document.all.pagina.src='';  document.all.pagina.src=pg;">
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
        <td width="694">
          <input type="hidden" name="modificando" value="0" /></td>
        <td width="76" valign="middle"><table width="74" cellpadding="0" cellspacing="0" border="0" align="center">
          <tr onclick='cerrarVentana()' style="cursor:hand">
            <td width="25"><img src="../../img/salir8.gif" /></td>
            <td width="49" align="left" style="font-size:18px;">&nbsp;Salir</td>
          </tr>
        </table></td>
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
                          <div class="menu_acord_cont" id="accordions">
                                <ul>
                         <?
							$s = "SELECT pm.id, pm.nombre, IF(ISNULL(pep.idempleado),'ACCESO DENEGADO',pm.vinculo) AS vinculo, pm.script, pm.tipo
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
                                  <li><a href="#posicionPagina" onclick="mostrarPagina('<?=$fx->vinculo?>','<?=$fx->script?>')">
                                  <? if($fx->tipo=="C"){ ?>(<strong id='con<?=$fx->id?>'></strong>)<? } ?> <?=str_replace("()","",$fx->nombre)?></a></li>
                        <?
							}
						?>
                                </ul>
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
		?>        <iframe onblur="document.all.modificando.value=1;" name="pagina" id="pagina" scrolling="auto" align="top" width="770" 
      height="600" src="<?=$pagina?>" frameborder="0" style="display:''"></iframe></td>
    </tr>
  </table>
</div>

</body>
</html>