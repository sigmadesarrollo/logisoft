<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=estadoCuenta_Excel.xls");
	//header("Pragma: no-cache");
	//header("Expires: 0"); 
	
	require_once("../ConectarSolo.php");
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente 
		FROM catalogocliente cc WHERE id = '$_GET[cliente]'";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$clienteNombre = $f->cliente;
	
	$s = "select descripcion
	from catalogosucursal where id = '$_GET[sucursal]'";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$sucusalNombre = ($f->descripcion=="")?"TODAS":$f->descripcion;
?>
<style>
	table{
		font:Verdana, Geneva, sans-serif;
		font-size:12px;
		border: 1px #5FADDC solid;
	}
	.titulo{
		font-size:14px;
		font-weight:bold;
	}
	.cabecera{
		font-weight:bold;
		border:1px solid #5FADDC;
	}
</style>
	<table width="1004" border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="8" align="center" class="titulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
        </tr>
    	<tr>
    	  <td width="97">REPORTE</td>
    	  <td colspan="7">ESTADO DE CUENTA</td>
   	  </tr>
    	<tr>
    	  <td>CLIENTE</td>
    	  <td colspan="7" align="left"><?=$clienteNombre?></td>
   	  </tr>
    	<tr>
    	  <td>SUCURSAL</td>
    	  <td colspan="7" align="left"><?=$sucusalNombre?></td>
   	  </tr>
    	<tr>
    	  <td height="9px"></td>
    	  <td width="128">&nbsp;</td>
    	  <td width="145"></td>
    	  <td width="148"></td>
    	  <td width="92"></td>
    	  <td width="93"></td>
    	  <td width="93"></td>
    	  <td width="190"></td>
      </tr>
    	<tr>
    	      <td width="97" class="cabecera" align="center">FECHA</td>
    	      <td width="128" class="cabecera" align="center" >SUCURSAL</td>
    	      <td width="145" class="cabecera" align="center" >REF CARGO</td>
    	      <td width="148" class="cabecera" align="center">REF ABONO</td>
    	      <td width="92" class="cabecera" align="right">CARGO</td>
    	      <td width="93" class="cabecera" align="right">ABONO</td>
    	      <td width="93" class="cabecera" align="right" >SALDO</td>
    	      <td width="190" class="cabecera" align="left" >DESCRIPCION</td>
      </tr>
    	    <?
					if($_GET[sucursal]!=''){
						$sucursal_filtro = " AND idsucursal = '$_GET[sucursal]' ";
					}
					
					$s = "CREATE TEMPORARY TABLE `movimientos_tmp` (                                                  
					  `id` DOUBLE NOT NULL AUTO_INCREMENT,                                  
					  `fecha` DATE DEFAULT NULL,  
					  `sucursal` VARCHAR(50) COLLATE utf8_general_ci DEFAULT NULL,                                           
					  `referenciacargo` VARCHAR(250) COLLATE utf8_general_ci DEFAULT NULL,  
					  `referenciaabono` VARCHAR(20) COLLATE utf8_general_ci DEFAULT NULL,   
					  `cargos` DOUBLE DEFAULT NULL,                                         
					  `abonos` DOUBLE DEFAULT NULL,                                         
					  `saldo` DOUBLE DEFAULT NULL,                                          
					  `descripcion` VARCHAR(100) COLLATE utf8_general_ci DEFAULT NULL,      
					  PRIMARY KEY  (`id`)                                                   
					) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
					mysql_query($s,$l) or die($s);
					
					//se insertan los movimientos anteriores
					$s = "INSERT INTO movimientos_tmp (cargos,abonos,saldo,fecha)
					SELECT SUM(cargo),SUM(abono),IFNULL(SUM(cargo)-SUM(abono),0) AS saldo, adddate(current_date, interval -1 day)
					FROM reporte_cobranza4
					WHERE ((MONTH(fecha) < MONTH(CURDATE()) and year(fecha) = year(CURDATE())) or (year(fecha) < year(CURDATE())))
					and idcliente = ".$_GET[cliente]." and reporte_cobranza4.estado <> 'DESACTIVADO' $sucursal_filtro
					HAVING saldo>0"; 
					$r = mysql_query($s,$l) or die($s);
					
					$s = "SELECT IFNULL(SUM(cargo)-SUM(abono),0) AS saldo
					FROM reporte_cobranza4
					WHERE ((MONTH(fecha) < MONTH(CURDATE()) and year(fecha) = year(CURDATE())) or (year(fecha) < year(CURDATE())))
					and idcliente = $_GET[cliente]
					and reporte_cobranza4.estado <> 'DESACTIVADO' $sucursal_filtro
					HAVING saldo>0 ";
					$r = mysql_query($s,$l) or die($s);
					$f = mysql_fetch_object($r);
					$saldo = $f->saldo;
					
					//se insertan los nuevos
					$s = "SELECT reporte_cobranza4.*, cargo FROM reporte_cobranza4 
					WHERE MONTH(fecha) = MONTH(CURDATE()) and YEAR(fecha)=YEAR(CURDATE()) and idcliente = ".$_GET[cliente]."
					and reporte_cobranza4.estado <> 'DESACTIVADO' $sucursal_filtro"; 
					$r = mysql_query($s,$l) or die($s);
					
					while($f=mysql_fetch_object($r)){
						$saldo = $saldo+$f->cargo;
						$saldo = $saldo-$f->abono;
						$s = "INSERT INTO movimientos_tmp
						SET fecha = '$f->fecha', sucursal = '$f->prefijosucursal', referenciacargo = '$f->folio', 
						referenciaabono = '$f->refabono', cargos = '$f->cargo', abonos = '$f->abono', saldo = '$saldo',
						descripcion = '$f->descripcion';";
						mysql_query($s,$l) or die($s);
					}
					
					#registros
					$s = "SELECT ifnull(DATE_FORMAT(fecha, '%d/%m/%Y'),'') AS fecha,
					ifnull(sucursal,'') as sucursal, ifnull(referenciacargo,'') as referenciacargo,
					ifnull(referenciaabono,'') as referenciaabono, 		
					ifnull(cargos,0) as cargos, ifnull(abonos,0) as abonos, ifnull(saldo,0) as saldo,
					ifnull(descripcion,'') as descripcion
					FROM movimientos_tmp";
					$r = mysql_query($s,$l) or die($s."\n".mysql_error($l));
					$cantidad	= 0;
					$cargos 	= 0;
					$abonos 	= 0;
					$saldos		= 0;
					while($f = mysql_fetch_object($r)){
						$cantidad++;
						$saldos = $f->saldo;
						$cargos += $f->cargos;
						$abonos += $f->abonos;
					?>
    	    <tr>
    	      <td align="center"><?=$f->fecha?></td>
    	      <td align="center"><?=$f->sucursal?></td>
    	      <td align="center"><?=$f->referenciacargo?></td>
    	      <td align="center"><?=$f->referenciaabono?></td>
    	      <td align="right"><?=$f->cargos?></td>
    	      <td align="right"><?=$f->abonos?></td>
    	      <td align="right"><?=$f->saldo?></td>
    	      <td align="left"><?=$f->descripcion?></td>
  	      </tr>
    	    <?
						}
				  ?>
          <tr>
    	      <td colspan="2" align="center" class="cabecera">&nbsp;</td>
    	      <td align="center" class="cabecera">TOTALES</td>
    	      <td align="center" class="cabecera"><?=$cantidad?></td>
    	      <td align="right" class="cabecera">$&nbsp;<?=number_format($cargos,2)?></td>
    	      <td align="right" class="cabecera">$&nbsp;<?=number_format($abonos,2)?></td>
    	      <td align="right" class="cabecera">$&nbsp;<?=number_format($saldos,2)?></td>
    	      <td align="left" class="cabecera">&nbsp;</td>
  	      </tr>
    </table>
    	    