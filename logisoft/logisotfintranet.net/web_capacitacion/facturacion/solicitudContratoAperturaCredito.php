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
		
		#SE INSERTA EL NUEVO CREDITO
		$s = "call proc_RegistroCobranza('CREDITO', '$folio', '', 'SI', 0, 0);";
		mysql_query($s,$link) or die($s);
		
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
				UCASE('".$_POST["tabla