<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_POST[accion]=="guardar"){		
		if($_POST[registros] > 0){
			$s = "DELETE FROM depositoscajachica";
			mysql_query($s,$l) or die($s);
			
			for($i=0;$i<$_POST[registros];$i++){
				$s = "INSERT depositoscajachica SET 
				keysucursal = '".trim($_POST['detalle_IDSUCURSAL'][$i])."',
				prefijosucursal = '".$_POST['detalle_SUCURSAL'][$i]."',				
				totalcajachica = '".str_replace('$ ','',str_replace(',','',$_POST['detalle_TOTAL_CAJA_CHICA'][$i]))."',
				fecha = NOW()";
				$sq = mysql_query($s,$l) or die($s);
				
				$detalle .= "{
				idsucursal:'".trim($_POST["detalle_IDSUCURSAL"][$i])."',
				sucursal:'".trim($_POST["detalle_SUCURSAL"][$i])."',
				total:'".str_replace('$ ','',str_replace(',','',$_POST['detalle_TOTAL_CAJA_CHICA'][$i]))."'},";
			}
			$detalle = substr($detalle,0,strlen($detalle)-1);			
		}
	}	
	

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js" language="javascript"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js" language="javascript"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js" language="javascript"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js" language="javascript"></script>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script>
	var seleccionado = "";
	var estiloanterior = "";
	var u = document.all;
	var tabla1 	= new ClaseTabla();
	var divModificar = '<div class="ebtn_modificar" onClick="agregarFila()"></div>';
	var divAgregar = '<div class="ebtn_agregar" id="btnAgregar" onClick="agregarFila()"></div>';
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:100, alineacion:"center", datos:"sucursal"},
			{nombre:"TOTAL_CAJA_CHICA", medida:150, alineacion:"right", tipo:"moneda", datos:"total"},
			{nombre:"IDSUCURSAL", medida:4, alineacion:"left", tipo:"oculto", datos:"idsucursal"}			
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"modificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();		
		obtenerDepositos();
		obtenerDetalles();		
	}
	
	function obtenerDetalles(){
		var datosTabla = <? if($detalle!=""){echo "[".$detalle."]";}else{echo "0";} ?>;
		if(datosTabla!=0){		
			for(var i=0; i<datosTabla.length;i++){
				tabla1.add(datosTabla[i]);		
			}			
		}		
	}
	
	function obtenerDepositos(){
		consultaTexto("mostrarDepositos","cajachica_con.php?accion=1");
	}
	
	function mostrarDepositos(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
			
			if(tabla1.getRecordCount()>0){
				tabla1.setSelectedById("detalle_id0")
			}else{
				u.Eliminar.style.visibility = "hidden";	
			}
		}
	}
	
	function limpiartodo(){		
		u.sucursal.value 			= "";
		u.totalcajachica.value 		= 0;
		u.idsucursal.value			= "";
		u.modificar.value			= "";
		u.registros.value			= "";
		tabla1.clear();
		u.tdAgregar.innerHTML		= divAgregar;
	}	
	
	var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57) || key==46);
	}
	
	function BuscarSucursal(){
		abrirVentanaFija('buscarSucursal.php', 550, 450, 'ventana', 'Busqueda');
	}
	
	function ObtenerSucursal(id, prefijo){
		document.getElementById('idsucursal').value=id;
		document.getElementById('sucursal').value=prefijo;
		u.totalcajachica.select();
	}
	
	function agregarFila(){		
		if(u.sucursal.value == ""){
			alerta3("Debe capturar Sucursal","¡Atención!");
			return false;
		}else if(u.totalcajachica.value == "" || u.totalcajachica.value == "0"){
			alerta("Debe captura Total de caja chica","¡Atención!","totalcajachica");
			return false;
		}else if(parseFloat(u.totalcajachica.value) <= 0){
			alerta("El Total de caja chica debe ser mayor a Cero","¡Atención!","totalcajachica");
			return false;
		}
		
		var obj = new Object();
		obj.sucursal	= u.sucursal.value;
		obj.total		= u.totalcajachica.value;
		obj.idsucursal	= u.idsucursal.value;
		
		var id_sucursal = tabla1.getValuesFromField('idsucursal',',');
		
		if(u.modificar.value == ""){
			if(id_sucursal.indexOf(obj.idsucursal)>-1){
				for(var i=0;i<tabla1.getRecordCount();i++){
					if(u["detalle_IDSUCURSAL"][i].value == obj.idsucursal){
						tabla1.setSelectedById("detalle_id"+i);
					}
				}
				tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
			}else{
				tabla1.add(obj);
			}
		}else{
			tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
			u.tdAgregar.innerHTML = divAgregar;
		}
		u.sucursal.value 		= "";
		u.totalcajachica.value 	= 0;
		u.idsucursal.value 		= "";
		if(tabla1.getRecordCount() > 0){		
			u.Eliminar.style.visibility = "visible";
		}
	}
	
	function eliminarFila(){
		if(tabla1.getValSelFromField('sucursal','SUCURSAL')!=""){
			confirmar('¿Esta seguro de Eliminar la Fila?','','borrarFila()','');
		}
	}
	
	function borrarFila(){
		tabla1.deleteById(tabla1.getSelectedIdRow());
		if(u.totalcajachica.value != "0"){
			u.totalcajachica.value = "0";			
		}
		u.tdAgregar.innerHTML = divAgregar;
		u.sucursal.value = "";
		u.idsucursal.value = "";
		u.modificar.value = "";
		if(tabla1.getRecordCount()==0){		
			u.Eliminar.style.visibility = "hidden";			
		}
	}
	
	function validar(){
		if(tabla1.getRecordCount()==0){
			alerta3("Debe capturar por lo menos una sucursal al detalle","¡Atención!");
			return false;
		}else{
			u.registros.value = tabla1.getRecordCount();
			u.accion.value = "guardar";
			document.form1.submit();
		}
	}
	
	function modificarFila(){
		if(tabla1.getValSelFromField("sucursal","SUCURSAL")!=""){
			var obj = tabla1.getSelectedRow();
			u.idsucursal.value = obj.idsucursal;
			u.sucursal.value = obj.sucursal;
			u.totalcajachica.value = obj.total;
			u.modificar.value = "si";
			u.tdAgregar.innerHTML = divModificar;
		}
	}	
