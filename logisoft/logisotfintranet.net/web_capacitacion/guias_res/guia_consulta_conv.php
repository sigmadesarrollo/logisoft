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
				$s = "select * from guia_temporaldetalle where idusuario=$_SESSION[IDUSUARIO]";
				
				$rn = mysql_query($s,$l) or die($s);
				while($fn = mysql_fetch_object($rn)){
					$res = "";
					$res = $vc->ObtenerFlete($_GET[idconvenio], $_GET[idsucorigen], $_GET[idsucdestino], "$fn->descripcion", 
													(($fn->volumen>$fn->peso)?$fn->volumen:$fn->peso), $fn->cantidad);
					
					$res = split(",",$res);
					$totalexcedente += $res[1];
				}
			}elseif($_GET[idconvenio]!=-1){
				$s = "select * from guia_temporaldetalle where idusuario=$_SESSION[IDUSUARIO]";
				
				$rn = mysql_query($s,$l) or die($s);
				while($fn = mysql_fetch_object($rn)){
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
			}else{
				$s = "delete from guia_temporaldetalle where idusuario = $_SESSION[IDUSUARIO]";
				mysql_query($s,$l) or die($s);
				
				$s = "SELECT NULL, em.cantidad, cd.descripcion, em.contenido, em.pesototal, em.volumen
				FROM evaluacionmercanciadetalle AS em
				INNER JOIN catalogodescripcion AS cd ON em.descripcion = cd.id
				WHERE em.evaluacion = '$_GET[fevaluacion]'";
				
				$rn = mysql_query($s,$l) or die($s);
				$tpesokg = 0;
				$tpesovo = 0;
				$tenvase = 0;
				$totalimporte = 0;
				$tproductos = 0;
				while($fn = mysql_fetch_object($rn)){
					
					$res = $vc->ObtenerFlete(0, $_GET[idsucorigen], $_GET[idsucdestino], "$fn->descripcion", 
													(($fn->volumen>$fn->pesototal)?$fn->volumen:$fn->pesototal), $fn->cantidad);
					
					$res = split(",",$res);
					$s = "insert into guia_temporaldetalle
					set cantidad= $fn->cantidad, descripcion='$fn->descripcion', contenido='$fn->contenido', 
					peso=$fn->pesototal, volumen=$fn->volumen, importe='".$res[0]."', excedente='".$res[1]."', 
					kgexcedente=0,idusuario=$_SESSION[IDUSUARIO]";
					
					mysql_query($s,$l) or die($s);
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
					WHERE idconvenio = $_GET[idconvenio]
					GROUP BY descripcion
					) AS t1 ON guia_temporaldetalle.descripcion = t1.descripcion
				WHERE guia_temporaldetalle.idusuario=$_SESSION[IDUSUARIO]";
			}else{
				$s = "SELECT guia_temporaldetalle.*, '' AS modificable,
				guia_temporaldetalle.id as idmercancia
				FROM guia_temporaldetalle 
				WHERE guia_temporaldetalle.idusuario=$_SESSION[IDUSUARIO]";
			}
			//echo $s;
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$arre = array();
				while($f = mysql_fetch_object($r)){
					$f->descripcion	= $vc->cambiartexto($f->descripcion);
					$f->contenido	= $vc->cambiartexto($f->contenido);
					$f->volumen		= round($f->volumen,2);
					$arre[] = $f;
				}
				$mercancia = str_replace("null","''",json_encode($arre));
			}else{
				$mercancia = '""';
			}
				echo "[{
					   mercancia:$mercancia,
					   excedente:".(($totalexcedente=="")?"0":"$totalexcedente").",
					   kgsexcedente:".(($kgsexcedente=="")?"0":"$kgsexcedente")."
					   }]";
	}
	
	if($_GET[accion] == 2){
		$s = "SELECT sc.montoautorizado - IFNULL(SUM(pg.total),0) as creditodisponible
		FROM solicitudcredito AS sc
		LEFT JOIN pagoguias AS pg ON sc.cliente = pg.cliente AND pg.pagado = 'N'
		LEFT JOIN catalogocliente as cc on sc.cliente = cc.id
		WHERE sc.estado = 'ACTIVADO' AND sc.cliente = $_GET[idcliente] and cc.activado='SI'";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if($f->creditodisponible=="")
			echo 0;
		else
			echo $f->creditodisponible;
	}
?>
