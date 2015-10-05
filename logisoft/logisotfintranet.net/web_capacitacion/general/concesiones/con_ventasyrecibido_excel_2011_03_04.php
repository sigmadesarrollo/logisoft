<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=estadoCuenta_Excel.xls");
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
    	  <td colspan="5">CONCECIONES (VENTAS Y RECIBIDO)</td>
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
				$s = mysql_query("CREATE TEMPORARY TABLE `reporteConcesiones_tmp` (  
				`idx` INT(11) NOT NULL AUTO_INCREMENT,
				`movimiento` VARCHAR(20) DEFAULT NULL,
				`pagcontado` DOUBLE DEFAULT NULL,
                `pagcredito` DOUBLE DEFAULT NULL,
                `cobcontado` DOUBLE DEFAULT NULL,
                `cobcredito` DOUBLE DEFAULT NULL,
				`idusuario` DOUBLE DEFAULT NULL,
				PRIMARY KEY (`idx`)
				) ENGINE=INNODB DEFAULT CHARSET=latin1",$l)  or die($s);
		
				$s = "INSERT INTO reporteConcesiones_tmp(movimiento,pagcontado,pagcredito,cobcontado,cobcredito,idusuario)
				SELECT 'VENTA' AS movimiento,
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CONTADO' AND tipo = 'V' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcontado,
				
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CREDITO' and tipo = 'V' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcredito,
				
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CONTADO' and tipo = 'V' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcontado,
				
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CREDITO' and tipo = 'V' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcredito, ".$_GET[usuario]."
				FROM reporte_concesiones
				WHERE tipo = 'V' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S'
				GROUP BY movimiento";
				mysql_query($s,$l) or die($s);
				
				$s = "INSERT INTO reporteConcesiones_tmp(movimiento,pagcontado,pagcredito,cobcontado,cobcredito,idusuario)
				SELECT 'RECIBIDO' AS movimiento, 
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CONTADO' and tipo = 'R' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcontado,
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CREDITO' and tipo = 'R' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcredito,
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CONTADO' and tipo = 'R' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcontado,
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CREDITO' and tipo = 'R' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcredito, ".$_GET[usuario]."
				FROM reporte_concesiones
				WHERE tipo = 'R' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S'
				GROUP BY movimiento";
				mysql_query($s,$l) or die($s);
				
				$s = "SELECT IFNULL(movimiento,'') AS movimiento, IFNULL(pagcontado,0) AS pagcontado, IFNULL(pagcredito,0) AS pagcredito,
				IFNULL(cobcontado,0) AS cobcontado, IFNULL(cobcredito,0) AS cobcredito
				FROM reporteConcesiones_tmp WHERE idusuario = ".$_GET[usuario];
				$r = mysql_query($s,$l) or die($s);
					$r = mysql_query($s,$l) or die($s);
					$importes=0;
					if(mysql_num_rows($r)>0){
						while($f = mysql_fetch_object($r)){
					?>
    	    <tr>
    	      <td align="left"><?=$f->movimiento?></td>
    	      <td align="right"><?=$f->pagcontado?></td>
    	      <td align="right"><?=$f->pagcredito?></td>
    	      <td align="right"><?=$f->cobcontado?></td>
    	      <td align="right"><?=$f->cobcredito?></td>
    	      <td align="right"><?=($f->pagcontado + $f->pagcredito + $f->cobcontado + $f->cobcredito)?></td>
  	      </tr>
					<?
						}
					}
                    ?>
    </table>
    	    