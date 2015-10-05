<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<link href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	var inicio		= 30;
	var sepaso		= 0;
	var cont		= 0;
	var totalDatos	= 0;
	var fecha		= "";
	var mens		= new ClaseMensajes();
	mens.iniciar('../javascript',false);
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SEL", medida:10, tipo:"checkbox", alineacion:"left",datos:"sel"},
			{nombre:"PROVIENE", medida:60, alineacion:"left",datos:"tipo"},
			{nombre:"PERSONA QUEJA", medida:150, alineacion:"left", datos:"personaqueja"},
			{nombre:"RAZON QUEJA", medida:150, alineacion:"left", datos:"razonqueja"},
			{nombre:"#CLIENTE", medida:4, alineacion:"left", tipo:"oculto", datos:"cliente"},
			{nombre:"NOMBRE", medida:150, alineacion:"left", datos:"nombre"},
			{nombre:"FOLIO_RECOLECCION", medida:100, alineacion:"center", datos:"recoleccion"},
			{nombre:"FOLIO_DAÑO_FALTANTE", medida:100, alineacion:"center", datos:"danofaltante"},
			{nombre:"REFERENCIA", medida:80, alineacion:"center",  datos:"referencia"},
			{nombre:"FECHA_REFERENCIA", medida:90, alineacion:"center",  datos:"fechareferencia"},
			{nombre:"FACTURA", medida:80, alineacion:"left", datos:"factura"},
			{nombre:"IMPORTE", medida:100, alineacion:"right", tipo:"moneda", datos:"importe"},
			{nombre:"ASIGNADO", medida:80, alineacion:"center",  datos:"asignado"},
			{nombre:"FECHA_ASIGNADA", medida:80, alineacion:"center", datos:"fechaasignado"},
			{nombre:"COMPROMISO", medida:80, alineacion:"left", datos:"compromiso"},
			{nombre:"ID", medida:4, alineacion:"left", tipo:"oculto", datos:"id"},
			{nombre:"GUARDADO", medida:4, alineacion:"left", tipo:"oculto", datos:"guardado"},
			{nombre:"ACTIVIDAD", medida:4, alineacion:"left", tipo:"oculto", datos:"actividad"},
			{nombre:"ACTIVIDAD2", medida:4, alineacion:"left", tipo:"oculto", datos:"actividad2"}
		],
		filasInicial:15,
		alto:210,
		seleccion:true,
		ordenable:false,
		/*eventoClickFila:"agregarResponsable()",*/
		nombrevar:"detalle"
	});	
	jQuery(function($){
	   $('#fecha').mask("99/99/9999");
	});	
	window.onload = function(){
		tabla1.create();
		obtenerGeneral();		
	}
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","carteraMorosa_con.php?accion=1");
	}	
	function mostrarGeneral(datos){
		var row = datos.split(",");
		u.sucursal.value = row[0];
		u.fecha.value = row[1];
		fecha = row[1];
		//consultaTexto("obtenerTotal","carteraMorosa_con.php?accion=7&tipo=0&empleado=<?=$_SESSION[IDUSUARIO] ?>");
		consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=7&tipo=1&inicio=0&empleado=<?=$_SESSION[IDUSUARIO] ?>");
	}	
	function eliminarTemporal(datos){
		if(datos.indexOf("ok")<0){
			mens.show("A","Hubo un error al eliminar Temporal","¡Atención!");
		}
	}	
	function generarDetalle(){
		var f1 = fecha.split("/");
		var f2 = u.fecha.value.split("/");
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
		
		if(u.fecha.value == ""){
			mens.show("A","Debe capturar Fecha","¡Atención!","fecha");
		}else if(f1 < f2){
			mens.show("A","Debe capturar una Fecha menor a la actual","¡Atención!","fecha");
		}else{
			consultaTexto("obtenerTotal","carteraMorosa_con.php?accion=7&tipo=0&empleado=<?=$_SESSION[IDUSUARIO] ?>&fecha="+u.fecha.value);
		}
	}
	function mostrarDetalle(datos){
		//mens.show("A",datos,"");
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
			//se quito para que ya no aparescan seleccionados al cargar
			/*for(var i =0;i<tabla1.getRecordCount();i++){
				if(u["detalle_COMPROMISO"][i].value != ""){
					u["detalle_SEL"][i].checked = true;
				}
			}*/
		}
	}	
	function buscarFactura(factura){
		if(tabla1.getRecordCount()>0){
			if(factura!=""){
				tabla1.setFilter("campo",factura);
			}else{
				tabla1.setFilter("","none");
			}
		}
	}
	
	function agregarResponsableSeleccionados(){
		if(u.fecha.value==""){
			mens.show("A","Debe capturar Fecha Compromiso","¡Atención!","compromiso");
			u["detalle_SEL"][tabla1.getSelectedIndex()].checked = false;
			return false;
		}
		for(var i=0; i<tabla1.getRecordCount(); i++){
			tabla1.setSelectedById("detalle_id"+i);
			
			var obj = tabla1.getSelectedRow();
			if(u["detalle_SEL"][tabla1.getSelectedIndex()].checked == true){
				var arr = tabla1.getSelectedRow();
				var obj = new Object();
				obj.sel				= 2;
				obj.cliente 		= arr.cliente;
				obj.nombre			= arr.nombre;
				obj.personaqueja	= arr.personaqueja;
				obj.razonqueja		= arr.razonqueja;
				obj.referencia 		= arr.referencia;
				obj.fechareferencia = arr.fechareferencia;
				obj.factura 		= arr.factura;
				obj.importe 		= arr.importe;
				obj.asignado 		= arr.asignado;
				obj.fechaasignado 	= arr.fechaasignado;
				obj.compromiso 		= u.fecha.value
				//obj.fecharevision	= u.fecha.value;
				obj.recoleccion 	= arr.recoleccion;
				obj.danofaltante 	= arr.danofaltante;
				obj.id				= arr.id;
				obj.tipo			= arr.tipo;
				obj.actividad		= arr.actividad;
				obj.actividad2		= arr.actividad2;
				tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
			}
		}
	}
	function agregarResponsable(){
		if(u.fecha.value==""){
			mens.show("A","Debe capturar Fecha Compromiso","¡Atención!","compromiso");
			u["detalle_SEL"][tabla1.getSelectedIndex()].checked = false;
			return false;
		}	
		var obj = tabla1.getSelectedRow();
		if(u["detalle_SEL"][tabla1.getSelectedIndex()].checked == true){
			var arr = tabla1.getSelectedRow();
			var obj = new Object();
			obj.sel				= 2;
			obj.cliente 		= arr.cliente;
			obj.nombre			= arr.nombre;
			obj.personaqueja	= arr.personaqueja;
			obj.razonqueja		= arr.razonqueja;
			obj.referencia 		= arr.referencia;
			obj.fechareferencia = arr.fechareferencia;
			obj.factura 		= arr.factura;
			obj.importe 		= arr.importe;
			obj.asignado 		= arr.asignado;
			obj.fechaasignado 	= arr.fechaasignado;
			obj.compromiso 		= u.fecha.value
			//obj.fecharevision	= u.fecha.value;
			obj.recoleccion 	= arr.recoleccion;
			obj.danofaltante 	= arr.danofaltante;
			obj.id				= arr.id;
			obj.tipo			= arr.tipo;
			obj.actividad		= arr.actividad;
			obj.actividad2		= arr.actividad2;
			tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
		}else{
			var arr = tabla1.getSelectedRow();
			var obj = new Object();
			obj.sel				= 1;
			obj.cliente 		= arr.cliente;
			obj.nombre			= arr.nombre;
			obj.personaqueja	= arr.personaqueja;
			obj.razonqueja		= arr.razonqueja;
			obj.referencia 		= arr.referencia;
			obj.fechareferencia = arr.fechareferencia;
			obj.factura 		= arr.factura;
			obj.importe 		= arr.importe;
			obj.asignado 		= arr.asignado;
			obj.fechaasignado 	= arr.fechaasignado;
			obj.compromiso 		= "";
			//obj.fecharevision	= "";
			obj.id				= arr.id;
			obj.recoleccion 	= arr.recoleccion;
			obj.danofaltante 	= arr.danofaltante;
			obj.tipo			= arr.tipo;
			obj.actividad		= 0;
			obj.actividad2		= arr.actividad2;
			tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
		}
	}
	function guardar(){
		var cant = tabla1.getRecordCount();
		if(cant == 0){
			mens.show("A","No existen datos en el detalle","¡Atención!");
			return false;
		}
		var contador = 0;
		var si = 0;
		
		for(var i=0; i<cant; i++){			
			if(u["detalle_ACTIVIDAD"][i].value != u["detalle_ACTIVIDAD2"][i].value){
				u.registros.value += u["detalle_ID"][i].value+","+u["detalle_COMPROMISO"][i].value
				+","+u["detalle_REFERENCIA"][i].value+","+u["detalle_PROVIENE"][i].value
				+","+u["detalle_ACTIVIDAD"][i].value
				+","+u["detalle_ACTIVIDAD2"][i].value+":";
				
			}else if(u["detalle_SEL"][i].checked == true && u["detalle_GUARDADO"][i].value != "SI"){
				u.registros.value += u["detalle_ID"][i].value+","+u["detalle_COMPROMISO"][i].value
				+","+u["detalle_REFERENCIA"][i].value+","+u["detalle_PROVIENE"][i].value
				+","+u["detalle_ACTIVIDAD"][i].value
				+","+u["detalle_ACTIVIDAD2"][i].value+":";
				
			}else if(u["detalle_GUARDADO"][i].value == "SI"){
				si++;
			}else{
				contador++;
			}
		}
		if(si == cant){
			return false;
		}else if(cant == contador){
			mens.show("A","Debe seleccionar una referencia en el detalle","¡Atención!");
			return false;
		}else{
			mens.show('C','&iquest;Desea guardar la informaci&oacute;n?', '', '', 'confirmarGuardar()');
		}
	}
	function confirmarGuardar(){
		u.d_Guardar.style.visibility = "hidden";
		u.registros.value = u.registros.value.substring(0,u.registros.value.length-1);
		consultaTexto("registrarCartera","carteraMorosa_con.php?accion=8&folios="+u.registros.value);
	}
	function registrarCartera(datos){		
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			if(row[1]=="guardado"){
				u.d_Guardar.style.visibility = "visible";
				u.registros.value = "";
				mens.show("I","","La información ha sido guardada correctamente");
				/*for(var i=0; i < tabla1.getRecordCount(); i++){
					if(u["detalle_SEL"][i].checked == true){
						u["detalle_GUARDADO"][i].value = "SI";	
					}
				}*/
				consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=7&empleado=<?=$_SESSION[IDUSUARIO] ?>");
			}
			
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
			u.d_Guardar.style.visibility = "visible";
		}
	}
	function obtenerTotal(datos){
		u.contadordes.value = datos;
		u.mostrardes2.value = datos;
		u.totaldes.value = "00";
		if(u.contadordes.value > 30){
			u.paginado.style.visibility = "visible";
			u.paginado.style.visibility = "visible";
			u.d_atrasdes.style.visibility = "hidden";
			u.primero.style.visibility = "hidden";
			totalDatos = parseInt(u.contadordes.value / 30);
		}else{
			u.paginado.style.visibility = "hidden";
		}
		consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=7&tipo=1&inicio=0&empleado=<?=$_SESSION[IDUSUARIO] ?>");
	}
	
	function paginacion(tipo){
		if(tipo == "atras"){
			u.d_sigdes.style.visibility = "visible";
			u.d_ultimo.style.visibility = "visible";
			u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
			if(parseFloat(u.totaldes.value) <= "1"){
				u.totaldes.value = "00";
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				if(u.ultimo.value == "SI"){
					var con = parseInt(u.contadordes.value / 30) - 1;
					u.totaldes.value = con * 30;
					u.ultimo.value = "";
					consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=7&tipo=1&inicio="+u.totaldes.value+"&empleado=<?=$_SESSION[IDUSUARIO] ?>");
				}else{
					u.d_atrasdes.style.visibility = "hidden";
					u.primero.style.visibility = "hidden";
					consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=7&tipo=1&inicio=0&empleado=<?=$_SESSION[IDUSUARIO] ?>");
				}
			}else{
				if(sepaso!=0){
					u.mostrardes.value = sepaso;
					sepaso = 0;
				}
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				if(u.ultimo.value == "SI"){
					var con = parseInt(u.contadordes.value / 30) - 1;
					u.totaldes.value = con * 30;
					u.ultimo.value = "";
				}
				consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=7&tipo=1&empleado=<?=$_SESSION[IDUSUARIO] ?>&inicio="+u.totaldes.value);
			}
		}else{
			cont++;
			u.d_atrasdes.style.visibility = "visible";
			u.primero.style.visibility = "visible";
			u.totaldes.value = inicio + parseFloat(u.totaldes.value);
			if(parseFloat(u.totaldes.value) > parseFloat(u.contadordes.value)){
				u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					u.mostrardes.value = u.contadordes.value;
				}
				u.d_sigdes.style.visibility = "hidden";
				u.d_ultimo.style.visibility = "hidden";
			}else{
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					sepaso	=	u.mostrardes.value;
					u.mostrardes.value = u.contadordes.value;
				}
				if(cont>=totalDatos){
					u.d_sigdes.style.visibility = "hidden";
					u.d_ultimo.style.visibility = "hidden";
					cont = 0;
				}
				consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=7&tipo=1&empleado=<?=$_SESSION[IDUSUARIO] ?>&inicio="+u.totaldes.value);
			}
		}
	}
	
	function obtenerPrimero(){
		u.totaldes.value = "00";
		u.d_sigdes.style.visibility = "visible";
		u.d_ultimo.style.visibility = "visible";
		consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=7&tipo=1&empleado=<?=$_SESSION[IDUSUARIO] ?>&inicio="+u.totaldes.value);
	}
	
	function obtenerUltimo(){
		u.ultimo.value = "SI";
		u.d_sigdes.style.visibility = "hidden";
		u.d_ultimo.style.visibility = "hidden";
		consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=9&empleado=<?=$_SESSION[IDUSUARIO] ?>");
	}	
	function nuevo(){
		fecha = "";
		u.referencia.value = "";
		tabla1.clear();
		obtenerGeneral();
	}	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo41 {font-size: 12px}
