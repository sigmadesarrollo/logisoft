<?	session_start();
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
<script src="../javascript/jquery.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
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
	$conexion = Conectarse("webpmm");
	
	function cerrarcon($resultado, $conexion){
		mysql_free_result($resultado);
		mysql_close($conexion);
	}
	
	$idsucursalactual = 0;//Si no se envia sucursal que cargue la sucursal de session
	if($_POST['enviar_sucursal']){
		$idsucursalactual = $_POST['idsucursal'];
	}else{
		$idsucursalactual = $session_sucursal;		
		if($idsucursalactual > 0){
			$s = "SELECT prefijo FROM catalogosucursal WHERE id = '".$idsucursalactual."'";
			$sq = mysql_query($s) or die($s);
			$sucursalprefijoactual = mysql_result($sq, 0);
		}
	}
	
	if($_POST[eliminarfolio]!=""){
		$s = "delete from capturagastoscajachica where folio = $_POST[eliminarfolio] 
		AND keysucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s) or die($s);
	}
	
	function guardardatos($arrayids){
		foreach($arrayids as $ids){
			$_POST['checksustituir'.$ids] ? $sustituir = "S" : $sustituir = "N";
			$_POST['checkreponer'.$ids] ? $reponer = "S" : $reponer = "N";
			$s = "UPDATE capturagastoscajachica SET
			  	 sustituir = '".$sustituir."',
				 reponer = '".$reponer."'
				 WHERE folio = '".$ids."' AND keysucursal = ".$_SESSION[IDSUCURSAL]."";
			
			$sq = mysql_query($s) or die($s);
		}
		
		$s = "SELECT IF(ISNULL(MAX(folio) + 1), 1, MAX(folio) + 1) FROM foliosgastoscajachica WHERE keytipopagoindex = '1'
	    AND keysucursal = '".$_POST['idsucursal']."'";
		$sq = mysql_query($s) or die($s);
		mysql_num_rows($sq) > 0 ? $foliomayor = mysql_result($sq, 0) : $foliomayor = 0;
		
		$folioaux = $foliomayor - 1;
		$s = "SELECT generada FROM foliosgastoscajachica WHERE keytipopagoindex = '1' AND folio = '".$folioaux."'";
		$sq = mysql_query($s) or die($s);
		mysql_num_rows($sq) > 0 ? $generada = mysql_result($sq, 0) : $generada = '';
		
		if($generada == 'N'){
			$foliomayor--;
			$sIni = 'UPDATE ';
			$sFin = ' WHERE keytipopagoindex = "1" AND folio = "'.$foliomayor.'"';
			$autorizado = "N";
		}else{
			$sIni = 'INSERT ';
			$sFin = '';
		}
		
		if($_POST['fecha_cheque'] == ''){
			$fechadecheque = '';
		}else{
			$fechaS = split("-", $_POST['fecha_cheque']);
			$fechachequeformat = $fechaS[2]."-".$fechaS[1]."-".$fechaS[0];
			$fechadecheque = " fechacheque = '".$fechachequeformat."',";
		}
		
		$total_gasto_sinformat = (0+str_replace(",","",$_POST['totalgasto']));
		$total_monto_sinformat = (0+str_replace(",","",$_POST['mcchica']));
		
		$checaestado = 0;
		foreach($_POST['send_array_ids'] as $ids){
			//Pendiente = 1; Autorizada = 2; Reposicin = 3
			if($_POST['checkautorizar'.$ids]) $checaestado = 2;
			if($_POST['checkreponer'.$ids]) $checaestado = 3;
			if($checaestado > 2) break;
		}
		if($checaestado == 0) $checaestado = 1;
		
		$s = $sIni." foliosgastoscajachica SET
			 folio = '".$foliomayor."',
			 keytipopagoindex = '1',
			 desctipopago = 'Caja Chica',
			 nocuenta = '".$_POST['nocuenta']."',
			 cheque = '".$_POST['cheque']."',
			 fechacheque = '".cambiaf_a_mysql($_POST[fecha_cheque])."',
			 idgerente = '".$_POST['gerente']."',
			 nombregerente = '".$_POST['gerenteb']."',
			 foliocorreointerno = '".$_POST['foliocorreo']."',
			 totalgasto = '".$total_gasto_sinformat."',
			 montocajachica = '".$total_monto_sinformat."',
			 keysucursal = '".$_POST['idsucursal']."',
			 prefijosucursal = '".$_POST['sucursal']."',
			 estado = '".$checaestado."',
			 generada = 'N',
			 fechagenerada = NOW()".$sFin;
		
		$sq = mysql_query($s) or die($s);
	}
	
	if($_POST['enviar_datos']){
		guardardatos($_POST['send_array_ids']);
	}
	
	if($_POST['generar_datos']){
		guardardatos($_POST['send_array_ids']);
		
		if($_POST['fecha_cheque'] == ''){
			$fechadecheque = '';
		}else{
			$fechaS = split("-", $_POST['fecha_cheque']);
			$fechachequeformat = $fechaS[2]."-".$fechaS[1]."-".$fechaS[0];
			$fechadecheque = " fechacheque = '".$fechachequeformat."',";
		}
		
		$checaestado = 0;
		foreach($_POST['send_array_ids'] as $ids){
			//Pendiente = 1; Autorizada = 2; Reposicin = 3
			if($_POST['checkautorizar'.$ids]) $checaestado = 2;
			if($_POST['checkreponer'.$ids]) $checaestado = 3;
			if($checaestado > 2) break;
		}
		if($checaestado == 0) $checaestado = 1;
		
		$total_gasto_sinformat = (0+str_replace(",","",$_POST['totalgasto']));
		
		$s = "SELECT IF(ISNULL(MAX(folio) + 1), 1, MAX(folio) + 1) FROM foliosgastoscajachica WHERE keytipopagoindex = '1'";
		$sq = mysql_query($s) or die($s);
		$folio = mysql_result($sq, 0);
		
		$folioaux = $folio - 1;
		$s = "SELECT generada FROM foliosgastoscajachica WHERE keytipopagoindex = '1' AND folio = '".$folioaux."'";
		$sq = mysql_query($s) or die($s);
		mysql_num_rows($sq) > 0 ? $generada = mysql_result($sq, 0) : $generada = '';
		
		if($generada == 'N'){
			$folio--;
			$sIni = 'UPDATE ';
			$sFin = ' WHERE keytipopagoindex = "1" AND folio = "'.$folio.'"';
			$autorizado = "N";
		}else{
			$sIni = 'INSERT ';
			$sFin = '';
		}
		
		$s = $sIni." foliosgastoscajachica SET
			 folio = '".$folio."',
			 keytipopagoindex = '1',
			 desctipopago = 'Caja Chica',
			 nocuenta = '".$_POST['nocuenta']."',
			 cheque = '".$_POST['cheque']."',
			 fechacheque = '".cambiaf_a_mysql($_POST[fecha_cheque])."',
			 idgerente = '".$_POST['gerente']."',
			 nombregerente = '".$_POST['gerenteb']."',
			 foliocorreointerno = '".$_POST['foliocorreo']."',
			 totalgasto = '".$total_gasto_sinformat."',
			 montocajachica = '".$total_monto_sinformat."',
			 keysucursal = '".$_POST['idsucursal']."',
			 prefijosucursal = '".$_POST['sucursal']."',
			 estado = '".$checaestado."',
			 generada = 'S',
			 fechagenerada = NOW()".$sFin;
		
		$sq = mysql_query($s) or die($s);
		
		if($sIni == 'INSERT '){
			$autocheque = mysql_insert_id($conexion);
		}else{
			$autocheque = $folio;
		}
		
		foreach($_POST['send_array_ids'] as $ids){
			if(!$_POST['checksustituir'.$ids]){
				$s = "UPDATE capturagastoscajachica SET
					 keyfoliosgastoscajachica = '".$folio."'
					 WHERE folio = '".$ids."' AND keysucursal = ".$_SESSION[IDSUCURSAL]."";
				
				$sq = mysql_query($s) or die($s);
			}
			
			$s = "INSERT detallefoliosgastoscajachica SET
				  keyfoliosgastoscajachica = '".$folio."',
				  keycapturagastoscajachica = '".$ids."',
				  folioautorizacion = '".$_POST['folioautorizacion'.$ids]."',
				  motivonoautorizacion = '".$_POST['motivonoautorizacion'.$ids]."', 
				  
				  foliocaptura 	= '".$_POST['foliocaptura'.$ids]."',
				  fechacaptura 	= '".cambiaf_a_mysql(str_replace('-','/',$_POST['fechacaptura'.$ids]))."',
				  nofactura 	= '".$_POST['nofactura'.$ids]."',
				  fechafactura 	= '".cambiaf_a_mysql(str_replace('-','/',$_POST['fechafacturavale'.$ids]))."',
				  proveedor 	= '".$_POST['proveedor'.$ids]."',
				  concepto 		= '".$_POST['concepto'.$ids]."',
				  descripcion 	= '".$_POST['descripcion'.$ids]."',
				  total 		= '".$_POST['total'.$ids]."',
				  sucursal		= ".$_SESSION[IDSUCURSAL]."";
				  
			$_POST['checkautorizar'.$ids] ? $s .= ",autorizar = 'S'" : $s .= ",autorizar = 'N'";
			$_POST['checksustituir'.$ids] ? $s .= ",sustituir = 'S'" : $s .= ",sustituir = 'N'";
			$_POST['checkreponer'.$ids] ? $s .= ",reponer = 'S'" : $s .= ",reponer = 'N'";
		
			/*if($_POST['checkreponer'.$ids])
			{
				$fechaS = split("-", $_POST['fecha_cheque']);
				$fechachequeformat = $fechaS[2]."-".$fechaS[1]."-".$fechaS[0];
				$s .= ", cheque = '".$_POST['cheque']."',
						fechacheque = '".$fechachequeformat."',
						idgerente = '".$_POST['gerente']."',
						nombregerente = '".$_POST['gerenteb']."',
						foliocorreointerno = '".$_POST['foliocorreo']."'";
			}*/
			$sq = mysql_query($s) or die($s);
		}
		
		//acomodan los datos para generar el archivo del checkpak
		//$totalcheque = str_replace(".","",str_replace(",","",$_POST['totalgasto']));

		//crear archivo
		/*$contenido = $_POST['nocuenta']."¦".date("dmY",time())."¦".date("dmY",time())."¦1¦".$_POST['cheque']."¦".$_POST['gerenteb']."¦¦RepCajChic ".str_pad($autocheque,9,"0",STR_PAD_LEFT)."¦".$totalcheque."¦3¦¦";
		$archivo = fopen("cheques/cheques".$_SESSION[IDSUCURSAL].".txt", "w");
		fwrite($archivo, $contenido);
		fclose($archivo);*/
		
		$s 	= "SELECT prefijo FROM catalogosucursal WHERE id = $_SESSION[IDSUCURSAL]";
		$rx	= mysql_query($s) or die($s);
		$fx	= mysql_fetch_object($rx);
		
		$total_gasto_sinformat = str_replace("$ ","",str_replace(",","",$_POST['totalgasto']));
		
		$s = "INSERT INTO cajachica_cheques 
		SET nocuenta = '$_POST[nocuenta]', fecha = current_date, nocheque = '$_POST[cheque]', gerente = '$_POST[gerenteb]', sucursal='$_SESSION[IDSUCURSAL]',
		idcheque = '".str_pad($autocheque,9,"0",STR_PAD_LEFT)."', concepto='Reposicion de Caja Chica Suc $fx->prefijo a favor de Coord $_POST[gerenteb]', totalcheque = '$total_gasto_sinformat', usuario='$_SESSION[IDUSUARIO]'";
		mysql_query($s) or die($s);
		
		$s = "";
		$_POST[fecha_cheque] = "";
		$_POST['nocuenta'] = "";
		$_POST['gerente'] = "";
		//descargar archivo
		/*?>
			<script>
				window.open("descargararchivo.php?contenido=<?=$contenido?>");
			</script>
		<?*/
	}
	
	$folio = 0;
	if($_POST['consultarfolio']  > 0){
		$folio = $_POST['consultarfolio'];
	}else{
		$s = "SELECT IF(ISNULL(MAX(folio) + 1), 1, MAX(folio) + 1) FROM foliosgastoscajachica WHERE keytipopagoindex = '1' AND keysucursal = '".$idsucursalactual."'";
		$sq = mysql_query($s) or die($s);
		mysql_num_rows($sq) > 0 ? $folio = mysql_result($sq, 0) : $folio = '';
		
		$folioaux = $folio-1;
		$s = "SELECT generada FROM foliosgastoscajachica WHERE keytipopagoindex = '1' AND folio = '".$folioaux."'";
		$sq = mysql_query($s) or die($s);
		mysql_num_rows($sq) > 0 ? $generada = mysql_result($sq, 0) : $generada = '';
		if($generada == 'N') $folio--;
	}
	
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
<table width="614" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">REPORTE DE GASTOS CAJA CHICA</td>
  </tr>
  <tr>
    <td><div align="center" class="Tablas">
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
              <td width="24"><div class="ebtn_buscar"  onClick="BuscarFolioGastos()" ></div></td>
              <td width="33">Estado</td>
              <td width="105"><span id="estado" style="font:tahoma; font-size:15px; font-weight:bold">
