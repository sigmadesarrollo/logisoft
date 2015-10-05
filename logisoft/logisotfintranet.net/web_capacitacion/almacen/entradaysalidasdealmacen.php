<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$_GET[accion]?>de almacen</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../javascript/DataSet.js"></script>
<script language="javascript" src="../javascript/jquery-1.4.js"></script>
</head>
<script>
	
	var u 		= document.all;
	var tabla1 = new ClaseTabla();
	var dataSet1 = new DataSet();
	var modulo = "<?=$_GET[accion]?>";
	
	tabla1.setAttributes({
		nombre:"datosguias",
		campos:[
			{nombre:"No_GUIA", medida:79, alineacion:"left", datos:"guia"},
			{nombre:"CANTIDAD", medida:60, alineacion:"center", datos:"cantidad"},
			{nombre:"TIPOGUIA", medida:79, alineacion:"left", datos:"tipoguia"},
			{nombre:"FECHA", medida:60, alineacion:"center", datos:"fecha"},
			{nombre:"SEL", medida:50, tipo:"checkbox", alineacion:"center", datos:"seleccion", onClick:"dioClick"}
		],
		filasInicial:15,
		alto:200,
		seleccion:false,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	function dioClick(valor, index){
		var fila = tabla1.getRowByIndex(index);
		fila.seleccion = ((valor)?'S':'1');
		dataSet1.actualizarRegistro(fila,null,index);
	}
	
	window.onload = function(){
		dataSet1.crear({
			'paginasDe':60,
			'objetoTabla':tabla1,
			'objetoPaginador':document.getElementById('celdacosa'),
			'nombreVariable':'dataSet1',
			'ubicacion':'../',
			'funcionOrdenar':function(a,b){
				return parseInt(a.guia.toString().substring(0,12)+a.guia.toString().charCodeAt(12))-
				parseInt(b.guia.toString().substring(0,12)+b.guia.toString().charCodeAt(12))
			}
		});
		
		tabla1.create();
	}
	
	function limpiarGuias(){
		tabla1.clear();
		dataSet1.limpiar();
	}
	
	function limpiarTodo(){
		limpiarGuias();
		document.getElementById('chktodos').checked=false;
		u.radiobutton[0].checked = false;
		u.radiobutton[1].checked = false;
		u.guardado.value		 = "0";
		u.guardado.value = "";
		bloquearTodo(false);
	}
	
	function bloquearTodo(valor){
		u.radiobutton[0].disabled = valor;
		u.radiobutton[1].disabled = valor;
	}
	
	function pedirGuiasEAD(valor){
		sucursalorigen 	= '<?=$_SESSION[IDSUCURSAL]?>';
		consultaTexto("mostrarGuiasEAD","entradaysalidasdealmacen_conjson.php?accion="+valor+"&sucorigen="+sucursalorigen+"&ramsd="+Math.random());
	}
	
	function mostrarGuiasEAD(datos){
		//u.folio.value = datos.getElementsByTagName('foliomax').item(0).firstChild.data;
		var datosx = eval(datos);
		dataSet1.setJsonData(datosx);
	}
	
	function guardarSeleccion(){
		<?=$cpermiso->verificarPermiso(314,$_SESSION[IDUSUARIO]);?>
		var cantreg	= u.cantidadregistros.value;
		var folios 	= "";
		
		for(var i=0; i<dataSet1.totalRegistros; i++){
			if(dataSet1.registros[i].seleccion=='S'){
				folios += ((folios!="")?",":"")+dataSet1.registros[i].guia;
			}
		}
		
		if(folios==""){
			alerta("No hay guias para dar "+((u.radiobutton[0].checked)?"Salida":"Entrada"),"¡Atencion!","guia");
		}else if(u.guardado.value!=""){
			alerta3("Esta "+((u.radiobutton[0].checked)?"Salida":"Entrada")+" ya fue guardada","¡Atencion!");
		}else{
			$.ajax({
			   type: "POST",
			   url: "entradaysalidasdealmacen_conjson.php",
			   data: "accion=3&dar="+((u.radiobutton[0].checked)?"1":"2")+"&folio="+folios,
			   success: respuestaGuardar
			 });
		}
	}
	
	function respuestaGuardar(datos){
		if(datos.indexOf('ok')>-1){
			info("Los datos han sido guardados","¡Atencion!");
			bloquearTodo(true);
			if(document.all.radiobutton[0].checked == true){
				pedirGuiasEAD(1);
			}else if(document.all.radiobutton[1].checked == true){
				pedirGuiasEAD(2);
			}
		}else{
			alerta3("Error al guardar<br>"+datos,"¡Atencion!");
		}
	}
	
	function seleccionarUnaGuia(valor){
		var indice = dataSet1.buscarYMostrar(valor,'guia');
		if(indice != false || indice===0){
			document.all.guia.value = "";
			var fila = dataSet1.registros[indice];
			if(fila.seleccion=='S'){
				alerta3("La guia "+valor+" fue seleccionada","ATENCION");
				return false;
			}else{
				fila.seleccion = 'S';
				dataSet1.actualizarRegistroSinMostrar(fila,1,indice);
			}
		}else{
			alerta("No se encontro el numero de guia", "¡Atencion!","guia");
		}
		dataSet1.refrescar();
	}
	
	function seleccionarTodos(valor){
		var total = tabla1.getRecordCount();
		for(var i=0; i<dataSet1.totalRegistros; i++){
			var fila = dataSet1.registros[i];
			
			fila.seleccion = ((valor)?'S':'1');
			dataSet1.actualizarRegistroSinMostrar(fila,1,i);
		}
		dataSet1.primero();
	}
</script>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="361" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="357" class="FondoTabla Estilo4">ENTRADA Y SALIDA DE ALMACÉN</td>
  </tr>
  <?
  	$s = "select date_format(current_date, '%d/%m/%Y') as fecha";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
  ?>
  <tr>
    <td><table width="365" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="6"></td>
      </tr>
      <tr>
        <td colspan="6"><label></label>
          <table width="356" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="5"><label></label></td>
    <td width="71"><input name="radiobutton" type="radio" value="1" onclick="pedirGuiasEAD(this.value);u.guardado.value='';" />
Salida</td>
    <td width="105"><input name="radiobutton" type="radio" value="2" onclick="pedirGuiasEAD(this.value);u.guardado.value='';" />
Entrada <input type="hidden" name="guardado" /></td>
    <td width="51">Folio:</td>
    <td width="124"><input name="folio" type="text" class="Tablas" style="width:100px;background:#FFFF99" readonly=""/></td>
  </tr>
</table></td>
        </tr>
      <tr>
        <td width="10">&nbsp;</td>
        <td width="28">Gu&iacute;a</td>
<td width="105"><span class="Tablas">
  <input name="guia" type="text" onkeypress="if(event.keyCode==13){seleccionarUnaGuia(this.value)}" class="Tablas" id="guia" style="width:100px" />
</span></td>
        <td width="38">&nbsp;</td>
        <td width="39"><span class="Tablas"> Fecha</span></td>
        <td width="145"><span class="Tablas">
          <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$f->fecha ?>" readonly=""/>
          <input type="hidden" name="cantidadregistros" />
        </span></td>
      </tr>
      <tr>
      	<td colspan="6">
        	<input type="checkbox" onclick="seleccionarTodos(this.checked)" id="chktodos"/> Todos
        </td>
      </tr>
      <tr>
        <td colspan="9"><table border="0" cellpadding="0" cellspacing="0" id="datosguias"></table></td>
      </tr>
      <tr>
        <td colspan="9" height="20px" id="celdacosa" align="right" style="border:1px #000 solid">
        </td>
      </tr>
      <tr>
        <td colspan="6" height="28" align="center" valign="middle">
        <table>
        	<tr>
            	<td>
			        <div class="ebtn_guardar" onclick="guardarSeleccion()"></div>                </td>
                <td>
			        <div class="ebtn_nuevo" onclick="confirmar('¿Desea borrar todos los datos?','¡Atencion!','limpiarTodo()','')"></div>                </td>
            </tr>
        </table>        </td>
      </tr>
    </table>  </tr>
</table>
</form>
</body>
</html>