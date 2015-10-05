<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT DATE_FORMAT(fechasolicitud,'%d/%m/%Y') AS fechasolicitud, estado,
		folioconvenio, DATE_FORMAT(fechaautorizacion,'%d/%m/%Y') AS fechaautorizacion, solicitante,
		personamoral, cliente, giro, antiguedad, representantelegal, actaconstitutiva,
		numeroacta, DATE_FORMAT(fechaescritura,'%d/%m/%Y') AS fechaescritura, 
		DATE_FORMAT(fechainscripcion,'%d/%m/%Y') AS fechainscripcion,
		identificacionlegal, numeroidentificacion,
		hacienda, DATE_FORMAT(fechainiciooperaciones,'%d/%m/%Y') AS fechainiciooperaciones,
		comprobante, comprobanteluz, estadocuenta, banco, cuenta,
		solicitud, semanapago, lunespago, martespago, miercolespago, juevespago, viernespago,
		sabadopago, horariopago, apago, responsablepago, celularpago, telefonopago, faxpago,
		semanarevision, lunesrevision, martesrevision, miercolesrevision, juevesrevision,
		viernesrevision, sabadorevision, horariorevision, arevision, montosolicitado, rfc2,
		montoautorizado, diascredito, observaciones, nick, rfc, nombre, paterno, materno,
		calle, numero, cp, colonia, poblacion, municipio, pais, celular, telefono, 
		email, estadoc FROM solicitudcredito
		WHERE folio=".$_GET['credito'];

		//die($s);

		$r = mysql_query($s,$l) or die($s);

		$f = mysql_fetch_object($r);		

		

		$principal = "";
		if(mysql_num_rows($r)>0){
			$f->nombre	= cambio_texto($f->nombre);				
			$f->nick	= cambio_texto($f->nick);
			$f->rfc		= cambio_texto($f->rfc);
			$f->nombre	= cambio_texto($f->nombre);
			$f->paterno	= cambio_texto($f->paterno);
			$f->materno	= cambio_texto($f->materno);
			$f->calle	= cambio_texto($f->calle);
			$f->numero	= cambio_texto($f->numero);
			$f->fechaautorizacion = date('d/m/Y');
			$f->colonia	= cambio_texto($f->colonia);
			$f->poblacion= cambio_texto($f->poblacion);
			$f->municipio= cambio_texto($f->municipio);
			$f->pais	= cambio_texto($f->pais);			

			
			$s = "SELECT folio as convenio, idcliente FROM generacionconvenio WHERE idcliente=".$f->cliente;
			$g = mysql_query($s,$l) or die($s);
			$gc= mysql_fetch_object($g);
			
			$f->convenio = $gc->convenio;
			$f->idcliente = (($gc->idcliente!="")?$gc->idcliente:0);
			
			$principal = str_replace('null','""',json_encode($f));
			
			$sucursales = "";
			$idsucursal = "";
			$s = "SELECT idsucursal,sucursal AS nombre FROM solicitudcreditosucursaldetalle
			WHERE solicitud=".$_GET['credito']."";		
			$r = mysql_query($s,$l) or die($s);		
			$registros = array();
			if(mysql_num_rows($r)>0){			
				$f = mysql_fetch_object($r);
				if($f->idsucursal=="0"){
					$s = mysql_query("SELECT descripcion as nombre FROM catalogosucursal WHERE id > 1",$l);
					while($r = mysql_fetch_object($s)){
						$r->sucursal = cambio_texto($r->sucursal);	
						$registros[] = $r;
					}
					$idsucursal = str_replace('null','""',json_encode($f->idsucursal));					
				}else{					
					if(mysql_num_rows($r)==1){
						$f->sucursal = cambio_texto($f->nombre);
						$idsucursal .= $f->sucursal.":".$f->idsucursal.",";
						$registros[] = $f;
						$idsucursal = str_replace('null','""',json_encode($idsucursal));	
					}else{						
						$s = "SELECT idsucursal,sucursal as nombre FROM solicitudcreditosucursaldetalle
						WHERE solicitud=".$_GET['credito']."";
						$rq = mysql_query($s,$l) or die($s);
						while($f = mysql_fetch_object($rq)){
							$f->sucursal = cambio_texto($f->nombre);
							$idsucursal .= $f->sucursal.":".$f->idsucursal.",";
							$registros[] = $f;
						}
						$idsucursal = str_replace('null','""',json_encode($idsucursal));	
					}
				}
			}
				$sucursales = str_replace("null",'""',json_encode($registros));
			
			
			$banco = array();
			
			$s = "SELECT banco,sucursal,cuenta,telefono FROM solicitudcreditobancodetalle
			WHERE solicitud=".$_GET['credito']."";			
			$b = mysql_query($s,$l) or die($s);			
				while($ba = mysql_fetch_object($b)){
					$ba->banco = cambio_texto($ba->banco);
					$ba->sucursal = cambio_texto($ba->sucursal);
					$ba->cuenta = cambio_texto($ba->cuenta);
					$ba->telefono = cambio_texto($ba->telefono);
					$banco[] = $ba;
				}
			
			$banco = str_replace("null",'""',json_encode($banco));
			
			$comerciales = array();
			
			$sc = mysql_query("SELECT empresa,contacto,telefono FROM solicitudcreditocomercialesdetalle
			WHERE solicitud=".$_GET['credito']."",$l);			
				while($c = mysql_fetch_object($sc)){
					$c->empresa = cambio_texto($c->empresa);
					$c->contacto = cambio_texto($c->contacto);
					$c->telefono = cambio_texto($c->telefono);
					$comerciales[] = $c;
				}				
			$comerciales = str_replace("null",'""',json_encode($comerciales));
			
			$persona = array();			
			$sp = mysql_query("SELECT persona FROM solicitudcreditopersonadetalle 
			WHERE solicitud=".$_GET['credito']."",$l);			
				while($p=mysql_fetch_object($sp)){
					$p->persona = cambio_texto($p->persona);
					$persona[] = $p;
				}
			$persona = str_replace("null",'""',json_encode($persona));
			
			echo "({principal:$principal,".(($idsucursal!='')?"idsucursal:$idsucursal":"idsucursal:'$idsucursal'").",
			sucursales:$sucursales, banco:$banco,comerciales:$comerciales,persona:$persona})";			
		}else{
			echo "0";
		}
	}else if($_GET[accion] == 2){
		$s = "SELECT idcliente FROM generacionconvenio WHERE folio = ".$_GET[folio];
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);		
		echo $f->idcliente;
		
	}else if($_GET[accion] == 3){//REGISTRAR SOLICITUD CREDITO
		if($_GET[grabar] == "grabar"){
			$s = "INSERT INTO solicitudcredito SET 
			fechasolicitud=CURDATE(), estado=UCASE('EN AUTORIZACION'),
			folioconvenio='".(($_GET[folioconvenio]!='')? $_GET[folioconvenio] : 0)."',
			fechaautorizacion='".(($_GET[fechaautorizacion]!='')? cambiaf_a_mysql($_GET[fechaautorizacion]):'')."',
			personamoral='".$_GET[rdmoral]."', cliente='".$_GET[cliente]."', 
			nick=UCASE('".$_GET[nick]."'), rfc=UCASE('".$_GET[rfc]."'), nombre=UCASE('".$_GET[nombre]."'),
			paterno=UCASE('".$_GET[paterno]."'), materno=UCASE('".$_GET[materno]."'), calle=UCASE('".$_GET[calle]."'), 
			numero=UCASE('".$_GET[numero]."'), cp=UCASE('".$_GET[cp]."'), colonia=UCASE('".$_GET[colonia]."'),
			poblacion=UCASE('".$_GET[poblacion]."'), municipio=UCASE('".$_GET[municipio]."'), estadoc=UCASE('".$_GET[estado2]."'), 
			pais=UCASE('".$_GET[pais]."'), celular=UCASE('".$_GET[celular]."'), 
			telefono=UCASE('".$_GET[telefono]."'), email=UCASE('".$_GET[email]."'), giro=UCASE('".$_GET[giro]."'),
			antiguedad=UCASE('".$_GET[antiguedad]."'), representantelegal=UCASE('".$_GET[representantelegal]."'),
			actaconstitutiva='".(($_GET[actaconstitutiva]==1)?1:0)."', numeroacta=UCASE('".$_GET[nacta]."'),
			fechaescritura='".(($_GET[fechaescritura]!='')?cambiaf_a_mysql($_GET[fechaescritura]):'')."',
			fechainscripcion='".(($_GET[fechainscripcion]!='')?cambiaf_a_mysql($_GET[fechainscripcion]):'')."',
			identificacionlegal='".(($_GET[identificacion]==1)?1:0)."', numeroidentificacion=UCASE('".$_GET[nidentificacion]."'),
			hacienda='".(($_GET[hacienda]==1)?1:0)."',
			fechainiciooperaciones='".(($_GET[fechainiciooperaciones]!='')?cambiaf_a_mysql($_GET[fechainiciooperaciones]):'')."',
			rfc2=UCASE('".$_GET[rfc2]."'), comprobante='".(($_GET[comprobante]==1)?1:0)."', 
			comprobanteluz='".(($_GET[comprobanteluz]==1)?1:0)."',
			estadocuenta='".(($_GET[estadocuenta]==1)?1:0)."',
			banco=UCASE('".$_GET[banco]."'), cuenta='".(($_GET[cuenta]!='')?$_GET[cuenta]:0)."', 
			solicitud='".(($_GET[solicitud]==1)?1:0)."',
			semanapago='".(($_GET[semanapago]==1)?1:0)."', lunespago='".(($_GET[lunespago]==1)?1:0)."',
			martespago='".(($_GET[martespago]==1)?1:0)."', miercolespago='".(($_GET[miercolespago]==1)?1:0)."',
			juevespago='".(($_GET[juevespago]==1)?1:0)."', viernespago='".(($_GET[viernespago]==1)?1:0)."',
			sabadopago='".(($_GET[sabadopago]==1)?1:0)."', horariopago='".$_GET[horariopago]."',
			apago='".$_GET[apago]."', responsablepago=UCASE('".$_GET[responsablepago]."'),
			celularpago='".$_GET[celularpago]."', telefonopago='".$_GET[telefonopago]."', faxpago='".$_GET[faxpago]."',
			semanarevision='".(($_GET[semanarevision]==1)?1:0)."', lunesrevision='".(($_GET[lunesrevision]==1)?1:0)."',
			martesrevision='".(($_GET[martesrevision]==1)?1:0)."',
			miercolesrevision='".(($_GET[miercolesrevision]==1)?1:0)."', juevesrevision='".(($_GET[juevesrevision]==1)?1:0)."',
			viernesrevision='".(($_GET[viernesrevision]==1)?1:0)."', sabadorevision='".(($_GET[sabadorevision]==1)?1:0)."',
			horariorevision='".$_GET[horariorevision]."',
			arevision='".$_GET[arevision]."', montosolicitado='".(($_GET[msolicitado]!='')?$_GET[msolicitado]:0)."',
			montoautorizado='".(($_GET[mautorizado]!='')?$_GET[mautorizado]:0)."', 
			diascredito='".(($_GET[diacredito]!='')?$_GET[diacredito]:0)."',
			observaciones=UCASE('".trim($_GET[observaciones])."'), usuario='".$_SESSION[NOMBREUSUARIO]."',
			idusuario=".$_SESSION[IDUSUARIO].", idsucursal=".$_SESSION[IDSUCURSAL].",
			fecha=CURRENT_TIMESTAMP()";
			mysql_query($s,$l) or die($s);
			$folio = mysql_insert_id();
			
			
			echo "ok,".$folio;
			
		}else if($_GET[grabar] == "modificar"){
			$s = "UPDATE solicitudcredito SET 
			fechasolicitud=CURDATE(), estado=UCASE('EN AUTORIZACION'),
			folioconvenio='".(($_GET[folioconvenio]!='')? $_GET[folioconvenio] : 0)."',
			fechaautorizacion='".(($_GET[fechaautorizacion]!='')? cambiaf_a_mysql($_GET[fechaautorizacion]):'')."',
			personamoral='".$_GET[rdmoral]."', cliente='".$_GET[cliente]."', 
			nick=UCASE('".$_GET[nick]."'), rfc=UCASE('".$_GET[rfc]."'), nombre=UCASE('".$_GET[nombre]."'),
			paterno=UCASE('".$_GET[paterno]."'), materno=UCASE('".$_GET[materno]."'), calle=UCASE('".$_GET[calle]."'), 
			numero=UCASE('".$_GET[numero]."'), cp=UCASE('".$_GET[cp]."'), colonia=UCASE('".$_GET[colonia]."'),
			poblacion=UCASE('".$_GET[poblacion]."'), municipio=UCASE('".$_GET[municipio]."'), estadoc=UCASE('".$_GET[estado2]."'), 
			pais=UCASE('".$_GET[pais]."'), celular=UCASE('".$_GET[celular]."'), 
			telefono=UCASE('".$_GET[telefono]."'), email=UCASE('".$_GET[email]."'), giro=UCASE('".$_GET[giro]."'),
			antiguedad=UCASE('".$_GET[antiguedad]."'), representantelegal=UCASE('".$_GET[representantelegal]."'),
			actaconstitutiva='".(($_GET[actaconstitutiva]==1)?1:0)."', numeroacta=UCASE('".$_GET[nacta]."'),
			fechaescritura='".(($_GET[fechaescritura]!='')?cambiaf_a_mysql($_GET[fechaescritura]):'')."',
			fechainscripcion='".(($_GET[fechainscripcion]!='')?cambiaf_a_mysql($_GET[fechainscripcion]):'')."',
			identificacionlegal='".(($_GET[identificacion]==1)?1:0)."', numeroidentificacion=UCASE('".$_GET[nidentificacion]."'),
			hacienda='".(($_GET[hacienda]==1)?1:0)."',
			fechainiciooperaciones='".(($_GET[fechainiciooperaciones]!='')?cambiaf_a_mysql($_GET[fechainiciooperaciones]):'')."',
			rfc2=UCASE('".$_GET[rfc2]."'), comprobante='".(($_GET[comprobante]==1)?1:0)."', 
			comprobanteluz='".(($_GET[comprobanteluz]==1)?1:0)."',
			estadocuenta='".(($_GET[estadocuenta]==1)?1:0)."',
			banco=UCASE('".$_GET[banco]."'), cuenta='".(($_GET[cuenta]!='')?$_GET[cuenta]:0)."', 
			solicitud='".(($_GET[solicitud]==1)?1:0)."',
			semanapago='".(($_GET[semanapago]==1)?1:0)."', lunespago='".(($_GET[lunespago]==1)?1:0)."',
			martespago='".(($_GET[martespago]==1)?1:0)."', miercolespago='".(($_GET[miercolespago]==1)?1:0)."',
			juevespago='".(($_GET[juevespago]==1)?1:0)."', viernespago='".(($_GET[viernespago]==1)?1:0)."',
			sabadopago='".(($_GET[sabadopago]==1)?1:0)."', horariopago='".$_GET[horariopago]."',
			apago='".$_GET[apago]."', responsablepago=UCASE('".$_GET[responsablepago]."'),
			celularpago='".$_GET[celularpago]."', telefonopago='".$_GET[telefonopago]."', faxpago='".$_GET[faxpago]."',
			semanarevision='".(($_GET[semanarevision]==1)?1:0)."', lunesrevision='".(($_GET[lunesrevision]==1)?1:0)."',
			martesrevision='".(($_GET[martesrevision]==1)?1:0)."',
			miercolesrevision='".(($_GET[miercolesrevision]==1)?1:0)."', juevesrevision='".(($_GET[juevesrevision]==1)?1:0)."',
			viernesrevision='".(($_GET[viernesrevision]==1)?1:0)."', sabadorevision='".(($_GET[sabadorevision]==1)?1:0)."',
			horariorevision='".$_GET[horariorevision]."',
			arevision='".$_GET[arevision]."', montosolicitado='".(($_GET[msolicitado]!='')?$_GET[msolicitado]:0)."',
			montoautorizado='".(($_GET[mautorizado]!='')?$_GET[mautorizado]:0)."', 
			diascredito='".(($_GET[diacredito]!='')?$_GET[diacredito]:0)."',
			observaciones=UCASE('".trim($_GET[observaciones])."'), usuario='".$_SESSION[NOMBREUSUARIO]."',
			idusuario=".$_SESSION[IDUSUARIO].", idsucursal=".$_SESSION[IDSUCURSAL].",
			fecha=CURRENT_TIMESTAMP() WHERE folio = ".$_GET[folio]."";
		
		}
	}
?>