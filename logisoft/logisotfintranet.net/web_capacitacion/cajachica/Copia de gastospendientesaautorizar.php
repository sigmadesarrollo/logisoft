<?
  session_start();
  $session_sucursal = $_SESSION['IDSUCURSAL'];
  
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
</style>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<?
	session_start();
	require_once("../Conectar.php");
	
	function cerrarcon($resultado, $conexion)
	{
		mysql_free_result($resultado);
		mysql_close($conexion);
	}
	
	if($_POST['enviar_datos'])
	{
		$conexion = Conectarse("webpmm");//********
		
		
		foreach($_POST['send_array_ids'] as $ids)	
		{
			$_POST['checkautorizar'.$ids] ? $autorizado = "S" : $autorizado = "N";
			$s = "UPDATE capturagastoscajachica SET
			  	 autorizado = '".$autorizado."', 
				 folioautorizacion = '".$_POST['folioautorizacion'.$ids]."', 
				 motivonoautorizacion = '".$_POST['motivonoautorizacion'.$ids]."'
				 WHERE id = '". $ids."'";
			
			$sq = mysql_query($s) or die($s);
		}
	}
	
	if($_POST[eliminarfolio]!=""){
		$conexion = Conectarse("webpmm");//********
		
		$s = "delete from capturagastoscajachica where id = $_POST[eliminarfolio]";
		$r = mysql_query($s) or die($s);
	}
?>

<form id="form1" name="form1" method="post" action="">
  <br>
