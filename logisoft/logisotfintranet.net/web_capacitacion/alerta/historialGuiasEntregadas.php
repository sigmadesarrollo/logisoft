<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion
	FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL]."";	
	$r = mysql_query($s,$l) or die($s); $fs = mysql_fetch_object($r);
	$sucdescripcion = cambio_texto($fs->descripcion);
	
	$s = "SELECT CONCAT(prefijo,' - ',descripcion,':',id) AS descripcion
	FROM catalogosucursal ORDER BY descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}
		$desc = substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/jquery-1.4.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	var mens = new ClaseMensajes();
	var pag1_cantidadporpagina = 30;
	
	mens.iniciar('../javascript');
	
	jQuery(function($){	   
	   $('#fechainicio').mask("99/99/9999");
	   $('#fechafin').mask("99/99/9999");
	});
	
	tabla1.setAttributes({//PRINCIPAL
		nombre:"detalle",
		campos:[
			{nombre:"GUIA", medida:80, alineacion:"left", datos:"guia"},
			{nombre:"FECHA", medida:115, alineacion:"center", datos:"fecha"},
			{nombre:"REMITENTE", medida:115, alineacion:"left", datos:"remitente"},
			{nombre:"DESTINATARIO", medida:115, alineacion:"left", datos:"destinatario"},
			{nombre:"ORIGEN", medida:80, alineacion:"center", datos:"origen"},
			{nombre:"DESTINO", medida:80, alineacion:"center", datos:"destino"},
			{nombre:"TOTAL", medida:120, tipo:"moneda", alineacion:"right", datos:"total"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.sucursal.select();
	}
	
	function obtenerDetalle(){
		if(u.sucursal_hidden.value==undefined || u.sucursal.value == ""){
			mens.show("A","Debe capturar Sucursal","¡Atención!","sucursal");
			return false;
		}
		if(u.fechainicio.value == "" || u.fechainicio.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha inicio","¡Atención!","fechainicio");
			return false;
		}
		
		if(u.fechafin.value == "" || u.fechafin.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha fin","¡Atención!","fechafin");
			return false;
		}
		
		
		var f1 = u.fechainicio.value.split("/");
		var f2 = u.fechafin.value.split("/");
		
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
		
		if(u.sucursal_hidden.value=="no" || u.sucursal_hidden.value==null){
			u.sucursal_hidden.value='<?=$_SESSION[IDSUCURSAL] ?>';
		}
		
		if(f1 > f2){
			mens.show("A","La fecha fin debe ser mayor a la fecha inicio","¡Atención!","fechafin");
			return false;
		}
		consultaTexto("mostrarPrincipal","consultasAlertas.php?accion=33&fechainicio="+u.fechainicio.value
		+"&fechafin="+u.fechafin.value+"&contador="+u.pag1_contador.value+"&sucursal="+u.sucursal_hidden.value
		+"&s="+Math.random());
	}
	
	function mostrarPrincipal(datos){
	//	mens.show("I",datos);
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;		
		if(obj.registros.length==0){
			mens.show("I","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla1.clear();
		}else{			
			tabla1.setJsonData(obj.registros);
		}
		if(obj.paginado==1){
			document.getElementById('paginado1').style.visibility = 'visible';
		}else{
			document.getElementById('paginado1').style.visibility = 'hidden';
		}
	}
	
	function paginacion1(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("mostrarPrincipal","consultasAlertas.php?accion=33&contador=0&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("mostrarPrincipal","consultasAlertas.php?accion=33&contador="+(parseFloat(u.pag1_contador.value)+1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("mostrarPrincipal","consultasAlertas.php?accion=33&contador="+(parseFloat(u.pag1_contador.value)-1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarPrincipal","consultasAlertas.php?accion=33&contador="+contador
				+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerSucursal(sucursal){
		u.sucursal_hidden.value = sucursal;
		consultaTexto("mostrarSucursal","consultasAlertas.php?accion=0&sucursal="+sucursal);
	}
	
	function mostrarSucursal(datos){
		var obj = eval(convertirValoresJson(datos));
		u.sucursal.value = obj.descripcion;
	}
	
	function limpiar(){
		tabla1.clear();			
		u.fechainicio.value = '<?=date('d/m/Y') ?>';
		u.fechafin.value = '<?=date('d/m/Y') ?>';
		u.pag1_contador.value = 0;
		obtenerSucursal(<?=$_SESSION[IDSUCURSAL]?>);
	}
	
	var desc = new Array(<?php echo $desc; ?>);
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="610" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Historial de Guias Entregadas </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="13%">Sucursal:</td>
              <td colspan="6"><input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:170px" onblur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo; document.all.fechainicio.select();}" value="<?=$sucdescripcion ?>" autocomplete="array:desc"/>
            &nbsp;&nbsp;&nbsp;<img src="../img/Buscar_24.gif" width="24" height="23"  align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 600, 500, 'ventana', 'Busqueda')" /> <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$_SESSION[IDUSUARIO] ?>" /></td>
              </tr>
            <tr>
              <td>Fecha Inicio: </td>
              <td width="16%"><label>
                <input name="fechainicio" type="text" id="fechainicio" style="width:80px" onkeypress="if(event.keyCode==13){document.all.fechafin.focus();}" value="<?=date('d/m/Y') ?>" class="Tablas" />
              </label></td>
              <td width="10%"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fechainicio,'dd/mm/yyyy',this)"></div></td>
              <td width="13%">Fecha Fin: </td>
              <td width="16%"><input name="fechafin" type="text" id="fechafin" style="width:80px" value="<?=date('d/m/Y') ?>" class="Tablas" onkeypress="if(event.keyCode==13){obtenerDetalle()}"/></td>
              <td width="9%"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fechafin,'dd/mm/yyyy',this)"></div></td>
              <td width="23%"><div class="ebtn_Generar" onclick="obtenerDetalle()"></div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></td>
        </tr>
        <tr>
          <td><div id="paginado1" align="center" style="visibility:hidden"> <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion1('primero')" /> <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion1('atras')" /> <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion1('adelante')" /> <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion1('ultimo')" />
                <input type="hidden" name="pag1_total" />
                <input type="hidden" name="pag1_contador" value="0" />
                <input type="hidden" name="pag1_adelante" value="" />
                <input type="hidden" name="pag1_atras" value="" />
          </div></td>
        </tr>
        <tr>
          <td align="right"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