&nbsp;              </span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="612" border="0" align="left" cellpadding="0" cellspacing="0">
            
            <tr>
              <td colspan="22" align="center"><div id="detalle" name="detalle" style="width:608px; height:300px; overflow:auto" align="left">
                  <? $line = 0; ?>
                  <table width="606" border="0" cellspacing="0" cellpadding="0">
                    <tr>
              <td width="4" height="32" class="formato_columnas_izqg">&nbsp;</td>
			  <td width="48" align="left" class="formato_columnasg">&nbsp;Autorizar&nbsp;&nbsp;</td>
			    <td width="47" align="left" class="formato_columnasg">Reponer </td>
              <td width="35" class="formato_columnasg" align="left">Folio Captura</td>
              <td width="33" class="formato_columnasg" align="left">Fecha Captura </td>
              <td width="45" align="left" class="formato_columnasg">No. Factura </td>
              <td width="54" align="left" class="formato_columnasg">Fecha Factura/Vale </td>
			  <td width="48" align="left" class="formato_columnasg">Proveedor </td>
			  <td width="48" align="left" class="formato_columnasg">Concepto </td>
			  <td width="48" align="left" class="formato_columnasg">Descripcion</td>
			  <td width="48" align="left" class="formato_columnasg">Total</td>
			  <td width="50" align="left" class="formato_columnasg">Folio Autorizacin </td>
			  <td width="50" align="left" class="formato_columnasg">Motivo No Autorizacion </td>
			  <td width="50" align="left" class="formato_columnasg">Observaciones </td>
			   <td width="48" align="left" class="formato_columnasg">Sustituir&nbsp;&nbsp; </td>
              <td width="6" class="formato_columnas_derg"></td>
            </tr>
				<?
				
				
				if($idsucursalactual > 0 || $_POST['consultarfolio'] > 0)
				{
					$conexion = Conectarse("webpmm");//borrame
					
					$s = "SELECT totalcajachica
					      FROM depositoscajachica
						  INNER JOIN (SELECT max(id) as max_id from depositoscajachica group by keysucursal) as MAXID
						  on depositoscajachica.id = MAXID.max_id
						  WHERE keysucursal = '".$idsucursalactual."'";
						  
                    $sq = mysql_query($s) or die($s);
					
					if(mysql_num_rows($sq) > 0) $montocajachica = mysql_result($sq, 0);
					
            
                    $s = "";
					if($_POST['consultarfolio'] > 0 && $_POST['foliogenerado'] != 'N')
					{						
						$s = "SELECT det.foliocaptura as id, 
							  DATE_FORMAT(det.fechacaptura,'%d-%m-%Y') as fecha, det.nofactura as factura, 
							  DATE_FORMAT(det.fechafactura,'%d-%m-%Y') as fechafacturavale, det.proveedor as nombreproveedor,
							  det.concepto as descripcionconcepto, det.descripcion, det.total, det.autorizar as autorizado,
							  det.folioautorizacion, det.motivonoautorizacion, det.sustituir, det.reponer
							  FROM capturagastoscajachica cap 
							  LEFT JOIN detallefoliosgastoscajachica det ON (det.keycapturagastoscajachica = cap.id)
							  LEFT JOIN foliosgastoscajachica fol ON (det.keyfoliosgastoscajachica = fol.folio AND fol.keytipopagoindex = '1')
							  WHERE tipopagoindex = '1' 
							  AND fol.keysucursal = '".$idsucursalactual."'";
						$sWhere = " AND det.keyfoliosgastoscajachica = '".$_POST['consultarfolio']."'";
					}
					else
					{
						$s = "SELECT tipogastodesc, prefijosucursal, folio AS id, DATE_FORMAT(fecha,'%d-%m-%Y') as fecha, factura, 
					      DATE_FORMAT(fechafacturavale,'%d-%m-%Y') as fechafacturavale, nombreproveedor,
						  descripcionconcepto, descripcion, total, autorizado, folioautorizacion, motivonoautorizacion,
						  sustituir, reponer
						  FROM capturagastoscajachica WHERE tipopagoindex = '1' ";
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
                      <td width="32" align="center" class="style31"  ><input type="checkbox" name="checkreponer<?=$row->id ?>" id="checkreponer<?=$row->id ?>" onClick="if(valirdarActivado(<?=$row->id ?>)){reponer(<?=$row->id ?>)}else{this.checked = false;}" <? if($_POST['consultarfolio'] > 0 && $_POST['foliogenerado'] != 'N') echo ' disabled ';?> <? if($row->reponer == 'S') echo 'checked'; ?> ></td>
                      <td width="44" class="style31" align="center"><input name="foliocaptura<?=$row->id ?>" type="text" class="style2" id="foliocaptura<?=$row->id ?>" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="6" value="<?=$row->id ?>" /></td>
                      <td width="40" align="center" class="style31"><input name="fechacaptura<?=$row->id ?>" type="text" readonly="" class="style2" id="fechacaptura<?=$row->id ?> " style="font-size:8px; font:tahoma;font-weight:bold" size="10" value="<?=$row->fecha ?>" /></td>
                      <td width="40" class="style31" align="center"><input name="nofactura<?=$row->id ?>" type="text" class="style2" id="nofactura<?=$row->id ?> " readonly="" style="font-size:8px; font:tahoma;font-weight:bold" size="10" value="<?=$row->factura ?>" /></td>
                      <td width="40" class="style31" align="center"><input name="fechafacturavale<?=$row->id ?>" type="text" class="style2" id="fechafacturavale<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" size="10" value="<?=$row->fechafacturavale ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="proveedor<?=$row->id ?>" type="text" class="style2" id="proveedor<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="20" value="<?=$row->nombreproveedor ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="concepto<?=$row->id ?>" type="text" class="style2" id="concepto<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="25" value="<?=$row->descripcionconcepto ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="descripcion<?=$row->id ?>" type="text" class="style2" id="descripcion<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="28" value="<?=$row->descripcion ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="total<?=$row->id ?>" type="text" class="style2" id="total<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="10" value="<?=number_format($row->total, 2, ',','.') ?>" />
                        <?
							if($row->autorizado == 'S')//kryzz solo sumar autorizados y estado
							{
								$estadoactual == 0 ? $estadoactual = 2 : $estadoactual = $estadoactual;
								$totalgastos += $row->total;
							}
							
							if($row->reponer == 'S')//kryzz estado
								$estadoactual = 3;
                        ?>
                      </td>
					  <td width="32" align="center" class="style31"  ><input name="folioautorizacion<?=$row->id ?>" type="text" class="style2" id="folioautorizacion<?=$row->id ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="20" value="<?=$row->folioautorizacion ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="motivonoautorizacion<?=$row->id ?>" type="text" class="style2" id="motivonoautorizacion<?=$row->id ?> " readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="25" value="<?=$row->motivonoautorizacion ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input name="observaciones<?=$row->id ?>" type="text" class="style2" id="observaciones<?=$row->id ?> " readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="25" value="<?=$row->observaciones ?>" /></td>
					  <td width="32" align="center" class="style31"  ><input type="checkbox" name="checksustituir<?=$row->id ?>" id="checksustituir<?=$row->id ?>" onClick="sustituir(<?=$row->id ?>)" <? if($_POST['consultarfolio'] > 0 && $_POST['foliogenerado'] != 'N') echo ' disabled ';?> <? if($row->sustituir == 'S') echo 'checked'; ?> ></td>
                      <td width="21" align="center" class="style31">&nbsp;</td>
                    </tr>
                    <?
		$line ++ ; 
		}
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
            <?
				$s = "SELECT cheque, DATE_FORMAT(fechacheque,'%d-%m-%Y'), idgerente, nombregerente, foliocorreointerno,
					  totalgasto, montocajachica, nocuenta
					  FROM foliosgastoscajachica
					  WHERE keytipopagoindex = '1' AND keysucursal = '".$idsucursalactual."'";
				if($_POST['consultarfolio'] > 0) $sWhere = " AND folio = '".$_POST['consultarfolio']."'";
				else $sWhere = " AND folio = '".$folio."'";
				$s .= $sWhere;
						  
                $sq = mysql_query($s) or die($s);
				
				//$montocajachica = '0';
				//if($_POST['consultarfolio'] > 0 && mysql_num_rows($sq) > 0) $montocajachica = mysql_result($sq, 0, 6);
				
				if(mysql_num_rows($sq) > 0){
					$varcheque = mysql_result($sq, 0, 0);
					$varfechacheque = mysql_result($sq, 0, 1);
					$vargerente = mysql_result($sq, 0, 2);
					$vargerenteb = mysql_result($sq, 0, 3);
					$varfoliocorreo = mysql_result($sq, 0, 4);
					$varnocuenta = mysql_result($sq, 0, 7);
					$_POST['nocuenta'] = $varnocuenta;
					$_POST['gerente'] = $vargerente;
					$_POST['fecha_cheque'] = $varfechacheque;
				}

			?>
              <td width="52"># Cuenta</td>
              <td width="152"><span class="Tablas">
                <input name="nocuenta" type="text" class="Tablas" id="nocuenta" style="width:120px;background:#FFFF99;" readonly="" value="<?=$_POST['nocuenta'] ?>" />
              </span></td>
              	<td width="217"></td>
                <td width="45">&nbsp;</td>
              <td width="86"></td>
              <td width="68">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="610" height="21" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="54"># Cheque </td>
              <td width="97"><span class="Tablas">
                <input name="cheque" type="text" class="Tablas" id="cheque" style="width:80px;background:#FFFF99;" readonly="" value="<?=$varcheque;?>" />
              </span></td>
              <td width="79">Fecha Cheque </td>
              <td width="125"><span class="Tablas">
                <input name="fecha_cheque" type="text" class="Tablas" id="fecha_cheque" style="width:120px;"  value="<?=$_POST[fecha_cheque] ?>" />
              </span></td>
                <td><div class="ebtn_calendario" onClick="enableCalendar(this);"></div>
              </td>
              <td width="94">Total Gastos </td>
              <td width="85"><span class="Tablas">
                <input name="totalgasto" type="text" class="Tablas" id="totalgasto" style="width:80px;background:#FFFF99; text-align:right" value="<?=number_format($totalgastos, 2) ?>" readonly=""/>
              </span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="611" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="54">Gerente</td>
              <td width="75"><span class="Tablas">
                <input name="gerente" type="text" class="Tablas" id="gerente" onKeyPress="if(event.keyCode==13){obtenerGerente(this.value);}" style="width:70px" value="<?=$_POST['gerente'] ?>" />
              </span></td>
              <td width="32"><div class="ebtn_buscar" onClick="BuscarGerente()"></div></td>
              <td width="267"><span class="Tablas">
                <input type="text" name="gerenteb" id="gerenteb" class="Tablas" style="width:220px;background:#FFFF99" readonly="" value="<?=$vargerenteb;?>" />
              </span></td>
              <td width="97">Monto Caja Chica </td>
              <td width="86"><span class="Tablas">
                <input name="mcchica" type="text" class="Tablas" id="mcchica" style="width:80px;background:#FFFF99; text-align:right" value="<?=number_format($montocajachica, 2) ?>" readonly=""/>
              </span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="612" height="16" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="110">Folio Correo Interno </td>
              <td width="309"><span class="Tablas">
                <input name="foliocorreo" type="text" class="Tablas" id="foliocorreo" style="width:100px;background:#FFFF99;" readonly=""  value="<?=$varfoliocorreo;?>" />
                </span>
              </td>
              <td width="96">Saldo</td>
              <td width="87"><span class="Tablas">
                <input name="saldo" type="text" class="Tablas" id="saldo" style="width:80px;background:#FFFF99; text-align:right" value="<?=number_format($montocajachica-$totalgastos, 2) ?>" readonly=""/>
              </span></td>
            </tr>
          </table></td>
        </tr>
        
        
        <tr>
          <td><table width="612" border="0" cellpadding="0" cellspacing="0">
            <tr><!--kryzzo home-->
              <td width="100" align="left"></td>
              <td width="158" align="right"><div class="ebtn_guardar" onClick="<? if($_POST['consultarfolio'] > 0 && $_POST['foliogenerado'] != 'N') echo "mensajeNoGuardar()"; else echo "enviar()"; ?>"></div></td>
              <td width="71" align="center"><div class="ebtn_Generar" onClick="<? if($_POST['consultarfolio'] > 0 && $_POST['foliogenerado'] != 'N') echo "mensajeNoGenerar()"; else echo "generar()"; ?>"> </div></td>
              <td width="283"><div class="ebtn_imprimir" onClick="imprimir()"></div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="618">&nbsp;</td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>
<input type="hidden" name="session_sucursal" value="<?=$session_sucursal?>" />
<input type="hidden" name="enviar_datos" value="<?=$enviar_datos ?>">
<input type="hidden" name="generar_datos" >
<input type="hidden" name="enviar_sucursal" value="<?=$_POST['enviar_sucursal'] == "" ? $enviar_sucursal : $_POST['enviar_sucursal'] ?>">
<input type="hidden" name="reponerg" id="reponerg" />
<input type="hidden" name="idsucursal" value="<?=$_POST['idsucursal'] == "" ? $idsucursalactual : $_POST['idsucursal'] ?>" />
<input type="hidden" name="consultarfolio" id="consultarfolio" value="<?=$_POST['consultarfolio']?>" />
<input type="hidden" name="foliogenerado" id="foliogenerado" value="<?=$_POST['foliogenerado']?>" />
<input type="hidden" name="eliminarfolio" id="eliminarfolio" value="" />
</form>
</body>
<script language="javascript">
	var u = document.all;
	jQuery(function($){
	   $('#fecha_cheque').mask("99/99/9999");
	});
	function valirdarActivado(id){
		if(document.all["checkautorizar"+id].checked==true){
			return true;
		}else{
			alerta3("No puede seleccionar reponer, hasta que no autorize el gasto", "Atencion");
			return false;
		}
	}

	function enviar()
	{		
		document.form1.enviar_datos.value = true;
		document.form1.submit();
	}
	
	function fEliminarGasto(id){
		document.all.eliminarfolio.value=id; 
		document.form1.submit();
	}
	
	function trim(cadena,caja)
	{
		for(i=0;i<cadena.length;)
		{
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(i+1, cadena.length);
			else
				break;
		}
	
		for(i=cadena.length-1; i>=0; i=cadena.length-1)
		{
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(0,i);
			else
				break;
		}
		
		document.getElementById(caja).value=cadena;
	}
	
	function validar()
	{
		if(document.getElementById('reponerg').value == 'false') return true;
		
		trim(document.getElementById('cheque').value, 'cheque')
		if( document.getElementById('cheque').value == '')
		{
			alerta('Debe capturar # de Cheque','Atencion!','cheque');
			return false;
		}
		
		trim(document.getElementById('nocuenta').value, 'nocuenta')
		if( document.getElementById('nocuenta').value == '')
		{
			alerta('Debe capturar # de Cuenta','Atencion!','nocuenta');
			return false;
		}
		
		trim(document.getElementById('fecha_cheque').value, 'fecha_cheque')
		if( document.getElementById('fecha_cheque').value == '')
		{ 
			alerta('Debe seleccionar una fecha','Atencion!','fecha_cheque');
			return false;
		}
		
		trim(document.getElementById('gerente').value, 'gerente')
		if( document.getElementById('gerente').value == '') 
		{
			alerta('Debe seleccionar un Gerente','Atencion!','gerente');
			return false;
		}
		
		/*trim(document.getElementById('foliocorreo').value, 'foliocorreo')
		if( document.getElementById('foliocorreo').value == '') 
		{
			alerta('Debe capturar un Folio Correo Interno','Atencion!','foliocorreo');
			return false;
		}*/
		return true;
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
			
			if(a.length<1){
				alerta3("No hay ningun gasto para generar el reporte", "¡Atencion!");
				return false;
			}
			
			for(i=0;i<a.length;i++){
				if(document.getElementById('checkreponer'+a[i]).chequed == false && document.getElementById('checksustituir'+a[i]).chequed == false){
					alerta3("debe de seleccionar un reponer o sustituir para todos los pagos", "¡Atencion!");
					return false;
				}
			}
			
			
			
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
		abrirVentanaFija('buscarSucursal.php', 550, 450, 'ventana', 'Busqueda');
	}
	
	function ObtenerSucursal(id, prefijo)
	{
		document.getElementById('idsucursal').value=id;
		document.getElementById('sucursal').value=prefijo;
		document.form1.enviar_sucursal.value = true;
		document.form1.submit();
	}
	
	function obtenerGerente(id){
		consultaTexto("mostrarGerente","cajachica_con.php?accion=3&gerente="+id);
	}
	
	function mostrarGerente(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			document.all.gerenteb.value = obj[0].gerente;
		}else{
			alerta("El numero de Gerente no existe","¡Atención!","gerente");
			document.getElementById('gerenteb').value = "";
		}
	}
	
	function BuscarGerente(){
		if(document.getElementById('reponerg').value == 'false') return;
		abrirVentanaFija('buscarGerente.php', 550, 450, 'ventana', 'Busqueda')
	}
	
	function ObtenerGerente(id, nombre){
		document.getElementById('gerente').value=id;
		document.getElementById('gerenteb').value=nombre;
	}
	
	function sustituir(idactual)
	{
		document.getElementById('checkreponer'+idactual).checked = false;
		reponer(idactual)
	}
	
	function reponergasto(breponer)
	{
		document.getElementById('reponerg').value = breponer;
	}
	
	function reponer(idactual)
	{
		if(idactual > 0)
		{
			if(document.getElementById('checkreponer'+idactual).checked)
			{
				document.getElementById('checksustituir'+idactual).checked = false;
			}
		}
		
		var a = new Array;
		<?
		for($i=0;$i<count($array_ids); $i++)
		{
			echo "a[$i]='".$array_ids[$i]."';\n";
		}			
	 	?>
		
		var total = 0;
		var saldo = 0;
		var breponer = false;
		for(i=0;i<a.length;i++)
		{
		
			if(document.getElementById('checkreponer'+a[i]).checked )
			{
				 total += parseFloat(document.getElementById('total'+a[i]).value);
				 breponer = true;
			}
		}
		if(breponer)
		{
			document.getElementById('totalgasto').value = total.toFixed(2);
			document.all.totalgasto.value = "$ "+numcredvar(document.all.totalgasto.value);
			esNan('totalgasto');
			reponergasto(true);
			
			document.form1.cheque.readOnly = false;
			document.form1.cheque.style.background = 'none';
			document.form1.nocuenta.readOnly = false;
			document.form1.nocuenta.style.background = 'none';
			document.form1.gerente.style.background = 'none';
			document.form1.foliocorreo.readOnly = false;
			document.form1.foliocorreo.style.background = 'none';
			document.form1.fecha_cheque.style.background = 'none';
		}
		else
		{
			total = parseFloat(<?=$totalgastos?>);
			document.getElementById('totalgasto').value = total.toFixed(2);
			document.all.totalgasto.value = "$ "+numcredvar(document.all.totalgasto.value);
			esNan('totalgasto');
			reponergasto(false);
			
			document.form1.cheque.readOnly = true;
			document.form1.cheque.style.background = '#FFFF99';
			document.form1.nocuenta.readOnly = true;
			document.form1.nocuenta.style.background = '#FFFF99';
			document.form1.gerente.style.background = '#FFFF99';
			document.form1.foliocorreo.readOnly = true;
			document.form1.foliocorreo.style.background = '#FFFF99';
			document.form1.fecha_cheque.style.background = '#FFFF99';
		}
		monto = document.getElementById('mcchica').value.replace(",", "");
		saldo = monto - parseFloat(total);
		//document.getElementById('saldo').value = convertiraMoneda(saldo.toFixed(2).toLocaleString());
		document.getElementById('saldo').value = "$ "+numcredvar(saldo.toFixed(2));
		esNan('saldo');
		document.getElementById('mcchica').value = "$ "+numcredvar(document.getElementById('mcchica').value);
		esNan('mcchica');
	}
	
	function enableCalendar(control){
		if(document.getElementById('reponerg').value == 'false') return;
		displayCalendar(document.all.fecha_cheque,'dd/mm/yyyy',control);
	}
	
	function cambiarEstado(idestado)
	{
		//if(idestado == 1)	document.getElementById('estado').innerHTML = "Pendiente";
		//if(idestado == 2)	document.getElementById('estado').innerHTML = "Autorizada";
		if(idestado == 3)	document.getElementById('estado').innerHTML = "Reposici&oacute;n";
	}
	
	function BuscarFolioGastos()
	{
		abrirVentanaFija('buscarFolioGastos.php?tipopago=1&idsucursal='+document.form1.idsucursal.value+'', 550, 450, 'ventana', 'Busqueda');
	}
	
	function ObtenerFolioGastos(id, generada)
	{
		document.getElementById('consultarfolio').value=id;
		document.getElementById('foliogenerado').value=generada;
		document.form1.submit();
	}
	
	function mensajeNoGuardar()
	{
		alerta('No es posible Guardar un folio ya generado','Atencion!','folio');
	}
	
	function mensajeNoGenerar()
	{
		alerta('No es posible Generar un folio ya generado previamente','Atencion!','folio');
	}
	
	function convertiraMoneda(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
	function esNan(caja){		
		if(document.getElementById(caja).value.replace("$ ","").replace(/,/g,"")=="NaN"){
			document.getElementById(caja).value = "$ 0.00";
		}
	}
	
	function imprimir(){
		if(document.URL.indexOf("web/")>-1){		
			window.open("http://www.pmmintranet.net/web/cajachica/excel_reportegastoscajachica.php?folio="+u.folio.value
		+"&titulo=CAJA CHICA&sucursal="+u.idsucursal.value);
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
		window.open("http://www.pmmintranet.net/web_capacitacion/cajachica/excel_reportegastoscajachica.php?folio="+u.folio.value
		+"&titulo=CAJA CHICA&sucursal="+u.idsucursal.value);
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("http://www.pmmintranet.net/web_pruebas/cajachica/excel_reportegastoscajachica.php?folio="+u.folio.value
		+"&titulo=CAJA CHICA&sucursal="+u.idsucursal.value);
		}
	}
	
	reponer(0);

	
</script>
</html>