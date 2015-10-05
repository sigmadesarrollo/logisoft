<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	$usuario = $_SESSION[NOMBREUSUARIO]; 		$idusuario = $_SESSION[IDUSUARIO];
	$idsucursal=$_SESSION[IDSUCURSAL];			$sucursalorigen = $_POST['sucursalorigen'];
	$accion = $_POST['accion']; 				$fecha = $_POST['fecha']; 
	$estado = $_POST['estado'];					$folio = $_POST['folio']; 
	$folioconvenio = $_POST['folioconvenio']; 	$fechaautorizacion = $_POST['fechaautorizacion'];  
	$rdmoral = $_POST['rdmoral']; 				$cliente = $_POST['cliente']; 
	$nick = $_POST['nick'];						$rfc = $_POST['rfc']; 
	$nombre = $_POST['nombre']; 				$paterno = $_POST['paterno'];
	$materno = $_POST['materno']; 				$calle = $_POST['calle']; 
	$numero = $_POST['numero'];					$cp = $_POST['cp']; 
	$colonia = $_POST['colonia']; 				$poblacion = $_POST['poblacion'];
	$municipio = $_POST['municipio']; 			$pais = $_POST['pais'];
	$celular = $_POST['celular']; 				$telefono = $_POST['telefono']; 
	$email = $_POST['email']; 					$giro = $_POST['giro']; 
	$antiguedad = $_POST['antiguedad'];			$representantelegal = $_POST['representantelegal']; 
	$actaconstitutiva = $_POST['actaconstitutiva']; $identificacion = $_POST['identificacion']; 
	$hacienda = $_POST['hacienda']; 			$comprobante = $_POST['comprobante']; 
	$estadocuenta = $_POST['estadocuenta']; 	$solicitud = $_POST['solicitud']; 
	$nacta = $_POST['nacta']; 					$fechaescritura = $_POST['fechaescritura']; 
	$fechainscripcion = $_POST['fechainscripcion']; $nidentificacion = $_POST['nidentificacion']; 
	$fechainiciooperaciones = $_POST['fechainiciooperaciones']; $rfc2 = $_POST['rfc2']; 
	$comprobanteluz = $_POST['comprobanteluz']; $banco = $_POST['banco']; 
	$cuenta = $_POST['cuenta']; 				$estado2 = $_POST['estado2'];
	$semanapago = $_POST['semanapago']; 		$lunespago = $_POST['lunespago']; 
	$martespago = $_POST['martespago']; 		$miercolespago = $_POST['miercolespago']; 
	$juevespago = $_POST['juevespago']; 		$viernespago = $_POST['viernespago']; 
	$sabadopago = $_POST['sabadopago'];			
	$semanarevision = $_POST['semanarevision']; $lunesrevision = $_POST['lunesrevision']; $martesrevision = $_POST['martesrevision']; $miercolesrevision = $_POST['miercolesrevision']; $juevesrevision = $_POST['juevesrevision']; $viernesrevision = $_POST['viernesrevision']; $sabadorevision = $_POST['sabadorevision'];
$horariopago = $_POST['horariopago']; $apago = $_POST['apago']; $responsablepago = $_POST['responsablepago']; $celularpago = $_POST['celularpago']; $telefonopago = $_POST['telefonopago']; $faxpago = $_POST['faxpago']; $horariorevision = $_POST['horariorevision']; $arevision = $_POST['arevision']; $rbanco = $_POST['rbanco']; $rsucursal = $_POST['rsucursal']; $rcuenta = $_POST['rcuenta']; $rtelefono = $_POST['rtelefono']; $cempresa = $_POST['cempresa']; $ccontacto = $_POST['ccontacto']; $ctelefono = $_POST['ctelefono']; 
$msolicitado = $_POST['msolicitado']; $mautorizado = $_POST['mautorizado'];

