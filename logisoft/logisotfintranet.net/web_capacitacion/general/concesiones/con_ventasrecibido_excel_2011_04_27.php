<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=conceciones_ventas_recibidas_Excel.xls");
	//header("Pragma: no-cache");
	//header("Expires: 0"); 
	
	require_once("../../Conectar.php");
	$l = Conectarse('webpmm');
	
	$s = "select * from catalogosucursal where id = '$_GET[sucursal]'";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$nsucursal = $f->descripcion;
	
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
	<table width="1728" border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="14" align="center" class="titulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
        </tr>
    	<tr>
    	  <td colspan="2">REPORTE</td>
    	  <td colspan="12">CONCECIONES (VENTAS RECIBIDAS)</td>
   	  </tr>
    	<tr>
    	  <td colspan="2"></td>
    	  <td colspan="12" align="left"><?=$nsucursal?></td>
   	  </tr>
    	<tr>
    	  <td colspan="2"></td>
    	  <td colspan="12" align="left"><?=date("d/m/Y");?></td>
   	  </tr>
    	<tr>
    	  <td height="9px" colspan="2"></td>
    	  <td width="95">&nbsp;</td>
    	  <td colspan="2"></td>
    	  <td colspan="2"></td>
    	  <td colspan="2"></td>
    	  <td colspan="5"></td>
      </tr>
    	<tr>
    	      <td width="132" height="19" align="left" class="cabecera">GUIA</td>
    	      <td width="83" align="left" class="cabecera">FECHA</td>
    	      <td align="right" class="cabecera" >FLETE</td>
    	      <td width="92" align="right" class="cabecera">DESCUENTO</td>
    	      <td width="114" align="right" class="cabecera">FLETE NETO</td>
    	      <td width="109" align="right" class="cabecera">COMISION</td>
    	      <td width="113" align="right" class="cabecera">RECOLECCION</td>
    	      <td width="109" align="right" class="cabecera" >COMISION RAD</td>
    	      <td width="98" align="right" class="cabecera" >ENTREGA</td>
    	      <td width="108" class="cabecera" align="right" >COMISION EAD</td>
    	      <td width="119" class="cabecera" align="right" >TOTAL COM</td>
			  <td width="119" class="cabecera" align="right" >TOTAL GRAL</td>
    	      <td width="205" class="cabecera" align="right" >CONDICION</td>
    	      <td width="207" align="right" class="cabecera" >STATUS</td>
      </tr>
    	    <?
				if ($_GET[fechainicio]!=''){
					$restar=" AND t2.fecha<'".$_GET[fechainicio]."'";
				}else{
					$restar=" AND t2.fecha<'2000-01-01'";
				}
				
		$s = "SELECT (t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,
		(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100)) comision,(t2.trecoleccion) recoleccion,0 AS comisionrad,(t2.tcostoead) entrega, 
		(t2.tcostoead*(t1.porcead/100)) comisionead,(t2.texcedente) sobrepeso,t2.estado,CONCAT('PAGADA','-',IF(t2.tipopago='CONTADO','CONTADO','CREDITO')) condicion,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100))) total,(t2.total) tgral
		FROM catalogosucursal t1 INNER JOIN guiasempresariales t2 ON t1.id=t2.idsucursaldestino
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t2.idsucursalorigen!=t2.idsucursaldestino AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) 
		AND t1.id=".$_GET[sucursal]." GROUP BY t2.id
		UNION
		SELECT (t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,
		(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100)) comision,(t2.trecoleccion) recoleccion,0 AS comisionrad,(t2.tcostoead) entrega, 
		(t2.tcostoead*(t1.porcead/100)) comisionead,(t2.texcedente) sobrepeso,t2.estado,
		CONCAT(IF(t2.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(t2.condicionpago=0,'CONTADO','CREDITO')) condicion,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100))) total,(t2.total) tgral
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2  ON t1.id=t2.idsucursaldestino
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.idsucursalorigen!=t2.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." GROUP BY t2.id
		UNION /*canceladas*/
		SELECT (t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))*-1) comision,(t2.trecoleccion) recoleccion,0 AS comisionrad,(t2.tcostoead) entrega, 
		((t2.tcostoead*(t1.porcead/100))*-1) comisionead,
		(t2.texcedente) sobrepeso,t2.estado,CONCAT(IF(t2.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(t2.condicionpago=0,'CONTADO','CREDITO')) condicion,
		(((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)))*-1) total,(t2.total) tgral
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2  ON t1.id=t2.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON t2.id=h.guia AND h.accion='SUSTITUCION REALIZADA'
		WHERE t2.estado='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.idsucursalorigen!=t2.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND h.sucursal=".$_GET[sucursal]." $restar 
		GROUP BY t2.id";
				$r = mysql_query($s,$l) or die($s);
					$r = mysql_query($s,$l) or die($s);
					if(mysql_num_rows($r)>0){
						while($f = mysql_fetch_object($r)){
					?>
    	    <tr>
    	      <td align="left"><?=$f->guia?></td>
    	      <td align="left"><?=$f->fechaguia?></td>
    	      <td align="right"><?='$ '.number_format($f->flete,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->descuento,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->fleteneto,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->comision,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->recoleccion,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->comisionrad,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->entrega,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->comisionead,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->total,2)?></td>
			  <td align="right"><?='$ '.number_format($f->tgral,2)?></td>
    	      <td align="right"><?=$f->condicion?></td>
    	      <td align="right"><?=$f->estado?></td>
          </tr>
					<?
						}
					}
                    ?>
    </table>
    	    