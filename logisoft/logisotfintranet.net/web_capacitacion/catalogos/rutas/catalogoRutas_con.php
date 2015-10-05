<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');

	if($_GET[accion] == 1){
		$row = folio("catalogoruta","webpmm");
		$s = "DELETE FROM catalogorutadetalletmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		$s = "DELETE FROM catalogorutadetallesucursaltmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		echo $row[0];
		
	}else if($_GET[accion] == 2){
		$s = "SELECT descripcion FROM catalogotipounidad WHERE id=".$_GET[unidad];
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->descripcion = cambio_texto($f->descripcion);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion] == 3){
		$s = "SELECT prefijo FROM catalogosucursal WHERE id=".$_GET[sucursal]."";
		$r = mysql_query($s,$l) or die(mysql_error($l).$s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->prefijo = cambio_texto($f->prefijo);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}	

	}else if($_GET[accion] == 4){
		if($_GET['hidensucursal2']=="TODAS"){
			$sucursalestransbordo = $_GET['hidensucursal2'];
		}else{
			$sucursalestransbordo=substr($_GET['hidensucursal2'],0,-1);	
		}
		$s = "INSERT INTO catalogorutadetalletmp
		(tipo, diasalidas, sucursal, horasllegada, tiempodescarga,
		tiempocarga, horasalida, trayectosucursal,transbordo,sucursalestransbordo,
		idusuario,usuario, fecha)
		VALUES 
		('".$_GET['tipo']."',UCASE('".$_GET['semana']."'),'".$_GET['sucursal']."', '".$_GET['llegada']."',
		'".$_GET['descarga']."', '".$_GET['carga']."', '".$_GET['salida']."',
		'".$_GET['ttss']."','".$_GET['transbordo']."',UCASE('$sucursalestransbordo'),
		".$_SESSION['IDUSUARIO'].", '".$_SESSION[NOMBREUSUARIO]."', '".$_GET[fecha]."')";
		mysql_query($s,$l) or die($s);	

		echo "ok";
	}else if($_GET[accion] == 5){
		$s = "DELETE FROM catalogorutadetalletmp
		WHERE idusuario=".$_SESSION['IDUSUARIO']." AND sucursal='".$_GET[idsucursal]."'";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	}else if($_GET[accion] == 6){
		if($_GET['hidensucursal2']=="TODAS"){
			$sucursalestransbordo = $_GET['hidensucursal2'];
		}else{
			$sucursalestransbordo=substr($_GET['hidensucursal2'],0,-1);	
		}
		$s = "UPDATE catalogorutadetalletmp SET tipo='".$_GET['tipo']."', diasalidas='".$_GET['semana']."',
		sucursal='".$_GET['sucursal']."', horasllegada='".$_GET['llegada']."',
		tiempodescarga='".$_GET['descarga']."',	tiempocarga='".$_GET['carga']."',
		horasalida='".$_GET['salida']."', trayectosucursal='".$_GET['ttss']."',
		transbordo='".$_GET['transbordo']."',sucursalestransbordo='$sucursalestransbordo',
		idusuario=".$_SESSION['IDUSUARIO'].", usuario='".$_SESSION[NOMBREUSUARIO]."',
		fecha='".$_GET[fecha]."' WHERE idusuario=".$_SESSION['IDUSUARIO']." AND fecha='".$_GET[fecha]."' /*sucursal='".$_GET[sucursal]."'*/";
		//die($s);
		mysql_query($s,$l) or die($s);
		echo "ok";

	}else if($_GET[accion] == 7){

		$row = split(",",$_GET[arre]);

		if($_GET[tipo]=="guardar"){
			$s = "INSERT INTO catalogoruta
			(descripcion,recorrido,km,idtipounidad,tipounidad,enuso,usuario,fecha)
			VALUES
			(UCASE('".$row[0]."'),'".$row[1]."','".$row[2]."',".$row[3].",
			UCASE('".$row[4]."'),0,'".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP)";
			mysql_query($s,$l) or die($s);
			$codigo = mysql_insert_id();

			$s = "INSERT INTO catalogorutadetalle
			SELECT 0 AS id, ".$codigo." AS ruta, tipo, diasalidas, sucursal, horasllegada,
			tiempodescarga, tiempocarga, horasalida, trayectosucursal, transbordo,
			sucursalestransbordo, idusuario, usuario, fecha
			FROM catalogorutadetalletmp
			WHERE idusuario=".$_SESSION[IDUSUARIO]."
			 ORDER BY fecha ASC";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO catalogorutadetallesucursal
			SELECT 0 as id, '".$codigo."', idsucursal, sucursal, ".$_SESSION[IDUSUARIO].", fecha 
			FROM catalogorutadetallesucursaltmp
			WHERE idusuario =".$_SESSION[IDUSUARIO]."";			
			$r = mysql_query($s,$l) or die($s);
			
			/*$s = "SELECT * FROM catalogorutadetallesucursal WHERE idusuario=".$_SESSION[IDUSUARIO]." AND idruta=0";

			$e = mysql_query($s,$l) or die($s);

				while($su = mysql_fetch_object($e)){

					$s = "UPDATE catalogorutadetallesucursal SET idruta=".$codigo."

					WHERE idusuario=".$_SESSION[IDUSUARIO]." AND idruta=0";

					mysql_query($s,$l) or die($s);

				}*/

				

			echo "ok,".$_GET[tipo].",".$codigo;

			

		}else if($_GET[tipo]=="modif"){
			
			$s = "SELECT * FROM bitacorasalida WHERE ruta = '$_GET[ruta]'
			AND STATUS=0 AND cancelada=0";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				die("No se puede modificar una ruta cuando esta en una bitacora activa, verifique que la bitacora $f->folio termine.");
			}
			
			$s = "UPDATE catalogoruta SET descripcion=UCASE('".$row[0]."'),recorrido='".$row[1]."',
			km='".$row[2]."', idtipounidad=".$row[3].",tipounidad='".$row[4]."',
			enuso=0,usuario='".$_SESSION[NOMBREUSUARIO]."',fecha=CURRENT_TIMESTAMP
			WHERE id=".$_GET[ruta]."";
			mysql_query($s,$l) or die($s);			

			$s = "DELETE FROM catalogorutadetalle WHERE ruta=".$_GET[ruta];
			mysql_query($s,$l) or die($s);

			$s = "INSERT INTO catalogorutadetalle
			SELECT 0 AS id, ".$_GET[ruta]." AS ruta, tipo, diasalidas, sucursal, horasllegada,
			tiempodescarga, tiempocarga, horasalida, trayectosucursal, transbordo,
			sucursalestransbordo, idusuario, usuario, fecha
			FROM catalogorutadetalletmp
			WHERE idusuario=".$_SESSION[IDUSUARIO]."
			order by catalogorutadetalletmp.id asc";
			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM catalogorutadetallesucursal WHERE idruta='".$_GET[ruta]."'";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO catalogorutadetallesucursal
			SELECT 0 as id, '".$_GET[ruta]."', idsucursal, sucursal, ".$_SESSION[IDUSUARIO].", fecha 
			FROM catalogorutadetallesucursaltmp WHERE idusuario =".$_SESSION[IDUSUARIO]."";			
			$r = mysql_query($s,$l) or die($s);			

			echo "ok,".$_GET[tipo];

		}

	}else if($_GET[accion] == 8){

		$sucursales = "";		

		$s = "SELECT idsucursal,sucursal FROM catalogorutadetallesucursal

		WHERE idruta =".$_GET[ruta]."";		

		$r = mysql_query($s,$l) or die($s);

		$registros = array();

		

			if(mysql_num_rows($r)>0){			

				$f = mysql_fetch_object($r);

				if($f->idsucursal=="0"){

					$s = mysql_query("SELECT descripcion FROM catalogosucursal",$l);

					while($r = mysql_fetch_object($s)){

						$r->sucursal = cambio_texto($r->sucursal);	

						$registros[] = $r;

					}					

				}else{					

					if(mysql_num_rows($r)==1){

						$f->sucursal = cambio_texto($f->sucursal);

						$registros[] = $f;

					}else{						

						$s = "SELECT idsucursal,sucursal FROM catalogorutadetallesucursal

						WHERE idruta =".$_GET[ruta]."";

						$rq = mysql_query($s,$l) or die($s);

						while($f = mysql_fetch_object($rq)){

							$f->sucursal = cambio_texto($f->sucursal);

							$registros[] = $f;

						}

					}					

				}

			}				

		

		$sucursales = str_replace("null",'""',json_encode($registros));

		echo "[{sucursales:$sucursales}]";

		

	}else if($_GET[accion] == 9){

		if($_GET[tipo] == ""){

			if($_GET[sucursal]!="TODAS"){

				$s = "INSERT INTO catalogorutadetallesucursaltmp (idsucursal,sucursal,idusuario,fecha)

				VALUES (".$_GET[idsucursal].",

				'".$_GET[sucursal]."',".$_SESSION[IDUSUARIO].", CURRENT_TIMESTAMP)";

				mysql_query($s,$l) or die($s);

				echo "ok";



			}else{		

				$s = "DELETE FROM catalogorutadetallesucursaltmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
				mysql_query($s,$l) or die($s);			

				$s = "INSERT INTO catalogorutadetallesucursaltmp (idsucursal,sucursal,idusuario,fecha)
				VALUES (".$_GET[idsucursal].",'".$_GET[sucursal]."',".$_SESSION[IDUSUARIO].", CURRENT_TIMESTAMP)";
				mysql_query($s,$l) or die($s);
				echo "ok";

			}			

		}else if($_GET[tipo] == "eliminar"){
			$s = "DELETE FROM catalogorutadetallesucursaltmp WHERE idusuario=".$_SESSION[IDUSUARIO]."
			AND idsucursal=0 AND sucursal='TODAS'";
			mysql_query($s,$l) or die($s);
			echo "ok";

		}else if($_GET[tipo] == "eliminar1"){
		 	$s = "DELETE FROM catalogorutadetallesucursaltmp 
			WHERE idusuario=".$_SESSION[IDUSUARIO]." AND idsucursal=".$_GET[idsucursal];
			mysql_query($s,$l) or die($s);
			echo "ok";
		}

		

	}else if($_GET[accion] == 10){		
		$principal = "";
		$s = "SELECT cr.descripcion, cr.recorrido, cr.km, cr.idtipounidad,
		ct.descripcion AS tipounidad FROM catalogoruta cr
		INNER JOIN catalogotipounidad ct ON cr.idtipounidad = ct.id
		WHERE cr.id =".$_GET[ruta];
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r);
				$f->descripcion = cambio_texto($f->descripcion);
				$f->tipounidad = cambio_texto($f->tipounidad);
							
			$principal = str_replace('null','""',json_encode($f));			
			$s = "DELETE FROM catalogorutadetalletmp WHERE idusuario=".$_SESSION[IDUSUARIO];
			mysql_query($s,$l) or die($s);			

			$s = "INSERT INTO catalogorutadetalletmp
			SELECT 0 AS id, tipo, diasalidas, sucursal, horasllegada, tiempodescarga, tiempocarga,
			horasalida, trayectosucursal, transbordo, sucursalestransbordo, ".$_SESSION[IDUSUARIO]." AS idusuario,
			'".$_SESSION[NOMBREUSUARIO]."' AS usuario, fecha FROM catalogorutadetalle WHERE ruta=".$_GET[ruta]."
			order by id asc";
			mysql_query($s,$l) or die($s);
			$detalle = "";
			$s = "SELECT cd.tipo, cd.diasalidas AS dia, cd.sucursal AS idsucursal,
			IF(cd.horasllegada='00:00:00','',TIME_FORMAT(cd.horasllegada,'%H:%i')) AS llegada, 
			IF(cd.tiempodescarga='00:00:00','',TIME_FORMAT(cd.tiempodescarga,'%H:%i')) AS descarga,
			IF(cd.tiempocarga='00:00:00','',TIME_FORMAT(cd.tiempocarga,'%H:%i')) AS carga,
			IF(cd.horasalida='00:00:00','',TIME_FORMAT(cd.horasalida,'%H:%i')) AS salida,
			IF(cd.trayectosucursal='00:00:00','',TIME_FORMAT(cd.trayectosucursal,'%H:%i')) AS siguiente,
			IF(cd.transbordo=0,'','SI') AS trasbordo, cd.sucursalestransbordo AS suctransbordo,
			cs.prefijo AS sucursal, cd.fecha FROM catalogorutadetalletmp cd
			INNER JOIN catalogosucursal cs ON cd.sucursal = cs.id
			WHERE idusuario=".$_SESSION[IDUSUARIO]."
			order by cd.id asc";
			$su = mysql_query($s,$l) or die($s);
			$deta = array();
			while($g = mysql_fetch_object($su)){
				$deta[] = $g;
			}
			$detalle = str_replace('null','""',json_encode($deta));
			$sucursales = "";
			
			$s = "DELETE FROM catalogorutadetallesucursaltmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO catalogorutadetallesucursaltmp
			SELECT 0 as id, idsucursal, sucursal, ".$_SESSION[IDUSUARIO].", fecha FROM catalogorutadetallesucursal
			WHERE idruta =".$_GET[ruta]."";			
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
			$s = "SELECT idsucursal as clave, sucursal AS nombre FROM catalogorutadetallesucursaltmp
			WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			$r = mysql_query($s,$l) or die($s);
				
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				if($f->clave=="0"){
					$s = mysql_query("SELECT descripcion as nombre FROM catalogosucursal WHERE id > 1 order by descripcion ",$l);
					while($r = mysql_fetch_object($s)){
						$r->nombre = cambio_texto($r->nombre);
						$registros[] = $r;
					}
					$idsucursal = str_replace('null','""',json_encode($f->clave));
				}else{
					if(mysql_num_rows($r)==1){
						$f->nombre = cambio_texto($f->nombre);
						$idsucursal .= $f->clave.":".$f->nombre.",";
						$registros[] = $f;
						$idsucursal = str_replace('null','""',json_encode(trim($idsucursal)));
					}else{
						$s = "SELECT idsucursal as clave,sucursal as nombre FROM catalogorutadetallesucursaltmp
						WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
						$rq = mysql_query($s,$l) or die($s);
						while($f = mysql_fetch_object($rq)){
							$f->nombre = cambio_texto($f->nombre);
							$idsucursal .= $f->clave.":".$f->nombre.",";
							$registros[] = $f;
						}
						$idsucursal = str_replace('null','""',json_encode(trim($idsucursal)));
					}
				}
			}
					
				/*if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);
					if($f->idsucursal=="0"){
						$s = mysql_query("SELECT descripcion as nombre FROM catalogosucursal WHERE id > 1",$l);
						while($r = mysql_fetch_object($s)){
							$r->sucursal = cambio_texto($r->sucursal);	
							$registros[] = $r;
						}
					}else{
						if(mysql_num_rows($r)==1){
							$f->sucursal = cambio_texto($f->sucursal);
							$idsucursal .= $f->sucursal.":".$f->idsucursal.",";
							$registros[] = $f;
						}else{
							$s = "SELECT idsucursal,sucursal as nombre FROM catalogorutadetallesucursaltmp
							WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
							$rq = mysql_query($s,$l) or die($s);
							while($f = mysql_fetch_object($rq)){
								$f->sucursal = cambio_texto($f->sucursal);
								$registros[] = $f;
							}
						}
					}
				}*/				

			$sucursales = str_replace("null",'""',json_encode($registros));			

			echo "({principal:$principal,".(($idsucursal!='')?"idsucursal:$idsucursal":"idsucursal:'$idsucursal'").",detalle:$detalle,sucursales:$sucursales})";		

		}else{

			echo "0";

		}

	}	

?>