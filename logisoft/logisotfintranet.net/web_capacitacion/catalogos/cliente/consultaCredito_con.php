<?
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');

	if($_GET[accion] == 1){
		if($_GET[tipo]==1){
			$x =rand(1,1000); 
			$s = "DROP TABLE IF EXISTS tmp_convenio$x";
			mysql_query($s,$l) or die($s);
			
			$s = "CREATE TABLE `tmp_convenio$x` (
			`id` DOUBLE NOT NULL AUTO_INCREMENT,
			`folio` DOUBLE DEFAULT NULL,
			`idcliente` DOUBLE DEFAULT NULL,
			`idsucursal` DOUBLE DEFAULT NULL,
			PRIMARY KEY  (`id`),
			KEY  `idcliente` (`idcliente`)
			) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
			mysql_query($s,$l) or die($s);
			$s = "INSERT INTO tmp_convenio$x SELECT NULL,MAX(folio) AS folio,idcliente,NULL FROM generacionconvenio GROUP BY idcliente;";
			mysql_query($s,$l) or die($s); 
			$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio SET idsucursal=gc.sucursal;";
			mysql_query($s,$l) or die($s); 
		}
		
		$s = "SELECT cc.foliocredito, cc.saldo, sc.montoautorizado AS limitecredito,cc.diascredito, cc.diapago, cc.diarevision, cc.activado,
		CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno) cliente,cc.clasificacioncliente, sc.estado
		FROM catalogocliente cc INNER JOIN solicitudcredito sc ON cc.id = sc.cliente
		WHERE cc.id=".$_GET[cliente]." AND (sc.estado='ACTIVADO' OR sc.estado='BLOQUEADO')";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
		  if($_GET[tipo]==1){
			while($f = mysql_fetch_object($r)){
				$s = "SELECT ivaretenido FROM configuradorgeneral";
				$r = mysql_query($s,$l) or die($s);
				$fw = mysql_fetch_object($r);
				$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
				(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($fw->ivaretenido/100),0)),ge.total)";
		
				$s = "SELECT $f->limitecredito - SUM(ifnull(total,0)) disponible, SUM(ventames) ventames, SUM(ifnull(total,0)) saldo FROM (
				SELECT SUM(IF(MONTH(ge.fecha)=MONTH(CURRENT_DATE) AND YEAR(ge.fecha)=YEAR(CURRENT_DATE),$if,0)) ventames,SUM($if) total
				FROM guiasempresariales ge INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
				INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
				WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
				AND ISNULL(ge.factura) AND tc.idcliente='".$_GET['cliente']."'
				UNION
				SELECT SUM(IF(MONTH(gv.fecha)=MONTH(CURRENT_DATE) AND YEAR(gv.fecha)=YEAR(CURRENT_DATE),gv.total,0)) ventames,SUM(gv.total) total
				FROM guiasventanilla gv INNER JOIN pagoguias pg ON gv.id = pg.guia
				WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.cliente='".$_GET['cliente']."'
				UNION
				SELECT SUM(IF(MONTH(f.fecha)=MONTH(CURRENT_DATE) AND YEAR(f.fecha)=YEAR(CURRENT_DATE),(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) 
				+ IFNULL(f.otrosmontofacturar,0)),0)) ventames,SUM(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) total
				FROM facturacion f 
				WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.tipoguia='empresarial' AND f.cliente='".$_GET['cliente']."'
				UNION
				SELECT SUM(IF(MONTH(f.fecha)=MONTH(CURRENT_DATE) AND YEAR(f.fecha)=YEAR(CURRENT_DATE),fd.total,0)) ventames,SUM(fd.total) total
				FROM facturacion f 
				INNER JOIN facturadetalle fd ON f.folio=fd.factura INNER JOIN guiasventanilla gv ON fd.folio=gv.id
				WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.tipoguia!='empresarial' AND f.cliente='".$_GET['cliente']."'
				) t1";
						$rx = mysql_query($s,$l) or die($s);
						$fx = mysql_fetch_object($rx);
						$f->nombre = cambio_texto($f->cliente);
						$f->clasificacioncliente = (($f->clasificacioncliente=="SELECCIONA" || 
						$f->clasificacioncliente=="selecciona")?"SELECCIONAR":$f->clasificacioncliente);					
	
						$s = "SELECT FORMAT(IFNULL(SUM(total),0),2) as ventames FROM pagoguias 
						WHERE cliente = ".$_GET[cliente]." AND MONTH(fechacreo) = MONTH(CURDATE()) AND fechacancelacion IS NULL";
						$rr = mysql_query($s,$l) or die($s);
						$fc = mysql_fetch_object($rr);
						
						$f->disponible = $fx->disponible;
						$f->ventames = $fc->ventames;
						$f->saldo = $fx->saldo;					
						$f->estado = cambio_texto($f->estado);
						$registros[] = $f;
	 		  }
			  echo str_replace('null','""',json_encode($registros));
			}else if($_GET[tipo]==2){
					while($f = mysql_fetch_object($r)){
						$f->nombre = cambio_texto($f->nombre);					
						$f->estado = cambio_texto($f->estado);
						$registros[] = $f;
					}
					echo str_replace('null','""',json_encode($registros));
			}
		}else{
			echo "no encontro";
		}
		if($_GET[tipo]==1){
			$s = "DROP TABLE tmp_convenio$x";
			mysql_query($s,$l) or die($s); 
		}
	}else if($_GET[accion] == 2){//OBTENER PROSPECTO
		$lqs=mysql_query("SELECT IFNULL(MAX(id),0)+1 as id FROM catalogoprospecto",$l);
		$rest=mysql_fetch_array($lqs);
		echo $rest[0];
	}else if($_GET[accion] == 3){
		$s = "SELECT id, CONCAT_WS(' ',nombre,paterno,materno) as cliente, rfc FROM catalogocliente
		WHERE rfc=UCASE('".trim($_GET[rfc])."') and id <> '$_GET[codigo]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->rfc = cambio_texto($f->rfc);
			$f->cliente = cambio_texto($f->cliente);
			echo str_replace('null','""',json_encode($f));
		}else{
			echo "no encontro";
		}		
	}else if($_GET[accion] == 4){
		$s = "SELECT id, CONCAT_WS(' ',nombre,paterno,materno) as cliente, rfc FROM catalogoprospecto
		WHERE rfc=UCASE('".trim($_GET[rfc])."')";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->rfc = cambio_texto($f->rfc);
			$f->cliente = cambio_texto($f->cliente);
			echo str_replace('null','""',json_encode($f));
		}else{
			echo "no encontro";
		}
	}
?>