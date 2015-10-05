<?

	session_start();
/*
	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	require_once("../Conectar.php");

	$l = Conectarse("webpmm");

	

	if($_GET[accion]==1){

		if($_GET[buscar]=="1"){

			$s = "SELECT iddestinatario FROM guiasventanilla WHERE ocurre = 1 AND id = '$_GET[folioguia]' AND idsucursaldestino = $_SESSION[IDSUCURSAL] ";

			$r = mysql_query($s,$l) or die($s);

			if(mysql_num_rows($r)>0){

				$f = mysql_fetch_object($r);

			}else{

				$s = "SELECT iddestinatario 

				FROM guiasempresariales 

				WHERE ocurre = 1 AND id = '$_GET[folioguia]' AND idsucursaldestino = $_SESSION[IDSUCURSAL] ";

				$r = mysql_query($s,$l) or die($s);

				if(mysql_num_rows($r)>0){

					$f = mysql_fetch_object($r);

				}

			}

			$idcliente = $f->iddestinatario;

		}else{

			$idcliente = $_GET[cliente];

		}

		$s = "SELECT 1 AS sel, gv.id AS guia, cs.descripcion AS origen, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,

		CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno) AS remitente, CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno) AS destinatario, 

		IF(tipoflete=1,'POR COBRAR','PAGADO') AS tipoflete, gv.total AS importe, gv.estado

		FROM guiasventanilla AS gv

		INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id

		INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id

		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id

		WHERE iddestinatario = '$idcliente' AND ocurre = 1 

		AND idsucursaldestino = $_SESSION[IDSUCURSAL] AND gv.estado = 'ALMACEN DESTINO'

		UNION

		SELECT 1 AS sel, gv.id AS guia, cs.descripcion AS origen, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,

		CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno) AS remitente, CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno) AS destinatario, 

		IF(tipoflete=1,'POR COBRAR','PAGADO') AS tipoflete, gv.total AS importe, gv.estado

		FROM guiasempresariales AS gv

		INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id

		INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id

		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id

		WHERE iddestinatario = '$idcliente' AND ocurre = 1  

		AND idsucursaldestino = $_SESSION[IDSUCURSAL] AND gv.estado = 'ALMACEN DESTINO'";

		$r = mysql_query($s,$l) or die($s);

		$arre = array();

		$total = 0;

		while($f = mysql_fetch_object($r)){

			$f->origen 			=  cambio_texto($f->origen);

			$f->remitente 		=  cambio_texto($f->remitente);

			$f->destinatario 	=  cambio_texto($f->destinatario);

			$total				+= $f->importe;

			$arre[] = $f;

		}

		$guias = str_replace("null",'""',json_encode($arre));

		

		$s = "SELECT nombre, paterno, materno

		FROM catalogocliente 

		WHERE id = $idcliente";

		$r = mysql_query($s,$l) or die($s);

		$f = mysql_fetch_object($r);

		$f->nombre = cambio_texto($f->nombre);

		$f->paterno = cambio_texto($f->paterno);

		$f->materno = cambio_texto($f->materno);

		$dcliente = str_replace("null",'""',json_encode($f));

		echo "({

			   guias:$guias,

			   cliente:$dcliente,

			   total:$total

		})";

		

	}else if($_GET[accion]==2){

		$slq="INSERT INTO entregasocurre (idsucursal,sucursal,nguia,cliente,nombre, 

			total,efectivo,cheque,banco,nocheque,tarjeta,transferencia, tipodeidentificacion,numeroidentificacion,personaquerecibe, 			usuario, idusuario,fecha)	VALUES

			('".$_GET['idsucursal']."','".$_GET['sucursal']."','".$_GET['nguia']."','".$_GET['cliente']."',

			'".$_GET['nombre']."', 

			'".$_GET['total']."',	'".$_GET['efectivo']."', '".$_GET['cheque']."',

			'".$_GET['banco']."', '".$_GET['ncheque']."', '".$_GET['tarjeta']."', 

			'".$_GET['transferencia']."',	'".$_GET['identificacion']."', 

			'".$_GET['nidentificacion']."', '".$_GET['precibe']."', '".$_SESSION['NOMBREUSUARIO']."',

			'".$_SESSION['IDUSUARIO']."', CURRENT_DATE)";

		$s=mysql_query(str_replace("''","NULL",$slq),$l) or die($slq." <BR> Error en la linea ".__LINE__);

		$folio=mysql_insert_id();

		if ($_GET[total]!="0"){
				$s = "INSERT INTO formapago SET guia='$folio',procedencia='O',tipo='X',
				total='$_GET[total]',efectivo='$_GET[efectivo]',tarjeta='$_GET[tarjeta]',
				transferencia='$_GET[transferencia]',cheque='$_GET[cheque]',
				ncheque='$_GET[ncheque]',banco='$_GET[banco]',
				notacredito='$_GET[nc]',nnotacredito='$_GET[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',
				usuario='$_SESSION[IDUSUARIO]',fecha=current_date";
		mysql_query(str_replace("''","null",$s),$l) or die($s);
		}

			$guia=split(",",$_GET['folios']);

			$num=count($guia);

			for($i=0;$i<$num;$i++){

				$detalle="INSERT INTO entregasocurre_detalle  (id,entregaocurre,guia,usuario,idusuario,fecha) 	VALUES 	(NULL,'".$folio."','".$guia[$i]."','".$_SESSION['NOMBREUSUARIO']."','".$_SESSION['IDUSUARIO']."',CURRENT_DATE)";

				$res_detalle=mysql_query(str_replace("''","NULL",$detalle),$l)or die($detalle." <BR> Error en la linea ".__LINE__);

				$estadoguia="UPDATE guiasventanilla SET estado ='POR ENTREGAR' WHERE id='".$guia[$i]."'";

				$estado_guia=mysql_query($estadoguia,$l) or die($estadoguia." Error en la linea ".__LINE__);

			}

			echo "guardado";

	}else if($_GET[accion]==3){

		$s = "SELECT folio, cliente, nombre FROM entregasocurre WHERE folio = $_GET[folio]";

		$r = mysql_query($s,$l) or die($s);

		$f = mysql_fetch_object($r);
		
		$f->nombre = cambio_texto($f->nombre); 
		
		$datoscliente = str_replace("null",'""', json_encode($f));

		

		$s = "SELECT 0 AS sel, gv.id AS guia, cs.descripcion AS origen, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,

		CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno) AS remitente, CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno) AS destinatario, 

		IF(tipoflete=1,'POR COBRAR','PAGADO') AS tipoflete, gv.total AS importe, gv.estado

		FROM guiasventanilla AS gv

		INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id

		INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id

		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id

		INNER JOIN entregasocurre_detalle AS eod ON gv.id = eod.guia AND eod.entregaocurre = $_GET[folio]

		where idsucursaldestino = (SELECT id FROM catalogosucursal WHERE descripcion = '$_GET[sucursal]')

		UNION

		SELECT 0 AS sel, gv.id AS guia, cs.descripcion AS origen, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,

		CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno) AS remitente, CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno) AS destinatario, 

		IF(tipoflete=1,'POR COBRAR','PAGADO') AS tipoflete, gv.total AS importe, gv.estado

		FROM guiasempresariales AS gv

		INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id

		INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id

		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id

		INNER JOIN entregasocurre_detalle AS eod ON gv.id = eod.guia AND eod.entregaocurre = $_GET[folio]

		WHERE idsucursaldestino = (SELECT id FROM catalogosucursal WHERE descripcion = '$_GET[sucursal]')";

		$r = mysql_query($s,$l) or die($s);

		$arre = array();

		while($f = mysql_fetch_object($r)){

			$f->destinatario 	= cambio_texto($f->destinatario);

			$f->remitente 		= cambio_texto($f->remitente);

			$arre[] = $f;

		}

		$datostabla = str_replace("null",'""', json_encode($arre));

		

		echo "{

			datoscliente:$datoscliente,

			datostabla:$datostabla

		}";

	}

?>