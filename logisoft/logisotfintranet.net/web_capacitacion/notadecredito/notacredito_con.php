<?
	 session_start();
	require_once("../clases/FacturacionElectronica.php");
	require_once("../Conectar.php");
	$l=Conectarse('webpmm');
	
if ($_GET[accion]==1){
		$arr = split(",",$_GET[arre]);
		
		
		
		
		$s = "SELECT CURRENT_TIMESTAMP() AS fecha";
		$ds= mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($ds);
		
		$sq = "INSERT INTO notacreditodetalle_tmp
			(cantidad,unidad,descripcion,precio,importe,usuario,fecha )
			VALUES 
			(".$arr[0].", '".$arr[1]."','".$arr[2]."', ".$arr[3].", ".$arr[4].",  
			 ".$_SESSION[IDUSUARIO].",'".$f->fecha."')";
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
		d.calle,d.numero,d.colonia,d.cp,cc.rfc, cc.personamoral,
		d.poblacion, d.poblacion AS ciudad, d.municipio,d.estado, d.id iddireccion
		FROM catalogocliente cc
		LEFT JOIN direccion d ON  cc.id=d.codigo AND d.origen='cl' AND facturacion = 'SI'
		WHERE cc.id='".$_GET[cliente]."' GROUP BY cc.id";
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
					//echo "[{'cliente':''}]";
				}
	}else if($_GET[accion]==3){
					
				$sql="SELECT guia,id,cliente, direccion,rfc,ciudad,estado FROM (
	SELECT gv.id AS guia,cc.id,CONCAT(cc.nombre,'',cc.paterno,'',cc.materno)AS cliente,
	CONCAT(d.calle,' ',d.numero,'',d.colonia,' ',d.cp)AS direccion,cc.rfc,d.municipio AS ciudad,d.estado FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON cs.id   = IF (gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN direccion d ON cc.id=d.codigo AND d.origen='cl' 
	WHERE gv.id='".$_GET[guia]."'
UNION ALL
	SELECT gv.id AS guia,cc.id,CONCAT(cc.nombre,'',cc.paterno,'',cc.materno)AS cliente,
	CONCAT(d.calle,' ',d.numero,'',d.colonia,' ',d.cp)AS direccion,cc.rfc,d.municipio AS ciudad,d.estado FROM guiasempresariales gv
	INNER JOIN catalogosucursal cs ON cs.id   = IF (gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN direccion d ON cc.id=d.codigo AND d.origen='cl' 
	WHERE gv.id='".$_GET[guia]."'
)guias ";
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
				$sql="DELETE FROM notacreditodetalle_tmp WHERE usuario=".$_SESSION[IDUSUARIO]."";
				$r=mysql_query($sql,$l)or die($sql); 
				$registros= array();
		
				if (mysql_num_rows($r)>0){
						while ($f=mysql_fetch_object($r))
						{
						$registros[]=$f;	
						}
						echo str_replace('null','""',json_encode($registros));
				}else{
					echo str_replace('null','""',json_encode(0));
				}
	
	}else if ($_GET[accion]==5){
	
			$sql = "SELECT nc.folio, nc.nombrecliente, nc.guia,DATE_FORMAT(nc.fechanotacredito,'%d/%m/%Y')AS fecha, nc.cliente, nc.concepto, 
			impuestoporc AS impuesto, fo.nombre AS formulo, nc.formulo idformulo, subtotal, iva, ivaretenido, total,
			nc.calle, nc.numero, nc.colonia, nc.cp, nc.poblacion, nc.municipio, nc.rfc, nc.estado, 'MEXICO' pais
			FROM notacredito nc
			INNER JOIN catalogoempleado fo ON nc.formulo = fo.id
			WHERE folio=".$_GET[folio]."";	
			$r=mysql_query($sql,$l)or die($sql); 
				$registros= array();
		
				if (mysql_num_rows($r)>0){
						while ($f=mysql_fetch_object($r)){
							//$f->sucursal=cambio_texto($f->sucursal);
							$registros[]=$f;
						}
						echo str_replace('null','""',json_encode($registros));
				}else{
						echo str_replace('null','""',json_encode(0));
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
	
		$sq = "SELECT CONCAT_WS(' ',nombre, apellidopaterno, apellidomaterno) AS empleado 
		FROM catalogoempleado WHERE id=".$_GET[idempleado]."";	
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
	
	}else if($_GET[accion]==11){
		$s = "SELECT IFNULL(MAX(folio),0)+1 folio FROM notacredito";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "({'folio':'$f->folio'})";
	}else if($_GET[accion]==12){
		if($_GET[tipo]=='factura'){
			$s = "SELECT * FROM facturacion WHERE folio = '".$_GET[folio]."' AND cliente = '".$_GET[idcliente]."'";
		}else{
			if(substr($_GET[folio],0,3)=='999'){
				$s = "SELECT * FROM guiasempresariales WHERE id = '".$_GET[folio]."'
				AND ((idremitente = '".$_GET[idcliente]."' AND tipoflete = 'PAGADA') OR (iddestinatario = '".$_GET[idcliente]."' AND tipoflete = 'POR COBRAR'));";
			}else{
				$s = "SELECT * FROM guiasventanilla WHERE id = '".$_GET[folio]."'
				AND ((idremitente = '".$_GET[idcliente]."' AND tipoflete = 0) OR (iddestinatario = '".$_GET[idcliente]."' AND tipoflete = 1));";
			}
		}
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)){
			if($_GET[tipo]=='factura'){
				echo "F Aceptada";
			}else{
				echo "G Aceptada";
			}
		}else{
			if($_GET[tipo]=='factura'){
				echo "F No Aceptada";
			}else{
				echo "G No Aceptada";
			}
		}
	}
	
?>