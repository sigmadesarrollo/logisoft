<? 	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}	*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");	
		
	if($_GET[accion]==1){//OBTENER UNIDAD
		$s = "SELECT * FROM catalogounidad 
		WHERE numeroeconomico='".$_GET[unidad]."' AND fueradeservicio=0";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			echo "encontro";
		}else{
			echo "no encontro";
		}
			
	}else if($_GET[accion]==2){//OBTENER DATOS PRINCIPALES	 
	  	$s = "SELECT DATE_FORMAT(CURRENT_DATE , '%d-%m-%Y') as fecha, id, descripcion FROM catalogodestino
		 WHERE sucursal = ".$_GET['idsucursal']." AND subdestinos=1";		
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){	
	 		while($f = mysql_fetch_object($r)){
				$f->origen = cambio_texto($f->origen);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
			
	}else if($_GET[accion]==3){//OBTENER DATOS DESTINO				
		$s = mysql_query("SELECT todasemana, CASE DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."')
			WHEN 2 THEN lunes
			WHEN 3 THEN martes
			WHEN 4 THEN miercoles
			WHEN 5 THEN jueves
			WHEN 6 THEN viernes
			WHEN 7 THEN sabado
			ELSE 0 END AS dia
			FROM catalogodestino WHERE id=".trim($_GET[destino])."",$l) or die(mysql_error($l).$s);
		$registros = array();
		while($f = mysql_fetch_object($s)){
			$registros[] = $f;
		}
		echo str_replace('null','""',json_encode($registros));
	}else if($_GET[accion]==4){//OBTENER SECTOR
		$s = "SELECT s.id, s.descripcion FROM catalogosector s
			INNER JOIN catalogosectordetalle d ON s.id=d.idsector
			WHERE d.colonia = '".$_GET['col']."' AND d.cp=".$_GET['cp']."";
			$registros = array();
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
		 		while($f = mysql_fetch_object($r)){
					$f->descripcion = cambio_texto($f->descripcion);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
						
	}else if($_GET[accion]==5){//OBTENER RECOLECCIONES(PARA RECOLECCION MERCANCIA)		
		$s = "SELECT r.folio, r.sucursal, r.estado, r.horario, CONCAT(c.nombre,' ',c.paterno,' ',c.materno) AS cliente,
		CONCAT(r.calle,' #',r.numero,' ',r.colonia) AS direccion, r.telefono,
		DATE_FORMAT(r.fecharecoleccion,'%d/%m/%Y') AS fecha, r.unidad,
		DATE_FORMAT(r.fecharegistro,'%d/%m/%Y') AS fecharegistro,
		IFNULL(r.transmitida,'NO') AS transmitida, IFNULL(r.realizo,'NO') AS realizo
		FROM recoleccion r
		INNER JOIN catalogocliente c ON r.cliente = c.id
		WHERE r.fecharegistro = 
		".(($_GET['fecha']=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))."
		".(($_GET['sucursal']!="")? " AND r.sucursal=".$_GET['sucursal']."" : "" )."
		".(($_GET['cliente'] !="")? " AND r.cliente =".$_GET['cliente']."" : "")." 
		ORDER BY r.folio ASC";		
		if($_GET[tipo]=="0"){
			$r = mysql_query($s,$l) or die($s);
			echo mysql_num_rows($r);
			
		}else if($_GET[tipo]=="1"){
			
			$registros = array();
			$s = $s." LIMIT ".$_GET[inicio].",30";
			$r = mysql_query($s,$l) or die($s);
		 		while($f = mysql_fetch_object($r)){
					$f->cliente = cambio_texto($f->cliente);
					$f->direccion = cambio_texto($f->direccion);					
					$f->colorcan = "";
					$f->colorrep = "";					
					
					$sc = mysql_query("SELECT r.motivo, m.descripcion AS desmotivo, m.color FROM recoleccionmotivocancelacion r
					INNER JOIN catalogomotivos m ON r.motivo = m.id
					WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]." AND 
					r.fecharegistro=CURRENT_DATE()",$l) or die(mysql_error($l).$sc);
					
					$can = mysql_fetch_object($sc);
					$f->colorcan = $can->color;
					
					$f->motivos = "";
					if($f->estado=="CANCELADO"){
						$f->motivos = cambio_texto($can->desmotivo);
					}
					
					$sr = mysql_query("SELECT r.motivo, m.descripcion AS desmotivoreprogramar,
					m.color FROM recoleccionmotivoreprogramacion r
					INNER JOIN catalogomotivos m ON r.motivo = m.id
					WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]." AND 
					r.fecharegistro>=CURRENT_DATE()",$l);
					
					$rep = mysql_fetch_object($sr);
					$f->colorrep = $rep->color;
					
					if($rep->desmotivoreprogramar!=""){
						$f->motivos = cambio_texto($rep->desmotivoreprogramar);
					}
					if($rep->desmotivoreprogramar=="" && $f->estado!="CANCELADO"){
						$f->motivos = cambio_texto($f->motivos);
					}
					
					$recolecciones = ""; $empresariales = ""; $guiasempresariales = "";
					$em =  mysql_query("SELECT gv.id AS guia FROM guiasventanilla gv
						INNER JOIN recolecciondetallefoliorecoleccion r ON gv.recoleccion = r.foliosrecolecciones
						WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]."",$l) or die($em);
					/*$em = mysql_query("SELECT g.id AS guia FROM evaluacionmercancia e
					INNER JOIN guiasventanilla g ON e.folio = g.evaluacion AND e.sucursal = g.idsucursalorigen
					WHERE e.sucursal = ".$_GET[sucursal]." AND 
					g.fecha = ".(($_GET['fecha']=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))." AND					
					e.recoleccion<>0",$l) or die($em);*/
					/*$em = mysql_query("SELECT g.guia FROM evaluacionmercancia e
					INNER JOIN (SELECT gv.id AS guia, gv.evaluacion, gv.fecha FROM guiasventanilla gv
					UNION
					SELECT ge.id AS guia, ge.evaluacion, ge.fecha FROM guiasempresariales ge) AS g ON e.folio = g.evaluacion
					WHERE e.sucursal = ".$_GET[sucursal]." AND
					g.fecha = ".(($_GET['fecha']=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))." AND
					(e.recoleccion<>0 OR e.recoleccion IS NOT NULL)",$l) or die($em);*/
					
					if(mysql_num_rows($em)>0){
						while($rowd=mysql_fetch_array($em)){
							$guiasempresariales .= $rowd[0].",";
						}
						$guiasempresariales = substr($guiasempresariales,0,strlen($guiasempresariales)-1);
					}

					if($f->estado=="REALIZADO"){						
						$sr = mysql_query("SELECT foliosrecolecciones FROM recolecciondetallefoliorecoleccion
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($sr);
					 
						if(mysql_num_rows($sr)>0){
							while($row=mysql_fetch_array($sr)){
								$recolecciones .=$row[0].",";
							}
							$recolecciones = substr($recolecciones,0,strlen($recolecciones)-1);
						}
					
						$se = mysql_query("SELECT foliosempresariales FROM recolecciondetallefolioempresariales
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($se);
						if(mysql_num_rows($se)>0){
							while($rowd=mysql_fetch_array($se)){
								$empresariales .=$rowd[0].",";
							}
							$empresariales = substr($empresariales,0,strlen($empresariales)-1);
						}
					}
					
				if((!empty($recolecciones) || $recolecciones!=" ") && (!empty($empresariales) || $recolecciones!=" ")){							
					$f->folios = $recolecciones."--".$empresariales;
				}
				
				if($f->folios == "--"){
					$f->folios = "";
				}
				
				$f->folios = cambio_texto($f->folios);
				$f->guia = cambio_texto($guiasempresariales);
				$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}
	}else if($_GET[accion]=="ultimorecoleccion"){//OBTENER RECOLECCIONES(PARA RECOLECCION MERCANCIA)		
		$s = "SELECT COUNT(*) AS total FROM recoleccion
		WHERE fecharegistro = ".(($_GET[fecha]=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))."
		".(($_GET[sucursal]!="")? " AND sucursal=".$_GET[sucursal]."" : "" )."
		".(($_GET[cliente] !="")? " AND cliente =".$_GET[cliente]."" : "")."";		
		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);
		$re = $c->total%30; $res = intval($c->total/30) * 30;
		$limit = $res.",".$re;
		
		$s = "SELECT r.folio, r.sucursal, r.estado, r.horario, CONCAT(c.nombre,' ',c.paterno,' ',c.materno) AS cliente,
		CONCAT(r.calle,' #',r.numero,' ',r.colonia) AS direccion, r.telefono,
		DATE_FORMAT(r.fecharecoleccion,'%d/%m/%Y') AS fecha, r.unidad,
		DATE_FORMAT(r.fecharegistro,'%d/%m/%Y') AS fecharegistro,
		r.transmitida, r.realizo
		FROM recoleccion r
		INNER JOIN catalogocliente c ON r.cliente = c.id
		WHERE r.fecharegistro = 
		".(($_GET['fecha']=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))."
		".(($_GET['sucursal']!="")? " AND r.sucursal=".$_GET['sucursal']."" : "" )."
		".(($_GET['cliente'] !="")? " AND r.cliente =".$_GET['cliente']."" : "")." 
		ORDER BY r.fecharecoleccion ASC LIMIT ".$limit."";
			$registros = array();
			$r = mysql_query($s,$l) or die($s);
		 		while($f = mysql_fetch_object($r)){
					$f->cliente = cambio_texto($f->cliente);
					$f->direccion = cambio_texto($f->direccion);					
					$f->colorcan = "";
					$f->colorrep = "";					
					
					$sc = mysql_query("SELECT r.motivo, m.descripcion AS desmotivo, m.color FROM recoleccionmotivocancelacion r
					INNER JOIN catalogomotivos m ON r.motivo = m.id
					WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]." AND 
					r.fecharegistro=CURRENT_DATE()",$l) or die(mysql_error($l).$sc);
					
					$can = mysql_fetch_object($sc);
					$f->colorcan = $can->color;
					
					$f->motivos = "";
					if($f->estado=="CANCELADO"){
						$f->motivos = cambio_texto($can->desmotivo);
					}
					
					$sr = mysql_query("SELECT r.motivo, m.descripcion AS desmotivoreprogramar,
					m.color FROM recoleccionmotivoreprogramacion r
					INNER JOIN catalogomotivos m ON r.motivo = m.id
					WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]." AND 
					r.fecharegistro>=CURRENT_DATE()",$l);
					
					$rep = mysql_fetch_object($sr);
					$f->colorrep = $rep->color;
					
					if($rep->desmotivoreprogramar!=""){
						$f->motivos = cambio_texto($rep->desmotivoreprogramar);
					}
					if($rep->desmotivoreprogramar=="" && $f->estado!="CANCELADO"){
						$f->motivos = cambio_texto($f->motivos);
					}
					
					$recolecciones = ""; $empresariales = ""; $guiasempresariales = "";
						/*$em = mysql_query("SELECT g.id AS guia FROM evaluacionmercancia e
						INNER JOIN guiasventanilla g ON e.folio = g.evaluacion AND e.sucursal = g.idsucursalorigen
						WHERE e.sucursal = ".$_GET[sucursal]." AND 
						g.fecha = ".(($_GET['fecha']=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))." AND
						e.recoleccion<>0",$l) or die($em);*/
						
						$em =  mysql_query("SELECT gv.id AS guia FROM guiasventanilla gv
						INNER JOIN recolecciondetallefoliorecoleccion r ON gv.recoleccion = r.foliosrecolecciones
						WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]."",$l) or die($em);
						
						/*$em = mysql_query("SELECT g.guia FROM evaluacionmercancia e
						INNER JOIN (SELECT gv.id AS guia, gv.evaluacion, gv.fecha FROM guiasventanilla gv
						UNION
						SELECT ge.id AS guia, ge.evaluacion, ge.fecha FROM guiasempresariales ge) AS g ON e.folio = g.evaluacion
						WHERE e.sucursal = ".$_GET[sucursal]." AND
						g.fecha = ".(($_GET['fecha']=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))." AND
						(e.recoleccion<>0 OR e.recoleccion IS NOT NULL)",$l) or die($em);*/
						
						if(mysql_num_rows($em)>0){
							while($rowd=mysql_fetch_array($em)){
								$guiasempresariales .=$rowd[0].",";
							}
							$guiasempresariales = substr($guiasempresariales,0,strlen($guiasempresariales)-1);
						}
					if($f->estado=="REALIZADO"){						
						$sr = mysql_query("SELECT foliosrecolecciones FROM recolecciondetallefoliorecoleccion
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($sr);
					 
						if(mysql_num_rows($sr)>0){
							while($row=mysql_fetch_array($sr)){
								$recolecciones .=$row[0].",";
							}
							$recolecciones = substr($recolecciones,0,strlen($recolecciones)-1);
						}
					
						$se = mysql_query("SELECT foliosempresariales FROM recolecciondetallefolioempresariales
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($se);
						if(mysql_num_rows($se)>0){
							while($rowd=mysql_fetch_array($se)){
								$empresariales .=$rowd[0].",";
							}
							$empresariales = substr($empresariales,0,strlen($empresariales)-1);
						}
					}
					
				if((!empty($recolecciones) || $recolecciones!=" ") && (!empty($empresariales) || $recolecciones!=" ")){							
					$f->folios = $recolecciones."--".$empresariales;
				}
				
				if($f->folios == "--"){
					$f->folios = "";
				}
					$f->guia = cambio_texto($guiasempresariales);
					$f->folios = cambio_texto($f->folios);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
				
	}else if($_GET[accion]==6){
		$s = "DELETE FROM recolecciondetalle_tmp WHERE idusuario=".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);		
		$s = "DELETE FROM recolecciondetallefolioempresariales WHERE recoleccion IS NULL AND idusuario=".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);		
		$s = "DELETE FROM recolecciondetallefoliorecoleccion WHERE recoleccion IS NULL AND idusuario=".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT r.folio, r.estado, r.sucursal, DATE_FORMAT(r.fecharegistro,'%d/%m/%Y') AS fecharegistro,
		DATE_FORMAT(r.fecharecoleccion,'%d/%m/%Y') AS fecharecoleccion,
		r.origen, r.destino, r.npedidos, r.dirigido, r.chnombre, r.llama, r.telefono, r.comentarios,
		r.cliente, r.calle, r.numero, r.crucecalles, r.cp, r.colonia, r.poblacion,
		r.municipio, r.telefono2, r.horario, r.sector, r.unidad, r.transmitida, r.realizo,
		CONCAT(c.nombre,' ',c.paterno,' ',c.materno) AS ncliente, s.descripcion AS dessuc,
		o.descripcion AS desori, r.multiple, r.horario2, r.hrcomida,
		r.hrcomida2 FROM recoleccion r
		INNER JOIN catalogocliente c ON r.cliente = c.id
		INNER JOIN catalogosucursal s ON r.sucursal = s.id
		LEFT JOIN catalogodestino o ON r.origen = o.id
		LEFT JOIN catalogodestino d ON r.destino = d.id
		WHERE r.folio='".$_GET['folio']."' AND r.sucursal=".$_GET[idsucursal]."";	
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
				$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion),
				CONCAT(cd.descripcion,' - ',cs.prefijo)) AS desdes FROM catalogodestino cd
				INNER JOIN catalogosucursal cs ON cd.sucursal = cs.id
				WHERE cd.id=".$f->destino;
				$t = mysql_query($s,$l) or die($s);
				$tt = mysql_fetch_object($t);
				
				$f->estado = cambio_texto($f->estado);
				$f->llama = cambio_texto($f->llama);
				$f->comentarios = cambio_texto($f->comentarios);
				$f->calle = cambio_texto($f->calle);
				$f->crucecalles = cambio_texto($f->crucecalles);
				$f->colonia = cambio_texto($f->colonia);
				$f->poblacion = cambio_texto($f->poblacion);
				$f->municipio = cambio_texto($f->municipio);
				$f->unidad = cambio_texto($f->unidad);
				$f->ncliente = cambio_texto($f->ncliente);
				$f->dessuc = cambio_texto($f->dessuc);
				$f->desori = cambio_texto($f->desori);
				$f->desdes = cambio_texto($tt->desdes);
				$registros[] = $f;
			
			$pincipal = str_replace('null','""',json_encode($f));
			
			$detalle = "";
			
			$s = "INSERT INTO recolecciondetalle_tmp
			SELECT 0 AS id,cantidad, iddescripcion, descripcion, contenido, 
			peso, largo, ancho, alto, volumen, pesototal, pesounit, ".$_SESSION[IDUSUARIO]." AS idusuario,
			fecha FROM recolecciondetalle
			WHERE recoleccion='".$_GET[folio]."' AND sucursal=".$_GET[idsucursal]."";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT cantidad, iddescripcion, descripcion, contenido, 
			peso, largo, ancho, alto, volumen, pesototal, pesounit,fecha FROM recolecciondetalle_tmp
			WHERE idusuario=".$_SESSION[IDUSUARIO];
			$registros = array();
			$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$f->descripcion = cambio_texto($f->descripcion);
				$f->contenido = cambio_texto($f->contenido);
				$registros[] = $f;
			}
			
			$detalle = str_replace('null','""',json_encode($registros));
			
			$recolecciones = "";
			$s = "SELECT foliosrecolecciones as folio FROM recolecciondetallefoliorecoleccion 
			WHERE recoleccion='".$_GET['folio']."' AND sucursal=".$_GET['idsucursal']."";
			$registros = array();
			$r = mysql_query($s,$l) or die($s);		
				while($f = mysql_fetch_object($r)){
					$registros[] = $f;
				}		
			$recolecciones = str_replace('null','""',json_encode($registros));
			
			$empresariales = "";
			$sq = "SELECT foliosempresariales as folio FROM recolecciondetallefolioempresariales 
			WHERE recoleccion='".$_GET['folio']."' AND sucursal=".$_GET['idsucursal']."";
			$registro = array();
			$rr = mysql_query($sq,$l) or die($sq);
			while($fr = mysql_fetch_object($rr)){
				$registro[] = $fr;
			}		
			$empresariales = str_replace('null','""',json_encode($registro));
			
			
			echo "({principal:$pincipal, detalle:$detalle,".(($recolecciones!="")?"recoleccion:$recolecciones,":"")."
			".(($empresariales!="")?"empresarial:$empresariales":"")."})";
			
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==7){
		$s = "SELECT cantidad, iddescripcion, descripcion, contenido, 
		peso, largo, ancho, alto, volumen, pesototal, pesounit FROM recolecciondetalle
		WHERE recoleccion='".$_GET['folio']."' AND sucursal=".$_GET[idsucursal]."";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$f->descripcion = cambio_texto($f->descripcion);
			$f->contenido = cambio_texto($f->contenido);
			$registros[] = $f;
		}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==8){//OBTENER SUCURSAL		
		$principal = "";
		$s = "SELECT cd.id, CONCAT(cs.prefijo,' - ',cd.descripcion) AS descripcion FROM catalogodestino cd
		INNER JOIN catalogosucursal cs ON cd.sucursal = cs.id
		WHERE ".(($_GET['sucursal']==1)?"":" cd.sucursal=".$_GET['sucursal']." AND ")." cd.subdestinos=1";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$principal = "";
		$f->descripcion = cambio_texto($f->descripcion);			

		$principal = str_replace('null','""',json_encode($f));
		
		$origenes = "";
		$s = "SELECT cd.id, cd.descripcion FROM catalogosucursal cs
		INNER JOIN catalogodestino cd ON cs.id = cd.sucursal
		WHERE ".(($_GET['sucursal']==1)?"":"cs.id = ".$_GET[sucursal]." AND ")." cd.subdestinos=1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
			$f->descripcion = cambio_texto($f->descripcion);			
		
		$origenes = str_replace('null','""',json_encode($f));
		
		$detalle = "";
		$s = "SELECT id, descripcion AS destino FROM catalogodestino 
		".(($_GET['sucursal']==1)?"":" WHERE sucursal=".$_GET[sucursal]."");
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$f->destino = cambio_texto($f->destino);
				$registros[] = $f;
			}
			
		$detalle = str_replace('null','""',json_encode($registros));		
		
		$folio = obtenerFolioRecoleccion($_GET[sucursal],'');
		
		echo "({principal:$principal ".(($_GET[origen]!="")?",origen:$detalle,folio:'$folio',origenes:$origenes":"")."})";
		
	}else if($_GET[accion]==10){//OBTENER FOLIOS RECOLECCION EMPRESARIALES
				$recolecciones = "";
		$s = "SELECT foliosrecolecciones as folio FROM recolecciondetallefoliorecoleccion WHERE recoleccion='".$_GET['folio']."' AND sucursal=".$_GET['idsucursal']."";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);		
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}		
		$recolecciones = str_replace('null','""',json_encode($registros));
		
		$empresariales = "";
		$sq = "SELECT foliosempresariales as folio FROM recolecciondetallefolioempresariales WHERE recoleccion='".$_GET['folio']."' AND sucursal=".$_GET['idsucursal']."";
		$registro = array();
		$rr = mysql_query($sq,$l) or die($sq);
		while($fr = mysql_fetch_object($rr)){
			$registro[] = $fr;
		}		
		$empresariales = str_replace('null','""',json_encode($registro));
		
		echo "[{recolecciones:$recolecciones, 
				empresariales:$empresariales}]";
				
	}else if($_GET[accion]==11){//OBTENER DESTINO		
		$s = "SELECT id, descripcion FROM catalogodestino WHERE sucursal=".$_GET['sucursal']."";
		//die($s);
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$f->descripcion = cambio_texto($f->descripcion);
				$registros[] = $f;
			}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==12){//OBTENER HORARIO CLIENTE
		$s = "SELECT  IFNULL( MAX( horario ) , '00:00' ) AS horario,
		IFNULL( MAX( horario2 ) , '00:00' ) AS horario2, 
		IFNULL( MAX( hrcomida ) , '00:00' ) AS hrcomida,
		IFNULL( MAX( hrcomida2 ) , '00:00' ) AS hrcomida2
 		FROM recoleccion WHERE cliente=".$_GET[cliente]."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		$f = mysql_fetch_object($r);
		if($f->horario!="00:00" && $f->horario2!="00:00"){
			$registros[] = $f;
			echo str_replace('null','""',json_encode($registros));
		}else{
			$s = "SELECT  IFNULL( MAX( horario ) , '00:00' ) AS horario,
			IFNULL( MAX( horario2 ) , '00:00' ) AS horario2, 
			IFNULL( MAX( hrcomida ) , '00:00' ) AS hrcomida,
			IFNULL( MAX( hrcomida2 ) , '00:00' ) AS hrcomida2
	 		FROM configurarrecoleccionesprogramadas WHERE idcliente=".$_GET[cliente]."";
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
			$f = mysql_fetch_object($r);
			if($f->horario!="00:00" && $f->horario2!="00:00"){
				$registros[] = $f;
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
		}		
	}else if($_GET[accion]==13){//OBTENER FOLIO		
		$folio = obtenerFolioRecoleccion($_GET['idsucursal'],cambiaf_a_mysql($_GET['fecha']));		
		echo $folio;
		
	}else if($_GET[accion]==14){//VERIFICAR RECOLECCIONES PROGRAMADAS Y DAR DE ALTA	
		$s = mysql_query("SELECT
		 CASE DATE_FORMAT(CURRENT_DATE,'%W')
			WHEN 'Monday' 	 THEN 'L'
			WHEN 'Tuesday'   THEN 'M'
			WHEN 'Wednesday' THEN 'MI'
			WHEN 'Thursday'  THEN 'J'
			WHEN 'Friday' 	 THEN 'V'
			WHEN 'Saturday'  THEN 'S'
			ELSE 0
		 END AS dia",$l) or die(mysql_error($l).$s);
		 $f = mysql_fetch_object($s);	 
		 $s = "SELECT id,idcliente,iddireccion,direccion,idsucursal,idorigen,origen,
	iddestino,destino,dia,sector,horario,horario2,hrcomida,hrcomida2,
	date_format(fecharegistro,'%d/%m/%Y') as fecharegistro,usuario,fecha
	FROM configurarrecoleccionesprogramadas";
 	$sc = mysql_query($s,$l)or die(mysql_error($l).$sc);
	
	if(mysql_num_rows($sc)>0){	
		$s = "SELECT * FROM recoleccion WHERE fecharegistro=CURRENT_DATE() AND diaprogramado='".$f->dia."'";
		$sdia = mysql_query($s,$l) or die($s);
		
		if(mysql_num_rows($sdia)<1){
			while($d=mysql_fetch_object($sc)){
				if($d->fecharegistro != date('d/m/Y')){
					if($d->dia=="L,M,MI,J,V,S"){
						$dia = split(",",$d->dia);
						for($j=0;$j<count($dia);$j++){
							if($dia[$j]==$f->dia){
							
								$folio = obtenerFolioRecoleccion($d->idsucursal,date("d/m/Y"));
								$s = "SELECT calle, numero, crucecalles, cp, 
								colonia, poblacion, municipio, telefono FROM direccion 
								WHERE id=".$d->iddireccion; 
								$r = mysql_query($s,$l) or die($s);
								$dir = mysql_fetch_object($r);						
								
								$s = "INSERT INTO recoleccion
								(folio, fecharegistro, estado, sucursal, origen, destino, cliente, calle, numero,
								crucecalles, cp, colonia, poblacion, municipio, telefono2, sector,
								horario, horario2, hrcomida, hrcomida2, diaprogramado, usuario, fecha) 
								VALUES ('".$folio."',CURRENT_DATE,'NO TRANSMITIDO', ".$d->idsucursal.",
								 ".$d->idorigen." , ".$d->iddestino.",
								'".$d->idcliente."', UCASE('".$dir->calle."'), UCASE('".$dir->numero."'),
								UCASE('".$dir->crucecalles."'), ".$dir->cp.", UCASE('".$dir->colonia."'),
								UCASE('".$dir->poblacion."'), UCASE('".$dir->municipio."'),
								'".$dir->telefono."', UCASE('".$d->sector."'),'".$d->horario."',
								'".$d->horario2."', '".$d->hrcomida."','".$d->hrcomida2."',
								'".$dia[$j]."', '".$_SESSION[NOMBREUSUARIO]."', CURRENT_TIMESTAMP())";								
								mysql_query($s,$l) or die($s);
								
								//die($s);
								
								$s = "INSERT INTO recolecciondetalle
								(recoleccion, sucursal,cantidad,iddescripcion,descripcion,
								contenido,peso, largo,ancho,alto,volumen,pesototal,usuario,fecha)
								VALUES ('".$folio."',".$d->idsucursal.",1,8,'ENVASE(S)',
								'DOCUMENTO',1,1,1,1,1,1,'$_SESSION[NOMBREUSUARIO]', CURRENT_TIMESTAMP())";
								mysql_query($s,$l) or die($s);
							}
						}
	
					}else{
									
						$dia = split(",",$d->dia);					
						for($i=0;$i<count($dia);$i++){
							if($dia[$i]==$f->dia){
								
								$folio = obtenerFolioRecoleccion($d->idsucursal,date("d/m/Y"));	
								$s = "SELECT calle, numero, crucecalles, cp, colonia, poblacion,	
								municipio, telefono FROM direccion WHERE id=".$d->iddireccion;	
								$r = mysql_query($s,$l) or die($s);
								$dir = mysql_fetch_object($r);
		
								$s = "INSERT INTO recoleccion	
								(folio, fecharegistro, estado, sucursal, origen, destino, cliente, calle,	
								numero, crucecalles, cp, colonia, poblacion, municipio,	
								telefono2, sector, horario, horario2, hrcomida, hrcomida2,	
								diaprogramado, usuario, fecha)
								VALUES ('".$folio."',CURRENT_DATE,'NO TRANSMITIDO', ".$d->idsucursal.",
								".$d->idorigen.", ".$d->iddestino.",	
								".$d->idcliente.", UCASE('".$dir->calle."'), UCASE('".$dir->numero."'), 	
								UCASE('".$dir->crucecalles."'), ".$dir->cp.", UCASE('".$dir->colonia."'),	
								UCASE('".$dir->poblacion."'), UCASE('".$dir->municipio."'), '".$dir->telefono."',	
								UCASE('".$d->sector."'),'".$d->horario."','".$d->horario2."',	
								'".$d->hrcomida."','".$d->hrcomida2."','".$dia[$i]."', '".$_SESSION[NOMBREUSUARIO]."',	
								CURRENT_TIMESTAMP())";								
								mysql_query($s,$l) or die($s);
								
								$s = "INSERT INTO recolecciondetalle	
								(recoleccion, sucursal,cantidad,iddescripcion,descripcion,contenido,peso,	
								largo,ancho,alto,volumen,pesototal,usuario,fecha) VALUES	
								('".$folio."',".$d->idsucursal.",1,8,'ENVASE(S)','DOCUMENTO',1,1,1,1,1,1,	
								'$_SESSION[NOMBREUSUARIO]', CURRENT_TIMESTAMP())";
								mysql_query($s,$l) or die($s);
							}
						}
					}
				}
			}
		}
	}		 
	
	}else if($_GET[accion]==15){//OBTENER RECOLECCIONES X FECHA
		$s = "SELECT r.folio, r.sucursal, r.estado, r.horario, CONCAT(c.nombre,' ',c.paterno,' ',c.materno) AS cliente,
		CONCAT(r.calle,' #',r.numero,' ',r.colonia) AS direccion, r.telefono,
		DATE_FORMAT(r.fecharecoleccion,'%d/%m/%Y') AS fecha, r.unidad,
		DATE_FORMAT(r.fecharegistro,'%d/%m/%Y') AS fecharegistro,
		r.transmitida, r.realizo
		FROM recoleccion r
		INNER JOIN catalogocliente c ON r.cliente = c.id
		WHERE
		(r.fecharegistro ='".cambiaf_a_mysql($_GET[fecha])."' AND r.sucursal=".$_GET[sucursal].") 
		OR (r.fecharegistro =CURRENT_DATE() AND r.estado<>'REALIZADO' AND r.estado<>'CANCELADO' AND r.sucursal=".$_GET[sucursal].") 
		/*OR (r.fecharegistro>CURRENT_DATE() AND r.estado='NO TRANSMITIDO' AND r.sucursal=".$_GET[sucursal].")*/";
		if($_GET[tipo]=="0"){
			$r = mysql_query($s,$l) or die($s);
			echo mysql_num_rows($r);
						
		}else if($_GET[tipo]=="1"){
			$registros = array();
			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);
		 		while($f = mysql_fetch_object($r)){
					$f->cliente = cambio_texto($f->cliente);
					$f->direccion = cambio_texto($f->direccion);
					$f->colorcan = "";
					$f->colorrep = "";
					
					$sc = mysql_query("SELECT r.motivo, m.descripcion AS desmotivo, m.color FROM recoleccionmotivocancelacion r
					INNER JOIN catalogomotivos m ON r.motivo = m.id
					WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]." AND 
					r.fecharegistro='".cambiaf_a_mysql($_GET[fecha])."'",$l);
					
					$can = mysql_fetch_object($sc);
					$f->colorcan = $can->color;
					
					$f->motivos = "";
					if($f->estado=="CANCELADO"){
						$f->motivos = cambio_texto($can->desmotivo);
					}
					
					$sr = mysql_query("SELECT r.motivo, m.descripcion AS desmotivoreprogramar,
					m.color FROM recoleccionmotivoreprogramacion r
					INNER JOIN recoleccion rc ON r.recoleccion = rc.folio AND rc.sucursal
					INNER JOIN catalogomotivos m ON r.motivo = m.id
					WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]." AND 
					rc.fecharegistro >= '".cambiaf_a_mysql($_GET[fecha])."'",$l);					
					
					$rep = mysql_fetch_object($sr);
					$f->colorrep = $rep->color;
					
					if($rep->desmotivoreprogramar!=""){
						$f->motivos = cambio_texto($rep->desmotivoreprogramar);
					}
					if($rep->desmotivoreprogramar=="" && $f->estado!="CANCELADO"){
						$f->motivos = cambio_texto($f->motivos);
					}
					$recolecciones = ""; $empresariales = ""; $guiasempresariales = "";
					/*$em = mysql_query("SELECT g.id AS guia FROM evaluacionmercancia e
					INNER JOIN guiasventanilla g ON e.folio = g.evaluacion AND e.sucursal = g.idsucursalorigen
					WHERE e.sucursal = ".$_GET[sucursal]." AND 
					g.fecha = ".(($_GET['fecha']=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))." AND
					e.recoleccion<>0",$l) or die($em);*/
					/*$em = mysql_query("SELECT g.guia FROM evaluacionmercancia e
					INNER JOIN (SELECT gv.id AS guia, gv.evaluacion, gv.fecha FROM guiasventanilla gv
					UNION
					SELECT ge.id AS guia, ge.evaluacion, ge.fecha FROM guiasempresariales ge) AS g ON e.folio = g.evaluacion
					WHERE e.sucursal = ".$_GET[sucursal]." AND
					g.fecha = ".(($_GET['fecha']=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))." AND
					(e.recoleccion<>0 OR e.recoleccion IS NOT NULL)",$l) or die($em);*/
						$em =  mysql_query("SELECT gv.id AS guia FROM guiasventanilla gv
						INNER JOIN recolecciondetallefoliorecoleccion r ON gv.recoleccion = r.foliosrecolecciones
						WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]."",$l) or die($em);
					
						if(mysql_num_rows($em)>0){
							while($rowd=mysql_fetch_array($em)){
								$guiasempresariales .=$rowd[0].",";
							}
							$guiasempresariales = substr($guiasempresariales,0,strlen($guiasempresariales)-1);
						}
					
					if($f->estado=="REALIZADO"){
						
						$sr = mysql_query("SELECT foliosrecolecciones FROM recolecciondetallefoliorecoleccion 
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($sr);					
						if(mysql_num_rows($sr)>0){
							while($row=mysql_fetch_array($sr)){
								$recolecciones .=$row[0].",";
							}
							$recolecciones = substr($recolecciones,0,strlen($recolecciones)-1);
						}
							
						$se = mysql_query("SELECT foliosempresariales FROM recolecciondetallefolioempresariales 
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($se);
						if(mysql_num_rows($se)>0){					
							while($rowd=mysql_fetch_array($se)){
								$empresariales .=$rowd[0].",";
							}
							$empresariales = substr($empresariales,0,strlen($empresariales)-1);
						}
					}
					
				if((!empty($recolecciones) || $recolecciones!=" ") && (!empty($empresariales) || $recolecciones!=" ")){							
					$f->folios = $recolecciones."--".$empresariales;
				}
				
				if($f->folios == "--"){
					$f->folios = "";
				}
					$f->guia = cambio_texto($guiasempresariales);	
					$f->folios = cambio_texto($f->folios);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}	
	}else if($_GET[accion]=="ultimorecfecha"){//OBTENER RECOLECCIONES X FECHA
		$s = "SELECT COUNT(*) AS total FROM recoleccion
		WHERE (fecharegistro='".cambiaf_a_mysql($_GET[fecha])."' AND sucursal=".$_GET[sucursal].") 
		OR (fecharegistro=CURRENT_DATE() AND estado<>'REALIZADO' AND estado<>'CANCELADO' AND sucursal=".$_GET[sucursal].")";		
		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);
		$re = $c->total%30; $res = intval($c->total/30) * 30;
		$limit = $res.",".$re;
		
		$s = "SELECT r.folio, r.sucursal, r.estado, r.horario, CONCAT(c.nombre,' ',c.paterno,' ',c.materno) AS cliente,
		CONCAT(r.calle,' #',r.numero,' ',r.colonia) AS direccion, r.telefono,
		DATE_FORMAT(r.fecharecoleccion,'%d/%m/%Y') AS fecha, r.unidad,
		DATE_FORMAT(r.fecharegistro,'%d/%m/%Y') AS fecharegistro,
		r.transmitida, r.realizo
		FROM recoleccion r
		INNER JOIN catalogocliente c ON r.cliente = c.id
		WHERE
		(r.fecharegistro ='".cambiaf_a_mysql($_GET[fecha])."' AND r.sucursal=".$_GET[sucursal].") 
		OR (r.fecharegistro =CURRENT_DATE() AND r.estado<>'REALIZADO' AND r.estado<>'CANCELADO' AND r.sucursal=".$_GET[sucursal].") 
		LIMIT ".$limit."
		/*OR (r.fecharegistro>CURRENT_DATE() AND r.estado='NO TRANSMITIDO' AND r.sucursal=".$_GET[sucursal].")*/";		
			$registros = array();
			$r = mysql_query($s,$l) or die($s);
		 		while($f = mysql_fetch_object($r)){
					$f->cliente = cambio_texto($f->cliente);
					$f->direccion = cambio_texto($f->direccion);
					$f->colorcan = "";
					$f->colorrep = "";
					
					$sc = mysql_query("SELECT r.motivo, m.descripcion AS desmotivo, m.color FROM recoleccionmotivocancelacion r
					INNER JOIN catalogomotivos m ON r.motivo = m.id
					WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]." AND 
					r.fecharegistro='".cambiaf_a_mysql($_GET[fecha])."'",$l);
					
					$can = mysql_fetch_object($sc);
					$f->colorcan = $can->color;
					
					$f->motivos = "";
					if($f->estado=="CANCELADO"){
						$f->motivos = cambio_texto($can->desmotivo);
					}
					
					$sr = mysql_query("SELECT r.motivo, m.descripcion AS desmotivoreprogramar,
					m.color FROM recoleccionmotivoreprogramacion r
					INNER JOIN recoleccion rc ON r.recoleccion = rc.folio AND rc.sucursal
					INNER JOIN catalogomotivos m ON r.motivo = m.id
					WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]." AND 
					rc.fecharegistro >= '".cambiaf_a_mysql($_GET[fecha])."'",$l);					
					
					$rep = mysql_fetch_object($sr);
					$f->colorrep = $rep->color;
					
					if($rep->desmotivoreprogramar!=""){
						$f->motivos = cambio_texto($rep->desmotivoreprogramar);
					}
					if($rep->desmotivoreprogramar=="" && $f->estado!="CANCELADO"){
						$f->motivos = cambio_texto($f->motivos);
					}
					$recolecciones = ""; $empresariales = ""; $guiasempresariales = "";
					$em =  mysql_query("SELECT gv.id AS guia FROM guiasventanilla gv
						INNER JOIN recolecciondetallefoliorecoleccion r ON gv.recoleccion = r.foliosrecolecciones
						WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]."",$l) or die($em);
					/*$em = mysql_query("SELECT g.id AS guia FROM evaluacionmercancia e
					INNER JOIN guiasventanilla g ON e.folio = g.evaluacion AND e.sucursal = g.idsucursalorigen
					WHERE e.sucursal = ".$_GET[sucursal]." AND 
					g.fecha = ".(($_GET['fecha']=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))." AND
					e.recoleccion<>0 ",$l) or die($em);*/
					/*$em = mysql_query("SELECT g.guia FROM evaluacionmercancia e
					INNER JOIN (SELECT gv.id AS guia, gv.evaluacion, gv.fecha FROM guiasventanilla gv
					UNION
					SELECT ge.id AS guia, ge.evaluacion, ge.fecha FROM guiasempresariales ge) AS g ON e.folio = g.evaluacion
					WHERE e.sucursal = ".$_GET[sucursal]." AND
					g.fecha = ".(($_GET['fecha']=="")? "CURRENT_DATE()" : cambiaf_a_mysql($_GET[fecha]))." AND
					(e.recoleccion<>0 OR e.recoleccion IS NOT NULL)",$l) or die($em);*/
						if(mysql_num_rows($em)>0){
							while($rowd=mysql_fetch_array($em)){
								$guiasempresariales .=$rowd[0].",";
							}
							$guiasempresariales = substr($guiasempresariales,0,strlen($guiasempresariales)-1);
						}
					
					if($f->estado=="REALIZADO"){
						
						$sr = mysql_query("SELECT foliosrecolecciones FROM recolecciondetallefoliorecoleccion 
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($sr);					
						if(mysql_num_rows($sr)>0){
							while($row=mysql_fetch_array($sr)){
								$recolecciones .=$row[0].",";
							}
							$recolecciones = substr($recolecciones,0,strlen($recolecciones)-1);
						}
							
						$se = mysql_query("SELECT foliosempresariales FROM recolecciondetallefolioempresariales 
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($se);
						if(mysql_num_rows($se)>0){					
							while($rowd=mysql_fetch_array($se)){
								$empresariales .=$rowd[0].",";
							}
							$empresariales = substr($empresariales,0,strlen($empresariales)-1);
						}
					}
					
				if((!empty($recolecciones) || $recolecciones!=" ") && (!empty($empresariales) || $recolecciones!=" ")){							
					$f->folios = $recolecciones."--".$empresariales;
				}
				
				if($f->folios == "--"){
					$f->folios = "";
				}
					$f->guia = cambio_texto($guiasempresariales);	
					$f->folios = cambio_texto($f->folios);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));			
	
	}else if($_GET[accion]==16){//OBTENER FOLIO Fecha
		$folio = obtenerFolioRecoleccion($_GET['idsucursal'],cambiaf_a_mysql($_GET[fecha]));		
		echo $folio;
		
	}else if($_GET[accion]==17){//OBTENER SUCURSAL PARA RECOLECCION MERCANCIA
		$s = "SELECT descripcion FROM catalogosucursal WHERE id=".$_GET[sucursal];
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$principal = "";
		$f->descripcion = cambio_texto($f->descripcion);
		$principal = str_replace('null','""',json_encode($f));
		
		echo "({principal:$principal})";
	}

	
	
	function obtenerFolioRecoleccion($sucursal,$fecha){
		$l = Conectarse('webpmm');
		$fecha	= (($fecha!="")? "'$fecha'" : CURRENT_DATE);
		$s = "SELECT IFNULL(CONCAT(DATE_FORMAT(".$fecha.",'%m'),'',
		DATE_FORMAT(".$fecha.",'%d'),'-',MAX(SUBSTRING(folio,6,LENGTH(folio)-1)*1) + 1),
		CONCAT(DATE_FORMAT(".$fecha.",'%m'),'',DATE_FORMAT(".$fecha.",'%d'),'-','1')) AS folio
		FROM recoleccion
		WHERE CONCAT(DATE_FORMAT(".$fecha.",'%m'),'',DATE_FORMAT(".$fecha.",'%d'))=SUBSTRING(folio,1,4)
		AND sucursal=".$sucursal."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		return  $f->folio;
	}
?>