-->
</style>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Datos Generales </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="5" align="right">Sucursal: &nbsp;&nbsp;<input name="sucursal" type="text" class="Tablas" style="width:150px" value="<?=$f->descripcion  ?>" readonly="true" /></td>
        </tr>
        
        <tr>
          <td>F. Compromiso:</td>
          <td colspan="2"><input name="fecha" type="text" class="Tablas" id="fecha" />
            <img src="../img/calendario.gif" alt="Baja" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" /></td>
          <td colspan="2">Referencia:
            <input name="referencia" type="text" class="Tablas" id="referencia" style="width:80px" onclick="buscarFactura(this.value)" /></td>
        </tr>
        
        <tr>
          <td width="95">&nbsp;</td>
          <td width="153"><label></label></td>
          <td width="31">&nbsp;</td>
          <td width="175"><label><img src="../img/Boton_Generar.gif" onclick="generarDetalle()" style="cursor:pointer; visibility:hidden" /></label></td>
          <td width="342"><img src="../img/Boton_AsignarAct.gif" onclick="agregarResponsableSeleccionados()" style="cursor:pointer;" /></td>
        </tr>
        <tr>
          <td colspan="5" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5"><div style=" height:230px; width:790px; overflow:auto">
		  <table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></div></td>
        </tr>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5">
		  <div id="paginado" align="center" style="visibility:hidden">
		  <input name="totaldes" type="hidden" id="totaldes" value="00" />
            <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
            <img src="../img/first.gif" style="cursor:pointer" id="primero"  onclick="obtenerPrimero()" /> <img src="../img/previous.gif" style="cursor:pointer" id="d_atrasdes" onclick="paginacion('atras')" /> <img src="../img/next.gif" style="cursor:pointer" id="d_sigdes" onclick="paginacion('siguiente')" /> <img src="../img/last.gif" style="cursor:pointer" id="d_ultimo" onclick="obtenerUltimo()" />
            <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" />
            <input name="mostrardes2" class="Tablas" type="hidden" id="mostrardes2" value="<?=$tdes; ?>" />
            <input name="ultimo" class="Tablas" type="hidden" id="ultimo" />
			<input name="registros" class="Tablas" type="hidden" id="registros" />
		  </div>			</td>
        </tr>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5"><table width="166" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="83"><table width="166" border="0" align="right" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="83"><div id="d_Guardar" class="ebtn_guardar" onclick="guardar();"></div></td>
                  <td width="83" align="right"><div class="ebtn_nuevo" onClick="mens.show('C','Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'nuevo();')"></div></td>
                </tr>
              </table></td>
              <td width="83" align="right">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
