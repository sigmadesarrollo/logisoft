<?


	//header('Content-type: text/xml');


	require_once("../Conectar.php");


	$l = Conectarse("webpmm");


	


	if($_GET[accion] == 1){


		$s = "select em.cantidadbolsa, em.estado, cd.descripcion as ndestino, em.destino as iddestino, 


		cs.descripcion as nsucursal, cs.id as idsucursal, cs.prefijo, em.recoleccion,


		em.bolsaempaque, em.emplaye, em.totalbolsaempaque, em.totalemplaye, cd.poblacion as npobdestino 


		from evaluacionmercancia as em


		inner join catalogodestino as cd on em.destino = cd.id


		inner join catalogosucursal as cs on cd.sucursal = cs.id


		where folio = $_GET[folio] and sucursal = ".$_SESSION[IDSUCURSAL]."";


		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);


		if(mysql_num_rows($r)>0){


			$cant0 = mysql_num_rows($r);


			$f = mysql_fetch_object($r);


			


			$pt_ead=0;


			$pt_recoleccion=0;


			


			$s = "select costo, porcada from configuradorservicios where servicio = 6";


			$rcs = mysql_query($s,$l) or die($s);


			$fcs = mysql_fetch_object($rcs);


			$v_costo 	= ($fcs->costo=="")?"0":$fcs->costo;


			$v_porcada 	= ($fcs->porcada=="")?"0":$fcs->porcada;


			


			$s = "select cdd.costoead


			from catalogodestino as cdd where cdd.id = $f->iddestino";


			$rb = mysql_query($s,$l) or die($s);


			$fb = mysql_fetch_object($rb);


			$pt_ead=$fb->costoead;


			//se keda como cero hasta que se haga la recoleccion;


			$pt_recoleccion=0;


			


			$s = "select * from configuradorgeneral";


			$rcg = mysql_query($s,$l) or die($s);


			$fcg = mysql_fetch_object($rcg);


			$por_combustible 	= ($fcg->cargocombustible=="")?0:$fcg->cargocombustible;


			$max_des 			= ($fcg->descuento=="")?0:$fcg->descuento;


			$iva				= ($fcg->iva=="")?0:$fcg->iva;


			$iva_retenido		= ($fcg->ivaretenido=="")?0:$fcg->ivaretenido;


			


			$s = "select catalogotiempodeentregas.*, hour(current_time) as tiempo 


			from catalogotiempodeentregas where idorigen = $_GET[idsucorigen] and iddestino = $f->idsucursal";


			$rm = mysql_query($s,$l) or die($s);


			$fm = mysql_fetch_object($rm);


			


			$horasparamensaje = array(0,12,14,16,8,9,10,11,13,15,17);


			


			if($fm->incrementartiempo==1){


				$restrinccion = "<restrincciones>Si documenta antes de las 


				".$horasparamensaje[$fm->siocurre]." se incrementaran ".$f->aincrementar. "hrs<restrincciones>";	


				$mashoras = $f->aincrementar;


			}else{


				$restrinccion = "<restrincciones>0</restrincciones>";


				$mashoras = 0;


			}


			


			$s = "select max(avisocel) as avisocel,


			max(acuserecibo) as acuserecibo,


			max(cod) as cod,


			max(cod) as bolemp,


			max(cod) as emp


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


				<estado>".strtoupper(cambio_texto($f->estado))."</estado>


				<ndestino>".strtoupper(cambio_texto($f->ndestino))." - ".strtoupper(cambio_texto($f->prefijo))."</ndestino>


				<iddestino>$f->iddestino</iddestino>


				<nsucursal>".strtoupper(cambio_texto($f->nsucursal))."</nsucursal>


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


				<ead>".cambio_texto($fm->tentregaad+$mashoras)."</ead>


				<pt_ead>".cambio_texto($pt_ead)."</pt_ead>


				<pt_recoleccion>".cambio_texto($pt_recoleccion)."</pt_recoleccion>


				<pt_iva>".cambio_texto($iva)."</pt_iva>


				<pt_ivaretenido>".cambio_texto($iva_retenido)."</pt_ivaretenido>


				<por_combustible>".cambio_texto($por_combustible)."</por_combustible>


				<max_des>".cambio_texto($max_des)."</max_des>


				<por_cada>".cambio_texto($v_porcada)."</por_cada>


				<scosto>".cambio_texto($v_costo)."</scosto>


				$restrinccion


			";


			$s = "select cantidad, descripcion, contenido, pesototal, volumen from evaluacionmercanciadetalle
			 where evaluacion = $_GET[folio] and sucursal = ".$_SESSION[IDSUCURSAL]."";


			$rx = mysql_query($s,$l) or die($s);


			if(mysql_num_rows($rx)>0){


				$cant = mysql_num_rows($rx);


				while($fx = mysql_fetch_object($rx)){


					$xml .= "<cantidad>$fx->cantidad</cantidad>


					";


					$xml .= "<descripcion>".strtoupper(cambio_texto($fx->descripcion))."</descripcion>


					";


					$xml .= "<contenido>".strtoupper(cambio_texto($fx->contenido))."</contenido>


					";


					$xml .= "<peso>".round($fx->pesototal,2)."</peso>


					";


					$xml .= "<volumen>".round($fx->volumen,2)."</volumen>


					";


					$s = "select costo from configuraciondetalles where 


					(select distancia from catalogodistancias where (idorigen=$_GET[idsucorigen] and iddestino=$f->idsucursal) or (iddestino=$_GET[idsucorigen] and idorigen=$f->idsucursal)) between zoi and zof


					and ".(($fx->volumen>$fx->pesototal)?$fx->volumen:$fx->pesototal)." between kgi and kgf";


					echo "<br>$s<br>";


					$rb = mysql_query($s,$l) or die($s);


					$fb = mysql_fetch_object($rb);


					


					$xml .= "<importe>".round($fb->costo,2)."</importe>


					";


				}


			}else{


				$xml .= "<cantidad>$fx->cantidad</cantidad>


				<descripcion>0</descripcion>


				<contenido>0</contenido>


				<peso>0</peso>


				<volumen>0</volumen>


				<importe>0</importe>


				";


			}


			$xml .= "<encontroevaluacion>$cant</encontroevaluacion>


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


	


	if($_GET[accion] == 2){


		$s = "select concat_ws(' ', cc.nombre, cc.paterno, cc.materno) as ncliente, cc.rfc, cc.celular 


		from catalogocliente as cc where id = $_GET[idcliente]";


		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);


		if(mysql_num_rows($r)>0){


			$cant0 = mysql_num_rows($r);


			$f = mysql_fetch_object($r);


			


			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 


			<datos>


				<encontro>$cant0</encontro>


				<ncliente>".strtoupper(cambio_texto($f->ncliente))."</ncliente>


				<rfc>".strtoupper(cambio_texto($f->rfc))."</rfc>


				<celular>".cambio_texto($f->celular)."</celular>


			";


			$s = "select d.id, d.calle, d.numero, d.cp, d.colonia, d.poblacion, d.telefono


			from direccion as d


			where origen = 'cl' and codigo = $_GET[idcliente]";


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


	


	echo $xml;


	


?>


