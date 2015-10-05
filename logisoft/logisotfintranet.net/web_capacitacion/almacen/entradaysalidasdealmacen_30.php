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
</head>
<script>
	
	var u 		= document.all;
	var tabla1 = new ClaseTabla();
	var modulo = "<?=$_GET[accion]?>";
	var losdatos = "";
	
	tabla1.setAttributes({
		nombre:"datosguias",
		campos:[
			{nombre:"No_GUIA", medida:79, alineacion:"left", datos:"guia"},
			{nombre:"CANTIDAD", medida:60, alineacion:"center", datos:"cantidad"},
			{nombre:"TIPOGUIA", medida:79, alineacion:"left", datos:"tipoguia"},
			{nombre:"FECHA", medida:60, alineacion:"center", datos:"fecha"},
			{nombre:"SEL", medida:50, tipo:"checkbox", alineacion:"center", datos:"seleccion"}
		],
		filasInicial:15,
		alto:200,
		seleccion:false,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
	}
	
	function limpiarGuias(){
		tabla1.clear();
	}
	
	function limpiarTodo(){
		limpiarGuias();
		u.radiobutton[0].checked = false;
		u.radiobutton[1].checked = false;
		u.guardado.value		 = "0";
		u.guardado.value = "";
		bloquearTodo(false);
		document.getElementById('paginado_grid').style.display='none';
	}
	
	function bloquearTodo(valor){
		u.radiobutton[0].disabled = valor;
		u.radiobutton[1].disabled = valor;
	}
	
	function pedirGuiasEAD(valor){
		sucursalorigen 	= '<?=$_SESSION[IDSUCURSAL]?>';
		consultaTexto("mostrarGuiasEAD","entradaysalidasdealmacen_con30.php?accion="+valor+"&sucorigen="+sucursalorigen+"&ramsd="+Math.random());
	}
	var indice = 0;
	function mostrarGuiasEAD(datos){
		losdatos = eval(datos);
		//u.folio.value = datos.getElementsByTagName('foliomax').item(0).firstChild.data;
		tabla1.setJsonData(losdatos[indice]['datos']);
		if(losdatos.length>1){
			document.getElementById('paginado_grid').style.display='';
			ponerPagina();
		}
	}
	
	function siguiente(){
		if(losdatos[indice+1]!=null){
			indice++;
			tabla1.setJsonData(losdatos[indice+1]['datos']);
		}else
			alerta3("Esta en el ultimo registro","¡Atención!");
		ponerPagina();
	}
	
	function anterior(){
		if(indice-1>-1){
			tabla1.setJsonData(losdatos[indice]['datos']);
			indice--;
		}else
			alerta3("Esta en el ultimo registro","¡Atención!");
		ponerPagina();
	}
	
	function ultimo(){
		indice = losdatos.length-1;
		tabla1.setJsonData(losdatos[indice]['datos']);
		ponerPagina();
	}
	
	function primero(){
		indice = 0;
		tabla1.setJsonData(losdatos[indice]['datos']);
		ponerPagina();
	}
	
	function ponerPagina(){
		document.getElementById('pagina').innerHTML=(indice+1)+"-"+(losdatos.length);
	}
	
	function guardarSeleccion(){
		<?=$cpermiso->verificarPermiso(314,$_SESSION[IDUSUARIO]);?>
		var cantreg	= u.cantidadregistros.value;
		var folios 	= "";
		
		folios = tabla1.getValSelFromField("guia","SEL");
		
		if(folios==""){
			alerta("No hay guias para dar "+((u.radiobutton[0].checked)?"Salida":"Entrada"),"¡Atencion!","guia");
		}else if(u.guardado.value!=""){
			alerta3("Esta "+((u.radiobutton[0].checked)?"Salida":"Entrada")+" ya fue guardada","¡Atencion!");
		}else{
			consulta("respuestaGuardar","entradaysalidasdealmacen_con30.php?accion=3&dar="+((u.radiobutton[0].checked)?"1":"2")+"&folio="+folios+"&ramsd="+Math.random());
		}
	}
	
	function respuestaGuardar(datos){
		if(datos.getElementsByTagName("guardado").item(0).firstChild.data==1){
			info("Los datos han sido guardados","¡Atencion!");
			bloquearTodo(true);
			if(document.all.radiobutton[0].checked == true){
				pedirGuiasEAD(1);
			}else if(document.all.radiobutton[1].checked == true){
				pedirGuiasEAD(2);
			}
		}else{
			alerta3("Error al guardar","¡Atencion!");
		}
	}
	
	function seleccionarUnaGuia(valor){
		var u = document.all;
		var cantreg	= tabla1.getRecordCount();
		var guias = tabla1.getValuesFromField("guia");
		var guiasarre = guias.split(",");
		
		for(var i=0;i<cantreg;i++){
			if(guiasarre[i].toUpperCase() == valor.toUpperCase()){
				u["datosguias_SEL"][i].checked = true;
				u.guia.value = "";
				return true;
			}
		}
		u.guia.value = "";
		
		alerta("No se encontro el numero de guia", "¡Atencion!","guia");
	}
	
	function seleccionarTodos(valor){
		var total = tabla1.getRecordCount();
		
		for(var i=0; i<total; i++){
			u["datosguias_SEL"][i].checked = valor;
		}
	}
	
	function seleccionarTodos(valor){
		var cantreg = tabla1.getRecordCount();
		for(var i=0;i<cantreg;i++){
			u["datosguias_SEL"][i].checked = valor;
		}
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
        	<input type="checkbox" onclick="seleccionarTodos(this.checked)"/> Todos
        </td>
      </tr>
      <tr>
        <td colspan="9" id="celdacosa" align="right">
        <table width="78" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>        
        
        </td>
      </tr>
      <tr>
      	<td colspan="6" style="text-align:center">
        	<div id="paginado_grid" align="center" style="display:; width:363px; height:15px;">
            	<table width="162" border="0" cellpadding="0" cellspacing="0">
                <tr>
                <td>
              <img src="../img/first.gif" name="d_primero" width="16" height="16" style="cursor:pointer"  onclick="primero()" /> 
              <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" style="cursor:pointer" onclick="anterior()" /> 
              </td>
              <td>
              <div style="position:relative; width:50px; height:15px; text-align:center" id="pagina"></div>
              </td>
              <td>
              <img src="../img/next.gif" name="d_sigdes" width="16" height="16" style="cursor:pointer" onclick="siguiente()" /> 
              <img src="../img/last.gif" name="d_ultimo" width="16" height="16" style="cursor:pointer" onclick="ultimo()" />
              </td>
			  </tr>
              </table>
	        </div>
        </td>
      </tr>
      <tr>
        <td colspan="9" id="celdacosa"><table border="0" cellpadding="0" cellspacing="0" id="datosguias"></table></td>
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