$observaciones = $_POST['observaciones']; $registropersona = $_POST['registropersona']; $registrobanco = $_POST['registrobanco']; $registrocomer = $_POST['registrocomer']; $diacredito = $_POST['diacredito'];
$shpago = $_POST['shpago']; $smpago = $_POST['smpago']; $ahpago = $_POST['ahpago'];
$ampago = $_POST['ampago']; $shrevision = $_POST['shrevision']; $smrevision = $_POST['smrevision']; $ahrevision = $_POST['ahrevision']; $amrevision = $_POST['amrevision'];
$hidensucursal = $_POST['hidensucursal']; $sucursalesead1_sel = $_POST['sucursalesead1_sel']; $todas = $_POST['todas'];
	
	if($accion == ""){
		$fecha = date("d/m/Y");
		$estado = "SOLICITUD";
		$row = ObtenerFolio('solicitudcredito','webpmm');
		$folio = $row[0];
	}else if($accion == "grabar"){
		if($semanarevision==1){
			$lunesrevision = 1; $martesrevision = 1; $miercolesrevision = 1; $juevesrevision = 1;
			$viernesrevision = 1; $sabadorevision = 1;
		}
		if($semanapago==1){
			$lunespago = 1; $martespago = 1; $miercolespago = 1; $juevespago = 1;
			$viernespago = 1; $sabadopago = 1;
		}
		$estado = "EN AUTORIZACION";		
		$s = "INSERT INTO solicitudcredito
		(fechasolicitud, estado, folioconvenio, fechaautorizacion, personamoral,
		 cliente, nick, rfc, nombre, paterno, materno, calle, numero, cp,
		 colonia, poblacion, municipio, estadoc, pais, celular, telefono, email, giro,
		 antiguedad, representantelegal, actaconstitutiva, numeroacta,
		 fechaescritura, fechainscripcion, identificacionlegal, numeroidentificacion,
		 hacienda, fechainiciooperaciones, rfc2, comprobante, comprobanteluz, estadocuenta,
		 banco, cuenta, solicitud, semanapago, lunespago, martespago, miercolespago,
		 juevespago, viernespago, sabadopago, horariopago, apago, responsablepago,		 
		 celularpago, telefonopago, faxpago, semanarevision, lunesrevision, martesrevision,
		 miercolesrevision, juevesrevision, viernesrevision, sabadorevision, horariorevision,
		 arevision, montosolicitado, montoautorizado, diascredito, observaciones, usuario,
		 idusuario, idsucursal, fecha) VALUES
		('".cambiaf_a_mysql($fecha)."',UCASE('$estado'),'".(($folioconvenio!='')? $folioconvenio : 0)."',
		'".(($fechaautorizacion!='')?cambiaf_a_mysql($fechaautorizacion):'0000-00-00')."','$rdmoral',
		 '$cliente', UCASE('$nick'), UCASE('$rfc'), UCASE('$nombre'), UCASE('$paterno'),
		 UCASE('$materno'), UCASE('$calle'), UCASE('$numero'), UCASE('$cp'),
		 UCASE('$colonia'), UCASE('$poblacion'), UCASE('$municipio'), UCASE('$estado2'),
		 UCASE('$pais'), UCASE('$celular'), UCASE('$telefono'), UCASE('$email'),
		 UCASE('$giro'),UCASE('$antiguedad'),UCASE('$representantelegal'),
		 '".(($actaconstitutiva==1)?1:0)."', UCASE('$nacta'),
		 '".(($fechaescritura!='')?cambiaf_a_mysql($fechaescritura):'0000-00-00')."',
		 '".(($fechainscripcion!='')?cambiaf_a_mysql($fechainscripcion):'0000-00-00')."',
		 '".(($identificacion==1)?1:0)."',
		 UCASE('$nidentificacion'),'".(($hacienda==1)?1:0)."',
		 '".(($fechainiciooperaciones!='')?cambiaf_a_mysql($fechainiciooperaciones):'0000-00-00')."',
		 '$rfc2', '".(($comprobante==1)?1:0)."',
		 '".(($comprobanteluz==1)?1:0)."', '".(($estadocuenta==1)?1:0)."',UCASE('$banco'),
		 '".(($cuenta!='')?$cuenta:0)."','".(($solicitud==1)?1:0)."','".(($semanapago==1)?1:0)."',
		 '".(($lunespago==1)?1:0)."', '".(($martespago==1)?1:0)."','".(($miercolespago==1)?1:0)."',
		 '".(($juevespago==1)?1:0)."', '".(($viernespago==1)?1:0)."',
		 '".(($sabadopago==1)?1:0)."','$horariopago','$apago',UCASE('$responsablepago'),
		 '$celularpago','$telefonopago', '$faxpago','".(($semanarevision==1)?1:0)."',
		 '".(($lunesrevision==1)?1:0)."', '".(($martesrevision==1)?1:0)."',
		 '".(($miercolesrevision==1)?1:0)."','".(($juevesrevision==1)?1:0)."',
		 '".(($viernesrevision==1)?1:0)."', '".(($sabadorevision==1)?1:0)."',
		 '$horariorevision', '$arevision', '".(($msolicitado!='')?$msolicitado:0)."',
		 '".(($mautorizado!='')?$mautorizado:0)."', '".(($diacredito!='')?$diacredito:0)."',
		 UCASE('".trim($observaciones)."'),'$usuario','$idusuario',
		 '$sucursalorigen',current_timestamp())";
		$r = mysql_query($s,$link) or die($s);		
		$folio = mysql_insert_id();
		if($registropersona>0){
			for($i=0;$i<$registropersona;$i++){
				$sqldetallep=mysql_query("INSERT INTO solicitudcreditopersonadetalle
				(solicitud,persona,usuario,fecha)VALUES
				('".$folio."',
				UCASE('".$_POST["tablaPersonas_PERSONA"][$i]."'),				
				UCASE('$usuario'), current_timestamp())", $link);
				$cadenapersona .= "{persona:'".$_POST["tablaPersonas_PERSONA"][$i]."'},";
			}
			$cadenapersona = substr($cadenapersona,0,strlen($cadenapersona)-1);
		}
		if($registrobanco>0){
			for($j=0;$j<$registrobanco;$j++){				
				$sqldetalleb=mysql_query("INSERT INTO solicitudcreditobancodetalle
				(solicitud,banco,sucursal,cuenta,telefono,usuario,fecha)VALUES
				('".$folio."',
				UCASE('".$_POST["tablaBanco_BANCO"][$j]."'),
				UCASE('".$_POST["tablaBanco_SUCURSAL"][$j]."'),
				UCASE('".$_POST["tablaBanco_CUENTA"][$j]."'),
				UCASE('".$_POST["tablaBanco_TELEFONO"][$j]."'),
				UCASE('$usuario'), current_timestamp())", $link);
				$cadenabanco .= "{banco:'".$_POST["tablaBanco_BANCO"][$j].
								"',sucursal:'".$_POST["tablaBanco_SUCURSAL"][$j].
								"',cuenta:'".$_POST["tablaBanco_CUENTA"][$j].
								"',telefono:'".$_POST["tablaBanco_TELEFONO"][$j]."'},";
			}
			$cadenabanco = substr($cadenabanco,0,strlen($cadenabanco)-1);
		}
		if($registrocomer>0){
			for($k=0;$k<$registrocomer;$k++){				
				$sqldetallec=mysql_query("INSERT INTO solicitudcreditocomercialesdetalle
				(solicitud,empresa,contacto,telefono,usuario,fecha)VALUES
				('".$folio."',
				UCASE('".$_POST["tablaComer_EMPRESA"][$k]."'),
				UCASE('".$_POST["tablaComer_CONTACTO"][$k]."'),
				UCASE('".$_POST["tablaComer_TELEFONO"][$k]."'),
				UCASE('$usuario'), current_timestamp())", $link);
				$cadenacomer .= "{empresa:'".$_POST["tablaComer_EMPRESA"][$k].
								"',contacto:'".$_POST["tablaComer_CONTACTO"][$k].
								"',telefono:'".$_POST["tablaComer_TELEFONO"][$k]."'},";
			}
			$cadenacomer = substr($cadenacomer,0,strlen($cadenacomer)-1);
		}
		if($hidensucursal=="todas"){			
			$sqlins=mysql_query("INSERT INTO solicitudcreditosucursaldetalle (solicitud,idsucursal,sucursal,usuario,fecha) VALUES('$folio','0',UCASE('TODAS'),'$usuario',current_timestamp())",$link);
		}else if($hidensucursal!=""){
		$coma = ",";
		$hidensucursal = substr($hidensucursal,0,strlen($hidensucursal)-1); 
		$lista=split($coma,$hidensucursal);		
		if(count($lista)>0){
			for ($i=0;$i<count($lista);$i++){
				$var = trim($lista[$i]);				
				$var = split(":",$var);			
				if ($var!=""){
				
				$sqlins=mysql_query("INSERT INTO solicitudcreditosucursaldetalle (solicitud,idsucursal,sucursal,usuario,fecha) VALUES('$folio','$var[1]',UCASE('$var[0]'),'$usuario',current_timestamp())",$link);					
				}
			}
		}
		}
		
		$mensaje ='Los datos han sido guardados correctamente';
		$accion = "autorizar";
		/*Modificacion*/
		if($semanarevision==1){
			$lunesrevision = 0; $martesrevision = 0; $miercolesrevision = 0; $juevesrevision = 0;
			$viernesrevision = 0; $sabadorevision = 0;
		}
		if($semanapago==1){
			$lunespago = 0; $martespago = 0; $miercolespago = 0; $juevespago = 0;
			$viernespago = 0; $sabadopago = 0;
		}
		
	}else if($accion == "autorizar"){
		if($semanarevision==1){
			$lunesrevision = 1; $martesrevision = 1; $miercolesrevision = 1; $juevesrevision = 1;
			$viernesrevision = 1; $sabadorevision = 1;
		}
		if($semanapago==1){
			$lunespago = 1; $martespago = 1; $miercolespago = 1; $juevespago = 1;
			$viernespago = 1; $sabadopago = 1;
		}
		$estado = "AUTORIZADO";
		$s = "UPDATE solicitudcredito SET 
		fechasolicitud='".cambiaf_a_mysql($fecha)."', estado=UCASE('$estado'),
		folioconvenio='".(($folioconvenio!='')? $folioconvenio : 0)."',
		fechaautorizacion='".(($fechaautorizacion!='')?cambiaf_a_mysql($fechaautorizacion):'0000-00-00')."',
		personamoral='$rdmoral', cliente='$cliente', nick=UCASE('$nick'), rfc=UCASE('$rfc'), nombre=UCASE('$nombre'),
		paterno=UCASE('$paterno'), materno=UCASE('$materno'), calle=UCASE('$calle'), 
		numero=UCASE('$numero'), cp=UCASE('$cp'), colonia=UCASE('$colonia'), poblacion=UCASE('$poblacion'),
		municipio=UCASE('$municipio'), estadoc=UCASE('$estado2'), pais=UCASE('$pais'), celular=UCASE('$celular'), 
		telefono=UCASE('$telefono'), email=UCASE('$email'), giro=UCASE('$giro'),
		antiguedad=UCASE('$antiguedad'), representantelegal=UCASE('$representantelegal'),
		actaconstitutiva='".(($actaconstitutiva==1)?1:0)."', numeroacta=UCASE('$nacta'),
		fechaescritura='".(($fechaescritura!='')?cambiaf_a_mysql($fechaescritura):'0000-00-00')."',
		fechainscripcion='".(($fechainscripcion!='')?cambiaf_a_mysql($fechainscripcion):'0000-00-00')."',
		identificacionlegal='".(($identificacion==1)?1:0)."', numeroidentificacion=UCASE('$nidentificacion'),
		hacienda='".(($hacienda==1)?1:0)."',
		fechainiciooperaciones='".(($fechainiciooperaciones!='')?cambiaf_a_mysql($fechainiciooperaciones):'0000-00-00')."',
		rfc2='$rfc2', comprobante='".(($comprobante==1)?1:0)."', comprobanteluz='".(($comprobanteluz==1)?1:0)."',
		estadocuenta='".(($estadocuenta==1)?1:0)."',
		banco=UCASE('$banco'), cuenta='".(($cuenta!='')?$cuenta:0)."', solicitud='".(($solicitud==1)?1:0)."',
		semanapago='".(($semanapago==1)?1:0)."', lunespago='".(($lunespago==1)?1:0)."',
		martespago='".(($martespago==1)?1:0)."', miercolespago='".(($miercolespago==1)?1:0)."',
		juevespago='".(($juevespago==1)?1:0)."', viernespago='".(($viernespago==1)?1:0)."',
		sabadopago='".(($sabadopago==1)?1:0)."', horariopago='$horariopago', apago='$apago',
		responsablepago=UCASE('$responsablepago'),
		celularpago='$celularpago', telefonopago='$telefonopago', faxpago='$faxpago',
		semanarevision='".(($semanarevision==1)?1:0)."', lunesrevision='".(($lunesrevision==1)?1:0)."',
		martesrevision='".(($martesrevision==1)?1:0)."',
		miercolesrevision='".(($miercolesrevision==1)?1:0)."', juevesrevision='".(($juevesrevision==1)?1:0)."',
		viernesrevision='".(($viernesrevision==1)?1:0)."', sabadorevision='".(($sabadorevision==1)?1:0)."',
		horariorevision='$horariorevision',
		arevision='$arevision', montosolicitado='".(($msolicitado!='')?$msolicitado:0)."',
		montoautorizado='".(($mautorizado!='')?$mautorizado:0)."', diascredito='".(($diacredito!='')?$diacredito:0)."',
		observaciones=UCASE('".trim($observaciones)."'), usuario='".$_SESSION[NOMBREUSUARIO]."',
		idusuario=".$_SESSION[IDUSUARIO].", idsucursal=".$_SESSION[IDSUCURSAL].",
		fecha=CURRENT_TIMESTAMP() WHERE folio=".$folio."";
		$r = mysql_query($s,$link) or die($s);
		
		$s = "DELETE FROM solicitudcreditopersonadetalle WHERE solicitud=$folio";
		$r = mysql_query($s,$link) or die($s);
		
		if($registropersona>0){
			for($i=0;$i<$registropersona;$i++){
				$sqldetallep=mysql_query("INSERT INTO solicitudcreditopersonadetalle
				(solicitud,persona,usuario,fecha)VALUES
				('".$folio."',
				UCASE('".$_POST["tablaPersonas_PERSONA"][$i]."'),				
				UCASE('$usuario'), current_timestamp())", $link);
				$cadenapersona .= "{persona:'".$_POST["tablaPersonas_PERSONA"][$i]."'},";
			}
			$cadenapersona = substr($cadenapersona,0,strlen($cadenapersona)-1);
		}
		
		$s = "DELETE FROM solicitudcreditobancodetalle WHERE solicitud=$folio";
		$r = mysql_query($s,$link) or die($s);		
		if($registrobanco>0){
			for($j=0;$j<$registrobanco;$j++){				
				$sqldetalleb=mysql_query("INSERT INTO solicitudcreditobancodetalle
				(solicitud,banco,sucursal,cuenta,telefono,usuario,fecha)VALUES
				('".$folio."',
				UCASE('".$_POST["tablaBanco_BANCO"][$j]."'),
				UCASE('".$_POST["tablaBanco_SUCURSAL"][$j]."'),
				UCASE('".$_POST["tablaBanco_CUENTA"][$j]."'),
				UCASE('".$_POST["tablaBanco_TELEFONO"][$j]."'),
				UCASE('$usuario'), current_timestamp())", $link);
				$cadenabanco .= "{banco:'".$_POST["tablaBanco_BANCO"][$j].
								"',sucursal:'".$_POST["tablaBanco_SUCURSAL"][$j].
								"',cuenta:'".$_POST["tablaBanco_CUENTA"][$j].
								"',telefono:'".$_POST["tablaBanco_TELEFONO"][$j]."'},";
			}
			$cadenabanco = substr($cadenabanco,0,strlen($cadenabanco)-1);
		}
		
		$s = "DELETE FROM solicitudcreditocomercialesdetalle WHERE solicitud=$folio";
		$r = mysql_query($s,$link) or die($s);
		if($registrocomer>0){
			for($k=0;$k<$registrocomer;$k++){				
				$sqldetallec=mysql_query("INSERT INTO solicitudcreditocomercialesdetalle
				(solicitud,empresa,contacto,telefono,usuario,fecha)VALUES
				('".$folio."',
				UCASE('".$_POST["tablaComer_EMPRESA"][$k]."'),
				UCASE('".$_POST["tablaComer_CONTACTO"][$k]."'),
				UCASE('".$_POST["tablaComer_TELEFONO"][$k]."'),
				UCASE('$usuario'), current_timestamp())", $link);
				$cadenacomer .= "{empresa:'".$_POST["tablaComer_EMPRESA"][$k].
								"',contacto:'".$_POST["tablaComer_CONTACTO"][$k].
								"',telefono:'".$_POST["tablaComer_TELEFONO"][$k]."'},";
			}
			$cadenacomer = substr($cadenacomer,0,strlen($cadenacomer)-1);
		}
		
		$s = "DELETE FROM solicitudcreditosucursaldetalle WHERE solicitud=$folio";
		$r = mysql_query($s,$link) or die($s);
		
		if($hidensucursal=="todas" || $hidensucursal=="toda"){
			$sqlins=mysql_query("INSERT INTO solicitudcreditosucursaldetalle
			(solicitud,idsucursal,sucursal,usuario,fecha) VALUES
			('$folio','0',UCASE('TODAS'),'$usuario',current_timestamp())",$link);
		}else if($hidensucursal!=""){
			$coma = ",";
			$hidensucursal = substr($hidensucursal,0,strlen($hidensucursal)-1); 
			$lista=split($coma,$hidensucursal);		
			if(count($lista)>0){
				for ($i=0;$i<count($lista);$i++){
					$var = trim($lista[$i]);				
					$var = split(":",$var);			
					if ($var!=""){				
					$sqlins=mysql_query("INSERT INTO solicitudcreditosucursaldetalle
					(solicitud,idsucursal,sucursal,usuario,fecha)
					VALUES('$folio','$var[1]',UCASE('$var[0]'),'$usuario',current_timestamp())",$link);
					}
				}
			}
		}
		
		$mensaje ='La solicitud de credito a sido Autorizada correctamente';
		if($semanarevision==1){
			$lunesrevision = 0; $martesrevision = 0; $miercolesrevision = 0; $juevesrevision = 0;
			$viernesrevision = 0; $sabadorevision = 0;
		}
		if($semanapago==1){
			$lunespago = 0; $martespago = 0; $miercolespago = 0; $juevespago = 0;
			$viernespago = 0; $sabadopago = 0;
		}
	}else if($accion == "activar"){
		$f = cambiaf_a_mysql($fecha); $fc = cambiaf_a_mysql($fechaautorizacion);
		$fe = cambiaf_a_mysql($fechaescritura); $fi = cambiaf_a_mysql($fechainscripcion);
		$fo = cambiaf_a_mysql($fechainiciooperaciones);
		$estado = "ACTIVADO";
		$cadenapago=(($semanapago==1)?"toda la semana":"");
		if($cadenapago	==  ""){
			$cadenapago .=(($lunespago==1)?"L,":"");
			$cadenapago .=(($martespago==1)?"M,":"");
			$cadenapago .=(($miercolespago==1)?"MI,":"");
			$cadenapago .=(($juevespago==1)?"J,":"");
			$cadenapago .=(($viernespago==1)?"V,":"");
			$cadenapago .=(($sabadopago==1)?"S,":"");
			$cadenapago = substr($cadenapago,0,strlen($cadenapago)-1);
		}
		$cadenarevision=(($semanarevision==1)?"toda la semana":"");
		if($cadenarevision	==  ""){
			$cadenarevision .=(($lunesrevision==1)?"L,":"");
			$cadenarevision .=(($martesrevision==1)?"M,":"");
			$cadenarevision .=(($miercolesrevision==1)?"MI,":"");
			$cadenarevision .=(($juevesrevision==1)?"J,":"");
			$cadenarevision .=(($viernesrevision==1)?"V,":"");
			$cadenarevision .=(($sabadorevision==1)?"S,":"");
			$cadenarevision = substr($cadenarevision,0,strlen($cadenarevision)-1);
		}
$sqlAc = mysql_query("UPDATE solicitudcredito SET estado='$estado', fechaactivacion=CURDATE() WHERE folio=$folio",$link);
$sqlCliente = mysql_query("UPDATE catalogocliente SET foliocredito='$folio',
limitecredito='".$mautorizado."', diascredito='$diacredito', 
diarevision = '$cadenarevision', diapago='$cadenapago', activado='SI'
WHERE id='$cliente'",$link);
$sqlp=mysql_query("SELECT * FROM solicitudcreditopersonadetalle WHERE solicitud=$folio",$link);
	$nump  = mysql_num_rows($sqlp);
		if($nump>0){
			while($rp=mysql_fetch_array($sqlp)){
				$cadenapersona .= "{persona:'".$rp[2]."'},";			
			}
			$cadenapersona = substr($cadenapersona,0,strlen($cadenapersona)-1);
		}
$sqlb=mysql_query("SELECT * FROM solicitudcreditobancodetalle WHERE solicitud=$folio",$link);
	$numb  = mysql_num_rows($sqlb);
		if($numb>0){
			while($rb=mysql_fetch_array($sqlb)){
				$cadenabanco .= "{banco:'".$rb[2].
									"',sucursal:'".$rb[3].
									"',cuenta:'".$rb[4].
									"',telefono:'".$rb[5]."'},";
			}
			$cadenabanco = substr($cadenabanco,0,strlen($cadenabanco)-1);
		}
	$sqlc  = mysql_query("SELECT * FROM solicitudcreditocomercialesdetalle WHERE solicitud=$folio",$link);
	$numc = mysql_num_rows($sqlc);
		if($numc>0){
			while($rc=mysql_fetch_array($sqlc)){
				$cadenacomer .= "{empresa:'".$rc[2].
									"',contacto:'".$rc[3].
									"',telefono:'".$rc[4]."'},";
			}
			$cadenacomer = substr($cadenacomer,0,strlen($cadenacomer)-1);
		}
		$mensaje ='La solicitud de credito a sido Activada correctamente';
		/*Modificacion*/
		$fecha= cambiaf_a_normal($f); 
		$fechaautorizacion= cambiaf_a_normal($fc);
		$fechaescritura= cambiaf_a_normal($fe);
		$fechainscripcion= cambiaf_a_normal($fi);
		$fechainiciooperaciones= cambiaf_a_normal($fo);
	}else if($accion == "noautorizar"){
		$f = cambiaf_a_mysql($fecha); $fc = cambiaf_a_mysql($fechaautorizacion);
		$fe = cambiaf_a_mysql($fechaescritura); $fi = cambiaf_a_mysql($fechainscripcion);
		$fo = cambiaf_a_mysql($fechainiciooperaciones);
		$estado = "NO AUTORIZADA";
$sqlAc = mysql_query("UPDATE solicitudcredito SET estado='$estado' WHERE folio=$folio",$link);
$sqlp=mysql_query("SELECT * FROM solicitudcreditopersonadetalle WHERE solicitud=$folio",$link);
	$nump  = mysql_num_rows($sqlp);
		if($nump>0){
			while($rp=mysql_fetch_array($sqlp)){
				$cadenapersona .= "{persona:'".$rp[2]."'},";			
			}
			$cadenapersona = substr($cadenapersona,0,strlen($cadenapersona)-1);
		}
$sqlb=mysql_query("SELECT * FROM solicitudcreditobancodetalle WHERE solicitud=$folio",$link);
	$numb  = mysql_num_rows($sqlb);
		if($numb>0){
			while($rb=mysql_fetch_array($sqlb)){
				$cadenabanco .= "{banco:'".$rb[2].
									"',sucursal:'".$rb[3].
									"',cuenta:'".$rb[4].
									"',telefono:'".$rb[5]."'},";
			}
			$cadenabanco = substr($cadenabanco,0,strlen($cadenabanco)-1);
		}
	$sqlc  = mysql_query("SELECT * FROM solicitudcreditocomercialesdetalle WHERE solicitud=$folio",$link);
	$numc = mysql_num_rows($sqlc);
		if($numc>0){
			while($rc=mysql_fetch_array($sqlc)){
				$cadenacomer .= "{empresa:'".$rc[2].
									"',contacto:'".$rc[3].
									"',telefono:'".$rc[4]."'},";
			}
			$cadenacomer = substr($cadenacomer,0,strlen($cadenacomer)-1);
		}
		$mensaje ='La solicitud de credito NO fue Autorizada';
		/*Modificacion*/
		$fecha= cambiaf_a_normal($f); 
		$fechaautorizacion= cambiaf_a_normal($fc);
		$fechaescritura= cambiaf_a_normal($fe);
		$fechainscripcion= cambiaf_a_normal($fi);
		$fechainiciooperaciones= cambiaf_a_normal($fo);
	}else if($accion == "limpiar"){
$accion = ""; $fecha = ""; $folio = ""; $folioconvenio = ""; $fechaautorizacion = "";  $rdmoral = ""; $cliente = ""; $nick = ""; $rfc = ""; $nombre = ""; $paterno = ""; $materno = ""; $calle = ""; $numero = ""; $cp = ""; $colonia = ""; $poblacion = ""; $municipio = ""; $estado = ""; $pais = ""; $celular = ""; $telefono = ""; $email = ""; $giro = ""; $antiguedad = ""; $representantelegal = ""; $actaconstitutiva = ""; $identificacion = ""; $hacienda = ""; $comprobante = ""; $estadocuenta = ""; $solicitud = ""; $nacta = ""; $fechaescritura = ""; $fechainscripcion = ""; $nidentificacion = ""; $fechainiciooperaciones = ""; $rfc2 = "";$comprobanteluz = ""; $banco = ""; $cuenta = ""; $estado2 = ""; $semanapago = ""; $lunespago = ""; $martespago = ""; $miercolespago = ""; $juevespago = ""; $viernespago = ""; $sabadopago = ""; $semanarevision = ""; $lunesrevision = ""; $martesrevision = ""; $miercolesrevision = ""; $juevesrevision = ""; $viernesrevision = ""; $sabadorevision = ""; $horariopago = ""; $apago = ""; $responsablepago = ""; $celularpago = ""; $telefonopago = ""; $faxpago = ""; $horariorevision = ""; $arevision = ""; $rbanco = ""; $rsucursal = ""; $rcuenta = ""; $rtelefono = ""; $cempresa = ""; $ccontacto = ""; $ctelefono = ""; $msolicitado = ""; $mautorizado = ""; $observaciones = ""; $registropersona = ""; $registrobanco = ""; $registrocomer = ""; $diacredito = ""; $shpago = ""; $smpago = ""; $ahpago = ""; $ampago = ""; $shrevision = ""; $smrevision = ""; $ahrevision = "";$amrevision = "";
$fecha = date("d/m/Y"); $estado = "SOLICITUD"; $hidensucursal= ""; $sucursalesead1_sel = ""; $todas = "";
$row = ObtenerFolio('solicitudcredito','webpmm'); $folio = $row[0];	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();	
	var tabla2 = new ClaseTabla();
	var tabla3 = new ClaseTabla();
	var nav4   = window.Event ? true : false;
	var Input = '<input  class="Tablas" name="colonia" type="text" id="colonia" size="32" readonly="" value="<?= $colonia; ?>" style="font:tahoma;font-size:9px; background:#FFFF99; text-transform:uppercase" onDblClick="javascript:popUp(\'buscarcolonia2.php\')" />';
	var combo1 = "<select class='Tablas' name='colonia' id='colonia'  style='width:185px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";
	var div = "<div class='ebtn_autorizarcredito' onClick='enviarAutorizacion();'></div>";
	var divNo = "<div class='ebtn_noautorizarcredito' onClick='enviarNoAutorizacion();'></div>";
	var divAc = "<div class='ebtn_activarcredito' onClick='activarCredito();'></div>";		
	var divEnviar = '<div id="btn_Enviarp" class="ebtn_Enviarp"  onClick="validar();"></div>';
	
	jQuery(function($){
	   $('#fechaescritura').mask("99/99/9999");
	   $('#fechainscripcion').mask("99/99/9999");
	   $('#fechainiciooperaciones').mask("99/99/9999");
	});
	
	function validar(){
	if(u.estado.innerHTML=="SOLICITUD"){	
	
		if(u.notieneconvenio.value == ""){
			if(u.folioconvenio.value!=""){
				if(u.folioconvenio.value!=" "){
					if(u.cliente.value != u.clienteconvenio.value){
						alerta("El folio de convenio no coincide con el cliente capturado","¡Atención!","folioconvenio");
						return false;
					}
				}
			}
		}
		
		if(u.cliente.value == ""){			
			alerta('Debe capturar Cliente','¡Atención!','cliente');
			return false;
		}
		if(u.rdmoral[1].checked == true){
			if(u.actaconstitutiva.checked == true){
				if(u.nacta.value == ""){			
				alerta('Debe capturar No. Acta','¡Atención!','nacta');
				return false;
				}else if(u.fechaescritura.value == ""){			
				alerta('Debe capturar Fecha Escritura','¡Atención!','fechaescritura');
				return false;
				}else if(u.fechainscripcion.value == ""){				
				alerta('Debe capturar Fecha de Inscripción','¡Atención!','fechainscripcion');
				return false;
				}
			}
		}
		if(u.identificacion.checked == true){
			if(u.nidentificacion.value == ""){			
		  alerta('Debe capturar No. Identificación','¡Atención!','nidentificacion');
			  return false;
			}
		}
		if(u.hacienda.checked == true){
			if(u.fechainiciooperaciones.value == ""){			
		  alerta('Debe capturar Fecha Inicio Operaciones','¡Atención!','fechainiciooperaciones');
		  	return false;
			}else if(!ValidaRfc(u.rfc2.value)){			
			alerta('Debe capturar un R.F.C valido','¡Atención!','rfc2');
			return false;
			}
		}
		if(u.comprobante.checked == true){
	if(u.comprobanteluz[0].checked == false && u.comprobanteluz[1].checked == false){			
			alerta('Debe capturar un Comprobante ','¡Atención!','comprobanteluz[0]');
			return false;
			}
		}
		if(u.estadocuenta.checked == true){
			if(u.banco.value == ""){				
				alerta('Debe capturar Banco','¡Atención!','banco');
				return false;
			}else if(u.cuenta.value == ""){				
				alerta('Debe capturar Cuenta','¡Atención!','cuenta');
				return false;
			}
		}
		if(u.rdmoral[0].checked == true){
			/*if(u.actaconstitutiva.checked == false || u.identificacion.checked == false
			   || u.hacienda.checked == false || u.comprobante.checked == false
			   || u.estadocuenta.checked == false || u.solicitud.checked == false){
				alerta3('Debe capturar toda la Documentación Requerida para poder enviar la solicitud','¡Atención!');
				return false;
			}*/
		}else{
			/*if(u.identificacion.checked == false || u.hacienda.checked == false 
				|| u.comprobante.checked == false || u.estadocuenta.checked == false 
				|| u.solicitud.checked == false){
				alerta3('Debe capturar toda la Documentación Requerida para poder enviar la solicitud','¡Atención!');
				return false;
			}*/
		}
		if(u.sucursalesead1_sel.options.length == 0){
			 alerta("Debe capturar Sucursal donde aplicara el Crédito","¡Atención!","sucursalesead1");
			 return false;
		}
		if(u.semanapago.checked == false){
			if(u.lunespago.checked == false && u.martespago.checked == false
			   && u.miercolespago.checked == false && u.juevespago.checked == false
			   && u.viernespago.checked == false && u.sabadopago.checked == false){
				alerta3('Debe capturar día de pago','¡Atención!');
				return false;
			}
		}
		
		if(u.semanarevision.checked == false){
			if(u.lunesrevision.checked == false && u.martesrevision.checked == false
			   && u.miercolesrevision.checked == false && u.juevesrevision.checked == false
			   && u.viernesrevision.checked == false && u.sabadorevision.checked == false){
				alerta3('Debe capturar día de revisión','¡Atención!');
				return false;
			}
		}
		
		if(u.msolicitado.value.replace("$ ","").replace(/,/g,"")==""){
			alerta("Debe capturar Monto solicitado","¡Atención!","msolicitado");
			return false;
		}
		
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			u.sucursalorigen.value = '<?=$_SESSION[IDSUCURSAL]?>';
		}
		
		if(u.accion.value == ""){
			u.registrobanco.value = tabla1.getRecordCount();
			u.registrocomer.value = tabla2.getRecordCount();
			u.registropersona.value = tabla3.getRecordCount();
			u.horariopago.value  = u.shpago.value +":"+ u.smpago.value;
			u.apago.value  = u.ahpago.value +":"+ u.ampago.value;
			u.horariorevision.value  = u.shrevision.value +":"+ u.smrevision.value;
			u.arevision.value  = u.ahrevision.value +":"+ u.amrevision.value;
			u.msolicitado.value = u.msolicitado.value.replace("$ ","").replace(/,/g,"");			
			u.accion.value = "grabar";
			
			alerta3("registro","solicitudContratoAperturaCredito_con.php?accion=3&grabar=grabar&folioconvenio="+u.folioconvenio.value+"&fechaautorizacion="+u.fechaautorizacion.value
			+"&rdmoral="+((u.rdmoral[0].checked==true)?1:0)
			+"&cliente="+u.cliente.value+"&nick="+u.nick.value
			+"&rfc="+u.rfc.value+"&nombre="+u.nombre.value+"&paterno="+u.paterno.value
			+"&materno="+u.materno.value+"&calle="+u.calle.value+"&numero="+u.numero.value
			+"&cp="+u.cp.value+"&colonia="+u.colonia.value+"&poblacion="+u.poblacion.value
			+"&municipio="+u.municipio.value+"&pais="+u.pais.value+"&celular="+u.celular.value
			+"&telefono="+u.telefono.value+"&email="+u.email.value+"&estado2="+u.estado2.value
			+"&giro="+u.giro.value+"&antiguedad="+u.antiguedad.value+"&representantelegal="+u.representantelegal.value
			+"&actaconstitutiva="+((u.actaconstitutiva.checked==true)?1:0)+"&nacta="+u.nacta.value
			+"&fechaescritura="+u.fechaescritura.value+"&fechainscripcion="+u.fechainscripcion.value
			+"&identificacion="+((u.identificacion.checked==true)?1:0)+"&nidentificacion="+u.nidentificacion.value
			+"&hacienda="+((u.hacienda.checked==true)?1:0)+"&fechainiciooperaciones="+u.fechainiciooperaciones.value
			+"&rfc2="+u.rfc2.value+"&comprobante="+((u.comprobante.checked==true)?1:0)
			+"&comprobanteluz="+((u.comprobanteluz[0].checked==true)?1:0)
			+"&estadocuenta="+((u.estadocuenta.checked==true)?1:0)+"&banco="+u.banco.value
			+"&cuenta="+u.cuenta.value+"&solicitud="+((u.solicitud.checked==true)?1:0)
			+"&semanapago="+((u.semanapago.checked==true)?1:0)
			+"&lunespago="+((u.lunespago.checked==true)?1:0)
			+"&martespago="+((u.martespago.checked==true)?1:0)
			+"&miercolespago="+((u.miercolespago.checked==true)?1:0)
			+"&juevespago="+((u.juevespago.checked==true)?1:0)
			+"&viernespago="+((u.viernespago.checked==true)?1:0)
			+"&sabadopago="+((u.sabadopago.checked==true)?1:0)
			+"&horariopago="+u.horariopago.value+"&apago="+u.apago.value
			+"&responsablepago="+u.responsablepago.value
			+"&celularpago="+u.celularpago.value+"&telefonopago="+u.telefonopago.value
			+"&faxpago="+u.faxpago.value
			+"&semanarevision="+((u.semanarevision.checked==true)?1:0)
			+"&lunesrevision="+((u.lunesrevision.checked==true)?1:0)
			+"&martesrevision="+((u.martesrevision.checked==true)?1:0)
			+"&miercolesrevision="+((u.miercolesrevision.checked==true)?1:0)
			+"&juevesrevision="+((u.juevesrevision.checked==true)?1:0)
	 		+"&viernesrevision="+((u.viernesrevision.checked==true)?1:0)
			+"&sabadorevision="+((u.sabadorevision.checked==true)?1:0)
			+"&horariorevision="+u.horariorevision.value
			+"&arevision="+u.arevision.value
			+"&msolicitado="+u.msolicitado.value.replace("$ ","").replace(/,/g,"")
			+"&mautorizado="+u.mautorizado.value.replace("$ ","").replace(/,/g,"")
			+"&diacredito="+u.diacredito.value
			+"&observaciones="+u.observaciones.value);
			
		}else if(u.accion.value == "modificar"){			
			u.registrobanco.value = tabla1.getRecordCount();
			u.registrocomer.value = tabla2.getRecordCount();
			u.registropersona.value = tabla3.getRecordCount();
			u.hpago.value  = u.shpago.value +":"+ u.smpago.value;
			u.apago.value  = u.ahpago.value +":"+ u.ampago.value;
			u.hrevision.value  = u.shrevision.value +":"+ u.smrevision.value;
			u.arevision.value  = u.ahrevision.value +":"+ u.amrevision.value;
			u.msolicitado.value = u.msolicitado.value.replace("$ ","").replace(/,/g,"");
			u.accion.value = "modificar";
			//document.form1.submit();	
		}
	}else if(u.estado.innerHTML=="EN AUTORIZACION"){
			alerta3('La Solicitud de credito ya fue enviada','¡Atención!');	
	}else if(u.estado.innerHTML=="AUTORIZADO"){
			alerta3('La Solicitud de credito ya fue Autorizada','¡Atención!');	
	}else if(u.estado.innerHTML=="ACTIVADO"){
			alerta3('La Solicitud de credito ya fue Activado','¡Atención!');	
	}else if(u.estado.innerHTML=="NO AUTORIZADO"){
			alerta3('La Solicitud de credito No fue Autorizado','¡Atención!');	
	}
}	tabla1.setAttributes({
		nombre:"tablaBanco", 
		campos:[
			{nombre:"BANCO", medida:126, alineacion:"left", datos:"banco"},
			{nombre:"SUCURSAL", medida:126, alineacion:"left", datos:"sucursal"},
			{nombre:"CUENTA", medida:126, alineacion:"left", datos:"cuenta"},
{nombre:"TELEFONO", medida:126, alineacion:"left", datos:"telefono"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"modificarBanco()",
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
		nombre:"tablaComer",
		campos:[
			{nombre:"EMPRESA", medida:168, alineacion:"left", datos:"empresa"},
			{nombre:"CONTACTO", medida:168, alineacion:"left", datos:"contacto"},
			{nombre:"TELEFONO", medida:168, alineacion:"left", datos:"telefono"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:true,
		eventoDblClickFila:"modificarComercial()",
		nombrevar:"tabla2"
	});
	
	tabla3.setAttributes({
		nombre:"tablaPersonas",
		campos:[
			{nombre:"PERSONA", medida:310, alineacion:"left", datos:"persona"}			
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:true,	
		eventoDblClickFila:"modificarPersona()",
		nombrevar:"tabla3"
	});	
	
	window.onload = function(){
	u.cliente.focus();
	tabla1.create();
	tabla2.create();
	tabla3.create();
	
	obtenerDetalles();
		<?
			$_GET[funcion2] = str_replace("\'","'",$_GET[funcion2]);
			if($_GET[funcion2]!=""){
				echo 'setTimeout("'.$_GET[funcion2].'",1500);';
			}
		?>
	}	
	
	function obtenerClienteBusqueda(id){
		u.cliente.value = id;
		consulta("mostrarCliente","consultasCredito.php?accion=2&cliente="+id);
	}
	function obtenerCliente(e,id){
		var persona;
		tecla = (u) ? e.keyCode : e.which;
		((u.rdmoral[0].checked == true) ? persona="SI" : persona="NO");
		if(tecla == 13 && id!=""){
			u.cliente.value = id;
			consulta("mostrarCliente","consultasCredito.php?accion=2&cliente="+id+"&persona="+persona);
		}
	}
	function mostrarCliente(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		limpiarCliente();
		if(con>0){
		if(u.folioconvenio.value!=""){
			if(u.folioconvenio.value!=" "){
				if(u.cliente.value != u.clienteconvenio.value){
					u.folioconvenio.value = "";
					u.clienteconvenio.value = "";
					alerta("El folio de convenio no coincide con el cliente capturado","¡Atención!","folioconvenio");
					return false;
				}
			}
		}			
			if(datos.getElementsByTagName('estadocredito').item(0).firstChild.data.replace(" ","")!=""){
	alerta3("El Cliente "+datos.getElementsByTagName('nombre').item(0).firstChild.data+" ya se le realizo una solicitud de crédito, la cual se encuentra en estado "+datos.getElementsByTagName('estadocredito').item(0).firstChild.data+".","¡Atención!");
				return false;
			}
			if(datos.getElementsByTagName('foliocredito').item(0).firstChild.data!="0"){
alerta3("El Cliente "+datos.getElementsByTagName('nombre').item(0).firstChild.data+" ya cuenta con crédito.","¡Atención!");
				return false;
			}
		u.nick.value 		= datos.getElementsByTagName('nick').item(0).firstChild.data;
		u.rfc.value 		= datos.getElementsByTagName('rfc').item(0).firstChild.data;
		u.rfc2.value 		= datos.getElementsByTagName('rfc').item(0).firstChild.data;
		u.nombre.value		= datos.getElementsByTagName('nombre').item(0).firstChild.data;
		u.paterno.value 	= datos.getElementsByTagName('paterno').item(0).firstChild.data;	
		u.materno.value 	= datos.getElementsByTagName('materno').item(0).firstChild.data;
		u.celular.value 	= datos.getElementsByTagName('celular').item(0).firstChild.data;
		u.email.value 		= datos.getElementsByTagName('email').item(0).firstChild.data;
		u.folioconvenio.value = datos.getElementsByTagName('convenio').item(0).firstChild.data;			
		
		if(datos.getElementsByTagName('idcliente').item(0).firstChild.data=="0"){
			u.notieneconvenio.value = "no tiene";
		}else{
			u.clienteconvenio.value = datos.getElementsByTagName('idcliente').item(0).firstChild.data;
		}
		
		
var endir = datos.getElementsByTagName('dir').item(0).firstChild.data;
if(endir==1){
		document.all.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:180px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>';
				u.numero.value =datos.getElementsByTagName('numero').item(0).firstChild.data;
				u.cp.value =datos.getElementsByTagName('cp').item(0).firstChild.data;
				u.colonia.value =datos.getElementsByTagName('colonia').item(0).firstChild.data;
				u.poblacion.value =datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;
				u.pais.value =datos.getElementsByTagName('pais').item(0).firstChild.data;
				u.telefono.value =datos.getElementsByTagName('telefono').item(0).firstChild.data;
				u.estado2.value =datos.getElementsByTagName('estado').item(0).firstChild.data;
				u.calle.value =datos.getElementsByTagName('calle').item(0).firstChild.data;
			}else if(endir>1){
			var comb = "<select name='calle' style='width:165px;font:tahoma; font-size:9px' onchange='"
			+"document.all.numero.value=this.options[this.selectedIndex].numero;"
			+"document.all.cp.value=this.options[this.selectedIndex].cp;"
			+"document.all.colonia.value=this.options[this.selectedIndex].colonia;"
			+"document.all.poblacion.value=this.options[this.selectedIndex].poblacion;"
			+"document.all.municipio.value=this.options[this.selectedIndex].municipio;"
			+"document.all.estado2.value=this.options[this.selectedIndex].estado2;"
			+"document.all.pais.value=this.options[this.selectedIndex].pais;"
			+"document.all.telefono.value=this.options[this.selectedIndex].telefono;"
			+"'>";
				
				for(var i=0; i<endir; i++){
					
					v_calle 		= datos.getElementsByTagName('calle').item(i).firstChild.data;
					v_numero		= datos.getElementsByTagName('numero').item(i).firstChild.data;
					v_cp 			= datos.getElementsByTagName('cp').item(i).firstChild.data;
					v_colonia		= datos.getElementsByTagName('colonia').item(i).firstChild.data;
					v_poblacion 	= datos.getElementsByTagName('poblacion').item(i).firstChild.data;
					v_municipio 	= datos.getElementsByTagName('municipio').item(i).firstChild.data;
					v_pais 			= datos.getElementsByTagName('pais').item(i).firstChild.data;
					v_telefono 		= datos.getElementsByTagName('telefono').item(i).firstChild.data;
					v_estado2 		= datos.getElementsByTagName('estado').item(i).firstChild.data;
					v_fact			= datos.getElementsByTagName('facturacion').item(i).firstChild.data;
		
					if(i==0){					
						u.numero.value 		= v_numero;
						u.cp.value 			= v_cp;
						u.colonia.value 	= v_colonia;
						u.poblacion.value 	= v_poblacion;
						u.telefono.value 	= v_telefono;
						u.municipio.value 	= v_municipio;
						u.pais.value 		= v_pais;
						u.telefono.value 	= v_telefono;
						u.estado2.value 	= v_estado2;						
					}else if(v_fact=="SI"){
						u.numero.value 		= v_numero;
						u.cp.value 			= v_cp;
						u.colonia.value 	= v_colonia;
						u.poblacion.value 	= v_poblacion;
						u.telefono.value 	= v_telefono;
						u.municipio.value 	= v_municipio;
						u.pais.value 		= v_pais;
						u.telefono.value 	= v_telefono;
						u.estado2.value 	= v_estado2;
					}
					
					comb += "<option "+ ((v_fact=="SI")? "selected " : "" ) +" value='"+v_calle+"' numero='"+v_numero+"'" 
					+"cp='"+v_cp+"' colonia='"+v_colonia+"'"
					+" poblacion='"+v_poblacion+"' telefono='"+v_telefono+"'"
					+" municipio='"+v_municipio+"' pais='"+v_pais+"'"
					+" telefono='"+v_telefono+"' estado='"+v_estado2+"'>"
					+v_calle+"</option>";					
				}
				comb += "</select>";
				document.all.celda_des_calle.innerHTML = comb;
			}
			u.giro.focus();
		}else{
			if(u.rdmoral[0].checked == true){
			alerta('El numero del cliente no existe o no es una persona moral','¡Atención!','cliente');
			}else{
			alerta('El numero del cliente no existe o no es una persona fisica','¡Atención!','cliente');
			}
		}	
		
}
	function limpiarCliente(){
	 	u.nick.value = ""; 		u.rfc.value = ""; 
		u.nombre.value = ""; 	u.paterno.value = ""; 
		u.materno.value = ""; 	u.calle.value = ""; 
		u.numero.value = ""; 	u.cp.value = ""; 
		u.colonia.value = ""; 	u.poblacion.value=""; 
		u.municipio.value=""; 	u.estado2.value = ""; 
		u.pais.value = ""; 		u.celular.value = ""; 
		u.telefono.value=""; 	u.email.value = "";
		u.clienteconvenio.value = ""; u.notieneconvenio.value = "";		
	}
	function validarDocumentacion(nombre){
		if(nombre == "actaconstitutiva" && u.actaconstitutiva.checked == false){
			u.nacta.value 			 = "";
			u.fechaescritura.value 	 = "";
			u.fechainscripcion.value = "";
			u.nacta.readOnly		 	 			= true;
			u.fechaescritura.readOnly 	 			= true;
			u.fechainscripcion.readOnly  			= true;
			u.nacta.style.backgroundColor			= "#FFFF99";
			u.fechaescritura.style.backgroundColor	= "#FFFF99";
			u.fechainscripcion.style.backgroundColor = "#FFFF99";	
			closeCalendar();
		}else if(nombre == "actaconstitutiva" && u.actaconstitutiva.checked == true){
			u.nacta.readOnly		 	 			= false;
			u.fechaescritura.readOnly 	 			= false;
			u.fechainscripcion.readOnly  			= false;
			u.nacta.style.backgroundColor			= "";
			u.fechaescritura.style.backgroundColor	= "";
			u.fechainscripcion.style.backgroundColor = "";
			u.nacta.focus();
		}else if(nombre == "identificacion" && u.identificacion.checked == false){
			u.nidentificacion.value	 				= "";
			u.nidentificacion.readOnly  			= true;
			u.nidentificacion.style.backgroundColor	= "#FFFF99";
		}else if(nombre == "identificacion" && u.identificacion.checked == true){
			u.nidentificacion.value	 				= "";
			u.nidentificacion.readOnly  			= false;
			u.nidentificacion.style.backgroundColor	= "";
			u.nidentificacion.focus();
		}else if(nombre == "hacienda" && u.hacienda.checked == false){
			u.fechainiciooperaciones.value					= "";			
			u.fechainiciooperaciones.readOnly  				= true;
			u.fechainiciooperaciones.style.backgroundColor	= "#FFFF99";
			u.rfc2.readOnly  								= true;
			u.rfc2.style.backgroundColor					= "#FFFF99";
			closeCalendar();
		}else if(nombre == "hacienda" && u.hacienda.checked == true){
			u.fechainiciooperaciones.value					= "";			
			u.fechainiciooperaciones.readOnly  				= false;
			u.fechainiciooperaciones.style.backgroundColor	= "";
			u.rfc2.readOnly  								= false;
			u.rfc2.style.backgroundColor					= "";
			u.fechainiciooperaciones.focus();
		}else if(nombre == "comprobante" && u.comprobante.checked == false){
			u.comprobanteluz[0].checked = false;
			u.comprobanteluz[1].checked = false;
			u.comprobanteluz[0].disabled = true;
			u.comprobanteluz[1].disabled = true;
		}else if(nombre == "comprobante" && u.comprobante.checked == true){
			u.comprobanteluz[0].disabled = false;
			u.comprobanteluz[1].disabled = false;
			u.comprobanteluz[0].focus();
		}else if(nombre == "estadocuenta" && u.estadocuenta.checked == false){
			u.banco.value			 = "";
			u.cuenta.value			 = "";
			u.banco.readOnly 		 = true;
			u.cuenta.readOnly 		 = true;
			u.cuenta.style.backgroundColor = "#FFFF99";
			u.banco.style.backgroundColor  = "#FFFF99";
		}else if(nombre == "estadocuenta" && u.estadocuenta.checked == true){
			u.banco.value			 = "";
			u.cuenta.value			 = "";
			u.cuenta.style.backgroundColor = "";
			u.banco.style.backgroundColor  = "";
			u.banco.readOnly 		 = false;
			u.cuenta.readOnly 		 = false;
			u.banco.focus();
		}
	}
	
	function agregarBanco(){
		if(u.rbanco.value!="" && u.rsucursal.value!="" && u.rcuenta.value!="" && u.rtelefono.value!=""){
			if(u.index.value!=""){
				eliminarBanco();
				var registro 		= new Object();
				registro.banco 		= u.rbanco.value;
				registro.sucursal 	= u.rsucursal.value;
				registro.cuenta 	= u.rcuenta.value;
				registro.telefono 	= u.rtelefono.value;
				tabla1.add(registro);
				u.rbanco.value		= "";
				u.rsucursal.value	= "";
				u.rcuenta.value		= "";
				u.rtelefono.value	= "";
				u.index.value		= "";
				u.rbanco.focus();
			}else{
				var registro 		= new Object();
				registro.banco 		= u.rbanco.value;
				registro.sucursal 	= u.rsucursal.value;
				registro.cuenta 	= u.rcuenta.value;
				registro.telefono 	= u.rtelefono.value;
				tabla1.add(registro);
				u.rbanco.value		= "";
				u.rsucursal.value	= "";
				u.rcuenta.value		= "";
				u.rtelefono.value	= "";
				u.rbanco.focus();
				
			}
		}else{
			if(u.rbanco.value == ""){
				alerta('Debe capturar Banco','¡Atención!','rbanco');
			}else if(u.rsucursal.value == ""){
				alerta('Debe capturar Sucursal','¡Atención!','rsucursal');				
			}else if(u.rcuenta.value == ""){
				alerta('Debe capturar Cuenta','¡Atención!','rcuenta');				
			}else if(u.rtelefono.value == ""){
				alerta('Debe capturar Teléfono','¡Atención!','rtelefono');				
			}
		}
	}
	function agregarComercial(){
		if(u.cempresa.value!="" && u.ccontacto.value!="" && u.ctelefono.value!=""){
			if(u.index.value!=""){
				eliminarComercial();
				var registro2 		= new Object();
				registro2.empresa 	= u.cempresa.value;
				registro2.contacto	= u.ccontacto.value;
				registro2.telefono  = u.ctelefono.value;
				tabla2.add(registro2);
				u.cempresa.value 	= "";
				u.ccontacto.value	= "";
				u.ctelefono.value	= "";
				u.index.value		= "";
				u.cempresa.focus();
			}else{
				var registro2 		= new Object();
				registro2.empresa 	= u.cempresa.value;
				registro2.contacto	= u.ccontacto.value;
				registro2.telefono  = u.ctelefono.value;
				tabla2.add(registro2);
				u.cempresa.value 	= "";
				u.ccontacto.value	= "";
				u.ctelefono.value	= "";
				u.cempresa.focus();
			}
		}else{
			if(u.cempresa.value == ""){
				alerta('Debe capturar Empresa','¡Atención!','cempresa');
			}else if(u.ccontacto.value == ""){
				alerta('Debe capturar Contacto','¡Atención!','ccontacto');				
			}else if(u.ctelefono.value == ""){
				alerta('Debe capturar Teléfono','¡Atención!','ctelefono');				
			}
		}
	}
	function agregarPersona(){
		if(u.persona.value!=""){
			if(u.index.value!=""){
				eliminarPersona();
				var registro3 		= new Object();
				registro3.persona	= u.persona.value;
				tabla3.add(registro3);
				u.persona.value 	= "";
				u.index.value 		= "";
				u.persona.focus();
			}else{
				var registro3 		= new Object();
				registro3.persona	= u.persona.value;
				tabla3.add(registro3);
				u.persona.value 	= "";
				u.persona.focus();
			}
		}else{
			alerta('Debe capturar Persona Autorizada','¡Atención!','persona');
		}
	}
	function borrarPersona(){
		if(tabla3.getValSelFromField('persona','PERSONA')!=""){
		confirmar('¿Esta seguro de Eliminar a la persona?','','eliminarPersona()','');	
		}
	}
	function eliminarPersona(){
	  tabla3.deleteById(tabla3.getSelectedIdRow());
	}
	function modificarPersona(){		
		if(tabla3.getValSelFromField('persona','PERSONA')!=""){
			u.persona.value = tabla3.getValSelFromField('persona','PERSONA');
			u.index.value = tabla3.getSelectedIndex();
		}
	}
	function borrarBanco(){
		if(tabla1.getValSelFromField('banco','BANCO')!=""){
			confirmar('¿Esta seguro de Eliminar el Banco?','','eliminarBanco()','');	
		}
	}
	function eliminarBanco(){
	  tabla1.deleteById(tabla1.getSelectedIdRow());
	}
	function modificarBanco(){
		if(tabla1.getValSelFromField('banco','BANCO')!=""){
			u.rbanco.value = tabla1.getValSelFromField('banco','BANCO');
			u.rsucursal.value = tabla1.getValSelFromField('sucursal','SUCURSAL');
			u.rcuenta.value = tabla1.getValSelFromField('cuenta','CUENTA');
			u.rtelefono.value = tabla1.getValSelFromField('telefono','TELEFONO');
			u.index.value = tabla1.getSelectedIndex();
		}
	}
	function borrarComercial(){
		if(tabla2.getValSelFromField('empresa','EMPRESA')!=""){
			confirmar('¿Esta seguro de Eliminar la Empresa?','','eliminarComercial()','');	
		}
	}
	function eliminarComercial(){
	  tabla2.deleteById(tabla2.getSelectedIdRow());
	}
	function modificarComercial(){
		if(tabla2.getValSelFromField('empresa','EMPRESA')!=""){
			u.cempresa.value = tabla2.getValSelFromField('empresa','EMPRESA');
			u.ccontacto.value = tabla2.getValSelFromField('contacto','CONTACTO');
			u.ctelefono.value = tabla2.getValSelFromField('telefono','TELEFONO');
			u.index.value = tabla2.getSelectedIndex();
		}
	}
	function borrarIndex(e,obj){
		tecla= (u) ? e.keyCode : e.which;
    	if((tecla==8 || tecla==46) && document.getElementById(obj).value==""){
			u.index.value = "";
		}
	}
	function obtener(id){
		u.folio.value = id;
		consultaTexto("mostrarDatos","solicitudContratoAperturaCredito_con.php?accion=1&credito="+id);
	}
	function mostrarDatos(datos){
		if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){
			limpiarDatos();
			var obj = eval(convertirValoresJson(datos));			
			u.fecha.value 			= obj.principal.fechasolicitud;
			u.estado.innerHTML 		= obj.principal.estado;
			u.folioconvenio.value   = ((obj.principal.folioconvenio!="0")?obj.principal.folioconvenio:"");
			u.fechaautorizacion.value = obj.principal.fechaautorizacion;
			if(obj.principal.personamoral==1){
				u.rdmoral[0].checked = true;
			}else{
				u.rdmoral[1].checked = true;
			}
			u.cliente.value = obj.principal.cliente;	
			u.giro.value= obj.principal.giro;
			u.antiguedad.value = obj.principal.antiguedad;
			u.representantelegal.value = obj.principal.representantelegal;
			u.actaconstitutiva.checked = ((obj.principal.actaconstitutiva==1)?true:false)
			u.nacta.value = obj.principal.numeroacta;	
			u.fechaescritura.value = ((obj.principal.fechaescritura!="00/00/0000")?obj.principal.fechaescritura:"");
			u.fechainscripcion.value = ((obj.principal.fechainscripcion!="00/00/0000")?obj.principal.fechainscripcion:"");	
			u.identificacion.checked = ((obj.principal.identificacionlegal==1)?true:false);
			u.nidentificacion.value = obj.principal.numeroidentificacion;
			u.hacienda.checked = ((obj.principal.hacienda==1)?true:false);
u.fechainiciooperaciones.value = ((obj.principal.fechainiciooperaciones!="00/00/0000")?obj.principal.fechainiciooperaciones:"");
			u.rfc2.value = obj.principal.rfc2;
			u.comprobante.checked = ((obj.principal.comprobante==1)?true:false);			
			if(obj.principal.comprobanteluz==1){
				u.comprobanteluz[0].checked = true ;
			}else{
				u.comprobanteluz[1].checked = true ;
			}
			u.estadocuenta.checked = ((obj.principal.estadocuenta==1)?true:false);	
			u.banco.value = obj.principal.banco;
			u.cuenta.value = obj.principal.cuenta;
			u.solicitud.checked = ((obj.principal.solicitud==1)?true:false);
			u.semanapago.checked =((obj.principal.semanapago==1)?true:false);
			u.lunespago.checked = ((obj.principal.lunespago==1)?true:false);
			u.martespago.checked = ((obj.principal.martespago==1)?true:false);
			u.miercolespago.checked = ((obj.principal.miercolespago==1)?true:false);
			u.juevespago.checked = ((obj.principal.juevespago==1)?true:false);
			u.viernespago.checked = ((obj.principal.viernespago==1)?true:false);
			u.sabadopago.checked = ((obj.principal.sabadopago==1)?true:false);
			activarPago();
			desactivarSemanaPago();
			u.horariopago.value = obj.principal.horariopago;
			u.apago.value = obj.principal.apago;
			var hpago = u.horariopago.value.split(":");
				u.shpago.value = hpago[0];
				u.smpago.value = hpago[1];				
			var apa = u.apago.value.split(":");
				u.ahpago.value = apa[0];
				u.ampago.value = apa[1];
			u.responsablepago.value=obj.principal.responsablepago;
			u.celularpago.value= obj.principal.celularpago;
			u.telefonopago.value= obj.principal.telefonopago;
			u.faxpago.value= obj.principal.faxpago;						
			if(obj.principal.idcliente=="0"){
				u.notieneconvenio.value = "no tiene";
			}else{
				u.clienteconvenio.value = obj.principal.idcliente;
			}			
			u.semanarevision.checked 	= ((obj.principal.semanarevision==1)?true:false);
			u.lunesrevision.checked 	= ((obj.principal.lunesrevision==1)?true:false);
			u.martesrevision.checked 	= ((obj.principal.martesrevision==1)?true:false);
			u.miercolesrevision.checked = ((obj.principal.miercolesrevision==1)?true:false);
			u.juevesrevision.checked 	= ((obj.principal.juevesrevision==1)?true:false);
			u.viernesrevision.checked 	= ((obj.principal.viernesrevision==1)?true:false);
			u.sabadorevision.checked 	= ((obj.principal.sabadorevision==1)?true:false);
			activarRevision();
			desactivarSemanaRevision();
			u.horariorevision.value=obj.principal.horariorevision;
			u.arevision.value = obj.principal.arevision;
			var hrevision = u.horariorevision.value.split(":");
				u.shrevision.value = hrevision[0];
				u.smrevision.value = hrevision[1];
			var arev = u.arevision.value.split(":");
				u.ahrevision.value = arev[0];
				u.amrevision.value = arev[1];			
			u.msolicitado.value = '$ '+numcredvar(obj.principal.montosolicitado);
			u.mautorizado.value = '$ '+numcredvar(obj.principal.montoautorizado);			
			u.observaciones.value= obj.principal.observaciones;
			u.diacredito.value = obj.principal.diascredito;
			u.nick.value = obj.principal.nick;
			u.rfc.value = obj.principal.rfc;
			u.nombre.value = obj.principal.nombre;
			u.paterno.value = obj.principal.paterno;
			u.materno.value = obj.principal.materno;
			u.celular.value = obj.principal.celular;
			u.email.value = obj.principal.email;			
			u.calle.value = obj.principal.calle;
			u.numero.value = obj.principal.numero;
			u.cp.value = obj.principal.cp;			
			u.colonia.value =obj.principal.colonia;
			u.poblacion.value =obj.principal.poblacion;
			u.municipio.value =obj.principal.municipio;			
			u.estado2.value =obj.principal.estadoc;
			u.pais.value =obj.principal.pais;
			u.telefono.value =obj.principal.telefono;
			habilitarCajas();
						
			agregarValores(u.sucursalesead1_sel,obj.sucursales);			
			
			if(u.estado.innerHTML == "EN AUTORIZACION" ){
				u.accion.value ="autorizar";
				u.mautorizado.disabled = false;
				u.mautorizado.style.backgroundColor	= "";
				u.diacredito.style.backgroundColor	= "";
				u.diacredito.disabled = false;
				u.col.innerHTML = div; u.colno.innerHTML = divNo;
			}
			if(u.estado.innerHTML == "AUTORIZADO"){
				u.accion.value ="activar";
				u.mautorizado.disabled = false;
				u.mautorizado.style.backgroundColor	= "";
				u.diacredito.style.backgroundColor	= "";
				u.diacredito.disabled = false;
				u.col.innerHTML = divAc;
			}
			if(u.estado.innerHTML == "ACTIVADO" || u.estado.innerHTML == "BLOQUEADO"){
				u.btn_Enviarp.style.visibility = "hidden";
				u.mautorizado.disabled = false;
				u.mautorizado.readOnly = true;
				u.mautorizado.style.backgroundColor	= "";
				u.diacredito.style.backgroundColor	= "";
				u.diacredito.disabled = false;
				u.diacredito.readOnly = true;
				u.sucursalesead1_sel.disabled = true;
			}			
			u.todas.checked = ((obj.idsucursal == "0")?true:false);	
			if(u.todas.checked==true){
				u.hidensucursal.value = "TODAS";
			}
			if(obj.idsucursal != "0"){
				u.hidensucursal.value = obj.idsucursal;
			}
			tabla1.setJsonData(obj.banco);
			tabla2.setJsonData(obj.comerciales);
			tabla3.setJsonData(obj.persona);
		}
	}
	function agregarValores(combo,objeto){		
		combo.options.length = 0;
		var opcion;
		for(var i=0; i<objeto.length; i++){
			opcion = new Option(objeto[i].nombre,objeto[i].clave);			
			combo.options[combo.options.length] = opcion;
		}
	}
	
	function limpiar(tipo){		
	u.fecha.value = ""; u.estado.innerHTML = "SOLICITUD"; u.folioconvenio.value = "";
	u.fechaautorizacion.value = ""; 
	 u.rdmoral[0].checked = false;
	u.rdmoral[1].checked = false; u.cliente.value = "";	u.giro.value= "";
	u.antiguedad.value = ""; u.representantelegal.value = ""; 
	u.actaconstitutiva.checked = false; u.nacta.value= ""; 	u.fechaescritura.value = "";	
	u.fechainscripcion.value = ""; 	u.identificacion.checked = false;
	u.nidentificacion.value = ""; u.hacienda.checked = false; 
	u.fechainiciooperaciones.value = ""; u.rfc2.value = "";	u.comprobante.checked = false ;
	u.comprobanteluz[0].checked = false ; u.comprobanteluz[1].checked = false ;
	u.estadocuenta.checked = false ; u.banco.value = ""; u.cuenta.value = "";	
	u.solicitud.checked = false ; u.semanapago.checked = false ; u.lunespago.checked = false ;
	u.martespago.checked = false ; u.miercolespago.checked = false ;
	u.juevespago.checked = false ; u.viernespago.checked = false ;
	u.sabadopago.checked = false ; u.horariopago.value = ""; u.apago.value = "";		
	u.responsablepago.value=""; u.celularpago.value= ""; u.telefonopago.value= "";
	u.faxpago.value= ""; u.semanarevision.checked = false ; u.lunesrevision.checked = false ;
	u.martesrevision.checked = false ;	u.miercolesrevision.checked = false;
	u.juevesrevision.checked = false; u.viernesrevision.checked = false;
	u.sabadorevision.checked = false; u.horariorevision.value="";
	u.arevision.value = "";	u.msolicitado.value=""; u.mautorizado.value= "";
	u.observaciones.value= ""; u.diacredito.value = ""; u.nick.value = "";
	u.rfc.value =""; u.nombre.value =""; u.paterno.value =""; u.materno.value ="";
	u.calle.value =""; 	u.numero.value =""; u.cp.value =""; u.colonia.value ="";
	u.poblacion.value =""; u.municipio.value = ""; 	u.pais.value ="";
	u.celular.value =""; u.telefono.value =""; u.email.value ="";
	u.estado2.value =""; u.accion.value =""; u.mautorizado.disabled = true;
	u.mautorizado.style.backgroundColor	= "#FFFF99";
	u.diacredito.style.backgroundColor	= "#FFFF99"; u.diacredito.disabled = true;
	//u.btn_Enviarp.style.visibility = "visible";
	u.col.innerHTML = divEnviar;
	tabla1.clear(); tabla2.clear(); tabla3.clear();
				if(tipo == 1){
					u.accion.value = "limpiar";
					document.form1.submit();
				}
}
	function limpiarDatos(){
		u.fecha.value = ""; u.estado.innerHTML = "SOLICITUD"; u.folioconvenio.value = "";
	u.fechaautorizacion.value = ""; 
	 u.rdmoral[0].checked = false;
	u.rdmoral[1].checked = false; u.cliente.value = "";	u.giro.value= "";
	u.antiguedad.value = ""; u.representantelegal.value = ""; 
	u.actaconstitutiva.checked = false; u.nacta.value= ""; 	u.fechaescritura.value = "";	
	u.fechainscripcion.value = ""; 	u.identificacion.checked = false;
	u.nidentificacion.value = ""; u.hacienda.checked = false; 
	u.fechainiciooperaciones.value = ""; u.rfc2.value = "";	u.comprobante.checked = false ;
	u.comprobanteluz[0].checked = false ; u.comprobanteluz[1].checked = false ;
	u.estadocuenta.checked = false ; u.banco.value = ""; u.cuenta.value = "";	
	u.solicitud.checked = false ; u.semanapago.checked = false ; u.lunespago.checked = false ;
	u.martespago.checked = false ; u.miercolespago.checked = false ;
	u.juevespago.checked = false ; u.viernespago.checked = false ;
	u.sabadopago.checked = false ; u.horariopago.value = ""; u.apago.value = "";		
	u.responsablepago.value=""; u.celularpago.value= ""; u.telefonopago.value= "";
	u.faxpago.value= ""; u.semanarevision.checked = false ; u.lunesrevision.checked = false ;
	u.martesrevision.checked = false ;	u.miercolesrevision.checked = false;
	u.juevesrevision.checked = false; u.viernesrevision.checked = false;
	u.sabadorevision.checked = false; u.horariorevision.value="";
	u.arevision.value = "";	u.msolicitado.value=""; u.mautorizado.value= "";
	u.observaciones.value= ""; u.diacredito.value = ""; u.nick.value = "";
	u.rfc.value =""; u.nombre.value =""; u.paterno.value =""; u.materno.value ="";
	u.calle.value =""; 	u.numero.value =""; u.cp.value =""; u.colonia.value ="";
	u.poblacion.value =""; u.municipio.value = ""; 	u.pais.value ="";
	u.celular.value =""; u.telefono.value =""; u.email.value ="";
	u.estado2.value =""; u.accion.value =""; u.mautorizado.disabled = true;
	u.mautorizado.style.backgroundColor	= "#FFFF99";
	u.diacredito.style.backgroundColor	= "#FFFF99"; u.diacredito.disabled = true;
	u.col.innerHTML = divEnviar;
	
	tabla1.clear(); tabla2.clear(); tabla3.clear();
	}	
	function enviarAutorizacion(){
		if(u.folioconvenio.value!=""){
			if(u.folioconvenio.value!=" "){
				if(u.cliente.value != u.clienteconvenio.value){
					alerta("El folio de convenio no coincide con el cliente capturado","¡Atención!","folioconvenio");
					return false;
				}
			}
		}
		if(u.sucursalesead1_sel.options.length == 0){
			 alerta("Debe capturar Sucursal donde aplicara el Crédito","¡Atención!","sucursalesead1");
			 return false;
		}
		if(u.mautorizado.value.replace("$ ","").replace(/,/g,"")=="0.00"){
			alerta('Debe capturar Monto Autorizado','¡Atención!','mautorizado');			
		}else if(u.mautorizado.value.replace("$ ","").replace(/,/g,"") <= "0"){
			alerta('Debe capturar Monto Autorizado','¡Atención!','mautorizado');
		}else if(u.diacredito.value==""){
			alerta('Debe capturar Dias de Credito','¡Atención!','diacredito');
		}else if(u.diacredito.value <= "0"){
			alerta('Debe capturar Dias de Credito','¡Atención!','diacredito');
		}else{
			confirmar('¿Esta seguro de Autorizar el Credito?','','autorizarCredito()','');
		}
	}
	function autorizarCredito(){
		u.registrobanco.value = tabla1.getRecordCount();
		u.registrocomer.value = tabla2.getRecordCount();
		u.registropersona.value = tabla3.getRecordCount();
		u.mautorizado.value = u.mautorizado.value.replace("$ ","").replace(/,/g,"");
		u.msolicitado.value = u.msolicitado.value.replace("$ ","").replace(/,/g,"");
		u.accion.value = "autorizar";
		document.form1.submit();
	}
	function enviarNoAutorizacion(){
		confirmar('¿Esta seguro de NO Autorizar el Credito?','','noautorizarCredito()','');
	}
	function noautorizarCredito(){
		u.mautorizado.value = u.mautorizado.value.replace("$ ","").replace(/,/g,"");
		u.msolicitado.value = u.msolicitado.value.replace("$ ","").replace(/,/g,"");
		u.accion.value = "noautorizar";
		document.form1.submit();
	}
	function activarCredito(){
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			u.sucursalorigen.value = '<?=$_SESSION[IDSUCURSAL]?>';
		}
			confirmar('¿Esta seguro de Activar el Credito?','','actCredito()','');
	}
	function actCredito(){
		u.mautorizado.value = u.mautorizado.value.replace("$ ","").replace(/,/g,"");
		u.msolicitado.value = u.msolicitado.value.replace("$ ","").replace(/,/g,"");
		u.accion.value = "activar";
		document.form1.submit();
	}
	function obtenerDetalles(){
		var datosPersona = <? if($cadenapersona!=""){echo "[".$cadenapersona."]";}else{echo "0";} ?>;
		var datosBanco 	 = <? if($cadenabanco!=""){echo "[".$cadenabanco."]";}else{echo "0";} ?>;
		var datosComer   = <? if($cadenacomer!=""){echo "[".$cadenacomer."]";}else{echo "0";} ?>;	
		if(datosPersona!=0){
			for(var i=0; i<datosPersona.length;i++){
				tabla3.add(datosPersona[i]);
			}
		}
		
		if(datosBanco!=0){
			for(var i=0; i<datosBanco.length;i++){
				tabla1.add(datosBanco[i]);
			}
		}
		
		if(datosComer!=0){
			for(var i=0; i<datosComer.length;i++){
				tabla2.add(datosComer[i]);
			}
		}
		if(u.mautorizado.value!=""){
			u.mautorizado.value='$ '+numcredvar(u.mautorizado.value.replace('$ ','').replace(/,/g,''));
		}
		
		if(u.msolicitado.value!=""){
			u.msolicitado.value='$ '+numcredvar(u.msolicitado.value.replace('$ ','').replace(/,/g,''));
		}
		if(document.all.estado.innerHTML!=""){
			u.fechaescritura.value 			= ((u.fechaescritura.value == "//")?"":u.fechaescritura.value);
			u.fechainscripcion.value 		= ((u.fechainscripcion.value == "//")?"":u.fechainscripcion.value);
			u.fechainiciooperaciones.value	= ((u.fechainiciooperaciones.value == "//")?"":u.fechainiciooperaciones.value);
			u.fechaautorizacion.value 		= ((u.fechaautorizacion.value == "//")?"":u.fechaautorizacion.value);
		}
		
		if(u.semanapago.checked == true){
			u.lunespago.disabled = true; u.martespago.disabled = true; u.miercolespago.disabled = true;
			u.juevespago.disabled = true; u.viernespago.disabled = true; u.sabadopago.disabled = true;
		}
		
		if(u.semanarevision.checked == true){
			u.lunesrevision.disabled = true; u.martesrevision.disabled = true; u.miercolesrevision.disabled = true;
			u.juevesrevision.disabled = true; u.viernesrevision.disabled = true; u.sabadorevision.disabled = true;
		}
		
		if(u.hidensucursal.value.toUpperCase == "TODAS" || u.hidensucursal.value.toUpperCase == "TODA"){
			u.todas.checked = true;
			agregarTodasSucursales();
		}
		
	}
	function foco(nombrecaja){
	if(nombrecaja=="cliente"){
		u.oculto.value="1";
	}else if(nombrecaja=="folioconvenio"){
		u.oculto.value="2";
	}
}
	shortcut.add("Ctrl+b",function() {
		if(u.oculto.value=="1"){
				if(u.rdmoral[0].checked==true){
	abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda&tipo=moral', 550, 450, 'ventana', 'Busqueda');
				   }else{
	abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda&tipo=fisica', 550, 450, 'ventana', 'Busqueda');
				   }
		}else if(u.oculto.value=="2"){
	abrirVentanaFija('buscarConvenio.php',550, 450, 'ventana', 'Busqueda')
		}
	});
	function tabular(e,obj){
				tecla=(document.all) ? e.keyCode : e.which;
				if(tecla!=13) return;
				frm=obj.form;
				for(i=0;i<frm.elements.length;i++) 
					if(frm.elements[i]==obj) 
					{ 
						if (i==frm.elements.length-1) 
							i=-1;
						break
					}
			   
				 if (frm.elements[i+1].disabled ==true )    
					tabular(e,frm.elements[i+1]);
				else if (frm.elements[i+1].readOnly ==true )    
					tabular(e,frm.elements[i+1]);
				else frm.elements[i+1].focus();
				return false;
	} 
	function mostrarSolicitudCreditoPendientes(){
		abrirVentanaFija('buscarCredito.php?accion=1', 550, 450, 'ventana', 'Busqueda');
	}
	function mostrarSolicitudCreditoPendientesActivar(){
		abrirVentanaFija('buscarCredito.php?accion=2', 550, 450, 'ventana', 'Busqueda');		
	}
	function ValidaRfc(rfcStr) {
	var strCorrecta;
	strCorrecta = rfcStr;
	
	if (u.rfc2.value.length == "12"){
		var valid = '^(([A-Z]|[a-z]|[&]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
	}else if(u.rfc2.value.length == "13"){
		var valid = '^(([A-Z]|[a-z]|[&]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
	}else{
		return false;	
	}
	var validRfc=new RegExp(valid);
	var matchArray=strCorrecta.match(validRfc);
	if (matchArray==null) {	
		return false;
	}else{
	return true;
	}
	
}
	function validarFecha(e,param,name){
		tecla = (document.all) ? e.keyCode : e.which;
		if(tecla == 13 || tecla == 9){
			if(param!=""){
				var mes  =  parseInt(param.substring(3,5),10);
				var dia  =  parseInt(param.substring(0,2),10);
				if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){
					alerta('La fecha no es valida', '¡Atención!',name);
					return false;
				}
				if (dia>"31" || dia=="0" ){
	alerta('La fecha no es valida, capture correctamente el Dia', '¡Atención!',name);
					return false;	
				}
				if (mes>"12" || mes=="0" ){
	alerta('La fecha no es valida, capture correctamente el Mes', '¡Atención!',name);
					return false;	
				}	
			}	
		}
	}	
	function habilitarCajas(){
		if(u.actaconstitutiva.checked == true){
			u.nacta.readOnly		 	 			= false;
			u.fechaescritura.readOnly 	 			= false;
			u.fechainscripcion.readOnly  			= false;
			u.nacta.style.backgroundColor			= "";
			u.fechaescritura.style.backgroundColor	= "";
			u.fechainscripcion.style.backgroundColor = "";			
		}
		if(u.identificacion.checked == true){		
			u.nidentificacion.readOnly  			= false;
			u.nidentificacion.style.backgroundColor	= "";			
		}
		if(u.hacienda.checked == true){			
			u.fechainiciooperaciones.readOnly  				= false;
			u.fechainiciooperaciones.style.backgroundColor	= "";
			u.rfc2.readOnly  								= false;
			u.rfc2.style.backgroundColor					= "";			
		}
		if(u.comprobante.checked == true){
			u.comprobanteluz[0].disabled = false;
			u.comprobanteluz[1].disabled = false;		
		}
		if(u.estadocuenta.checked == true){			
			u.cuenta.style.backgroundColor = "";
			u.banco.style.backgroundColor  = "";
			u.banco.readOnly 		 = false;
			u.cuenta.readOnly 		 = false;			
		}
	}
	function Numeros(evt){ 
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	}	
	
	function insertarServicio(combo, valor, va, nombre, tipo){
		var ubi = document.all;
		if(combo.value!=""){			
			for(var i=0; i<va.options.length; i++){
				if(va.options[i].value==valor){
					alerta3(nombre+" seleccionado ya fue agregado","¡Atencion!");
					combo.value="";
					return false;
				}
			}
			//ubi.servrestring.value = 0;
			var opcion = new Option(combo.options[combo.selectedIndex].text,combo.value);
			va.options[va.options.length] = opcion;
			u.hidensucursal.value += combo.options[combo.selectedIndex].text+":"+combo.value+",";
			combo.value="";			
		}
	}
	function borrarServicio(va,tipo){
		if(va.options.selectedIndex>-1){			
		var frase = u.hidensucursal.value.replace(u.sucursalesead1_sel.options[u.sucursalesead1_sel.selectedIndex].text+":"+u.sucursalesead1_sel.value,"");			u.hidensucursal.value = frase.replace(",,",",");
			if(u.hidensucursal.value.substring(0,1)==","){
				u.hidensucursal.value = u.hidensucursal.value.substring(1,u.hidensucursal.value.legth);
			}
			va.options[va.options.selectedIndex] = null;
			va.value = "";
		}
	}
	function agregarTodasSucursales(){
		if(u.todas.checked==true){
			u.hidensucursal.value = "todas";
			for(var i=1; i<u.sucursalesead1.options.length; i++){
				var opcion = new Option(u.sucursalesead1.options[i].text,u.sucursalesead1.value);
			u.sucursalesead1_sel.options[u.sucursalesead1_sel.options.length] = opcion;		
			}
			u.sucursalesead1_sel.disabled = true;
		}else{
			u.sucursalesead1_sel.options.length = 0;
			u.hidensucursal.value = "";
			u.sucursalesead1_sel.disabled = false;
		}
	}	
	function obtenerConvenio(id){
		u.folioconvenio.value = id;
		consultaTexto("mostrarConvenio","solicitudContratoAperturaCredito_con.php?accion=2&folio="+id);
	}
	function mostrarConvenio(datos){
		if(datos!="" && datos!=undefined){
			u.clienteconvenio.value = datos;
		}
		
		if(u.notieneconvenio.value == "no tiene"){
			alerta("El folio de convenio no coincide con el cliente capturado","¡Atención!","folioconvenio");
			u.folioconvenio.value = "";
			return false;
		}
	}
	function activarPago(){
		if(u.semanapago.checked == true){
			u.lunespago.disabled = true; u.martespago.disabled = true;
			u.miercolespago.disabled = true; u.juevespago.disabled = true;
			u.viernespago.disabled = true; u.sabadopago.disabled = true;
		}else{
			u.lunespago.disabled = false; u.martespago.disabled = false;
			u.miercolespago.disabled = false; u.juevespago.disabled = false;
			u.viernespago.disabled = false; u.sabadopago.disabled = false;
		}
	}
	function activarRevision(){
		if(u.semanarevision.checked == true){
			u.lunesrevision.disabled = true; u.martesrevision.disabled = true;
			u.miercolesrevision.disabled = true; u.juevesrevision.disabled = true;
			u.viernesrevision.disabled = true; u.sabadorevision.disabled = true;
		}else{
			u.lunesrevision.disabled = false; u.martesrevision.disabled = false;
			u.miercolesrevision.disabled = false; u.juevesrevision.disabled = false;
			u.viernesrevision.disabled = false; u.sabadorevision.disabled = false;
		}
	}
	function desactivarSemanaPago(){
		if(u.lunespago.checked==true){u.semanapago.disabled = true;}
		if(u.martespago.checked==true){u.semanapago.disabled = true;}
		if(u.miercolespago.checked==true){u.semanapago.disabled = true;}
		if(u.juevespago.checked==true){u.semanapago.disabled = true;}
		if(u.viernespago.checked==true){u.semanapago.disabled = true;}
		if(u.sabadopago.checked==true){u.semanapago.disabled = true;}
		if(u.lunespago.checked==false && u.martespago.checked==false
		   && u.miercolespago.checked==false && u.juevespago.checked==false
		   && u.viernespago.checked==false && u.sabadopago.checked==false){
			u.semanapago.disabled = false;
		}
	}
	function desactivarSemanaRevision(){
		if(u.lunesrevision.checked==true){u.semanarevision.disabled = true;}
		if(u.martesrevision.checked==true){u.semanarevision.disabled = true;}
		if(u.miercolesrevision.checked==true){u.semanarevision.disabled = true;}
		if(u.juevesrevision.checked==true){u.semanarevision.disabled = true;}
		if(u.viernesrevision.checked==true){u.semanarevision.disabled = true;}
		if(u.sabadorevision.checked==true){u.semanarevision.disabled = true;}
		if(u.lunesrevision.checked==false && u.martesrevision.checked==false
		   && u.miercolesrevision.checked==false && u.juevesrevision.checked==false
		   && u.viernesrevision.checked==false && u.sabadorevision.checked==false){
			u.semanarevision.disabled = false;
		}
	}
	function mostrarEstado(estado){
		u.estado.innerHTML = estado;
	}
	function validarPersona(){
		if(u.rdmoral[0].checked == true){			
			u.cliente.value = "";
			u.folioconvenio.value = "";
			limpiarCliente();
		}else if(u.rdmoral[1].checked == true){
			u.cliente.value = "";
			u.folioconvenio.value = "";
			limpiarCliente();
		}
	}
	function numcredvar(cad){
		var flag = false; 
		if(cad.indexOf('.') == cad.length - 1) flag = true; 
		var num = cad.split(',').join(''); 
		cad = Number(num).toLocaleString(); 
		if(flag) cad += '.'; 
		return cad;
	}
</script>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="solicitudContratoAperturaCredito.php">  
<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="535" class="FondoTabla Estilo4">SOLICITUD DE CR&Eacute;DITO </td>
  </tr>
  <tr>
    <td><table width="590" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="536" colspan="6">
          <table width="532" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width="25">&nbsp;</td>
            <td width="165">&nbsp;</td>
            <td width="40">Fecha:</td>
            <td width="111"><span class="Tablas">
              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
            </span></td>
            <td width="45">Estado:</td>
            <td width="146"  id="estado" style="font:tahoma; font-size:15px; font-weight:bold"></td>
          </tr>
        </table></td>
      </tr>
      
      <tr>
        <td colspan="6"><table width="535" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width="27">Folio:</td>
            <td width="98"><span class="Tablas">
              <input name="folio" type="text" class="Tablas" id="folio" style="width:50px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
              <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" 
              onClick="abrirVentanaFija('buscarCredito.php?accion=3', 600, 550, 'ventana', 'Busqueda')"></span></td>
            <td width="76">Folio Convenio:</td>
            <td width="55"><span class="Tablas">
              <input name="folioconvenio" type="text" class="Tablas" id="folioconvenio" style="width:50px" value="<?=$folioconvenio ?>"/>
            </span></td>
            <td width="69"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarConvenioGen.php?funcion=obtenerConvenio&cestado=ACTIVADO', 550, 450, 'ventana', 'Busqueda')"></div></td>
            <td width="100">Fecha Autorizaci&oacute;n: </td>
            <td width="110"><span class="Tablas">
              <input name="fechaautorizacion" type="text" class="Tablas" id="fechaautorizacion" style="width:100px;background:#FFFF99" value="<?=$fechaautorizacion ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="6"><script>mostrarEstado('<?=$estado ?>');</script></td>
      </tr>
      <tr>
        <td colspan="6" class="FondoTabla Estilo4">Datos Clientes </td>
      </tr>
      <tr>
        <td colspan="6"><table width="589" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="4"><label>
              <input name="rdmoral" type="radio" onClick="validarPersona()" value="1" <? if($rdmoral=="1" || $rdmoral==""){ echo'checked'; } ?> />
              Persona Moral</label>
              <label>
                <input name="rdmoral" type="radio" onClick="validarPersona()" value="0" <? if($rdmoral=="0"){ echo'checked'; } ?> />
                Persona F&iacute;sica</label></td>
            </tr>
          <tr>
            <td width="93"># Cliente:              </td>
            <td colspan="3"><span class="Tablas">
              <input name="cliente" type="text" class="Tablas" id="cliente" style="width:100px" value="<?=$cliente ?>" onKeyPress="obtenerCliente(event,this.value);" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" />
              <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" 
              onClick="if(document.all.rdmoral[0].checked==true){
              abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda&tipo=moral', 550, 450, 'ventana', 'Busqueda')
               }else{                             abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda&tipo=fisica', 550, 450, 'ventana', 'Busqueda')
               }
              "></span></td>
          </tr>
          <tr>
            <td>Nick:</td>
            <td width="194"><span class="Tablas">
              <input name="nick" type="text" class="Tablas" id="nick" style="width:140px;background:#FFFF99" value="<?=$nick ?>" readonly=""/>
              </span></td>
            <td width="77"><span class="Tablas">R.F.C.:</span></td>
            <td width="225"><span class="Tablas">
              <input name="rfc" type="text" class="Tablas" id="rfc" style="width:140px;background:#FFFF99" value="<?=$rfc ?>" readonly=""/>
              </span></td>
            </tr>
          <tr>
            <td>Nombre:</td>
            <td colspan="3"><span class="Tablas">
              <input name="nombre" type="text" class="Tablas" id="nombre" style="width:410px;background:#FFFF99" value="<?=$nombre ?>" readonly=""/>
              </span></td>
            </tr>
          <tr>
            <td><span class="Tablas">Ap. Paterno:</span></td>
            <td><span class="Tablas">
              <input name="paterno" type="text" class="Tablas" id="paterno" style="width:140px;background:#FFFF99" value="<?=$paterno ?>" readonly=""/>
              </span></td>
            <td>Ap. Materno:</td>
            <td><span class="Tablas">
              <input name="materno" type="text" class="Tablas" id="materno" style="width:140px;background:#FFFF99" value="<?=$materno ?>" readonly=""/>
              </span></td>
            </tr>
          <tr>
            <td><span class="Tablas">
              <label>Calle</label>
              :
              </span></td>
            <td id="celda_des_calle"><span class="Tablas">
              <input name="calle" type="text" class="Tablas" id="calle" style="width:180px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>
              <input type="hidden" name="rem_direcciones">
            </span></td>
            <td><span class="Tablas">N&uacute;mero: </span></td>
            <td><span class="Tablas">
              <input name="numero" type="text" class="Tablas" id="numero" style="width:82px;background:#FFFF99" value="<?=$numero ?>" readonly=""/>
            </span></td>
            </tr>
          <tr>
            <td>CP:</td>
            <td><input name="cp" type="text" class="Tablas" id="cp" style="width:140px;background:#FFFF99" value="<?=$cp ?>" readonly=""/></td>
            <td>Colonia:</td>
            <td><input name="colonia" type="text" class="Tablas" id="colonia" style="width:140px;background:#FFFF99" value="<?=$colonia ?>" readonly=""/></td>
            </tr>
          <tr>
            <td>Poblaci&oacute;n:</td>
            <td><input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:140px;background:#FFFF99" value="<?=$poblacion ?>" readonly=""/></td>
            <td>Mun. / Deleg.:</td>
            <td><input name="municipio" type="text" class="Tablas" id="municipio" style="width:140px;background:#FFFF99" value="<?=$municipio ?>" readonly=""/></td>
            </tr>
          <tr>
            <td>Estado</td>
            <td><input name="estado2" type="text" class="Tablas" id="estado2" style="width:140px;background:#FFFF99" value="<?=$estado2 ?>" readonly=""/></td>
            <td>Pa&iacute;s:</td>
            <td><input name="pais" type="text" class="Tablas" id="pais" style="width:140px;background:#FFFF99" value="<?=$pais ?>" readonly=""/></td>
            </tr>
          <tr>
            <td>Celular:</td>
            <td><input name="celular" type="text" class="Tablas" id="celular" style="width:140px;background:#FFFF99" value="<?=$celular ?>" readonly=""/></td>
            <td>Telefono:</td>
            <td><input name="telefono" type="text" class="Tablas" id="telefono" style="width:140px;background:#FFFF99" value="<?=$telefono ?>" readonly=""/></td>
            </tr>
          <tr>
            <td>Email:</td>
            <td><input name="email" type="text" class="Tablas" id="email" style="width:140px;background:#FFFF99" value="<?=$email ?>" readonly=""/></td>
            <td>Giro:</td>
            <td><span class="Tablas">
              <input name="giro" type="text" class="Tablas" id="giro" style="width:140px" value="<?=$giro ?>" onKeyPress="return tabular(event,this)" />
              </span></td>
            </tr>
          <tr>
            <td>Antiguedad:</td>
            <td><span class="Tablas">
              <input name="antiguedad" onKeyPress="return tabular(event,this)" type="text" class="Tablas" id="antiguedad" style="width:140px" value="<?=$antiguedad ?>" />
              </span></td>
            <td>Repte. Legal:</td>
            <td><span class="Tablas">
              <input name="representantelegal" type="text" class="Tablas" id="representantelegal" style="width:140px" onKeyPress="return tabular(event,this)" value="<?=$representantelegal ?>" />
              </span></td>
          </tr>
          </table></td>
      </tr>
      <tr>
        <td colspan="6" class="FondoTabla Estilo4">Documentacion Requerida </td>
      </tr>
      <tr>
        <td colspan="6"><table width="599" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="21"><input type="checkbox" name="actaconstitutiva" style="width:13px" value="1" id="actaconstitutiva"<? if($actaconstitutiva==1){echo "checked";} ?> onClick="validarDocumentacion(this.name);" onKeyPress="return tabular(event,this)"></td>
            <td width="95">Acta Constitutiva</td>
            <td width="49">No. Acta:</td>
            <td width="89"><span class="Tablas">
              <input name="nacta" type="text" class="Tablas" id="nacta" style="background:#FF9;width:80px" value="<?=$nacta ?>" readonly="readonly" onKeyPress="return tabular(event,this)" />
              </span></td>
            <td width="66">F. Escritura:</td>
            <td width="103"><span class="Tablas">
              <input name="fechaescritura" type="text" class="Tablas" id="fechaescritura" style="background:#FF9;width:75px" value="<?=$fechaescritura ?>" readonly="readonly" onKeyPress="validarFecha(event,this.value,this.name); return tabular(event,this)" />
              <img src="../img/calendario.gif" width="16" height="16" style="cursor:pointer" onClick="if(document.all.fechaescritura.readOnly==false){displayCalendar(document.all.fechaescritura,'dd/mm/yyyy',this)}">            </span></td>
            <td width="75">F. Inscripcion:</td>
            <td width="101"><span class="Tablas">
              <input name="fechainscripcion" type="text" class="Tablas" id="fechainscripcion" style="background:#FF9;width:75px" value="<?=$fechainscripcion ?>" readonly="readonly" onKeyPress="validarFecha(event,this.value,this.name); return tabular(event,this)"  />
              <img src="../img/calendario.gif" width="16" height="16" style="cursor:pointer" onClick="if(document.all.fechainscripcion.readOnly==false){displayCalendar(document.all.fechainscripcion,'dd/mm/yyyy',this)}"></span></td>
            </tr>
          <tr>
            <td><input type="checkbox" name="identificacion" style="width:13px" value="1" id="identificacion" <? if($identificacion==1){echo "checked";} ?> onClick="validarDocumentacion(this.name);" onKeyPress="return tabular(event,this)"></td>
            <td colspan="3">Identificaci&oacute;n Representante Legal</td>
            <td colspan="3">No. Identificaci&oacute;n:<span class="Tablas">
              <input name="nidentificacion" type="text" class="Tablas" id="nidentificacion" style="background:#FF9;width:100px" value="<?=$nidentificacion ?>" readonly="readonly" onKeyPress="return tabular(event,this)" />
              </span></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td><input type="checkbox" name="hacienda" style="width:13px" value="1" id="hacienda" <? if($hacienda==1){echo "checked";} ?> onClick="validarDocumentacion(this.name);" onKeyPress="return tabular(event,this)"></td>
            <td>Alta Hacienda</td>
            <td>&nbsp;</td>
            <td colspan="3">F. Inicio Operaciones:<span class="Tablas">
              <input name="fechainiciooperaciones" type="text" class="Tablas" id="fechainiciooperaciones" style="background:#FF9;width:75px" value="<?=$fechainiciooperaciones ?>" readonly="readonly" onKeyPress="validarFecha(event,this.value,this.name); return tabular(event,this)" />
              <img src="../img/calendario.gif" width="16" height="16" style="cursor:pointer" onClick="if(document.all.fechainiciooperaciones.readOnly==false){displayCalendar(document.all.fechainiciooperaciones,'dd/mm/yyyy',this)}"></span></td>
            <td colspan="2">R.F.C.:<span class="Tablas">
              <input name="rfc2" type="text" class="Tablas" id="rfc2" style="background:#FF9;width:100px" value="<?=$rfc2 ?>" readonly="readonly" onKeyPress="return tabular(event,this)" />
              </span></td>
            </tr>
          <tr>
            <td><input type="checkbox" name="comprobante" style="width:13px" value="1" id="comprobante" <? if($comprobante==1){echo "checked";} ?> onClick="validarDocumentacion(this.name);" onKeyPress="return tabular(event,this)"></td>
            <td colspan="2">Comprobante Domicilio</td>
            <td colspan="2"><input name="comprobanteluz" type="radio" style="width:13px" value="1"<? if($comprobanteluz=="1"){echo "checked";} ?> disabled onKeyPress="return tabular(event,this)">
              Luz
              <input name="comprobanteluz" type="radio" style="width:13px" value="0" <? if($comprobanteluz=="0"){echo "checked";} ?> disabled onKeyPress="return tabular(event,this)">
              Tel&eacute;fono</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td><input type="checkbox" name="estadocuenta" style="width:13px" value="1" id="estadocuenta" <? if($estadocuenta==1){echo "checked";} ?> onClick="validarDocumentacion(this.name);" onKeyPress="return tabular(event,this)"></td>
            <td>Estado de Cuenta</td>
            <td>Banco:</td>
            <td colspan="2"><span class="Tablas">
              <input name="banco" type="text" class="Tablas" id="banco" style="background:#FF9;width:100px" value="<?=$banco ?>" readonly="readonly" onKeyPress="return tabular(event,this)" />
              </span></td>
            <td colspan="2">Cuenta:<span class="Tablas">
              <input name="cuenta" type="text" class="Tablas" id="cuenta" style="background:#FF9;width:100px" value="<?=$cuenta ?>" readonly="readonly" onKeyPress="return tabular(event,this)" />
              </span></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td><input type="checkbox" name="solicitud" style="width:13px" value="1" id="solicitud" <? if($solicitud==1){echo "checked";} ?> onKeyPress="return tabular(event,this)"></td>
            <td>Solicitud</td>
            <td><script>habilitarCajas();</script></td>
            <td>&nbsp;</td>
            <td><input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td colspan="6"><table width="600" bordercolor="#016193" border="1" cellpadding="0" cellspacing="0">
          <tr class="FondoTabla">
            <td width="340">Revisi&oacute;n y Pago</td>
            <td width="254">Sucursal(es) Aplicara Credito</td>
            </tr>
          <tr>
            <td><table width="340" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>Persona autorizada(s)  revisi&oacute;n:
                  <input name="persona" class="Tablas" onKeyUp="return borrarIndex(event,this.name)" type="text" id="persona" onKeyPress="if(event.keyCode==13){agregarPersona();}" style="width:100px" value="<?=$persona ?>">
                  <img src="../img/Boton_Agregari.gif" width="70" height="20" align="absbottom" style="cursor:pointer" onClick="agregarPersona();"></td>
              </tr>
              <tr>
                <td><table width="290" border="0" cellpadding="0" cellspacing="0" id="tablaPersonas" name="tablaPersonas" >
                </table></td>
              </tr>
              <tr>
                <td><img src="../img/Boton_Eliminar.gif"  width="70" height="20" align="middle" style="cursor:pointer" onClick="borrarPersona();"></td>
              </tr>
            </table></td>
            <td><table width="255" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td width="206" class="Tablas"><input type="checkbox" name="todas" style="width:13px" value="1" id="todas" <? if($todas==1){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="agregarTodasSucursales();">Todas
                    <select class="Tablas" name="sucursalesead1" style="width:150px" onChange="insertarServicio(this, this.value, document.all.sucursalesead1_sel, 'La Sucursal', 'SUCONVENIO')")>
                      <option value=""></option>
                      <? 	
					$s = "select id, descripcion from catalogosucursal where id > 1 ORDER BY descripcion ASC";
					$r = mysql_query($s,$link) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
                      <option value="<?=$f->id?>">
                        <?=$f->descripcion?>
                        </option>
                      <?
					}
				?>
                    </select></td>
                </tr>
              <tr>
                <td><p>
                  <select name="sucursalesead1_sel" size="7" style="width:200px" ondblclick="borrarServicio(this, 'SUCONVENIO')">		
                  <?
					$s = mysql_query("SELECT sucursal FROM solicitudcreditosucursaldetalle WHERE solicitud=$folio",$link);					
					if(mysql_num_rows($s)>0){
						$row = mysql_fetch_array($s);
						if($row[0]=="TODAS"){
							$s = mysql_query("SELECT descripcion FROM catalogosucursal ORDER BY descripcion ASC ",$link);
							while($r = mysql_fetch_array($s)){
							?> <option><?=$r[0]; ?></option><?	
							}							
						}else{
							?> <option><?=$row[0]; ?></option><?	
							while($r = mysql_fetch_array($s)){
							?> <option><?=$r[0]; ?></option><?	
							}
						}
					}
				?>
                  </select>
                </p>
                  <p>
                    <input name="hidensucursal" type="hidden" style="width:50px" id="hidensucursal" value="<?=$hidensucursal ?>">
                  </p></td>
                </tr>
              </table></td>
          </tr>
          </table>         </td>
      </tr>
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" class="FondoTabla">D&iacute;as Pago </td>
      </tr>
      <tr>
        <td colspan="6"><table width="584" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="20"><label>
              <input type="checkbox" name="semanapago" style="width:13px" value="1" id="semanapago" <? if($semanapago=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="activarPago();">
            </label>
              <label></label></td>
            <td width="80"><label>Toda la Semana</label>
              <label> </label></td>
            <td width="20"><input type="checkbox" style="width:13px" name="lunespago" value="1" id="lunespago" <? if($lunespago=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaPago()"></td>
            <td width="10">L</td>
            <td width="20"><input type="checkbox" style="width:13px" name="martespago" value="1" id="martespago" <? if($martespago=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaPago()"></td>
            <td width="10">M</td>
            <td width="20"><input type="checkbox" name="miercolespago" style="width:13px" value="1" id="miercolespago" <? if($miercolespago=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaPago()"></td>
            <td width="14">MI</td>
            <td width="20"><input type="checkbox" name="juevespago" style="width:13px" value="1" id="juevespago" <? if($juevespago=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaPago()"></td>
            <td width="10">J</td>
            <td width="20"><input type="checkbox" name="viernespago" style="width:13px" value="1" id="viernespago" <? if($viernespago=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaPago()"></td>
            <td width="10">V</td>
            <td width="20"><input type="checkbox" name="sabadopago" style="width:13px" value="1" id="sabadopago" <? if($sabadopago=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaPago()"></td>
            <td width="38">S</td>
            <td width="272"><label>Horario:<span class="Tablas">
              <select name="shpago" size="1" onKeyPress="return tabular(event,this)" class="Tablas" id="shpago">
                <? for($h=0;$h<24;$h++){ ?>
                <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                  <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                  </option>
                <? }?>
              </select>
              <select name="smpago" size="1" onKeyPress="return tabular(event,this)" class="Tablas" id="smpago">
                <? for($m=0;$m<60;$m++){ ?>
                <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                  <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                  </option>
                <? }?>
              </select>
            </span></label>
              <label>a<span class="Tablas">
                <select name="ahpago" size="1" onKeyPress="return tabular(event,this)" class="Tablas" id="ahpago">
                  <? for($h=0;$h<24;$h++){ ?>
                  <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                  <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                  </option>
                <? }?>
              </select>
              <select name="ampago" size="1" onKeyPress="return tabular(event,this)" class="Tablas" id="ampago">
                <? for($m=0;$m<60;$m++){ ?>
                <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                  <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                  </option>
                <? }?>
              </select>
              </span></label></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="6">Personas Responsable de Pagos: <span class="Tablas">
          <input name="responsablepago" type="text" class="Tablas" id="responsablepago" style="width:350px" value="<?=$responsablepago ?>" onKeyPress="return tabular(event,this)" />
        </span></td>
      </tr>
      <tr>
        <td colspan="6">Celular:<span class="Tablas">
          <input name="celularpago" type="text" class="Tablas" id="celularpago" style="width:120px" value="<?=$celularpago ?>" onKeyPress="return tabular(event,this)" />
        </span>Tel&eacute;fono:<span class="Tablas">
        <input name="telefonopago" type="text" class="Tablas" id="telefonopago" style="width:120px" value="<?=$telefonopago ?>" onKeyPress="return tabular(event,this)" />
        </span>Fax:<span class="Tablas">
        <input name="faxpago" type="text" class="Tablas" id="faxpago" style="width:120px" value="<?=$faxpago ?>" onKeyPress="return tabular(event,this)" />
        </span></td>
      </tr>
      <tr>
        <td colspan="6" class="FondoTabla">D&iacute;as Revisi&oacute;n </td>
      </tr>
      <tr>
        <td colspan="6"><table width="586" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="20"><label>
              <input type="checkbox" name="semanarevision" value="1" id="semanarevision" <? if($semanarevision=="1"){echo "checked";} ?> style="width:13px" onKeyPress="return tabular(event,this)" onClick="activarRevision();">
              </label>
                <label></label></td>
            <td width="80"><label>Toda la Semana</label>
                <label> </label></td>
            <td width="20"><input type="checkbox" name="lunesrevision" style="width:13px" value="1" id="lunesrevision" <? if($lunesrevision=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaRevision();"></td>
            <td width="10">L</td>
            <td width="20"><input type="checkbox" name="martesrevision" style="width:13px" value="1" id="martesrevision" <? if($martesrevision=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaRevision();" ></td>
            <td width="10">M</td>
            <td width="20"><input type="checkbox" name="miercolesrevision" style="width:13px" value="1" id="miercolesrevision" <? if($miercolesrevision=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaRevision();"></td>
            <td width="14">MI</td>
            <td width="20"><input type="checkbox" name="juevesrevision" style="width:13px" value="1" id="juevesrevision" <? if($juevesrevision=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaRevision();"></td>
            <td width="10">J</td>
            <td width="20"><input type="checkbox" name="viernesrevision" style="width:13px" value="1" id="viernesrevision" <? if($viernesrevision=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaRevision();"></td>
            <td width="10">V</td>
            <td width="20"><input type="checkbox" name="sabadorevision" style="width:13px" value="1" id="sabadorevision" <? if($sabadorevision=="1"){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="desactivarSemanaRevision();"></td>
            <td width="38">S</td>
            <td width="274"><label>Horario:</label>
              <label><span class="Tablas">
                <select name="shrevision" size="1" onKeyPress="return tabular(event,this)" class="Tablas" id="shrevision">
                  <? for($h=0;$h<24;$h++){ ?>
                  <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                    <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                    </option>
                  <? }?>
                </select>
                <select name="smrevision" size="1" onKeyPress="return tabular(event,this)" class="Tablas" id="smrevision">
                  <? for($m=0;$m<60;$m++){ ?>
                  <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                    <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                    </option>
                  <? }?>
                </select>
                a
                <select name="ahrevision" size="1" onKeyPress="return tabular(event,this)" class="Tablas" id="ahrevision">
                  <? for($h=0;$h<24;$h++){ ?>
                  <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                    <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                    </option>
                  <? }?>
                </select>
                <select name="amrevision" size="1" onKeyPress="return tabular(event,this)" class="Tablas" id="amrevision">
                  <? for($m=0;$m<60;$m++){ ?>
                  <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                    <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                    </option>
                  <? }?>
                </select>
                </span></label></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="6" class="FondoTabla">Referencia Bancarias </td>
      </tr>
      <tr>
        <td colspan="6"><table width="577" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="284">Banco:</td>
            <td width="285"><span class="Tablas">
              <input name="rbanco" type="text" class="Tablas" id="rbanco" style="width:80px" value="<?=$rbanco ?>" onKeyPress="return tabular(event,this)" />
            </span></td>
            <td width="571">Sucursal:</td>
            <td width="571"><span class="Tablas">
              <input name="rsucursal" type="text" class="Tablas" id="rsucursal" style="width:80px" value="<?=$rsucursal ?>" onKeyPress="return tabular(event,this)"/>
            </span></td>
            <td width="571">Cuenta:</td>
            <td width="571"><span class="Tablas">
              <input name="rcuenta" type="text" class="Tablas" id="rcuenta" style="width:80px" value="<?=$rcuenta ?>" onKeyPress="return tabular(event,this)" />
            </span></td>
            <td width="284">Tel&eacute;fono:</td>
            <td width="141"><span class="Tablas">
              <input name="rtelefono" type="text" class="Tablas" id="rtelefono" onKeyPress="if(event.keyCode==13){agregarBanco();};" style="width:80px" value="<?=$rtelefono ?>" />
            </span></td>
            <td width="142"><div class="ebtn_agregar" onClick="agregarBanco()"></div></td>
          </tr>
        </table>         </td>	
      </tr>
      <tr>
        <td colspan="6"></td>
      </tr>
      <tr>
        <td colspan="6"><table width="589" border="1" cellpadding="0" cellspacing="0" bordercolor="#016193">
          <tr>
            <td><table width="525" cellpadding="0" border="0" cellspacing="0" name="tablaBanco" id="tablaBanco" ></table></td>
             <td ><img src="../img/Boton_Eliminar.gif"  width="70" height="20" align="middle" style="cursor:pointer" onClick="borrarBanco();"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" class="FondoTabla">Referencias Comerciales </td>
      </tr>
      <tr>
        <td colspan="6">
          <table width="577" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="284">Empresa:</td>
              <td width="285"><span class="Tablas">
                <input name="cempresa" type="text" class="Tablas" id="cempresa" style="width:80px" value="<?=$cempresa?>" onKeyPress="return tabular(event,this)" />
              </span></td>
              <td width="571">&nbsp;</td>
              <td width="571">&nbsp;</td>
              <td width="571">Contacto:</td>
              <td width="571"><span class="Tablas">
                <input name="ccontacto" type="text" class="Tablas" id="ccontacto" style="width:160px" value="<?=$ccontacto ?>" onKeyPress="return tabular(event,this)" />
              </span></td>
              <td width="284">Tel&eacute;fono:</td>
              <td width="141"><span class="Tablas">
                <input name="ctelefono" type="text" class="Tablas" id="ctelefono" style="width:80px" value="<?=$ctelefono ?>" onKeyPress="if(event.keyCode==13){agregarComercial();}" />
              </span></td>
              <td width="142"><div class="ebtn_agregar" onClick="agregarComercial()"></div></td>
            </tr>
          </table>          </td>
      </tr>      
      <tr>
        <td colspan="6"><table width="589" border="1" cellpadding="0" cellspacing="0" bordercolor="#016193">
          <tr>
            <td><table width="515" cellpadding="0" cellspacing="0" border="0" id="tablaComer" name="tablaComer" >
            </table></td>
             <td ><img src="../img/Boton_Eliminar.gif"  width="70" height="20" align="middle" style="cursor:pointer" onClick="borrarComercial();"></td>
          </tr>
        </table></td>
      </tr>
      
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" class="FondoTabla">Autorizaci&oacute;n Tr&aacute;mite de Cr&eacute;dito </td>
      </tr>
      <tr>
        <td colspan="6"><label>Monto Solicitado:<span class="Tablas">
          <input name="msolicitado" type="text" class="Tablas" id="msolicitado" style="width:120px" onKeyPress="if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/g,'')); document.all.observaciones.focus();}else{return solonumeros(event);}"   value="<?=$msolicitado ?>" maxlength="15" />
        </span></label>
          <label>Monto Autorizado:<span class="Tablas">
          <input name="mautorizado" type="text" class="Tablas" id="mautorizado" style="width:120px" onKeyPress="if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/g,'')); document.all.diacredito.focus();}else{return solonumeros(event);}" value="<?=$mautorizado ?>" maxlength="15" <? if($estado=="SOLICITUD"){ echo "style='background:#FF9'"; echo "disabled";} ?>  />
          Dias Credito:
          <input name="diacredito" type="text" class="Tablas" id="diacredito" style="width:50px" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$diacredito ?>" maxlength="10" <? if($estado=="SOLICITUD"){ echo "style='background:#FF9'"; echo "disabled";} ?>  />
          </span></label></td>
      </tr>
      <tr>
        <td colspan="6" class="FondoTabla">Observaciones</td>
      </tr>
      <tr>
        <td colspan="6"><label>
          <textarea name="observaciones" class="Tablas" style="text-transform:uppercase" cols="60" onKeyPress="return tabular(event,this)" id="observaciones"><?=$observaciones ?>
          </textarea>
        </label></td>
      </tr>
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6"><table width="483" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width="146" id="col"><div id="btn_Enviarp" class="ebtn_Enviarp"  onClick="validar();"></div></td>
            <td width="90" id="colno">&nbsp;</td>
            <td width="152"><div class="ebtn_imprimircontrato" ></div></td>
            <td width="95"><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar(1);', '')"></div></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td colspan="6" align="center"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
          <input name="registropersona" type="hidden" id="registropersona" value="<?=$registropersona ?>">
          <input name="registrobanco" type="hidden" id="registrobanco" value="<?=$registrobanco ?>">
          <input name="registrocomer" type="hidden" id="registrocomer" value="<?=$registrocomer ?>">
          <input name="index" type="hidden" id="index" value="<?=$index ?>">
          
            <input name="horariopago" type="hidden" id="horariopago" value="<?=$hpago ?>">
            <input name="apago" type="hidden" id="apago" value="<?=$apago ?>">
            <input name="horariorevision" type="hidden" id="horariorevision" value="<?=$hrevision ?>">
            <input name="arevision" type="hidden" id="arevision" value="<?=$arevision?>">
            <input name="sucursalorigen" type="hidden" id="sucursalorigen" value="<?=$sucursalorigen ?>">
            <input name="clienteconvenio" type="hidden" id="clienteconvenio" value="<?=$clienteconvenio ?>">
            <input name="notieneconvenio" type="hidden" id="notieneconvenio"></td>
      </tr>
      
    </table>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<? 
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
}
//}
?>