</script>
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></link>
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
.seleccionado{
	background-color:#3482C0;
}
</style>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
</head>
<body>

<form id="form1" name="form1" method="post" action="configuradordepositoscajachica.php">
  <br>
<table width="260" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193" class="Tablas">
  <tr>
    <td width="279" class="FondoTabla Estilo4">CONFIGURADOR DEP&Oacute;SITOS CAJA CHICA</td>
  </tr>
  <tr>
    <td><table width="260" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="99">Sucursal:
          <input type="hidden" name="registros"></td>
        <td width="200"><input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$_POST['sucursal'] == "" ? $sucursal : $_POST['sucursal'] ?>" readonly=""/>
            <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="BuscarSucursal()"></td>
      </tr>
      <tr>
        <td>Total Caja Chica:</td>
        <td><input name="totalcajachica" type="text" class="Tablas" id="totalcajachica" style="width:100px" value="0" onKeyPress="if(event.keyCode==13){agregarFila();}else{ return solonumeros(event);}" /></td>
      </tr>
      <tr>
        <td><input name="accion" type="hidden" id="accion" value="<?=$_POST[accion] ?>"></td>
        <td><table width="157" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="60"><div class="ebtn_eliminar" id="Eliminar" onClick="eliminarFila()"></div></td>
              <td align="right" width="84" id="tdAgregar"><div class="ebtn_agregar" id="btnAgregar" onClick="agregarFila()"></div></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><table width="257" border="0" align="left" cellpadding="0" cellspacing="0" id="detalle">
        </table></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><table width="172" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td><div class="ebtn_guardar" onClick="validar()"></div></td>
              <td><div class="ebtn_nuevo" onClick="limpiartodo()"></div></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<input name="modificar" type="hidden" id="modificar">
<input type="hidden" name="idsucursal" value="<?=$_POST['idsucursal'] == "" ? $idsucursal : $_POST['idsucursal'] ?>" />
</form>
</body>
</html>