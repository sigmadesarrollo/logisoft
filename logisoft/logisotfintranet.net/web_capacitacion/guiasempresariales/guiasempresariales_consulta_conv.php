<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	require_once("../clases/ValidaConvenio.php");
	$l = Conectarse("webpmm");
	
	//solicitar datos evaluacion
	
	if($_GET[accion] == 1){
		$vc = new validaConvenio('','','','');
			
			$totalexcedente = 0;
			
			$s = "SELECT prepagada FROM solicitudguiasempresariales WHERE '$_GET[folioempresarial]' BETWEEN desdefolio AND hastafolio";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$prepagada = $f->prepagada;
			
			$s = "SELECT consignacioncaja FROM generacionconvenio 
			WHERE folio='$_GET[idconvenio]' AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
			$r = mysql_query($s,$l) or die($s);
			
			$totalPeso = 0;
			$totalVolumen = 0;
			$cantidadMaxima = 0;
			
			$totalFlete = 0;
			
			$modificables = true;
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				if($f->consignacioncaja==1){
					$s = "SELECT guiasempresariales_temporaldetalle.*, IF(ISNULL(t1.descripcion), 'X','') AS modificable,
					guiasempresariales_temporaldetalle.id as idmercancia
					FROM guiasempresariales_temporaldetalle 
					LEFT JOIN 
						(
						 SELECT descripcion FROM cconvenio_configurador_caja
						WHERE idconvenio = $_GET[idconvenio] and tipo = 'CONSIGNACION'
						GROUP BY descripcion
						) AS t1 ON guiasempresariales_temporaldetalle.descripcion = t1.descripcion
					WHERE guiasempresariales_temporaldetalle.idusuario=$_GET[idpagina]";
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
				$s = "select * from guiasempresariales_temporaldetalle where id=$_GET[idmercancia]";
				
				$rn = mysql_query($s,$l) or die($s);
				while($fn = mysql_fetch_object($rn)){
					$kgse = "";
					$kgse = $vc->obtenerExcedenteEmp($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$_GET[descripcion]", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad, "$_GET[folioempresarial]");
					
					$res = "";
					$res = $vc->ObtenerFleteEmp($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$_GET[descripcion]", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad, "$_GET[folioempresarial]");
					
					//echo $res."|";
					$res = split(",",$res);
					$s = "update guiasempresariales_temporaldetalle
					set descripcion = '$_GET[descripcion]', contenido='$fn->contenido', 
					importe=$res[0], excedente=$res[1], kgexcedente='$kgse' where id = $_GET[idmercancia]";
					//echo "<br>1<br>$s<br><br>";
					mysql_query($s,$l) or die($s);
					
					
				}
				$s = "select * from guiasempresariales_temporaldetalle where idusuario=$_GET[idpagina]";
				
				$rn = mysql_query($s,$l) or die($s);
				while($fn = mysql_fetch_object($rn)){
					$res = "";
					
					$res = $vc->ObtenerFleteEmp($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$fn->descripcion", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad, "$_GET[folioempresarial]");
					
					$res = split(",",$res);
					$totalexcedente += $res[1];
				}
			}elseif($_GET[idconvenio]!=-1){
				$s = "select * from guiasempresariales_temporaldetalle where idusuario=$_GET[idpagina]";
				
				$rn = mysql_query($s,$l) or die($s);
				while($fn = mysql_fetch_object($rn)){
					$totalPeso += $fn->peso;
					$totalVolumen += $fn->volumen;
					$cantidadMaxima += $fn->cantidad;
					
					$kgse = "";
					$kgse = $vc->obtenerExcedenteEmp($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$fn->descripcion", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad, "$_GET[folioempresarial]");
					//echo $kgse;
					$res = "";
					$res = $vc->ObtenerFleteEmp($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$fn->descripcion", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad, "$_GET[folioempresarial]");
					//echo $res;
					$res = split(",",$res);
					$s = "update guiasempresariales_temporaldetalle
					set descripcion = '$fn->descripcion', contenido='$fn->contenido', 
					importe=$res[0], excedente=$res[1], kgexcedente='$kgse' where id = $fn->id";
					mysql_query($s,$l) or die($s);
					//echo "<br>2<br>$s<br><br>";
					$totalexcedente += $res[1];
				}
				if(!$precioporcaja){
					$resf = $vc->ObtenerFleteEmp($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "", 
													(($totalVolumen>$totalPeso)?$totalVolumen:$totalPeso), $cantidadMaxima, "$_GET[folioempresarial]");
					$resf = split(",",$resf);
					$totalFlete = $resf[0];
				}
			}else{
				$s = "delete from guiasempresariales_temporaldetalle where idusuario = $_GET[idpagina]";
				mysql_query($s,$l) or die($s);
				
				$s = "SELECT NULL, em.cantidad, cd.descripcion, em.contenido, em.pesototal, em.volumen
				FROM evaluacionmercanciadetalle AS em
				INNER JOIN catalogodescripcion AS cd ON em.descripcion = cd.id
				WHERE em.evaluacion = '$_GET[fevaluacion]' and em.sucursal = ".$_SESSION[IDSUCURSAL]."";
				
				$rn = mysql_query($s,$l) or die($s);
				$tpesokg = 0;
				$tpesovo = 0;
				$tenvase = 0;
				$totalimporte = 0;
				$tproductos = 0;
				while($fn = mysql_fetch_object($rn)){
					$totalPeso += $fn->pesototal;
					$totalVolumen += $fn->volumen;
					$cantidadMaxima += $fn->cantidad;
					
					$res = $vc->ObtenerFleteEmp(0, $_GET[idsucorigen], $_GET[idsucdestino], "$fn->descripcion", 
													(($fn->volumen>$fn->pesototal)?$fn->volumen:$fn->pesototal), $fn->cantidad, "$_GET[folioempresarial]");
					
					$res = split(",",$res);
					$s = "insert into guiasempresariales_temporaldetalle
					select null, $fn->cantidad, '$fn->descripcion', '$fn->contenido', 
					'$fn->pesototal', '$fn->largo', '$fn->ancho', '$fn->alto', 
					'$fn->volumen', '".$res[0]."', '".$res[1]."',0 ,$_GET[idpagina]";
					
					mysql_query($s,$l) or die($s);
				}
				if(!$precioporcaja){
					$resf = $vc->ObtenerFleteEmp($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "", 
													(($totalVolumen>$totalPeso)?$totalVolumen:$totalPeso), $cantidadMaxima, "$_GET[folioempresarial]");
					$resf = split(",",$resf);
					$totalFlete = $resf[0];
				}
			}
			
			$s = "SELECT * FROM generacionconvenio WHERE folio = $_GET[idconvenio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			
			if($f->consignacioncaja==1){			
				$s = "SELECT guiasempresariales_temporaldetalle.*, IF(ISNULL(t1.descripcion), 'X','') AS modificable,
				guiasempresariales_temporaldetalle.id as idmercancia
				FROM guiasempresariales_temporaldetalle 
				LEFT JOIN 
					(
					 SELECT descripcion FROM cconvenio_configurador_caja
					WHERE idconvenio = $_GET[idconvenio] and tipo = 'CONSIGNACION'
					GROUP BY descripcion
					) AS t1 ON guiasempresariales_temporaldetalle.descripcion = t1.descripcion
				WHERE guiasempresariales_temporaldetalle.idusuario=$_GET[idpagina]";
			}else{
				$s = "SELECT guiasempresariales_temporaldetalle.*, '' AS modificable,
				guiasempresariales_temporaldetalle.id as idmercancia
				FROM guiasempresariales_temporaldetalle 
				WHERE guiasempresariales_temporaldetalle.idusuario=$_GET[idpagina]";
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
					   flete:$totalFlete}]";
	}
	
?>
