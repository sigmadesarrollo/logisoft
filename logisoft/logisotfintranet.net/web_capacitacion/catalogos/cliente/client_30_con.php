<?	session_start();
	require_once('../../Conectar.php');
	$link=Conectarse('webpmm');	
	
	$usuario	=$_SESSION[NOMBREUSUARIO]; 				$registros	=$_POST['registros'];
	$msg		=$_POST['msg']; 	  					$esprospecto=$_POST['esprospecto'];
	$prospecto	=$_POST['prospecto']; 					$accion		=$_POST['accion']; 
	$codigo		=$_POST['codigo'];	  					$convenio	=$_POST['convenio'];
	$rdmoral	=$_POST['rdmoral'];	  					$nombre		= str_replace("´","",str_replace("`","",$_POST['nombre']));
	$paterno	=$_POST['paterno'];	  					$materno	=$_POST['materno'];
	$rfc		=$_POST['rfc'];		  					$email		=$_POST['email'];
	$celular	=$_POST['celular'];	  					$web		=$_POST['web'];
	$listnick	=$_POST['listnick'];  					$npoliza	=$_POST['npoliza'];
	$poliza		=$_POST['chpoliza'];  					$aseguradora=$_POST['aseguradora'];
	$vigencia	=$_POST['vigencia'];  					$tipocliente=$_POST['lstipocliente'];
	$clasificacioncliente=$_POST['clasificacioncliente']; $activado=$_POST['activado'];
	$pago		= $_POST['pago']; $clasificacion = $_POST[clasificacion];	
	$idpagina 	= $_POST[idpagina];
	$comisiongeneral = $_POST[comisiongeneral];
	
	$nombre = $nombre;
	$paterno = $paterno;
	$materno = $materno;
	$listnick = $listnick;
	
	$s = "SELECT comisiongeneral FROM configuradorgeneral";
	$r = mysql_query($s,$link) or die($s);
	$f = mysql_fetch_object($r);
	$comgeneral = $f->comisiongeneral;
	
	if($accion==""){
		$resid=folio('catalogocliente','webpmm');
		$codigo=$resid[0];
		$recoleccion = $_GET['recoleccion'];
	}else if($accion=="grabar"){
		
		$s = "INSERT INTO catalogocliente 
		(id, personamoral, tipocliente, nombre, paterno, materno, rfc, email,
		celular, web,  poliza, npoliza, aseguradora, vigencia, clasificacioncliente,
		activado, pagocheque, tipoclientepromociones, sucursal, comision, usuario, fecha,fecharegistro)
		VALUES(null, '$rdmoral', '$tipocliente', UCASE('$nombre'), UCASE('$paterno'),
		UCASE('$materno'), UCASE('$rfc'), '$email', '$celular', '$web',  '$poliza',
		'$npoliza', UCASE('$aseguradora'), '$vigencia', UCASE('$clasificacioncliente'),
		'$activado','$pago','$clasificacion', $_SESSION[IDSUCURSAL], '$comisiongeneral', '$usuario', current_timestamp(),current_date)";
		$sqlins=mysql_query($s,$link) or die($s);
		$codigo=mysql_insert_id();
		
		$varnick = split(chr(13),$listnick);
		
		$s = "INSERT INTO losclientes 
		(nick,rfc,id,nombre,paterno,materno,sucursal,convenio,credito)
		SELECT '$varnick[0]','$rfc','$codigo','$nombre','$paterno','$materno','".$_POST["tabladetalle_POBLACION"][$i]."','0','0'";
		mysql_query($s,$link) or die($s);
		//INSERTAR TABLA DETALLE
		$s = "INSERT INTO direccion 
		(origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado, 
		pais,telefono,fax,facturacion,usuario,fecha)
		SELECT
		origen,'$codigo',calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado, 
		pais,telefono,fax,facturacion,'$_SESSION[IDUSUARIO]',CURRENT_TIMESTAMP()
		FROM direcciontmp
		WHERE idusuario = '$_SESSION[IDUSUARIO]' and idpagina='$idpagina'";
		mysql_query($s,$link) or die($s);
		
		if($esprospecto=="SI"){
			$delpro=mysql_query("DELETE FROM catalogoprospecto WHERE id='$prospecto'",$link);
			$delprodir=mysql_query("DELETE FROM direccion WHERE origen='pro' AND codigo='$prospecto'",$link);
		}
		$mensaje	="Los datos han sido guardados correctamente";
		$accion		="modificar";	
	
	}else if($accion=="modificar"){
		$s = "UPDATE losclientes set nombre = UCASE('$nombre'), paterno = UCASE('$paterno'),
		materno = UCASE('$materno'), rfc = UCASE('$rfc') where id = $codigo";
		mysql_query($s,$link) or die($s);
		
		$s = "UPDATE catalogocliente SET personamoral='$rdmoral',
		tipocliente='$tipocliente', nombre=UCASE('$nombre'), paterno=UCASE('$paterno'),
		materno=UCASE('$materno'), rfc=UCASE('$rfc'), email='$email', celular='$celular',
		web='$web', poliza='$poliza', npoliza='$npoliza', aseguradora=UCASE('$aseguradora'),
		vigencia='$vigencia', clasificacioncliente=UCASE('$clasificacioncliente'),
		activado='$activado', pagocheque='$pago', tipoclientepromociones='$clasificacion', sucursal='$_SESSION[IDSUCURSAL]',
		comision='$comisiongeneral', usuario='$usuario', fecha=current_timestamp() where id='$codigo'";
		$sqlupd=mysql_query($s,$link) or die($s);
	
		if($activado=="NO"){
			$s = "UPDATE solicitudcredito SET estado='BLOQUEADO', idusuario = ".$_SESSION[IDUSUARIO]." WHERE cliente='$codigo'";
			mysql_query($s,$link) or die($s);
			
			$s = "UPDATE losclientes set credito = 'NO' where id = $codigo";
			mysql_query($s,$link) or die($s);
			
			$s = "SELECT IFNULL(MAX(id),0) AS id FROM reportecliente2 WHERE idcliente = ".$codigo."";
			$r = mysql_query($s,$link) or die($s); 
			$cc = mysql_fetch_object($r);
			
			$s = "UPDATE reportecliente2 SET estadocredito = 'BLOQUEADO' WHERE id = ".$cc->id."";
			mysql_query($s,$link) or die($s);
		}else if($activado=="SI"){
			$s = "UPDATE losclientes set credito = 'SI' where id = $codigo";
			mysql_query($s,$link) or die($s);
			
			$s = "UPDATE solicitudcredito SET estado='ACTIVADO', idusuario = ".$_SESSION[IDUSUARIO]." WHERE cliente='$codigo'";
			mysql_query($s,$link) or die($s);
			
			$s = "SELECT IFNULL(MAX(id),0) AS id FROM reportecliente2 WHERE idcliente = ".$codigo."";
			$r = mysql_query($s,$link) or die($s); $cc = mysql_fetch_object($r);
			
			$s = "UPDATE reportecliente2 SET estadocredito = 'ACTIVADO' WHERE id = ".$cc->id."";
			mysql_query($s,$link) or die($s);
		}
	
		$sql_eliminar=mysql_query("DELETE FROM direccion WHERE origen='cl' AND codigo ='$codigo'",$link);
		//INSERTAR TABLA DETALLE
		$s = "INSERT INTO direccion 
		(id,origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado, 
		pais,telefono,fax,facturacion,usuario,fecha)
		SELECT
		iddireccion,origen,'$codigo',calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado, 
		pais,telefono,fax,facturacion,'$_SESSION[IDUSUARIO]',CURRENT_TIMESTAMP()
		FROM direcciontmp
		WHERE idusuario = '$_SESSION[IDUSUARIO]' and idpagina='$idpagina'";
		mysql_query($s,$link) or die($s);
	
		$mensaje="Los cambios han sido guardados correctamente";
		$accion="modificar";
	}
	
	if($accion=="grabar"||$accion=="modificar"){
		$del=mysql_query("DELETE FROM catalogoclientenick WHERE cliente='$codigo'",$link);		
		$enter=chr(13);
		$lista=split($enter,$listnick);		
		if (count($lista)>0){
			for ($i=0;$i<count($lista);$i++){	
				$var = trim($lista[$i]);
				if ($var!=""){
					$reg=mysql_num_rows(mysql_query("SELECT * FROM catalogoclientenick WHERE cliente='$codigo' and nick='$var'",$link));
					if ($reg==0){
						$sqlins=mysql_query("INSERT INTO catalogoclientenick (id,cliente,nick,usuario,fecha) VALUES(null,'$codigo',UCASE('$var'),'$usuario',current_timestamp())",$link);
					}
				}
			}
		}
	}
	
	echo "datos guardados,$codigo";
	
?>