<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");	
	
	//para cargar propuestaconvenio
	if($_GET[accion]==1){
		//propuesta
		
		$propuesta = "";
		$s = "SELECT propuestaconvenio.*, date_format(current_date, '%d/%m/%Y') as factual, 
		date_format(CONCAT(YEAR(CURRENT_DATE),'-12-31'), '%d/%m/%Y') as fvencimiento
		FROM propuestaconvenio WHERE folio = $_GET[valor] and estadopropuesta = 'AUTORIZADA'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->nombre = cambio_texto($f->nombre);
		$f->paterno = cambio_texto($f->paterno);
		$f->materno = cambio_texto($f->materno);
		$f->nick = cambio_texto($f->nick);
		$f->rfc = cambio_texto($f->rfc);
		$f->calle = cambio_texto($f->calle);
		$f->numero = cambio_texto($f->numero);
		$f->colonia = cambio_texto($f->colonia);
		$f->poblacion = cambio_texto($f->poblacion);
		$f->municipio = cambio_texto($f->municipio);
		$f->estado = cambio_texto($f->estado);
		$f->pais = cambio_texto($f->pais);
		$f->email = cambio_texto($f->email);
		$f->nvendedor = cambio_texto($f->nvendedor);
		$estado = $f->estadopropuesta;
		$pro = array();
		
		
		if($f->tipo=='CLI'){
			//checa si el cliente ya cuenta con convenio
			$s = "SELECT * FROM generacionconvenio WHERE idcliente=".$f->idprospecto." AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
		
			$r = mysql_query($s,$l) or die($s); $gc = mysql_fetch_object($r);
			if(mysql_num_rows($r)>0){	
				if(date('Y-m-d') >= $gc->vigencia){
					$f->vencido = "vencido";
				}	
				if(mysql_num_rows($r)>0){
					$f->yatiene = "ya tiene";
				}
			}
		}
		
		$pro[] = $f;
		$propuesta = str_replace("null",'""',json_encode($pro));
		
		//servicios grid
		$servicioscobro = "";
		$s = "SELECT servicio, cobro, precio FROM convenio_servicios WHERE idconvenio = $_GET[valor] and tipo = 'CONVENIO'";
		$r = mysql_query($s,$l) or die($s);
		$ser = array();
		while($f = mysql_fetch_object($r)){
			$ser[] = $f;
		}
		$servicioscobro = str_replace("null",'""',json_encode($ser));
		
		$servicioscobro2 = "";
		$s = "SELECT servicio, cobro, precio FROM convenio_servicios WHERE idconvenio = $_GET[valor] and tipo = 'CONSIGNACION'";
		$r = mysql_query($s,$l) or die($s);
		$ser2 = array();
		while($f = mysql_fetch_object($r)){
			$ser2[] = $f;
		}
		$servicioscobro2 = str_replace("null",'""',json_encode($ser2));
		
		//servicios combo
		$servicioscombo1 = "";
		$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SRCONVENIO'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}
		$servicioscombo1 = str_replace("null",'""',json_encode($arre));
		
		$servicioscombo2 = "";
		$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SUCONVENIO'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}
		$servicioscombo2 = str_replace("null",'""',json_encode($arre));
		
		
		$servicioscombo5 = "";
		$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SUCONSIGNACION2'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}
		$servicioscombo5 = str_replace("null",'""',json_encode($arre));
		
		// combos de prepagadas
		$servicioscombo3 = "";
		$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SRCONSIGNACION'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}
		$servicioscombo3 = str_replace("null",'""',json_encode($arre));
		
		$servicioscombo4 = "";
		$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SUCONSIGNACION1'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}
		$servicioscombo4 = str_replace("null",'""',json_encode($arre));		
		
		echo "[{propuesta:$propuesta, 
				serviciogrid1:$servicioscobro, 
				serviciogrid2:$servicioscobro2, 
				serviciocombo1:$servicioscombo1, 
				serviciocombo2:$servicioscombo2, 
				serviciocombo3:$servicioscombo3, 
				serviciocombo5:$servicioscombo5}]";
	}
	
	if($_GET[accion]==2){
		$s = "SELECT * FROM generacionconvenio WHERE idcliente=".$_GET[valor]." AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
		$r = mysql_query($s,$l) or die($s); $gc = mysql_fetch_object($r);
		
		if(date('Y-m-d') >= $gc->vigencia){
			die("vencido");			
		}	
		if(mysql_num_rows($r)>0){
			die("ya tiene");
		}
	
		$s = "SELECT cp.id, max(sc.folio) as folio, cpn.nick, cp.rfc, cp.nombre, cp.paterno, cp.materno,
		d.calle, d.numero, d.colonia, d.cp, d.poblacion, d.municipio,
		d.estado, d.pais, cp.celular, d.telefono, cp.email
		FROM catalogocliente AS cp
		left JOIN catalogoprospectonick AS cpn ON cp.id = cpn.prospecto
		left JOIN direccion AS d ON cp.id = d.codigo AND d.origen = 'cl'
		left join solicitudcredito as sc on cp.id = sc.cliente
		WHERE cp.id = $_GET[valor] and cp.personamoral = '$_GET[personamoral]'
		group by cp.id";
		
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$f->paterno = cambio_texto($f->paterno);
			$f->materno = cambio_texto($f->materno);
			$f->nick = cambio_texto($f->nick);
			$f->rfc = cambio_texto($f->rfc);
			$f->calle = cambio_texto($f->calle);
			$f->numero = cambio_texto($f->numero);
			$f->colonia = cambio_texto($f->colonia);
			$f->poblacion = cambio_texto($f->poblacion);
			$f->municipio = cambio_texto($f->municipio);
			$f->estado = cambio_texto($f->estado);
			$f->pais = cambio_texto($f->pais);
			$f->email = cambio_texto($f->email);
			$registros[] = $f;
		}
		
		echo str_replace("null",'""',json_encode($registros));
	}
	
	//guardar convenio
	if($_GET[accion]==3){
		
		$s = "SELECT folio FROM generacionconvenio WHERE propuesta = '$_GET[idpropuesta]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			//die("La propuesta ya ha sido convertida en convenio y tiene el folio de convenio $f->folio");
		}
		
		if($_GET[tipoc]!="PRO"){
			/*$s = "SELECT * FROM generacionconvenio WHERE idcliente = '$_GET[idcliente]' AND CURRENT_DATE < vigencia AND 
			(estado = 'AUTORIZADO' or estado = 'IMPRESO' or estado = 'ACTIVADO' or estado = 'RENOVAR')";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				die("No se puede registrar el convenio, el cliente ya cuenta con uno");
			}*/
		}elseif($_GET[tipoc]=="PRO"){
			$s = "SELECT idprospecto FROM propuestaconvenio WHERE folio = $_GET[idpropuesta]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$idprospecto = $f->idprospecto;
			
			$s = "INSERT INTO catalogocliente (id,personamoral,tipocliente,nombre,paterno,materno,rfc,email,celular,web,usuario,fecha)
			SELECT null,personamoral,1,nombre,paterno,materno,rfc,email,celular,web,'$_SESSION[NOMBREUSUARIO]',CURRENT_DATE
			FROM catalogoprospecto
			WHERE catalogoprospecto.id = $idprospecto";
			mysql_query($s,$l) or die($s);
			$idcliente = mysql_insert_id($l);
			
			$s = "INSERT INTO losclientes 
			(nick,rfc,id,nombre,paterno,materno,sucursal,convenio,credito)
			SELECT '', rfc,id,nombre,paterno,materno,sucursal,'NO','NO'
			FROM catalogocliente cc
			WHERE id = '$idcliente'";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO catalogoclientenick
			SELECT NULL, '$idcliente', nick, '$_SESSION[IDUSUARIO]', CURRENT_DATE
			FROM catalogoprospectonick
			WHERE catalogoprospectonick.prospecto = $idprospecto";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE direccion SET origen = 'cl', codigo = '$idcliente'
			WHERE origen = 'pro' AND codigo = $idprospecto";
			mysql_query($s,$l) or die($s);
			
			$s = "delete from catalogoprospectonick where prospecto = $idprospecto";
			mysql_query($s,$l) or die($s);
			$s = "delete from catalogoprospecto where id = $idprospecto";
			mysql_query($s,$l) or die($s);
			
			$_GET[idcliente] = $idcliente;
		}
		
		//Vigencia
		//LAST_DAY(CONCAT(YEAR(CURRENT_DATE),'-12-01'))
		//ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR)
		
		$s = "INSERT INTO generacionconvenio
		SELECT NULL, NULL, NULL, '$_GET[credito]', '$_GET[consumomensual]',
		CURRENT_DATE, 'AUTORIZADO', tipoautorizacion,'".cambiaf_a_mysql($_GET[vigencia])."',
		pc.sucursal, pc.vendedor, pc.nvendedor, 
		pc.personamoral, cp.id, cpn.nick, cp.rfc, cp.nombre, cp.paterno, cp.materno,
		pc.calle, pc.numero, pc.colonia, pc.cp, pc.poblacion, pc.municipio,
		pc.estado, pc.pais, cp.celular, pc.telefono, cp.email, pc.precioporkg, 
		pc.precioporcaja, pc.descuentosobreflete, 
		pc.cantidaddescuento, pc.limitekg, pc.costo, pc.preciokgexcedente,
		pc.prepagadas, pc.consignacionkg, 
		pc.consignacioncaja, pc.consignaciondescuento, 
		pc.consignaciondescantidad, '$_SESSION[NOMBREUSUARIO]', '$_SESSION[IDUSUARIO]', pc.valordeclarado,
		pc.limite,pc.porcada,pc.costoextra, $_GET[idpropuesta], legal FROM propuestaconvenio AS pc
		LEFT JOIN catalogocliente AS cp ON cp.id = '$_GET[idcliente]'
		LEFT JOIN catalogoclientenick AS cpn ON cp.id = cpn.cliente
		LEFT JOIN direccion AS d ON cp.id = d.codigo AND d.origen = 'cl'
		WHERE pc.folio = '$_GET[idpropuesta]'
		GROUP BY cp.id";
		mysql_query(str_replace("''","null",$s),$l) or die(str_replace("''","null",$s));
		$idconvenio = mysql_insert_id($l);
		
		$s = "UPDATE propuestaconvenio p
		SET p.tipoautorizacion = CONCAT(p.tipoautorizacion,',G')
		WHERE folio = '$_GET[idpropuesta]'";
		mysql_query($s,$l) or die(mysql_error($l).$s);
		
		$s = "insert into historialmovimientos(modulo,folio,estado,idusuario,fechamodificacion) 
		values ('generacionconvenio',$idconvenio,'AUTORIZADO',$_SESSION[IDUSUARIO],CURRENT_TIMESTAMP);";
		mysql_query($s,$l) or die(mysql_error($l).$s);
		
		//se agrego la actualizacion a la propuesta cuando es por prospecto para ke no guarde 0
		$s = "UPDATE propuestaconvenio SET idprospecto = '$_GET[idcliente]', tipo='CLI' WHERE folio = '$_GET[idpropuesta]'";
		mysql_query(str_replace("''","null",$s),$l) or die(str_replace("''","null",$s));
		
		//convenio grids
		//consignacion grids
		$s = "insert into cconvenio_configurador_preciokg
		select  $idconvenio,zona,kmi,kmf,valor,tipo,'$_SESSION[NOMBREUSUARIO]',$_SESSION[IDUSUARIO],current_date  
		from convenio_configurador_preciokg where idconvenio = $_GET[idpropuesta]";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into cconvenio_configurador_caja
		select $idconvenio,descripcion,zona,kmi,kmf,tipo,precio,'$_SESSION[NOMBREUSUARIO]',$_SESSION[IDUSUARIO],current_date,pesolimite,preciokgexcedente
		from convenio_configurador_caja where idconvenio = $_GET[idpropuesta]";
		mysql_query($s,$l) or die($s);
		//servicios
		
		$s = "insert into cconvenio_servicios
			select $idconvenio,idservicio,servicio,cobro,precio,tipo,'$_SESSION[NOMBREUSUARIO]',$_SESSION[IDUSUARIO],current_date 
			from convenio_servicios where idconvenio = $_GET[idpropuesta]";
		mysql_query($s,$l) or die($s);
		
		//selects convenio
		$s = "insert into cconvenio_servicios_sucursales
			select $idconvenio,clave,nombre,tipo,'$_SESSION[NOMBREUSUARIO]',$_SESSION[IDUSUARIO],current_date 
			from convenio_servicios_sucursales where idconvenio = $_GET[idpropuesta]";
		mysql_query($s,$l) or die($s);
		
		echo "guardo,$idconvenio,'AUTORIZADO',$_GET[idcliente]";
	}
	
	if($_GET[accion]==4){
		$s = "update generacionconvenio set estadoconvenio = 'IMPRESO' where folio = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);
		
		echo "impreso";
	}
	
	if($_GET[accion]==5){
		
		$propuesta = "";
		$s = "SELECT generacionconvenio.*, DATE_FORMAT(generacionconvenio.fecha, '%d/%m/%Y') AS factual, 
		DATE_FORMAT(generacionconvenio.vigencia, '%d/%m/%Y') AS fvencimiento,
		MAX(sc.folio) AS foliocredito, if(current_date > adddate(generacionconvenio.vigencia, interval -1 month),'RENOVAR','') as botonrenovar
		FROM generacionconvenio 
		LEFT JOIN solicitudcredito AS sc ON generacionconvenio.idcliente = sc.cliente
		WHERE generacionconvenio.folio = $_GET[valor]
		GROUP BY generacionconvenio.folio";
		
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->nombre = cambio_texto($f->nombre);
		$f->paterno = cambio_texto($f->paterno);
		$f->materno = cambio_texto($f->materno);
		$f->nick = cambio_texto($f->nick);
		$f->rfc = cambio_texto($f->rfc);
		$f->calle = cambio_texto($f->calle);
		$f->numero = cambio_texto($f->numero);
		$f->colonia = cambio_texto($f->colonia);
		$f->poblacion = cambio_texto($f->poblacion);
		$f->municipio = cambio_texto($f->municipio);
		$f->estado = cambio_texto($f->estado);
		$f->pais = cambio_texto($f->pais);
		$f->email = cambio_texto($f->email);
		$f->nvendedor = cambio_texto($f->nvendedor);
		$estado = $f->estadopropuesta;
		$pro = array();
		$pro[] = $f;
		$propuesta = str_replace("null",'""',json_encode($pro));
		
		//servicios grid
		$servicioscobro = "";
		$s = "SELECT servicio, cobro, precio FROM cconvenio_servicios WHERE idconvenio = $_GET[valor] and tipo = 'CONVENIO'";
		$r = mysql_query($s,$l) or die($s);
		$ser = array();
		while($f = mysql_fetch_object($r)){
			$f->servicio = cambio_texto($f->servicio);
			$ser[] = $f;
		}
		$servicioscobro = str_replace("null",'""',json_encode($ser));
		
		$servicioscobro2 = "";
		$s = "SELECT servicio, cobro, precio FROM cconvenio_servicios WHERE idconvenio = $_GET[valor] and tipo = 'CONSIGNACION'";
		$r = mysql_query($s,$l) or die($s);
		$ser2 = array();
		while($f = mysql_fetch_object($r)){
			$f->servicio = cambio_texto($f->servicio);
			$ser2[] = $f;
		}
		$servicioscobro2 = str_replace("null",'""',json_encode($ser2));
		
		//servicios combo
		$servicioscombo1 = "";
		$s = "SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SRCONVENIO'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$arre[] = $f;
		}
		$servicioscombo1 = str_replace("null",'""',json_encode($arre));
		
		$servicioscombo2 = "";
		$s = "SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SUCONVENIO'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$arre[] = $f;
		}
		$servicioscombo2 = str_replace("null",'""',json_encode($arre));
		
		
		$servicioscombo5 = "";
		$s = "SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SUCONSIGNACION2'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$arre[] = $f;
		}
		$servicioscombo5 = str_replace("null",'""',json_encode($arre));
		
		// combos de prepagadas
		$servicioscombo3 = "";
		$s = "SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SRCONSIGNACION'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$arre[] = $f;
		}
		$servicioscombo3 = str_replace("null",'""',json_encode($arre));
		
		$servicioscombo4 = "";
		$s = "SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SUCONSIGNACION1'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$arre[] = $f;
		}
		$servicioscombo4 = str_replace("null",'""',json_encode($arre));
		echo "[{propuesta:$propuesta, 
				serviciogrid1:$servicioscobro, 
				serviciogrid2:$servicioscobro2, 
				serviciocombo1:$servicioscombo1, 
				serviciocombo2:$servicioscombo2, 
				serviciocombo3:$servicioscombo3, 
				serviciocombo4:$servicioscombo4, 
				serviciocombo5:$servicioscombo5}]";
	}
	
	if($_GET[accion]==6){
		$s = "update generacionconvenio set estadoconvenio = 'ACTIVADO' where folio = $_GET[folio]";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE losclientes lc
		INNER JOIN generacionconvenio gc ON lc.id = gc.idcliente
		SET lc.convenio = 'SI'
		WHERE gc.folio = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE catalogocliente SET fechainicioconvenio = IF(ISNULL(fechainicioconvenio),CURRENT_DATE,fechainicioconvenio),
				fechafinconvenio = CONCAT(YEAR(CURRENT_DATE),'-12-31') WHERE id = (SELECT idcliente FROM generacionconvenio WHERE folio = $_GET[folio])";
		mysql_query($s,$l) or die($s);
		
		$s = "select idcliente, sucursal from generacionconvenio where folio = $_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$idcliente = $f->idcliente;
		$sucursal = $f->sucursal;
		
		$s = "UPDATE catalogocliente SET folioconvenio = $_GET[folio], sucursal='$sucursal', convenio='SI' WHERE id = $idcliente";
		mysql_query($s,$l) or die($s);
		
		$s = "CALL proc_RegistroClientes('convenio',".$idcliente.",".$_GET[folio].",0,0)";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT estado, montoautorizado FROM solicitudcredito WHERE cliente = ".$idcliente."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT IFNULL(MAX(id),0) AS id FROM reportecliente2 WHERE idcliente = ".$idcliente."";
		$r = mysql_query($s,$l) or die($s); $cc = mysql_fetch_object($r);
		
		if($cc->id==0){
			$s = "INSERT INTO reportecliente2 SET estadocredito = '".$f->estado."', 
			limitecredito = '".$f->montoautorizado."', idcliente = ".$idcliente."";
			mysql_query($s,$l) or die($s);
		}else{		
			$s = "UPDATE reportecliente2 SET estadocredito = '".$f->estado."', limitecredito = '".$f->montoautorizado."'
			WHERE id = ".$cc->id."";
			mysql_query($s,$l) or die($s);
		}
		
		echo "impreso";
	}
	
	if($_GET[accion]==7){
		$s = "update generacionconvenio set estadoconvenio = 'NO ACTIVADO' where folio = $_GET[folio]";
		mysql_query($s,$l) or die($s);
		
		echo "impreso";
	}
	
	if($_GET[accion]==8){
		if($_GET[valor]==1){
				$s = "SELECT * FROM cconvenio_configurador_preciokg 
						where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
						GROUP BY zona";
				$r = mysql_query($s,$l) or die($s);
				$sihay = (mysql_num_rows($r)>0)?1:0;
				
						if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_preciokg 
							where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                <tr>
                  <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                  <?
						if($sihay==1){
							$s = "SELECT cconvenio_configurador_preciokg.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_preciokg 
							where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                    <?=$zona?>
                    <br>
                    <?=$f->zoi?>
                    -
                    <?=$f->zof?></td>
                  <?
							$zona++;
						}
					?>
                </tr>
                <tr>
                  <td  class="formato_columnasg" height="16" >Precio KG</td>
                  <?
				  		if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_preciokg 
							where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" ><input type="text" readonly class="estilo_cajadesseleccion"
                  style="width:55; text-align:right" name="caja<?=$zona?>" value="<?=$f->valor?>"
                  onDblClick="seleccionar('caja<?=$zona?>')"></td>
                  <?	
				  		$zona++;
						}
					?>
                </tr>
              </table>
			<?
		}
		
		if($_GET[valor]==2){
			$s = "SELECT * FROM cconvenio_configurador_caja 
					where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
					GROUP BY zona";
					
					//die($s);
			$r = mysql_query($s,$l) or die($s);
			$sihay = (mysql_num_rows($r)>0)?1:0;
			
						if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_caja 
							where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+265?>" id="tablaconveniopreciocaja">
                <tr>
                  <td height="16" width="25"  class="formato_columnasg">
                  	
                    </td>
                  <td height="16" width="80"  class="formato_columnasg">Descripcion</td>
                  <?
						if($sihay==1){
							$s = "SELECT cconvenio_configurador_caja.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_caja 
							where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                    <?=$zona?>
                    <br>
                    <?=$f->zoi?> - <?=$f->zof?></td>
                  <?
							$zona++;
						}
					?>
				  <td height="16" width="80"  class="formato_columnasg" align="right">Peso Limite</td>
				  <td height="16" width="80"  class="formato_columnasg" align="right">Precio Excedente</td>
                </tr>
                <?
					$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONVENIO' and idconvenio = $_GET[idconvenio] GROUP BY descripcion";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_array($r)){
				?>
                <tr>
                	<td>&nbsp;</td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='<?=$f[descripcion]?>'  
					  onDblClick='seleccionar(obtenerIndice(this,"descripcion"),"zona1")'></td>
                    <? 
					$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONVENIO' and idconvenio = $_GET[idconvenio] and descripcion = '$f[descripcion]' order by zona";
					$rx = mysql_query($s,$l) or die($s);
					while($fx = mysql_fetch_object($rx)){
					?>
                    <td>
                      <input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:55; text-align:right' name='zona<?=$fx->zona?>[]' id='zona<?=$fx->zona?>' value='<?=$fx->precio?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"zona<?=$fx->zona?>"),"zona<?=$fx->zona?>")'>
                    </td>
                    <? }?>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='pesolimite[]' id='pesolimite' value='<?=$f["pesolimite"]?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"pesolimite"),"zona1")'></td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion' 
					  style='width:80; text-align:right' name='precioexcedente[]' id='precioexcedente' value='<?=$f["preciokgexcedente"]?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"precioexcedente"),"zona1")'></td>
                </tr>
                <?
					}
				?>
              </table>
		<?
		}
		
		if($_GET[valor]==3){
				$s = "SELECT * FROM cconvenio_configurador_preciokg 
						where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
						GROUP BY zona";
				$r = mysql_query($s,$l) or die($s);
				$sihay = (mysql_num_rows($r)>0)?1:0;
				
						if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_preciokg 
							where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                <tr>
                  <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                  <?
						if($sihay==1){
							$s = "SELECT cconvenio_configurador_preciokg.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_preciokg 
							where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                    <?=$zona?>
                    <br>
                    <?=$f->zoi?>
                    -
                    <?=$f->zof?></td>
                  <?
							$zona++;
						}
					?>
                </tr>
                <tr>
                  <td  class="formato_columnasg" height="16" >Precio KG</td>
                  <?
				  		if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_preciokg 
							where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" ><input type="text" readonly class="estilo_cajadesseleccion"
                  style="width:55; text-align:right" name="caja<?=$zona?>" value="<?=$f->valor?>"
                  onDblClick="seleccionar('caja<?=$zona?>')"></td>
                  <?	
				  		$zona++;
						}
					?>
                </tr>
              </table>
			<?
		}
		
		if($_GET[valor]==4){
			$s = "SELECT * FROM cconvenio_configurador_caja 
					where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
					GROUP BY zona";
			$r = mysql_query($s,$l) or die($s);
			$sihay = (mysql_num_rows($r)>0)?1:0;
			
						if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_caja 
							where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+265?>" id="tablaconveniopreciocaja">
                <tr>
                  <td height="16" width="25"  class="formato_columnasg">
                  	
                    </td>
                  <td height="16" width="80"  class="formato_columnasg">Descripcion</td>
                  <?
						if($sihay==1){
							$s = "SELECT cconvenio_configurador_caja.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_caja 
							where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                    <?=$zona?>
                    <br>
                    <?=$f->zoi?> - <?=$f->zof?></td>
                  <?
							$zona++;
						}
					?>
				  <td height="16" width="80"  class="formato_columnasg" align="right">Peso Limite</td>
				  <td height="16" width="80"  class="formato_columnasg" align="right">Precio Excedente</td>
                </tr>
                <?
					$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[idconvenio] GROUP BY descripcion";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_array($r)){
				?>
                <tr>
                	<td>&nbsp;</td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='<?=$f[descripcion]?>'  
					  onDblClick='seleccionar(obtenerIndice(this,"descripcion"),"zona1")'></td>
                    <? 
					$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[idconvenio] and descripcion = '$f[descripcion]' order by zona";
					$rx = mysql_query($s,$l) or die($s);
					while($fx = mysql_fetch_object($rx)){
					?>
                    <td>
                      <input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:55; text-align:right' name='zona<?=$fx->zona?>[]' id='zona<?=$fx->zona?>' value='<?=$fx->precio?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"zona<?=$fx->zona?>"),"zona<?=$fx->zona?>")'>
                    </td>
                    <? }?>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='pesolimite[]' id='pesolimite' value='<?=$f["pesolimite"]?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"pesolimite"),"zona1")'></td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion' 
					  style='width:80; text-align:right' name='precioexcedente[]' id='precioexcedente' value='<?=$f["preciokgexcedente"]?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"precioexcedente"),"zona1")'></td>
                </tr>
                <?
					}
				?>
              </table>
		<?
		}
	}
	
	if($_GET[accion]==9){
		$s = "select CONCAT(YEAR(ADDDATE(vigencia, INTERVAL 1 YEAR)), '-12-31') as fechavigencia 
				from generacionconvenio
				where folio = $_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$fecha = $f->fechavigencia;
		$fechaarre = split("-",$fecha);
		$fechashow = "$fechaarre[2]-$fechaarre[1]-$fechaarre[0]";
		$s = "update generacionconvenio set vigencia = '$fecha', fecharenovacion=CURRENT_DATE, estadoconvenio = 'AUTORIZADO'
				where folio = $_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "UPDATE catalogocliente SET fechafinconvenio = '$fecha' 
		WHERE id = (SELECT idcliente FROM generacionconvenio WHERE folio = $_GET[folio])";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reportecliente2(convenio,fechamodificacion,fechavencimiento,
		estadocredito,limitecredito,tipoconvenio,pesomaximo,preciosobrepeso,idcliente)
		SELECT ".$_GET[folio].", gc.fecharenovacion, gc.vigencia,c.estado,
		IFNULL(c.montoautorizado,0) AS limitecredito,
		CONCAT(IF(gc.descuentosobreflete=1 OR gc.consignaciondescuento=1,'DESCUENTO,', ''), 
		IF(gc.precioporkg=1 OR gc.consignacionkg=1,'KILOGRAMO,',''), 
		IF(gc.precioporcaja=1 OR gc.consignacioncaja=1,'PAQUETE,','')) AS tipo, 
		IFNULL(gc.limitekg,0) AS pesomaximo,IFNULL(gc.preciokgexcedente,0) AS preciosobrepeso,gc.idcliente
		FROM generacionconvenio gc
		LEFT JOIN solicitudcredito c ON gc.folio = c.folioconvenio
		WHERE gc.folio = ".$_GET[folio]."";
		mysql_query($s,$l) or die($s);
		
		echo "actualizo,$fechashow";
	}
	
	if($_GET[accion]==10){
		$s = "update generacionconvenio set estadoconvenio = 'CANCELADO' where folio = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE losclientes lc
		INNER JOIN generacionconvenio gc ON lc.id = gc.idcliente
		SET lc.convenio = 'NO'
		WHERE gc.folio = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);
		
		$s = "CALL proc_RegistroClientes('cancelacionConvenio',0,".$_GET[folio].",0,0)";
		mysql_query($s,$l) or die($s);
		
		echo "cancelado";
	}
	
	if($_GET[accion]==11){
		$s = "SELECT IFNULL((SELECT MAX(folio)+1 FROM generacionconvenio),1) AS folio,
		DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fecha,
		CONCAT('31-12-',YEAR(CURRENT_DATE)) AS fechalimite";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "({'folio':'$f->folio','fecha':'$f->fecha','fechalimite':'$f->fechalimite'})";
	}
	if($_GET[accion]==12){
		
		$s = "select * from generacionconvenio where estadoconvenio = 'ACTIVADO' and idcliente = '$_GET[cliente]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			echo "SI TIENE,$f->folio";
			
		}else
			echo "NO TIENE";
	}
	
?>