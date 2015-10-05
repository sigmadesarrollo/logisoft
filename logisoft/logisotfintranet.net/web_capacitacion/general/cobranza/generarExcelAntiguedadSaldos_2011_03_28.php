<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=AntiguedadDeSaldos.xls");
	header("Expires: 0");
?>
<style>
	td{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif;
	}
</style>
<?
	function cambio_texto($texto){
		if($texto == " ")
			$texto = "";
		if($texto!=""){
			$n_texto=ereg_replace("á","&#224;",$texto);
			$n_texto=ereg_replace("é","&#233;",$n_texto);
			$n_texto=ereg_replace("í","&#237;",$n_texto);
			$n_texto=ereg_replace("ó","&#243;",$n_texto);
			$n_texto=ereg_replace("ú","&#250;",$n_texto);
			
			$n_texto=ereg_replace("Á","&#193;",$n_texto);
			$n_texto=ereg_replace("É","&#201;",$n_texto);
			$n_texto=ereg_replace("Í","&#205;",$n_texto);
			$n_texto=ereg_replace("Ó","&#211;",$n_texto);
			$n_texto=ereg_replace("Ú","&#218;",$n_texto);
			
			$n_texto=ereg_replace("ñ", "&#241;", $n_texto);
			$n_texto=ereg_replace("Ñ", "&#209;", $n_texto);
			$n_texto=ereg_replace("¿", "&#191;", $n_texto);
			return $n_texto;
		}else{
			return "&#32;";
		}
	}
	
	function cambiaf_a_mysql($fecha){//Convierte fecha de normal a mysql
    	ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    	return $lafecha; 
	}
	
	$str = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 
	if(!ereg("dbserver",$str)){
		$l = mysql_connect("localhost","pmm","gqx64p9n");
	}else{
		$l = mysql_connect("DBSERVER","root","root");
	}
	if(ereg("web_pruebas/",$str)){
		mysql_select_db("pmm_dbpruebas", $l);
	}else if(ereg("web_capacitacion/",$str)){
		mysql_select_db("pmm_curso", $l);
	}else if(ereg("web/",$str)){
		mysql_select_db("pmm_dbweb", $l);
	}else if(ereg("dbserver",$str)){
		mysql_select_db("webpmm", $l);
	}
		if($_GET[sucursal]!=''){
			$andsucursal1 = " AND tc.idsucursal = '".$_GET[sucursal]."'"; 
			$andsucursal2 = " AND pg.sucursalacobrar = '".$_GET[sucursal]."'"; 
			$andsucursal3 = " AND f.idsucursal = '".$_GET[sucursal]."'"; 
		}
		if($_GET[idCliente]!=''){
			$folioCliente = " AND temp.idcliente = $_GET[idCliente]";
		}
		$x =rand(1,1000); 
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";mysql_query($s,$l) or die($s);
		$s = "DROP TABLE IF EXISTS tmp_clientes$x";mysql_query($s,$l) or die($s);
		/* tabla de convenios */  //$andsucursal
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
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' GROUP BY idcliente;";
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
		SELECT NULL,0 AS nfolio,pg.cliente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM guiasempresariales ge INNER JOIN pagoguias pg ON ge.id = pg.guia
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND pg.pagado = 'N' GROUP BY pg.cliente
		UNION 
		SELECT NULL,0 AS nfolio,pg.cliente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM guiasventanilla gv INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.pagado = 'N' GROUP BY pg.cliente
		UNION
		SELECT NULL,0 AS nfolio,f.cliente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM facturacion f WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' GROUP BY f.cliente;";
		mysql_query($s,$l) or die($s); 
		//agregar datos a la temporal
		$s = "UPDATE tmp_clientes$x temp INNER JOIN solicitudcredito sc ON temp.idcliente=sc.cliente
		SET dcredito=sc.diascredito,sucursal=sc.idsucursal,nfolio=sc.folio,ncliente=CONCAT(sc.nombre,' ',sc.paterno,' ',sc.materno)";
		mysql_query($s,$l) or die($s); 
		
		$s = "SELECT ivaretenido FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$ivaretenido = $f->ivaretenido;
		$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
		(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
		
	$s = "SELECT prefijosucursal,cliente,folio,fechaguia,fechafactura,fechavenc,diasvencidos,alcorriente,c1a15dias,c16a30dias,
		c31a60dias,may60dias,saldo,factura,contrarecibo,idcliente
		FROM (
		SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,ge.id AS folio,ge.fecha AS fechaguia,'' AS fechafactura,
		IFNULL(ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,$if,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))<16,$if,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))<31,$if,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))<61,$if,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,$if,0) AS may60dias,
		$if AS saldo,0 AS factura,IFNULL(ge.acuserecibo,0)AS contrarecibo,temp.idcliente
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON ge.idremitente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) $folioCliente $andsucursal1
		UNION
		SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,gv.id AS folio,gv.fecha AS fechaguia,'' AS fechafactura,
		IFNULL(ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,gv.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))<16,gv.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))<31,gv.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))<61,gv.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,gv.total,0) AS may60dias,
		gv.total AS saldo,0 AS factura,IFNULL(gv.acuserecibo,0)AS contrarecibo,temp.idcliente FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		INNER JOIN tmp_clientes$x temp ON gv.idremitente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.pagado = 'N' $folioCliente $andsucursal2
		UNION
		SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,f.folio,'' AS fechaguia,f.fecha AS fechafactura,
		IFNULL(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,f.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<16,f.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<31,f.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<61,f.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,f.total,0) AS may60dias,
		f.total AS saldo,IFNULL(f.folio,'')AS factura,0 AS contrarecibo,temp.idcliente
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' $folioCliente $andsucursal3)t ORDER BY cliente,fechaguia ";
	$r  = mysql_query($s,$l) or die($s);
