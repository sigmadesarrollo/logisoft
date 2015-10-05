<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){//OBTENER DATOS GENERALES -MODULOQUEJASDAÑOSFALTANTES.PHP
		$s = "SELECT DATE_FORMAT(CURRENT_DATE(),'%d/%m/%Y') AS fecha";
		$r = mysql_query($s, $l) or die($s);
		$principal = "";
		$f = mysql_fetch_object($r);		
		$row = ObtenerFolio('moduloquejasdanosfaltantes','webpmm');
		$f->folio = $row[0];
		$principal = str_replace('null','""',json_encode($f));
		
		$responsable = "";
			//responsable
			$r=mysql_query("SELECT id, CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS nombre
			FROM catalogoempleado WHERE puesto = 12 limit 1 ",$l) or die($s);
			$f=mysql_fetch_object($r);			
			$f->nombre = cambio_texto($f->nombre);					
		$responsable = str_replace('null','""',json_encode($f));
		//Sucursal
		$sucursal = "";
			$suc = mysql_query("SELECT descripcion FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."",$l); 
			$f = mysql_fetch_object($suc); 
			$f->descripcion = cambio_texto($f->descripcion);
		$sucursal = str_replace('null','""',json_encode($f));
		
		echo "({principal:$principal,responsable:$responsable,sucursal:$sucursal})";			

	}if($_GET[accion]==2){//OBTENER DATOS GUIA -MODULOQUEJASDAÑOSFALTANTES.PHP
		$s = "SELECT * FROM moduloquejasdanosfaltantes WHERE nguia='".$_GET[guia]."'";
		$r = mysql_query($s, $l) or die($s);
		if(mysql_num_rows($r)>0){
			die("ya existe");
		}
		
		$s = "(SELECT id,estado FROM  guiasempresariales WHERE id='".$_GET[guia]."') 
		UNION(SELECT id,estado FROM guiasventanilla WHERE id='".$_GET[guia]."')";
		$r = mysql_query($s, $l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
}if($_GET[accion]==3){ // GUARDAR Y MODIFICAR -MODULOQUEJASDAÑOSFALTANTES.PHP
		if($_GET[tipo]=="grabar"){
			$fecha=calcularHabiles(date('d'),date('m'),date('Y'),'30');
			$sqlnuevo="INSERT INTO moduloquejasdanosfaltantes (folio, fecharegistro, estado, idsucursal,nguia,idcliente,remitentecliente, 
			nombre, observaciones, relacionembarque, copiafactura,confirmacopiafactura, comentariogerente,confirmacomentariogerente, 
			cartareclamacion, reportedanosyfaltantes,confirmareportedanosyfaltantes, idresponsable,quejas,fechaposible, idusuario, usuario, fecha)	
			VALUES (NULL, CURRENT_DATE, 'REVISION COMITE', '".$_SESSION[IDSUCURSAL]."','".$_GET[guia]."','".$_GET['cliente']."' ,
			'".$_GET['remitente_cliente']."',UCASE('".$_GET[nombre]."'), UCASE('".$_GET[observaciones]."'), '".$_GET[relacionembarque]."',
			'".$_GET[copiafactura]."','".$_GET[confirmacopiafactura]."','".$_GET[comentariogerente]."','".$_GET[confirmacomentariogerente]."',
			'".$_GET[cartareclamacion]."', '".$_GET[reportedanosyfaltantes]."','".$_GET[confirmareportedanosyfaltantes]."','".$_GET[responsable]."',
			'QUEJAS DAÑOS Y FALTANTES','".cambiaf_a_mysql($fecha)."','".$_SESSION[IDUSUARIO]."','".$_SESSION[NOMBREUSUARIO]."',CURRENT_DATE)";
			$sql_nuevo	=mysql_query(str_replace("''",'null',$sqlnuevo),$l) or die("error en linea ".__LINE__.mysql_error($l));
			$folio		=mysql_insert_id();
			echo "ok,REVISION COMITE";
		}else if($_GET[tipo] == "modificar"){
			$sql_modificar =mysql_query(str_replace("''",'null',"UPDATE moduloquejasdanosfaltantes 	SET
							idsucursal = '".$_SESSION[IDSUCURSAL]."' , 
							nguia = '".$_GET[guia]."' , 
							idcliente = '".$_GET[cliente]."' , 
							remitentecliente = '".$_GET[remitente_cliente]."' , 
							nombre = UCASE('".$_GET[nombre]."') , 
							observaciones =  UCASE('".$_GET[observaciones]."') , 
							relacionembarque = '".$_GET[relacionembarque]."' , 
							copiafactura = '".$_GET[copiafactura]."' , 
							confirmacopiafactura='".$_GET[confirmacopiafactura]."',
							comentariogerente = '".$_GET[comentariogerente]."' , 
							confirmacomentariogerente='".$_GET[confirmacomentariogerente]."',
							cartareclamacion = '".$_GET[cartareclamacion]."' , 
							reportedanosyfaltantes = '".$_GET[reportedanosyfaltantes]."' , 
							confirmareportedanosyfaltantes='".$_GET[confirmareportedanosyfaltantes]."',
							idresponsable = '".$_GET[responsable]."' , 
							idusuario = '".$_SESSION[IDUSUARIO]."' , 
							usuario = '".$_SESSION[NOMBREUSUARIO]."' , 
							fecha = CURRENT_DATE
							WHERE
							folio = '".$_GET[folio]."'"),$l) or die("error en linea ".__LINE__.mysql_error($l));
			echo "ok,$_GET[estado]";
		}
		
	}else if($_GET[accion]==4){// BUSCAR cliente -MODULOQUEJASDAÑOSFALTANTES.PHP
		$s = "SELECT id,CONCAT_WS(' ',nombre,paterno,materno) AS nombre  FROM catalogocliente WHERE id='".$_GET[id]."'";
		$r = mysql_query($s, $l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	}else if($_GET[accion]==5){// BUSCAR RESPONSABLE  GERENTE DE OPERACIONES -MODULOQUEJASDAÑOSFALTANTES.PHP
			$s = "SELECT id,nombre FROM catalogoempleado WHERE id='".$_GET[id]."'";
			$r = mysql_query($s, $l) or die($s);
			$registros = array();
			if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	}else if($_GET[accion]==6){// ESTADO PROCEDE -MODULOQUEJASDAÑOSFALTANTES.PHP
		$s = "UPDATE moduloquejasdanosfaltantes SET estado='ESPERA FACTURA',idusuario='".$_SESSION[IDUSUARIO]."',
		usuario='".$_SESSION[NOMBREUSUARIO]."',fecha=CURRENT_DATE WHERE folio='".$_GET[folio]."'";
		$r = mysql_query($s, $l) or die($s);
		echo "ok,ESPERA FACTURA";
	}else if($_GET[accion]==7){//ESTADO NO PROCEDE -MODULOQUEJASDAÑOSFALTANTES.PHP
		$s = "UPDATE moduloquejasdanosfaltantes SET estado='VIA LEGAL',idusuario='".$_SESSION[IDUSUARIO]."',
		usuario='".$_SESSION[NOMBREUSUARIO]."',fecha=CURRENT_DATE WHERE folio='".$_GET[folio]."'";
		$r = mysql_query($s, $l) or die($s);
		echo "ok,VIA LEGAL";
	}else if($_GET[accion]==8){//ESTADO ROGRAMAR PAGO -MODULOQUEJASDAÑOSFALTANTES.PHP
		$s = "UPDATE moduloquejasdanosfaltantes SET estado='PROGRAMAR PAGO',idusuario='".$_SESSION[IDUSUARIO]."',
		usuario='".$_SESSION[NOMBREUSUARIO]."',fecha=CURRENT_DATE WHERE folio='".$_GET[folio]."'";
		$r = mysql_query($s, $l) or die($s);
		echo "ok,PROGRAMAR PAGO";
	
	}else if($_GET[accion]==9){// BUSCAR REGISTROS MODULO QUEJAS -MODULOQUEJASDAÑOSFALTANTES.PHP
		$s = "SELECT mq.folio,DATE_FORMAT(mq.fecharegistro,'%d/%m/%Y')AS fecharegistro,mq.estado,
		mq.idsucursal,cs.descripcion AS sucursal,mq.nguia,gv.estado AS estadoguia,mq.idcliente,
		cc.nombre AS clientedescripcion,mq.remitentecliente,mq.nombre,mq.observaciones,
		mq.relacionembarque,mq.copiafactura,mq.confirmacopiafactura,mq.comentariogerente,
		mq.confirmacomentariogerente,mq.cartareclamacion,
		mq.reportedanosyfaltantes,mq.confirmareportedanosyfaltantes,mq.idresponsable,ce.nombre AS responsable
		FROM moduloquejasdanosfaltantes AS mq
		INNER JOIN catalogosucursal AS cs ON cs.id=mq.idsucursal
		INNER JOIN catalogocliente AS cc ON cc.id=mq.idcliente
		INNER JOIN catalogoempleado AS ce ON ce.id=mq.idresponsable
		INNER JOIN guiasventanilla gv ON mq.nguia =gv.id
		WHERE mq.folio='".$_GET[folio]."' 
		UNION
		SELECT mq.folio,DATE_FORMAT(mq.fecharegistro,'%d/%m/%Y')AS fecharegistro,mq.estado,
		mq.idsucursal,cs.descripcion AS sucursal,mq.nguia,ge.estado AS estadoguia,mq.idcliente,
		cc.nombre AS clientedescripcion,mq.remitentecliente,mq.nombre,mq.observaciones,
		mq.relacionembarque,mq.copiafactura,mq.confirmacopiafactura,mq.comentariogerente,
		mq.confirmacomentariogerente,mq.cartareclamacion,
		mq.reportedanosyfaltantes,mq.confirmareportedanosyfaltantes,mq.idresponsable,ce.nombre AS responsable
		FROM moduloquejasdanosfaltantes AS mq
		INNER JOIN catalogosucursal AS cs ON cs.id=mq.idsucursal
		INNER JOIN catalogocliente AS cc ON cc.id=mq.idcliente
		INNER JOIN catalogoempleado AS ce ON ce.id=mq.idresponsable
		INNER JOIN guiasempresariales ge ON mq.nguia =ge.id
		WHERE mq.folio ='".$_GET[folio]."'";
		$r = mysql_query($s, $l) or die($s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$registros[] = $f;
		}
		echo str_replace('null','""',json_encode($registros));
	
	}else if($_GET[accion]==10){//ESTADO CERRADO -MODULOQUEJASDAÑOSFALTANTES.PHP		
		$s = "UPDATE moduloquejasdanosfaltantes SET estado='SOLUCIONADO',
		idusuario='".$_SESSION[IDUSUARIO]."',usuario='".$_SESSION[NOMBREUSUARIO]."',
		fecha=CURRENT_DATE WHERE folio='".$_GET[folio]."'";
		$r = mysql_query($s, $l) or die($s);
		
		$w="UPDATE solicitudtelefonica SET estado='SOLUCIONADO' WHERE guia='".$_GET[guia]."'";
		$g=mysql_query($w,$l)or die($w);
		
		$s = "UPDATE actividadusuario SET estado = 1 WHERE danofaltante = ".$_GET[folio]."";
		mysql_query($s,$l)or die($s);
		
		$s = "UPDATE actividadusuario SET estado = 1 WHERE referencia='".$_GET[guia]."'";
		mysql_query($s,$l)or die($s);
		
		echo "ok,SOLUCIONADO";
	}
	
?>