<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="596" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td><div align="center">
      <table width="579" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><div align="center"></div></td>
        </tr>
        <tr>
          <td><table width="532" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="28">Gasto</td>
              <td width="488"><span class="Tablas">
                <select name="select_gasto" style="width:300px" onChange="document.form1.submit();">
                    <option value="0" <?=$_POST['select_gasto'] == "0" ? "selected" : "" ?> >Todos</option>
                    <option value="1" <?=$_POST['select_gasto'] == "1" ? "selected" : "" ?> >Gasto Mantenimiento Locales</option>
                    <option value="2" <?=$_POST['select_gasto'] == "2" ? "selected" : "" ?> >Gasto Vehículos Foráneos</option>
                    <option value="3" <?=$_POST['select_gasto'] == "3" ? "selected" : "" ?> >Inmobiliario y Equipo</option>
                </select>
              </span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="692" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="20" align="left"><div id="detalle" name="detalle" style="width:690px; height:300px; overflow:auto" align="left">
                  <? $line = 0; ?>
                  <table width="692" border="0" cellspacing="0" cellpadding="0">
                  <tr>
              <td width="4" height="30" style="background: url(../img/bordeb1_1.jpg) no-repeat right"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
			  <td width="34" align="left" background="../img/bordeb1_2.jpg" class="style5">Autorizar&nbsp;&nbsp;&nbsp;</td>
              <td width="17"  background="../img/bordeb1_2.jpg" class="style5" align="center">&nbsp;</td>
              <td width="128" background="../img/bordeb1_2.jpg" class="style5" align="center">Gasto</td>
              <td width="29" background="../img/bordeb1_2.jpg" class="style5" align="left">Sucursal</td>
              <td width="29" background="../img/bordeb1_2.jpg" class="style5" align="left">Folio Captura</td>
              <td width="40" background="../img/bordeb1_2.jpg" class="style5" align="left">Fecha Captura </td>
              <td width="40" align="left" background="../img/bordeb1_2.jpg" class="style5">No. Factura </td>
              <td width="48" align="left" background="../img/bordeb1_2.jpg" class="style5">Fecha Factura/Vale </td>
			  <td width="80" align="left" background="../img/bordeb1_2.jpg" class="style5">Proveedor </td>
			  <td width="100" align="left" background="../img/bordeb1_2.jpg" class="style5">Concepto </td>
			  <td width="112" align="left" background="../img/bordeb1_2.jpg" class="style5">Descripcion</td>
			  <td width="40" align="left" background="../img/bordeb1_2.jpg" class="style5">Total</td>
			  <td width="80" align="left" background="../img/bordeb1_2.jpg" class="style5">Folio Autorización </td>
			  <td width="100" align="left" background="../img/bordeb1_2.jpg" class="style5">Motivo No Autorización </td>
              <td width="12" background="../img/bordeb1_2.jpg" class="style5"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
              <td width="3"  background="../img/bordeb1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
            </tr>
                    <?		
					$conexion = Conectarse("webpmm");
            
                    $s = "SELECT tipogastodesc, prefijosucursal, id, DATE_FORMAT(fecha,'%d-%m-%Y') as fecha, factura, 
					      DATE_FORMAT(fechafacturavale,'%d-%m-%Y') as fechafacturavale, nombreproveedor,
						  descripcionconcepto, descripcion, total, autorizado, folioautorizacion, motivonoautorizacion
						  FROM capturagastoscajachica";
					$sWhere = " WHERE autorizado = 'N' AND (keyfoliosgastoscajachica = '' or ISNULL(keyfoliosgastoscajachica) 
						  or keyfoliosgastoscajachica = '0')";
					if($_POST['select_gasto'] > 0)
					{
						$sWhere .= " and tipogastoindex = ".$_POST['select_gasto'];
					}
					
					$s .= $sWhere." ORDER BY prefijosucursal";
                    $sq = mysql_query($s) or die($s);
                    
			while($row = mysql_fetch_object($sq))
			{
				$array_ids[] = $row->id; ?>
                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? //if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >
                      <td align="center">
                      	<img src="../img/delete.png" onClick="confirmar('Desea eliminar el gasto', '¡Atencion!', 'fEliminarGasto(<?=$row->id ?>)')" style="cursor:hand">
                      </td>
					  <td align="center" class="style31"  >
                      	<input type="checkbox" name="checkautorizar<?=$row->id ?>" id="checkautorizar<?=$row->id ?>" onClick="poneraescritura(<?=$row->id ?>)">
                      </td>
                      <td height="16"  >
                      	<input name="id<?=$row->id ?>" type="hidden" value="<?=$row->id ?>" />
                      </td>
                      <td align="center" class="style31"  >
                      	<input name="gasto<?=$row->id ?>" value="<?=$row->tipogastodesc ?>" type="text" class="style2" id="gasto<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="32"  />
                      </td>
                      <td align="center" class="style31">
                      	<input name="sucursal<?=$row->id ?>" value="<?=$row->prefijosucursal ?>" type="text" class="style2" id="sucursal<?=$row->id ?>" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="6" />
                      </td>
                      <td class="style31" align="center">
                      	<input name="folio<?=$row->id ?>" value="<?=$row->id ?>" type="text" class="style2" id="folio<?=$row->id ?>" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="6" />
                      </td>
                      <td class="style31" align="center">
                      	<input name="fecha<?=$row->id ?>" value="<?=$row->fecha ?>" type="text" readonly="" class="style2" id="fecha<?=$row->id ?>" style="font-size:8px; font:tahoma;font-weight:bold" size="10" />
                      </td>
                      <td  class="style31" align="center">
                      	<input name="factura<?=$row->id ?>" value="<?=$row->factura ?>" type="text" class="style2" id="factura<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" size="10" />
                      </td>
                      <td  class="style31" align="center">
                      	<input name="fechafacturavale<?=$row->id ?>" value="<?=$row->fechafacturavale ?>" type="text" class="style2" id="fechafacturavale<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" size="10" />
                      </td>
					  
					  <td  align="center" class="style31"  >
                      	<input name="proveedor<?=$row->id ?>" value="<?=$row->nombreproveedor ?>" type="text" class="style2" id="proveedor<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="20" />
                      </td>
					  <td align="center" class="style31"  >
                      	<input name="concepto<?=$row->id ?>" value="<?=$row->descripcionconcepto ?>" type="text" class="style2" id="concepto<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="25" />
                      </td>
					  <td align="center" class="style31"  >
                      	<input name="descripcion<?=$row->id ?>" value="<?=$row->descripcion ?>" type="text" class="style2" id="descripcion<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="28" />
                      </td>
					  <td align="center" class="style31"  >
                      	<input name="total<?=$row->id ?>" value="<?=number_format($row->total, 2, ',','.') ?>" type="text" class="style2" id="total<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="10" />
                      </td>
					  <td align="center" class="style31"  >
                      	<input name="folioautorizacion<?=$row->id ?>" value="<?=$row->folioautorizacion ?>" type="text" class="style2" id="folioautorizacion<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="20" />
                      </td>
					  <td align="center" class="style31"  >
                      	<input name="motivonoautorizacion<?=$row->id ?>" value="<?=$row->motivonoautorizacion ?>" type="text" class="style2" id="motivonoautorizacion<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="25" />
                      </td>
                      <td align="center" class="style31">&nbsp;</td>
                      <td ></td>
                    </tr>
                    <?
		$line ++ ; }
		if(count($array_ids) == 0)
		{ ?>
			<tr class="Balance">
                      <td align="center">&nbsp;</td>
					  <td align="center" class="style31"  >&nbsp;
                      	
                      </td>
                      <td height="16"  >
                      	<input name="id<?=$row->id ?>" type="hidden" value="<?=$row->id ?>" />
                      </td>
                      <td align="center" class="style31"  >
                      	<input name="gasto<?=$row->id ?>" value="<?=$row->tipogastodesc ?>" type="text" class="style2" id="gasto<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="32"  />
                      </td>
                      <td align="center" class="style31">
                      	<input name="sucursal<?=$row->id ?>" value="<?=$row->prefijosucursal ?>" type="text" class="style2" id="sucursal<?=$row->id ?>" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="6" />
                      </td>
                      <td class="style31" align="center">
                      	<input name="folio<?=$row->id ?>" value="<?=$row->id ?>" type="text" class="style2" id="folio<?=$row->id ?>" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="6" />
                      </td>
                      <td class="style31" align="center">
                      	<input name="fecha<?=$row->id ?>" value="<?=$row->fecha ?>" type="text" readonly="" class="style2" id="fecha<?=$row->id ?>" style="font-size:8px; font:tahoma;font-weight:bold" size="10" />
                      </td>
                      <td  class="style31" align="center">
                      	<input name="factura<?=$row->id ?>" value="<?=$row->factura ?>" type="text" class="style2" id="factura<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" size="10" />
                      </td>
                      <td  class="style31" align="center">
                      	<input name="fechafacturavale<?=$row->id ?>" value="<?=$row->fechafacturavale ?>" type="text" class="style2" id="fechafacturavale<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" size="10" />
                      </td>
					  
					  <td  align="center" class="style31"  >
                      	<input name="proveedor<?=$row->id ?>" value="<?=$row->nombreproveedor ?>" type="text" class="style2" id="proveedor<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="20" />
                      </td>
					  <td align="center" class="style31"  >
                      	<input name="concepto<?=$row->id ?>" value="<?=$row->descripcionconcepto ?>" type="text" class="style2" id="concepto<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="25" />
                      </td>
					  <td align="center" class="style31"  >
                      	<input name="descripcion<?=$row->id ?>" value="<?=$row->descripcion ?>" type="text" class="style2" id="descripcion<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="28" />
                      </td>
					  <td align="center" class="style31"  >
                      	<input name="total<?=$row->id ?>" value="<?=$row->total ?>" type="text" class="style2" id="total<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="10" />
                      </td>
					  <td align="center" class="style31"  >
                      	<input name="folioautorizacion<?=$row->id ?>" value="<?=$row->folioautorizacion ?>" type="text" class="style2" id="folioautorizacion<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="20" />
                      </td>
					  <td align="center" class="style31"  >
                      	<input name="motivonoautorizacion<?=$row->id ?>" value="<?=$row->motivonoautorizacion ?>" type="text" class="style2" id="motivonoautorizacion<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="25" />
                      </td>
                      <td align="center" class="style31">&nbsp;</td>
                      <td ></td>
                    </tr>
                    <?
		}
		cerrarcon($sq, $conexion);
	?>
                  </table>
              </div></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td align="right"><!--kryzzo home--><table width="100%" cellpadding="0" cellspacing="0">
            <tr><td width="50%" align="right"></td>
            <td width="50%" align="right">
              <div class="ebtn_guardar" onClick="<?=count($array_ids) > 0 ? "enviar()" : "alertanodatos()" ?>"></div>
            </td></tr>
          </table>
          </td>
        </tr>
        <tr>
          <td width="579">&nbsp;</td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>
