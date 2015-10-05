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
				$pagcontado=0;
    	      	$pagcredito=0;
				$cobcontado=0;
    	      	$cobcredito=0;
    	      	$total=0;
			
			if ($_GET[fechainicio]!=''){
				$restar=" AND gv.fecha<'".$_GET[fechainicio]."'";
			}else{
				$restar=" AND gv.fecha<'2000-01-01'";
			}
			
			$s ="SELECT 'VENTA' AS movimiento,SUM(pagcont) AS pagcontado,SUM(pagcred) AS pagcredito,SUM(cobcont) AS cobcontado,
		SUM(cobcred) AS cobcredito, SUM(pagcont) + SUM(pagcred) + SUM(cobcont) + SUM(cobcred) AS total FROM(
		SELECT cs.id,SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,0)) AS pagcont,
		SUM(IF(gv.tipoflete=0 AND gv.condicionpago=1,gv.total,0)) AS pagcred,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,0)) AS cobcont,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=1,gv.total,0)) AS cobcred
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv ON cs.id=gv.idsucursalorigen
		WHERE gv.estado!='CANCELADO' AND YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY cs.id
		UNION
		SELECT cs.id,SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CONTADO',ge.total,0)) AS pagcont,
		SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CREDITO',ge.total,0)) AS pagcred,0 cobcont, 0 cobcred 
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursalorigen 
		WHERE ge.estado!='CANCELADO' AND YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." 
		GROUP BY cs.id) AS t1
		UNION
		/*recibido*/
		SELECT 'RECIBIDO' AS movimiento, SUM(pagcont) AS pagcontado,SUM(pagcred) AS pagcredito,SUM(cobcont) AS cobcontado,SUM(cobcred) AS cobcredito,
		SUM(pagcont) + SUM(pagcred) + SUM(cobcont) + SUM(cobcred) AS total FROM(
		SELECT cs.id,SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CONTADO',ge.total,0)) AS pagcont,
		SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CREDITO',ge.total,0)) AS pagcred,0 cobcont, 0 cobcred 
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursaldestino
		WHERE ge.estado!='CANCELADO' AND YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' 
		".((!empty($_GET[fechainicio]))? " AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND ge.idsucursalorigen!=ge.idsucursaldestino
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY cs.id
		UNION
		SELECT cs.id,SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,0)) AS pagcont,
		SUM(IF(gv.tipoflete=0 AND gv.condicionpago=1,gv.total,0)) AS pagcred,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,0)) AS cobcont,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=1,gv.total,0)) AS cobcred
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		WHERE gv.estado!='CANCELADO' AND YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND gv.idsucursalorigen!=gv.idsucursaldestino
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY cs.id
		UNION /*canceladas*/
		SELECT cs.id,SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,0))*-1 AS pagcont,
		SUM(IF(gv.tipoflete=0 AND gv.condicionpago=1,gv.total,0))*-1 AS pagcred,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,0))*-1 AS cobcont,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=1,gv.total,0))*-1 AS cobcred
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON gv.id=h.guia AND h.accion='SUSTITUCION REALIZADA'
		WHERE gv.estado='CANCELADO' AND YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND gv.idsucursalorigen!=gv.idsucursaldestino
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND h.sucursal=".$_GET[sucursal]." $restar GROUP BY cs.id) AS t2";
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
						$pagcontado+=$f->pagcontado;
    	      			$pagcredito+=$f->pagcredito;
				  		$cobcontado+=$f->cobcontado;
    	      			$cobcredito+=$f->cobcredito;
    	      			$total+=$f->pagcontado + $f->pagcredito + $f->cobcontado + $f->cobcredito;
						}
					}
                    ?>
		  <tr>
    	      <td align="left"><b>TOTALES</b></td>
    	      <td align="right"><?='$ '.number_format($pagcontado,2)?></td>
    	      <td align="right"><?='$ '.number_format($pagcredito,2)?></td>
    	      <td align="right"><?='$ '.number_format($cobcontado,2)?></td>
    	      <td align="right"><?='$ '.number_format($cobcredito,2)?></td>
    	      <td align="right"><?='$ '.number_format($total,2)?></td>
  	      </tr>
    </table>
    	    