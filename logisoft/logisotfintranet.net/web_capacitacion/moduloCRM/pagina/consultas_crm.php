<?	session_start();

	require_once('../../Conectar.php');

	$l = Conectarse('webpmm');

	

	if($_GET[accion]=="0"){

		$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS cliente FROM catalogocliente WHERE id=".$_GET[cliente];

		$r = mysql_query($s,$l) or die($s);

		$registros = array();

		if(mysql_num_rows($r)>0){

			while($f = mysql_fetch_object($r)){

				$f->cliente	= cambio_texto($f->cliente);

				$registros[] = $f;

			}		

			echo str_replace('null','""',json_encode($registros));

		}else{

			echo "no encontro";

		}

		

	}else if($_GET[accion]==1){//OBTENER CLIENTE

		$s = "SELECT nombre,paterno,materno,rfc,email,celular,web FROM catalogocliente 

		WHERE id=".$_GET[cliente];

		$r = mysql_query($s,$l) or die($s);

		$registros = array();

		$f = mysql_fetch_object($r);

		

		$s = "SELECT CONCAT(calle,' #',numero,' COL.',colonia) AS direccion, poblacion,

		estado, cp, facturacion FROM direccion WHERE codigo=".$_GET[cliente];

		$t = mysql_query($s,$l) or die($s);

			while($d = mysql_fetch_object($t)){	

				if($d->facturacion=="SI"){

					$d->direccion = cambio_texto($d->direccion);

					$d->poblacion = cambio_texto($d->poblacion);

					$d->estado = cambio_texto($d->estado);

					$d->cp = cambio_texto($d->cp);

					break;

				}else if($d->facturacion=="NO"){					

					$d->direccion = cambio_texto($d->direccion);

					$d->poblacion = cambio_texto($d->poblacion);

					$d->estado = cambio_texto($d->estado);

					$d->cp = cambio_texto($d->cp);

					break;

				}

			}

		$f->nombre 			= cambio_texto($f->nombre);

		$f->paterno 		= cambio_texto($f->paterno);

		$f->materno 		= cambio_texto($f->materno);

		$f->rfc 			= cambio_texto($f->rfc);

		$f->email 			= cambio_texto($f->email);

		$f->celular 		= cambio_texto($f->celular);

		$f->web 			= cambio_texto($f->web);		

		$f->direccion 		= cambio_texto($d->direccion);

		$f->poblacion 		= cambio_texto($d->poblacion);

		$f->estado 			= cambio_texto($d->estado);

		$f->cp 				= cambio_texto($d->cp);

		$registros[] 		= $f;		

		echo str_replace('null','""',json_encode($registros));

		

	}else if($_GET[accion]==2){//OBTENER HISTORIAL GUIAS VENTANILLA		

		$s = "SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.total,

		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,

		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario FROM guiasventanilla gv

		INNER JOIN catalogocliente re ON gv.idremitente = re.id

		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id

		WHERE gv.idremitente = ".$_GET[cliente]." AND

		gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		if($_GET[tipo]=="0"){

			$r = mysql_query($s,$l) or die($s);

			echo mysql_num_rows($r);

		}else if($_GET[tipo]=="1"){

			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);

			$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->remitente = cambio_texto($f->remitente);

				$f->destinatario = cambio_texto($f->destinatario);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

		}

		

	}else if($_GET[accion]==3){//OBTENER HISTORIAL GUIAS VENTANILLA

		$s = "SELECT COUNT(*) AS total FROM guiasventanilla		

		WHERE idremitente = ".$_GET[cliente]." AND

		fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);

		$re = $c->total%30; $res = intval($c->total/30) * 30;

		$limit = $res.",".$re;

		$s = "SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.total,

		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,

		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario FROM guiasventanilla gv

		INNER JOIN catalogocliente re ON gv.idremitente = re.id

		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id

		WHERE gv.idremitente = ".$_GET[cliente]." AND

		gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'

		LIMIT ".$limit."";

		$r = mysql_query($s,$l) or die($s);

		$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->remitente = cambio_texto($f->remitente);

				$f->destinatario = cambio_texto($f->destinatario);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

		

	}else if($_GET[accion]==4){//OBTENER HISTORIAL GUIAS EMPRESARIALES	

		$s = "SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.total,

		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,

		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario FROM guiasempresariales gv

		INNER JOIN catalogocliente re ON gv.idremitente = re.id

		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id

		WHERE gv.idremitente = ".$_GET[cliente]." AND

		gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		if($_GET[tipo]=="0"){

			$r = mysql_query($s,$l) or die($s);

			echo mysql_num_rows($r);

		}else if($_GET[tipo]=="1"){

			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);

			$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->remitente = cambio_texto($f->remitente);

				$f->destinatario = cambio_texto($f->destinatario);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

		}

		

	}else if($_GET[accion]==5){//OBTENER HISTORIAL GUIAS EMPRESARIALES
		$s = "SELECT COUNT(*) AS total FROM guiasempresariales
		WHERE idremitente = ".$_GET[cliente]." AND
		fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);
		$re = $c->total%30; $res = intval($c->total/30) * 30;
		$limit = $res.",".$re;

		$s = "SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.total,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario FROM guiasempresariales gv
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.idremitente = ".$_GET[cliente]." AND
		gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		LIMIT ".$limit."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			while($f = mysql_fetch_object($r)){

				$f->remitente = cambio_texto($f->remitente);

				$f->destinatario = cambio_texto($f->destinatario);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

			

	}else if($_GET[accion]==6){//OBTENER HISTORIAL GUIAS VENTANILLA		

		$s = "SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.total,

		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,

		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario FROM guiasventanilla gv

		INNER JOIN catalogocliente re ON gv.idremitente = re.id

		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id

		WHERE gv.idremitente = ".$_GET[cliente]." AND

		gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		if($_GET[tipo]=="0"){

			$r = mysql_query($s,$l) or die($s);

			echo mysql_num_rows($r);

		}else if($_GET[tipo]=="1"){

			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);

			$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->remitente = cambio_texto($f->remitente);

				$f->destinatario = cambio_texto($f->destinatario);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

		}

		

	}else if($_GET[accion]==7){//OBTENER HISTORIAL GUIAS VENTANILLA

		$s = "SELECT COUNT(*) AS total FROM guiasventanilla		

		WHERE idremitente = ".$_GET[cliente]." AND

		fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);

		$re = $c->total%30; $res = intval($c->total/30) * 30;

		$limit = $res.",".$re;

		$s = "SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.total,

		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,

		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario FROM guiasventanilla gv

		INNER JOIN catalogocliente re ON gv.idremitente = re.id

		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id

		WHERE gv.idremitente = ".$_GET[cliente]." AND

		gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'

		LIMIT ".$limit."";

		$r = mysql_query($s,$l) or die($s);

		$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->remitente = cambio_texto($f->remitente);

				$f->destinatario = cambio_texto($f->destinatario);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

		

	}else if($_GET[accion]==8){//HISTORIAL DE ENVIOS

		$s = "SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,

		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,

		cs.descripcion AS origen, 

		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,

		cd.descripcion AS destino,

		IF(gv.condicionpago=0,'CONTADO','CREDITO') AS condpago,

		IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega,gv.total

		FROM guiasventanilla gv

		INNER JOIN catalogocliente re ON gv.idremitente = re.id

		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id

		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id

		INNER JOIN catalogodestino cd ON gv.iddestino = cd.id

		WHERE gv.idremitente = ".$_GET[cliente]." AND

		gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'

		UNION

		SELECT ge.id AS guia,DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,

		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,

		cs.descripcion AS origen, 

		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,

		cd.descripcion AS destino, ge.tipopago AS condpago,

		IF(ge.ocurre=0,'EAD','OCURRE') AS tipoentrega, ge.total

		FROM guiasempresariales ge

		INNER JOIN catalogocliente re ON ge.idremitente = re.id

		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id

		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id

		INNER JOIN catalogodestino cd ON ge.iddestino = cd.id

		WHERE ge.idremitente = ".$_GET[cliente]." AND

		ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		if($_GET[tipo]=="0"){

			$r = mysql_query($s,$l) or die($s);

			echo mysql_num_rows($r);

		}else if($_GET[tipo]=="1"){

			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);

			$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->remitente = cambio_texto($f->remitente);

				$f->destinatario = cambio_texto($f->destinatario);

				$f->origen = cambio_texto($f->origen);

				$f->destino = cambio_texto($f->destino);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

		}

	}else if($_GET[accion]==9){//HISTORIAL DE ENVIOS ULTIMO

		$s = "SELECT SUM(total) AS total FROM(

		SELECT COUNT(*) AS total FROM guiasventanilla gv

		WHERE gv.idremitente = ".$_GET[cliente]." AND

		gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'

		UNION

		SELECT COUNT(*) AS total FROM guiasempresariales ge

		WHERE ge.idremitente = ".$_GET[cliente]." AND

		ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."') t";

		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);

		$re = $c->total%30; $res = intval($c->total/30) * 30;

		$limit = $res.",".$re;

		

		$s = "SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,

		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,

		cs.descripcion AS origen, 

		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,

		cd.descripcion AS destino,

		IF(gv.condicionpago=0,'CONTADO','CREDITO') AS condpago,

		IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega,gv.total

		FROM guiasventanilla gv

		INNER JOIN catalogocliente re ON gv.idremitente = re.id

		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id

		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id

		INNER JOIN catalogodestino cd ON gv.iddestino = cd.id

		WHERE gv.idremitente = ".$_GET[cliente]." AND

		gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'

		UNION

		SELECT ge.id AS guia,DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,

		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,

		cs.descripcion AS origen, 

		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,

		cd.descripcion AS destino, ge.tipopago AS condpago,

		IF(ge.ocurre=0,'EAD','OCURRE') AS tipoentrega, ge.total

		FROM guiasempresariales ge

		INNER JOIN catalogocliente re ON ge.idremitente = re.id

		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id

		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id

		INNER JOIN catalogodestino cd ON ge.iddestino = cd.id

		WHERE ge.idremitente = ".$_GET[cliente]." AND

		ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'

		LIMIT ".$limit."";

		$r = mysql_query($s,$l) or die($s);

		$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->remitente = cambio_texto($f->remitente);

				$f->destinatario = cambio_texto($f->destinatario);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

			

	}else if($_GET[accion]==10){//HISTORIAL DE RECOLECCION

		$s = "SELECT r.folio, r.estado, IFNULL(DATE_FORMAT(r.fecharecoleccion,'%d/%m/%Y'),'') AS fecha,

		cs.descripcion AS origen, cd.descripcion AS destino,CONCAT(r.calle,' #',r.numero,' ',r.colonia) AS direccion,

		FROM recoleccion r

		INNER JOIN catalogosucursal cs ON r.origen = cs.id

		INNER JOIN catalogodestino cd ON r.destino = cd.id

		WHERE r.estado = 'REALIZADO' AND r.cliente = ".$_GET[cliente]." AND

		r.fecharecoleccion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		if($_GET[tipo]=="0"){

			$r = mysql_query($s,$l) or die($s);

			echo mysql_num_rows($r);

		}else if($_GET[tipo]=="1"){

			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);

			$registros = array();

			while($f = mysql_fetch_object($r)){

			

				if($f->estado=="REALIZADO"){

					$sr = mysql_query("SELECT foliosrecolecciones FROM recolecciondetallefoliorecoleccion

					WHERE recoleccion='".$f->folio."'",$l) or die($sr);

					$recolecciones = ""; $empresariales = ""; 

					if(mysql_num_rows($sr)>0){

						while($row=mysql_fetch_array($sr)){

							$recolecciones .=$row[0].",";

						}

						$recolecciones = substr($recolecciones,0,strlen($recolecciones)-1);

					}

					

					$se = mysql_query("SELECT foliosempresariales FROM recolecciondetallefolioempresariales

					WHERE recoleccion='".$f->folio."'",$l) or die($se);

					if(mysql_num_rows($se)>0){

						while($rowd=mysql_fetch_array($se)){

							$empresariales .=$rowd[0].",";

						}

						$empresariales = substr($empresariales,0,strlen($empresariales)-1);

					}

					

					if($recolecciones!="" && $empresariales!=""){

						$f->folios = $recolecciones."--".$empresariales;	

					}

					

				}					

				$f->folios = cambio_texto($f->folios);			

				$f->origen = cambio_texto($f->origen);

				$f->destino = cambio_texto($f->destino);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

		}

	}else if($_GET[accion] == 11){//HISTORIAL DE RECOLECCION ULTIMO

		$s = "SELECT COUNT(*) AS total FROM recoleccion 

		WHERE estado = 'REALIZADO' AND cliente = ".$_GET[cliente]." AND

		fecharecoleccion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);

		$re = $c->total%30; $res = intval($c->total/30) * 30;

		$limit = $res.",".$re;

		

		$s = "SELECT r.folio, r.estado, IFNULL(DATE_FORMAT(r.fecharecoleccion,'%d/%m/%Y'),'') AS fecha,

		cs.descripcion AS origen, cd.descripcion AS destino,CONCAT(r.calle,' #',r.numero,' ',r.colonia) AS direccion,

		FROM recoleccion r

		INNER JOIN catalogosucursal cs ON r.origen = cs.id

		INNER JOIN catalogodestino cd ON r.destino = cd.id

		WHERE r.estado = 'REALIZADO' AND r.cliente = ".$_GET[cliente]." AND

		r.fecharecoleccion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'

		LIMIT ".$limit."";

		$r = mysql_query($s,$l) or die($s);

		$registros = array();

			while($f = mysql_fetch_object($r)){

				if($f->estado=="REALIZADO"){

					$sr = mysql_query("SELECT foliosrecolecciones FROM recolecciondetallefoliorecoleccion

					WHERE recoleccion='".$f->folio."'",$l) or die($sr);

					$recolecciones = ""; $empresariales = ""; 

					if(mysql_num_rows($sr)>0){

						while($row=mysql_fetch_array($sr)){

							$recolecciones .=$row[0].",";

						}

						$recolecciones = substr($recolecciones,0,strlen($recolecciones)-1);

					}

					

					$se = mysql_query("SELECT foliosempresariales FROM recolecciondetallefolioempresariales

					WHERE recoleccion='".$f->folio."'",$l) or die($se);

					if(mysql_num_rows($se)>0){

						while($rowd=mysql_fetch_array($se)){

							$empresariales .=$rowd[0].",";

						}

						$empresariales = substr($empresariales,0,strlen($empresariales)-1);

					}

					

					if($recolecciones!="" && $empresariales!=""){

						$f->folios = $recolecciones."--".$empresariales;	

					}

					

				}					

				$f->folios = cambio_texto($f->folios);

				$f->origen = cambio_texto($f->origen);

				$f->destino = cambio_texto($f->destino);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

			

	}else if($_GET[accion] == 12){//HISTORIAL SOLICITUD GUIAS EMPRESARIALES

		$s = "SELECT id, condicionpago, cantidad, CONCAT_WS('-',desdefolio, hastafolio) AS folios, total 

		FROM solicitudguiasempresariales

		WHERE idcliente = ".$_GET[cliente]." AND

		fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		if($_GET[tipo]=="0"){

			$r = mysql_query($s,$l) or die($s);

			echo mysql_num_rows($r);

		}else if($_GET[tipo]=="1"){

			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);

			$registros = array();

			while($f = mysql_fetch_object($r)){

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

		}

	}else if($_GET[accion] == 13){//HISTORIAL SOLICITUD GUIAS EMPRESARIALES (ULTIMO)

		$s = "SELECT COUNT(*) AS total FROM solicitudguiasempresariales 

		WHERE idcliente = ".$_GET[cliente]." AND

		fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);

		$re = $c->total%30; $res = intval($c->total/30) * 30;

		$limit = $res.",".$re;

		

		$s = "SELECT id, condicionpago, cantidad, CONCAT_WS('-',desdefolio, hastafolio) AS folios, total 

		FROM solicitudguiasempresariales

		WHERE idcliente = ".$_GET[cliente]." AND

		fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";

		$r = mysql_query($s." LIMIT ".$limit."",$l) or die($s);

		$registros = array();

			while($f = mysql_fetch_object($r)){

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

			

	}else if($_GET[accion] == 14){//ESTADO DE CUENTA		

		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,sucursal,referenciacargo,

		referenciaabono,cargo,abono,saldo,descripcion FROM( 

		/*GUIAS EMPRESARIALES*/

		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo,

		0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM guiasempresariales gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura)

		UNION

		/*GUIAS EMPRESARIALES*/

		SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono,

		f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM facturacion f

		INNER JOIN guiasempresariales gv ON f.folio=gv.factura

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		WHERE gv.factura<>0

		GROUP BY f.folio

		UNION

		/*GUIAS VENTANILLA*/

		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 

		0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM guiasventanilla gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura)

		UNION

		/*GUIAS VENTANILLA*/

		SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, 

		f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM facturacion f

		INNER JOIN guiasventanilla gv ON f.folio=gv.factura

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		WHERE gv.factura<>0

		GROUP BY f.folio	

		UNION

		/*ABONOS CLIENTE*/

		SELECT a.fecharegistro AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,

		CONCAT('ABONO-','',a.folio) AS referenciaabono, 0 AS cargo, SUM(a.abonar) AS abono,0 AS saldo,

		CONCAT('PAGO',' ',IF(a.efectivo>0,'EFECTIVO',' '),',',IF(a.banco>0,'BANCO',' '),',',IF(a.cheque>0,'CHEQUE',' '),',',IF(a.tarjeta>0,'TARJETA',' '),',',IF(a.transferencia>0,'TRANSFERENCIA',' '))AS descripcion, 

		a.idcliente AS cliente FROM abonodecliente a

		INNER JOIN catalogosucursal cs ON a.idsucursal=cs.id 

		GROUP BY a.factura

		UNION

		/*ABONOS GUIAS A CONTADO*/

		SELECT fp.fecha AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',fp.guia) AS referenciaabono,

		0 AS cargo, SUM(fp.total) AS abono,0 AS saldo,

		CONCAT('PAGO',' ',IF(fp.efectivo>0,'EFECTIVO',' '),',',IF(fp.banco>0,'BANCO',' '),',',IF(fp.cheque>0,'CHEQUE',' '),',',IF(fp.tarjeta>0,'TARJETA',' '),',',IF(fp.transferencia>0,'TRANSFERENCIA',' '))AS descripcion,

		pg.cliente AS cliente FROM formapago fp

		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 

		INNER JOIN pagoguias pg ON fp.guia=pg.guia

		WHERE fp.procedencia='G'

		GROUP BY fp.guia

		UNION

		/*LIQUIDACION COBRANZA*/

		SELECT lc.fechaliquidacion AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,

		CONCAT('ABONO-','',lc.folio) AS referenciaabono, 0 AS cargo, SUM(lcd.importe) AS abono,0 AS saldo,

		CONCAT('PAGO',' ',IF(lcd.efectivo>0,'EFECTIVO',' '),',',IF(lcd.banco>0,'BANCO',' '),',',IF(lcd.cheque>0,'CHEQUE',' '),',',IF(lcd.tarjeta>0,'TARJETA',' '),',',IF(lcd.transferencia>0,'TRANSFERENCIA',' '))AS descripcion, 

		lcd.cliente FROM liquidacioncobranza lc

		INNER JOIN liquidacioncobranzadetalle lcd ON lc.folio=lcd.folioliquidacion

		INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id

		WHERE lcd.cobrar='SI'

		GROUP BY lcd.factura

		UNION

		/*CANCELACION GUIAS VENTANILLA*/

		SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,

		0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion,

		pg.cliente AS cliente FROM guiasventanilla gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura) AND gv.estado='CANCELADO'

		UNION

		/*CANCELACION GUIAS EMPRESARIALES*/

		SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,

		0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion, 

		pg.cliente AS cliente FROM guiasempresariales gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura) AND gv.estado='CANCELADO'

		)Tabla

		fecha BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 

		AND cliente=".$_GET[cliente]." ORDER BY fecha";

		if($_GET[tipo]=="0"){

			$r = mysql_query($s,$l) or die($s);

			echo mysql_num_rows($r);

		}else if($_GET[tipo]=="1"){

			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);

			$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->sucursal 		= cambio_texto($f->sucursal);

				$f->referenciacargo = cambio_texto($f->referenciacargo);

				$f->referenciaabono = cambio_texto($f->referenciaabono);

				$f->descripcion		= cambio_texto($f->descripcion);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

		}

	}else if($_GET[accion] == 15){

		$s = "SELECT COUNT(*) AS total FROM( 

		/*GUIAS EMPRESARIALES*/

		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo,

		0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM guiasempresariales gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura)

		UNION

		/*GUIAS EMPRESARIALES*/

		SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono,

		f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM facturacion f

		INNER JOIN guiasempresariales gv ON f.folio=gv.factura

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		WHERE gv.factura<>0

		GROUP BY f.folio

		UNION

		/*GUIAS VENTANILLA*/

		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 

		0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM guiasventanilla gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura)

		UNION

		/*GUIAS VENTANILLA*/

		SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, 

		f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM facturacion f

		INNER JOIN guiasventanilla gv ON f.folio=gv.factura

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		WHERE gv.factura<>0

		GROUP BY f.folio	

		UNION

		/*ABONOS CLIENTE*/

		SELECT a.fecharegistro AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,

		CONCAT('ABONO-','',a.folio) AS referenciaabono, 0 AS cargo, SUM(a.abonar) AS abono,0 AS saldo,

		CONCAT('PAGO',' ',IF(a.efectivo>0,'EFECTIVO',' '),',',IF(a.banco>0,'BANCO',' '),',',IF(a.cheque>0,'CHEQUE',' '),',',IF(a.tarjeta>0,'TARJETA',' '),',',IF(a.transferencia>0,'TRANSFERENCIA',' '))AS descripcion, 

		a.idcliente AS cliente FROM abonodecliente a

		INNER JOIN catalogosucursal cs ON a.idsucursal=cs.id 

		GROUP BY a.factura

		UNION

		/*ABONOS GUIAS A CONTADO*/

		SELECT fp.fecha AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',fp.guia) AS referenciaabono,

		0 AS cargo, SUM(fp.total) AS abono,0 AS saldo,

		CONCAT('PAGO',' ',IF(fp.efectivo>0,'EFECTIVO',' '),',',IF(fp.banco>0,'BANCO',' '),',',IF(fp.cheque>0,'CHEQUE',' '),',',IF(fp.tarjeta>0,'TARJETA',' '),',',IF(fp.transferencia>0,'TRANSFERENCIA',' '))AS descripcion,

		pg.cliente AS cliente FROM formapago fp

		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 

		INNER JOIN pagoguias pg ON fp.guia=pg.guia

		WHERE fp.procedencia='G'

		GROUP BY fp.guia

		UNION

		/*LIQUIDACION COBRANZA*/

		SELECT lc.fechaliquidacion AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,

		CONCAT('ABONO-','',lc.folio) AS referenciaabono, 0 AS cargo, SUM(lcd.importe) AS abono,0 AS saldo,

		CONCAT('PAGO',' ',IF(lcd.efectivo>0,'EFECTIVO',' '),',',IF(lcd.banco>0,'BANCO',' '),',',IF(lcd.cheque>0,'CHEQUE',' '),',',IF(lcd.tarjeta>0,'TARJETA',' '),',',IF(lcd.transferencia>0,'TRANSFERENCIA',' '))AS descripcion, 

		lcd.cliente FROM liquidacioncobranza lc

		INNER JOIN liquidacioncobranzadetalle lcd ON lc.folio=lcd.folioliquidacion

		INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id

		WHERE lcd.cobrar='SI'

		GROUP BY lcd.factura

		UNION

		/*CANCELACION GUIAS VENTANILLA*/

		SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,

		0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion,

		pg.cliente AS cliente FROM guiasventanilla gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura) AND gv.estado='CANCELADO'

		UNION

		/*CANCELACION GUIAS EMPRESARIALES*/

		SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,

		0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion, 

		pg.cliente AS cliente FROM guiasempresariales gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura) AND gv.estado='CANCELADO'

		)Tabla

		fecha BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 

		AND cliente=".$_GET[cliente]."";

		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);

		$re = $c->total%30; $res = intval($c->total/30) * 30;

		$limit = $res.",".$re;

		

		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,sucursal,referenciacargo,

		referenciaabono,cargo,abono,saldo,descripcion FROM( 

		/*GUIAS EMPRESARIALES*/

		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo,

		0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM guiasempresariales gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura)

		UNION

		/*GUIAS EMPRESARIALES*/

		SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono,

		f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM facturacion f

		INNER JOIN guiasempresariales gv ON f.folio=gv.factura

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		WHERE gv.factura<>0

		GROUP BY f.folio

		UNION

		/*GUIAS VENTANILLA*/

		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 

		0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM guiasventanilla gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura)

		UNION

		/*GUIAS VENTANILLA*/

		SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, 

		f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion, pg.cliente AS cliente FROM facturacion f

		INNER JOIN guiasventanilla gv ON f.folio=gv.factura

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		WHERE gv.factura<>0

		GROUP BY f.folio	

		UNION

		/*ABONOS CLIENTE*/

		SELECT a.fecharegistro AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,

		CONCAT('ABONO-','',a.folio) AS referenciaabono, 0 AS cargo, SUM(a.abonar) AS abono,0 AS saldo,

		CONCAT('PAGO',' ',IF(a.efectivo>0,'EFECTIVO',' '),',',IF(a.banco>0,'BANCO',' '),',',IF(a.cheque>0,'CHEQUE',' '),',',IF(a.tarjeta>0,'TARJETA',' '),',',IF(a.transferencia>0,'TRANSFERENCIA',' '))AS descripcion, 

		a.idcliente AS cliente FROM abonodecliente a

		INNER JOIN catalogosucursal cs ON a.idsucursal=cs.id 

		GROUP BY a.factura

		UNION

		/*ABONOS GUIAS A CONTADO*/

		SELECT fp.fecha AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',fp.guia) AS referenciaabono,

		0 AS cargo, SUM(fp.total) AS abono,0 AS saldo,

		CONCAT('PAGO',' ',IF(fp.efectivo>0,'EFECTIVO',' '),',',IF(fp.banco>0,'BANCO',' '),',',IF(fp.cheque>0,'CHEQUE',' '),',',IF(fp.tarjeta>0,'TARJETA',' '),',',IF(fp.transferencia>0,'TRANSFERENCIA',' '))AS descripcion,

		pg.cliente AS cliente FROM formapago fp

		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 

		INNER JOIN pagoguias pg ON fp.guia=pg.guia

		WHERE fp.procedencia='G'

		GROUP BY fp.guia

		UNION

		/*LIQUIDACION COBRANZA*/

		SELECT lc.fechaliquidacion AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,

		CONCAT('ABONO-','',lc.folio) AS referenciaabono, 0 AS cargo, SUM(lcd.importe) AS abono,0 AS saldo,

		CONCAT('PAGO',' ',IF(lcd.efectivo>0,'EFECTIVO',' '),',',IF(lcd.banco>0,'BANCO',' '),',',IF(lcd.cheque>0,'CHEQUE',' '),',',IF(lcd.tarjeta>0,'TARJETA',' '),',',IF(lcd.transferencia>0,'TRANSFERENCIA',' '))AS descripcion, 

		lcd.cliente FROM liquidacioncobranza lc

		INNER JOIN liquidacioncobranzadetalle lcd ON lc.folio=lcd.folioliquidacion

		INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id

		WHERE lcd.cobrar='SI'

		GROUP BY lcd.factura

		UNION

		/*CANCELACION GUIAS VENTANILLA*/

		SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,

		0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion,

		pg.cliente AS cliente FROM guiasventanilla gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura) AND gv.estado='CANCELADO'

		UNION

		/*CANCELACION GUIAS EMPRESARIALES*/

		SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,

		0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion, 

		pg.cliente AS cliente FROM guiasempresariales gv

		INNER JOIN pagoguias pg ON gv.id=pg.guia

		INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

		INNER JOIN catalogocliente cc ON cc.id= pg.cliente

		WHERE ISNULL(gv.factura) AND gv.estado='CANCELADO'

		)Tabla

		fecha BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 

		AND cliente=".$_GET[cliente]." ORDER BY fecha";

		$r = mysql_query($s." LIMIT ".$limit."",$l) or die($s);

		$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->sucursal 		= cambio_texto($f->sucursal);

				$f->referenciacargo = cambio_texto($f->referenciacargo);

				$f->referenciaabono = cambio_texto($f->referenciaabono);

				$f->descripcion		= cambio_texto($f->descripcion);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

			

	}else if($_GET[accion] == 16){//LINEA DE CREDITO

		$s = "SELECT DATE_FORMAT(sc.fechaautorizacion,'%d/%m/%Y') AS fechacredito,

		sc.montoautorizado,concat_ws(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS modifico,

		sc.folio AS solicitud FROM solicitudcredito sc

		INNER JOIN catalogoempleado ce ON sc.idusuario = ce.id

		WHERE sc.cliente='".$_GET[cliente]."'";

		if($_GET[tipo]=="0"){

			$r = mysql_query($s,$l) or die($s);

			echo mysql_num_rows($r);

		}else if($_GET[tipo]=="1"){

			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);

			$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->modifico = cambio_texto($f->modifico);

				$registros[] = $f;

			}

			echo str_replace('null','""',json_encode($registros));

		}

	}else if($_GET[accion] == 17){

		$s = "SELECT count(*) as total FROM solicitudcredito sc		

		WHERE sc.cliente='".$_GET[cliente]."'";

		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);

		$re = $c->total%30; $res = intval($c->total/30) * 30;

		$limit = $res.",".$re;

		

		$s = "SELECT DATE_FORMAT(sc.fechaautorizacion,'%d/%m/%Y') AS fechacredito,

		sc.montoautorizado,concat_ws(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS modifico,

		sc.folio AS solicitud FROM solicitudcredito sc

		INNER JOIN catalogoempleado ce ON sc.idusuario = ce.id

		WHERE sc.cliente='".$_GET[cliente]."'";		

		$r = mysql_query($s." LIMIT ".$limit."",$l) or die($s);

		$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->modifico = cambio_texto($f->modifico);

				$registros[] = $f;

			}

		echo str_replace('null','""',json_encode($registros));			

	}

?>