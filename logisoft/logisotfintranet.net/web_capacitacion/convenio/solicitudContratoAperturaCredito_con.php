<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	//$_SESSION[IDUSUARIO] = 1;
	if($_GET[accion]==1){
		$s = "DELETE FROM solicitudcreditobancodetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);
		$s = "DELETE FROM solicitudcreditocomercialesdetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);
		$s = "DELETE FROM solicitudcreditopersonadetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);
		$s = "DELETE FROM solicitudcreditosucursaldetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);
		
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
			//$f->fechaautorizacion = date('d/m/Y');
			$f->colonia	= cambio_texto($f->colonia);
			$f->poblacion= cambio_texto($f->poblacion);
			$f->municipio= cambio_texto($f->municipio);
			$f->pais	= cambio_texto($f->pais);
			$f->antiguedad	= cambio_texto($f->antiguedad);
			
			$s = "SELECT folio as convenio, idcliente FROM generacionconvenio WHERE idcliente=".$f->cliente;
			$g = mysql_query($s,$l) or die($s);
			$gc= mysql_fetch_object($g);
			
			$f->convenio = $gc->convenio;
			$f->idcliente = (($gc->idcliente!="")?$gc->idcliente:0);
			
			$principal = str_replace('null','""',json_encode($f));
			
			/*$sucursales = "";
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
			$sucursales = str_replace("null",'""',json_encode($registros));*/
			
			$sucursal = array();
			
			$s = "INSERT INTO solicitudcreditosucursaldetalletmp
			SELECT null as id,idsucursal, sucursal, ".$_SESSION[IDUSUARIO].", fecha FROM solicitudcreditosucursaldetalle
			WHERE solicitud = ".$_GET['credito']."";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT * FROM solicitudcreditosucursaldetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";			
			$su = mysql_query($s,$l) or die($s);
				while($suc = mysql_fetch_object($su)){
					$suc->sucursal = cambio_texto($suc->sucursal);					
					$sucursal[] = $suc;
				}
			
			$sucursal = str_replace("null",'""',json_encode($sucursal));
			
			$banco = array();
			
			$s = "INSERT INTO solicitudcreditobancodetalletmp
			SELECT null as id,banco,sucursal,cuenta,telefono, ".$_SESSION[IDUSUARIO].", fecha FROM solicitudcreditobancodetalle
			WHERE solicitud=".$_GET['credito']."";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT banco,sucursal,cuenta,telefono, fecha FROM solicitudcreditobancodetalletmp
			WHERE idusuario = ".$_SESSION[IDUSUARIO]."";			
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
			
			$s = "INSERT INTO solicitudcreditocomercialesdetalletmp
			SELECT null as id,empresa,contacto,telefono, ".$_SESSION[IDUSUARIO].", fecha FROM solicitudcreditocomercialesdetalle
			WHERE solicitud=".$_GET['credito']."";
			mysql_query($s,$l) or die($s);
			
			$sc = mysql_query("SELECT empresa,contacto,telefono, fecha FROM solicitudcreditocomercialesdetalletmp
			WHERE idusuario = ".$_SESSION[IDUSUARIO]."",$l);			
				while($c = mysql_fetch_object($sc)){
					$c->empresa = cambio_texto($c->empresa);
					$c->contacto = cambio_texto($c->contacto);
					$c->telefono = cambio_texto($c->telefono);
					$comerciales[] = $c;
				}				
			$comerciales = str_replace("null",'""',json_encode($comerciales));
			
			$persona = array();	
			
			$s = "INSERT INTO solicitudcreditopersonadetalletmp
			SELECT null as id,persona, ".$_SESSION[IDUSUARIO].", fecha FROM solicitudcreditopersonadetalle
			WHERE solicitud=".$_GET['credito']."";
			mysql_query($s,$l) or die($s);
					
			$sp = mysql_query("SELECT persona, fecha FROM solicitudcreditopersonadetalletmp 
			WHERE idusuario = ".$_SESSION[IDUSUARIO]."",$l);			
				while($p=mysql_fetch_object($sp)){
					$p->persona = cambio_texto($p->persona);
					$persona[] = $p;
				}
			$persona = str_replace("null",'""',json_encode($persona));
			
			echo "({principal:$principal, banco:$banco,comerciales:$comerciales,
			persona:$persona,sucursal:$sucursal})";			
		}else{
			echo "no encontro";
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
			
			$s = "insert into historialmovimientos(modulo,folio,estado,idusuario,fechamodificacion) 
			values ('solicitudcredito',$folio,'EN AUTORIZACION',$_SESSION[IDUSUARIO],CURRENT_TIMESTAMP);";
			mysql_query($s,$l) or die(mysql_error($l).$s);
			
			$s = "INSERT INTO solicitudcreditobancodetalle
			SELECT null as id, ".$folio.", banco, sucursal, cuenta, telefono, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditobancodetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditocomercialesdetalle
			SELECT null as id, ".$folio.", empresa, contacto, telefono, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditocomercialesdetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditopersonadetalle
			SELECT null as id, ".$folio.", persona, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditopersonadetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditosucursaldetalle
			SELECT null as id, ".$folio.", idsucursal, sucursal, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditosucursaldetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "call proc_RegistroCobranza('CREDITO', '".$folio."', '', 'SI', 0, 0);";
			mysql_query($s,$l) or die($s);
			
			echo "ok,grabar,".$folio;
			
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
			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM solicitudcreditobancodetalle WHERE solicitud = ".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditobancodetalle
			SELECT null as id, ".$_GET[folio].", banco, sucursal, cuenta, telefono, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditobancodetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM solicitudcreditocomercialesdetalle WHERE solicitud = ".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditocomercialesdetalle
			SELECT null as id, ".$_GET[folio].", empresa, contacto, telefono, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditocomercialesdetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM solicitudcreditopersonadetalle WHERE solicitud = ".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditopersonadetalle
			SELECT null as id, ".$_GET[folio].", persona, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditopersonadetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM solicitudcreditosucursaldetalle WHERE solicitud = ".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditosucursaldetalle
			SELECT null as id, ".$_GET[folio].", idsucursal, sucursal, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditosucursaldetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "call proc_RegistroCobranza('CREDITO', '".$_GET[folio]."', '', 'NO', 0, 0);";
			mysql_query($s,$l) or die($s);
				
			echo "ok,modificar";
		}
		
	}else if($_GET[accion]==4){
		$row = ObtenerFolio('solicitudcredito','webpmm');
		$s = "DELETE FROM solicitudcreditobancodetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		$s = "DELETE FROM solicitudcreditocomercialesdetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		$s = "DELETE FROM solicitudcreditopersonadetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		$s = "DELETE FROM solicitudcreditosucursaldetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		$fecha = date('d/m/Y');
		
		echo "({folio:$row[0],fecha:'$fecha'})";	
		
	}else if($_GET[accion]==5){//REGISTRAR, MODIFICAR, ELIMINAR PERSONAS
		if($_GET[tipo]=="alta"){
			$s = "INSERT INTO solicitudcreditopersonadetalletmp SET
			persona = UCASE('".$_GET[persona]."'), idusuario = ".$_SESSION[IDUSUARIO].",
			fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}else if($_GET[tipo]=="modificar"){
			$s = "UPDATE solicitudcreditopersonadetalletmp SET
			persona = UCASE('".$_GET[persona]."')
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}else if($_GET[tipo]=="borrar"){
			$s = "DELETE FROM solicitudcreditopersonadetalletmp			
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}
		echo "ok,".$_GET[tipo];
		
	}else if($_GET[accion]==6){//INSERTAR SUCURSALES APLICA CREDITO
		if($_GET[sucursal]=="todas" || $_GET[sucursal]=="TODAS"){
			$s = "INSERT INTO solicitudcreditosucursaldetalletmp SET
			idsucursal = 0, sucursal = 'TODAS', idusuario = ".$_SESSION[IDUSUARIO].",
			fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}else{
			$s = "INSERT INTO solicitudcreditosucursaldetalletmp SET
			idsucursal = '".$_GET[idsucursal]."', sucursal = UCASE('".$_GET[sucursal]."'),
			idusuario = ".$_SESSION[IDUSUARIO].", fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}
		echo "ok";
	}else if($_GET[accion]==7){//BORRAR SUCURSALES APLICA CREDITO
		if($_GET[sucursal]=="todas" || $_GET[sucursal]=="TODAS"){
			if($_GET[tenia] == "si"){
				$s = "DELETE FROM solicitudcreditosucursaldetalletmp 
				WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
				mysql_query($s,$l) or die($s);
			}else{
				$s = "DELETE FROM solicitudcreditosucursaldetalletmp 
				WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
				mysql_query($s,$l) or die($s);
			}
		}else{
			$s = "DELETE FROM solicitudcreditosucursaldetalletmp
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."' AND idsucursal = ".$_GET[idsucursal]."";
			mysql_query($s,$l) or die($s);
		}
		if($_GET[tenia]=="si"){
			echo "ok,si";
		}else{
			echo "ok";
		}
	}else if($_GET[accion]==8){//REGISTRAR, MODIFICAR, ELIMINAR BANCO
		if($_GET[tipo]=="alta"){
			$s = "INSERT INTO solicitudcreditobancodetalletmp SET
			banco = UCASE('".$_GET[banco]."'), sucursal = UCASE('".$_GET[sucursal]."'),
			cuenta = UCASE('".$_GET[cuenta]."'), telefono = UCASE('".$_GET[telefono]."'),
			idusuario = ".$_SESSION[IDUSUARIO].", fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}else if($_GET[tipo]=="modificar"){
			$s = "UPDATE solicitudcreditobancodetalletmp SET
			banco = UCASE('".$_GET[banco]."'), sucursal = UCASE('".$_GET[sucursal]."'),
			cuenta = UCASE('".$_GET[cuenta]."'), telefono = UCASE('".$_GET[telefono]."')
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}else if($_GET[tipo]=="borrar"){
			$s = "DELETE FROM solicitudcreditobancodetalletmp			
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}
		echo "ok,".$_GET[tipo];
	}else if($_GET[accion]==9){//REGISTRAR, MODIFICAR, ELIMINAR COMERCIAL
		if($_GET[tipo]=="alta"){
			$s = "INSERT INTO solicitudcreditocomercialesdetalletmp SET
			empresa = UCASE('".$_GET[empresa]."'), contacto = UCASE('".$_GET[contacto]."'),
			telefono = UCASE('".$_GET[telefono]."'),
			idusuario = ".$_SESSION[IDUSUARIO].", fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}else if($_GET[tipo]=="modificar"){
			$s = "UPDATE solicitudcreditocomercialesdetalletmp SET
			empresa = UCASE('".$_GET[empresa]."'), contacto = UCASE('".$_GET[contacto]."'),
			telefono = UCASE('".$_GET[telefono]."')
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}else if($_GET[tipo]=="borrar"){
			$s = "DELETE FROM solicitudcreditocomercialesdetalletmp			
			WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
		}
		echo "ok,".$_GET[tipo];
		
	}else if($_GET[accion]==10){//AUTORIZAR CREDITO
		$s = "UPDATE solicitudcredito SET 
			fechasolicitud=CURDATE(), estado=UCASE('AUTORIZADO'),
			folioconvenio='".(($_GET[folioconvenio]!='')? $_GET[folioconvenio] : 0)."',
			fechaautorizacion=".(($_GET[fechaautorizacion]!='00/00/0000')? '".cambiaf_a_mysql($_GET[fechaautorizacion])."':CURRENT_DATE).",
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

			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM solicitudcreditobancodetalle WHERE solicitud = ".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditobancodetalle
			SELECT null as id, ".$_GET[folio].", banco, sucursal, cuenta, telefono, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditobancodetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM solicitudcreditocomercialesdetalle WHERE solicitud = ".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditocomercialesdetalle
			SELECT null as id, ".$_GET[folio].", empresa, contacto, telefono, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditocomercialesdetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM solicitudcreditopersonadetalle WHERE solicitud = ".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditopersonadetalle
			SELECT null as id, ".$_GET[folio].", persona, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditopersonadetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM solicitudcreditosucursaldetalle WHERE solicitud = ".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO solicitudcreditosucursaldetalle
			SELECT null as id, ".$_GET[folio].", idsucursal, sucursal, ".$_SESSION[IDUSUARIO].", fecha
			FROM solicitudcreditosucursaldetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "call proc_RegistroCobranza('CREDITO', '".$_GET[folio]."', '', 'NO', 0, 0);";
			mysql_query($s,$l) or die($s);
				
			echo "ok";
	
	}else if($_GET[accion]==11){//ACTIVAR CREDITO
		$s = "UPDATE solicitudcredito SET estado='ACTIVADO', fechaactivacion=CURDATE(), idusuario = ".$_SESSION[IDUSUARIO]." 
		WHERE folio=".$_GET[folio]."";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE losclientes lc
		SET lc.credito = 'SI'
		WHERE lc.id = '$_GET[cliente]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE catalogocliente SET foliocredito=".$_GET[folio].",
		limitecredito='".$_GET[autorizado]."', diascredito = '".$_GET[diascredito]."',
		diarevision = '".$_GET[revision]."', diapago = '".$_GET[pago]."', activado='SI'
		WHERE id = ".$_GET[cliente]."";
		mysql_query($s,$l) or die($s);
		
		#SE REFISTRA COMO HISTORIAL LA MODIFICACION
		$s = "call proc_RegistroCobranza('CREDITO', '".$_GET[folio]."', '', 'NO', 0, 0);";
		mysql_query($s,$l) or die($s);

		$s = "SELECT IFNULL(MAX(id),0) AS id FROM reportecliente2 WHERE idcliente = ".$_GET[cliente]."";
		$r = mysql_query($s,$l) or die($s); $cc = mysql_fetch_object($r);
		
		if($cc->id==0){
			$s = "INSERT INTO reportecliente2 SET estadocredito = 'ACTIVADO',
			limitecredito = ".$_GET[autorizado].", idcliente = ".$_GET[cliente]."";
			mysql_query($s,$l) or die($s);
		}else{		
			$s = "UPDATE reportecliente2 SET estadocredito = 'ACTIVADO', limitecredito = ".$_GET[autorizado]."
			WHERE id = ".$cc->id."";
			mysql_query($s,$l) or die($s);
		}
		
		echo "ok";
	}else if($_GET[accion]==12){//NO AUTORIZAR CREDITO
		$s = "UPDATE solicitudcredito SET estado='NO AUTORIZADA', idusuario = ".$_SESSION[IDUSUARIO]." WHERE folio=".$_GET[folio]."";
		mysql_query($s,$l) or die($s);
		
		$s = "call proc_RegistroCobranza('CREDITO', '".$_GET[folio]."', '', 'NO', 0, 0);";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
		
	}else if($_GET[accion]==13){
		$s = "UPDATE solicitudcredito SET
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
		horariorevision='".$_GET[horariorevision]."', montosolicitado='".(($_GET[msolicitado]!='')?$_GET[msolicitado]:0)."', 
		arevision='".$_GET[arevision]."', montoautorizado='".(($_GET[mautorizado]!='')?$_GET[mautorizado]:0)."', 
		diascredito='".(($_GET[diacredito]!='')?$_GET[diacredito]:0)."',
		observaciones=UCASE('".trim($_GET[observaciones])."'), usuario='".$_SESSION[NOMBREUSUARIO]."',
		idusuario=".$_SESSION[IDUSUARIO].", idsucursal=".$_SESSION[IDSUCURSAL].",
		fecha=CURRENT_TIMESTAMP() WHERE folio = ".$_GET[folio]."";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM solicitudcreditobancodetalle WHERE solicitud = ".$_GET[folio]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO solicitudcreditobancodetalle
		SELECT null AS id, ".$_GET[folio].", banco, sucursal, cuenta, telefono, ".$_SESSION[IDUSUARIO].", fecha
		FROM solicitudcreditobancodetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM solicitudcreditocomercialesdetalle WHERE solicitud = ".$_GET[folio]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO solicitudcreditocomercialesdetalle
		SELECT null AS id, ".$_GET[folio].", empresa, contacto, telefono, ".$_SESSION[IDUSUARIO].", fecha
		FROM solicitudcreditocomercialesdetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM solicitudcreditopersonadetalle WHERE solicitud = ".$_GET[folio]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO solicitudcreditopersonadetalle
		SELECT null AS id, ".$_GET[folio].", persona, ".$_SESSION[IDUSUARIO].", fecha
		FROM solicitudcreditopersonadetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM solicitudcreditosucursaldetalle WHERE solicitud = ".$_GET[folio]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO solicitudcreditosucursaldetalle
		SELECT null AS id, ".$_GET[folio].", idsucursal, sucursal, ".$_SESSION[IDUSUARIO].", fecha
		FROM solicitudcreditosucursaldetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE catalogocliente SET limitecredito = '".(($_GET[mautorizado]!='')?$_GET[mautorizado]:0)."',
		diascredito = '".(($_GET[diacredito]!='')?$_GET[diacredito]:0)."', diarevision = '".$_GET[h_revision]."', 
		diapago = '".$_GET[h_pago]."' WHERE id = ".$_GET[idcliente]."";
		mysql_query($s,$l) or die($s);
		
		$s = "call proc_RegistroCobranza('CREDITO_MODIFICADO', '".$_GET[folio]."', '', 'NO', 0, 0);";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	}
?>