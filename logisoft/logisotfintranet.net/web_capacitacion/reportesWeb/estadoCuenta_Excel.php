<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=estadoCuenta_Excel.xls");
	//header("Pragma: no-cache");
	//header("Expires: 0"); 
	
	require_once("../Conectar.php");
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
		$x =rand(1,1000); 
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";mysql_query($s,$l) or die($s);
		/* tabla de convenios */
		$s = "CREATE TABLE `tmp_convenio$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio$x
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL	
		FROM generacionconvenio GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		/* tabla de clientes */
		$s = "CREATE TABLE `tmp_clientes$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`nfolio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`ncliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		`sucursal` DOUBLE DEFAULT NULL,
		`dcredito` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los clientes en la temporal
		$s = "INSERT INTO tmp_clientes$x
		SELECT NULL,0 AS nfolio,ge.idremitente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM guiasempresariales ge 
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) GROUP BY ge.idremitente
		UNION 
		SELECT NULL,0 AS nfolio,pg.cliente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM guiasventanilla gv INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' GROUP BY pg.cliente
		UNION
		SELECT NULL,0 AS nfolio,f.cliente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM facturacion f WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' GROUP BY f.cliente;";
		mysql_query($s,$l) or die($s); 
		//agregar datos a la temporal
		$s = "UPDATE tmp_clientes$x temp INNER JOIN solicitudcredito sc ON temp.idcliente=sc.cliente
		SET dcredito=sc.diascredito,sucursal=sc.idsucursal,nfolio=sc.folio,ncliente=CONCAT(sc.nombre,' ',sc.paterno,' ',sc.materno)";
		mysql_query($s,$l) or die($s); 
		
		if($_GET[sucursal]!=''){
			$sucursal_filtro = " AND tc.idsucursal = '$_GET[sucursal]' ";
			$sucursal_filtro2 = " AND pg.sucursalacobrar = '$_GET[sucursal]' ";
			$sucursal_filtro3 = " AND f.idsucursal = '$_GET[sucursal]' ";
			$sucursal_filtro4 = " AND fp.sucursal = '$_GET[sucursal]' ";
		}
		
		$s = "SELECT ivaretenido FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$ivaretenido = $f->ivaretenido;
		$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
		(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
		
		#registros
		$s = "SELECT fecha,sucursal,IFNULL(refcargo,'') referenciacargo,IFNULL(refabono,'') referenciaabono,cargos,abonos,saldo,descripcion	
		FROM(	/* cargos */
		SELECT ge.fecha,cs.prefijo AS sucursal,ge.id AS refcargo,0 AS refabono,SUM($if) AS cargos,0 AS abonos,SUM($if) AS saldo,'' AS descripcion
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON tc.idcliente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND temp.idcliente=$_GET[cliente] AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."' $sucursal_filtro GROUP BY ge.id
		UNION
		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS refcargo,0 AS refabono,SUM(gv.total) AS cargos,0 AS abonos,SUM(gv.total) AS saldo,'' AS descripcion
		FROM guiasventanilla gv	
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		INNER JOIN tmp_clientes$x temp ON pg.cliente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND temp.idcliente=$_GET[cliente] 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' $sucursal_filtro2 GROUP BY gv.id
		UNION
		SELECT f.fecha,cs.prefijo AS sucursal,f.folio AS refcargo,0 AS refabono,SUM(f.total + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) AS cargos,
		0 AS abonos,SUM(f.total + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) AS saldo,'' AS descripcion
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND temp.idcliente=$_GET[cliente] AND f.tipoguia='empresarial'
		AND f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' $sucursal_filtro3 GROUP BY f.folio
		UNION
		SELECT f.fecha,cs.prefijo AS sucursal,fd.folio AS refcargo,0 AS refabono,SUM(fd.total) AS cargos,0 AS abonos,SUM(fd.total) AS saldo,'' AS descripcion
		FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND temp.idcliente=$_GET[cliente] AND f.tipoguia!='empresarial'
		AND f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' $sucursal_filtro3 GROUP BY fd.folio
		UNION	/* abonos */
		SELECT fp.fecha,cs.prefijo AS sucursal,0 AS refcargo,fp.guia AS refabono,0 AS cargos,SUM(fp.total) AS abonos,SUM(fp.total) AS saldo,
		CONCAT(IF(fp.efectivo>0,'EFECTIVO, ',''),IF(fp.tarjeta>0,'TARJETA, ',''),IF(fp.transferencia>0,'TRANSFERENCIA, ',''),
		IF(fp.cheque>0,CONCAT('CHEQUE ',IFNULL(fp.ncheque,'')),'')) AS descripcion
		FROM formapago fp 
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON fp.cliente=temp.idcliente
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND temp.idcliente=$_GET[cliente]
		AND fp.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' $sucursal_filtro4  
		GROUP BY fp.guia)t1 ORDER BY fecha ";
		$r = mysql_query($s,$l) or die($s."\n".mysql_error($l));
		$cantidad	= 0;
		$cargos 	= 0;
		$abonos 	= 0;
		$saldos		= 0;
		while($f = mysql_fetch_object($r)){
			$cantidad++;
			$saldos += $f->saldo;
			$cargos += $f->cargos;
			$abonos += $f->abonos;
	?>
    	    <tr>
    	      <td align="center"><?=$f->fecha?></td>
    	      <td align="center"><?=$f->sucursal?></td>
    	      <td align="center"><?=$f->referenciacargo?></td>
    	      <td align="center"><?=$f->referenciaabono?></td>
    	      <td align="right"><?='$ '.number_format($f->cargos,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->abonos,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->saldo,2)?></td>
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
    	      <td align="right" class="cabecera">$&nbsp;<?=number_format($cargos-$abonos,2)?></td>
    	      <td align="left" class="cabecera">&nbsp;</td>
  	      </tr>
    </table>
   <?
   	$s = "DROP TABLE tmp_convenio$x";
		 mysql_query($s,$l) or die($s);
   ?> 	    