<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){//FACTURACION
		$s = "SELECT origen, guia, cliente, destino, fecha, tipoguia, estado FROM 
		(SELECT cs.prefijo AS origen, gv.id AS guia, de.prefijo AS destino,
		DATE_FORMAT(gv.fecha_registro,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		'NORMAL' AS tipoguia, gv.estado
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal de ON gv.idsucursaldestino = de.id
		INNER JOIN catalogocliente cc ON IF(gv.idsucursalorigen<>'".$_SESSION[IDSUCURSAL]."',gv.iddestinatario,gv.idremitente)=cc.id
		UNION
		SELECT cs.prefijo AS origen, ge.id AS guia, de.prefijo AS destino,
		DATE_FORMAT(ge.fecha_registro,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		ge.tipoguia, ge.estado
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal de ON ge.idsucursaldestino = de.id
		INNER JOIN catalogocliente cc ON IF(ge.idsucursalorigen<>'".$_SESSION[IDSUCURSAL]."',ge.iddestinatario,ge.idremitente)=cc.id) t";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($f));
		}else{
			echo "0";
		}
					
	}else if($_GET[accion]==2){//GUIAS POR RECIBIR
	
	}else if($_GET[accion]==3){
	
	}else if($_GET[accion]==4){

	}else if($_GET[accion]==5){
	
	}else if($_GET[accion]==6){
	
	}else if($_GET[accion]==7){
	
	}else if($_GET[accion]==8){
	
	}
?>