<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}	*/
	header('Content-type: text/xml');
	require_once('../../Conectar.php');	
	$link=Conectarse('webpmm');		
	if($_GET['accion']==1){// CLIENTE
	$s = "SELECT c.personamoral, c.tipocliente, t.descripcion As destipocliente,
	c.foliocredito, c.saldo, c.disponible, c.ventames, c.limitecredito,
	c.diascredito, c.diapago, c.diarevision, c.pagocheque,
	c.nombre, c.paterno, c.materno, c.rfc, c.email, c.celular, c.web,
	c.convenio, c.poliza, c.npoliza, c.aseguradora, c.vigencia,
	c.clasificacioncliente, c.activado,c.tipoclientepromociones,
	c.comision 
	FROM catalogocliente c
	LEFT JOIN catalogotipocliente t ON c.tipocliente=t.id
	WHERE c.id='".$_GET['cliente']."'";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){			
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml="<xml version=\"1.0\" standalone=\"yes\" encoding=\"utf-8\">"; 			$xml.="<datos>";
			$sql="SELECT * FROM direccion WHERE origen='cl' AND codigo='".$_GET['cliente']."'";
			$sqldir=mysql_query($sql,$link); 
			while($res=mysql_fetch_array($sqldir)){
				$xml.="<id>".cambio_texto($res['id'])."</id>";
				$xml.="<calle>".cambio_texto($res['calle'])."</calle>";
				$xml.="<num>".cambio_texto($res['numero'])."</num>";
				$xml.="<cruce>".cambio_texto($res['crucecalles'])."</cruce>";
				$xml.="<cp>".cambio_texto($res['cp'])."</cp>";
				$xml.="<colonia>".cambio_texto($res['colonia'])."</colonia>";
				$xml.="<poblacion>".cambio_texto($res['poblacion'])."</poblacion>";
				$xml.="<municipio>".cambio_texto($res['municipio'])."</municipio>";
				$xml.="<estado>".cambio_texto($res['estado'])."</estado>";
				$xml.="<pais>".cambio_texto($res['pais'])."</pais>";
				$xml.="<telefono>".cambio_texto($res['telefono'])."</telefono>";
				$xml.="<fax>".cambio_texto($res['fax'])."</fax>";
				$xml.="<fact>".cambio_texto($res['facturacion'])."</fact>";				
			}
			$xml.="</datos>";
			$sql_r=mysql_query($sql,$link); 
			$r=mysql_fetch_array($sql_r);
			$usuario=$r['usuario'];
			$fecha=$r['fecha'];			
			
			$s = "SELECT $f->limitecredito - IFNULL(SUM(IF(pagado='N', total,0)),0) AS disponible, 
			IFNULL(SUM( IF(MONTH(CURRENT_DATE)=MONTH(fechacreo) AND YEAR(CURRENT_DATE)=YEAR(fechacreo) ,1, 0)),0) AS ventames, 
			IFNULL(SUM(IF(pagado='N', total,0)),0) AS saldo
			FROM pagoguias WHERE cliente = '".$_GET['cliente']."'";
			$rx = mysql_query($s,$link) or die($s);
			$fx = mysql_fetch_object($rx);
			
			$s = "SELECT * FROM generacionconvenio WHERE estadoconvenio = 'ACTIVADO' and idcliente=".$_GET[cliente];
			$rc = mysql_query($s,$link) or die($s);
			
			$xml.="<datos>";
			$xml.="<personamoral>".cambio_texto($f->personamoral)."</personamoral>";
			$xml.="<nombre>".cambio_texto($f->nombre)."</nombre>";
			$xml.="<paterno>".cambio_texto($f->paterno)."</paterno>";
			$xml.="<materno>".cambio_texto($f->materno)."</materno>";
			$xml.="<comision>".cambio_texto($f->comision)."</comision>";
			$xml.="<rfc>".cambio_texto($f->rfc)."</rfc>";			
			$xml.="<email>".cambio_texto($f->email)."</email>";
			$xml.="<celular>".cambio_texto($f->celular)."</celular>";
			$xml.="<web>".cambio_texto($f->web)."</web>";
			$xml.="<tipocliente>".cambio_texto($f->tipocliente)."</tipocliente>";
			$xml.="<destipocliente>".cambio_texto($f->destipocliente)."</destipocliente>";
			$xml.="<clasificacioncliente>".cambio_texto($f->clasificacioncliente)."</clasificacioncliente>";
			$xml.="<foliocredito>".cambio_texto($f->foliocredito)."</foliocredito>";
			$xml.="<saldo>".cambio_texto($fx->saldo)."</saldo>";
			$xml.="<disponible>".cambio_texto($fx->disponible)."</disponible>";
			$xml.="<ventames>".cambio_texto($fx->ventames)."</ventames>";
			$xml.="<limitecredito>".cambio_texto($f->limitecredito)."</limitecredito>";
			$xml.="<diascredito>".cambio_texto($f->diascredito)."</diascredito>";
			$xml.="<diapago>".cambio_texto($f->diapago)."</diapago>";
			$xml.="<diarevision>".cambio_texto($f->diarevision)."</diarevision>";
			$xml.="<activado>".cambio_texto($f->activado)."</activado>";
			$xml.="<npoliza>".cambio_texto($f->npoliza)."</npoliza>";
			$xml.="<poliza>".cambio_texto($f->poliza)."</poliza>";
			$xml.="<aseguradora>".cambio_texto($f->aseguradora)."</aseguradora>";
			$xml.="<vigencia>".cambio_texto($f->vigencia)."</vigencia>";
			$xml.="<convenio>".cambio_texto($f->convenio)."</convenio>";
			$xml.="<pagocheque>".cambio_texto($f->pagocheque)."</pagocheque>";
			$xml.="<tipoclientepromociones>".cambio_texto($f->tipoclientepromociones)."</tipoclientepromociones>";
			$xml.="<tieneconvenio>".((mysql_num_rows($rc)>0)?"SI":"NO")."</tieneconvenio>";
			$sql = mysql_query("SELECT nick FROM catalogoclientenick WHERE cliente='".$_GET['cliente']."'",$link);
			$to = mysql_num_rows($sql);
			$xml.="<total>".$to."</total>";
		if($to>0){
			while($row=mysql_fetch_array($sql)){
			$xml.="<nick>".cambio_texto($row['nick'])."</nick>";
			}
		}
			$xml.="<encontro>$cant</encontro>";
			$xml.="</datos>";
			$xml.="</xml>";			
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
		
	}else if($_GET['accion']==2){// PROSPECTO
		$s = "SELECT * FROM catalogoprospecto WHERE id='".$_GET['cliente']."'";
		$sql = mysql_query("SELECT nick FROM catalogoprospectonick WHERE prospecto='".$_GET['cliente']."'",$link);
		$to = mysql_num_rows($sql);
		$sqldir=mysql_query("SELECT usuario, fecha FROM direccion WHERE origen='pro' AND codigo='".$_GET['cliente']."'",$link); $res=mysql_fetch_array($sqldir);
		$usuario=$res[0];
		$fechahora=$res[1];
		
		$con = mysql_query("SELECT * FROM direcciontmp WHERE usuario='$usuario' AND fecha='$fechahora'",$link);
		if(mysql_num_rows($con)==0){
			$direc=mysql_query("INSERT INTO direcciontmp SELECT 0 as id, calle, numero, crucecalles, cp, colonia, poblacion, municipio, estado, pais, telefono, fax, facturacion, usuario, fecha FROM direccion WHERE usuario='$usuario' And fecha='$fechahora'",$link);
		}		
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){			
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<personamoral>".cambio_texto($f->personamoral)."</personamoral>";
		$xml.="<nombre>".cambio_texto($f->nombre)."</nombre>";
		$xml.="<paterno>".cambio_texto($f->paterno)."</paterno>";
		$xml.="<materno>".cambio_texto($f->materno)."</materno>";
		$xml.="<rfc>".cambio_texto($f->rfc)."</rfc>";			
		$xml.="<email>".cambio_texto($f->email)."</email>";
		$xml.="<celular>".cambio_texto($f->celular)."</celular>";
		$xml.="<web>".cambio_texto($f->web)."</web>";
		$xml.="<esprospecto>SI</esprospecto>";
		$xml.="<total>".$to."</total>";
		if($to>0){
			while($row=mysql_fetch_array($sql)){
			$xml.="<nick>".cambio_texto($row['nick'])."</nick>";
			}
		}
		$xml.="<encontro>$cant</encontro>";
		$xml.="</datos>
				</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==3){// OBTENER DIRECCION PROSPECTO
		$s = "SELECT * FROM direccion WHERE codigo='".$_GET['cliente']."' and origen='pro'";		
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){			
			$f = mysql_fetch_array($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml.="<calle>".cambio_texto($f['calle'])."</calle>";
			$xml.="<num>".cambio_texto($f['numero'])."</num>";
			$xml.="<cruce>".cambio_texto($f['crucecalles'])."</cruce>";
			$xml.="<cp>".cambio_texto($f['cp'])."</cp>";
			$xml.="<colonia>".cambio_texto($f['colonia'])."</colonia>";
			$xml.="<poblacion>".cambio_texto($f['poblacion'])."</poblacion>";
			$xml.="<municipio>".cambio_texto($f['municipio'])."</municipio>";
			$xml.="<estado>".cambio_texto($f['estado'])."</estado>";
			$xml.="<pais>".cambio_texto($f['pais'])."</pais>";
			$xml.="<telefono>".cambio_texto($f['telefono'])."</telefono>";
			$xml.="<fax>".cambio_texto($f['fax'])."</fax>";
			$xml.="<fact>".cambio_texto($f['facturacion'])."</fact>";
			$xml.="<id>".cambio_texto($f['id'])."</id>";		
		$xml.="</datos>
				</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>			
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==4){// OBTENER SUCURSALES CREDITO
		$s = "SELECT ss.sucursal FROM solicitudcredito sc
		INNER JOIN solicitudcreditosucursaldetalle ss ON sc.folio=ss.solicitud
		WHERE sc.cliente='".$_GET['cliente']."' and sc.folio=".$_GET['credito']." AND ss.solicitud=".$_GET['credito']."";		
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
	$suc = mysql_query("SELECT descripcion FROM catalogosucursal WHERE id > 1 ORDER BY descripcion ASC",$link);	
		if(mysql_num_rows($r)>0){
			$cant = mysql_num_rows($r);
			$fi = mysql_fetch_array($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			if($fi['sucursal']=="TODAS" || $fi['sucursal']=="TODA"){
				$xml.="<total>".mysql_num_rows($suc)."</total>";
				while($row=mysql_fetch_object($suc)){
					$xml.="<sucursal>".cambio_texto($row->descripcion)."</sucursal>";
				}
			}else if($cant>1){
				$rs = mysql_query($s,$link);
				$xml.="<total>".$cant."</total>";
				while($fsuc=mysql_fetch_object($rs)){
					$xml.="<sucursal>".cambio_texto($fsuc->sucursal)."</sucursal>";
				}	
			}else{
				
				$xml.="<total>1</total>";
				$xml.="<sucursal>".cambio_texto($fi['sucursal'])."</sucursal>";
			}
			
		$xml.="</datos>
				</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<total>0</total>			
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==5){// OBTENER SOLICITUD CREDITO
		$s = "SELECT ss.sucursal FROM solicitudcredito sc
		INNER JOIN solicitudcreditosucursaldetalle ss ON sc.folio=ss.solicitud
		WHERE sc.cliente='".$_GET['cliente']."' and sc.folio=".$_GET['credito']." AND ss.solicitud=".$_GET['credito']."";		
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
	$suc = mysql_query("SELECT SUBSTRING(descripcion,10) AS descripcion FROM catalogosucursal WHERE id > 1",$link);	
		if(mysql_num_rows($r)>0){
			$cant = mysql_num_rows($r);
			$fi = mysql_fetch_array($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			if($fi['sucursal']=="TODAS"){
				$xml.="<total>".mysql_num_rows($suc)."</total>";
				while($row=mysql_fetch_object($suc)){
					$xml.="<sucursal>".cambio_texto($row->descripcion)."</sucursal>";
				}
			}else if($cant>1){
				$rs = mysql_query($s,$link);
				$xml.="<total>".$cant."</total>";
				while($fsuc=mysql_fetch_object($rs)){
					$xml.="<sucursal>".cambio_texto($fsuc->sucursal)."</sucursal>";
				}	
			}else{
				
				$xml.="<total>1</total>";
				$xml.="<sucursal>".cambio_texto($fi['sucursal'])."</sucursal>";
			}
			
		$xml.="</datos>
				</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>			
			</datos>
			</xml>";
		}echo $xml;
		
	}else if($_GET['accion']==7){// OBTENER CONVENIO
		$s ="SELECT c.folio, c.fecha, c.estadoconvenio,
		CONCAT(v.nombre,' ',v.apellidopaterno,' ',v.apellidomaterno)AS vendedor,
		c.precioporkg, c.precioporcaja, c.descuentosobreflete, c.consignacionkg,
		c.consignacioncaja, c.consignaciondescuento, c.prepagadas,
		c.descuentosobreflete, IFNULL(c.cantidaddescuento,0) AS cantidaddescuento,
		IFNULL(c.consignaciondescantidad,0) AS cantidaddescuentoconsignacion,
		IFNULL(c.limitekg,0) AS limitekg, IFNULL(c.costo,0) AS costo, c.preciokgexcedente,
		date_format(c.vigencia,'%d/%m/%Y') as vigencia
		FROM generacionconvenio c
		LEFT JOIN catalogoempleado v ON c.vendedor = v.id
		WHERE c.idcliente='".$_GET['cliente']."' AND c.estadoconvenio='ACTIVADO'";			
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);	
		if(mysql_num_rows($r)>0){
			$cant = mysql_num_rows($r);
			$f = mysql_fetch_object($r);
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml.="<folio>".$f->folio."</folio>";
			$xml.="<activacion>".cambiaf_a_normal($f->fecha)."</activacion>";
			$xml.="<vigencia>".$f->vigencia."</vigencia>";
			$xml.="<vendedor>".cambio_texto($f->vendedor)."</vendedor>";			
			$xml.="<precioporkg>".cambio_texto($f->precioporkg)."</precioporkg>";
			$xml.="<precioporcaja>".cambio_texto($f->precioporcaja)."</precioporcaja>";
			$xml.="<descuentoflete>".cambio_texto($f->descuentosobreflete)."</descuentoflete>";
			$xml.="<consignacionkg>".cambio_texto($f->consignacionkg)."</consignacionkg>";
			$xml.="<consignacioncaja>".cambio_texto($f->consignacioncaja)."</consignacioncaja>";
			$xml.="<consignaciondescuento>".cambio_texto($f->consignaciondescuento)."</consignaciondescuento>";			
			$xml.="<prepagadas>".cambio_texto($f->prepagadas)."</prepagadas>";
			$xml.="<cantidaddescuento>".cambio_texto($f->cantidaddescuento)."</cantidaddescuento>";
			$xml.="<preciokgexcedente>".cambio_texto($f->preciokgexcedente)."</preciokgexcedente>";
			$xml.="<cantidaddescuentoconsignacion>".cambio_texto($f->cantidaddescuentoconsignacion)."</cantidaddescuentoconsignacion>";
			$xml.="<limitekg>".cambio_texto($f->limitekg)."</limitekg>";
			$xml.="<costo>".cambio_texto($f->costo)."</costo>";
			$xml.="<estadoconvenio>".cambio_texto($f->estadoconvenio)."</estadoconvenio>";
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
	}
	
?>