<p align="center"><a href="../menu/webministator.php" ><img src="../img/inicio_30.gif" alt="Home" name="IMG0"  border="0"  id="IMG0" /></a></p>
<?
	$conexion = Conectarse("webpmm");//********
            
	$s = 'SELECT prefijo FROM catalogosucursal WHERE id= "'.$session_sucursal.'"';
	$sq = mysql_query($s) or die($s);
	$prefijo_sucursal =@mysql_result($sq,0);
	
	if(count($array_ids) > 0)
	{
		foreach ($array_ids as $ids)
		{
			echo '<input type=hidden name="send_array_ids[]" value="'.$ids.'">';
		}
	}

?>
<input type="hidden" name="session_sucursal" value="<?=$session_sucursal?>" />
<input type="hidden" name="prefijo_sucursal" value="<?=$prefijo_sucursal?>" />
<input type="hidden" name="enviar_datos" value="<?=$enviar_datos ?>">
<input type="hidden" name="eliminarfolio" value="">
</form>
</body>
<script language="javascript">
	parent.frames[1].document.getElementById('titulo').innerHTML = 'REPORTAR GASTOS';
	
	function fEliminarGasto(id){
		document.all.eliminarfolio.value=id; 
		document.form1.submit();
	}
	
	function poneraescritura(idactual)
	{
		if(document.getElementById('checkautorizar'+idactual).checked == false)
		{
			document.getElementById('folioautorizacion'+idactual).value = '';
			document.getElementById('motivonoautorizacion'+idactual).readOnly = false;
			return;
		}
		
		var a = new Array;
		<?
		for($i=0;$i<count($array_ids); $i++)
		{
			echo "a[$i]='".$array_ids[$i]."';\n";
		}			
	 	?>
		
		var today = new Date();
		dia = today.getDate().toString();
		if (dia.length == 1) dia = "0" + dia;
		mes = (today.getMonth() + 1).toString();
		if (mes.length == 1) mes = "0" + mes;
		anio = today.getFullYear().toString();
		hora = today.getHours().toString();
		if (hora.length == 1) hora = "0" + hora;
		minutos = today.getMinutes().toString();
		if (minutos.length == 1) minutos = "0" + minutos;
		segundos = today.getSeconds().toString();
		if (segundos.length == 1) segundos = "0" + segundos;
		
		var currentDate = dia + mes + anio +  hora + minutos + segundos;
		document.getElementById('folioautorizacion'+idactual).value = document.getElementById('sucursal'+idactual).value + currentDate;
		
		for(i=0;i<a.length;i++)
		{
			document.getElementById('motivonoautorizacion'+a[i]).readOnly = true;
			if(document.getElementById('checkautorizar'+a[i]).checked == false)
			{
				document.getElementById('motivonoautorizacion'+a[i]).readOnly = false;
			}
		}
	}
	
	function enviar(){		
		abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=GuiaVentanilla&usuario=Admin&funcion=enviarReal', 370, 500, 'ventana', 'Inicio de Sesión Secundaria');
	}
	
	function enviarReal(){
		document.form1.enviar_datos.value = true;
		document.form1.submit();
	}
	
	function alertanodatos()
	{
		alerta('No hay gastos pendientes a autorizar en la lista ','¡Atención!','select_gasto');
		return false;
	}

</script>
</html>
