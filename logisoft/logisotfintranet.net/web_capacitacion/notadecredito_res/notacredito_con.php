<?
	 session_start();
	require_once("../Conectar.php");
	$l=Conectarse('webpmm');
	
if ($_GET[accion]==1){
		$arr = split(",",$_GET[arre]);
		$s = "SELECT CURRENT_TIMESTAMP() AS fecha";
		$ds= mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($ds);
		
		$sq = "INSERT INTO notacreditodetalle_tmp
			(cantidad,unidad,descripcion,precio,importe,usuario,fecha,sucursal)
			VALUES 
			(".$arr[0].", '".$arr[1]."','".$arr[2]."', ".$arr[3].", ".$arr[4].",  
			 ".$_SESSION[IDUSUARIO].",'".$f->fecha."',".$_SESSION[IDSUCURSAL].")";
			$t=mysql_query($sq,$l)or die($sq); 
		
		
		
		$sql="SELECT id,cantidad,unidad,descripcion,precio,importe 
		FROM notacreditodetalle_tmp WHERE usuario=".$_SESSION[IDUSUARIO]."";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
			while ($f=mysql_fetch_object($r))
			{
			$f->descripcion=cambio_texto($f->descripcion);
			$registros[]=$f;	
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}

}else if($_GET[accion]==2){	
		$sql="SELECT cc.id,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
		CONCAT(d.calle,' ',d.numero,'',d.colonia,' ',d.cp)AS direccion,cc.rfc,d.poblacion AS ciudad,d.estado 
		FROM catalogocliente cc
		INNER JOIN direccion d ON cc.id=d.codigo AND d.origen='cl'
		WHERE cc.id='".$_GET[cliente]."' AND d.facturacion = 'SI'";
		$r=mysql_query($sql,$l)or die($sql); 
				$registros= array();
		
				if (mysql_num_rows($r)>0){
						while ($f=mysql_fetch_object($r))
						{
						$f->cliente=cambio_texto($f->cliente);
						$f->direccion=cambio_texto($f->direccion);
						$f->ciudad=cambio_texto($f->ciudad);
						$f->estado=cambio_texto($f->estado);
						$registros[]=$f;	
						}
						echo str_replace('null','""',json_encode($registros));
				}else{
					echo str_replace('null','""',json_encode(0));
				}
	}else if($_GET[accion]==3){
					
				$sql="SELECT guia,id,cliente, direccion,rfc,ciudad,estado FROM (
	SELECT gv.id AS guia,cc.id,CONCAT(cc.nombre,'',cc.paterno,'',cc.materno)AS cliente,
	CONCAT(d.calle,' ',d.numero,'',d.colonia,' ',d.cp)AS direccion,cc.rfc,d.municipio AS ciudad,d.estado FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON cs.id   = IF (gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN direccion d ON cc.id=d.codigo AND d.origen='cl' 
UNION ALL
	SELECT gv.id AS guia,cc.id,CONCAT(cc.nombre,'',cc.paterno,'',cc.materno)AS cliente,
	CONCAT(d.calle,' ',d.numero,'',d.colonia,' ',d.cp)AS direccion,cc.rfc,d.municipio AS ciudad,d.estado FROM guiasempresariales gv
	INNER JOIN catalogosucursal cs ON cs.id   = IF (gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN direccion d ON cc.id=d.codigo AND d.origen='cl' 
)guias  WHERE guia='".$_GET[guia]."'";
				$r=mysql_query($sql,$l)or die($sql); 
				$registros= array();
		
				if (mysql_num_rows($r)>0){
						while ($f=mysql_fetch_object($r))
						{
						$f->cliente=cambio_texto($f->cliente);
						$f->direccion=cambio_texto($f->direccion);
						$registros[]=$f;	
						}
						echo str_replace('null','""',json_encode($registros));
				}else{
					echo str_replace('null','""',json_encode(0));
				}
		
	
	}else if($_GET[accion]==4){
				$s = "DELETE FROM notacreditodetalle_tmp WHERE usuario=".$_SESSION[IDUSUARIO]."";
				mysql_query($s,$l)or die($s); 			

				$row = ObtenerFolio('notacredito','webpmm');
				$folio = $row[0];
				
				$s = "SELECT (iva/100) AS iva FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."";
				$r = mysql_query($s,$l)or die($s); 
				$f = mysql_fetch_object($r);
				
				echo $folio.",".$f->iva;
				
	}else if ($_GET[accion]==5){	
			$sql = "SELECT n.folio, n.guia,DATE_FORMAT(n.fechanotacredito,'%d/%m/%Y')AS fecha,
			n.cliente,n.concepto,n.impuestoporc AS impuesto,
			CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS nombre,
			CONCAT(d.calle,' ',d.numero,'',d.colonia,' ',d.cp)AS direccion,cc.rfc,d.poblacion AS ciudad,d.estado
			FROM notacredito n
			INNER JOIN catalogocliente cc ON n.cliente = cc.id
			INNER JOIN direccion d ON cc.id=d.codigo AND d.origen = 'cl'
			WHERE n.folio=".$_GET[folio]." AND d.facturacion = 'SI'";	
			$r = mysql_query($sql,$l)or die($sql);
			if(mysql_num_rows($r)>0){
				$f->nombre = cambio_texto($f->nombre);
				$f->direccion = cambio_texto($f->direccion);
				$f->rfc = cambio_texto($f->rfc);
				$f->ciudad = cambio_texto($f->ciudad);
				$f->estado = cambio_texto($f->estado);
				$f->concepto = cambio_texto($f->concepto);
				$f = mysql_fetch_object($r);
				
				$principal = str_replace('null','""',json_encode($f));
				
				$sql = "SELECT cantidad,unidad,descripcion,precio,importe FROM notacreditodetalle 
				WHERE folionotacredito=".$_GET[folio]."";			
				$r = mysql_query($sql,$l)or die($sql); 
				$registros= array();
					while($f = mysql_fetch_object($r)){
						$f->descripcion = cambio_texto($f->descripcion);
						$registros[] = $f;
					}
				$detalle = str_replace('null','""',json_encode($registros));
				
				echo "({principal:$principal,detalle:$detalle})";
			}else{
				echo "no encontro";
			}
				
	}else if ($_GET[accion]==6){
	
					$sql = "SELECT id,cantidad,unidad,descripcion,precio,importe FROM notacreditodetalle WHERE folionotacredito=".$_GET[folio]."";	
			$r=mysql_query($sql,$l)or die($sql); 
				$registros= array();
				
				if (mysql_num_rows($r)>0){
						while ($f=mysql_fetch_object($r))
						{
						//$f->sucursal=cambio_texto($f->sucursal);
						$registros[]=$f;	
						}
						echo str_replace('null','""',json_encode($registros));
				}else{
						echo str_replace('null','""',json_encode(0));
				}
	
	}else if ($_GET[accion]==7){
	
		$sql = "DELETE FROM notacreditodetalle_tmp WHERE id=".$_GET[id]." and usuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($sql,$l)or die($sql); 
		
		echo "ok";
	}else if ($_GET[accion]==8){
	
			$sq = "SELECT SUM(importe)AS importe FROM notacreditodetalle_tmp WHERE usuario=".$_SESSION[IDUSUARIO]."";	
			$d=mysql_query($sq,$l)or die($sq); 
			$registross= array();
			
			if (mysql_num_rows($d)>0){
				while ($f=mysql_fetch_object($d))
				{
				//$f->cliente=cambio_texto($f->cliente);
				$registross[]=$f;	
				}
				echo str_replace('null','""',json_encode($registross));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	
	}else if ($_GET[accion]==9){
	
			$sq = "SELECT (iva/100)AS iva FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."";	
			$d=mysql_query($sq,$l)or die($sq); 
			$registros= array();
			
			if (mysql_num_rows($d)>0){
				while ($f=mysql_fetch_object($d))
				{
				$registros[]=$f;	
				}
				echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	
	}else if ($_GET[accion]==10){
	
		$sq = "SELECT nombre AS empleado FROM catalogoempleado WHERE id=".$_GET[idempleado]."";	
			$d=mysql_query($sq,$l)or die($sq); 
			$registros= array();
			
			if (mysql_num_rows($d)>0){
				while ($f=mysql_fetch_object($d))
				{
				$f->empleado=cambio_texto($f->empleado);
				$registros[]=$f;	
				}
				echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	
	}
	
?>