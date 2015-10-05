<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	function esBisiesto($year=NULL){
    	$year = ($year==NULL)? date('Y'):$year;
	    return (($year%4 == 0 && $year%100 != 0) || $year%400 == 0 ); // devolvemos true si es bisiesto
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<link href="../recepciones/estilos_estandar.css" rel="stylesheet" type="text/css" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" >
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script>
	var u = document.all;	
	var tabla1 = new ClaseTabla();
	var tabla2 = new ClaseTabla();
	var pag1_cantidadporpagina = 30;
	
	tabla1.setAttributes({
	nombre:"detalle",
	campos:[
			{nombre:"SE GENERO EN", medida:70, alineacion:"left", datos:"segenero"},
			{nombre:"FOLIO_QUEJA", medida:70, alineacion:"left", datos:"folioqueja"},
			{nombre:"TIPO", medida:70, alineacion:"left", datos:"tipo"},
			{nombre:"No_GUIA", medida:80, alineacion:"center", datos:"guia"},
			{nombre:"ESTADO_GUIA", medida:70, alineacion:"left", datos:"estado"},
			{nombre:"DESTINATARIO", medida:150, alineacion:"left", datos:"destinatario"},
			{nombre:"DESTINO", medida:50, alineacion:"center", datos:"destino"},
			{nombre:"ORIGEN", medida:50, alineacion:"center", datos:"origen"},
			{nombre:"FECHA_RECEPCION", medida:90, alineacion:"center", datos:"fecharecepcion"},
			{nombre:"FOLIO_RECEP", medida:60, alineacion:"left", datos:"recepcion"},
			{nombre:"COMENTARIOS", medida:90, alineacion:"left", datos:"comentarios"},
			{nombre:"EN_QUEJA", medida:4, alineacion:"left", tipo:'oculto', datos:"enqueja"}			
		],
		filasInicial:14,
		alto:200,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
	nombre:"detalle2",
	campos:[
			{nombre:"GUIA", medida:90, alineacion:"center", datos:"guia"},
			{nombre:"IDSUCURSAL", medida:70, alineacion:"center", datos:"idsucursal"},
			{nombre:"SUCURSAL", medida:180, alineacion:"left", datos:"sucursal"},
			{nombre:"MODULO", medida:180, alineacion:"left", datos:"modulo"},
			{nombre:"UNIDAD", medida:100, alineacion:"center", datos:"unidad"},
			{nombre:"FECHA", medida:70, alineacion:"center", datos:"fecha"}			
		],
		filasInicial:14,
		alto:200,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla2"
	});
	
	jQuery(function($){
	   $('#fechainicio').mask("99/99/9999");
	   $('#fechafin').mask("99/99/9999");
	});
	
	window.onload = function(){
		tabla1.create();		
		tabla2.create();		
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
						alerta3('La fecha '+((name=="fechainicio")?"inicio":"fin")+' no es valida, por que el año '+year+' es bisiesto su maximo dia es 29', '¡Atención!');
						return false;
					}else{
						alerta3('La fecha '+((name=="fechainicio")?"inicio":"fin")+' no es valida, por que el año '+year+' no es bisiesto su maximo dia es 28', '¡Atención!');
						return false;
					}
				}
				
				if(dia >= 29 && (mes=="02" || mes=="2")){
					if(!((year % 4 == 0 && year % 100 != 0) || year % 400 == 0)){
						alerta3('La fecha '+((name=="fechainicio")?"inicio":"fin")+' no es valida, por que el año '+year+' no es bisiesto su maximo dia es 28', '¡Atención!');
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
	function generar(){
		<?=$cpermiso->verificarPermiso("304",$_SESSION[IDUSUARIO]);?>
		if(u.todas.checked == false && u.sucursal.value==0){
			alerta('Debe capturar Sucursal','¡Atención!','sucursal');
			return false;
		}else if(u.fechainicio.value=="" || u.fechainicio.value=="__/__/____"){
			alerta('Debe capturar Fecha inicio','¡Atención!','fechainicio');
			return false;
		}
		
		if(u.fechafin.value=="" || u.fechafin.value=="__/__/____"){
			alerta('Debe capturar Fecha fin','¡Atención!','fechafin');
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
		
		if(f1 > f2){
			alerta('La fecha fin no debe ser menor que la fecha inicio','¡Atención!','fechafin');			
		}else{		
			
			consultaTexto("resTabla1","recepcionMercancia_con.php?accion=10&sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value
			+"&contador="+u.pag1_contador.value+"&s="+Math.random());
			
		}
	}	
	
	function imprimirFaltantes(){
		<?=$cpermiso->verificarPermiso("304",$_SESSION[IDUSUARIO]);?>
		if(u.todas.checked == false && u.sucursal.value==0){
			alerta('Debe capturar Sucursal','¡Atención!','sucursal');
			return false;
		}else if(u.fechainicio.value=="" || u.fechainicio.value=="__/__/____"){
			alerta('Debe capturar Fecha inicio','¡Atención!','fechainicio');
			return false;
		}
		
		if(u.fechafin.value=="" || u.fechafin.value=="__/__/____"){
			alerta('Debe capturar Fecha fin','¡Atención!','fechafin');
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
		
		if(f1 > f2){
			alerta('La fecha fin no debe ser menor que la fecha inicio','¡Atención!','fechafin');			
		}else{		
			
			window.open("historicoDanosFaltantes_excel.php?accion=10&sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+
				"&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&contador="+u.pag1_contador.value+"&s="+Math.random());
			
		}
	}
	
	function resTabla1(datos){
		try{
			var obj = eval(convertirValoresJson(datos));
		}catch(e){
			alerta3(datos,'¡Atención!');	
		}
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		if(obj.registros.length==0){
			alerta3("No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla1.clear();
		}else{			
			tabla1.setJsonData(obj.registros);
			for(var i=0;i<tabla1.getRecordCount();i++){
				if(u["detalle_EN_QUEJA"][i].value=="SI"){
					tabla1.setColorById('#FF0000','detalle_id'+i);
				}
			}
		}
		if(obj.paginado==1){
			document.getElementById('paginado').style.visibility = 'visible';
		}else{
			document.getElementById('paginado').style.visibility = 'hidden';
		}
	}
	
	function paginacion(movimiento){
		switch(movimiento){
			case 'primero':				
				consultaTexto("resTabla1","recepcionMercancia_con.php?accion=10&contador=0&sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value
				+"&s="+Math.random());
				
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","recepcionMercancia_con.php?accion=10&sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value
			+"&contador="+(parseFloat(u.pag1_contador.value)+1)+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","recepcionMercancia_con.php?accion=10&sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value
			+"&contador="+(parseFloat(u.pag1_contador.value)-1)+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","recepcionMercancia_con.php?accion=10&sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechaini="+u.fechainicio.value+"&fechafin="+u.fechafin.value
			+"&contador="+contador+"&s="+Math.random());
				break;
		}
	}
	
	function mostrarDetalle(datos){	
		tabla1.clear();
		if(datos.indexOf("no encontro")<0){
			var objeto = eval(convertirValoresJson(datos));
			tabla1.setJsonData(objeto);
			for(var i=0;i<tabla1.getRecordCount();i++){
				if(u["detalle_EN_QUEJA"][i].value=="SI"){
					tabla1.setColorById('#FF0000','detalle_id'+i);
				}
			}			
		}else{
			alerta3("No se encontraron datos con los criterios seleccionados","¡Atención!");
		}
	}
	function imprimirReporte(tipo){
	if(tabla1.getRecordCount()!=0){
		if(tipo == "Archivo"){		
			if(document.URL.indexOf("web/")>-1){		
			window.open("http://www.pmmintranet.net/web/reportes/danoFaltante.php?sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&m="+Math.random());
		
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/reportes/danoFaltante.php?sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&m="+Math.random());
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				window.open("http://www.pmmintranet.net/web_pruebas/reportes/danoFaltante.php?sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&m="+Math.random());
			}
		
			
		}else{
			if(document.URL.indexOf("web/")>-1){		
				window.open("http://www.pmmintranet.net/web/fpdf/reportes/danoFaltante.php?sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&m="+Math.random());
						
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/danoFaltante.php?sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&m="+Math.random());
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/danoFaltante.php?sucursal="+((u.todas.checked==false)? u.sucursal.value :'todas')+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&m="+Math.random());
			}
		}
	}else{
		alerta3("Debe generar el reporte, para imprimir la información","¡Atención!");
	}
}
	
	function buscarSobrantes(folio){
		<?=$cpermiso->verificarPermiso("389",$_SESSION[IDUSUARIO]);?>
		consultaTexto("mostrarSobrantes","historicoDanosFaltantes_con.php?accion=1&folio="+folio);
	}
	
	function mostrarSobrantes(datos){
		var objeto = eval(convertirValoresJson(datos));
		tabla2.setJsonData(objeto);
	}
</script>
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="535" class="FondoTabla Estilo4">HIST&Oacute;RICO DE DA&Ntilde;OS Y FALTANTES</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      
      <tr>
        <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="64">Sucursal:</td>
            <td width="186"><?
				$s = "SELECT id, descripcion FROM catalogosucursal ORDER BY descripcion";
				$r = mysql_query($s, $l) or die(mysql_error($l).$s);
			?>
              <select name="sucursal" class="Tablas" id="sucursal" style="width:150px">
                <option value="0" selected="selected">SELECCIONAR</option>
                <? while($f = mysql_fetch_object($r)){ ?>
                <option value="<?=$f->id;?>">
                  <?=$f->descripcion; ?>
                  </option>
                <? } ?>
              </select></td>
            <td width="69"><label>
              <input name="todas" type="checkbox" id="todas" onClick="if(!this.checked){document.all.sucursal.disabled=false;}else{document.all.sucursal.disabled=true;}" value="todas">
            Todas</label></td>
            <td width="178">Fecha Inicio: <span class="Tablas">
              <input name="fechainicio" type="text" class="Tablas" id="fechainicio" style="width:70px" value="<?=date('d/m/Y') ?>"  onKeyUp="if(event.keyCode==13){document.all.fechafin.focus();}" onKeyPress="validarFecha(event,this.value,this.name);" />
              <img src="../img/calendario.gif" width="16" height="16" align="absbottom" style="cursor:pointer" onClick="displayCalendar(document.all.fechainicio,'dd/mm/yyyy',this)"></span></td>
            <td width="13">&nbsp;</td>
            <td width="180">Fecha Fin:<span class="Tablas">
              <input name="fechafin" type="text" class="Tablas" id="fechafin" style="width:70px" value="<?=date('d/m/Y') ?>"  onKeyPress="validarFecha(event,this.value,this.name);" />
              <img src="../img/calendario.gif" width="16" height="16" align="absbottom" style="cursor:pointer" onClick="displayCalendar(document.all.fechafin,'dd/mm/yyyy',this)"></span></td>
            <td width="109"><img src="../img/Boton_Generar.gif" width="74" height="20" onClick="generar();" style="cursor:pointer"></td>
          </tr>
          
        </table>
          </td>
      </tr>
      <tr>
        <td width="535" colspan="2"><div id="txtDir" style=" height:220px; width:799px; overflow:auto" align=left><table width="534" id="detalle" border="0" cellspacing="0" cellpadding="0">          
        </table></div></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><div id="paginado" align="center" style="visibility:hidden">              
              <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion('primero')" /> 
			  <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onClick="paginacion('atras')" /> 
			  <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onClick="paginacion('adelante')" /> 
			  <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onClick="paginacion('ultimo')" />
		  <input type="hidden" name="pag1_total" />
          <input type="hidden" name="pag1_contador" value="0" />
          <input type="hidden" name="pag1_adelante" value="" />
          <input type="hidden" name="pag1_atras" value="" />
          </div></td>
      </tr>
      <tr>
      	<td align="right">
      	<div class="ebtn_imprimir" onClick="imprimirFaltantes();" style="cursor:pointer"></div>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
        	<table width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
            	  <td height="19" colspan="4" class="FondoTabla Estilo4">SOBRANTES</td>
           	    </tr>	
            	<tr>
                	<td width="87">Folio Buscado</td>
                    <td width="109"><input type="text" name="folioguia" value=""></td>
                    <td width="282"><img src="../img/Boton_Buscar.gif" onClick="buscarSobrantes(document.all.folioguia.value);"></td>
                    <td width="321">&nbsp;</td>
                </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td width="535" colspan="2"><div id="txtDir" style=" height:200px; width:799px; overflow:auto" align=left><table width="534" id="detalle2" border="0" cellspacing="0" cellpadding="0">          
        </table></div></td>
      </tr>
      <tr>
        <td colspan="2" align="center"></td>
      </tr>
	  <tr>
        <td colspan="2" align="right"></td>
      </tr>
    </table>
      </td>
  </tr>
</table>
</form>
</body>
</html>


