<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<!--<link href="../recepciones/estilos_estandar.css" rel="stylesheet" type="text/css" />-->
<script>
	var huella;
	var u = document.all;	
	var tabla1 = new ClaseTabla();
	//var tabla2 = new ClaseTabla();	
	var v_sucursal = "";
	var permisoVerificado = "0";
	
	tabla1.setAttributes({
	nombre:"detalle",
	campos:[
			{nombre:"UNIDAD", medida:80, alineacion:"left", datos:"unidad"},
			{nombre:"RUTA", medida:100, alineacion:"left", datos:"ruta"},
			{nombre:"HR_LLEGADA", medida:70, alineacion:"center", datos:"llegada"},
			{nombre:"HR_SALIDA", medida:90, alineacion:"center", datos:"salida"},
			{nombre:"FECHA", medida:90, alineacion:"center", datos:"fecha"},
			{nombre:"TIPO", medida:4, alineacion:"center", tipo:"oculto", datos:"tipo"},
			{nombre:"FOLIO", medida:4, alineacion:"center", tipo:"oculto", datos:"folio"},
			{nombre:"COND1", medida:4, alineacion:"center", tipo:"oculto", datos:"conductor1"},
			{nombre:"COND2", medida:4, alineacion:"center", tipo:"oculto", datos:"conductor2"},
			{nombre:"COND3", medida:4, alineacion:"center", tipo:"oculto", datos:"conductor3"},
			{nombre:"CHK1", medida:4, alineacion:"center", tipo:"oculto", datos:"chk1"},
			{nombre:"CHK2", medida:4, alineacion:"center", tipo:"oculto", datos:"chk2"},
			{nombre:"CHK3", medida:4, alineacion:"center", tipo:"oculto", datos:"chk3"},
			{nombre:"IDBitacora", medida:4, alineacion:"center", tipo:"oculto", datos:"IDBitacora"}
			],
		filasInicial:14,
		alto:200,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	/*tabla2.setAttributes({
	nombre:"detalle2",
	campos:[
			{nombre:"No_GUIA", medida:100, alineacion:"center", datos:"guia"},
			{nombre:"PAQUETE", medida:100, alineacion:"center", datos:"paquete"},
			{nombre:"CODIGO_DE_BARRAS", medida:70, alineacion:"center", datos:"codigobarras"},
			{nombre:"ESTADO", medida:90, alineacion:"center", datos:"estado"}
		],
		filasInicial:14,
		alto:100,
		seleccion:true,
		ordenable:false,
		//eventoClickFila:"ObtDetalleIzq()",
		nombrevar:"tabla2"
	});*/
	
	window.onload = function(){
		tabla1.create();
		//tabla2.create();
		u.idsucursal.value = '<?=$_SESSION[IDSUCURSAL]?>';
		mostrarGeneral();
		
		try{
			document.getElementById('DGhuella').ponerBaseDatos("pmm_curso","www.pmmintranet.net");
			document.getElementById('DGhuella').iniciar()
			verificarSiPuso();
		}catch(e){
			e=null;
		}
	}
	
	window.onbeforeunload = function(){
		//huella.Finalizar();
	}
	
	function verificarSiPuso(){
		if(document.getElementById('DGhuella').lecturasNecesitadas()<4){
			setTimeout("solicitarVerificacion()",500);
		}
		setTimeout("verificarSiPuso()","1000");
	}
	
	function solicitarVerificacion(){
		seleccionarUnidad(document.getElementById('DGhuella').IdentificaUsuario());
	}
	
	function seleccionarUnidad(idempleado){
		if(idempleado==0){
			alerta3("Huella dactilar no registrada. \nPor favor intente de nuevo","Atencion");
			return false;
		}
		for(var i=0; i<tabla1.getRecordCount();i++){			
			/*if((document.all['detalle_COND1'][i].value==idempleado || document.all['detalle_COND2'][i].value==idempleado || document.all['detalle_COND3'][i].value==idempleado) && (document.all['detalle_TIPO'][i].value == 1 || document.all['detalle_TIPO'][i].value == 3)){
				tabla1.setSelectedById("detalle_id"+i);
				info("Usuario encontrado","�Atenci�n!");
				var guardar = true;
				if(document.all['detalle_COND1'][i].value==idempleado){document.all['detalle_CHK1'][i].value=1;}
				if(document.all['detalle_COND2'][i].value==idempleado){document.all['detalle_CHK2'][i].value=1;}
				if(document.all['detalle_COND3'][i].value==idempleado){document.all['detalle_CHK3'][i].value=1;}				
				if(document.all['detalle_COND1'][i].value!="0" && document.all['detalle_CHK1'][i].value!=1){
					guardar = false;
				}
				if(document.all['detalle_COND2'][i].value!="0" && document.all['detalle_CHK2'][i].value!=1){
					guardar = false;
				}
				if(document.all['detalle_COND3'][i].value!="0" && document.all['detalle_CHK3'][i].value!=1){
					guardar = false;
				}
				if(guardar==true){
					mostrarDatosPrecintos();
				}
				return false;
			}*/
			if((document.all['detalle_COND1'][i].value==idempleado || document.all['detalle_COND2'][i].value==idempleado || document.all['detalle_COND3'][i].value==idempleado)){
				//se kito esta validacion del if -> ( && document.all['detalle_TIPO'][i].value == 2)
				tabla1.setSelectedById("detalle_id"+i);				
				mostrarDatosPrecintos();
				return false;				
			}
		}
		alerta3("No se encontro el Conductor de la unidad","Atencion");
	}
	
	function mostrarGeneral(){
		consultaTexto("obtenerGeneral","programacionRecepcionDiaria_con.php?accion=2&sucursal=<?=$_SESSION[IDSUCURSAL]?>&val="+Math.random());
	}
	function obtenerGeneral(datos){

		var objeto = eval(datos);
		u.fecha.value = objeto[0].fecha;
		u.sucursal.value = objeto[0].sucursal;
		
		consultaTexto("mostrarDetalle","programacionRecepcionDiaria_con.php?accion=6&sucursal=<?=$_SESSION[IDSUCURSAL]?>&val="+Math.random());
		
	}	
	function mostrarDetalle(datos){
		var objeto = eval(datos);
		tabla1.setJsonData(objeto);	
	}
	
	function mostrarDatosPrecintos(){
		<?=$cpermiso->verificarPermiso("300,305",$_SESSION[IDUSUARIO]);?>
		if(tabla1.getSelectedIdRow()==""){
			alerta3('Debe seleccionar una Unidad','�Atenci�n!');
		}else{
			consultaTexto("mostrarLosPrecintos","programacionRecepcionDiaria_con.php?accion=17&unidad="+tabla1.getSelectedRow().unidad+"&val="+Math.random());
		}
	}
	
	function ponerPermiso(){
		<?=$cpermiso->verificarPermiso("411",$_SESSION[IDUSUARIO]);?>
		abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=llegadaUnidad&usuario=Admin&funcion=validarDatosLogueo',370, 500, 'ventana', 'Inicio de Sesi�n Secundaria');	
	}
	
	function validarDatosLogueo(datos){
		permisoVerificado = "1";
		mostrarDatosPrecintos();
	}
	
	function mostrarLosPrecintos(datos){
		alerta3(datos,"");
		var obj = eval(datos);
		//validacion para saber si se brinco sucursales
		if(obj.encontro>0 && permisoVerificado=="0"){
			confirmar("Existen sucursales en las que no se ha recibido la unidad\n"+obj.sucursales+"\n�Desea continuar?","�ATENCION!","ponerPermiso()");
		}else{
			var arr = tabla1.getSelectedRow();
			abrirVentanaFija('registroPrecintosRecepcion.php?brincosucursal='+permisoVerificado+'&sucursal=<?=$_SESSION[IDSUCURSAL]?>&ruta='+arr.ruta
			+'&unidad='+arr.unidad+'&tipo=llegada',600, 550, 'ventana', 'Busqueda','checarAsignacionPrecinto();');
			permisoVerificado = "0";
		}
	}
	
	function horaLlegada(){
		if(tabla1.getSelectedIdRow()==""){
			alerta3('Debe seleccionar una Unidad','�Atenci�n!');
		}else{
			//consultaTexto("obtenerHoraLlegadaSalida","programacionRecepcionDiaria_con.php?accion=3&tipo=llegada");			
			obtenerHoraLlegadaSalida('llegada',obtenerHora());
		}
	}
	function horaSalida(){
		<?=$cpermiso->verificarPermiso("301,306",$_SESSION[IDUSUARIO]);?>
		var arr = tabla1.getSelectedRow();
		if(tabla1.getSelectedIdRow()==""){
			alerta3('Debe seleccionar una Unidad','�Atenci�n!');
			return false;
		}
		if(arr.tipo!="1"){
			if(tabla1.getValSelFromField('llegada','HR_LLEGADA')=="00:00:00"){
				alerta3('Debe agregar Hora de Llegada','�Atenci�n!');
				return false;
			}
		}
		//consultaTexto("obtenerHoraLlegadaSalida","programacionRecepcionDiaria_con.php?accion=3&tipo=salida");
		obtenerHoraLlegadaSalida('salida',obtenerHora());
	}
	function obtenerHoraLlegadaSalida(tipo,hora){		
		if(tipo == "llegada"){			
			u.horallegada.value = "";
			u.horallegada.value = hora;
			u.tipo.value = tipo;
			actualizarFila(tipo);
		}else{
			u.horasalida.value  = "";
			u.horasalida.value = hora;
			u.tipo.value = tipo;
			actualizarFila(tipo);
		}
	}	
	function actualizarFila(tipo){	
		var arr = tabla1.getSelectedRow();
		var obj		=   Object();
		obj.unidad	=	arr.unidad;
		obj.ruta	=	arr.ruta;
		obj.IDBitacora	=	arr.IDBitacora;
		if(arr.tipo=="1"){
			obj.llegada	=	"00:00:00";
		}else{
			obj.llegada	=	((tipo=="llegada")? u.horallegada.value : arr.llegada);
		}
		if(arr.tipo=="3"){
			obj.salida	=	"00:00:00";
		}else{
			obj.salida	=	((tipo!="llegada")? u.horasalida.value : arr.salida);		
		}
		obj.folio	=	arr.folio;
		obj.tipo	=	arr.tipo;
		obj.conductor1	=	arr.conductor1;
		obj.conductor2	=	arr.conductor2;
		obj.conductor3	=	arr.conductor3;
		obj.chk1		=	arr.chk1;
		obj.chk2		=	arr.chk2;
		obj.chk3		=	arr.chk3;
		obj.fecha		=	arr.fecha;
		tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
		var miArray =   Array();
		miArray[0]	=	obj.folio;		
		miArray[1]	=	obj.llegada;
		miArray[2]	=	obj.salida;
		miArray[3]	=	obj.IDBitacora;
		consultaTexto("insertarDatos","programacionRecepcionDiaria_con.php?accion=7&arre="+miArray
		+"&unidad="+arr.unidad+"&sucursal="+u.idsucursal.value+"&val="+Math.random());
	}
	function insertarDatos(datos){
		var array=datos.split(',');
		if(datos.indexOf("guardo")>-1){	
			info("La unidad a sido recepcionada correctamente","�ATENCION!");
			mostrarGeneral();
			//tabla2.clear();
		}else{
			alerta3(datos,"Error al guardar");
		}
	}
	function checarAsignacionPrecinto(f){
		if(f!=undefined || u.agrego.value=="SI"){
			u.agrego.value="";
			var arr = tabla1.getSelectedRow();
			if(tabla1.getValSelFromField('salida','HR_SALIDA')!="00:00:00"){
				tabla1.deleteById(tabla1.getSelectedIdRow());	
				//tabla2.clear();
			}
			if(arr.tipo=="2"){
				tabla1.deleteById(tabla1.getSelectedIdRow());
				//tabla2.clear();
			}
			if(arr.tipo=="3"){
				var unidad = arr.unidad;				
				tabla1.deleteById(tabla1.getSelectedIdRow());
				//tabla2.clear();
				consultaTexto("insertarDatos2","programacionRecepcionDiaria_con.php?accion=16&unidad="+unidad
				+"&val="+Math.random());
			}
		}else{
			if(u.llegada.value == "SI"){
				u.agrego.value="";
				var arr = tabla1.getSelectedRow();
				var obj		=   Object();
				obj.unidad	=	arr.unidad;
				obj.ruta	=	arr.ruta;
				obj.llegada	=	arr.llegada;
				obj.salida	=	"00:00:00";
				obj.folio	=	arr.folio;
				obj.tipo	=	arr.tipo;
				obj.fecha		=	arr.fecha;
				obj.conductor1	=	arr.conductor1;
				obj.conductor2	=	arr.conductor2;
				tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
				var miArray =   Array();
				miArray[0]	=	obj.folio;		
				miArray[1]	=	obj.llegada;
				miArray[2]	=	obj.salida;
			consultaTexto("insertarDatos2","programacionRecepcionDiaria_con.php?accion=12&arre="+miArray
			+"&unidad="+arr.unidad+"&sucursal="+u.idsucursal.value+"&val="+Math.random());
			}else{
				u.agrego.value="";
				var arr = tabla1.getSelectedRow();
				var obj		=   Object();
				obj.unidad	=	arr.unidad;
				obj.ruta	=	arr.ruta;
				obj.llegada	=	"00:00:00";
				obj.salida	=	"00:00:00";
				obj.folio	=	arr.folio;
				obj.tipo	=	arr.tipo;		
				obj.fecha		=	arr.fecha;
				obj.conductor1	=	arr.conductor1;
				obj.conductor2	=	arr.conductor2;
				tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
				var miArray =   Array();
				miArray[0]	=	obj.folio;		
				miArray[1]	=	obj.llegada;
				miArray[2]	=	obj.salida;
				consultaTexto("insertarDatos2","programacionRecepcionDiaria_con.php?accion=12&arre="+miArray
				+"&unidad="+arr.unidad+"&sucursal="+u.idsucursal.value+"&val="+Math.random());
			}
			
		}
	}
	function insertarDatos2(datos){
		if(datos.indexOf("ok")<0){
			alerta3("Hubo un error "+datos,"�Atenci�n!");
		}
	}
	function agregoPrecintos(agrego){		
		u.agrego.value = agrego;		
	}
	function fueLlegada(agrego){
		u.llegada.value = agrego;
	}
	function obtenerDetalleAbajo(){
		if(tabla1.getValSelFromField('unidad','UNIDAD')!=""){
			consultaTexto("mostrarDetalleAbajo","programacionRecepcionDiaria_con.php?accion=5&unidad="+tabla1.getValSelFromField('unidad','UNIDAD')+"&val="+Math.random());
		}else{
			//tabla2.clear();
		}
	}
	function mostrarDetalleAbajo(datos){
		//tabla2.clear();
		var objeto = eval(datos);
		//tabla2.setJsonData(objeto);
		var arr = tabla1.getSelectedRow();
		if(arr.tipo=="1"){
			//u.imgllegada.style.visibility="hidden";
		}else if(arr.tipo=="2"){
			//u.imgllegada.style.visibility="visible";
		}else if(arr.tipo=="3"){
			//u.imgllegada.style.visibility="visible";
		}
	}	
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.style31 {font-size: 9px;
	color: #464442;
}
.style31 {font-size: 9px;
	color: #464442;
}
.style51 {color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="495" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="365" class="FondoTabla Estilo4">PROGRAMACION RECEPCION DIARIA</td>
  </tr>
  <tr>
    <td><table width="492" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="127" rowspan="2" align="center" bgcolor="#016193" style="font-size:12px; color:#FFF">
        	<OBJECT ID="DGhuella"
            CLASSID="CLSID:FB90BEF3-564C-48F7-B391-0EFE14A0BD08" width="121" height="129">
            </OBJECT>        
        </td>
        <td width="103" height="14">&nbsp;</td>
        <td width="68">&nbsp;</td>
        <td width="187">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="79" colspan="3" align="center">
          <table width="289">
            <tr>
              <td width="75" align="left"><span class="Tablas">Fecha:</span></td>
              <td width="257" align="left"><span class="Tablas">
                <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$_POSTfecha ?>" readonly=""/>
                </span></td>
              </tr>
            <tr>
              <td align="left"><span class="Tablas">Sucursal:</span></td>
              <td align="left"><span class="Tablas">
                <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:150px;background:#FFFF99" value="<?=$sucursal ?>" readonly=""/>
                </span></td>
              </tr>
            <tr>
              <td colspan="2" align="center"><span class="Tablas">&nbsp;<img src="../img/Boton_llegada.gif" name="imgllegada" width="70" height="20" align="absbottom" id="imgllegada" style="cursor:pointer;" onclick="mostrarDatosPrecintos();" />&nbsp;&nbsp;&nbsp;<img src="../img/Boton_salida.gif" width="70" height="20" id="imgsalida" align="absbottom" style="cursor:pointer;visibility:hidden" onclick="horaSalida()" /></span></td>
  </tr>
          </table></td>
        <td>&nbsp;</td>
        </tr>
              
      <tr>
        <td height="34" align="center" bgcolor="#016193"><span style="font-size:12px; color:#FFF">Proporcione su huella</span></td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="8" align="center"><table id="detalle" width="448" border="0" cellspacing="0" cellpadding="0">          
          </table>         </td>
      </tr>
	  <tr>
	    <td height="20" colspan="4" align="center">&nbsp;</td>
	    <td width="7" align="center">&nbsp;</td>
	  </tr>		
	  <tr>
        <td colspan="6" align="center">
          <input name="horallegada" type="hidden" id="horallegada" value="<?=$horallegada ?>">
          <input name="horasalida" type="hidden" id="horasalida" value="<?=$horasalida ?>">         
          <input name="idsucursal" type="hidden" id="idsucursal" value="<?=$_POST[idsucursal] ?>">
          <input name="agrego" type="hidden" id="agrego" value="<?=$_POST[agrego] ?>">
          <input name="tipo" type="hidden" id="tipo" value="<?=$_POST[tipo] ?>">
          <input name="llegada" type="hidden" id="llegada" value="<?=$_POST[llegada] ?>"></td>
      </tr>     
    </table>
  </tr>
</table>
</form>
</body>
</html>