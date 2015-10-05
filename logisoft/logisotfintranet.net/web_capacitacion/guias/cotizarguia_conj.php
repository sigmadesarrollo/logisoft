<? 	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}	
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");	
	
	if($_GET[accion]==1){//OBTENER CLIENTE
		$s = "SELECT c.nombre, c.paterno, c.materno, IFNULL(g.folio,0) AS folioconvenio, c.email FROM catalogocliente c
		LEFT JOIN generacionconvenio g ON c.id=g.idcliente
		WHERE c.id=".$_GET[cliente]."";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
			echo str_replace("null",'""',json_encode($registros));
		}else{
			echo str_replace("null",'""',json_encode(0));
		}		
	}else if($_GET[accion]==2){ //OBTENER DISTANCIA
		$s ="SELECT distancia FROM catalogodistancias WHERE (idorigen=".$_GET[idorigen]." AND iddestino=".$_GET[iddestino].") OR (iddestino=".$_GET[idorigen]." AND idorigen=".$_GET[iddestino].")";
		$r = mysql_query($s,$l) or die($s);
		$row = mysql_fetch_array($r);
		$valor = $row[0];
		$s = "SELECT kmi, kmf, valor FROM cconvenio_configurador_preciokg WHERE idconvenio=4";	
		$rr = mysql_query($s,$l) or die($s);
		$rowr = mysql_fetch_array($rr);
		$cant = mysql_num_rows($rr);
			for($i=0;$i<$cant;$i++){
				for($k=$rowr[0];$k<=$rowr[1];$k++){
					if($valor == $k){echo $k.")SI <br>";}else{echo $k.")NO <br>";}
				}
			}
			
	}else if($_GET[accion]==3){//OBTENER SUCURSAL
		$s = "SELECT descripcion FROM catalogosucursal WHERE id=".$_GET[sucursal]."";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$registros[] = $f;
		}
		echo str_replace("null",'""',json_encode($registros));
		
	}else if($_GET[accion]==4){//OBTENER DATOS PRINCIPALES COTIZACION GUIA	 
	  	$s = "SELECT '001' AS folio, DATE_FORMAT(CURRENT_DATE , '%d/%m/%Y') as fecha, (SELECT descripcion FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL].") as origen";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);	
	 	while($f = mysql_fetch_object($r)){
			$registros[] = $f;
		}
		echo str_replace("null",'""',json_encode($registros));
	}
?>
