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
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></link>
<script language="javascript" src="../javascript/Mascara.js"></script>
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
</head>
<body>
<?
	require_once("../Conectar.php");
	
	function cerrarcon($resultado, $conexion)
	{
		mysql_free_result($resultado);
		mysql_close($conexion);
	}
	
	$conexion = Conectarse("webpmm");
	
	$idsucursalactual = 0;//Si no se envia sucursal que cargue la sucursal de session
	if($_POST['enviar_sucursal'])
	{
		$idsucursalactual = $_POST['idsucursal'];
	}
	else
	{
		$idsucursalactual = $session_sucursal;
		
		if($idsucursalactual > 0)
		{
			$s = "SELECT prefijo FROM catalogosucursal WHERE id = '".$idsucursalactual."'";
			$sq = mysql_query($s) or die($s);
			$sucursalprefijoactual = mysql_result($sq, 0);
		}
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
          <td><table width="599" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="49" rowspan="2">Sucursal</td>
              <td width="135" rowspan="2"><table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="105">
              <span class="Tablas">
               <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$_POST['sucursal'] == "" ? $sucursalprefijoactual : $_POST['sucursal'] ?>" readonly=""/>
              </span>
              </td>
              <td><div class="ebtn_buscar" onClick="BuscarSucursal()"></div>
                </td>
              </tr>
              </table>
              </td>
              <td width="65">Fecha Inicio </td>
              <td width="143"><table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="105">
              	<span class="Tablas">
                  <input name="fecha_inicio" type="text" class="Tablas" id="fecha_inicio" style="width:100px;"  value="<?=$_POST['fecha_inicio'] == "" ? "" : $_POST['fecha_inicio'] ?>" onKeyUp="mascara(this,'/',patron,true)" />
                  </span>
                  </td>
                 <td>
                <div class="ebtn_calendario" onClick="displayCalendar(document.all.fecha_inicio,'dd-mm-yyyy',this); "></div>
                </td>
                </tr>
              </table>  
              </td>
              <td width="35" rowspan="2">Estado</td>
              <td width="110" rowspan="2"><span class="Tablas"><!--kryzzo estado-->
                <select name="select_estado" style="width:120px">
                  	<option value="0" style="text-transform:none">Seleccione opción...</option>
                    <option value="1" <?=$_POST['select_estado'] == "1" ? "selected" : "" ?>>Pendiente</option>
                    <option value="2" <?=$_POST['select_estado'] == "2" ? "selected" : "" ?>>Autorizada</option>
                    <option value="3" <?=$_POST['select_estado'] == "3" ? "selected" : "" ?>>Reposición</option>
                </select>
              </span></td>
              <td rowspan="2"><div class="ebtn_aceptar" onClick="ValidaFecha();"></div></td>
            </tr>
            <tr>
              <td>Fecha Fin </td>
              <td width="143"><table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="105">
              	<span class="Tablas">
                  <input name="fecha_fin" type="text" class="Tablas" id="fecha_fin" style="width:100px;"  value="<?=$_POST['fecha_fin'] == "" ? "" : $_POST['fecha_fin'] ?>"  onKeyUp="mascara(this,'/',patron,true)" />
                  </span>
                  </td>
                 <td>
                <div class="ebtn_calendario" onClick="displayCalendar(document.all.fecha_fin,'dd-mm-yyyy',this); ValidaFechaFin()"></div>
                </td>
                </tr>
              </table> </td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="532" border="0" align="left" cellpadding="0" cellspacing="0">
            
            <tr>
              <td colspan="20" align="right"><div id="detalle" name="detalle" style="width:590px; height:300px; overflow:auto" align="left">
                  <? $line = 0; ?>
                  <table width="574" border="0" cellspacing="0" cellpadding="0">
                  <tr>
              <td width="9" height="16" class="formato_columnas_izq"></td>
              <td width="125" class="formato_columnas" align="left">Folio Reposicion </td>
              <td width="83" class="formato_columnas" align="left">Estado Reposicion </td>
              <td width="56" class="formato_columnas" align="left">Prefijo</td>
              <td width="60" class="formato_columnas" align="left">Gerente </td>
              <td width="61" align="left" class="formato_columnas">No. Cheque </td>
              <td width="71" align="left" class="formato_columnas">Fecha Cheque </td>
			  <td width="57" align="center" class="formato_columnas">Cantidad </td>
			  <td width="64" align="center" class="formato_columnas">No. C. Interno </td>
              <td width="9" class="formato_columnas_der"></td>
            </tr>
                    <?		
					$conexion = Conectarse("webpmm");
            
                    $s = "SELECT folio, prefijosucursal, nombregerente, 
						 cheque, DATE_FORMAT(fechacheque,'%d-%m-%Y') as fecha, 
						 totalgasto, foliocorreointerno, 
						 IF(estado = 1 or estado = 0, 'PENDIENTE', IF(estado = 2, 'AUTORIZADA', 'REPOSICION')) AS estado
						 FROM foliosgastoscajachica
						 WHERE keytipopagoindex = '1' ";
						 
					$sWhere = "";
					if($idsucursalactual > 0)
						$sWhere = " AND keysucursal = '".$idsucursalactual."'";
					
					if($_POST['select_estado'] > 0)
						$_POST['select_estado'] == 1 ? $sWhere .= " AND (estado = '1' or estado = '0')" : $sWhere .= " AND estado  = '".$_POST['select_estado']."'";	
					
					if($_POST['fecha_inicio'])
					{
						$fechaIniS = split("-", $_POST['fecha_inicio']);
						$fechaIni = $fechaIniS[2]."-".$fechaIniS[1]."-".$fechaIniS[0];
						$fechaFinS = split("-", $_POST['fecha_fin']);
						$fechaFin = $fechaFinS[2]."-".$fechaFinS[1]."-".$fechaFinS[0];
						$sWhere .= " AND DATE(fechagenerada) BETWEEN '".$fechaIni."' AND '".$fechaFin."'";
					}
                    $s .= $sWhere;
					//echo "<br><br>$s<br><br>";
					$sq = mysql_query($s) or die($s);
                    
					$totalgastos = 0;
					while($row = mysql_fetch_object($sq))
					{  
					  $array_ids[] = $row->id; ?>
                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? /*if ($line==0){ echo "style='visibility:hidden;display:none'" ;} */?>  >
                      <td height="16" width="17" ><input name="id" type="hidden" value="<?=$row->folio ?>" /></td>
                      <td width="32" align="center" class="style31"  ><input name="folio" type="text" class="style2" id="folio" readonly="" style="font-size:8px; font:tahoma; font-weight:bold; text-align:center" size="8" value="<?=$row->folio ?>" /></td>
                      <td width="40" align="center" class="style31"><input name="estado" type="text" class="style2" id="estado" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="20" value="<?=$row->estado ?>" /></td>
                      <td width="44" class="style31" align="left"><input name="prefijo" type="text" class="style2" id="prefijo" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="11" value="<?=$row->prefijosucursal ?>" /></td>
                      <td width="40" align="center" class="style31"><input name="gerente" type="text" readonly="" class="style2" id="gerente" style="font-size:8px; font:tahoma;font-weight:bold" size="30" value="<?=$row->nombregerente ?>" /></td>
                      <td width="40" class="style31" align="center"><input name="nocheque" type="text" class="style2" id="nocheque" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" size="10" value="<?=$row->cheque ?>" /></td>
                      <td width="40" class="style31" align="center"><input name="fechachequeal" type="text" class="style2" id="fechachequeal" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" size="10" value="<?=$row->fecha ?>" /></td>
					  
					  <td width="32" align="center" class="style31"  ><input name="cantidad" type="text" class="style2" id="cantidad" readonly="" style="font-size:8px; font:tahoma; font-weight:bold; text-align:right" size="8" value="<?=number_format($row->totalgasto, 2) ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="nocinterno" type="text" class="style2" id="nocinterno" readonly="" style="font-size:8px; font:tahoma; font-weight:bold; text-align:right" size="8" value="<?=$row->foliocorreointerno ?>" /></td>
                      <td width="21" align="center" class="style31">&nbsp;</td>
                    </tr>
                    <?
				$line ++ ; 
			}	
			if(count($array_ids) > 0)
			{
				foreach ($array_ids as $ids)
				{
					echo '<input type=hidden name="send_array_ids[]" value="'.$ids.'">';
				}
			}		
	?>
                  </table>
              </div></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><table width="600" border="0" cellpadding="0" cellspacing="0">
            <tr><!--kryzzo home-->
              <td width="50%" align="right"></td>
              <td align="right"><div class="ebtn_Exportarexcel"></div></td>
              <td width="72" align="right"><div class="ebtn_imprimir"></div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="579">&nbsp;</td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>
<input type="hidden" name="idsucursal" value="<?=$_POST['idsucursal'] == "" ? $idsucursalactual : $_POST['idsucursal'] ?>" />
<input type="hidden" name="enviar_sucursal" value="<?=$_POST['enviar_sucursal'] == "" ? $enviar_sucursal : $_POST['enviar_sucursal'] ?>">
</form>
</body>
<script>
	function BuscarSucursal()
	{
		abrirVentanaFija('buscarSucursal.php', 550, 450, 'ventana', 'Busqueda');
	}
	
	function ObtenerSucursal(id, prefijo)
	{
		document.getElementById('idsucursal').value=id;
		document.getElementById('sucursal').value=prefijo;
		document.form1.enviar_sucursal.value = true;
		document.form1.submit();
	}
	
	function ValidaFecha()
	{
		if( (document.form1.fecha_inicio.value != '' && document.form1.fecha_fin.value == '') || (document.form1.fecha_fin.value != '' && document.form1.fecha_inicio.value == '') )
		{
			alerta('Debe seleccionar Fecha Inicio y Fecha Fin para continuar','¡Atención!','fecha_inicio');
			return;
		}
		
		var today = new Date();
		dia = today.getDate();
		mes = today.getMonth();
		anio = today.getFullYear();			
		fechainiSplit = document.form1.fecha_inicio.value.split('-');
		fechafinSplit = document.form1.fecha_fin.value.split('-');
		fechaInicio = new Date(fechainiSplit[2], fechainiSplit[1]-1, fechainiSplit[0]);
		fechaFin = new Date(fechafinSplit[2], fechafinSplit[1]-1, fechafinSplit[0]);
		
		if(fechaInicio>fechaFin){
			alerta('Fecha Inicio no debe ser mayor a Fecha Fin','¡Atención!','fecha_inicio');
			return;
		}
		
		document.form1.submit();
	}
	
	parent.frames[1].document.getElementById('titulo').innerHTML = 'REPORTE CONSOLIDADO GASTOS CAJA CHICA';
</script>
</html>
