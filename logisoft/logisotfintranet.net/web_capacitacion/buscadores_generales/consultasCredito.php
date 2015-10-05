<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	header('Content-type: text/xml');
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	if($_GET['accion']==1){// OBTENER SOLICITUD
	$s = "SELECT fechasolicitud, estado, folioconvenio, fechaautorizacion, solicitante,
	personamoral, cliente, giro, antiguedad, representantelegal, actaconstitutiva,
	numeroacta, fechaescritura, fechainscripcion, identificacionlegal, numeroidentificacion,
	hacienda, fechainiciooperaciones, comprobante, comprobanteluz, estadocuenta, banco, cuenta,
	solicitud, semanapago, lunespago, martespago, miercolespago, juevespago, viernespago,
	sabadopago, horariopago, apago, responsablepago, celularpago, telefonopago, faxpago,
	semanarevision, lunesrevision, martesrevision, miercolesrevision, juevesrevision,
	viernesrevision, sabadorevision, horariorevision, arevision, montosolicitado, rfc2,
	montoautorizado, diascredito, observaciones, nick, rfc, nombre, paterno, materno,
	calle, numero, cp, colonia, poblacion, municipio, pais, celular, telefono, 
	email, estadoc FROM solicitudcredito
	WHERE folio=".$_GET['credito']."";

	$suc = mysql_query("SELECT * FROM solicitudcreditosucursaldetalle WHERE solicitud=".$_GET['credito']."",$link);
	$fi = mysql_fetch_array($suc);
	$cantcu = mysql_num_rows($suc);	
	$r = mysql_query($s,$link) or die(mysql_error($link)."error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">
		<datos>";
		$xml.="<fechasolicitud>".cambiaf_a_normal($f->fechasolicitud)."</fechasolicitud>";
		$xml.="<estado>".cambio_texto($f->estado)."</estado>";
		$xml.="<folioconvenio>".cambio_texto($f->folioconvenio)."</folioconvenio>";
		$xml.="<fechaautorizacion>".date('d/m/Y')."</fechaautorizacion>";
		$xml.="<solicitante>".cambio_texto($f->solicitante)."</solicitante>";
		$xml.="<personamoral>".cambio_texto($f->personamoral)."</personamoral>";
		$xml.="<cliente>".cambio_texto($f->cliente)."</cliente>";		
		$xml.="<nick>".cambio_texto($f->nick)."</nick>";
		$xml.="<rfc>".cambio_texto($f->rfc)."</rfc>";
		$xml.="<nombre>".cambio_texto($f->nombre)."</nombre>";
		$xml.="<paterno>".cambio_texto($f->paterno)."</paterno>";
		$xml.="<materno>".cambio_texto($f->materno)."</materno>";		
		$xml.="<calle>".cambio_texto($f->calle)."</calle>";
		$xml.="<numero>".cambio_texto($f->numero)."</numero>";
		$xml.="<cp>".cambio_texto($f->cp)."</cp>";
		$xml.="<colonia>".cambio_texto($f->colonia)."</colonia>";
		$xml.="<poblacion>".cambio_texto($f->poblacion)."</poblacion>";		
		$xml.="<municipio>".cambio_texto($f->municipio)."</municipio>";
		$xml.="<pais>".cambio_texto($f->pais)."</pais>";
		$xml.="<celular>".cambio_texto($f->celular)."</celular>";
		$xml.="<telefono>".cambio_texto($f->telefono)."</telefono>";
		$xml.="<email>".cambio_texto($f->email)."</email>";
		$xml.="<estadoc>".cambio_texto($f->estadoc)."</estadoc>";		
		$xml.="<giro>".cambio_texto($f->giro)."</giro>";
		$xml.="<antiguedad>".cambio_texto($f->antiguedad)."</antiguedad>";
		$xml.="<representante>".cambio_texto($f->representantelegal)."</representante>";
		$xml.="<actaconstitutiva>".cambio_texto($f->actaconstitutiva)."</actaconstitutiva>";
		$xml.="<numeroacta>".cambio_texto($f->numeroacta)."</numeroacta>";
		$xml.="<fechaescritura>".cambiaf_a_normal($f->fechaescritura)."</fechaescritura>";
		$xml.="<fechainscripcion>".cambiaf_a_normal($f->fechainscripcion)."</fechainscripcion>";
		$xml.="<identificacion>".cambio_texto($f->identificacionlegal)."</identificacion>";
		$xml.="<nidentificacion>".cambio_texto($f->numeroidentificacion)."</nidentificacion>";
		$xml.="<hacienda>".cambio_texto($f->hacienda)."</hacienda>";
		$xml.="<fechainiciooperaciones>".cambiaf_a_normal($f->fechainiciooperaciones)."</fechainiciooperaciones>";
		$xml.="<rfc2>".cambio_texto($f->rfc2)."</rfc2>";
		$xml.="<comprobante>".cambio_texto($f->comprobante)."</comprobante>";
		$xml.="<comprobanteluz>".cambio_texto($f->comprobanteluz)."</comprobanteluz>";
		$xml.="<estadocuenta>".cambio_texto($f->estadocuenta)."</estadocuenta>";
		$xml.="<banco>".cambio_texto($f->banco)."</banco>";
		$xml.="<cuenta>".cambio_texto($f->cuenta)."</cuenta>";
		$xml.="<solicitud>".cambio_texto($f->solicitud)."</solicitud>";
		$xml.="<semanapago>".cambio_texto($f->semanapago)."</semanapago>";
		$xml.="<lunespago>".cambio_texto($f->lunespago)."</lunespago>";
		$xml.="<martespago>".cambio_texto($f->martespago)."</martespago>";
		$xml.="<miercolespago>".cambio_texto($f->miercolespago)."</miercolespago>";
		$xml.="<juevespago>".cambio_texto($f->juevespago)."</juevespago>";
		$xml.="<viernespago>".cambio_texto($f->viernespago)."</viernespago>";				
		$xml.="<sabadopago>".cambio_texto($f->sabadopago)."</sabadopago>";
		$xml.="<horariopago>".cambio_texto($f->horariopago)."</horariopago>";
		$xml.="<apago>".cambio_texto($f->apago)."</apago>";
		$xml.="<responsablepago>".cambio_texto($f->responsablepago)."</responsablepago>";
		$xml.="<celularpago>".cambio_texto($f->celularpago)."</celularpago>";
		$xml.="<telefonopago>".cambio_texto($f->telefonopago)."</telefonopago>";
		$xml.="<faxpago>".cambio_texto($f->faxpago)."</faxpago>";
		$xml.="<semanarevision>".cambio_texto($f->semanarevision)."</semanarevision>";
		$xml.="<lunesrevision>".cambio_texto($f->lunesrevision)."</lunesrevision>";
		$xml.="<martesrevision>".cambio_texto($f->martesrevision)."</martesrevision>";
		$xml.="<miercolesrevision>".cambio_texto($f->miercolesrevision)."</miercolesrevision>";
		$xml.="<juevesrevision>".cambio_texto($f->juevesrevision)."</juevesrevision>";		
		$xml.="<viernesrevision>".cambio_texto($f->viernesrevision)."</viernesrevision>";
		$xml.="<sabadorevision>".cambio_texto($f->sabadorevision)."</sabadorevision>";
		$xml.="<horariorevision>".cambio_texto($f->horariorevision)."</horariorevision>";
		$xml.="<arevision>".cambio_texto($f->arevision)."</arevision>";		
		$xml.="<montosolicitado>".cambio_texto($f->montosolicitado)."</montosolicitado>";
		$xml.="<montoautorizado>".cambio_texto($f->montoautorizado)."</montoautorizado>";
		$xml.="<diascredito>".cambio_texto($f->diascredito)."</diascredito>";
		$xml.="<observaciones>".cambio_texto($f->observaciones)."</observaciones>";			
		$xml.="</datos>";
		if($fi[2]=="TODAS"){
			$suc = mysql_query("SELECT descripcion FROM catalogosucursal WHERE id > 1",$link);
			
				$xml.="<cansuc>".mysql_num_rows($suc)."</cansuc>";
				$xml.="<todas>todas</todas>";
				while($row=mysql_fetch_object($suc)){
					$xml.="<suc>".cambio_texto($row->descripcion)."</suc>";
				}
			
		}else if($cantcu>1){
				$xml.="<datos>";
				$xml.="<cansuc>".$cantcu."</cansuc>";
				while($rowcu=mysql_fetch_array($suc)){
					$xml.="<suc>".cambio_texto($rowcu[2])."</suc>";					
				}
				$xml.="</datos>";
		}else{
				$rcu = mysql_fetch_array($suc);
				$xml.="<datos>";
				$xml.="<cansuc>".$cantcu."</cansuc>";				
				$xml.="<suc>".cambio_texto($rcu[2])."</suc>";				
				$xml.="</datos>";
		}
		$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			</datos>
			</xml>";
		}echo $xml;
		
	}else if($_GET['accion']==2){//OBTENER CLIENTE
		$s = "SELECT nombre, paterno, materno, rfc, email, celular, foliocredito FROM catalogocliente c
		 WHERE id='".$_GET['cliente']."' ".(($_GET['persona']!="")?"AND personamoral='".$_GET['persona']."'":"");	
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
	
		$d = "SELECT UCASE(calle) AS calle, UCASE(numero) AS numero, cp, UCASE(colonia) AS colonia, 
		UCASE(poblacion) AS poblacion, UCASE(municipio) AS municipio, UCASE(estado) AS estado, UCASE(pais) AS pais,
		UCASE(telefono) AS telefono, facturacion FROM direccion
		WHERE codigo =".$_GET['cliente']." AND origen = 'cl'";		
		
	$sd = mysql_query($d,$link) or die("error en linea ".__LINE__);	
	$rd = mysql_num_rows($sd); 
	
	$n = "SELECT UCASE(nick) AS nick FROM catalogoclientenick WHERE cliente =".$_GET['cliente']."";
	$nd = mysql_query($n,$link) or die("error en linea ".__LINE__);
	$ni = mysql_num_rows($nd); 
	
	$s = "SELECT UCASE(estado) AS estadocredito FROM solicitudcredito WHERE cliente = ".$_GET['cliente']." AND estado<>'ACTIVADO'";
	$cr= mysql_query($s,$link) or die("error en linea ".__LINE__);
	$cc= mysql_fetch_object($cr);
	
	$s = "SELECT folio, idcliente FROM generacionconvenio WHERE idcliente=".$_GET['cliente'];
	$g = mysql_query($s,$link) or die($s);
	$gc= mysql_fetch_object($g);
	
	
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
				$xml.="<nombre>".cambio_texto($f->nombre)."</nombre>";
				$xml.="<paterno>".cambio_texto($f->paterno)."</paterno>";
				$xml.="<materno>".cambio_texto($f->materno)."</materno>";
				$xml.="<celular>".cambio_texto($f->celular)."</celular>";
				$xml.="<email>".cambio_texto($f->email)."</email>";
				$xml.="<rfc>".cambio_texto($f->rfc)."</rfc>";				
				$xml.="<foliocredito>".cambio_texto($f->foliocredito)."</foliocredito>";
				$xml.="<estadocredito>".cambio_texto($cc->estadocredito)."</estadocredito>";
				$xml.="<convenio>".cambio_texto($gc->folio)."</convenio>";
				$xml.="<idcliente>".(($gc->idcliente!='')?cambio_texto($gc->idcliente):0)."</idcliente>";
		if($rd > 1){
			while($row=mysql_fetch_object($sd)){
				$xml.="<entro>Entro</entro>";
				$xml.="<calle>".cambio_texto($row->calle)."</calle>";
				$xml.="<numero>".cambio_texto($row->numero)."</numero>";
				$xml.="<cp>".cambio_texto($row->cp)."</cp>";
				$xml.="<colonia>".cambio_texto($row->colonia)."</colonia>";
				$xml.="<poblacion>".cambio_texto($row->poblacion)."</poblacion>";
				$xml.="<municipio>".cambio_texto($row->municipio)."</municipio>";
				$xml.="<estado>".cambio_texto($row->estado)."</estado>";
				$xml.="<pais>".cambio_texto($row->pais)."</pais>";
				$xml.="<telefono>".cambio_texto($row->telefono)."</telefono>";
				$xml.="<facturacion>".cambio_texto($row->facturacion)."</facturacion>";
			}
		}else{
				$drow = mysql_fetch_object($sd);
				$xml.="<calle>".cambio_texto($drow->calle)."</calle>";
				$xml.="<numero>".cambio_texto($drow->numero)."</numero>";
				$xml.="<cp>".cambio_texto($drow->cp)."</cp>";
				$xml.="<colonia>".cambio_texto($drow->colonia)."</colonia>";
				$xml.="<poblacion>".cambio_texto($drow->poblacion)."</poblacion>";
				$xml.="<municipio>".cambio_texto($drow->municipio)."</municipio>";
				$xml.="<estado>".cambio_texto($drow->estado)."</estado>";
				$xml.="<pais>".cambio_texto($drow->pais)."</pais>";
				$xml.="<telefono>".cambio_texto($drow->telefono)."</telefono>";
				$xml.="<facturacion>".cambio_texto($drow->facturacion)."</facturacion>";
		}
		if($ni > 1){
			while($nrow1=mysql_fetch_object($nd)){
				$xml.="<nick>".cambio_texto($nrow1->nick)."</nick>";
			}
		}else{
				$nrow = mysql_fetch_object($nd);
				$xml.="<nick>".cambio_texto($nrow->nick)."</nick>";
		}
		$xml.="<n>".$ni."</n>";
		$xml.="<dir>".$rd."</dir>";
		$xml.="<encontro>".$cant."</encontro>";
		$xml.="</datos>
				</xml>";			
				
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;			
	
	}else if($_GET['accion']==3){//OBTENER CLIENTE BUSQUEDA EN BUSCADORES GENERALES
		if($_GET[campo]=="nick")
			$_GET[campo] = "ccn.nick";
		else
			$_GET[campo] = "cc.".$_GET[campo];
		
		$s = "select ccn.nick, cc.rfc, cc.id, cc.nombre, cc.paterno, cc.materno, cc.sucursal
		from catalogocliente as cc
		left join catalogoclientenick as ccn on cc.id = ccn.cliente
		where cc.personamoral='".$_GET['tipo']."' AND $_GET[campo] ".(($_GET[campo]=="cc.id")?" = '$_GET[valor]'":" like '$_GET[valor]%'")."
		group by cc.id";
		$r = mysql_query($s,$link) or die(mysql_error($link)."$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			while($f = mysql_fetch_object($r)){
			$xml .= "<nick>".cambio_texto(strtoupper($f->nick))."</nick>
				<rfc>".cambio_texto(strtoupper($f->rfc))."</rfc>
				<idcliente>$f->id</idcliente>
				<nombre>".cambio_texto(strtoupper($f->nombre))."</nombre>
				<paterno>".cambio_texto(strtoupper($f->paterno))."</paterno>
				<materno>".cambio_texto(strtoupper($f->materno))."</materno>
				<sucursal>".cambio_texto(strtoupper($f->sucursal))."</sucursal>";
			}
			$xml .= "<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	
	}else if($_GET['accion']==4){//OBTENER BANCOS
$sb = mysql_query("SELECT * FROM solicitudcreditobancodetalle WHERE solicitud=".$_GET['credito']."",$link);
		$cantb = mysql_num_rows($sb);
		if($cantb>0){
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">";
			if($cantb>1){
					$xml.="<datos>";					
					while($rowb=mysql_fetch_array($sb)){
						$xml.="<banco>".cambio_texto($rowb[2])."</banco>";
						$xml.="<sucursal>".cambio_texto($rowb[3])."</sucursal>";
						$xml.="<cuenta>".cambio_texto($rowb[4])."</cuenta>";
						$xml.="<telefono>".cambio_texto($rowb[5])."</telefono>";	
					}
					$xml.="</datos>";		
			}else{
					$rb = mysql_fetch_array($sb);
					$xml.="<datos>";			
					$xml.="<banco>".cambio_texto($rb[2])."</banco>";
					$xml.="<sucursal>".cambio_texto($rb[3])."</sucursal>";
					$xml.="<cuenta>".cambio_texto($rb[4])."</cuenta>";
					$xml.="<telefono>".cambio_texto($rb[5])."</telefono>";
					$xml.="</datos>";
					
			}
			$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>			
				</datos>
				</xml>";
		}echo $xml;	
	}else if($_GET['accion']==5){//OBTENER COMERCIALES
	$sc = mysql_query("SELECT * FROM solicitudcreditocomercialesdetalle WHERE solicitud=".$_GET['credito']."",$link);
	$cantc = mysql_num_rows($sc);
		if(mysql_num_rows($sc)>0){
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">";
			if($cantc>1){
					$xml.="<datos>";
					while($rowc=mysql_fetch_array($sc)){
						$xml.="<empresa>".cambio_texto($rowc[2])."</empresa>";
						$xml.="<contacto>".cambio_texto($rowc[3])."</contacto>";
						$xml.="<telefono>".cambio_texto($rowc[4])."</telefono>";
					}
					$xml.="</datos>";
			}else{
					$rc = mysql_fetch_array($sc);
					$xml.="<datos>";
					$xml.="<empresa>".cambio_texto($rc[2])."</empresa>";
					$xml.="<contacto>".cambio_texto($rc[3])."</contacto>";
					$xml.="<telefono>".cambio_texto($rc[4])."</telefono>";
					$xml.="</datos>";
	
			}
			$xml.="</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>			
			</datos>
			</xml>";
		}echo $xml;	
	}else if($_GET['accion']==6){//OBTENER PERSONAS
	$sp = mysql_query("SELECT * FROM solicitudcreditopersonadetalle WHERE solicitud=".$_GET['credito']."",$link);
	$cantp = mysql_num_rows($sp);
		if(mysql_num_rows($sp)>0){
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">";
			if($cantp>1){
				$xml.="<datos>";
				while($rowp=mysql_fetch_array($sp)){
				$xml.="<persona>".cambio_texto($rowp[2])."</persona>";	
				}
				$xml.="</datos>";
			}else{
					$rp = mysql_fetch_array($sp);
					$xml.="<datos>";
					$xml.="<persona>".cambio_texto($rp[2])."</persona>";	
					$xml.="</datos>";			
			}
			$xml.="</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>			
			</datos>
			</xml>";
		}echo $xml;	
	}
?>