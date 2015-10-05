<?
	session_start();
	require_once("../Conectar.php");
	require_once("../clases/ValidaConvenio.php");
	$l = Conectarse("webpmm");
	
	//solicitar datos evaluacion
	
	if($_GET[idpagina]==""){
		$_GET[idpagina]=$_SESSION[IDUSUARIO];
	}
	
	if($_GET[accion] == 1){
		$vc = new validaConvenio('','','','');
			
			$totalexcedente = 0;
			
			$s = "SELECT precioporcaja FROM generacionconvenio 
			WHERE folio='$_GET[idconvenio]' AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
			$r = mysql_query($s,$l) or die($s);
			
			$totalPeso = 0;
			$totalVolumen = 0;
			$cantidadMaxima = 0;
			
			$totalFlete = 0;
			
			$modificables = true;
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				if($f->precioporcaja==1){
					$s = "SELECT guia_temporaldetalle.*, IF(ISNULL(t1.descripcion), 'X','') AS modificable,
					guia_temporaldetalle.id as idmercancia
					FROM guia_temporaldetalle 
					LEFT JOIN 
						(
						 SELECT descripcion FROM cconvenio_configurador_caja
						WHERE idconvenio = $_GET[idconvenio] and tipo = 'CONVENIO'
						GROUP BY descripcion
						) AS t1 ON guia_temporaldetalle.descripcion = t1.descripcion
					WHERE guia_temporaldetalle.idusuario=$_GET[idpagina]";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
						if($f->modificable==''){
							$modificables = false;
						}
					}
					if($modificables && $_GET[idmercancia]=="")
						$precioporcaja = false;
					else
						$precioporcaja = true;
				}else
					$precioporcaja = false;
			}else{
				$precioporcaja = false;
			}
			if($_GET[idmercancia]!=""){
				$s = "select * from guia_temporaldetalle where id=$_GET[idmercancia]";
				$rn = mysql_query($s,$l) or die($s);
				while($fn = mysql_fetch_object($rn)){
					$kgse = "";
					$kgse = $vc->obtenerExcedente($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$_GET[descripcion]", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad);
					
					$res = "";
					$res = $vc->ObtenerFlete($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$_GET[descripcion]", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad);
					
					//echo $res."|";
					$res = split(",",$res);
					$s = "update guia_temporaldetalle
					set descripcion = '$_GET[descripcion]', contenido='$fn->contenido', 
					importe=$res[0], kgexcedente=$res[1], kgexcedente='$kgse' where id = $_GET[idmercancia]";
					//echo "<br>1<br>$s<br><br>";
					mysql_query($s,$l) or die($s);
				}
				$s = "select * from guia_temporaldetalle where idusuario=$_GET[idpagina]";
				
				$rn = mysql_query($s,$l) or die($s);
				while($fn = mysql_fetch_object($rn)){
					$res = "";
					$res = $vc->ObtenerFlete($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$fn->descripcion", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad);
					
					$res = split(",",$res);
					$totalexcedente += $res[1];
				}
			}elseif($_GET[idconvenio]!=-1){
				$s = "select * from guia_temporaldetalle where idusuario=$_GET[idpagina]";
				
				$rn = mysql_query($s,$l) or die($s);
				while($fn = mysql_fetch_object($rn)){
					$totalPeso += $fn->peso;
					$totalVolumen += $fn->volumen;
					$cantidadMaxima += $fn->cantidad;
					
					$kgse = "";
					$kgse = $vc->obtenerExcedente($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$fn->descripcion", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad);
					
					$res = "";
					$res = $vc->ObtenerFlete($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$fn->descripcion", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad);
					
					$res = split(",",$res);
					$s = "update guia_temporaldetalle
					set descripcion = '$fn->descripcion', contenido='$fn->contenido', 
					importe=$res[0], excedente=$res[1], kgexcedente=$kgse where id = $fn->id";
					mysql_query($s,$l) or die($s);
					//echo "<br>2<br>$s<br><br>";
					$totalexcedente += $res[1];
				}
				if(!$precioporcaja){
					$resf = $vc->ObtenerFlete($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "", 
													(($totalVolumen>$totalPeso)?$totalVolumen:$totalPeso), $cantidadMaxima);
					$resf = split(",",$resf);
					$totalFlete = $resf[0];
				}
			}else{
				$s = "delete from guia_temporaldetalle where idusuario = $_GET[idpagina]";
				mysql_query($s,$l) or die($s);
				
				$s = "SELECT NULL, em.cantidad, cd.descripcion, em.contenido, em.pesototal, em.volumen
				FROM evaluacionmercanciadetalle AS em
				INNER JOIN catalogodescripcion AS cd ON em.descripcion = cd.id
				WHERE em.evaluacion = '$_GET[fevaluacion]' and em.sucursal = ".$_GET[idsucorigen]."";
				$rn = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rn)==0){
					$s = "SELECT NULL, em.cantidad, em.descripcion, em.contenido, em.peso as pesototal, em.volumen
					FROM guiaventanilla_detalle em
					WHERE em.idguia = '$_GET[folioguia]'";
					$rn = mysql_query($s,$l) or die($s);
				}
				while($fn = mysql_fetch_object($rn)){
					$totalPeso += $fn->pesototal;
					$totalVolumen += $fn->volumen;
					$cantidadMaxima += $fn->cantidad;
					
					$res = $vc->ObtenerFlete(0, $_GET[idsucorigen], $_GET[idsucdestino], "$fn->descripcion", 
													(($fn->volumen>$fn->pesototal)?$fn->volumen:$fn->pesototal), $fn->cantidad);
					
					$res = split(",",$res);
					$s = "insert into guia_temporaldetalle
					set cantidad= $fn->cantidad, descripcion='$fn->descripcion', contenido='$fn->contenido', 
					peso=$fn->pesototal, volumen=$fn->volumen, importe='".$res[0]."', excedente='".$res[1]."', 
					kgexcedente=0,idusuario=$_GET[idpagina]";
					
					mysql_query($s,$l) or die($s);
				}
				if(!$precioporcaja){
					$resf = $vc->ObtenerFlete($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "", 
													(($totalVolumen>$totalPeso)?$totalVolumen:$totalPeso), $cantidadMaxima);
					$resf = split(",",$resf);
					$totalFlete = $resf[0];
				}
			}
			
			$s = "SELECT * FROM generacionconvenio WHERE folio = $_GET[idconvenio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			
			if($f->precioporcaja==1){			
				$s = "SELECT guia_temporaldetalle.*, IF(ISNULL(t1.descripcion), 'X','') AS modificable,
				guia_temporaldetalle.id as idmercancia
				FROM guia_temporaldetalle 
				LEFT JOIN 
					(
					 SELECT descripcion FROM cconvenio_configurador_caja
					WHERE idconvenio = $_GET[idconvenio] and tipo = 'CONVENIO'
					GROUP BY descripcion
					) AS t1 ON guia_temporaldetalle.descripcion = t1.descripcion
				WHERE guia_temporaldetalle.idusuario=$_GET[idpagina]";
			}else{
				$s = "SELECT guia_temporaldetalle.*, '' AS modificable,
				guia_temporaldetalle.id as idmercancia
				FROM guia_temporaldetalle 
				WHERE guia_temporaldetalle.idusuario=$_GET[idpagina]";
			}
			//echo $s;
			
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$arre = array();
				while($f = mysql_fetch_object($r)){
					$f->descripcion	= $vc->cambiartexto($f->descripcion);
					$f->contenido	= $vc->cambiartexto($f->contenido);
					$f->volumen		= round($f->volumen,2);
					if($totalFlete!=0 && $modificables){
						$f->importe = 0;
					}
					$arre[] = $f;
				}
				$mercancia = str_replace("null","''",json_encode($arre));
			}else{
				$mercancia = '""';
			}
				echo "[{
					   mercancia:$mercancia,
					   excedente:".(($totalexcedente=="")?"0":"$totalexcedente").",
					   kgsexcedente:".(($kgsexcedente=="")?"0":"$kgsexcedente").",
					   flete:$totalFlete
					   }]";
	}
	
	if($_GET[accion] == 2){
		$s = "SELECT (SELECT activado FROM catalogocliente WHERE id = '$_GET[idcliente]') activado,
		IFNULL((SELECT 'SI' FROM solicitudcredito WHERE cliente = '$_GET[idcliente]'),'NO') credito;";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if($f->credito=='NO'){
			die("NO TIENE CREDITO");
		}
		if($f->activado==''){
			die("CREDITO DESACTIVADO");
		}
		
		$s = "SELECT (SELECT montoautorizado 
		FROM solicitudcredito sc
		WHERE sc.cliente = '$_GET[idcliente]') 
		- SUM(adquirido) creditodisponible FROM (
				(SELECT IFNULL(SUM(IF(pagado='N', total,0)),0) AS adquirido
				FROM pagoguias 
				WHERE cliente = '$_GET[idcliente]' AND tipo <> 'EMPRESARIAL'
				AND credito = 'SI')
				UNION
				(SELECT IFNULL(SUM(IF(pg.pagado='N', pg.total,0)),0) AS adquirido
				FROM pagoguias pg
				INNER JOIN guiasempresariales ge ON pg.guia = ge.id
				WHERE pg.cliente = '$_GET[idcliente]' AND pg.tipo = 'EMPRESARIAL' AND ge.tipoguia <> 'PREPAGADA' 
				AND credito = 'SI')
		) t1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		if($f->creditodisponible=="")
			echo 0;
		else
			echo $f->creditodisponible;
	}
?>
