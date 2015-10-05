<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$resid=folio('catalogocliente','webpmm');
		$s = "DELETE FROM direcciontmp WHERE (codigo IS NULL OR codigo='') AND idusuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		echo $resid[0];
		
	}else if($_GET[accion]==2){
		$s = "INSERT INTO catalogocliente SET personamoral = '".$_GET[moral]."',
		tipocliente = '".$_GET[tipocliente]."', nombre = '".$_GET[nombre]."', 
		paterno = '".$_GET[paterno]."', materno = '".$_GET[materno]."', rfc = '".$_GET[rfc]."',
		email = '".$_GET[email]."', celular = '".$_GET[celular]."', web = '".$_GET[web]."',
		poliza = '".$_GET[poliza]."', npoliza = '".$_GET[npoliza]."', aseguradora = '".$_GET[aseguradora]."', 
		vigencia = '".$_GET[vigencia]."', clasificacioncliente = '".$_GET[clasificacioncliente]."',
		activado = '".$_GET[activado]."', pagocheque = '".$_GET[pagocheque]."', 
		tipoclientepromociones = '".$_GET[tipoclientepromociones]."',
		sucursal = '".$_SESSION[IDSUCURSAL]."', usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_timestamp()";
		mysql_query($s,$l) or die($s);		
		$codigo = mysql_insert_id();
		
		insertarNick($codigo,$_GET[nick]);
		
		$s = "SELECT * FROM direccion WHERE (codigo IS NULL OR codigo='') AND idusuario=".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$s = "UPDATE direccion SET origen='cl', codigo='".$codigo."' WHERE (codigo IS NULL OR codigo='') 
			AND idusuario=".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
		}		
		
		if($_GET[esprospecto]=="SI"){
			$s = mysql_query("DELETE FROM catalogoprospecto WHERE id='".$_GET[prospecto]."'",$l);
			$s = mysql_query("DELETE FROM direccion WHERE origen='pro' AND codigo='".$_GET[prospecto]."'",$l);
		}
		
		echo "guardo,".$codigo;
	
	}else if($_GET[accion]==3){
		$s = "UPDATE catalogocliente SET personamoral = '".$_GET[moral]."',
		tipocliente = '".$_GET[tipocliente]."', nombre = '".$_GET[nombre]."', 
		paterno = '".$_GET[paterno]."', materno = '".$_GET[materno]."', rfc = '".$_GET[rfc]."',
		email = '".$_GET[email]."', celular = '".$_GET[celular]."', web = '".$_GET[web]."',
		poliza = '".$_GET[poliza]."', npoliza = '".$_GET[npoliza]."', aseguradora = '".$_GET[aseguradora]."', 
		vigencia = '".$_GET[vigencia]."', clasificacioncliente = '".$_GET[clasificacioncliente]."',
		activado = '".$_GET[activado]."', pagocheque = '".$_GET[pagocheque]."', 
		tipoclientepromociones = '".$_GET[tipoclientepromociones]."',
		sucursal = '".$_SESSION[IDSUCURSAL]."', usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_timestamp()
		where id = '".$_GET[codigo]."'";		
		mysql_query($s,$l) or die($s);
		
		insertarNick($_GET[codigo],$_GET[nick]);
		
		if($_GET[activado]=="NO"){
			$s = "UPDATE solicitudcredito SET estado='BLOQUEADO' WHERE cliente='".$_GET[codigo]."'";
			mysql_query($s,$l) or die($s);
		}else if($_GET[activado]=="SI"){
			$s = "UPDATE solicitudcredito SET estado='ACTIVADO' WHERE cliente='".$_GET[codigo]."'";
			mysql_query($s,$l) or die($s);
		}
		
		echo "modifico";
		
	}else if($_GET[accion]==4){
		$s = "SELECT IFNULL(MAX(iddireccion),0) + 1 AS iddireccion FROM direccion";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		
		$s = "INSERT INTO direccion SET 
		iddireccion	= ".$f->iddireccion.",
		calle		= UCASE('".$_GET[calle]."'),
		numero		= UCASE('".$_GET[numero]."'),
		crucecalles	= UCASE('".$_GET[entrecalles]."'),
		cp			= UCASE('".$_GET[cp]."'),
		colonia		= UCASE('".$_GET[colonia]."'),
		poblacion	= UCASE('".$_GET[poblacion]."'),
		municipio	= UCASE('".$_GET[municipio]."'),
		estado		= UCASE('".$_GET[estado]."'),
		pais		= UCASE('".$_GET[pais]."'),
		telefono	= UCASE('".$_GET[telefono]."'),
		fax			= UCASE('".$_GET[fax]."'),
		facturacion	= UCASE('".$_GET[facturacion]."'),
		usuario		= UCASE('".$_SESSION[NOMBREUSUARIO]."'),
		fecha		= '".$_GET[fecha]."',
		idusuario	= ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok,".$f->iddireccion;
		
	}else if($_GET[accion]==5){
		$s = "UPDATE direccion SET
		calle		= UCASE('".$_GET[calle]."'),
		numero		= UCASE('".$_GET[numero]."'),
		crucecalles	= UCASE('".$_GET[entrecalles]."'),
		cp			= UCASE('".$_GET[cp]."'),
		colonia		= UCASE('".$_GET[colonia]."'),
		poblacion	= UCASE('".$_GET[poblacion]."'),
		municipio	= UCASE('".$_GET[municipio]."'),
		estado		= UCASE('".$_GET[estado]."'),
		pais		= UCASE('".$_GET[pais]."'),
		telefono	= UCASE('".$_GET[telefono]."'),
		fax			= UCASE('".$_GET[fax]."'),
		facturacion	= UCASE('".$_GET[facturacion]."')
		WHERE iddireccion = ".$_GET[iddireccion]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	}
	
	function insertarNick($codigo,$nick){
		$del = mysql_query("DELETE FROM catalogoclientenick WHERE cliente='".$codigo."'",$l);
		$enter = chr(13);
		$lista = split($enter,$nick);
		if(count($lista)>0){
			for($i=0;$i<count($lista);$i++){	
				$var = trim($lista[$i]);
				if($var!=""){
					$reg=mysql_num_rows(mysql_query("SELECT * FROM catalogoclientenick 
					WHERE cliente='".$codigo."' and nick='".$var."'",$l));
					if($reg==0){
						$sqlins=mysql_query("INSERT INTO catalogoclientenick(cliente,nick,usuario,fecha)
						VALUES('".$codigo."',UCASE('".$var."'),'".$_SESSION[NOMBREUSUARIO]."',current_timestamp())",$l);
					}
				}
			}			
		}
	}

?>
