<?

	session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	require_once("../Conectar.php");

	$l = Conectarse("webpmm");

	

	if($_GET[accion]==1){ // optener cliente abonocliente.php

		$s = "SELECT id, CONCAT_WS(' ',nombre, paterno, materno) AS ncliente,(SELECT SUM(total + otrosmontofacturar + sobmontoafacturar) AS total 

FROM facturacion WHERE cliente='".$_GET[valor]."' GROUP BY cliente) AS totaldeudacliente

FROM catalogocliente 

WHERE id = '".$_GET[valor]."'";	

		//$registros = array();

		$r = mysql_query($s,$l) or die($s);

		$f = mysql_fetch_object($r);

		$f->ncliente = cambio_texto($f->ncliente);

		$w="SELECT SUM(TMP.importe)AS importe FROM		

			((SELECT SUM(ge.total)  AS importe

			FROM guiasventanilla ge

			INNER JOIN facturacion f ON ge.factura=f.folio

			INNER JOIN catalogocliente cc ON f.cliente=cc.id

			WHERE 

			f.folio NOT IN(SELECT ld.factura FROM liquidacioncobranza l

			INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 

			WHERE l.estado='LIQUIDADO' and ld.cobrar='SI' GROUP BY ld.factura) AND

			 f.cliente='".$_GET[valor]."')

			UNION

			(SELECT IF(SUM(ge.total) IS NULL,0,SUM(ge.total))  AS importe

			FROM guiasempresariales ge

			INNER JOIN facturacion f ON ge.factura=f.folio

			INNER JOIN catalogocliente cc ON f.cliente=cc.id

			WHERE 

			f.folio NOT IN(SELECT ld.factura FROM liquidacioncobranza l

			INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 

			WHERE l.estado='LIQUIDADO' and ld.cobrar='SI' GROUP BY ld.factura) AND

			 f.cliente='".$_GET[valor]."')) TMP";

		$wx = mysql_query($w,$l) or die($w);

		$w_row=mysql_fetch_array($wx);

		$f->importe=$w_row[importe];

		

		echo str_replace("null",'""',json_encode($f));

		

	}else if($_GET[accion]==2){ // sucursal ,fecha,folio abonocliente.php

		$s = "select id AS idsucursal, descripcion as sucursal, date_format(current_date, '%d/%m/%Y') AS fecha from catalogosucursal where id = '".$_SESSION[IDSUCURSAL]."'";	

			//$registros = array();

			$r = mysql_query($s,$l) or die($s);

			$f = mysql_fetch_object($r);

			$row=ObtenerFolio('abonodecliente','webpmm');

			$f->folio=$row[0];

			$f->sucursal = cambio_texto($f->sucursal);

			echo str_replace("null",'""',json_encode($f));

			

	}else if($_GET[accion]==3){//obtener Guia abonocliente.php

			$s = "(SELECT ge.id AS guia,ge.fecha, DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fechavencimiento,

					f.folio AS foliofactura, ge.total  AS importe,

					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual,1 AS aplicacion

					FROM guiasventanilla ge

					INNER JOIN facturacion f ON ge.factura=f.folio

					INNER JOIN catalogocliente cc ON f.cliente=cc.id

					WHERE f.Folio NOT IN (SELECT ld.factura FROM liquidacioncobranza l

					INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 

					WHERE l.estado='LIQUIDADO' GROUP BY ld.factura) 

					AND

					f.Folio NOT IN (SELECT a.factura FROM abonodecliente a WHERE a.factura=(SELECT factura FROM guiasventanilla WHERE id='".$_GET[guia]."')

					&& a.idcliente='".$_GET[cliente]."') 

					AND

					ge.factura=(SELECT factura FROM guiasventanilla WHERE id='".$_GET[guia]."') AND f.cliente='".$_GET[cliente]."')

					UNION 

					(SELECT ge.id AS guia,ge.fecha,

					DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fechavencimiento,

					f.folio AS foliofactura, ge.total  AS importe,

					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual,1 AS aplicacion

					FROM guiasempresariales ge

					INNER JOIN facturacion f ON ge.factura=f.folio

					INNER JOIN catalogocliente cc ON f.cliente=cc.id

					WHERE f.Folio NOT IN (SELECT ld.factura FROM liquidacioncobranza l

					INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 

					WHERE l.estado='LIQUIDADO' GROUP BY ld.factura)

					AND

					f.Folio NOT IN (SELECT a.factura FROM abonodecliente a WHERE a.factura=(SELECT factura FROM guiasventanilla WHERE id='".$_GET[guia]."')

					 && a.idcliente='".$_GET[cliente]."') 

					AND ge.factura=(SELECT factura FROM guiasventanilla WHERE id='".$_GET[guia]."') AND f.cliente='".$_GET[cliente]."')";	

				$registros = array();

				$r = mysql_query($s,$l) or die($s);

				while($f = mysql_fetch_object($r)){

					$registros[] = $f;

				}

				echo str_replace("null",'""',json_encode($registros));

	

	}else if($_GET[accion]==4){// obtener factura abonocliente.php
		$s = "SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL cc.diascredito DAY),'%d/%m/%Y') AS fechavencimiento,
		f.folio AS foliofactura, gv.total  AS importe,
		(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual,1 AS aplicacion
		FROM guiasventanilla gv
		INNER JOIN facturacion f ON gv.factura=f.folio
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE NOT EXISTS (SELECT ld.factura FROM liquidacioncobranza l
		INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
		WHERE l.estado='LIQUIDADO' GROUP BY ld.factura)
		AND NOT EXISTS (SELECT * FROM abonodecliente
		WHERE abonodecliente.factura = ".$_GET[factura]." AND idcliente=".$_GET[cliente].")
		UNION 
		SELECT ge.id AS guia,ge.fecha,
		DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fechavencimiento,
		f.folio AS foliofactura, ge.total  AS importe,
		(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual,1 AS aplicacion
		FROM guiasempresariales ge
		INNER JOIN facturacion f ON ge.factura=f.folio
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE NOT EXISTS (SELECT ld.factura FROM liquidacioncobranza l
		INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
		WHERE l.estado='LIQUIDADO' GROUP BY ld.factura)
		AND NOT EXISTS (SELECT * FROM abonodecliente
		WHERE abonodecliente.factura = ".$_GET[factura]." AND idcliente=".$_GET[cliente].")";
			
			
			/*$s = "(SELECT ge.id AS guia,date_format(ge.fecha,'%d/%m/%Y') as fecha,

					date_format(DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY),'%d/%m/%Y') AS fechavencimiento,

					f.folio AS foliofactura, ge.total  AS importe,

					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual,1 AS aplicacion

					FROM guiasventanilla ge

					INNER JOIN facturacion f ON ge.factura=f.folio

					INNER JOIN catalogocliente cc ON f.cliente=cc.id

					WHERE f.Folio NOT IN (SELECT ld.factura FROM liquidacioncobranza l

					INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 

					WHERE l.estado='LIQUIDADO' GROUP BY ld.factura) AND

					f.Folio NOT IN (SELECT a.factura FROM abonodecliente a WHERE a.factura='".$_GET[factura]."' && a.idcliente='".$_GET[cliente]."')

					&& ge.factura='".$_GET[factura]."' && f.cliente='".$_GET[cliente]."')

					UNION 

					(SELECT ge.id AS guia,ge.fecha,

					DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fechavencimiento,

					f.folio AS foliofactura, ge.total  AS importe,

					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual,1 AS aplicacion

					FROM guiasempresariales ge

					INNER JOIN facturacion f ON ge.factura=f.folio

					INNER JOIN catalogocliente cc ON f.cliente=cc.id

					WHERE f.Folio NOT IN (SELECT ld.factura FROM liquidacioncobranza l

					INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 

					WHERE l.estado='LIQUIDADO' GROUP BY ld.factura) AND

					f.Folio NOT IN (SELECT a.factura FROM abonodecliente a WHERE a.factura='".$_GET[factura]."' && a.idcliente='".$_GET[cliente]."')

					&& ge.factura='".$_GET[factura]."' && f.cliente='".$_GET[cliente]."')";	*/

				$registros = array();

				$r = mysql_query($s,$l) or die($s);

				while($f = mysql_fetch_object($r)){

					$registros[] = $f;

				}

				echo str_replace("null",'""',json_encode($registros));

				

	}else if($_GET[accion]==5){  //Guardar abonodecliente.php

			$s = "INSERT INTO abonodecliente (folio,fecharegistro,idsucursal,

			idcliente,descripcion,cobrador,abonar,saldocon,   

			saldoantesdeaplicar,efectivo,banco,cheque,ncheque,tarjeta,

			transferencia,factura,idusuario, usuario, fecha)VALUES

			(NULL,CURRENT_DATE, '".$_GET[idsucursal]."', '".$_GET[idcliente]."', 

			'".$_GET[descripcion]."', '".$_GET[cobrador]."', '".$_GET[abonar]."', 

			'".$_GET[saldocon]."', '".$_GET[sandoantes]."','".$_GET[efectivo]."',

			'".$_GET[banco]."','".$_GET[cheque]."','".$_GET[ncheque]."','".$_GET[tarjeta]."',

			'".$_GET[transferencia]."','".$_GET[factura]."', '".$_SESSION[IDUSUARIO]."', 

			'".$_SESSION[NOMBREUSUARIO]."',CURRENT_DATE)";	

			$r = mysql_query(str_replace("''",'NULL',$s),$l) or die($s);

					

					

			$folio = mysql_insert_id();

			

			$s = "INSERT INTO formapago SET guia='$folio',procedencia='A',tipo='X',total='$_GET[abonar]',efectivo='$_GET[efectivo]',

		tarjeta='$_GET[tarjeta]',transferencia='$_GET[transferencia]',cheque='$_GET[cheque]',ncheque='$_GET[ncheque]',banco='$_GET[banco]',notacredito='$_GET[nc]',

		nnotacredito='$_GET[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',usuario='$_SESSION[IDUSUARIO]',fecha=current_date";

		@mysql_query(str_replace("''","null",$s),$l) or die($s);

		

			$sql="SELECT guias FROM (SELECT id AS guias FROM guiasventanilla WHERE factura=

			(SELECT factura FROM abonodecliente WHERE folio='$folio')

					UNION 

					SELECT id AS guias FROM guiasempresariales WHERE factura=

					(SELECT factura FROM abonodecliente WHERE folio='$folio')

				)tabla";

			$r = mysql_query(str_replace("''",'NULL',$sql),$l) or die($sql);

			$registros= array();

		

			if (mysql_num_rows($r)>0){

				while ($f=mysql_fetch_object($r)){

				$sq="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',

				sucursalcobro='".$_GET[idsucursal]."' WHERE guia='$f->guias' ";		

				$d = mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);

				}

			}

			

			echo "ok";

		

	}else if($_GET[accion]==6){

		$s="SELECT 	ac.folio,date_format(ac.fecharegistro,'%d/%m/%Y')AS fecharegistro, ac.idsucursal,cs.descripcion AS sucursal, ac.idcliente, 

			CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno) AS cliente,

			ac.descripcion, ac.cobrador,

			ac.abonar, ac.saldocon, ac.saldoantesdeaplicar, 

			ac.efectivo, ac.banco, ac.cheque,ac.ncheque, ac.tarjeta, ac.transferencia, ac.factura 

			FROM abonodecliente  ac 

			INNER JOIN catalogosucursal cs ON cs.id=ac.idsucursal

			INNER JOIN catalogocliente cc ON cc.id=ac.idcliente

			WHERE ac.folio='".$_GET[id]."'";

			$r = mysql_query($s,$l) or die($s);

			$f = mysql_fetch_object($r);

			echo str_replace("null",'""',json_encode($f));

	}else if($_GET[accion]==7){// obtener  factura guardada abonocliente.php

			$s = "(SELECT ge.id AS guia,date_format(ge.fecha,'%d/%m/%Y') as fecha,date_format(DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY),'%d/%m/%Y') AS fechavencimiento,

					f.folio AS foliofactura, ge.total  AS importe,

					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual,1 AS aplicacion

					FROM guiasventanilla ge

					INNER JOIN facturacion f ON ge.factura=f.folio

					INNER JOIN catalogocliente cc ON f.cliente=cc.id

					WHERE f.Folio NOT IN (SELECT ld.factura FROM liquidacioncobranza l

					INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 

					WHERE l.estado='LIQUIDADO' GROUP BY ld.factura) 

					&& ge.factura='".$_GET[factura]."' && f.cliente='".$_GET[cliente]."')

					UNION 

					(SELECT ge.id AS guia,ge.fecha,

					DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fechavencimiento,

					f.folio AS foliofactura, ge.total  AS importe,

					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual,1 AS aplicacion

					FROM guiasempresariales ge

					INNER JOIN facturacion f ON ge.factura=f.folio

					INNER JOIN catalogocliente cc ON f.cliente=cc.id

					WHERE f.Folio NOT IN (SELECT ld.factura FROM liquidacioncobranza l

					INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 

					WHERE l.estado='LIQUIDADO' GROUP BY ld.factura) 

					&& ge.factura='".$_GET[factura]."' && f.cliente='".$_GET[cliente]."')";	

				$registros = array();

				$r = mysql_query($s,$l) or die($s);

				while($f = mysql_fetch_object($r)){

					$registros[] = $f;

				}

				echo str_replace("null",'""',json_encode($registros));

				

	}

?>