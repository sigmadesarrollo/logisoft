<?
  session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
  $session_sucursal = $_SESSION['IDSUCURSAL'];

  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<!--<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />-->
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js" language="javascript"></script>
<script src="../javascript/ajax.js" language="javascript"></script>
<script src="../javascript/funciones.js" language="javascript"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js" language="javascript"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js" language="javascript"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js" language="javascript"></script>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></link>
<script>
	function obtener(valor){
		document.all.unidad.value = valor;
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
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	
	function cerrarcon($resultado, $conexion)
	{
		mysql_free_result($resultado);
		mysql_close($conexion);
	}
		
	$fecha = date("d/m/Y");
	
	$conexion = Conectarse("webpmm");//********
	
	$tipo_gasto_index = $_POST['tipo_gasto_index'];
	$tipo_gasto_texto = $_POST['tipo_gasto_texto'];	
	$idsucursal = $_POST['idsucursal'];	
	if($_POST['select_tipo']){
		$tipo_gasto_index = $_POST['select_tipo'];
		$tipo_gasto_texto = $_POST['tipo_gasto'];	
		
		$s = "SELECT id, prefijo FROM catalogosucursal WHERE id = '".$session_sucursal."'";		
		$sq = mysql_query($s) or die($s);
		if(mysql_num_rows($sq) > 0) 
		{
			$idsucursal = mysql_result($sq, 0, "id");
			$sucursal = mysql_result($sq, 0, "prefijo");
		}

		$s = "SELECT obtenerFolio('capturagastoscajachica',$_SESSION[IDSUCURSAL]) AS folio";		
		$sq = mysql_query($s) or die($s);
		if(mysql_num_rows($sq) > 0) $folio = mysql_result($sq, 0);
	}
	
	$s = "SELECT diaspermitidos FROM configuradorgeneral";		
	$sq = mysql_query($s) or die($s);
	if(mysql_num_rows($sq) > 0) $diaspermitidos = mysql_result($sq, 0);
?>
<form id="form1" name="form1" method="post" action="capturagastoscajachica.php">
  <br>
<table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="535" class="FondoTabla Estilo4">CAPTURA GASTOS CAJA CHICA</td>
  </tr>
  <tr>
    <td><table width="535" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="259"><table width="534" border="1">
          </table>
          <table width="532" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="532" colspan="4"></td>
              </tr>
          </table>          </td>
      </tr>
      <tr>
        <td><table width="532" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="73">Sucursal</td>
            <td width="100"><span class="Tablas">
              <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$_POST['sucursal'] == "" ? $sucursal : $_POST['sucursal'] ?>" readonly=""/>
            </span></td>
            <td width="26"><div class="ebtn_buscar"></div></td>
            <td width="24">Folio</td>
            <td width="100"><span class="Tablas">
              <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$_POST['folio'] == "" ? $folio : $_POST['folio'] ?>" readonly=""/>
            </span></td>
            <td width="27"><div class="ebtn_buscar" onClick="BuscarFolio()"></div></td>
            <td width="29">Fecha</td>
            <td width="153"><span class="Tablas">
              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$_POST['fecha'] == "" ? $fecha : $_POST['fecha'] ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      
      <tr>
        <td><table width="534" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><table width="532" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="73">Tipo Pago</td>
                <td width="213"><span class="Tablas">
                  <select name="select_tipopago" class="Tablas" style="width:200px; text-transform:uppercase">
                  	<option value="0" style="text-transform:none">SELECCIONAR</option>
                    <option value="1" <?=$_POST['tipo_pago_index'] == "1" ? "selected" : "" ?>>Caja Chica</option>
                    <option value="2" <?=$_POST['tipo_pago_index'] == "2" ? "selected" : "" ?>>Prepagado</option>
                    <option value="3" <?=$_POST['tipo_pago_index'] == "3" ? "selected" : "" ?>>Pago Proveedor</option>
                  </select>
                </span></td>
                <td width="57"><?=(($_POST['tipo_gasto']=='Prestamo')?"Bitacora":"Unidad")?></td>
            <td width="114"><span class="Tablas">
              <input name="unidad" type="text" class="Tablas" id="unidad" style="width:110px;<?=(($_POST['tipo_gasto']=='Prestamo')?"background:#FFFF99":"")?>"
              <?=(($_POST['tipo_gasto']=='Prestamo')?"readonly":"")?> 
              value="<?=$_POST['unidad'] == "" ? "" : $_POST['unidad'] ?>"  readonly="" />
            </span></td>
            <td>
            	<div class="ebtn_buscar" onClick="BuscarUnidad()" style="<?=(($_POST['tipo_gasto']=='Prestamo')?"display:none":"")?>"></div>
                <img src="../img/Buscar_24.gif" alt="buscar" width="24" height="23" align="absbottom" 
                style="cursor:pointer;<?=(($_POST['tipo_gasto']=='Prestamo')?"":"display:none;")?>" 
                title="Buscar Prospecto" onClick="abrirVentanaFija('../corm/buscarBitacora.php?liquidada=1', 600, 500, 'ventana', 'Busqueda')"/>
               </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="532" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="73">No. Factura </td>
                <td width="105"><span class="Tablas">
                  <input name="nfactura" type="text" class="Tablas" id="nfactura" style="width:100px" value="<?=$_POST['nfactura'] == "" ? "" : $_POST['nfactura'] ?>"  onKeyDown="return tabular(event,this)" />
                </span></td>
                <td width="101">Fecha Factura/Vale </td>
                <td width="113"><span class="Tablas">
                  <input name="fecha_factura_vale" type="text" class="Tablas" id="fecha_factura_vale" style="width:174px;" readonly="" value="<?=$_POST['fecha_factura_vale'] == "" ? "" : $_POST['fecha_factura_vale'] ?>" />
                </span></td>
                <td><div class="ebtn_calendario" onClick="enableCalendar(this);"></div></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>
            <table width="532" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="73">Proveedor</td>
                <td width="100"><span class="Tablas">
                  <input name="proveedor" type="text" class="Tablas" id="proveedor" style="width:100px;<?=(($_POST['tipo_gasto']=='Prestamo')?"background:#FFFF99":"")?>"
                  <?=(($_POST['tipo_gasto']=='Prestamo')?"readonly":"")?>
                   value="<?=$_POST['proveedor'] == "" ? "" : $_POST['proveedor'] ?>" onKeyPress="if(event.keyCode==13){obtenerProveedorc(this.value);}" />
                </span></td>
                <td width="24"><div class="ebtn_buscar" onClick="BuscarProveedor()" style="<?=(($_POST['tipo_gasto']=='Prestamo')?"display:none":"")?>"></div></td>
                <td width="335"><span class="Tablas">
                  <input name="proveedorb" type="text" class="Tablas" id="proveedorb" style="width:283px;background:#FFFF99" value="<?=$_POST['proveedorb'] == "" ? "" : $_POST['proveedorb'] ?>" readonly=""/>
                </span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="532" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="73">Concepto</td>
                <td><span class="Tablas">
                  <select name="select_concepto" class="Tablas" style="width:405px; text-transform:uppercase">
                  	<option value="0" style="text-transform:none" >SELECCIONAR</option>
                    <?
                    $conexion = Conectarse("webpmm");//********
            
                    $s = "SELECT id, descripcion FROM catalogoconcepto ORDER BY id";
                    $sq = mysql_query($s) or die($s);
					
					while($row = mysql_fetch_array($sq))
					{ 
					?>
                    	<option value="<?=$row[0]?>" <? if($row[0]==$_POST['concepto_index']){ echo "selected";}?>><?=$row[1]?></option>
                    <?
					}
                    
                    cerrarcon($sq, $conexion);//********
					?>
                  </select>				  
                </span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="532" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="73">Subtotal</td>
                <td width="113"><span class="Tablas">
                  <input name="subtotal" type="text" class="Tablas" id="subtotal" style="width:100px;<?=(($_POST['tipo_gasto']=='Prestamo')?"background:#FFFF99":"")?>"
                  <?=(($_POST['tipo_gasto']=='Prestamo')?"readonly":"")?> value="<?=$_POST['subtotal'] == "" ? "0" : $_POST['subtotal'] ?>" onKeyPress="return Numeros(event)" onKeyUp="sumatotal()"  onKeyDown="return tabular(event,this)" />
                </span></td>
                <td width="37">IVA</td>
                <td width="115"><span class="Tablas">
                  <input name="iva" type="text" class="Tablas" id="iva" style="width:100px;<?=(($_POST['tipo_gasto']=='Prestamo')?"background:#FFFF99":"")?>"
                  <?=(($_POST['tipo_gasto']=='Prestamo')?"readonly":"")?>
                   value="<?=$_POST['iva'] == "" ? "0" : $_POST['iva'] ?>" onKeyPress="return Numeros(event)" onKeyUp="sumatotal()"  onKeyDown="return tabular(event,this)" />
                </span></td>
                <td width="42">Total</td>
                <td width="152"><span class="Tablas">
                  <input name="total" type="text" class="Tablas" id="total" 
                  style="width:100px; 
                  <?=(($_POST['tipo_gasto']=='Prestamo')?"":"background:#FFFF99;")?>
                  " value="<?=$_POST['total'] == "" ? "0" : $_POST['total'] ?>" 
                  <?=(($_POST['tipo_gasto']=='Prestamo')?"":"readonly")?>
                   onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" />
                </span></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="532" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="73">Descripci&oacute;n</td>
            <td ><span class="Tablas">
              <input name="descripcion" type="text" class="Tablas" id="descripcion" style="width:405px" value="<?=$_POST['descripcion'] == "" ? "" : $_POST['descripcion'] ?>"  onKeyDown="return tabular(event,this)"  />
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="532" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="73">Observaciones</td>
            <td ><span class="Tablas">
              <input name="observaciones" type="text" class="Tablas" id="observaciones" style="width:405px" value="<?=$_POST['observaciones'] == "" ? "" : $_POST['observaciones'] ?>"  onKeyDown="return tabular(event,this)"  />
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right">
        	<table width="532" border="0" cellpadding="0" cellspacing="0" >
              <tr >
                <td width="257" align="right"><div class="ebtn_guardar" onClick="validar()"></div></td>
                <td width="75" align="left"><div class="ebtn_nuevo" onClick="nuevo()"></div></td>
                <td width="200"><div class="ebtn_imprimir" onClick="top.parent[0].focus(); window.print();"></div></td>
              </tr>
            </table>        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
      <div align="center"></div></td>
  </tr>
</table>
</p>


<?
	if($_POST['enviar_datos'])
	{
		//Guardar datos
		$conexion = Conectarse("webpmm");
		
		//$autorizado = $_POST['tipo_gasto_index'] == "4" ? "S" : "N";
		$autorizado = "N";

		$fechaS = split("-", $_POST['fecha']);
		$fechaformat = $fechaS[2]."-".$fechaS[1]."-".$fechaS[0];

		$fechaS = split("-", $_POST['fecha_factura_vale']);
		$fechafacturaformat = $fechaS[2]."-".$fechaS[1]."-".$fechaS[0];
		$guardar = $_POST['guardar_datos'];
		switch ($_POST['guardar_datos']) 
		{				
			case "G": //Guardar Nuevo
				$sIni = 'INSERT ';
				$sFin = ', fechacreada = NOW()';
				$_POST['guardar_datos'] = 'S';
				break;
			case "S": //Sustituir
				$sIni = 'UPDATE ';
				$sFin = ', fechamod = NOW() WHERE id = "'.$_POST['folio'].'"';
				break;
		}
		$s = $sIni."capturagastoscajachica SET 
			  ".(($guardar == "G")?"folio = obtenerFolio('capturagastoscajachica',$_SESSION[IDSUCURSAL]),":"")."
			  keysucursal = '".$_POST['idsucursal']."',
			  prefijosucursal = '".$_POST['sucursal']."',
			  fecha = '".cambiaf_a_mysql($_POST['fecha'])."',
			  tipogastoindex = '".$_POST['tipo_gasto_index']."',
			  tipogastodesc = '".$_POST['tipo_gasto_texto']."',
			  tipopagoindex = '".$_POST['tipo_pago_index']."',
			  tipopagodesc = '".$_POST['tipo_pago_text']."',
			  keyunidad = '".$_POST['idunidad']."',
			  unidadnumeconomico = '".$_POST['unidad']."',
			  factura = '".$_POST['nfactura']."',
			  fechafacturavale = '".cambiaf_a_mysql($_POST[fecha_factura_vale])."',
			  keyproveedor = '".$_POST['proveedor']."',
			  nombreproveedor = '".$_POST['proveedorb']."',
			  keyconcepto = '".$_POST['concepto_index']."',
			  descripcionconcepto = '".$_POST['concepto_text']."',
			  subtotal = '".str_replace("$ ","",str_replace(",","",$_POST[subtotal]))."',
			  iva = '".str_replace("$ ","",str_replace(",","",$_POST[iva]))."',
			  total = '".str_replace("$ ","",str_replace(",","",$_POST[total]))."',
			  descripcion = UCASE('".$_POST['descripcion']."'),
			  observaciones = UCASE('".$_POST['observaciones']."'),
			  autorizado = '".$autorizado."' ".$sFin;
		if($sq = mysql_query($s)){
			if($guardar == "G"){
				$id = mysql_insert_id($conexion);
				$s = "SELECT folio FROM capturagastoscajachica WHERE id = ".$id;
				$r = mysql_query($s,$conexion) or die($s); $fo = mysql_fetch_object($r);
				$folio = $fo->folio;
			}
			 echo '<script language="javascript"> setTimeout("displaysavedialog()", 500); </script>';
		}else{ 
			die($s);		
		}
		
		mysql_close($conexion);
	}
	?>
    
<input type="hidden" name="idsucursal" value="<?=$idsucursal ?>" />
<input type="hidden" name="session_sucursal" value="<?=$session_sucursal?>" />
<input type="hidden" name="idunidad" value="<?=$_POST['idunidad'] ?>">
<input type="hidden" name="tipo_pago_text" value="<?=$tipo_pago_text ?>">
<input type="hidden" name="tipo_pago_index" value="<?=$tipo_pago_index ?>">
<input type="hidden" name="concepto_index" value="<?=$concepto_index ?>">
<input type="hidden" name="tipo_gasto_index" value="<?=$tipo_gasto_index ?>">
<input type="hidden" name="tipo_gasto_texto" value="<?=$tipo_gasto_texto ?>">
<input type="hidden" name="concepto_text" value="<?=$concepto_text ?>">
<input type="hidden" name="enviar_datos" value="<?=$enviar_datos ?>">
<input type="hidden" name="sustituir_datos" value="<?=$_POST['sustituir_datos'] ?>">
<input type="hidden" name="dias_permitidos" id="dias_permitidos" value="<?=$diaspermitidos?>">
<input type="hidden" name="guardar_datos" value="<?=$_POST['guardar_datos'] == "" ? "G" : $_POST['guardar_datos'] ?>">
<input type="hidden" name="tipo_gasto" value="<?=$_POST['tipo_gasto']?>">
<?
	if($_POST['sustituir_datos'] == 'S' ) echo '<script language="javascript"> setTimeout("desactivarcampos()", 500); </script>';
?>
</form>
</body>
<script>	
	var u = document.all;
	var nav4 = window.Event ? true : false;
	function Numeros(evt)
	{ 
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57) || key==46);
	}

	function submitform()
	{
		if(document.form1.tipo_gasto_index.value == "4")
			document.form1.action = "index.php";
		else
			document.form1.action = "gastospendientesaautorizar.php";		
		document.form1.submit();
	}
	
	function nuevo()
	{
		document.form1.action = "reportargastos.php";		
		document.form1.submit();
	}
	
	function validar()
	{
		var selindex = document.form1.select_tipopago.selectedIndex;
		if(document.form1.select_tipopago.options[selindex].value == 0)
		{
			alerta('Debe seleccionar un Tipo Pago','Atencion!','select_tipopago');
			return;
		}
		<?
			if($_POST[select_tipo]=='2' || $_POST[select_tipo]=='1'){
		?>
		if(document.form1.unidad.value == '')
		{
			alerta('Debe seleccionar una Unidad','Atencion!','unidad');
			return;
		}
		<?
			}
		?>
		if(document.form1.fecha_factura_vale.value == '')
		{
			alerta('Debe seleccionar una Fecha','Atencion!','fecha_factura_vale');
			return;
		}
		
		var today = new Date();
		dia = today.getDate();
		mes = today.getMonth();
		anio = today.getFullYear();			
		fechaSplit = document.form1.fecha_factura_vale.value.split('-');		
		fechafacturavale = new Date(fechaSplit[2], fechaSplit[1]-1, fechaSplit[0]);		
		fechaToday=new Date(anio,mes,dia);		
		
		if(fechafacturavale>fechaToday){
			alerta('Fecha Factura/Vale no debe ser mayor a la fecha actual','Atencion!','fecha_factura_vale');
			return;
		}
		
		if(fechaSplit[1]-1 < mes)
		{
			if(dia >= document.form1.dias_permitidos.value)
			{
				alerta('Fecha Factura/Vale excede los das permitidos para captura de gastos del mes anterior','Atencion!','fecha_factura_vale');
				return;
			}
		}
		
		selindex = document.form1.select_concepto.selectedIndex;
		if(document.form1.select_concepto.options[selindex].value == 0)
		{
			alerta('Debe seleccionar un Concepto','Atencion!','select_concepto');
			return;
		}
		enviar();
	}
	
	function enviar()
	{
		var selindex = document.form1.select_tipopago.selectedIndex;
		document.form1.tipo_pago_text.value = document.form1.select_tipopago.options[selindex].text;
		
		selindex = document.form1.select_concepto.selectedIndex;
		document.form1.concepto_text.value = document.form1.select_concepto.options[selindex].text;
		
		document.form1.enviar_datos.value = true;
		document.form1.tipo_pago_index.value = document.form1.select_tipopago.selectedIndex;
		document.form1.concepto_index.value = document.form1.select_concepto.selectedIndex;
		document.form1.submit();
	}
	
	function sumatotal()
	{
		document.form1.total.value = parseFloat(document.form1.subtotal.value) + parseFloat(document.form1.iva.value);
	}
	
	function BuscarFolio(){
		idsucursal = document.form1.session_sucursal.value;
		idtipogasto = document.form1.tipo_gasto_index.value;
		abrirVentanaFija('buscarFolio.php?tipogasto='+ idtipogasto +'&sucursal=' + idsucursal, 550, 450, 'ventana', 'Busqueda')
	}
	
	function desactivarcampos()
	{
		document.form1.select_tipopago.disabled = true;
		document.form1.select_tipopago.style.background = '#FFFF99';
		document.form1.unidad.readOnly = true;
		document.form1.unidad.style.background = '#FFFF99';
		document.form1.proveedor.readOnly = true;
		document.form1.proveedor.style.background = '#FFFF99';
		document.form1.select_concepto.disabled = true;
		document.form1.select_concepto.style.background = '#FFFF99';
		document.form1.subtotal.readOnly = true;
		document.form1.subtotal.style.background = '#FFFF99';
		document.form1.iva.readOnly = true;
		document.form1.iva.style.background = '#FFFF99';
		document.form1.descripcion.readOnly = true;
		document.form1.descripcion.style.background = '#FFFF99';
		if(document.form1.sustituir_datos.value == 'N')
		{
			document.form1.nfactura.readOnly = true;
			document.form1.nfactura.style.background = '#FFFF99';
			document.form1.fecha_factura_vale.readOnly = true;
			document.form1.fecha_factura_vale.style.background = '#FFFF99';
		}
	}
	
	function activarcampos()
	{
		document.form1.nfactura.readOnly = false;
		document.form1.nfactura.style.background = 'none';
		document.form1.fecha_factura_vale.readOnly = false;
		document.form1.fecha_factura_vale.style.background = 'none';
	}
	
	function ObtenerFolio(id){
		u.folio.value = id;
		consultaTexto("mostrarDatos","cajachica_con.php?accion=2&folio="+id);
	}
	
	function mostrarDatos(datos){
		if(datos.indexOf("no encontro")>-1){
			return false;
		}else{
			activarcampos();
			var obj = eval(convertirValoresJson(datos));
			document.all.idsucursal.value = obj[0].keysucursal;
			document.all.sucursal.value = obj[0].prefijosucursal;
			document.all.fecha.value = obj[0].fecha;
			document.all.tipo_gasto_index.value = obj[0].tipogastoindex;
			document.all.tipo_gasto_texto.value = obj[0].tipogastodesc;
			document.all.select_tipopago.selectedIndex = obj[0].tipopagoindex;
			document.all.tipo_pago_text.value = obj[0].tipopagodesc;
			document.all.idunidad.value = obj[0].keyunidad;
			document.all.unidad.value = obj[0].unidadnumeconomico;
			document.all.nfactura.value = obj[0].factura;
			document.all.fecha_factura_vale.value = obj[0].fechafacturavale;
			document.all.proveedor.value = obj[0].keyproveedor;
			document.all.proveedorb.value = obj[0].nombreproveedor;
			document.all.select_concepto.selectedIndex = obj[0].keyconcepto;
			document.all.concepto_text.value = obj[0].descripcionconcepto;
			document.all.subtotal.value = obj[0].subtotal;
			document.all.subtotal.value = "$ "+numcredvar(document.all.subtotal.value);
			document.all.iva.value = obj[0].iva;
			document.all.iva.value = "$ "+numcredvar(document.all.iva.value);
			document.all.total.value = obj[0].total;
			document.all.total.value = "$ "+numcredvar(document.all.total.value);
			document.all.descripcion.value = obj[0].descripcion;
			document.all.sustituir_datos.value = obj[0].sustituir;
			document.all.guardar_datos.value = 'S';
			desactivarcampos();
		}
	}
	
	function BuscarUnidad()
	{
		if(document.form1.guardar_datos.value == 'S') return;
		idsucursal = document.form1.session_sucursal.value;
		<?
			$mas = "";
			if($_POST['tipo_gasto']=='Gasto Mantenimiento Locales'){
				$mas = "&tiporuta=LOCAL";
			}
			if($_POST['tipo_gasto']=='Gasto Vehículos Foráneos'){
				$mas = "&tiporuta=FORANEA";
			}
		?>
		abrirVentanaFija('buscarUnidad.php?sucursal=' + idsucursal + '<?=$mas?>', 550, 450, 'ventana', 'Busqueda')
	}
	
	function ObtenerUnidad(folio, unidad){
		document.getElementById('idunidad').value=folio;
		document.getElementById('unidad').value=unidad;
	}
	
	function BuscarProveedor()
	{
		if(document.form1.guardar_datos.value == 'S') return;
		abrirVentanaFija('buscarProveedor.php', 550, 450, 'ventana', 'Busqueda')
	}
	
	function ObtenerProveedor(folio, razon){
		document.getElementById('proveedor').value=folio;
		document.getElementById('proveedorb').value=razon;
	}
	
	function enableCalendar(control){
		if(document.form1.sustituir_datos.value == 'N') return;
		displayCalendar(document.all.fecha_factura_vale,'dd/mm/yyyy',control);
	}
	
	function tabular(e,obj) 
	{
		tecla=(document.all) ? e.keyCode : e.which;
		if(tecla!=13) return;
		frm=obj.form;
		for(i=0;i<frm.elements.length;i++) 
			if(frm.elements[i]==obj) 
			{ 
				if (i==frm.elements.length-1) 
					i=-1;
				break 
			}

		if ( frm.elements[i+1].disabled == true || frm.elements[i+1].readOnly == true)
			tabular(e,frm.elements[i+1]);
		else 
		{
			frm.elements[i+1].focus();
			frm.elements[i+1].select();
		}
		return false;
	} 
	
	function displaysavedialog()
	{
		info("El gasto se ha guardado correctamente", "¡ATENCION!");
	}
	
	function obtenerProveedorc(id){
		consultaTexto("mostrarProveedor","cajachica_con.php?accion=4&proveedor="+id);
	}
	
	function mostrarProveedor(datos){		
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.proveedorb.value = obj[0].nombre;
			u.select_concepto.focus();
		}else{
			alerta("El numero de proveedor no existe","¡Atención!","proveedor");
			u.proveedorb.value = "";
		}
	}
	
</script>
</html>

