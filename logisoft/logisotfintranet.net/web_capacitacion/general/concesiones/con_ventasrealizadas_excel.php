<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=concesiones_ventas_realizadas_Excel.xls");
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
        	<td colspan="15" align="center" class="titulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
        </tr>
    	<tr>
    	  <td colspan="2">REPORTE</td>
    	  <td colspan="13">CONCESIONES (VENTAS REALIZADAS)</td>
   	  </tr>
    	<tr>
    	  <td colspan="2"></td>
    	  <td colspan="13" align="left"><?=$nsucursal?></td>
   	  </tr>
    	<tr>
    	  <td colspan="2"></td>
    	  <td colspan="13" align="left"><?=date("d/m/Y");?></td>
   	  </tr>
    	<tr>
    	  <td height="9px" colspan="2"></td>
    	  <td width="95">&nbsp;</td>
    	  <td colspan="2"></td>
    	  <td colspan="2"></td>
    	  <td colspan="2"></td>
    	  <td colspan="6"></td>
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
    	      <td width="114" class="cabecera" align="right" >COM. SOBREPESO</td>
    	      <td width="119" class="cabecera" align="right" >TOTAL COM</td>
			  <td width="119" class="cabecera" align="right" >TOTAL GRAL</td>
    	      <td width="205" class="cabecera" align="right" >CONDICION</td>
    	      <td width="207" align="right" class="cabecera" >STATUS</td>
      </tr>
    	<?
				$flete = 0;
				$descuento = 0;
				$fleteneto = 0;
				$comision = 0;
				$recoleccion = 0;
				$comisionrad = 0;
				$entrega = 0;
				$comisionead = 0;
				$sobrepeso = 0;
				$total = 0;
				$tgral = 0;
						
			$s = "SELECT (gv.id) guia,(gv.fecha) fechaguia,(gv.tflete) flete,(gv.ttotaldescuento) descuento,((gv.tflete+gv.texcedente)-gv.ttotaldescuento) fleteneto,
		(((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.ventas/100)) comision,(gv.trecoleccion) recoleccion,
		(gv.trecoleccion*(cs.porcrecoleccion/100)) comisionrad,(gv.tcostoead) entrega, 0 AS comisionead,(gv.texcedente) sobrepeso,gv.estado,
		CONCAT(IF(gv.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(gv.condicionpago=0,'CONTADO','CREDITO')) condicion,
		((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.ventas/100))+(gv.trecoleccion*(cs.porcrecoleccion/100))) total,(gv.total) tgral
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv ON cs.id=gv.idsucursalorigen
		WHERE /*gv.estado!='CANCELADO' AND*/ YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) 
		AND cs.id=".$_GET[sucursal]." GROUP BY gv.id
		UNION
		SELECT (ge.id) guia,(ge.fecha) fechaguia,(ge.tflete) flete,(ge.ttotaldescuento) descuento,((ge.tflete+ge.texcedente)-ge.ttotaldescuento) fleteneto,
		(((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.ventas/100)) comision,(ge.trecoleccion) recoleccion,
		(ge.trecoleccion*(cs.porcrecoleccion/100)) comisionrad,(ge.tcostoead) entrega, 0 AS comisionead,
		(ge.texcedente) sobrepeso,ge.estado,CONCAT('PAGADA','-',IF(ge.tipopago='CONTADO','CONTADO','CREDITO')) condicion,
		((((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.ventas/100))+(ge.trecoleccion*(cs.porcrecoleccion/100))) total, (ge.total) tgral
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursalorigen 
		WHERE /*ge.estado!='CANCELADO' AND*/ YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY ge.id
		UNION /*canceladas*/
		SELECT (gv.id) guia,(gv.fecha) fechaguia,(gv.tflete*-1) flete,(gv.ttotaldescuento*-1) descuento,((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*-1 fleteneto,
		((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))*-1) comision,(gv.trecoleccion*-1) recoleccion,0 AS comisionrad,(gv.tcostoead*-1) entrega, 
		((gv.tcostoead*(cs.porcead/100))*-1) comisionead,
		(gv.texcedente*-1) sobrepeso,gv.estado,CONCAT(IF(gv.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(gv.condicionpago=0,'CONTADO','CREDITO')) condicion,
		(((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))+(gv.tcostoead*(cs.porcead/100)))*-1) total,(gv.total*-1) tgral
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursalorigen
		INNER JOIN historial_cancelacionysustitucion h ON gv.id=h.guia AND (h.accion='SUSTITUCION REALIZADA' or h.accion='CANCELADO')
		WHERE gv.estado='CANCELADO' AND YEAR(gv.fecha)>='2011' AND YEAR(h.fecha)>=2011 
		AND gv.idsucursalorigen!=gv.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND h.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		h.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND h.sucursal=".$_GET[sucursal]." 
		GROUP BY gv.id ";	
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
    	      <td align="right"><?='$ '.number_format($f->sobrepeso,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->total,2)?></td>
			  <td align="right"><?='$ '.number_format($f->tgral,2)?></td>
    	      <td align="right"><?=$f->condicion?></td>
    	      <td align="right"><?=$f->estado?></td>
          </tr>
					<?
						$flete += $f->flete;
						$descuento += $f->descuento;
						$fleteneto += $f->fleteneto;
						$comision += $f->comision;
						$recoleccion += $f->recoleccion;
						$comisionrad += $f->comisionrad;
						$entrega += $f->entrega;
						$comisionead += $f->comisionead;
						$sobrepeso += $f->sobrepeso;
						$total += $f->total;
						$tgral +=$f->tgral;
						}
					}
                    ?>
		  <tr>
    	      <td align="left"></td>
    	      <td align="left">TOTALES</td>
    	      <td align="right"><?='$ '.number_format($flete,2)?></td>
    	      <td align="right"><?='$ '.number_format($descuento,2)?></td>
    	      <td align="right"><?='$ '.number_format($fleteneto,2)?></td>
    	      <td align="right"><?='$ '.number_format($comision,2)?></td>
    	      <td align="right"><?='$ '.number_format($recoleccion,2)?></td>
    	      <td align="right"><?='$ '.number_format($comisionrad,2)?></td>
    	      <td align="right"><?='$ '.number_format($entrega,2)?></td>
    	      <td align="right"><?='$ '.number_format($comisionead,2)?></td>
    	      <td align="right"><?='$ '.number_format($sobrepeso,2)?></td>
    	      <td align="right"><?='$ '.number_format($total,2)?></td>
			  <td align="right"><?='$ '.number_format($tgral,2)?></td>
    	      <td align="right"></td>
    	      <td align="right"></td>
          </tr>
    </table>
    	    