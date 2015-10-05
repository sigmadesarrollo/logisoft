<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once("../Conectar.php");
	require_once("../clases/ValidaConvenio.php");
	$l = Conectarse("webpmm");
	
	$_GET[idsucorigen] = $_SESSION[IDSUCURSAL];
	
	//solicitar datos evaluacion
	if($_GET[accion] == 1){
		$s = "select em.cantidadbolsa, '' as estado, cd.descripcion as ndestino, em.destino as iddestino, 
		cs.descripcion as nsucursal, cs.id as idsucursal, cs.prefijo, em.recoleccion, cs.frontera,
		em.bolsaempaque, em.emplaye, em.totalbolsaempaque, em.totalemplaye, cd.poblacion as npobdestino,
		cs.iva, cpo.descripcion as npobdes, cd.restringirporcobrar,  cd.subdestinos,
		cd.todasemana, cd.lunes, cd.martes, cd.miercoles, cd.jueves, cd.viernes, cd.sabado,
		weekday(current_date) as diasemana, cd.restringiread
		from evaluacionmercancia as em
		inner join catalogodestino as cd on em.destino = cd.id
		inner join catalogopoblacion as cpo on cd.poblacion = cpo.id
		inner join catalogosucursal as cs on cd.sucursal = cs.id
		where folio = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$cant0 = mysql_num_rows($r);
			$f = mysql_fetch_object($r);
			
			if(trim($f->ndestino)!=trim($f->nsucursal)){
				$bloquearocurrenead = "<bloquearocurre>0</bloquearocurre>";
			}else{
				$bloquearocurrenead = "<bloquearocurre>0</bloquearocurre>";
			}
			
			$totalhorasincre = 0;
			$arredias = array("lunes","martes","miercoles","jueves","viernes","sabado");
			
			$rest2= "<restr2>0</restr2>";
			if($f->todasemana==0){//
				$valordias[0]	= ($f->lunes==1)?0:1;
				$valordias[1] 	= ($f->martes==1)?0:1;
				$valordias[2] 	= ($f->miercoles==1)?0:1;
				$valordias[3] 	= ($f->jueves==1)?0:1;
				$valordias[4] 	= ($f->viernes==1)?0:1;
				$valordias[5] 	= ($f->sabado==1)?0:1;
				
				$totaldias = 0;
				for($i=0; $i<6; $i++){
					
					$valarre 	= $f->diasemana+$i;
					
					$totaldias	+= $valordias[(($valarre>5)?($valarre-6):($valarre))];
					
					if($valordias[(($valarre>5)?($valarre-6):($valarre))]==0)
						break;
				}
				
				$totalhorasincre 	= 24*$totaldias;
				$ultvalor			= (($valarre>5)?($valarre-6):($valarre));
				$rest2= "<restr2>".$arredias[$ultvalor]."</restr2>";
			}
			
			$s 		= "select iva from catalogosucursal where id = $_GET[idsucorigen]";
			$rh 	= mysql_query($s,$l) or die($s);
			$fh 	= mysql_fetch_object($rh);
			$iva	= ($fh->iva=="")?0:$fh->iva;
			
			$pt_ead=0;
			$pt_recoleccion=0;
			
			
			$s = "SELECT CONCAT(cuentac,numerodesde,letra) AS newfolio FROM (
				SELECT 
					(SELECT idsucursal FROM catalogosucursal WHERE id = $_GET[idsucorigen]) AS cuentac,
					
				LPAD(
					IFNULL(
						IF(SUBSTRING(MAX(id),4,9)+1=1000000000,1,SUBSTRING(MAX(id),4,9)+1)
					,1)
				,9,'0') AS numerodesde,
				CHAR(ASCII(IFNULL(SUBSTRING(MAX(id),13,1),'A'))+IF(SUBSTRING(MAX(id),4,9)+1=1000000000,1,0)) AS letra
				FROM guiasventanilla WHERE SUBSTRING(id,1,3) = (SELECT idsucursal FROM catalogosucursal WHERE id = $_GET[idsucorigen])
			) AS t1";
			$rfg = mysql_query($s,$l) or die($s);
			$ffg = mysql_fetch_object($rfg);
			$newfolio = $ffg->newfolio;
			
			$s = "select costo, porcada from configuradorservicios where servicio = 6";
			$rcs = mysql_query($s,$l) or die($s);
			$fcs = mysql_fetch_object($rcs);
			$v_costo 	= ($fcs->costo=="")?"0":$fcs->costo;
			$v_porcada 	= ($fcs->porcada=="")?"0":$fcs->porcada;
			
			$s = "select cd.costorecoleccion, cd.subdestinos, cs.id as idsucursalrecoleccion
			from recoleccion r
			LEFT join catalogodestino cd on r.origen = cd.id
			left join catalogosucursal cs on cd.sucursal = cs.id
			left join recolecciondetallefoliorecoleccion rdf on r.folio = rdf.recoleccion
			where rdf.foliosrecolecciones = '$f->recoleccion'";
			$rb = mysql_query($s,$l) or die($s);
			$fb = mysql_fetch_object($rb);
			$pt_recoleccion			= $fb->costorecoleccion;
			$idsucursalrecoleccion	= $fb->idsucursalrecoleccion;
			$origensubdestinos		= $fb->subdestinos;
			
			$s = "select cdd.costoead, 
			restringiread, restringirrecoleccion, restringirporcobrar,
			deshabilitarconvenio
			from catalogodestino as cdd where cdd.id = $f->iddestino";
			$rb = mysql_query($s,$l) or die($s);
			$fb = mysql_fetch_object($rb);
			$pt_ead=$fb->costoead;
			
			$pv_restead				= $fb->restringiread;
			$pv_restrrecoleccion	= $fb->restringirrecoleccion;
			$pv_restporcobrar		= $fb->restringirporcobrar;
			$pv_desconvenio			= $fb->deshabilitarconvenio;		
			
			$s = "select * from configuradorgeneral";
			$rcg = mysql_query($s,$l) or die($s);
			$fcg = mysql_fetch_object($rcg);
			$por_combustible 	= ($fcg->cargocombustible=="")?0:$fcg->cargocombustible;
			$max_des 			= ($fcg->descuento=="")?0:$fcg->descuento;
			$iva_retenido		= ($fcg->ivaretenido=="")?0:$fcg->ivaretenido;
			$pagominimocheques	= ($fcg->pagominimocheques=="")?0:$fcg->pagominimocheques;
			$pesominimodesc		= ($fcg->pesominaplicardescuento=="")?0:$fcg->pesominaplicardescuento;
			
			$s = "select catalogotiempodeentregas.*, hour(current_time) as tiempo 
			from catalogotiempodeentregas where idorigen = $_GET[idsucorigen] and iddestino = $f->idsucursal";
			$rm = mysql_query($s,$l) or die($s);
			$fm = mysql_fetch_object($rm);
			
			$horasparamensaje = array(0,12,14,16,8,9,10,11,13,15,17);
			
			if($fm->incrementartiempo==1){
				$s = "select if(current_time>'".$horasparamensaje[$fm->siocurre]."',1,0) as validacion";
				$rpr = mysql_query($s,$l) or die($s);
				$fpr = mysql_fetch_object($rpr);
				if($fpr->validacion=="1"){
					$restrinccion = "<restrincciones>Si documenta despues de las ".$horasparamensaje[$fm->siocurre]." hrs se incrementaran ".$fm->aincrementar. "hrs</restrincciones>";	
					$mashoras = $fm->aincrementar;
				}else{
					$restrinccion = "<restrincciones>0</restrincciones>";
					$mashoras = 0;
				}
			}else{
				$restrinccion = "<restrincciones>0</restrincciones>";
				$mashoras = 0;
			}
			
			$s = "select max(avisocel) as avisocel,
			max(acuserecibo) as acuserecibo,
			max(cod) as cod,
			max(bolemp) as bolemp,
			max(emp) as emp
			from(
			select if(servicio=3, costo, 0) as avisocel,
			if(servicio=4, costo, 0) as acuserecibo, 
			if(servicio=5, costo, 0) as cod,
			if(servicio=1, costo, 0) as bolemp,
			if(servicio=2, costo, 0) as emp
			from configuradorservicios
			) as t1";
			$rz = mysql_query($s,$l) or die($s);
			$fz = mysql_fetch_object($rz);
			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
				<encontro>$cant0</encontro>
				<folioevaluacion>$_GET[folio]</folioevaluacion>
				<nuevofolioguia>$newfolio</nuevofolioguia>
				<estado>".strtoupper(cambio_texto($f->estado))."</estado>
				<restringirporcobrar>".strtoupper(cambio_texto($f->restringirporcobrar))."</restringirporcobrar>
				<ndestino>".strtoupper(cambio_texto($f->ndestino))." - ".strtoupper(cambio_texto($f->prefijo))."</ndestino>
				<iddestino>$f->iddestino</iddestino>
				<nsucursal>".strtoupper(cambio_texto($f->nsucursal))."</nsucursal>
				<frontera>".$f->frontera."</frontera>
				<subdestinos>".$f->subdestinos."</subdestinos>
				<idsucursalrecoleccion>".cambio_texto($idsucursalrecoleccion)."</idsucursalrecoleccion>
				<origensubdestinos>".cambio_texto($origensubdestinos)."</origensubdestinos>
				
				<npobdes>".strtoupper(cambio_texto($f->npobdes))."</npobdes>
				<idsucursal>$f->idsucursal</idsucursal>
				<npobdestino>".strtoupper(cambio_texto($f->npobdestino))."</npobdestino>
				<bolsaempaque>".cambio_texto($f->bolsaempaque)."</bolsaempaque>
				<cantidadbolsa>".cambio_texto($f->cantidadbolsa)."</cantidadbolsa>
				<semplaye>".cambio_texto($fz->emp)."</semplaye>
				<sbolsaempaque>".cambio_texto($fz->bolemp)."</sbolsaempaque>
				<emplaye>".cambio_texto($f->emplaye)."</emplaye>
				<totalbolsaempaque>".cambio_texto($f->totalbolsaempaque)."</totalbolsaempaque>
				<totalemplaye>".cambio_texto($f->totalemplaye)."</totalemplaye>
				<avisocelular>".cambio_texto($fz->avisocel)."</avisocelular>
				<acuserecibo>".cambio_texto($fz->acuserecibo)."</acuserecibo>
				<recoleccion>".cambio_texto($f->recoleccion)."</recoleccion>
				<cod>".cambio_texto($fz->cod)."</cod>
				<ocu>".cambio_texto($fm->tentrega+$mashoras)."</ocu>
				<ead>".cambio_texto($fm->tentregaad+$mashoras+$totalhorasincre)."</ead>
				<restringiread>".cambio_texto($f->restringiread)."</restringiread>
				<pt_ead>".cambio_texto($pt_ead)."</pt_ead>
				<pt_recoleccion>".cambio_texto($pt_recoleccion)."</pt_recoleccion>
				<pt_iva>".cambio_texto($iva)."</pt_iva>
				<pt_ivaretenido>".cambio_texto($iva_retenido)."</pt_ivaretenido>
				<pfp_pagominimocheques>".cambio_texto($pagominimocheques)."</pfp_pagominimocheques>
				<pesominimodesc>".cambio_texto($pesominimodesc)."</pesominimodesc>
				<por_combustible>".cambio_texto($por_combustible)."</por_combustible>
				<max_des>".cambio_texto($max_des)."</max_des>
				<por_cada>".cambio_texto($v_porcada)."</por_cada>
				<scosto>".cambio_texto($v_costo)."</scosto>
				<desead>$pv_restead</desead>			
				<desrrecoleccion>$pv_restrrecoleccion</desrrecoleccion>
				<desporcobrar>$pv_restporcobrar</desporcobrar>
				<desconvenio>$pv_desconvenio</desconvenio>
				$bloquearocurrenead
				$restrinccion
				$rest2
			";
			
			$s = "delete from guia_temporaldetalle where idusuario = $_SESSION[IDUSUARIO]";
			mysql_query($s,$l) or die($s);
			
			$vc = new validaConvenio('','','','');
			
			$s = "SELECT NULL, em.cantidad, cd.descripcion, em.contenido, em.pesototal, em.peso, em.largo, em.ancho, em.alto, em.volumen
			FROM evaluacionmercanciadetalle AS em
			INNER JOIN catalogodescripcion AS cd ON em.descripcion = cd.id
			WHERE em.evaluacion = '$_GET[folio]'";
			
			$rn = mysql_query($s,$l) or die($s);
			$tpesokg = 0;
			$tpesovo = 0;
			$tenvase = 0;
			$totalimporte = 0;
			$tproductos = 0;
			while($fn = mysql_fetch_object($rn)){
				
				$res = $vc->ObtenerFlete(0, $_GET[idsucorigen], $f->idsucursal, "$fn->descripcion", 
												(($fn->volumen>$fn->pesototal)?$fn->volumen:$fn->pesototal), $fn->cantidad);
				
				$res = split(",",$res);
				$s = "insert into guia_temporaldetalle
				select null, $fn->cantidad, '$fn->descripcion', '$fn->contenido', 
				'$fn->peso', '$fn->alto', '$fn->ancho', '$fn->largo',
				$fn->pesototal, $fn->volumen, '".$res[0]."', '".$res[1]."',0,$_SESSION[IDUSUARIO]";
				
				mysql_query($s,$l) or die("$s<br>".mysql_error($l));
			}
			
			$s = "select * 
			from guia_temporaldetalle 
			where idusuario = $_SESSION[IDUSUARIO]";
			$rx = mysql_query($s,$l) or die($s);
			
			if(mysql_num_rows($rx)>0){
				$cante = mysql_num_rows($rx);
				while($fx = mysql_fetch_object($rx)){
					$xml .= "<idmercancia>$fx->id</idmercancia>
					";
					$xml .= "<cantidad>$fx->cantidad</cantidad>
					";
					$xml .= "<descripcion>".strtoupper(cambio_texto($fx->descripcion))."</descripcion>
					";
					$xml .= "<contenido>".strtoupper(cambio_texto($fx->contenido))."</contenido>
					";
					$xml .= "<peso>".round($fx->peso,2)."</peso>
					";
					$xml .= "<volumen>".round($fx->volumen,2)."</volumen>
					";
					$xml .= "<importe>0</importe>
					";
					if($fx->descripcion=="ENVASE(S)"){
						$tenvase += $fb->costo;
					}else{
						$tproductos++;
						$tpesokg += round($fx->peso,2);
						$tpesovo += round($fx->volumen,2);
					}
				}
				
				if($tproductos>0){
					//echo "entro en normales totales";
					$s = "select costo from configuraciondetalles where 
					(select IFNULL(SUM(distancia),0) AS distancia from catalogodistancias where 
					(idorigen=$_GET[idsucorigen] and iddestino=$f->idsucursal) or 
					(iddestino=$_GET[idsucorigen] and idorigen=$f->idsucursal)) between zoi and zof
					and ".(($tpesokg>$tpesovo)?$tpesokg:$tpesovo)." between kgi and kgf";
					//echo "<br>$s<br>";
					$rb = mysql_query($s,$l) or die($s);
					if(mysql_num_rows($rb)>0){
						$fb = mysql_fetch_object($rb);
						if($fb->costo<10)
							$totalimporte = round($fb->costo*(($tpesokg>$tpesovo)?$tpesokg:$tpesovo),2);
						else
							$totalimporte = round($fb->costo,2);
					}
				}
				
			}else{
				$totalimporte = 0;
				$xml .= "<cantidad>$fx->cantidad</cantidad>
				<descripcion>0</descripcion>
				<contenido>0</contenido>
				<peso>0</peso>
				<volumen>0</volumen>
				<importe>0</importe>
				";
			}
					
			
			$xml .= "<valor_totalimporte>".cambio_texto($totalimporte+$tenvase)."</valor_totalimporte>
			<tipototales>1</tipototales>
			<encontroevaluacion>$cante</encontroevaluacion>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
	}
	
	//solicitar clientes
	if($_GET[accion] == 2){
		$s = "select concat_ws(' ', cc.nombre, cc.paterno, cc.materno) as ncliente, cc.rfc, cc.celular,
		cc.personamoral
		from catalogocliente as cc where id = $_GET[idcliente]";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$cant0 = mysql_num_rows($r);
			$f = mysql_fetch_object($r);
			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
				<encontro>$cant0</encontro>
				<ncliente>".strtoupper(cambio_texto($f->ncliente))."</ncliente>
				<personamoral>".strtoupper(cambio_texto($f->personamoral))."</personamoral>
				<rfc>".strtoupper(cambio_texto($f->rfc))."</rfc>
				<celular>".cambio_texto($f->celular)."</celular>
			";
			
			$iddir 	= "";
			$and	= " and d.id not in";
			$s = "CREATE TEMPORARY TABLE `direccion_tmp` (  
			 `idx` int(11) NOT NULL auto_increment, 
             `id` double NOT NULL,                  
             `calle` varchar(150) NOT NULL default '',                 
             `numero` varchar(10) NOT NULL default '',                
             `cp` int(5) NOT NULL default '0',                         
             `colonia` varchar(150) NOT NULL default '',               
             `poblacion` varchar(50) NOT NULL default '',                             
             `telefono` varchar(20) NOT NULL default '',                 
             PRIMARY KEY  (`idx`)                                       
           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
			mysql_query($s,$l) or die($s);
			
			if($_GET[poblacion]!=""){
				$s  = "select id from direccion where origen='cl' and codigo = $_GET[idcliente] and facturacion = 'NO' and poblacion = '$_GET[poblacion]'";
				//echo "<br>$s<br>";
				$rx = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rx)>0){
					$fx 	= mysql_fetch_object($rx);
					$iddir	= "$fx->id";
					
					$s = "insert into direccion_tmp
					select null, d.id, d.calle, d.numero, d.cp, d.colonia, d.poblacion, d.telefono
					from direccion as d
					where origen='cl' and codigo = $_GET[idcliente] and poblacion = '$_GET[poblacion]' and id = $fx->id";
					//echo "<br>$s<br>";
					mysql_query($s,$l) or die($s);
				}
			}
			
			if($_GET[poblacion]!=""){
				$s  = "select id from direccion where origen='cl' and codigo = $_GET[idcliente] and facturacion = 'SI' and poblacion = '$_GET[poblacion]'";
				//echo "<br>$s<br>";
				$rx = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rx)>0){
					$fx 	= mysql_fetch_object($rx);
					$iddir	.= ($iddir=="")?"$fx->id":",$fx->id";
					
					$s = "insert into direccion_tmp
					select null, d.id, d.calle, d.numero, d.cp, d.colonia, d.poblacion, d.telefono
					from direccion as d
					where origen='cl' and codigo = $_GET[idcliente] and facturacion = 'SI' and poblacion = '$_GET[poblacion]' and id = $fx->id";
					//echo "<br>$s<br>";
					mysql_query($s,$l) or die($s);
				}
			}
			$and .= "($iddir)";	
			if($iddir!=""){
				$s = "insert into direccion_tmp
				select null, d.id, d.calle, d.numero, d.cp, d.colonia, d.poblacion, d.telefono
				from direccion as d
				where origen='cl' and codigo = $_GET[idcliente] $and";
				//echo "<br>$s<br>";
				mysql_query($s,$l) or die($s);
			}
			
			
			$s = "";
			
			$s = "select d.id, d.calle, d.numero, d.cp, d.colonia, d.poblacion, d.telefono
			from ".(($iddir=="")?"direccion":"direccion_tmp")." as d
			".(($iddir=="")?" where origen = 'cl' and codigo = $_GET[idcliente] ":" order by idx")."";
			//echo "<br>$s<br>";
			$rx = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rx)>0){
				$cant = mysql_num_rows($rx);
				while($fx = mysql_fetch_object($rx)){
					$xml .= "<idcalle>".strtoupper(cambio_texto($fx->id))."</idcalle>
					";
					$xml .= "<calle>".strtoupper(cambio_texto($fx->calle))."</calle>
					";
					$xml .= "<numero>".cambio_texto($fx->numero)."</numero>
					";
					$xml .= "<cp>".cambio_texto($fx->cp)."</cp>
					";
					$xml .= "<colonia>".strtoupper(cambio_texto($fx->colonia))."</colonia>
					";
					$xml .= "<poblacion>".strtoupper(cambio_texto($fx->poblacion))."</poblacion>
					";
					$xml .= "<telefono>".cambio_texto($fx->telefono)."</telefono>
					";
				}
				$xml .= "<encontrodirecciones>$cant</encontrodirecciones>
				</datos>
				</xml>";	
			}else{
				$xml .= "<encontrodirecciones>0</encontrodirecciones>
				</datos>
				</xml>";
			}
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
	}
	
	//solicitar sucursales
	if($_GET[accion] == 3){
		$s = "select cs.id, cs.descripcion, cd.poblacion 
		from catalogosucursal as cs 
		inner join catalogodestino as cd on cs.id = cd.sucursal
		where cd.id = $_GET[iddestino]";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$encontro = mysql_num_rows($r);
			$f = mysql_fetch_object($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontro>$encontro</encontro>
				<iddestino>".cambio_texto($f->id)."</iddestino>
				<descripcion>".cambio_texto($f->descripcion)."</descripcion>
				<poblacion>".cambio_texto($f->poblacion)."</poblacion>
				</datos>
				</xml>";
		}else{
						$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontro>0</encontro>
				</datos>
				</xml>";

		}
	}
	
	//guardar guias
	if($_GET[accion] == 4){
		$lfecha = split("/",$_GET[fecha]);
		$lfecha = $lfecha[2]."/".$lfecha[1]."/".$lfecha[0];
		
		$s = "update evaluacionmercancia set estado = 'ENGUIA' where folio = $_GET[evaluacion]";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "SELECT sucursal FROM evaluacionmercancia where folio = $_GET[evaluacion]";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		$f = mysql_fetch_object($r);
		$_GET[idsucursalorigen] = $f->sucursal;
		
		$s = "SELECT CONCAT(cuentac,numerodesde,letra) AS newfolio FROM (
				SELECT 
					(SELECT idsucursal FROM catalogosucursal WHERE id = $_GET[idsucursalorigen]) AS cuentac,
					
				LPAD(
					IFNULL(
						IF(SUBSTRING(MAX(id),4,9)+1=1000000000,1,SUBSTRING(MAX(id),4,9)+1)
					,1)
				,9,'0') AS numerodesde,
				CHAR(ASCII(IFNULL(SUBSTRING(MAX(id),13,1),'A'))+IF(SUBSTRING(MAX(id),4,9)+1=1000000000,1,0)) AS letra
				FROM guiasventanilla WHERE SUBSTRING(id,1,3) = (SELECT idsucursal FROM catalogosucursal WHERE id = $_GET[idsucursalorigen])
			) AS t1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$newfolio = $f->newfolio;
		
		if($_GET[idsucursalorigen]==$_GET[idsucursaldestino]){
			$estado = "ALMACEN DESTINO";
		}else{
			$estado = "ALMACEN ORIGEN";
		}
		
		$s = "insert into guiasventanilla set id='$newfolio', estado='$estado', 
		evaluacion='$_GET[evaluacion]', fecha='$lfecha', tipoflete='$_GET[tipoflete]', 
		ocurre='$_GET[ocurre]', idsucursalorigen='$_GET[idsucursalorigen]', iddestino='$_GET[iddestino]', 
		idsucursaldestino='$_GET[idsucursaldestino]', condicionpago='$_GET[condicionpago]', idremitente='$_GET[idremitente]',
		iddireccionremitente='$_GET[iddireccionremitente]', iddestinatario='$_GET[iddestinatario]',
		iddirecciondestinatario='$_GET[iddirecciondestinatario]', entregaocurre='$_GET[entregaocurre]', entregaead='$_GET[entregaead]',
		restrinccion='$_GET[restrinccion]', totalpaquetes='$_GET[totalpaquetes]', totalpeso='$_GET[totalpeso]', 
		totalvolumen='$_GET[totalvolumen]', emplaye='$_GET[emplaye]', bolsaempaque='$_GET[bolsaempaque]', 
		totalbolsaempaque='$_GET[totalbolsaempaque]', avisocelular='$_GET[avisocelular]', celular='$_GET[celular]', 
		valordeclarado='$_GET[valordeclarado]', acuserecibo='$_GET[acuserecibo]', cod='$_GET[cod]', recoleccion='$_GET[recoleccion]', 
		observaciones='$_GET[observaciones]', tflete='$_GET[tflete]', tdescuento='$_GET[tdescuento]', 
		ttotaldescuento='$_GET[ttotaldescuento]', tcostoead='$_GET[tcostoead]', trecoleccion='$_GET[trecoleccion]', 
		tseguro='$_GET[tseguro]', totros='$_GET[totros]', texcedente='$_GET[texcedente]', tcombustible='$_GET[tcombustible]', 
		subtotal='$_GET[subtotal]', tiva='$_GET[tiva]', ivaretenido='$_GET[ivaretenido]', total='$_GET[total]', 
		efectivo='$_GET[efectivo]', cheque='$_GET[cheque]', banco='$_GET[banco]', ncheque='$_GET[ncheque]', 
		clienteconvenio='$_GET[clienteconvenio]', sucursalconvenio='$_GET[sucursalconvenio]', idvendedorconvenio='$_GET[idvendedorconvenio]', 
		nvendedorconvenio='$_GET[nvendedorconvenio]', convenioaplicado='$_GET[convenioaplicado]',
		tarjeta='$_GET[tarjeta]', trasferencia='$_GET[trasferencia]', usuario='".$_SESSION[NOMBREUSUARIO]."',
		ubicacion = $_SESSION[IDSUCURSAL],
		sector=ifNull((SELECT cs.id
			FROM catalogosector AS cs
			INNER JOIN catalogosectordetalle AS csd ON cs.id = csd.idsector
			INNER JOIN direccion AS d ON csd.cp = d.cp
			WHERE d.origen = 'cl' AND d.id = $_GET[iddirecciondestinatario]
			LIMIT 1),0),
		idusuario='".$_SESSION[IDUSUARIO]."', fecha_registro=current_date, hora_registro=current_time";
		$r = @mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>".str_replace("''","null",$s)."</consulta>
				</datos>
				</xml>");
		
		if($_GET[tipoflete]=="0" && $_GET[condicionpago]=="0"){
		$s = "INSERT INTO formapago SET guia='$newfolio',procedencia='G',tipo='V',total='$_GET[total]',efectivo='$_GET[efectivo]',
		tarjeta='$_GET[tarjeta]',transferencia='$_GET[trasferencia]',cheque='$_GET[cheque]',ncheque='$_GET[ncheque]',banco='$_GET[banco]',notacredito='$_GET[nc]',
		nnotacredito='$_GET[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',usuario='$_SESSION[IDUSUARIO]',fecha=current_date";
		@mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		}
		
		$s = "INSERT INTO guia_rastreo SET numerorastreo = (SELECT CONCAT(DATE_FORMAT(CURRENT_TIMESTAMP(), '%s%H%i'),
		DATE_FORMAT(CURRENT_TIMESTAMP(), '%y%m%d'),CHAR(FLOOR(RAND()*25)+65),FLOOR(RAND()*9),
		(SELECT idsucursal FROM catalogosucursal WHERE id = '$_GET[idsucursalorigen]')) AS refe), 
		noguia = '$newfolio', tipoguia = 'V', origen = '$_GET[idsucursalorigen]', destino = '$_GET[idsucursaldestino]'";
		@mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		//registrar el abono
		$s = "INSERT INTO pagoguias SET guia = '$newfolio', tipo='NORMAL', total='$_GET[total]', 
		fechacreo = CURRENT_DATE, usuariocreo = $_SESSION[IDUSUARIO], sucursalcreo = $_SESSION[IDSUCURSAL], 
		cliente = '".(($_GET[tipoflete]==0)?"$_GET[idremitente]":"$_GET[iddestinatario]")."',
		sucursalacobrar = '".(($_GET[tipoflete]==0)?"$_GET[idsucursalorigen]":"$_GET[idsucursaldestino]")."',
		".(($_GET[condicionpago]==0)?"credito='NO'":"credito='SI'")."
		".(($_GET[condicionpago]==0 && $_GET[tipoflete]==0)?", pagado='S', fechapago = CURRENT_DATE, usuariocobro = $_SESSION[IDUSUARIO], sucursalcobro = $_SESSION[IDSUCURSAL]":"");
		$r = @mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "INSERT INTO guiaventanilla_detalle 
		SELECT '$newfolio', cantidad, descripcion, contenido, pesou, alto, ancho, largo, peso, volumen, importe, excedente, kgexcedente, idusuario 
		FROM guia_temporaldetalle WHERE idusuario = $_SESSION[IDUSUARIO]";
		$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$cantidad = 1;
		$s = "select * from guiaventanilla_detalle
		where idguia = '$newfolio'";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$pesousado = ($f->peso>$f->volumen)?$f->peso:$f->volumen;
			for($i=0;$i<$f->cantidad;$i++){
				$s = "INSERT INTO guiaventanilla_unidades SET idguia='$newfolio', descripcion='$f->descripcion', 
					  contenido='$f->contenido', peso=$pesousado/$f->cantidad, paquete=$cantidad, 
					  depaquetes=$_GET[totalpaquetes], ubicacion = $_SESSION[IDSUCURSAL],
					  codigobarras='".$newfolio.str_pad($cantidad,4,"0",STR_PAD_LEFT).str_pad($_GET[totalpaquetes],4,"0",STR_PAD_LEFT)."'";
				@mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
				$cantidad++;
			}
		}
		
		$s = "update seguimiento_guias set guia = '$newfolio' where guia = $_GET[evaluacion]";
		@mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
		<datos>
		<guardado>0</guardado>
		<consulta>$s</consulta>
		</datos>
		</xml>");
		
		$s = "INSERT INTO seguimiento_guias SET 
		guia='$newfolio', ubicacion='$_SESSION[IDSUCURSAL]', unidad='',estado='ALMACEN ORIGEN',
		fecha=CURRENT_DATE, hora=CURRENT_TIME,
		usuario=$_SESSION[IDUSUARIO]";
		@mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
		<datos>
		<guardado>0</guardado>
		<consulta>$s</consulta>
		</datos>
		</xml>");
		
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>1</guardado>
				<folioguia>$newfolio</folioguia>
				</datos>
				</xml>";
	}
	
	//solicitar guias
	if($_GET[accion] == 5){
		
		//obtener la unidad si es ke van en unidad
		$s = "select ifnull(unidad,'') as unidad from seguimiento_guias where guia = '$_GET[folio]' order by id desc limit 1";
		$r = mysql_query($s,$l) or die($s);
		$etiquetaunidad = "";
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			if($f->unidad!=""){
				$etiquetaunidad = "<unidadguia>".cambio_texto($f->unidad)."</unidadguia>";
			}
		}
		//verificar si 
		$s = "SELECT IF(COUNT(*)=1,CONCAT('SUSTITUIDA POR ',hcs.sustitucion),'') AS sustitucion
		FROM historial_cancelacionysustitucion AS hcs
		WHERE accion = 'SUSTITUCION REALIZADA' AND hcs.guia = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			if($f->sustitucion!=""){
				$etiquetasustitucion = "<sustituciondeguia>".cambio_texto($f->sustitucion)."</sustituciondeguia>";
			}
		}
		
		$s = "SELECT IF(COUNT(*)=1,CONCAT('SUSTITUCION DE ',hcs.guia),'') AS sustitucion
		FROM historial_cancelacionysustitucion AS hcs
		WHERE accion = 'SUSTITUCION REALIZADA' AND hcs.sustitucion = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			if($f->sustitucion!=""){
				$etiquetasustitucion = "<sustituciondeguia>".cambio_texto($f->sustitucion)."</sustituciondeguia>";
			}
		}
	
		
		$s = "select concat(if(dano=1,'CON DAOS',''), if(dano=1 and faltante=1,' Y CON FALTANTES', if(faltante=1, 'CON FALTANTES',''))) as recepcion
		from reportedanosfaltante where guia = '$_GET[folio]';";
		$r = mysql_query($s,$l) or die($s);
		$etiquetafaltante="";
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			if($f->recepcion!=""){
				$etiquetafaltante = "<danosfaltantes>RECIBIDA EN SUCURSAL ".cambio_texto($f->recepcion)."</danosfaltantes>";
			}
		}
		
		$s = "SELECT
		gv.id, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, gv.fechaentrega, gv.factura, 
		gv.tipoflete, gv.ocurre, gv.idsucursalorigen, gv.estado,
		concat(cd.descripcion,' - ',csd.prefijo) as ndestino, csd.descripcion as nsucdestino,
		gv.idsucursalorigen,gv.idsucursaldestino,
		gv.condicionpago, 
		
		gv.idremitente, 
		concat_ws(' ', ccr.nombre, ccr.paterno, ccr.materno) as rncliente, ccr.rfc as rrfc, ccr.celular as rcelular,
		dr.calle as rcalle, dr.numero as rnumero, dr.cp as rcp, dr.colonia as rcolonia, 
		dr.poblacion as rpoblacion, dr.telefono as rtelefono,
		 
		gv.iddestinatario,
		concat_ws(' ', ccd.nombre, ccd.paterno, ccd.materno) as dncliente, ccd.rfc as drfc, ccd.celular as dcelular,
		dd.calle as dcalle, dd.numero as dnumero, dd.cp as dcp, dd.colonia as dcolonia, 
		dd.poblacion as dpoblacion, dd.telefono as dtelefono,
		
		gv.entregaocurre, 
		gv.entregaead, gv.restrinccion, gv.totalpaquetes, gv.totalpeso, 
		gv.totalvolumen, gv.emplaye, gv.bolsaempaque, gv.totalbolsaempaque, 
		gv.avisocelular, gv.celular, gv.valordeclarado, gv.acuserecibo, 
		gv.cod, gv.recoleccion, gv.observaciones, gv.tflete, gv.tdescuento, 
		gv.ttotaldescuento, gv.tcostoead, gv.trecoleccion, gv.tseguro, gv.totros, 
		gv.texcedente, gv.tcombustible, gv.subtotal, gv.tiva, gv.ivaretenido, 
		gv.total, gv.efectivo, gv.cheque, gv.banco, gv.ncheque, gv.tarjeta, gv.trasferencia, 
		gv.usuario, gv.fecha_registro, gv.hora_registro, date_format(current_date, '%d/%m/%Y') as fechaactual,
		gv.recibio, gv.fechaentrega, gv.factura
		FROM guiasventanilla as gv
		inner join catalogosucursal as csd on gv.idsucursaldestino = csd.id
		inner join catalogodestino as cd on gv.iddestino = cd.id
		inner join catalogocliente as ccr on gv.idremitente = ccr.id
		left join direccion as dr on gv.iddireccionremitente = dr.id
		inner join catalogocliente as ccd on gv.iddestinatario = ccd.id
		left join direccion as dd on gv.iddirecciondestinatario = dd.id
		where gv.id= '$_GET[folio]'";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontrados>1</encontrados>
				<id>".cambio_texto($f->id)."</id>
				<fechaactual>".cambio_texto($f->fechaactual)."</fechaactual>
				<evaluacion>".cambio_texto($f->evaluacion)."</evaluacion>
				<fecha>".cambio_texto($f->fecha)."</fecha>
				<estado>".cambio_texto($f->estado)."</estado>
				$etiquetaunidad
				$etiquetafaltante
				
				$etiquetasustitucion
				<fechaentrega>".cambio_texto($f->fechaentrega)."</fechaentrega>
				<factura>".cambio_texto($f->factura)."</factura>
				<recibio>".cambio_texto($f->recibio)."</recibio>
				<tipoflete>".cambio_texto($f->tipoflete)."</tipoflete>
				<ocurre>".cambio_texto($f->ocurre)."</ocurre>
				<idsucursalorigen>".cambio_texto($f->idsucursalorigen)."</idsucursalorigen>
				<ndestino>".cambio_texto($f->ndestino)."</ndestino>
				<nsucdestino>".cambio_texto($f->nsucdestino)."</nsucdestino>
				<idsucursaldestino>".cambio_texto($f->idsucursaldestino)."</idsucursaldestino>
				<condicionpago>".cambio_texto($f->condicionpago)."</condicionpago>
				<idremitente>".cambio_texto($f->idremitente)."</idremitente>
				<rncliente>".cambio_texto($f->rncliente)."</rncliente>
				<rrfc>".cambio_texto($f->rrfc)."</rrfc>
				<rcelular>".cambio_texto($f->rcelular)."</rcelular>
				<rcalle>".cambio_texto($f->rcalle)."</rcalle>
				<rnumero>".cambio_texto($f->rnumero)."</rnumero>
				<rcp>".cambio_texto($f->rcp)."</rcp>
				<rpoblacion>".cambio_texto($f->rpoblacion)."</rpoblacion>
				<rtelefono>".cambio_texto($f->rtelefono)."</rtelefono>
				<rcolonia>".cambio_texto($f->rcolonia)."</rcolonia>
				<iddestinatario>".cambio_texto($f->iddestinatario)."</iddestinatario>
				<dncliente>".cambio_texto($f->dncliente)."</dncliente>
				<drfc>".cambio_texto($f->drfc)."</drfc>
				<dcelular>".cambio_texto($f->dcelular)."</dcelular>
				<dcalle>".cambio_texto($f->dcalle)."</dcalle>
				<dnumero>".cambio_texto($f->dnumero)."</dnumero>
				<dcp>".cambio_texto($f->dcp)."</dcp>
				<dpoblacion>".cambio_texto($f->dpoblacion)."</dpoblacion>
				<dtelefono>".cambio_texto($f->dtelefono)."</dtelefono>
				<dcolonia>".cambio_texto($f->dcolonia)."</dcolonia>	
				<entregaocurre>".cambio_texto($f->entregaocurre)."</entregaocurre>
				<entregaead>".cambio_texto($f->entregaead)."</entregaead>
				<restrinccion>".cambio_texto($f->restrinccion)."</restrinccion>
				<totalpaquetes>".cambio_texto($f->totalpaquetes)."</totalpaquetes>
				<totalpeso>".cambio_texto($f->totalpeso)."</totalpeso>
				<totalvolumen>".cambio_texto($f->totalvolumen)."</totalvolumen>
				<emplaye>".cambio_texto($f->emplaye)."</emplaye>
				<bolsaempaque>".cambio_texto($f->bolsaempaque)."</bolsaempaque>
				<totalbolsaempaque>".cambio_texto($f->totalbolsaempaque)."</totalbolsaempaque>
				<avisocelular>".cambio_texto($f->avisocelular)."</avisocelular>
				<celular>".cambio_texto($f->celular)."</celular>
				<valordeclarado>".cambio_texto($f->valordeclarado)."</valordeclarado>
				<acuserecibo>".cambio_texto($f->acuserecibo)."</acuserecibo>
				<cod>".cambio_texto($f->cod)."</cod>
				<recoleccion>".cambio_texto($f->recoleccion)."</recoleccion>
				<observaciones>".cambio_texto($f->observaciones)."</observaciones>
				<tflete>".cambio_texto($f->tflete)."</tflete>
				<tdescuento>".cambio_texto($f->tdescuento)."</tdescuento>
				<ttotaldescuento>".cambio_texto($f->ttotaldescuento)."</ttotaldescuento>
				<tcostoead>".cambio_texto($f->tcostoead)."</tcostoead>
				<trecoleccion>".cambio_texto($f->trecoleccion)."</trecoleccion>
				<tseguro>".cambio_texto($f->tseguro)."</tseguro>
				<totros>".cambio_texto($f->totros)."</totros>
				<texcedente>".cambio_texto($f->texcedente)."</texcedente>
				<tcombustible>".cambio_texto($f->tcombustible)."</tcombustible>
				<subtotal>".cambio_texto($f->subtotal)."</subtotal>
				<tiva>".cambio_texto($f->tiva)."</tiva>
				<ivaretenido>".cambio_texto($f->ivaretenido)."</ivaretenido>
				<total>".cambio_texto($f->total)."</total>
				<efectivo>".cambio_texto($f->efectivo)."</efectivo>
				<cheque>".cambio_texto($f->cheque)."</cheque>
				<banco>".cambio_texto($f->banco)."</banco>
				<ncheque>".cambio_texto($f->ncheque)."</ncheque>
				<tarjeta>".cambio_texto($f->tarjeta)."</tarjeta>
				<trasferencia>".cambio_texto($f->trasferencia)."</trasferencia>
				<usuario>".cambio_texto($f->usuario)."</usuario>
				<fecha_registro>".cambio_texto($f->fecha_registro)."</fecha_registro>
				<hora_registro>".cambio_texto($f->hora_registro)."</hora_registro>";
				
				$s = "SELECT * FROM guiaventanilla_detalle where idguia='$_GET[folio]'";
				$rx = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rx)>0){
					$cant = mysql_num_rows($rx);
					while($fx = mysql_fetch_object($rx)){
						$xml .= "
						<idmercancia>0</idmercancia>
						<cantidad>$fx->cantidad</cantidad>
						<descripcion>".strtoupper(cambio_texto($fx->descripcion))."</descripcion>
						<contenido>".strtoupper(cambio_texto($fx->contenido))."</contenido>
						<peso>".round($fx->peso,2)."</peso>
						<volumen>".round($fx->volumen,2)."</volumen>
						<importe>".round($fx->importe,2)."</importe>
						";	
					}
					
				}else{
					$totalimporte = 0;
					$xml .= "<cantidad>$fx->cantidad</cantidad>
					<descripcion>0</descripcion>
					<contenido>0</contenido>
					<peso>0</peso>
					<volumen>0</volumen>
					<importe>0</importe>
					";
				}
				$xml .= "<valor_totalimporte>".cambio_texto($totalimporte+$tenvase)."</valor_totalimporte>
				<encontroevaluacion>$cant</encontroevaluacion>
			<tipototales>1</tipototales>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontrados>0</encontrados>
				</datos>
				</xml>";
		}
	}
	
	//poner estado AUTORIZACION PARA CANCELAR
	if($_GET[accion] == 6){
		
		$s = "insert into historial_cancelacionysustitucion
		set guia = '$_GET[folio]', accion='ENVIO AUTORIZACION', tipo='LOCAL', 
		sucursal='$_SESSION[IDSUCURSAL]', fecha=current_date,
		hora=current_time, usuario = '$_SESSION[IDUSUARIO]';";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "update guiasventanilla set estado = 'AUTORIZACION PARA CANCELAR' where id='$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "insert into cancelacionguiasventanilla set guia='$_GET[folio]',motivocancelacion='$_GET[motivo]',
		usuario='$_GET[Nombre]',fecha=current_date";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<guardado>$s</guardado>
				</datos>
				</xml>");
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>1</guardado>
				</datos>
				</xml>";
	}
	
	//poner estado CANCELADO
	if($_GET[accion] == 7){
		$s = "insert into historial_cancelacionysustitucion
		set guia = '$_GET[folio]', accion='CANCELADO', tipo='LOCAL', 
		sucursal='$_SESSION[IDSUCURSAL]', fecha=current_date,
		hora=current_time, usuario = '$_SESSION[IDUSUARIO]';";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "UPDATE pagoguias SET pagado = 'C', fechacancelacion=current_date WHERE guia = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<guardado>$s</guardado>
				</datos>
				</xml>");
		
		$s = "UPDATE formapago SET fechacancelacion = current_date and procedencia='G' and guia='$_GET[folio]''";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<guardado>$s</guardado>
				</datos>
				</xml>");
		
		$s = "update guiasventanilla set estado = 'CANCELADO' where id='$_GET[folio]'";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<guardado>$s</guardado>
				</datos>
				</xml>");
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>1</guardado>
				</datos>
				</xml>";
	}
	echo $xml;
	
?>
