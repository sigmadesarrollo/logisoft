<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$fecha = date('d/m/Y');	
	
	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion) AS descripcion FROM catalogosucursal cs
	WHERE id = ".$_SESSION[IDSUCURSAL]."";
	$r = mysql_query($s,$l) or die($s); $fs = mysql_fetch_object($r);
	
	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion FROM catalogosucursal cs
	ORDER BY cs.descripcion";
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}
		$desc = substr($desc, 0, -1);
	}
	//die("Estamos trabjando en la mejora de este proceso, disculpe las molestias...");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script>
	var tabla1 	= new ClaseTabla();
	var	u		= document.all;
	var pag1_cantidadporpagina = 30;
	var arre		= "";
	var v_suc		= "<?=$_SESSION[IDSUCURSAL] ?>";
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:65, alineacion:"left", datos:"sucursal"},			
			{nombre:"GUIA", medida:75, alineacion:"left", datos:"guia"},
			{nombre:"FLETE", medida:90, alineacion:"left", datos:"flete"},
			{nombre:"COND. PAGO", medida:90, alineacion:"left", datos:"pago"},
			{nombre:"CLIENTE", medida:50, alineacion:"center", datos:"nocliente"},
			{nombre:"NOMBRE", medida:150, alineacion:"left",  datos:"cliente"},
			{nombre:"FECHA_GUIA", medida:70, alineacion:"left", datos:"fecha"},
			{nombre:"DESCRIPCION", medida:100, alineacion:"left", datos:"descripcion"},
			{nombre:"CONTENIDO", medida:100, alineacion:"left", datos:"contenido"},
			{nombre:"IMPORTE", medida:90, tipo:"moneda", alineacion:"right", datos:"importe"},			
			{nombre:"ALMACEN", medida:70, alineacion:"left", datos:"almacen"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	jQuery(function($){	   
	   $('#fechainicio').mask("99/99/9999");
	});
	
	window.onload = function(){
		tabla1.create();
	}
	
	function obtenerDetalle(){
		if(u.sucursal.value == ""){
			alerta('Debe capturar Sucursal','¡Atención!','sucursal');
		}else if(u.fechainicio.value == "" || u.fechainicio.value == "__/__/____"){
			alerta('Debe capturar fecha ','¡Atención!','fechainicio');
		}else if(u.estado.value == 0){
			alerta('Debe capturar Almacen','¡Atención!','estado');
		}else{
			if(u.sucursal_hidden.value == undefined || u.sucursal_hidden.value == "undefined" || u.sucursal_hidden.value == "no"){
				u.sucursal_hidden.value = v_suc;
			}
		
			var arr = new Array();
			arr[0] = u.sucursal_hidden.value;
			arr[1] = u.fechainicio.value;
			arr[2] = u.fechafin.value;
			arr[3] = u.estado.options[u.estado.options.selectedIndex].text;	
			
			/*window.open("inventarioMercancia_con_2.php?accion=1&arre="+arr+"&contador="+u.pag1_contador.value
			+"&s="+Math.random());*/
				
			consultaTexto("resTabla1","inventarioMercancia_con.php?accion=1&arre="+arr+"&contador="+u.pag1_contador.value
			+"&s="+Math.random());
		}
	}
	
	function resTabla1(datos){
		try{
			var obj = eval(convertirValoresJson(datos));
		}catch(e){
			alerta3(datos);
		}
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		
		u.cantidadguias.value = obj.totales.cantidad;
		u.totalguias.value = "$ "+obj.totales.total;
		
		tabla1.setJsonData(obj.registros);
		
		if(obj.paginado==1){
			document.getElementById('paginado').style.visibility = 'visible';
		}else{
			document.getElementById('paginado').style.visibility = 'hidden';
		}
	}
	
	function paginacion(movimiento){
		switch(movimiento){
			case 'primero':
				var arr = new Array();
				arr[0] = u.sucursal_hidden.value;
				arr[1] = u.fechainicio.value;
				arr[2] = u.fechafin.value;
				arr[3] = u.estado.options[u.estado.options.selectedIndex].text;

				consultaTexto("resTabla1","inventarioMercancia_con.php?accion=1&arre="+arr+"&contador=0&s="+Math.random());
				break;
			case 'adelante':
				var arr = new Array();
				arr[0] = u.sucursal_hidden.value;
				arr[1] = u.fechainicio.value;
				arr[2] = u.fechafin.value;
				arr[3] = u.estado.options[u.estado.options.selectedIndex].text;
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","inventarioMercancia_con.php?accion=1&arre="+arr+"&contador="+(parseFloat(u.pag1_contador.value)+1)					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				var arr = new Array();
				arr[0] = u.sucursal_hidden.value;
				arr[1] = u.fechainicio.value;
				arr[2] = u.fechafin.value;
				arr[3] = u.estado.options[u.estado.options.selectedIndex].text;
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","inventarioMercancia_con.php?accion=1&arre="+arr+"&contador="+(parseFloat(u.pag1_contador.value)-1)					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var arr = new Array();
				arr[0] = u.sucursal_hidden.value;
				arr[1] = u.fechainicio.value;
				arr[2] = u.fechafin.value;
				arr[3] = u.estado.options[u.estado.options.selectedIndex].text;
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));				
				consultaTexto("resTabla1","inventarioMercancia_con.php?accion=1&arre="+arr+"&contador="+contador
				+"&s="+Math.random());
				break;
		}
	}
	
	function nuevo(){
		u.sucursal_hidden.value	= "<?=$_SESSION[IDSUCURSAL] ?>";
		u.sucursal.value	= "<?=$fs->descripcion ?>";
		u.fechainicio.value		= "<?=date('d/m/Y') ?>";
		u.fechafin.value		= "";
		u.estado.value			= 0;
		tabla1.clear();		
		u.paginado.style.visibility = "hidden";	
	}
	
	function validarFecha(e,param,name){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 || tecla == 9){
			if(param!=""){
				var mes  =  parseInt(param.substring(3,5),10);
				var dia  =  parseInt(param.substring(0,3),10);
				var year = 	parseInt(param.substring(6,10),10);
				if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){
					alerta('La fecha no es valida', '¡Atención!',name);
					return false;
				}				
				if(dia > 29 && (mes=="02" || mes==2)){
					if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){
						alerta3('La fecha no es valida, por que el año '+year+' es bisiesto su maximo dia es 29', '¡Atención!');
						return false;
					}else{
						alerta3('La fecha no es valida, por que el año '+year+' no es bisiesto su maximo dia es 28', '¡Atención!');
						return false;
					}
				}
				
				if(dia >= 29 && (mes=="02" || mes=="2")){
					if(!((year % 4 == 0 && year % 100 != 0) || year % 400 == 0)){
						alerta3('La fecha no es valida, por que el año '+year+' no es bisiesto su maximo dia es 28', '¡Atención!');
							return false;
					}
				}
				if(dia > "31" || dia=="0"){
					alerta('La fecha no es valida, capture correctamente el Dia', '¡Atención!',name);
					return false;
				}
				if(mes > "12" || mes=="0"){
					alerta('La fecha no es valida, capture correctamente el Mes', '¡Atención!',name);
					return false;	
				}
			}	
		}
	}
	
	function imprimirReporte(){
		if(u.sucursal.value == ""){
			alerta('Debe capturar Sucursal','¡Atención!','sucursal');
		}else if(u.fechainicio.value == "" || u.fechainicio.value == "__/__/____"){
			alerta('Debe capturar fecha ','¡Atención!','fechainicio');
		}else if(u.estado.value == 0){
			alerta('Debe capturar Almacen','¡Atención!','estado');
		}else{
			if(u.sucursal_hidden.value == undefined || u.sucursal_hidden.value == "undefined" || u.sucursal_hidden.value == "no"){
				u.sucursal_hidden.value = v_suc;
			}
			if(document.URL.indexOf("web_capacitacionPruebas/")>-1){		
				window.open("https://www.pmmintranet.net/web_capacitacionPruebas/almacen/inventarioMercancia_excel.php?sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.fechainicio.value+"&estado="+u.estado.options[u.estado.options.selectedIndex].text+"&val="+Math.random());
			
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
				window.open("https://www.pmmintranet.net/web_capacitacion/almacen/inventarioMercancia_excel.php?sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.fechainicio.value+"&estado="+u.estado.options[u.estado.options.selectedIndex].text+"&val="+Math.random());
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				window.open("https://www.pmmintranet.net/web_pruebas/almacen/inventarioMercancia_excel.php?sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.fechainicio.value+"&estado="+u.estado.options[u.estado.options.selectedIndex].text+"&val="+Math.random());
			}
		}
	}
	
	function reiniciarcontador ()
	{
		document.all.pag1_contador.value="0";
	}
	
	var desc = new Array(<?php echo $desc; ?>);
</script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
  <table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="668" class="FondoTabla Estilo4">INVENTARIO DE MERCANCIA</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>
            Sucursal:</td>
            <td colspan="2"><span class="Tablas">
              <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:250px" value="<?=$fs->descripcion ?>" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo;}" onBlur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}"
        />
            </span><input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$_SESSION[IDSUCURSAL] ?>"></td>
            <td><img src="../img/Boton_Generar.gif" width="74" height="20" style="cursor:pointer" onClick="obtenerDetalle()"></td>
            <td>&nbsp;</td>
            <td>Fecha: </td>
            <td><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/></td>
          </tr>
          <tr>
            <td width="82">A la Fecha: </td>
            <td width="193"><input name="fechainicio" type="text" class="Tablas" id="fechainicio"  style="width:80px" onKeyPress="validarFecha(event,this.value,this.name);  if(event.keyCode==13){document.all.fechafin.focus();}" value="<?=$fecha ?>">
            <span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="25" height="25" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.all.fechainicio,'dd/mm/yyyy',this)" /></span></td>
            <td width="86">Almacen:</td>
            <td width="152"><select name="estado" class="Tablas" id="estado" style="width:100px" onchange="reiniciarcontador();">
              <option value="0">SELECCIONAR</option>
			  <option value="1">ALMACEN ORIGEN</option>
              <option value="2">ALMACEN TRASBORDO</option>
              <option value="3">EAD</option>
			  <option value="4">EN REPARTO EAD</option>
			  <option value="5">EN TRANSITO</option>
              <option value="6">OCURRE</option>			  
  			  <option value="7">POR ENTREGAR</option>
			  <option value="8">POR RECIBIR</option>
              <option value="9">TODOS</option>
            </select></td>
            <td width="58">&nbsp;</td>
            <td width="77">&nbsp;</td>
            <td width="150"><span class="Estilo6 Tablas">
              <input name="fechafin" type="text" class="Tablas" id="fechafin" style="width:80px; visibility:hidden" onKeyPress="validarFecha(event,this.value,this.name); if(event.keyCode==13){document.all.estado.focus();}" value="<?=$fecha ?>">
            <img src="../img/calendario.gif" alt="Alta" width="25" height="25" align="absbottom" style="cursor:pointer; visibility:hidden" title="Calendario" onClick="displayCalendar(document.all.fechafin,'dd/mm/yyyy',this)" /></span></td>
          </tr>
          <tr>
            <td colspan="7"><div id="txtDir" style=" height:280px; width:799px; overflow:auto" align=left>
              <table width="100%" id="detalle" border="0" cellpadding="0" cellspacing="0">
              </table>
            </div></td>
          </tr>
          <tr>
          	<td colspan="7">
            	<table align="right">
                	<tr>
                    	<td>Cantidad</td>
                        <td><input type="text" readonly name="cantidadguias" style="text-align:right"></td>
                        <td>Total</td>
                        <td><input type="text" readonly name="totalguias" style="text-align:right"></td>
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
            <td colspan="7"><div id="paginado" align="center" style="visibility:hidden">
			 <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion('primero')" />
			  <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onClick="paginacion('atras')" /> <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onClick="paginacion('adelante')" /> <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onClick="paginacion('ultimo')" />
                  <input type="hidden" name="pag1_total" />
                  <input type="hidden" name="pag1_contador" value="0" />
                  <input type="hidden" name="pag1_adelante" value="" />
                  <input type="hidden" name="pag1_atras" value="" />
            </div></td>
          </tr>
          <tr>
            <td colspan="7" align="right">			
				<table width="200" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td><div class="ebtn_imprimir" onClick="imprimirReporte()"></div></td>
					<td><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'nuevo();', '')"></div></td>
				  </tr>
				</table>
			</td>			
          </tr>
          <tr>
            <td colspan="7" align="center"> </div></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>