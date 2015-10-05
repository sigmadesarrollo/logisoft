<?	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){//BORRAR TEMPORAL Y OBTENER CODIGO CLIENTE
		$s = "DELETE FROM direcciontmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		$s = "DELETE FROM catalogoclientenicktmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$resid = folio('catalogocliente','webpmm');
		echo $resid[0];
		
	}else if($_GET[accion]==2){//REGISTRAR CLIENTE
		$s = "UPDATE catalogocliente SET 
		personamoral	= '".$_GET[moral]."',
		tipocliente		= '".$_GET[tipocliente]."',
		nombre			= UCASE('".$_GET[nombre]."'),
		paterno			= UCASE('".$_GET[paterno]."'),
		materno			= UCASE('".$_GET[materno]."'),
		rfc				= UCASE('".$_GET[rfc]."'), 
		email			= '".$_GET[email]."', 
		celular			= '".$_GET[celular]."',
		web				= '".$_GET[web]."', 
		poliza			= '".$_GET[poliza]."',
		npoliza			= '".$_GET[npoliza]."',
		aseguradora		= UCASE('".$_GET[aseguradora]."'),
		vigencia		= '".cambiaf_a_mysql($_GET[vigencia])."', 
		clasificacioncliente	= UCASE('".$_GET[clasificacioncliente]."'),
		activado		= '".$_GET[activado]."', 
		pagocheque		= '".$_GET[pago]."', 
		tipoclientepromociones	= '".$_GET[clasificacion]."', 
		sucursal		= '".$_SESSION[IDSUCURSAL]."',
		usuario 		= '".$_SESSION[NOMBREUSUARIO]."', 
		fecha			= current_timestamp()";
		mysql_query($s,$l) or die($s);
		$codigo = mysql_insert_id();
		
		$s = "INSERT INTO catalogoclientenick
		SELECT 0 AS id, ".$codigo." AS cliente, nick, idusuario, fecha
		FROM catalogoclientenicktmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO direccion
		SELECT 0 AS id, origen, ".$codigo." AS codigo, calle, numero,
		crucecalles, cp, colonia, poblacion, municipio, estado, 
		pais, telefono, fax, facturacion, ".$_SESSION[IDUSUARIO]." AS idusuario,
		fecha WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok,".$codigo;
		
	}else if($_GET[accion]==3){//MODIFICAR CLIENTE
		$s = "UPDATE catalogocliente SET 
		personamoral	= '".$_GET[moral]."',
		tipocliente		= '".$_GET[tipocliente]."',
		nombre			= UCASE('".$_GET[nombre]."'),
		paterno			= UCASE('".$_GET[paterno]."'),
		materno			= UCASE('".$_GET[materno]."'),
		rfc				= UCASE('".$_GET[rfc]."'), 
		email			= '".$_GET[email]."', 
		celular			= '".$_GET[celular]."',
		web				= '".$_GET[web]."', 
		poliza			= '".$_GET[poliza]."',
		npoliza			= '".$_GET[npoliza]."',
		aseguradora		= UCASE('".$_GET[aseguradora]."'),
		vigencia		= '".cambiaf_a_mysql($_GET[vigencia])."', 
		clasificacioncliente	= UCASE('".$_GET[clasificacioncliente]."'),
		activado		= '".$_GET[activado]."', 
		pagocheque		= '".$_GET[pago]."', 
		tipoclientepromociones	= '".$_GET[clasificacion]."', 
		sucursal		= '".$_SESSION[IDSUCURSAL]."',
		usuario 		= '".$_SESSION[NOMBREUSUARIO]."', 
		fecha			= current_timestamp() 
		WHERE id 		= '".$_GET[cliente]."'";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM catalogoclientenick WHERE cliente = ".$_GET[cliente]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO catalogoclientenick
		SELECT 0 AS id, ".$_GET[cliente]." AS cliente, nick,
		".$_SESSION[IDUSUARIO]." AS idusuario, fecha
		FROM catalogoclientenicktmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO direccion
		SELECT 0 AS id, origen, ".$_GET[cliente]." AS codigo, calle, numero,
		crucecalles, cp, colonia, poblacion, municipio, estado, 
		pais, telefono, fax, facturacion, ".$_SESSION[IDUSUARIO]." AS idusuario,
		fecha WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
		
	}else if($_GET[accion]==4){//ABC DIRECCION TEMPORAL
		if($_GET[tipo]=="grabar"){
			$s = "INSERT INTO direcciontmp SET
			origen = 'cl', calle = UCASE('".$_GET[calle]."'), numero = UCASE('".$_GET[numero]."'),
			crucecalles = UCASE('".$_GET[crucecalles]."'), cp = UCASE('".$_GET[cp]."'),
			colonia = UCASE('".$_GET[colonia]."'), poblacion = UCASE('".$_GET[poblacion]."'),
			municipio = UCASE('".$_GET[municipio]."'), estado = UCASE('".$_GET[estado]."'), 
			pais = UCASE('".$_GET[pais]."'), telefono = UCASE('".$_GET[telefono]."'),
			fax = UCASE('".$_GET[fax]."'), facturacion = UCASE('".$_GET[facturacion]."'),
			idusuario = ".$_SESSION[IDUSUARIO].", fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
			echo "ok";
		}else if($_GET[tipo]=="modificar"){
			$s = "UPDATE direcciontmp SET
			origen = 'cl', calle = UCASE('".$_GET[calle]."'), numero = UCASE('".$_GET[numero]."'),
			crucecalles = UCASE('".$_GET[crucecalles]."'), cp = UCASE('".$_GET[cp]."'),
			colonia = UCASE('".$_GET[colonia]."'), poblacion = UCASE('".$_GET[poblacion]."'),
			municipio = UCASE('".$_GET[municipio]."'), estado = UCASE('".$_GET[estado]."'), 
			pais = UCASE('".$_GET[pais]."'), telefono = UCASE('".$_GET[telefono]."'),
			fax = UCASE('".$_GET[fax]."'), facturacion = UCASE('".$_GET[facturacion]."')
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
			echo "ok";
		}else if($_GET[tipo]=="eliminar"){
			$s = "DELETE FROM direcciontmp 
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
			echo "ok";
		}
	}else if($_GET[accion]==5){//ABC NICK TEMPORAL
		if($_GET[tipo]=="grabar"){
			$s = "INSERT INTO catalogoclientenicktmp SET
			nick = UCASE('".$_GET[nick]."'), idusuario = ".$_SESSION[IDUSUARIO].",
			fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
			echo "ok";
		}else if($_GET[tipo]=="modificar"){
			$s = "UPDATE catalogoclientenicktmp SET
			nick = UCASE('".$_GET[nick]."')
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
			echo "ok";
		}else if($_GET[tipo]=="eliminar"){
			$s = "DELETE FROM catalogoclientenicktmp
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
			echo "ok";
		}
	}

?>