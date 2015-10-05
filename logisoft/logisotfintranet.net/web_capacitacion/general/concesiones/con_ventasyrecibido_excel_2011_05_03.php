<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=concesiones_ventas_y_recibido_Excel.xls");
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
	<table width="1163" border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="6" align="center" class="titulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
        </tr>
    	<tr>
    	  <td width="171">REPORTE</td>
    	  <td colspan="5">CONCESIONES (VENTAS Y RECIBIDO)</td>
   	  </tr>
    	<tr>
    	  <td></td>
    	  <td colspan="5" align="left"><?=$nsucursal?></td>
   	  </tr>
    	<tr>
    	  <td></td>
    	  <td colspan="5" align="left"><?=date("d/m/Y");?></td>
   	  </tr>
    	<tr>
    	  <td height="9px"></td>
    	  <td width="207">&nbsp;</td>
    	  <td width="210"></td>
    	  <td width="180"></td>
    	  <td width="199"></td>
    	  <td></td>
      </tr>
    	<tr>
    	      <td width="171" height="19" align="left" class="cabecera">MOVIMIENTOS</td>
    	      <td align="right" class="cabecera" >PAGADA-CONTADO</td>
    	      <td align="right" class="cabecera">PAGADA-CREDITO </td>
    	      <td align="right" class="cabecera">COBRAR-CONTADO</td>
    	      <td align="right" class="cabecera" >COBRAR-CREDITO</td>
    	      <td width="182" class="cabecera" align="right" >TOTAL</td>
      </tr>
    	    <?
			
			if ($_GET[fechainicio]!=''){
				$restar=" AND t2.fecha<'".$_GET[fechainicio]."'";
			}else{
				$restar=" AND t2.fecha<'2000-01-01'";
			}
			
			$s ="SELECT 'VENTA' AS movimiento,SUM(pagcont) AS pagcontado,SUM(pagcred) AS pagcredito,SUM(cobcont) AS cobcontado,
		SUM(cobcred) AS cobcredito, SUM(pagcont) + SUM(pagcred) + SUM(cobcont) + SUM(cobcred) AS total FROM(
		SELECT t1.id,t1.descripcion,t1.ventas,t1.porcrecoleccion,
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS pagcont,
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS pagcred,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS cobcont,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS cobcred
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2 ON t1.id=t2.idsucursalorigen
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."
		AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." GROUP BY t1.id
		UNION
		SELECT t1.id,t1.descripcion,t1.ventas,t1.porcrecoleccion, 
		SUM(IF(t2.tipoflete='PAGADA' AND t2.tipopago='CONTADO',(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS pagcont,
		SUM(IF(t2.tipoflete='PAGADA' AND t2.tipopago='CREDITO',(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS pagcred,
		0 cobcont, 0 cobcred FROM catalogosucursal t1 INNER JOIN guiasempresariales t2 ON t1.id=t2.idsucursalorigen 
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." 
		AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." GROUP BY t1.id) AS t1
		UNION
		/*recibido*/
		SELECT 'RECIBIDO' AS movimiento, SUM(pagcont) AS pagcontado,SUM(pagcred) AS pagcredito,SUM(cobcont) AS cobcontado,SUM(cobcred) AS cobcredito,
		SUM(pagcont) + SUM(pagcred) + SUM(cobcont) + SUM(cobcred) AS total FROM(
		SELECT t1.id,t1.descripcion,t1.recibido,t1.porcead, 
		SUM(IF(t2.tipoflete='PAGADA' AND t2.tipopago='CONTADO',(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS pagcont,
		SUM(IF(t2.tipoflete='PAGADA' AND t2.tipopago='CREDITO',(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS pagcred,
		0 cobcont, 0 cobcred FROM catalogosucursal t1 INNER JOIN guiasempresariales t2 ON t1.id=t2.idsucursaldestino
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.tipoflete='PAGADA' 
		".((!empty($_GET[fechainicio]))? " AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t2.idsucursalorigen!=t2.idsucursaldestino
		AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." GROUP BY t1.id
		UNION
		SELECT t1.id,t1.descripcion,t1.recibido,t1.porcead, 
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS pagcont,
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS pagcred,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS cobcont,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS cobcred
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2  ON t1.id=t2.idsucursaldestino
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t2.idsucursalorigen!=t2.idsucursaldestino
		AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." GROUP BY t1.id
		UNION /*canceladas*/
		SELECT t1.id,t1.descripcion,t1.recibido,t1.porcead, 
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0))*-1 AS pagcont,
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0))*-1 AS pagcred,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0))*-1 AS cobcont,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0))*-1 AS cobcred
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2  ON t1.id=t2.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON t2.id=h.guia AND h.accion='SUSTITUCION REALIZADA'
		WHERE t2.estado='CANCELADO' AND YEAR(t2.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t2.idsucursalorigen!=t2.idsucursaldestino
		AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND h.sucursal=".$_GET[sucursal]." $restar GROUP BY t1.id) AS t2";
					$r = mysql_query($s,$l) or die($s);
					$importes=0;
					if(mysql_num_rows($r)>0){
					while($f = mysql_fetch_object($r)){
					?>
    	    <tr>
    	      <td align="left"><?=$f->movimiento?></td>
    	      <td align="right"><?='$ '.number_format($f->pagcontado,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->pagcredito,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->cobcontado,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->cobcredito,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->pagcontado + $f->pagcredito + $f->cobcontado + $f->cobcredito,2)?></td>
  	      </tr>
					<?
						}
					}
                    ?>
    </table>
    	    