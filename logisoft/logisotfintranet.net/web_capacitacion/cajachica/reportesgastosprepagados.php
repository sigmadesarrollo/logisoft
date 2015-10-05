<?  session_start();
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
	require_once("../Conectar.php");
	
	function cerrarcon($resultado, $conexion)
	{
		mysql_free_result($resultado);
		mysql_close($conexion);
	}
	
	$conexion = Conectarse("webpmm");
	
	$idsucursalactual = 0;//Si no se envia sucursal que cargue la sucursal de session
	if($_POST['enviar_sucursal']){
		$idsucursalactual = $_POST['idsucursal'];
	}else{
		$idsucursalactual = $session_sucursal;
		
		if($idsucursalactual > 0)
		{
			$s = "SELECT prefijo FROM catalogosucursal WHERE id = '".$idsucursalactual."'";
			$sq = mysql_query($s) or die($s);
			$sucursalprefijoactual = mysql_result($sq, 0);
		}
	}
	
	if($_POST[eliminarfolio]!=""){
		$s = "delete from capturagastoscajachica where id = $_POST[eliminarfolio]";
		$r = mysql_query($s) or die($s);
	}
	
	function guardardatos($arrayids)
	{
		if($arrayids!="")
		foreach($arrayids as $ids)	
		{
			$_POST['checksustituir'.$ids] ? $sustituir = "S" : $sustituir = "N";
			$s = "UPDATE capturagastoscajachica SET
			  	 sustituir = '".$sustituir."'
				 WHERE folio = '".$ids."' and keysucursal = ".$_SESSION[IDSUCURSAL]."";
			
			$sq = mysql_query($s) or die($s);
		}
		
		$s = "SELECT IF(ISNULL(MAX(folio) + 1), 1, MAX(folio) + 1) FROM foliosgastoscajachica WHERE keytipopagoindex = '2'
			  AND keysucursal = '".$_POST['idsucursal']."'";
		$sq = mysql_query($s) or die($s);
		mysql_num_rows($sq) > 0 ? $foliomayor = mysql_result($sq, 0) : $foliomayor = 0;
		
		$folioaux = $foliomayor - 1;
		$s = "SELECT generada FROM foliosgastoscajachica WHERE keytipopagoindex = '2' AND folio = '".$folioaux."'";
		$sq = mysql_query($s) or die($s);
		mysql_num_rows($sq) > 0 ? $generada = mysql_result($sq, 0) : $generada = '';
		if($generada == 'N') 
		{
			$foliomayor--;
			$sIni = 'UPDATE ';
			$sFin = ' WHERE keytipopagoindex = "2" AND folio = "'.$foliomayor.'"';
			$autorizado = "N";
		}
		else
		{
			$sIni = 'INSERT ';
			$sFin = '';
		}
		
		$total_gasto_sinformat = (0+str_replace(",","",$_POST['totalgasto']));
		
		$checaestado = 0;
		if($arrayids!="")
		foreach($arrayids as $ids)	
		{
			//Pendiente = 1; Autorizada = 2; Reposición = 3
			if($_POST['checkautorizar'.$ids]) $checaestado = 2;
			/*if($_POST['checkreponer'.$ids]) $checaestado = 3;*/
			if($checaestado > 2) break;
		}
		if($checaestado == 0) $checaestado = 1;
		
		$s = $sIni." foliosgastoscajachica SET
			 folio = '".$foliomayor."',
			 keytipopagoindex = '2',
			 desctipopago = 'Prepagado',
			 keysucursal = '".$_POST['idsucursal']."',
			 prefijosucursal = '".$_POST['sucursal']."',
			 estado = '".$checaestado."',
			 generada = 'N',
			 fechagenerada = NOW()".$sFin;
		
		$sq = mysql_query($s) or die($s);
	}
	
	if($_POST['enviar_datos'])
	{
		guardardatos($_POST['send_array_ids']);
	}
	
	if($_POST['generar_datos'])
	{
		guardardatos($_POST['send_array_ids']);
		
		$checaestado = 0;
		if($arrayids!="")
		foreach($_POST['send_array_ids'] as $ids)	
		{
			//Pendiente = 1; Autorizada = 2; Reposición = 3
			if($_POST['checkautorizar'.$ids]) $checaestado = 2;
			if($_POST['checkreponer'.$ids]) $checaestado = 3;
			if($checaestado > 2) break;
		}
		if($checaestado == 0) $checaestado = 1;
		
		//Prepagados = 2
		$s = "SELECT IF(ISNULL(MAX(folio) + 1), 1, MAX(folio) + 1) FROM foliosgastoscajachica WHERE keytipopagoindex = '2' AND keysucursal = '".$idsucursalactual."'";//1009-2
		$sq = mysql_query($s) or die($s);
		$folio = mysql_result($sq, 0);
		
		$folioaux = $folio - 1;
		$s = "SELECT generada FROM foliosgastoscajachica WHERE keytipopagoindex = '2' AND folio = '".$folioaux."'";
		$sq = mysql_query($s) or die($s);
		mysql_num_rows($sq) > 0 ? $generada = mysql_result($sq, 0) : $generada = '';
		
		if($generada == 'N') 
		{
			$folio--;
			$sIni = 'UPDATE ';
			$sFin = ' WHERE keytipopagoindex = "2" AND folio = "'.$folio.'"';
			$autorizado = "N";
		}
		else
		{
			$sIni = 'INSERT ';
			$sFin = '';
		}
		
		$s = $sIni." foliosgastoscajachica SET
			 folio = '".$folio."',
			 keytipopagoindex = '2',
			 desctipopago = 'Prepagado',
			 estado = '".$checaestado."',
			 generada = 'S',
			 fechagenerada = NOW()".$sFin;
		
		$sq = mysql_query($s) or die($s);
		if($arrayids!="")
		foreach($_POST['send_array_ids'] as $ids)	
		{
			if(!$_POST['checksustituir'.$ids])
			{
				$s = "UPDATE capturagastoscajachica SET
					 keyfoliosgastoscajachica = '".$folio."'
					 WHERE folio = '".$ids."' and keysucursal = ".$_SESSION[IDSUCURSAL]."";
				
				$sq = mysql_query($s) or die($s);
			
				$s = "INSERT detallefoliosgastoscajachica SET
				      keyfoliosgastoscajachica = '".$folio."',
					  keycapturagastoscajachica = '".$ids."',
					  sucursal = ".$_SESSION[IDSUCURSAL]."";
			
				$sq = mysql_query($s) or die($s);
			}
		}
	}
	
	$folio = 0;
	if($_POST['consultarfolio']  > 0)
	{
		$folio = $_POST['consultarfolio'];
	}
	else
	{
		$s = "SELECT IF(ISNULL(MAX(folio) + 1), 1, MAX(folio) + 1) FROM foliosgastoscajachica WHERE keytipopagoindex = '2' AND keysucursal = '".$idsucursalactual."'";//1009-2
		$sq = mysql_query($s) or die($s);
		$folio = mysql_result($sq, 0);
		
		$folioaux = $folio-1;
		$s = "SELECT generada FROM foliosgastoscajachica WHERE keytipopagoindex = '2' AND folio = '".$folioaux."'";
		$sq = mysql_query($s) or die($s);
		mysql_num_rows($sq) > 0 ? $generada = mysql_result($sq, 0) : $generada = '';
		if($generada == 'N') $folio--;
	}
	
	/*$idsucursalactual = 0;//Si no se envia sucursal que cargue la sucursal de session
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
	}*/
