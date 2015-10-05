<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$s = "DELETE FROM entregasocurrealmacen_tmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
	mysql_query($s,$l) or die($s);
	
	$s = "DELETE FROM reportedanosfaltanteocurre WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND folioentrega IS NULL";
	mysql_query($s,$l) or die($s);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
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
.Estilo4 {font-size: 12px}
-->
</style>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/ClaseMensajes.js"></script>
<script>
	var tabla1	=  new ClaseTabla();
	var u  = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript');
	
	tabla1.setAttributes({
		nombre:"tablaguias",
		campos:[
			{nombre:"GUIA", medida:80, alineacion:"left", datos:"noguia"},
			{nombre:"TIPO GUIA", medida:70, alineacion:"center", datos:"tipoguia"},
			{nombre:"FECHA", medida:62, alineacion:"center", datos:"fecha"},
			{nombre:"REMITENTE", medida:140, alineacion:"left", datos:"remitente"},
			{nombre:"DESTINATARIO", medida:140, alineacion:"left", datos:"destinatario"},
			{nombre:"IMPORTE", medida:71, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"PAQUETES", medida:4, tipo:"oculto", alineacion:"right", datos:"totalpaquetes"},
			{nombre:"PAQUETESLEIDOS", medida:4, tipo:"oculto", alineacion:"left", datos:"paquetesleidos"},
			{nombre:"COMPLETO", medida:4, tipo:"oculto", alineacion:"left", datos:"completo"},
			{nombre:"ENDANOFALTANTE", medida:4, tipo:"oculto", alineacion:"left", datos:"endanofaltante"},
			{nombre:"ENTREGA", medida:40, tipo:"checkbox",alineacion:"left", datos:"entregada"}
		],
		filasInicial:15,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"SeleccionarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.folioocurre.focus();
	}
	
	function f_nuevo(){
		u.folio.value="";
		u.folioocurre.disabled=false;
		u.folioocurre.style.backgroundColor='';
		tabla1.clear();
		consultaTexto("ponerFolio", "entregademercanciaocurre_con.php?accion=3&folio="+u.folioocurre.value);
		u.folioocurre.value="";
		u.ebtn_guardar.style.visibility="visible";
	}
	function ponerFolio(datos){
		u.folio.value = datos.split(",")[1];
	}
	function devolverFolio(folio){
		u.folioocurre.value = folio;
		consultaTexto("mostrarGuias", "entregaMercanciaOcurre_con.php?accion=1&folio="+folio);
	}
	function mostrarGuias(datos){
		
		if(datos.indexOf("ya existe")>-1){
			alerta("El folio de entrega ocurre capturado ya fue registrado","¡Atencion!","folioocurre");
			return false;
		}else{
			if(datos.indexOf("no encontro")<0){
				var obj = eval(convertirValoresJson(datos));
				tabla1.setJsonData(obj);
				for(i=0;i<tabla1.getRecordCount();i++){
					u["tablaguias_PAQUETESLEIDOS"][i].value = "";
					u["tablaguias_COMPLETO"][i].value = "";
				}
				u.guia.focus();
			}else{
				alerta3("El folio no existe","¡Atencion!");
				return false;
			}
		}
	}
	
	function f_guardar(){
		<?=$cpermiso->verificarPermiso("315,330",$_SESSION[IDUSUARIO]);?>;
		var todos = 0;
		guias = "";
		v_index = 0;
		var arr = "";
		for(var i=0;i<tabla1.getRecordCount();i++){
			arr = u["tablaguias_PAQUETESLEIDOS"][i].value;
			arr = arr.substring(0,arr.length - 1);
			arr = arr.split(",");			
			if(parseFloat(u["tablaguias_PAQUETES"][i].value) != parseFloat(arr.length) && u["tablaguias_ENDANOFALTANTE"][i].value == "NO"){
				guias += u["tablaguias_GUIA"][i].value + ",";
			}
			arr = "";
		}
		guias = guias.substring(0,guias.length -1);		
		v_incompletas = guias.split(",");
		if(guias!=""){
			mens.show('C','Existen Guias incompletas, se generara un reporte de daños y faltantes por cada uno de los faltantes ¿Desea continuar?', '', '', 'mostrarGuiaArreglo()');
		}else{
			guardarFinal();
		}
	}
	
	function mostrarGuiaArreglo(){
		abrirVentanaFija('../recepcion/reporteDanoFaltante.php?guia='+v_incompletas[v_index]
		+"&tipo=faltante&vieneentrega=SI&indice="+v_index, 600, 480, 'ventana', 'REPORTE DAÑOS Y FALTANTES');
		if(v_incompletas[v_index]==undefined){
			VentanaModal.cerrar();
			info("Se han registrado las guias con faltantes","¡Atencion!");
			guardarFinal();
		}
		v_index++;
	}
	
	function guardarFinal(){
		var folios = "";
		for(var i=0;i<tabla1.getRecordCount();i++){			
			folios +=u["tablaguias_GUIA"][i].value+",";
		}
		folios = folios.substring(0,folios.length - 1);
		u.ebtn_guardar.style.visibility = "hidden";
		consultaTexto("respuestaGuardar","entregademercanciaocurre_con.php?accion=2&folio="+u.folioocurre.value
		+"&folios="+folios+"&guiasfaltantes="+guias);
	}
	
	function respuestaGuardar(datos){
		if(datos.indexOf("correcto")>-1){			
			info("Se han guardado los datos","¡Atencion!");
			var arre = datos.split(",");
			u.folio.value = arre[2];
			//devolverFolio(arre[1]);
			u.seguardo.value = 1;
			u.btnImprimir.style.visibility = "visible";
		}else{
			alerta("Hubo un error al guardar "+datos,"¡Atencion!","folio");
			u.ebtn_guardar.style.visibility = "visible";
		}
	}
	
	function validarimprimio(){
		if(u.seguardo.value==1){
			alerta("No puede salir, tiene que imprimir la entrega","¡Atencion!","folio");
			return false;
		}
		return true;
	}
	
	function tipoImpresion(valor){
		<?=$cpermiso->verificarPermiso(316,$_SESSION[IDUSUARIO]);?>;
		u.seguardo.value=0;
		//proceso para las impresiones
	}
	
	function perdirEntrega(folio){
		u.folio.value = folio;
		consultaTexto("MostrarPedirCliente", "entregaMercanciaOcurre_con.php?accion=2&folio="+folio);
	}
	
	function MostrarPedirCliente(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.folioocurre.value = obj.principal.folioentregasocurre;
			u.folioocurre.disabled = true;
			u.folioocurre.style.backgroundColor='#FFFF99';
			u.ebtn_guardar.style.visibility="hidden";
			tabla1.setJsonData(obj.detalle);
		}else{
			tabla1.clear();
			u.folioocurre.value="";
			u.folioocurre.disabled=false;
			u.folioocurre.style.backgroundColor='';
			u.ebtn_guardar.style.visibility="visible";
		}
	}
	
	function SeleccionarFila(){
		var i=tabla1.getSelectedIndex();
		if(u["tablaguias_ENTREGA"][i].checked==false){
			u["tablaguias_ENTREGA"][i].checked = true;
		}else{
			u["tablaguias_ENTREGA"][i].checked = false;
		}
	}
	
	function contarPaquetes(pguia){
		var v_guia = "";
		if(tabla1.getRecordCount()>0 && pguia!=""){
			if(pguia!="" && u.guia.value!=""){
				if(pguia.length > 21 || pguia.length < 21){
					mens.show("A","El paquete debe contener 21 caracteres","¡Atencion!","guia");
					u.guia.value = "";
					return false;
				}
			
				for(var i=0;i<tabla1.getRecordCount();i++){
					v_guia += u["tablaguias_GUIA"][i].value + ",";
					if(u["tablaguias_GUIA"][i].value == pguia.substr(0,13)){
						if(parseFloat(u["tablaguias_PAQUETES"][i].value) < parseFloat(pguia.substring(17,21))){						
							mens.show("A","El numero de paquete no existe","¡Atencion!","guia");
							u.guia.value = "";
							return false;
						}
						
						if((parseFloat(u["tablaguias_PAQUETES"][i].value) < parseFloat(pguia.substring(17,21))) || (parseFloat(u["tablaguias_PAQUETES"][i].value) < parseFloat(pguia.substring(13,17)))){
							mens.show("A","El numero de paquete no existe","¡Atencion!","guia");
							u.guia.value = "";
							return false;
						}
					}
				
					if(u["tablaguias_GUIA"][i].value==pguia.substr(0,13)){
						if(u["tablaguias_PAQUETESLEIDOS"][i].value.indexOf(pguia)<0){
							var row = u["tablaguias_PAQUETESLEIDOS"][i].value.substr(0,u["tablaguias_PAQUETESLEIDOS"][i].value.length-1);
								row = row.split(",");
								if(u["tablaguias_PAQUETES"][i].value == row.length && row[0]!="" && row[0]!=null){
									mens.show("A","La guia "+pguia.substr(0,13)+" ya fue completada","¡Atención!");
									u.guia.value = "";
									return false;
								}
							
							u["tablaguias_PAQUETESLEIDOS"][i].value += pguia+",";
							u.guia.value = "";
							
							row = u["tablaguias_PAQUETESLEIDOS"][i].value.substr(0,u["tablaguias_PAQUETESLEIDOS"][i].value.length-1);					
							row = row.split(",");
							if(u["tablaguias_PAQUETES"][i].value == row.length){
								u["tablaguias_ENTREGA"][i].checked = true;
							}			
							
						}else{
							var row = u["tablaguias_PAQUETESLEIDOS"][i].value.substr(0,u["tablaguias_PAQUETESLEIDOS"][i].value.length-1);
								row = row.split(",");
								if(u["tablaguias_PAQUETES"][i].value == row.length && row[0]!="" && row[0]!=null){
									mens.show("A","La guia "+pguia.substr(0,13)+" ya fue completada","¡Atención!");
									u.guia.value = "";
									return false;
								}else{
									mens.show("A","El paquete #"+pguia+" ya fue leido","¡Atención!");
									u.guia.value = "";
									return false;
							}
						}
					}
				}			
			}				
			if(v_guia.indexOf(pguia.substr(0,13))<0){
				mens.show("A","El numero de paquete no existe","¡Atención!","guia");
				u.guia.value = "";
				return false;
			}
		}else{
			if(pguia==""){
				mens.show("A","Debe capturar el numero de paquete","¡Atención!","guia");
			}else if(tabla1.getRecordCount()==0){
				mens.show("A","No existen Guias en el detalle","¡Atención!","guia");
			}
			u.guia.value = "";			
			return false;
		}		
	}
	
