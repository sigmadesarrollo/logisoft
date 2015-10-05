<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT descripcion FROM catalogosucursal WHERE id=$_GET[sucursal]";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	$s = "SELECT COUNT(*) FROM recepcionregistroprecintos WHERE unidad='".$_GET[unidad]."'";
	$sd= mysql_query($s,$l) or die($s);
	$total = mysql_result($sd,0)-1;	
	$s = "SELECT p.id, p.observaciones FROM recepcionregistroprecintos p
	LEFT JOIN bitacorasalida bs ON p.unidad = bs.unidad
	WHERE p.unidad='".$_GET[unidad]."' AND p.enruta = 0 GROUP BY p.unidad ORDER BY p.id DESC";
	$sq = mysql_query($s,$l) or die($s); $sql=mysql_fetch_object($sq);
	if(mysql_num_rows($sq)>0){
		$observaciones = trim($sql->observaciones);		
		$id = $sql->id;
	}
	
	$s = "SELECT folio FROM bitacorasalida WHERE unidad = '".$_GET[unidad]."' AND STATUS = 0 and cancelada=0";
	$r = mysql_query($s,$l) or die($s); 
	$f = mysql_fetch_object($r);
	$bitacora = $f->folio;
	
	$s = "SELECT d.sucursal, d.tipo FROM bitacorasalida b
	INNER JOIN catalogoruta c ON b.ruta = c.id
	INNER JOIN catalogorutadetalle d ON c.id = d.ruta
	WHERE b.folio = ".$bitacora." AND d.sucursal = ".$_SESSION[IDSUCURSAL]."";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r); $tipo = $f->tipo;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	var v_contador = 0;
	var totalRemolques = "";
	tabla1.setAttributes({
	nombre:"detalle",
	campos:[
			{nombre:"REMOLQUE", medida:150, alineacion:"left", datos:"remolque"},
			{nombre:"PRECINTO", medida:110, alineacion:"left", datos:"precinto"},
			{nombre:"UBICACION", medida:170, alineacion:"left", datos:"ubicacion"},
			{nombre:"F. EMBARQUE", medida:70, alineacion:"left", datos:"fechaasignado"},
			{nombre:"FECHA2", medida:6, tipo:"oculto", alineacion:"left", datos:"fecha"},
			{nombre:"SUCURSAL", medida:6, tipo:"oculto", alineacion:"left", datos:"sucursal"}
		],
		filasInicial:14,
		alto:150,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	window.onload = function(){
		tabla1.create();
		u.d_eliminar.style.visibility = "hidden";
		u.d_siguiente.style.visibility = "hidden";
		u.precinto.focus();
		if(u.total.value<="0"){
		u.d_atras.style.visibility = "hidden"; 
		}
		if(u.tipo.value=="llegada"){
		u.d_agregar.style.visibility = "hidden";
		u.t_precinto.style.display	 = "none";
		}
		eliminarPrecintos();
	}	
	function eliminarPrecintos(){
	consultaTexto("obtenerRemolques","programacionRecepcionDiaria_con.php?accion=14");
	}
	function mostrarPrecintos(datos){
		var objeto = eval(datos);
		tabla1.setJsonData(objeto);
	}	
	function agregarValores(combo,objeto){	
		combo.options.length = 0;
		var opcion;
		opcion = new Option(objeto.remolque1);
		combo.options[combo.options.length] = opcion;
		
		var opcion;
		opcion = new Option(objeto.remolque2);
		combo.options[combo.options.length] = opcion;
	}
	function obtenerRemolques(){
		consultaTexto("mostrarRemolques","programacionRecepcionDiaria_con.php?accion=9&unidad="+u.unidad.value);
	}
	function mostrarRemolques(datos){
		var objeto = eval(convertirValoresJson(datos));
		if(objeto != undefined){
			agregarValores(u.remolques,objeto.remolques);
		}else{
			totalRemolques = "no";
		}
		consultaTexto("mostrarPrecintos","programacionRecepcionDiaria_con.php?accion=11&foliobitacora=<?=$bitacora?>&unidad="+u.unidad.value);
	}
	function obtenerPrecintosBusqueda(precinto){
		u.precinto.value = precinto;
	}
	function agregar(){
		var precinto = tabla1.getValuesFromField('precinto',',');		
		if(u.precinto.value ==""){
			alerta('Debe capturar Precinto','¡Atención!','precinto');
			return false;
			
		}else if(precinto.indexOf(u.precinto.value)>-1){
			alerta('El Precinto #'+u.precinto.value+' ya fue asignado','¡Atención!','precinto');
			return false;
			
		}else if(u.ubicacion.value==0){
			alerta('Debe capturar Ubicación','¡Atención!','ubicacion');
			return false;
		}
		
		var ubi = u.ubicacion.options[u.ubicacion.options.selectedIndex].text;
		var rem = u.remolques.options[u.remolques.options.selectedIndex].text;
		if(tabla1.getRecordCount()>0){
			for(var i=0;i<tabla1.getRecordCount();i++){
				if(totalRemolques==""){
					/*if(u["detalle_REMOLQUE"][i].value == rem && u["detalle_SUCURSAL"][i].value == '<?=$_SESSION[IDSUCURSAL] ?>'){
						alerta('Solo se puede agregar un precinto por remolque','¡Atención!','remolques');
						return false;
					}*/
					
					if(u["detalle_REMOLQUE"][i].value == rem && u["detalle_UBICACION"][i].value == ubi && u["detalle_SUCURSAL"][i].value == '<?=$_SESSION[IDSUCURSAL] ?>'){
						alerta('Solo se puede agregar un precinto por ubicacion','¡Atención!','ubicacion');
						return false;
					}					
				}else{
					if(u["detalle_UBICACION"][i].value == ubi && u["detalle_SUCURSAL"][i].value == '<?=$_SESSION[IDSUCURSAL] ?>'){
						alerta('Solo se puede agregar un precinto por ubicacion','¡Atención!','ubicacion');
						return false;
					}
				}
			}
		}
		
		var obj = Object();
		obj.remolque = u.remolques.options[u.remolques.options.selectedIndex].text;
		obj.ubicacion = u.ubicacion.options[u.ubicacion.options.selectedIndex].text;
		obj.precinto = u.precinto.value;
		obj.fechaasignado = '<?=date('d/m/Y') ?>';
		obj.fecha	= fechahora(obj.fecha);
		obj.sucursal= '<?=$_SESSION[IDSUCURSAL] ?>';
		tabla1.add(obj);
		u.d_eliminar.style.visibility = "visible";
		consultaTexto("registroAgregar","programacionRecepcionDiaria_con.php?accion=8&remolque="+obj.remolque
		+"&precinto="+obj.precinto+"&ubicacion="+obj.ubicacion+"&fecha="+obj.fecha+"&fechaasignado="+obj.fechaasignado
		+"&foliobitacora=<?=$_GET[foliopro]?>");
	}
	function registroAgregar(datos){
		if(datos.indexOf("ok")<0){
			alerta3('Hubo un error al agregar '+datos,'¡Atención!');
		}else{
			u.precinto.value = "";
			u.ubicacion.value = "";
		}
	}
	
	function guardarRevisarPrecintos(){
		//if(u.tipo2.value == "1" && tabla1.getRecordCount()==0){
		//	alerta3('No puede dar salida si no a registrado precintos','¡Atención!');
		//}else if(u.sinprecinto.checked == true){
		///	guardar();
		//}else if(tabla1.getRecordCount()==0){
		//	alerta3('No puede dar salida si no a registrado precintos','¡Atención!');
		//}else{
			guardar();
		//}
	}
	
	function guardar(){
		var v_fecha = fechahora(v_fecha);
		consultaTexto("registro","programacionRecepcionDiaria_con.php?accion=10&sucursal="+u.idsucursal.value
		+"&foliopro=<?=$_GET[foliopro]?>&ruta=<?=$_GET[ruta] ?>&unidad="+u.unidad.value+((u.id.value=="")? "&observaciones="+u.observaciones.value :"")+"&fechahora="+v_fecha);
	}
	function registro(datos){
		if(datos.indexOf("guardado")>-1){
			info('Los datos han sido guardados correctamente', '');
			if(u.tipo.value=="SALIDA" || u.tipo.value=="salida"){
				window.parent.agregoPrecintos('SI');
			}else{
				window.parent.fueLlegada('SI');
			}
		}else{
			alerta3('Hubo un error al guardar '+datos,'¡Atención!');
		}
	}
	function eliminar(){
		if(tabla1.getValSelFromField('precinto','PRECINTO')!=""){
			confirmar('¿Esta seguro de Eliminar la Fila?','','borrarFila()','');
		}
	}
	function borrarFila(){
		var arr = tabla1.getSelectedRow();
		consultaTexto("eliminarFila","programacionRecepcionDiaria_con.php?accion=15&fecha="+arr.fecha);		
	}
	function eliminarFila(datos){
		if(datos.indexOf("ok")>-1){
			tabla1.deleteById(tabla1.getSelectedIdRow());
		  	if(tabla1.getRecordCount()==0){		
				u.d_eliminar.style.visibility = "hidden";		
		  	}
		}else{
			alerta3("Hubo un error al eliminar "+datos,"¡Atención!");
		}
	}
	function verObservacion(tipo){	
		if(tipo=="atras"){
			var limit = parseInt(u.contador.value) - 1;			
			if(limit < 0){
				u.contador.value = 0;
			}else{
				u.contador.value = limit;
				u.d_siguiente.style.visibility = "visible";
			}
				
		}else{
			u.d_atras.style.visibility = "visible";
			var limit = parseInt(u.contador.value) + 1;
			if(limit > u.total.value){
				u.contador.value = u.total.value;
			}else{
				u.contador.value = limit;
			}
		}	
	consultaTexto("mostrarObservaciones","programacionRecepcionDiaria_con.php?accion=13&unidad="+u.unidad.value+"&limit="+limit);
	}
	function mostrarObservaciones(datos){
		if(datos!=0){
			var obj = eval(datos);
			if(datos!=0){
				u.observaciones.value = obj[0].observaciones;
				u.id.value = obj[0].id;
			}
			if(u.contador.value==0){
				u.d_atras.style.visibility = "hidden";
			}
			if(u.contador.value==u.total.value){
				u.d_siguiente.style.visibility = "hidden";
			}
		}
	}
	function validaObservaciones(e,obj){
		tecla = (u) ? e.keyCode : e.which;
		if((tecla==8 || tecla==46) && document.getElementById(obj).value==""){
			u.id.value = "";			
		}
	}
	function agregarObservaciones(){
		u.d_atras.style.visibility = "hidden";
		u.d_siguiente.style.visibility = "hidden";
		u.observaciones.value = "";
		u.id.value="";
		u.observaciones.readOnly=false;
		u.observaciones.style.backgroundColor='';
		u.observaciones.select();
	}
	
	function buscarPrecinto(){
		if (u.precinto.value!=''){
			consultaTexto("cargaPrecinto","programacionRecepcionDiaria_con.php?accion=16&sucursal="+<?=$_SESSION[IDSUCURSAL] ?>+"&precinto="+u.precinto.value);
		}else{
			alerta3("Debe escribir el folio del precinto a buscar","¡Atención!");
		}
	}
	
	function cargaPrecinto(datos){
		if(datos=='no encontro'){
			u.precinto.value="";
			alerta3('Este precinto no es valido seleccione otro');			
		}else{
			agregar();
		}
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<style type="text/css">

<!--

body {

	margin-left: 1px;

	margin-top: 1px;

	margin-right: 1px;

	margin-bottom: 1px;

}

-->

</style>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form name="form1" method="post" action="">
  <table width="500" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">REGISTRO DE PRECINTOS</td>
    </tr>
    <tr>
      <td><table width="480" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="2">&nbsp;</td>
            <td width="64">&nbsp;</td>
            <td width="168">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="idsucursal" type="hidden" id="idsucursal" value="<?=$_GET[sucursal] ?>" />
              <input name="tipo" type="hidden" id="tipo" value="<?=$_GET[tipo] ?>" />
              <input name="total" type="hidden" id="total" value="<?=$total ?>" />
              <input name="contador" type="hidden" id="contador" value="<?=$total ?>" />
              <input name="tipo2" type="hidden" id="tipo2" value="<?=$tipo ?>" /></td>
            <td>&nbsp;</td>
            <td><input name="sucursal" type="hidden" class="Tablas" id="sucursal" style="background:#FFFF99; width:160px" value="<?=$f->descripcion ?>" /></td>
          </tr>
          <tr>
            <td width="76">Unidad:</td>
            <td width="172"><input name="unidad" class="Tablas" type="text" id="unidad" style="background:#FFFF99; width:150px" value="<?=$_GET[unidad] ?>"></td>
            <td>Remolque:</td>
            <td><select name="remolques" class="Tablas" id="remolques" style="width:160px">
            </select></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4"><table width="479" border="0" cellpadding="0" cellspacing="0" id="t_precinto">
              <tr>
                <td width="76">Precintos:</td>
                <td width="162"><input name="precinto" class="Tablas" type="text" style="background:#FFFF99" id="precinto" onkeypress="if(event.keyCode==13){buscarPrecinto(this.value)};">
                  <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarPrecintosGen.php?funcion=obtenerPrecintosBusqueda&sucursal='+document.all.idsucursal.value, 450, 450, 'ventana', 'Busqueda');"></td>
                <td width="241"><select name="ubicacion" class="Tablas" id="ubicacion">
                  <option value="0">SELECCIONAR UBICACION</option>
                  <option value="1">LATERAL DERECHO</option>
                  <option value="2">LATERAL IZQUIERDA</option>
                  <option value="3">POSTERIOR</option>
                </select>
                  <img src="../img/Boton_Agregari.gif" width="70" height="20" id="d_agregar" align="absbottom" style="cursor:pointer" onClick="buscarPrecinto()"></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label>
            <input name="sinprecinto" type="checkbox" id="sinprecinto" style="width:13px" value="1" />
            Embarcar Sin Precinto </label></td>
            <td colspan="2">&nbsp;</td>
          </tr>
          
          <tr>
            <td colspan="4"><table width="440" id="detalle" border="0" cellspacing="0" cellpadding="0">
            </table></td>
          </tr>
		  <tr>
		    <td valign="top">&nbsp;</td>
		    <td colspan="3" align="right"><div id="d_eliminar" class="ebtn_eliminar" onClick="eliminar()"></div></td>
	      </tr>
		  <tr>
		    <td valign="top">Observaciones:</td>
		    <td colspan="3"><textarea name="observaciones" class="Tablas" id="observaciones" onKeyUp="return validaObservaciones(event,this.name)" <? if($observaciones!=""){echo 'readonly="readonly"';} ?> style="text-transform:uppercase;width:350px; <? if($observaciones!=""){ echo "background:#FFFF99";} ?>"><?=$observaciones ?></textarea>
	        <input name="id" type="hidden" id="id" value="<?=$id ?>"></td>
	      </tr>
		  <tr>
		    <td valign="top">&nbsp;</td>
	        <td colspan="3"><table width="310" border="0" align="left" cellpadding="0" cellspacing="0">
              <tr>
                <td width="78"><div id="d_atras" class="ebtn_atraz" onClick="verObservacion('atras')"></div></td>
                <td width="71"><div id="d_siguiente" class="ebtn_adelante" onClick="verObservacion('siguiente')"></div></td>
                <td width="161"><img src="../img/Boton_Agregar_Observacion.gif" align="absbottom" style="cursor:pointer" onClick="agregarObservaciones()"></td>
              </tr>
            </table>	          <label></label></td>
          </tr>
		  <tr>
            <td colspan="4"><table width="150" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td width="68">&nbsp;</td>
                <td width="82"><div id="d_guardar" class="ebtn_guardar" onClick="guardarRevisarPrecintos()"></div></td>
              </tr>
            </table></td>
          </tr>
      </table>
      <p>&nbsp;</p></td>
    </tr>
  </table>  
</form>
</body>
</html>