?>
	<table width="1211">
    	<tr>
        	<td colspan="14" style="font-weight:bold; font-size:16">TITULO: ANTIGUEDAD DE SALDOS</td>
        </tr>
    	<tr>
    	  <td width="97" style="font-weight:bold; border:#000 solid 1px">SUCURSAL</td>
		  <td width="162" style="font-weight:bold; border:#000 solid 1px">GUIA/FACTURA</td>
    	  <td width="77" style="font-weight:bold; border:#000 solid 1px">FECHA</td>
		  <td width="76" style="font-weight:bold; border:#000 solid 1px">FECHA FACT</td>
    	  <td width="94" style="font-weight:bold; border:#000 solid 1px">FECHA VTO.</td>
    	  <td width="53" style="font-weight:bold; border:#000 solid 1px">DIAS VENC.</td>
    	  <td width="86" style="font-weight:bold; border:#000 solid 1px">AL CORRIENTE</td>
    	  <td width="61" style="font-weight:bold; border:#000 solid 1px">1-15 DIAS</td>
    	  <td width="63" style="font-weight:bold; border:#000 solid 1px">16-30 DIAS</td>
    	  <td width="61" style="font-weight:bold; border:#000 solid 1px">31-60 DIAS</td>
    	  <td width="64" style="font-weight:bold; border:#000 solid 1px">60- DIAS</td>
    	  <td width="87" style="font-weight:bold; border:#000 solid 1px">SALDO</td>
    	  <td width="62" style="font-weight:bold; border:#000 solid 1px">FACTURA</td>
    	  <td width="108" style="font-weight:bold; border:#000 solid 1px">CONTRARECIBO</td>
      </tr>
      	<?
		$arre = array();
		$arr = 0;
		$cliente = "";
		$cant = 0;
		$total = 0;
		$var_alco  	= 0;
		$var_115   	= 0;
		$var_1630   = 0;
		$var_3160   = 0;
		$var_60   	= 0;
		$var_saldo 	= 0;
		
		$alcorriente = 0;
		$vencido = 0;
		$vartotal = 0;
		
		$to_115 = 0;
		$to_1630 = 0;
		$to_3160 = 0;
		$to_60 = 0;
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;	
			if($cliente!=cambio_texto($f[cliente])){
				if($cliente!=""){
				?>
				<tr>
				  <td colspan="5"  style="border-bottom: 1px #000 solid; border-left:1px #000 solid; font-weight:bold">TOTAL <?=cambio_texto($cliente)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_alco,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_115,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_1630,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_3160,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_60,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_saldo,2)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp; </td>
				  <td style="border-bottom: 1px #000 solid; border-right:1px #000 solid">&nbsp;</td>
			  </tr>
              <tr>
              	<td colspan="14" style="font-weight:bold"></td>
              </tr>
			  <?
	  			$var_alco  	= 0;
				$var_115   	= 0;
				$var_1630   = 0;
				$var_3160   = 0;
				$var_60   	= 0;
				$var_saldo 	= 0;
	  			$cant = 0;
				}
				$cliente = cambio_texto($f[cliente]);
				?>
              <tr>
                  <td colspan="4" style="border-top:#000 1px solid; border-left:#000 1px solid;font-weight:bold">
				  <?=cambio_texto($f[cliente])?></td>
                  <td style="border-top:#000 1px solid">No. <?=cambio_texto($f[idcliente])?></td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid; border-right:#000 1px solid">&nbsp;</td>
              </tr>
			<?
			}
			if($cliente == cambio_texto($f[cliente])){
				$cant++;
			}
			?>
			<tr>
				  <td style=" border-left:1px solid #000"><?=$f[0]?></td>
				  <td><?=$f[2]?></td>
				  <td><?=$f[3]?></td>
				  <td><?=$f[4]?></td>
				  <td><?=$f[5]?></td>
				  <td><?=$f[6]?></td>
				  <td><?=$f[7]?></td>
				  <td><?=$f[8]?></td>
				  <td><?=$f[9]?></td>
				  <td><?=$f[10]?></td>
				  <td><?=$f[11]?></td>
				  <td><?=$f[12]?></td>
				  <td><?=$f[13]?></td>
				  <td style=" border-right:1px solid #000"><?=$f[14]?></td>
			  </tr>
			<?
				$var_alco  	+= $f[7];
				$var_115   	+= $f[8];
				$var_1630   += $f[9];
				$var_3160   += $f[10];
				$var_60   	+= $f[11];
				$var_saldo 	+= $f[12];
				
				$alcorriente += $f[alcorriente];
				$vencido += $f[8]+$f[9]+$f[10]+$f[11];
				$vartotal += $f[saldo];
				
				$to_115 	+= $f[8];
				$to_1630 	+= $f[9];
				$to_3160 	+= $f[10];
				$to_60 		+= $f[11];
			}
			?>
     		 <tr>
				  <td colspan="5"  style="border-bottom: 1px #000 solid; border-left:1px #000 solid; font-weight:bold">TOTAL <?=cambio_texto($cliente)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_alco,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_115,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_1630,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_3160,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_60,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_saldo,2)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; border-right:1px #000 solid">&nbsp;</td>
			  </tr>
              
              <tr>
				  <td colspan="5"  style="border-bottom: 1px #000 solid; border-left:1px #000 solid; font-weight:bold">TOTAL</td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($to_115,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($to_1630,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($to_3160,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($to_60,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($vartotal,2)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; border-right:1px #000 solid">&nbsp;</td>
			  </tr>
    </table>
<?
	$s = "DROP TABLE tmp_convenio$x";
		mysql_query($s,$l) or die($s); 
	$s = "DROP TABLE tmp_clientes$x";
		mysql_query($s,$l) or die($s); 
?>