</script>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="650" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="596" class="FondoTabla Estilo4">ENTREGAS OCURRE ALMACEN </td>
  </tr>
  <tr>
    <td>
    <?
		$s = "SELECT DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fecha, cs.descripcion AS sucursal, 
		(SELECT obtenerFolio('entregasocurrealmacen',".$_SESSION[IDSUCURSAL].")) AS folio
		FROM catalogosucursal AS cs WHERE cs.id = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
	?>
    
    <span class="ebtn_buscar">
    <input type="hidden" name="todos" />
    <input type="hidden" name="folios" />
    </span>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">  
      <tr>
        <td colspan="4" align="right">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="18%">
        Folio:
          <input name="folio" type="text" class="Tablas" id="folio" style="width:70px; text-align:right" value="<?=$f->folio ?>"  onkeypress="if(event.keyCode=='13'){perdirEntrega(this.value)}"/></td>
            	<td width="7%" align="left">
                <div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarFoliosOcurre.php?funcion=perdirEntrega&tipo=almacen', 600, 500, 'ventana', 'Buscar')"></div>                </td>
          		<td width="45%">
			Sucursal:
              <input name="sucural" type="text" class="Tablas" id="sucural" style="width:180px;background:#FFFF99" value="<?=$f->sucursal ?>" readonly=""/></td>
            	<td width="30%">
            	Fecha:
              	  <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$f->fecha ?>" readonly=""/></td>
            </tr>
        </table>            </td>
      </tr>
      <tr>
        <td width="129">F. Entrega Ocurre:</td>
        <td width="70"><span class="Tablas">
          <input name="folioocurre" type="text" class="Tablas" id="folioocurre" style="width:70px;" value="<?=$folio ?>"  onkeypress="if(event.keyCode==13){devolverFolio(this.value)}"/>
        </span></td>
        <td width="82"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarFoliosEntregaOcurre.php?funcion=devolverFolio&mostrar=entregasocurre', 625, 500, 'ventana', 'Busqueda')"></div></td>
        <td width="315"><label>
          Paquete:
          <input name="guia" type="text" id="guia" class="Tablas" style="width:200px" onkeypress="if(event.keyCode==13){this.value = this.value.toUpperCase(); setTimeout('contarPaquetes(document.all.guia.value)',500)}" />
        </label></td>
      </tr>
      <tr>
        <td colspan="4"><span class="Tablas">
          <input type="hidden" name="seguardo" value="0" />
        </span></td>
      </tr>
      <tr>
        <td colspan="4" class="FondoTabla">Gu&iacute;as Por Entregar </td>
      </tr>
      <tr>
        <td colspan="4" id="tablagrid" class="">
            <table border="0" cellpadding="0" cellspacing="0" id="tablaguias" width="100%">
            </table></td>
      </tr>
      
      <tr>
        <td colspan="4" align="center">
        	<table width="250" align="right">
       	    <tr>
           	    <td align="center"><div id="btnImprimir" class="ebtn_imprimir" onClick="abrirVentanaFija('../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"  style="visibility:hidden"></div></td>
                <td align="center"><div class="ebtn_guardar" id="ebtn_guardar" onclick="f_guardar()"></div></td>
                <td align="center"><div class="ebtn_nuevo" onclick="f_nuevo()"></div></td>
           </tr>
           </table>        </td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
    </table>    </tr>
</table>
</form>
</body>
</html>