?>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="614" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">REPORTE DE GASTOS PREPAGADOS</td>
  </tr>

  <tr>
    <td><div align="center">
      <table width="612" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><div align="center"></div></td>
        </tr>
        <tr>
          <td><table width="609" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="157">&nbsp;</td>
              <td width="42">Sucursal</td>
              <td width="100"><span class="Tablas">
                <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$_POST['sucursal'] == "" ? $sucursalprefijoactual : $_POST['sucursal'] ?>" readonly=""/>
              </span></td>
              <td width="24"><div class="ebtn_buscar" onClick="BuscarSucursal()"></div></td>
              <td width="24">Folio</td>
              <td width="100"><span class="Tablas">
                <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
              </span></td>
              <td width="24"><div class="ebtn_buscar" onClick="BuscarFolioGastos()" ></div></td>
              <td width="33">Estado</td>
              <td width="105"><span id="estado" style="font:tahoma; font-size:15px; font-weight:bold">
                &nbsp;&nbsp;Estado
              </span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="612" border="0" align="left" cellpadding="0" cellspacing="0">
            
            <tr>
              <td colspan="22" align="center"><div align="left" class="Tablas" id="detalle" style="width:608px; height:300px; overflow:auto" name="detalle">
                  <? $line = 0; ?>
                  <table width="606" border="0" cellspacing="0" cellpadding="0">
                    <tr>
              <td width="4" height="32" class="formato_columnas_izqg">&nbsp;</td>
			  <td width="48" align="left" class="formato_columnasg">&nbsp;Autorizar&nbsp;&nbsp;</td>
              <td width="35" class="formato_columnasg" align="left">Folio Captura</td>
              <td width="33" class="formato_columnasg" align="left">Fecha Captura </td>
              <td width="45" align="left" class="formato_columnasg">No. Factura </td>
              <td width="54" align="left" class="formato_columnasg">Fecha Factura/Vale </td>
			  <td width="68" align="left" class="formato_columnasg">Proveedor </td>
			  <td width="68" align="left" class="formato_columnasg">Concepto </td>
			  <td width="55" align="left" class="formato_columnasg">Descripcion</td>
			  <td width="48" align="left" class="formato_columnasg">Total</td>
			  <td width="50" align="left" class="formato_columnasg">Folio Autorización </td>
			  <td width="50" align="left" class="formato_columnasg">Motivo No Autorización </td>
			   <td width="50" align="left" class="formato_columnasg">Observaciones </td>
			   <td width="48" align="left" class="formato_columnasg">Sustituir&nbsp;&nbsp; </td>
              <td width="6" class="formato_columnas_derg"></td>
            </tr>
				<?
				
				
				if($idsucursalactual > 0 || $_POST['consultarfolio'] > 0)
				{
					$conexion = Conectarse("webpmm");
            
                    $s = "";
					if($_POST['consultarfolio'] > 0 && $_POST['foliogenerado'] != 'N')
					{
						$s = "SELECT cap.tipogastodesc, cap.prefijosucursal, cap.id, 
							  DATE_FORMAT(cap.fecha,'%d-%m-%Y') as fecha, cap.factura, 
							  DATE_FORMAT(cap.fechafacturavale,'%d-%m-%Y') as fechafacturavale, cap.nombreproveedor,
							  cap.descripcionconcepto, cap.descripcion, cap.total, det.autorizar as autorizado,
							  det.folioautorizacion,
							  det.motivonoautorizacion, det.sustituir,cap.observaciones
							  FROM capturagastoscajachica cap 
							  LEFT JOIN detallefoliosgastoscajachica det ON (det.keycapturagastoscajachica = cap.id)
							  LEFT JOIN foliosgastoscajachica fol ON (det.keyfoliosgastoscajachica = fol.folio AND fol.keytipopagoindex = '2')
							  WHERE tipopagoindex = '2' 
							  AND fol.keysucursal = '".$idsucursalactual."'";
						$sWhere = " AND det.keyfoliosgastoscajachica = '".$_POST['consultarfolio']."'";
					}
					else
					{
						$s = "SELECT tipogastodesc, prefijosucursal, folio as id, DATE_FORMAT(fecha,'%d-%m-%Y') as fecha, factura, 
					      DATE_FORMAT(fechafacturavale,'%d-%m-%Y') as fechafacturavale, nombreproveedor,
						  descripcionconcepto, descripcion, total, autorizado, folioautorizacion, motivonoautorizacion,
						  sustituir,observaciones
						  FROM capturagastoscajachica WHERE tipopagoindex = '2' ";
						$sWhere = " AND (keyfoliosgastoscajachica = '' or ISNULL(keyfoliosgastoscajachica) 
						  			or keyfoliosgastoscajachica = '0') AND keysucursal = '".$idsucursalactual."'";
					}					
					$s .= $sWhere;
						  
                    $sq = mysql_query($s) or die($s);
                    
					$totalgastos = 0;
					$estadoactual = 0;
					while($row = mysql_fetch_object($sq))
					{  
					  $array_ids[] = $row->id; ?>
                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? //if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >
                      <td height="16" width="17" ><input name="id" type="hidden" value="<?=$row->id ?>" />
                      <? if($_POST['consultarfolio'] > 0 && $_POST['foliogenerado'] != 'N'){ 
						}else{?>
						     <img src="../img/delete.png" onClick="confirmar('Desea eliminar el gasto', '¡Atencion!', 'fEliminarGasto(<?=$row->id ?>)')" style="cursor:hand">
					  <? }  ?>
                      </td>
					  <td width="32" align="center" class="style31"  ><input type="checkbox" name="checkautorizar<?=$row->id ?>" id="checkautorizar<?=$row->id ?>" disabled <? if($row->autorizado == 'S') echo 'checked'; ?> ></td>
                      <td width="44" class="style31" align="center"><input name="foliocaptura<?=$row->id ?>" type="text" class="style2" id="foliocaptura<?=$row->id ?>" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="6" value="<?=$row->id ?>" /></td>
                      <td width="40" align="center" class="style31"><input name="fechacaptura<?=$row->id ?>" type="text" readonly="" class="style2" id="fechacaptura<?=$row->id ?> " style="font-size:8px; font:tahoma;font-weight:bold" size="10" value="<?=$row->fecha ?>" /></td>
                      <td width="40" class="style31" align="center"><input name="nofactura<?=$row->id ?>" type="text" class="style2" id="nofactura<?=$row->id ?> " readonly="" style="font-size:8px; font:tahoma;font-weight:bold" size="10" value="<?=$row->factura ?>" /></td>
                      <td width="40" class="style31" align="center"><input name="fechafacturavale<?=$row->id ?>" type="text" class="style2" id="fechafacturavale<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" size="10" value="<?=$row->fechafacturavale ?>" /></td>
					  
					  <td width="32" align="center" class="style31"  ><input name="proveedor<?=$row->id ?>" type="text" class="style2" id="proveedor<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="20" value="<?=$row->nombreproveedor ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="concepto<?=$row->id ?>" type="text" class="style2" id="concepto<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="25" value="<?=$row->descripcionconcepto ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="descripcion<?=$row->id ?>" type="text" class="style2" id="descripcion<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="28" value="<?=$row->descripcion ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="total<?=$row->id ?>" type="text" class="style2" id="total<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="10" value="<?=number_format($row->total, 2,',','.') ?>" />
                        <?
							if($row->autorizado == 'S')
							{
								$estadoactual == 0 ? $estadoactual = 2 : $estadoactual = $estadoactual;
								$totalgastos += $row->total;
							}
							
							/*if($row->reponer == 'S')
								$estadoactual = 3;*/
                        ?>
                      </td>
					  <td width="32" align="center" class="style31"  ><input name="folioautorizacion<?=$row->id ?>" type="text" class="style2" id="folioautorizacion<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="20" value="<?=$row->folioautorizacion ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="motivonoautorizacion<?=$row->id ?>" type="text" class="style2" id="motivonoautorizacion<?=$row->id ?> " readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="25" value="<?=$row->motivonoautorizacion ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="observaciones<?=$row->id ?>" type="text" class="style2" id="observaciones<?=$row->id ?> " readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="25" value="<?=$row->observaciones ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input type="checkbox" name="checksustituir<?=$row->id ?>" id="checksustituir<?=$row->id ?>" onClick="sustituir(<?=$row->id ?>)" <? if($_POST['consultarfolio'] > 0 && $_POST['foliogenerado'] != 'N') echo ' disabled ';?> <? if($row->sustituir == 'S') echo 'checked'; ?> ></td>
                      <td width="21" align="center" class="style31">&nbsp;</td>
                    </tr>
                    <?
		$line ++ ; }
		$estadoactual == 0 ? $estadoactual = 1 : $estadoactual = $estadoactual;
		echo '<script language="javascript"> setTimeout("cambiarEstado('.$estadoactual.')", 500); </script>';
			if(count($array_ids) > 0)
			{
				foreach ($array_ids as $ids)
				{
					echo '<input type=hidden name="send_array_ids[]" value="'.$ids.'">';
				}
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
          <td><table width="610" height="21" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="29">&nbsp;</td>
              <td width="91">&nbsp;</td>
              <td width="90">Total Gastos </td>
              <td width="100"><span class="Tablas">
                <input name="totalgasto" type="text" class="Tablas" id="totalgasto" style="width:80px;background:#FFFF99" value="<?=number_format($totalgastos, 2) ?>" readonly=""/>
              </span></td>
              <td width="90"><div class="ebtn_guardar"  onClick="<? if($_POST['consultarfolio'] > 0 && $_POST['foliogenerado'] != 'N') echo "mensajeNoGuardar()"; else echo "enviar()"; ?>"></div></td>
              <td width="137"><div class="ebtn_Cerrarreporte" onClick="<? if($_POST['consultarfolio'] > 0 && $_POST['foliogenerado'] != 'N') echo "mensajeNoGenerar()"; else echo "generar()"; ?>"></div></td>

				<td width="73"><div class="ebtn_imprimir" onClick="imprimir()"></div></td>
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td width="618" align="center"><!--kryzzo home--></td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>
<input type="hidden" name="session_sucursal" value="<?=$session_sucursal?>" />
<input type="hidden" name="enviar_datos" value="<?=$enviar_datos ?>">
<input type="hidden" name="generar_datos" >
<input type="hidden" name="enviar_sucursal" value="<?=$_POST['enviar_sucursal'] == "" ? $enviar_sucursal : $_POST['enviar_sucursal'] ?>">
<input type="hidden" name="idsucursal" value="<?=$_POST['idsucursal'] == "" ? $idsucursalactual : $_POST['idsucursal'] ?>" />
<input type="hidden" name="consultarfolio" id="consultarfolio" value="<?=$_POST['consultarfolio']?>" /><!--kryzzo consulta folio-->
<input type="hidden" name="foliogenerado" id="foliogenerado" value="<?=$_POST['foliogenerado']?>" /><!--1009-3-->
<input type="hidden" name="eliminarfolio" value="">
</form>
</body>
<script language="javascript">
	var u = document.all;

	
	function enviar()
	{		
		var a = new Array;
		<?
		for($i=0;$i<count($array_ids); $i++)
		{
			echo "a[$i]='".$array_ids[$i]."';\n";
		}			
	 	?>
		
		for(i=0;i<a.length;i++)
		{
			document.getElementById('checkautorizar'+a[i]).disabled = false;
		}
		
		document.form1.enviar_datos.value = true;
		document.form1.submit();
	}
	
	function validar()
	{		
		return true;
	}
	
	function fEliminarGasto(id){
		document.all.eliminarfolio.value=id; 
		document.form1.submit();
	}
	
	function generar()
	{
		if(validar())
		{
		
			var a = new Array;
			<?
			for($i=0;$i<count($array_ids); $i++)
			{
				echo "a[$i]='".$array_ids[$i]."';\n";
			}			
			?>
			
			for(i=0;i<a.length;i++)
			{
			
				document.getElementById('checkautorizar'+a[i]).disabled = false;
			}
			
			document.form1.generar_datos.value = true;
			document.form1.submit();
		}
	}
	
	function BuscarSucursal()
	{
		abrirVentanaFija('buscarSucursal.php', 600, 550, 'ventana', 'Busqueda');
	}
	
	function ObtenerSucursal(id, prefijo)
	{
		document.getElementById('idsucursal').value=id;
		document.getElementById('sucursal').value=prefijo;
		document.form1.enviar_sucursal.value = true;
		document.form1.submit();
	}
	
	function cambiarEstado(idestado)
	{
		//if(idestado == 1)	document.getElementById('estado').innerHTML = "Pendiente";
		//if(idestado == 2)	document.getElementById('estado').innerHTML = "Autorizada";
		if(idestado == 3)	document.getElementById('estado').innerHTML = "Reposici&oacute;n";
	}
	
	function BuscarFolioGastos()
	{
		abrirVentanaFija('buscarFolioGastos.php?tipopago=2&idsucursal='+document.form1.idsucursal.value+'', 600, 550, 'ventana', 'Busqueda');
	}
	
	function ObtenerFolioGastos(id, generada)
	{
		document.getElementById('consultarfolio').value=id;
		document.getElementById('foliogenerado').value=generada;//1009-3
		document.form1.submit();
	}
	
	function mensajeNoGuardar()
	{
		alerta('No es posible Guardar un folio ya generado','¡Atención!','folio');
	}
	
	function mensajeNoGenerar()
	{
		alerta('No es posible Cerrar un folio ya cerrado previamente','¡Atención!','folio');
	}

	function imprimir(){		
		if(document.URL.indexOf("web/")>-1){		
			window.open("http://www.pmmintranet.net/web/cajachica/excel_reportegastoscajachica.php?folio="+u.folio.value
		+"&titulo=GASTOS PREPAGADOS&sucursal="+u.idsucursal.value);
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
		window.open("http://www.pmmintranet.net/web_capacitacion/cajachica/excel_reportegastoscajachica.php?folio="+u.folio.value
		+"&titulo=GASTOS PREPAGADOS&sucursal="+u.idsucursal.value);
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("http://www.pmmintranet.net/web_pruebas/cajachica/excel_reportegastoscajachica.php?folio="+u.folio.value
		+"&titulo=GASTOS PREPAGADOS&sucursal="+u.idsucursal.value);
		}
	}

</script>
</html>