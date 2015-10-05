<?	session_start();
	require_once('../../Conectar.php');	
	$link=Conectarse('webpmm');		
	if($_GET['accion']==1){// CLIENTE
		$s = "SELECT c.personamoral, c.tipocliente, t.descripcion As destipocliente,
		c.foliocredito, c.saldo, c.disponible, c.ventames, c.limitecredito,
		c.diascredito, c.diapago, c.diarevision, c.pagocheque,
		c.nombre, c.paterno, c.materno, c.rfc, c.email, c.celular, c.web,
		c.convenio, c.poliza, c.npoliza, c.aseguradora, c.vigencia,
		c.clasificacioncliente, c.activado,c.tipoclientepromociones, c.activado,
		c.comision
		FROM catalogocliente c
		LEFT JOIN catalogotipocliente t ON c.tipocliente=t.id
		WHERE c.id='".$_GET['cliente']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){			
			$f = mysql_fetch_object($r);
			$f->nombre = utf8_decode($f->nombre);
			$f->paterno = utf8_decode($f->paterno);
			$f->materno = utf8_decode($f->materno);
			$f->email = utf8_decode($f->email);
			
			$s = "delete from direcciontmp where idusuario = $_SESSION[IDUSUARIO] and idpagina = '$_GET[idpagina]'";
			mysql_query($s,$link);
			
			$s = "INSERT INTO direcciontmp
			(iddireccion,origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado,pais,telefono,fax,facturacion,idusuario,idpagina)
			SELECT 
			id,origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado,pais,telefono,fax,facturacion,'$_SESSION[IDUSUARIO]',$_GET[idpagina]
			FROM direccion
			WHERE codigo = $_GET[cliente] and origen = 'cl'";
			mysql_query($s,$link) or die($s);
			
			$sql="SELECT * FROM direcciontmp WHERE idusuario='$_SESSION[IDUSUARIO]' and idpagina='$_GET[idpagina]'";
			$sqldir=mysql_query($sql,$link); 
			while($res=mysql_fetch_array($sqldir)){
				$res['calle'] = cambio_texto($res['calle']);
				$res['crucecalles'] = cambio_texto($res['crucecalles']);
				$res['colonia'] = cambio_texto($res['colonia']);
				$res['poblacion'] = cambio_texto($res['poblacion']);
				$res['municipio'] = cambio_texto($res['municipio']);
				$res['estado'] = cambio_texto($res['estado']);
				$res['pais'] = cambio_texto($res['pais']);
				$res['facturacion'] = ($res['facturacion']=="SI")?'SI':'NO';
				$res['telefono'] = ($res['telefono']=="")?0:$res['telefono'];
				$direccion[] = $res;
			}
			
			$direcciones = json_encode($direccion);
			
			$s = "SELECT $f->limitecredito - IFNULL(SUM(IF(pagado='N', total,0)),0) AS disponible, 
			IFNULL(SUM( IF(MONTH(CURRENT_DATE)=MONTH(fechacreo) AND YEAR(CURRENT_DATE)=YEAR(fechacreo) ,1, 0)),0) AS ventames, 
			IFNULL(SUM(IF(pagado='N', total,0)),0) AS saldo
			FROM pagoguias WHERE cliente = '".$_GET['cliente']."'";
			$rx = mysql_query($s,$link) or die($s);
			$fx = mysql_fetch_object($rx);
			
			$s = "SELECT * FROM generacionconvenio WHERE estadoconvenio = 'ACTIVADO' and idcliente=".$_GET[cliente];
			$rc = mysql_query($s,$link) or die($s);
			
			if(mysql_num_rows($rc)=='0'){
				$s ="SELECT MAX(folio) folio FROM generacionconvenio WHERE idcliente = '".$_GET[cliente]."'";
				$rc = mysql_query($s,$link) or die("error en linea ".__LINE__);
				$fc = mysql_fetch_object($rc);
				
				$s = "SELECT estadoconvenio FROM generacionconvenio WHERE folio = '$fc->folio'";
				$rc = mysql_query($s,$link) or die("error en linea ".__LINE__);
				$fc = mysql_fetch_object($rc);
				if($fc->estadoconvenio=='EXPIRADO'){
					$f->tieneconvenio = "EX";
				}
			}else{
				$f->tieneconvenio = ((mysql_num_rows($rc)>0)?"SI":"NO");
			}
			
			$s = "SELECT sc.cliente FROM solicitudcredito sc INNER JOIN catalogocliente c ON sc.cliente= c.id 
			WHERE sc.estado='ACTIVADO' AND sc.cliente=".$_GET[cliente];
			$rc = mysql_query($s,$link) or die($s);
			$f->tienecredito = ((mysql_num_rows($rc)>0)?"SI":"NO");
			
			$f->personamoral = cambio_texto($f->personamoral);
			$f->nombre = cambio_texto($f->nombre);
			$f->paterno = cambio_texto($f->paterno);
			$f->materno = cambio_texto($f->materno);
			$f->comision = cambio_texto($f->comision);	
			$f->email = cambio_texto($f->email);
			$f->web = cambio_texto($f->web);
			$f->tipocliente = cambio_texto($f->tipocliente);
			$f->destipocliente = cambio_texto($f->destipocliente);
			$f->clasificacioncliente = cambio_texto($f->clasificacioncliente);
			$f->foliocredito = cambio_texto($f->foliocredito);
			$f->saldo = cambio_texto($fx->saldo);
			$f->disponible = cambio_texto($fx->disponible);
			$f->ventames = cambio_texto($fx->ventames);
			$f->limitecredito = cambio_texto($f->limitecredito);
			$f->diascredito = cambio_texto($f->diascredito);
			$f->diapago = cambio_texto($f->diapago);
			$f->diarevision = cambio_texto($f->diarevision);
			$f->activado = cambio_texto($f->activado);
			$f->npoliza = cambio_texto($f->npoliza);
			$f->poliza = cambio_texto($f->poliza);
			$f->aseguradora = cambio_texto($f->aseguradora);
			$f->vigencia = cambio_texto($f->vigencia);
			$f->convenio = cambio_texto($f->convenio);
			$f->pagocheque = cambio_texto($f->pagocheque);
			$f->tipoclientepromociones = cambio_texto($f->tipoclientepromociones);

			$datoscliente = json_encode($f);
			
			$sql = mysql_query("SELECT nick FROM catalogoclientenick WHERE cliente='".$_GET['cliente']."'",$link);
			while($row=mysql_fetch_array($sql)){
				$row['nick'] = cambio_texto($row['nick']);
				$nick[] = $row;
			}
			$nicks = json_encode($nick);
			$resultado = "({
				'datoscliente':$datoscliente,
				'direcciones':$direcciones,
				'nicks':$nicks
			})";
			
			echo str_replace('"null"',"''",str_replace("&#32;","",$resultado));
		}else{
			echo "({'datoscliente':null})";
		}
		
	}else if($_GET['accion']==2){// PROSPECTO
	
		$s = "SELECT * FROM catalogoprospecto WHERE id='".$_GET['cliente']."'";			
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			
			$sx = "delete from direcciontmp where idusuario = $_SESSION[IDUSUARIO] and idpagina = '$_GET[idpagina]'";
			mysql_query($sx,$link);
			
			$sx = "INSERT INTO direcciontmp
			(iddireccion,origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado,pais,telefono,fax,facturacion,idusuario,idpagina)
			SELECT 
			id,'cl',codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado,pais,telefono,fax,facturacion,'$_SESSION[IDUSUARIO]',$_GET[idpagina]
			FROM direccion
			WHERE codigo = $_GET[cliente] and origen='pro'";
			mysql_query($sx,$link) or die($s);
			
			$f = mysql_fetch_object($r);
			$f->personamoral = cambio_texto($f->personamoral);
			$f->nombre = cambio_texto($f->nombre);
			$f->paterno = cambio_texto($f->paterno);
			$f->materno = cambio_texto($f->materno);
			$f->comision = cambio_texto($f->comision);	
			$f->email = cambio_texto($f->email);
			$f->web = cambio_texto($f->web);
			
			$datoscliente = json_encode($f);
			
			$sql = mysql_query("SELECT nick FROM catalogoprospectonick WHERE prospecto='".$_GET['cliente']."'",$link);
			$to = mysql_num_rows($sql);
			$nicks = "[]";
			if($to>0){
				while($row=mysql_fetch_array($sql)){
					$row['nick'] = cambio_texto($row['nick']);
					$nick[] = $row['nick'];
				}
				$nicks = json_encode($nick);
			}
			
			$s = "SELECT * FROM direcciontmp WHERE idusuario='$_SESSION[IDUSUARIO]' and idpagina='$_GET[idpagina]'";		
			$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
			$direcciones = "[]";
			while($res=mysql_fetch_array($r)){
				$res['calle'] = cambio_texto($res['calle']);
				$res['crucecalles'] = cambio_texto($res['crucecalles']);
				$res['colonia'] = cambio_texto($res['colonia']);
				$res['poblacion'] = cambio_texto($res['poblacion']);
				$res['municipio'] = cambio_texto($res['municipio']);
				$res['estado'] = cambio_texto($res['estado']);
				$res['pais'] = cambio_texto($res['pais']);
				$res['facturacion'] = ($res['facturacion']=="SI")?'SI':'NO';
				$res['telefono'] = ($res['telefono']=="")?0:$res['telefono'];
				$direccion[] = $res;
			}
			
			$direcciones = json_encode($direccion);
			
			$resultado = "({
				'datoscliente':$datoscliente,
				'direcciones':$direcciones,
				'nicks':$nicks
			})";
			echo str_replace('"null"',"''",str_replace("&#32;","",$resultado));
		}else{
			echo "({'datoscliente':null})";
		}
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
	
	if($_POST['accion']==8){
		
		$s = "select * from direcciontmp where id='$_POST[id]' and idusuario = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$link) or die($s);
		if(mysql_num_rows($r)>0){
			$s = "update direcciontmp set
			calle='$_POST[calle]',numero='$_POST[numero]',crucecalles='$_POST[cruce]',
			cp='$_POST[cp]',colonia='$_POST[colonia]',poblacion='$_POST[poblacion]',
			municipio='$_POST[municipio]',estado='$_POST[estado]',pais='$_POST[pais]',
			telefono='$_POST[telefono]',fax='$_POST[fax]',facturacion='$_POST[facturacion]'
			where id='$_POST[id]'";
			mysql_query($s,$link) or die("$s <br>error en linea ".__LINE__);
			$id = $_POST[id];
		}else{
			$s = "INSERT INTO direcciontmp
			(iddireccion,origen,codigo,calle,numero,crucecalles,
			 cp,colonia,poblacion,municipio,estado,pais,
			 telefono,fax,facturacion,idusuario,idpagina)
			SELECT 
			null,'cl','$_POST[codigo]','$_POST[calle]','$_POST[numero]','$_POST[cruce]',
			'$_POST[cp]','$_POST[colonia]','$_POST[poblacion]','$_POST[municipio]','$_POST[estado]','$_POST[pais]',
			'$_POST[telefono]','$_POST[fax]','$_POST[facturacion]','$_SESSION[IDUSUARIO]',$_POST[idpagina]";
			mysql_query($s,$link) or die("$s <br>error en linea ".__LINE__);
			$id = mysql_insert_id($link);
		}
		echo "({'id':'$id'})";
	}
	
	if($_POST['accion']==9){
		$s = "delete from direcciontmp where idusuario = $_SESSION[IDUSUARIO] and idpagina='$_POST[idpagina]'";
		$r = mysql_query($s,$link) or die($s);
	}
	
	if($_POST['accion']==10){
		$s = "delete from direcciontmp where idusuario = $_SESSION[IDUSUARIO] and idpagina='$_POST[idpagina]' and id='$_POST[idfila]'";
		$r = mysql_query($s,$link) or die($s);
	}
?>