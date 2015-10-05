<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=guiasCanceladas_Excel.xls");
	//header("Pragma: no-cache");
	//header("Expires: 0"); 
	
	require_once("../Conectar.php");
	$l = Conectarse('webpmm');
	
	if(!empty($_GET[checktodas])){
		$nsucursal = "TODAS";
		$andsuc = "";
	}else{
		if($_GET[sucursalmovio]==0){
			$andsuc = " AND gv.idsucursalorigen = $_GET[sucursal_hidden] ";
			$nsucursal = "REALIZADAS EN ".$_GET[sucursal];
		}else{
			$andsuc = " AND hc.sucursal = $_GET[sucursal_hidden] ";
			$nsucursal = "CANCELADAS EN ".$_GET[sucursal];
		}
	}
	
	if($_GET[tipofecha]==0){
		$fechas = "REALIZADAS ENTRE $_GET[inicio] Y $_GET[fin]";
		$andfec = " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."' ";
	}else{
		$fechas = "CANCELADAS ENTRE $_GET[inicio] Y $_GET[fin]";
		$andfec = " AND hc.fecha BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."' ";
	}
	
	
	
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
		/*background-color:#288ADB;*/
	}
	
</style>
	<table width="1389" border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="10" align="center" class="titulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
        </tr>
    	<tr>
    	  <td width="157">REPORTE</td>
    	  <td colspan="9">REPORTE DE GUIAS CANCELADAS</td>
   	  </tr>
    	<tr>
    	  <td></td>
    	  <td colspan="9" align="left"><?=$nsucursal?></td>
   	  </tr>
    	<tr>
    	  <td></td>
    	  <td colspan="9" align="left"><?=$fechas?></td>
   	  </tr>
    	<tr>
    	  <td height="9px"></td>
    	  <td width="82">&nbsp;</td>
    	  <td width="87"></td>
    	  <td width="133"></td>
    	  <td width="115"></td>
    	  <td width="189"></td>
    	  <td width="98"></td>
    	  <td colspan="3"></td>
      </tr>
    	<tr>
    	      <td width="157" class="cabecera" align="center">GUIA</td>
    	      <td width="82" class="cabecera" align="center" >ORIGEN</td>
    	      <td width="87" class="cabecera" align="center" >DESTINO</td>
    	      <td width="133" class="cabecera" align="center">EMISION</td>
    	      <td width="115" class="cabecera" align="center">CANCELACI&Oacute;N</td>
    	      <td width="189" class="cabecera" align="right">IMPORTE</td>
    	      <td width="98" class="cabecera" align="center" >TIPOFLETE</td>
    	      <td width="98" class="cabecera" align="center" >CANCELO</td>
    	      <td width="102" class="cabecera" align="center" >AFECTA</td>
    	      <td width="306" class="cabecera" align="left" >EMPLEADO</td>
      </tr>
    	    <?
					$s = "SELECT gv.id guia, cso.prefijo origen, csd.prefijo destino, DATE_FORMAT(gv.fecha, '%d/%m/%Y') emision,
					DATE_FORMAT(hc.fecha, '%d/%m/%Y') cancelacion, gv.total importe, 
					IF(gv.tipoflete = 0,'PAGADA','POR COBRAR') tipoflete,
					csc.prefijo cancelo, IF(gv.tipoflete=0, cso.prefijo, csd.prefijo) afecta,
					CONCAT(ce.nombre, ' ', ce.apellidopaterno, ' ', ce.apellidomaterno) empleado
					FROM guiasventanilla gv
					INNER JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia
					INNER JOIN catalogosucursal cso ON gv.idsucursalorigen = cso.id
					INNER JOIN catalogosucursal csd ON gv.idsucursaldestino = csd.id
					INNER JOIN catalogosucursal csc ON hc.sucursal = csc.id
					INNER JOIN catalogoempleado ce ON hc.usuario = ce.id
					WHERE (hc.accion = 'SUSTITUCION REALIZADA' OR hc.accion = 'CANCELADO')
					$andsuc $andfec";
					$r = mysql_query($s,$l) or die($s);
					$importes=0;
					if(mysql_num_rows($r)>0){
						$total = mysql_num_rows($r);
						while($f = mysql_fetch_object($r)){
							$importes += $f->importe;
							$f->origen = utf8_encode($f->origen);
							$f->destino = utf8_encode($f->destino);
							$f->cancelo = utf8_encode($f->cancelo);
							$f->afecta = utf8_encode($f->afecta);
							$f->empleado = utf8_encode($f->empleado);
					?>
    	    <tr>
    	      <td align="center"><?=$f->guia?></td>
    	      <td align="center"><?=$f->origen?></td>
    	      <td align="center"><?=$f->destino?></td>
    	      <td align="center"><?=$f->emision?></td>
    	      <td align="center"><?=$f->cancelacion?></td>
    	      <td align="right"><?='$ '.number_format($f->importe,2)?></td>
    	      <td align="center"><?=$f->tipoflete?></td>
    	      <td align="center"><?=$f->cancelo?></td>
    	      <td align="center"><?=$f->afecta?></td>
    	      <td align="left"><?=$f->empleado?></td>
  	      </tr>
					<?
						}
					}
                    ?>
          <tr>
    	      <td colspan="2" align="center" class="cabecera">TOTALES</td>
    	      <td align="center" class="cabecera">&nbsp;</td>
    	      <td align="right" class="cabecera"><?=$total?></td>
    	      <td align="right" class="cabecera">&nbsp;</td>
    	      <td align="right" class="cabecera">=SUMA(F7:F21)</td>
    	      <td align="right" class="cabecera">&nbsp;</td>
    	      <td align="right" class="cabecera">&nbsp;</td>
    	      <td align="right" class="cabecera">&nbsp;</td>
    	      <td align="right" class="cabecera">&nbsp;</td>
  	      </tr>
    </table>
    	    