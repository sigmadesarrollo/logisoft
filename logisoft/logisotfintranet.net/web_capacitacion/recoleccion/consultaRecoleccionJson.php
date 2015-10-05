<? 	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	 if($_GET[accion]==1){//OBTENER SECTOR
		$s = "SELECT s.id, s.descripcion FROM catalogosector s
			INNER JOIN catalogosectordetalle d ON s.id=d.idsector
			WHERE d.colonia = '".$_GET['col']."' AND d.cp=".$_GET['cp'].""; 
			$registros = array();
			$r = mysql_query($s,$link) or die($s);
			if(mysql_num_rows($r)>0){
		 		while($f = mysql_fetch_object($r)){
					$registros[] = $f;
				}
				echo str_replace("null",'""',json_encode($registros));
			}else{
				echo str_replace("null",'""',json_encode(0));
			}	
			
	}else if($_GET[accion]==2){//OBTENER DETALLE
		$s = "SELECT idcliente, iddireccion, direccion, idsucursal, idorigen,
		origen, dia, iddestino, destino, sector, horario,
		horario2, hrcomida, hrcomida2
		FROM configurarrecoleccionesprogramadas WHERE idcliente=".$_GET[cliente]."";		
		$registros = array();
		$r = mysql_query($s,$link) or die($s);
		while($f = mysql_fetch_object($r)){
			$f->sucursal = cambio_texto($f->sucursal);
			$f->destino = cambio_texto($f->destino);
			$f->sector = cambio_texto($f->sector);
			$registros[] = $f;
		}
		echo str_replace("null",'""',json_encode($registros));
		
	}else if($_GET[accion]==3){//OBTENER POBLACION
		$s = "SELECT id, poblacion FROM direccion WHERE origen='suc' AND codigo=".$_GET[sucursal]."";	
		$r = mysql_query($s,$link) or die(mysql_error($link).$s); $f = mysql_fetch_object($r);
		
		$s = "SELECT id, CONCAT(calle,' #',numero,' COL. ',colonia) AS calle, facturacion, poblacion FROM direccion WHERE origen='cl' AND codigo=".$_GET[cliente]." AND poblacion='".$f->poblacion."' ";		
		$sq = mysql_query($s,$link) or die(mysql_error($link).$s);
		
		$registros = array();
		if(mysql_num_rows($sq)>0){

			while($sql = mysql_fetch_object($sq)){
				if($f->facturacion=="SI"){
					$registros[] = $sql;
					echo str_replace("null",'""',json_encode($registros));
					return true;
				}else{
					$registros[] = $sql;
					echo str_replace("null",'""',json_encode($registros));
					return true;
				}				
			}
			
		}else{

			$s = "SELECT id, CONCAT(calle,' #',numero,' COL. ',colonia) AS calle, facturacion, poblacion
			FROM direccion WHERE origen='cl' AND codigo=".$_GET[cliente]."";
			$fa = mysql_query($s,$link) or die(mysql_error($link).$s);
						
			while($fac = mysql_fetch_object($fa)){
				if($fac->facturacion=="SI"){
					$registros[] = $fac;
					echo str_replace("null",'""',json_encode($registros));
					return true;
				}else{
					$registros[] = $fac;
					echo str_replace("null",'""',json_encode($registros));
					return true;
				}						
			}
		}
		
	}else if($_GET[accion]==4){//OBTENER DIAS RECOLECCION
		$s = "SELECT todasemana, lunes, martes, miercoles, jueves, viernes, sabado,sucursal 
		FROM catalogodestino WHERE id=".$_GET[destino];
		$r = mysql_query($s,$link) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			echo str_replace('null','""',json_encode($f));
		}else{
			echo "no encontro";
		}
	}
?>