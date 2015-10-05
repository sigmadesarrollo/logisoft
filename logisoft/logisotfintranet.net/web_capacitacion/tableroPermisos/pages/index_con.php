<?
	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$arreids = split(",",$_GET["ids"]);
		$losides = ",";
		
		$s = "select ";
		
		for($i=0;$i<count($arreids);$i++){
			if(($arreids[$i]==2 || $arreids[$i]==13 || $arreids[$i]==30) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "2,13,30,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM propuestaconvenio WHERE estadopropuesta LIKE '%EN AUTORIZACION%') prpeau";
			}
			
			if(($arreids[$i]==3 || $arreids[$i]==14 || $arreids[$i]==35) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "3,14,35,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM propuestaconvenio WHERE estadopropuesta LIKE '%AUTORIZADA%'
									  AND tipoautorizacion NOT LIKE '%,G') prau";
			}
			
			if($arreids[$i]==31 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "31,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM propuestaconvenio WHERE estadopropuesta LIKE 'AUTORIZADA'
									  AND tipoautorizacion NOT LIKE '%,G') prauco";
			}
			
			if($arreids[$i]==36 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "36,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM generacionconvenio WHERE estadoconvenio = 'AUTORIZADO') copeim";
			}
			
			if(($arreids[$i]==15 || $arreids[$i]==37) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "15,37,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM generacionconvenio WHERE estadoconvenio = 'IMPRESO') copeac";
			}
			
			if(($arreids[$i]==16 || $arreids[$i]==38) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "16,38,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM generacionconvenio 
				WHERE sucursal=".$_SESSION[IDSUCURSAL]." AND 
				DATEDIFF(vigencia,CURDATE()) <= (SELECT diasvencimientoconvenio FROM configuradorgeneral)) copeau";
			}
			
			if(($arreids[$i]==41 || $arreids[$i]==49) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "41,49,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM recoleccion 
					 WHERE estado <> 'REPROGRAMADA' AND estado <> 'REALIZADO' AND estado <> 'CANCELADO'
					 AND fecharegistro < CURRENT_DATE AND sucursal = '$_SESSION[IDSUCURSAL]') reat";
			}
			
			if(($arreids[$i]==262) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "262,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM recoleccion		
				WHERE fecharegistro = CURDATE() AND 
				".(($_SESSION[IDSUCURSAL]!=1)? " sucursal = $_SESSION[IDSUCURSAL] AND " :"")."
				realizo = 'NO' AND estado<>'CANCELADO') rere ";
			}
			
			if($arreids[$i]==47 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "47,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(reportedanosfaltante.id) 
				FROM reportedanosfaltante
				INNER JOIN recepcionmercancia ON reportedanosfaltante.recepcion = recepcionmercancia.folio
				WHERE recepcionmercancia.idsucursal = '$_SESSION[IDSUCURSAL]') redafa";
			}
			
			if(($arreids[$i]==48 || $arreids[$i]==93) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "48,93,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT SUM(total)  FROM (
					 (SELECT COUNT(*) total FROM guiasventanilla WHERE estado = 'ALMACEN DESTINO' AND
					 ADDDATE(fecha, INTERVAL (IF(entregaead=0 OR ISNULL(entregaead),entregaead,24)/24) DAY) < CURRENT_DATE
					 AND ocurre = 0 ".(($_SESSION[IDSUCURSAL]!=1)?" and idsucursaldestino=$_SESSION[IDSUCURSAL]":"").")
					 UNION
					 (SELECT COUNT(*) total FROM guiasempresariales WHERE estado = 'ALMACEN DESTINO' AND
					 ADDDATE(fecha, INTERVAL (IF(entregaead=0 OR ISNULL(entregaead),entregaead,24)/24) DAY) < CURRENT_DATE
					 AND ocurre = 0 ".(($_SESSION[IDSUCURSAL]!=1)?" and idsucursaldestino=$_SESSION[IDSUCURSAL]":"").")
				) AS t1) redafa";
			}
			
			if($arreids[$i]==100 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "100,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT SUM(total)  FROM (
						 (SELECT COUNT(*) total FROM guiasventanilla WHERE estado = 'ALMACEN DESTINO' AND
						 ADDDATE(fecha, INTERVAL (IF(entregaocurre=0 OR ISNULL(entregaocurre),entregaocurre,24)/24) DAY) < CURRENT_DATE
						 AND ocurre = 1 ".(($_SESSION[IDSUCURSAL]!=1)?" and idsucursaldestino=$_SESSION[IDSUCURSAL]":"").")
						 UNION
						 (SELECT COUNT(*) total FROM guiasempresariales WHERE estado = 'ALMACEN DESTINO' AND
						 ADDDATE(fecha, INTERVAL (IF(entregaocurre=0 OR ISNULL(entregaocurre),entregaocurre,24)/24) DAY) < CURRENT_DATE
						 AND ocurre = 1 ".(($_SESSION[IDSUCURSAL]!=1)?" and idsucursaldestino=$_SESSION[IDSUCURSAL]":"").")
					) AS t1) enocmaef";
			}
			
			if($arreids[$i]==56 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "56,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(id) FROM reportedanosfaltante
				WHERE faltante = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = ".$_SESSION[IDSUCURSAL]."":"").") guvefa";
			}
			
			if($arreids[$i]==57 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "57,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(id) FROM reportedanosfaltante
				WHERE dano = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = ".$_SESSION[IDSUCURSAL]."":"").") guveda";
			}
			
			if($arreids[$i]==58 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "58,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(DISTINCT(guia)) FROM sobrantes ".(($_SESSION[IDSUCURSAL]!=1)? " WHERE sucursal = ".$_SESSION[IDSUCURSAL]."":"")." ) guveso";
			}
			
			if($arreids[$i]==59 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "59,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT SUM(total) FROM (
					(SELECT COUNT(gv.id) total 
					  FROM guiasventanilla gv  					  
					  WHERE gv.estado NOT IN('ALMACEN DESTINO','CANCELADO','ENTREGADA','AUTORIZACION PARA CANCELAR',
			'EN REPARTO EAD','POR ENTREGAR','AUTORIZACION PARA SUSTITUIR')
					  AND gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]')
					UNION
					(SELECT COUNT(ge.id) total 
					  FROM guiasempresariales ge
					  WHERE ge.estado NOT IN('ALMACEN DESTINO','CANCELADO','ENTREGADA','AUTORIZACION PARA CANCELAR',
			'EN REPARTO EAD','POR ENTREGAR','AUTORIZACION PARA SUSTITUIR')
					  AND ge.idsucursaldestino = '$_SESSION[IDSUCURSAL]')
				) AS t1) gupore";
			}
			
			if($arreids[$i]==269 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "269,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT SUM(total) FROM (
					(SELECT COUNT(gv.id) total 
					  FROM guiasventanilla gv  					  
					  WHERE gv.estado = 'ALMACEN ORIGEN'
					  AND gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]')
					UNION
					(SELECT COUNT(ge.id) total 
					  FROM guiasempresariales ge
					  WHERE ge.estado = 'ALMACEN ORIGEN'
					  AND ge.idsucursaldestino = '$_SESSION[IDSUCURSAL]')
				) AS t1) mereem";
			}
			
			if($arreids[$i]==63 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "63,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT SUM(total) FROM (
					(SELECT COUNT(*) total FROM guiasventanilla WHERE estado = 'ALMACEN ORIGEN' 
					".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").")
					UNION
					(SELECT COUNT(*) total FROM guiasempresariales WHERE estado = 'ALMACEN ORIGEN' 
					".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")." )
				) AS t1) gunoem";
			}
			
			if($arreids[$i]==64 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "64,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM (
						(SELECT guiasventanilla.id total 
						FROM guiasventanilla 
						INNER JOIN guiaventanilla_unidades ON guiasventanilla.id = guiaventanilla_unidades.idguia
						WHERE guiasventanilla.estado = 'ALMACEN TRASBORDO' AND guiaventanilla_unidades.ubicacion = '$_SESSION[IDSUCURSAL]'
						GROUP BY guiasventanilla.id)
						UNION
						(SELECT guiasempresariales.id total 
						FROM guiasempresariales 
						INNER JOIN guiasempresariales_unidades ON guiasempresariales.id = guiasempresariales_unidades.idguia
						WHERE guiasempresariales.estado = 'ALMACEN TRASBORDO' AND guiasempresariales_unidades.ubicacion = '$_SESSION[IDSUCURSAL]'
						GROUP BY guiasempresariales.id)
					) AS t1) gupotr";
			}
			
			if(($arreids[$i]==86 || $arreids[$i]==230) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "86,230,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM capturagastoscajachica 
				WHERE autorizado = 'N' AND (keyfoliosgastoscajachica = '' 
				OR ISNULL(keyfoliosgastoscajachica) 
				OR keyfoliosgastoscajachica = '0') AND keysucursal = '$_SESSION[IDSUCURSAL]') gapeau";
			}
			
			if($arreids[$i]==92 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "86,230,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM repartomercanciaead WHERE liquidado = 0 ".(($_SESSION[IDSUCURSAL]!=1)?" AND sucursal = ".$_SESSION[IDSUCURSAL]."":"").") gupoli";
				#$s .= "(SELECT COUNT(*)	FROM facturacion WHERE estadocobranza = 'C' AND fecha = CURDATE() 
				#".(($_SESSION[IDSUCURSAL]!=1)?" AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"").") gupoli";			
			}
			
			if($arreids[$i]==101 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "101,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT SUM(total) FROM (
					(SELECT COUNT(*) total FROM guiasventanilla WHERE estado = 'POR ENTREGAR' AND idsucursaldestino = '$_SESSION[IDSUCURSAL]' )
					UNION
					(SELECT COUNT(*) total FROM guiasempresariales WHERE estado = 'POR ENTREGAR' AND idsucursaldestino = '$_SESSION[IDSUCURSAL]' )
				) AS t1) gupoenoc";
			}
			
			if(($arreids[$i]==103 || $arreids[$i]==242) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "103,242,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM guiasventanilla WHERE estado = 'AUTORIZACION PARA CANCELAR' AND idsucursalorigen = '$_SESSION[IDSUCURSAL]' ) guloaupaca";
			}
			
			#NO APARECEE
			if(($arreids[$i]==105 || $arreids[$i]==243) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "105,243,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT count(*) FROM guiasventanilla_cs WHERE estado = 'SUSTITUCION'
									 ".(($_SESSION[IDSUCURSAL]!=1)?" AND sucursal = 'ninguna'":"")."
									 ) gufoaupaca";
			}
			
			if(($arreids[$i]==106 || $arreids[$i]==244) && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "106,244,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM guiasventanilla_cs WHERE estado = 'AUTORIZADA PARA SUSTITUIR' AND sucursal = '$_SESSION[IDSUCURSAL]') gufoaupasu";
			}
			
			if($arreids[$i]==108 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "108,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(t1.id) FROM (
					SELECT gv.id 
					FROM guiasventanilla AS gv
					INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
					WHERE gv.estado = 'CANCELADO' AND gvu.ubicacion = '$_SESSION[IDSUCURSAL]'
					GROUP BY gv.id
				) AS t1) guca";
			}
			
			if($arreids[$i]==111 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "111,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM solicitudcredito WHERE estado = 'EN AUTORIZACION') socrpeau";
			}
			
			if($arreids[$i]==112 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "112,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*)  FROM solicitudcredito WHERE estado = 'AUTORIZADO') socrpeac";
			}
			
			if($arreids[$i]==79 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "79,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM facturacion WHERE facturaestado='CANCELADO' AND idsucursal = '$_SESSION[IDSUCURSAL]') faca";
			}
			
			if($arreids[$i]==80 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "80,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM guiasventanilla AS gv 
				INNER JOIN pagoguias AS pg ON gv.id = pg.guia
				WHERE IF(gv.tipoflete = 1 AND gv.condicionpago = 1, gv.estado = 'ALMACEN DESTINO', gv.tipoflete = 0 AND gv.condicionpago = 1 AND gv.estado <> 'CANCELADO') AND
				(ISNULL(gv.factura) OR gv.factura='') AND pg.sucursalacobrar = '$_SESSION[IDSUCURSAL]') gupefa";
			}
			
			if($arreids[$i]==128 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "128,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM evaluacionmercancia 
				WHERE estado = 'GUARDADO' AND sucursal = '$_SESSION[IDSUCURSAL]' AND guiaempresarial = '') evpegeguve";
			}
			
			if($arreids[$i]==246 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "246,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM evaluacionmercancia 
				WHERE estado = 'GUARDADO' AND sucursal = '$_SESSION[IDSUCURSAL]' AND guiaempresarial <> '') evpegeguem";
			}
			
			if($arreids[$i]==46 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "46,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) AS total FROM solicitudtelefonica 
				WHERE estado='POR SOLUCIONAR' AND sucursal='$_SESSION[IDSUCURSAL]') danofaltante";
			}
			
			if($arreids[$i]==138 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "138,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*)
				FROM facturacion AS f
				INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
				WHERE f.estadocobranza = 'N' AND f.idsucursal='$_SESSION[IDSUCURSAL]' AND 
					(CASE DAYOFWEEK(CURRENT_DATE)
						WHEN 2 THEN sc.lunesrevision=1
						WHEN 3 THEN sc.martesrevision=1
						WHEN 4 THEN sc.miercolesrevision=1
						WHEN 5 THEN sc.juevesrevision=1
						WHEN 6 THEN sc.viernesrevision=1
						WHEN 7 THEN sc.sabadorevision=1
					END OR sc.semanarevision = 1)) fare";
			}
			
			if($arreids[$i]==139 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "139,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(f.folio)
						FROM facturacion f
						INNER JOIN solicitudcredito sc ON f.cliente = sc.cliente
						WHERE
						(CASE DAYOFWEEK(CURRENT_DATE)
							WHEN 2 THEN sc.lunespago=1
							WHEN 3 THEN sc.martespago=1
							WHEN 4 THEN sc.miercolespago=1
							WHEN 5 THEN sc.juevespago=1
							WHEN 6 THEN sc.viernespago=1
							WHEN 7 THEN sc.sabadopago=1
						END OR sc.semanapago = 1) AND f.credito = 'SI' ".(($_SESSION[IDSUCURSAL]!=1)? " AND f.idsucursal = $_SESSION[IDSUCURSAL]" : "")." 
						AND f.estadocobranza<>'C') faco";
			}
			
			if($arreids[$i]==131 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "131,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM solicitudguiasempresarialesnw
				WHERE STATUS<>'CANCELADA' AND STATUS<>'AUTORIZADA' AND STATUS<>'FOLIADO') AS sogupeasfo";
			}
			
			if($arreids[$i]==282 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "282,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM historialdetraspaso
				WHERE aceptada = 0 AND sucursalacepta = ".$_SESSION[IDSUCURSAL].") AS histraspaso";
			}
			
			if($arreids[$i]==132 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "132,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM solicitudguiasempresarialesnw
				Where status<>'CANCELADA' AND status<>'' AND status<>'FOLIADO') AS soguempeat";
			}
			
			if($arreids[$i]==143 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "143,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM facturacion f
				INNER JOIN guiasventanilla gv ON f.folio=gv.factura
				INNER JOIN catalogocliente cc ON f.cliente=cc.id
				INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
				WHERE (DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)))>60
				AND f.estado<>'CANCELADO' 
				".(($_SESSION[IDSUCURSAL]!="1")? " AND f.idsucursal=".$_SESSION[IDSUCURSAL]."" : "")."
				UNION
				SELECT COUNT(*) FROM facturacion f
				INNER JOIN guiasempresariales gv ON f.folio=gv.factura
				INNER JOIN catalogocliente cc ON f.cliente=cc.id
				INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
				WHERE (DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)))>60 
				AND f.estado='CANCELADO' 
				".(($_SESSION[IDSUCURSAL]!="1")? " AND f.idsucursal=".$_SESSION[IDSUCURSAL]."" : "").") AS dias60";
			}
			
			if($arreids[$i]==296 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "296,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM entregasespecialesead
				where estado=1 ".(($_SESSION[IDSUCURSAL]!="1")? " AND sucursal=".$_SESSION[IDSUCURSAL]."" : "").") AS entesp";
			}
			
			if($arreids[$i]==302 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "302,";
				$s .= ($s != "select ")?",":"";
				$s .= "(SELECT COUNT(*) FROM relacioncobranza rc
				WHERE rc.fecharelacion = CURDATE() ".(($_SESSION[IDSUCURSAL]!="1")? " AND rc.sucursal=".$_SESSION[IDSUCURSAL]."" : "")." 
				AND NOT EXISTS (SELECT * FROM liquidacioncobranza lq WHERE rc.folio=lq.foliocobranza AND rc.sucursal = lq.sucursal AND lq.estado='LIQUIDADO')) AS relpen";
			}
			
			if($arreids[$i]==270 && !ereg(",".$arreids[$i].",",$losides)){
				$losides .= "270,";
				$s .= ($s != "select ")?",":"";
				$s .= "(select count(*) from guiasventanillaclientes where estado = 'GUARDADA' AND idsucursalorigen = $_SESSION[IDSUCURSAL]) as guemwepeev";
			}
		}
		//$s .= " 1";
		
		//$s = "select * from lasalertas WHERE sucursal= '$_SESSION[IDSUCURSAL]'";
		$s = "select * from lasalertas ";
		
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$res = "[";
			   for($i=0;$i<count($arreids);$i++){
					if(($arreids[$i]==2 || $arreids[$i]==13 || $arreids[$i]==30)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->prpeau'}";
					}
					
					if(($arreids[$i]==3 || $arreids[$i]==14 || $arreids[$i]==35)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->prau'}";
					}
					
					if(($arreids[$i]==31)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->prauco'}";
					}
					
					if($arreids[$i]==36){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->copeim'}";
					}
					
					if(($arreids[$i]==15 || $arreids[$i]==37)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->copeac'}";
					}
					
					if(($arreids[$i]==16 || $arreids[$i]==38)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->copeau'}";
					}
					
					if(($arreids[$i]==41 || $arreids[$i]==49)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->reat'}";
					}
					
					if($arreids[$i]==262){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->rere'}";
					}
					
					if($arreids[$i]==47){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->redafa'}";
					}
					
					if(($arreids[$i]==48 || $arreids[$i]==93)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->redafa'}";
					}
					
					if($arreids[$i]==100){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->enocmaef'}";
					}
					
					if($arreids[$i]==56){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->guvefa'}";
					}
					
					if($arreids[$i]==57){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->guveda'}";
					}
					
					if($arreids[$i]==58){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->guveso'}";
					}
					
					if($arreids[$i]==59){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->gupore'}";
					}
					
					if($arreids[$i]==269){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->mereem'}";
					}
					
					if($arreids[$i]==63){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->gunoem'}";
					}
					
					if($arreids[$i]==64){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->gupotr'}";
					}
					
					if(($arreids[$i]==86 || $arreids[$i]==230)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->gapeau'}";
					}
					
					if($arreids[$i]==92){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->gupoli'}";
					}
					
					if($arreids[$i]==101){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->gupoenoc'}";
					}
					
					if(($arreids[$i]==103 || $arreids[$i]==242)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->guloaupaca'}";
					}
					
					if(($arreids[$i]==105 || $arreids[$i]==243)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->gufoaupaca'}";
					}
					
					if(($arreids[$i]==106 || $arreids[$i]==244)){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->gufoaupasu'}";
					}
					
					if($arreids[$i]==108){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->guca'}";
					}
					
					if($arreids[$i]==111){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->socrpeau'}";
					}
					
					if($arreids[$i]==112){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->socrpeac'}";
					}
					
					if($arreids[$i]==79){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->faca'}";
					}
					
					if($arreids[$i]==80){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->gupefa'}";
					}
					
					if($arreids[$i]==128){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->evpegeguve'}";
					}
					
					if($arreids[$i]==246){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->evpegeguem'}";
					}
					
					if($arreids[$i]==138){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->fare'}";
					}
					
					if($arreids[$i]==139){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->faco'}";
					}
					
					if($arreids[$i]==131){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->sogupeasfo'}";
					}
					
					if($arreids[$i]==132){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->soguempeat'}";
					}
					
					if($arreids[$i]==143){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->dias60'}";
					}
					
					if($arreids[$i]==282){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->histraspaso'}";
					}
					
					if($arreids[$i]==296){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->entesp'}";
					}
					
					if($arreids[$i]==302){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->relpen'}";
					}
					
					if($arreids[$i]==270){
						$res .= ($res!="[")?",":"";
						$res .= "{'campoid':'con".$arreids[$i]."','valor':'$f->guemwepeev'}";
					}
				}
		$res .= "]";
						
		echo $res;		
	}
	
	if($_GET[accion]==2){
		$_SESSION[IDSUCURSAL]		= "";
		$_SESSION[NOMBREUSUARIO]	= "";
		$_SESSION[IDUSUARIO]		= ""; 
		
		echo "perfecto";
	}
			
	if($_GET[accion]==3){
		$s = "SELECT 'V' tipo FROM guiasventanilla WHERE id = '$_GET[folio]'
		UNION
		SELECT 'E' tipo FROM guiasempresariales WHERE id = '$_GET[folio]'"; 
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo $f->tipo.",".$_GET[folio];
	}	
?>