<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once("../Conectar.php");
	require_once("../clases/ValidaConvenio.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion] == 1){
	$s = "SELECT todasemana, lunes, martes, miercoles, jueves, viernes, sabado
		  FROM catalogodestino WHERE sucursal=".$_GET['idsucorigen']."";
	$r 	= mysql_query($s,$l) or die($s);
	$f	= mysql_fetch_object($r);
	
		$totalhorasincre = 0;
			$arredias = array("lunes","martes","miercoles","jueves","viernes","sabado");
			
			$rest2= "<restr2>0</restr2>";
			if($f->todasemana==0){
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

$s = "select costo, porcada from configuradorservicios where servicio = 6";
$rcs = mysql_query($s,$l) or die($s);
$fcs = mysql_fetch_object($rcs);
$v_costo 	= ($fcs->costo=="")?"0":$fcs->costo;
$v_porcada 	= ($fcs->porcada=="")?"0":$fcs->porcada;
			
$s = "select costoead from catalogodestino where id = ".$_GET['idsucorigen']."";
$rb = mysql_query($s,$l) or die($s);
$fb = mysql_fetch_object($rb);
$pt_ead=$fb->costoead;

//se keda como cero hasta que se haga la recoleccion;
$s 	 = "SELECT costorecoleccion FROM catalogodestino WHERE id=".$_GET['sucdestino']." AND restringirrecoleccion=0";
$re  = mysql_query($s,$l) or die($s);
$ree =  mysql_fetch_object($re);

$pt_recoleccion=(($ree->costorecoleccion!="")?$ree->costorecoleccion : 0);
			
$s = "select * from configuradorgeneral";
$rcg = mysql_query($s,$l) or die($s);
$fcg = mysql_fetch_object($rcg);
$por_combustible 	= ($fcg->cargocombustible=="")?0:$fcg->cargocombustible;
$max_des 			= ($fcg->descuento=="")?0:$fcg->descuento;
$iva_retenido		= ($fcg->ivaretenido=="")?0:$fcg->ivaretenido;
$pagominimocheques	= ($fcg->pagominimocheques=="")?0:$fcg->pagominimocheques;
$pesominimodesc		= ($fcg->pesominaplicardescuento=="")?0:$fcg->pesominaplicardescuento;
			
$s = "select catalogotiempodeentregas.*, hour(current_time) as tiempo 
from catalogotiempodeentregas where idorigen = ".$_GET[idsucorigen]." and iddestino = ".$_GET['sucdestino']."";
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
			
			$s = "SELECT costo, costoextra, limite, porcada FROM configuradorservicios WHERE servicio=2";
			$sq = mysql_query($s,$l) or die($s);
			$fq =  mysql_fetch_object($sq);
		$emplaye ="";
		$emplaye ="<costoe>".cambio_texto($fq->costo)."</costoe>";
		$emplaye .="<costoextrae>".cambio_texto($fq->costoextra)."</costoextrae>";
		$emplaye .="<limitee>".cambio_texto($fq->limite)."</limitee>";
		$emplaye .="<porcadae>".cambio_texto($fq->porcada)."</porcadae>";
			
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
			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 			<datos>";
			$xml .=	"<avisocelular>".cambio_texto($fz->avisocel)."</avisocelular>";
			$xml .=	"<acuserecibo>".cambio_texto($fz->acuserecibo)."</acuserecibo>";
			$xml .=	"<cod>".cambio_texto($fz->cod)."</cod>";
			$xml .=	"<bolemp>".cambio_texto($fz->bolemp)."</bolemp>";
			$xml .=	"<emp>".cambio_texto($fz->emp)."</emp>";
			$xml .= "<iva>".cambio_texto($iva)."</iva>";
			$xml .= "<porcada>".cambio_texto($v_porcada)."</porcada>";
			$xml .= "<costo>".cambio_texto($v_costo)."</costo>";
			$xml .= "<costoead>".cambio_texto($pt_ead)."</costoead>";
			$xml .= "<recoleccion>".cambio_texto($pt_recoleccion)."</recoleccion>";			
			$xml .= "<combustible>".cambio_texto($por_combustible)."</combustible>";
			$xml .= "<max_des>".cambio_texto($max_des)."</max_des>";
			$xml .= "<pagominimocheques>".cambio_texto($pagominimocheques)."</pagominimocheques>";
			$xml .= "<pesominimodesc>".cambio_texto($pesominimodesc)."</pesominimodesc>";
			$xml .= "<ivaretenido>".cambio_texto($iva_retenido)."</ivaretenido>";
			$xml .= "<ocu>".cambio_texto($fm->tentrega+$mashoras)."</ocu>";
			$xml .= "<ead>".cambio_texto($fm->tentregaad+$mashoras+$totalhorasincre)."</ead>";
			$xml .= $restrinccion;
			$xml .= $rest2;
			$xml .= $emplaye;
			$xml .="</datos>
					</xml>";	
	}	
	
	//solicitar sucursales
	if($_GET[accion] == 3){
		$s = "SELECT cs.id, cs.descripcion, cd.poblacion, cd.restringiread, 
		cd.restringirrecoleccion, cd.restringirporcobrar, cd.deshabilitarconvenio 
		FROM catalogosucursal AS cs 
		INNER JOIN catalogodestino AS cd ON cs.id = cd.sucursal
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
				<ead>".$f->restringiread."</ead>
				<restringirrecoleccion>".$f->restringirrecoleccion."</restringirrecoleccion>
				<restringirporcobrar>".$f->restringirporcobrar."</restringirporcobrar>
				<deshabilitarconvenio>".$f->deshabilitarconvenio."</deshabilitarconvenio>
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
		
		$s = "select concat(numero,CHAR(ASCII(letra)+aumento)) as folio, principales from (
				select concat(
					(select idsucursal from catalogosucursal where id = $_GET[idsucursalorigen])
				,lpad(if(substring(max(id),4,8)+1=100000000,1,substring(max(id),4,8)+1),8,'0')) as numero,
				substring(max(id),12,1) as letra,
				if(substring(max(id),4,8)+1=100000000,1,0) AS aumento,
				substring((select idsucursal from catalogosucursal where id = $_GET[idsucursalorigen]),1,3) as principales
				from guiasventanilla where substring(id,1,3) = (select idsucursal from catalogosucursal where id = $_GET[idsucursalorigen])
			) as t1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$newfolio = $f->folio;
		if($newfolio==""){
			$newfolio = $f->principales."00000001A";
		}
		
		if($_GET[idsucursalorigen]==$_GET[idsucursaldestino]){
			$estado = "ALMACEN DESTINO";
		}else{
			$estado = "ALMACEN ORIGEN";
		}
		
		$s = "insert into guiasventanilla set id='$newfolio', estado='ALMACEN ORIGEN', 
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
		tarjeta='$_GET[tarjeta]', trasferencia='$_GET[trasferencia]', usuario='".$_SESSION[NOMBREUSUARIO]."', 
		idusuario='".$_SESSION[IDUSUARIO]."', fecha_registro=current_date, hora_registro=current_time";
		$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
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
		//echo "que peddooooo";
		$s = "SELECT
		gv.id, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, gv.fechaentrega, gv.factura, 
		gv.estado, gv.tipoflete, gv.ocurre, gv.idsucursalorigen, 
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
		gv.usuario, gv.fecha_registro, gv.hora_registro, date_format(current_date, '%d/%m/%Y') as fechaactual
		FROM guiasventanilla as gv
		inner join catalogosucursal as csd on gv.idsucursaldestino = csd.id
		inner join catalogodestino as cd on gv.iddestino = cd.id
		inner join catalogocliente as ccr on gv.idremitente = ccr.id
		inner join direccion as dr on gv.iddireccionremitente = dr.id
		inner join catalogocliente as ccd on gv.iddestinatario = ccd.id
		inner join direccion as dd on gv.iddirecciondestinatario = dd.id
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
				<fechaentrega>".cambio_texto($f->fecha)."</fechaentrega>
				<factura>".cambio_texto($f->estado)."</factura>
				<tipoflete>".cambio_texto($f->tipoflete)."</tipoflete>
				<ocurre>".cambio_texto($f->ocurre)."</ocurre>
				<idsucursalorigen>".cambio_texto($f->idsucursalorigen)."</idsucursalorigen>
				<ndestino>".cambio_texto($f->ndestino)."</ndestino>
				<nsucdestino>".cambio_texto($f->nsucdestino)."</nsucdestino>
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
				
				$s = "select em.cantidad, cd.descripcion, em.contenido, em.pesototal, em.volumen 
				from evaluacionmercanciadetalle as em
				inner join catalogodescripcion as cd on em.descripcion = cd.id
				where em.evaluacion = $f->evaluacion";
				$rx = mysql_query($s,$l) or die($s);
				$tpesokg = 0;
				$tpesovo = 0;
				$tenvase = 0;
				$totalimporte = 0;
				$tproductos = 0;
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
							
						if($fx->descripcion=="ENVASE(S)"){
							//echo "entro en envases";
							$s = "select costo from configuraciondetalles where 
							(select IFNULL(SUM(distancia),0) AS distancia from catalogodistancias where 
							 (idorigen=$f->idsucursalorigen and iddestino=$f->idsucursaldestino) 
							 or (iddestino=$f->idsucursalorigen and idorigen=$f->idsucursaldestino)) between zoi and zof
							and kgi = -1";
							//echo "<br>$s<br>";
							$rb = mysql_query($s,$l) or die($s);
							$fb = mysql_fetch_object($rb);
							$xml .= "<importe>".round($fb->costo,2)."</importe>
							";
							$tenvase += $fb->costo;
						}else{
							//echo "entro en normales";
							$tproductos++;
							$tpesokg += round($fx->pesototal,2);
							$tpesovo += round($fx->volumen,2);
							//echo "$tpesokg<br>$tpesovo";
							$s = "select costo from configuraciondetalles where 
							(select IFNULL(SUM(distancia),0) AS distancia from catalogodistancias where 
							 (idorigen=$f->idsucursalorigen and iddestino=$f->idsucursaldestino) 
							 or (iddestino=$f->idsucursalorigen and idorigen=$f->idsucursaldestino)) between zoi and zof
							and ".(($fx->volumen>$fx->pesototal)?$fx->volumen:$fx->pesototal)." between kgi and kgf";
							//echo "<br>$s<br>";
							$rb = mysql_query($s,$l) or die($s);
							$fb = mysql_fetch_object($rb);
							if($fb->costo<10){
								$xml .= "<importe>".round($fb->costo*(($fx->volumen>$fx->pesototal)?$fx->volumen:$fx->pesototal),2)."</importe>
								";
							}else{
								$xml .= "<importe>".round($fb->costo,2)."</importe>
								";
							}
						}
					}
					
					if($tproductos>0){
						//echo "entro en normales totales";
						$s = "select costo from configuraciondetalles where 
						(select IFNULL(SUM(distancia),0) AS distancia from catalogodistancias where 
						(idorigen=$f->idsucursalorigen and iddestino=$f->idsucursaldestino) or 
						(iddestino=$f->idsucursalorigen and idorigen=$f->idsucursaldestino)) between zoi and zof
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
		
		if($_GET[accion] == 8){		
			$vc = new ValidaConvenio('','','','');
			$flete = $vc->ObtenerFlete($_GET[convenio], $_GET[idorigen], $_GET[iddestino], $_GET[descripcion], $_GET[peso], $_GET[cantidad]);
			$f = split(",",$flete);
			if($flete!=""){
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<flete>".cambio_texto($f[0])."</flete>
				<excedente>".cambio_texto($f[1])."</excedente>
				</datos>
				</xml>";
			}else{
				$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<flete>".cambio_texto($f[0])."</flete>
				<excedente>".cambio_texto($f[1])."</excedente>
				</datos>
				</xml>";
			}			
		}
		
		if($_GET[accion] == 9){
			if(mail($_GET[direccion],"Cotización","Ejemplo de envio de email de texto plano\n\nWebEstilo.\nhttp://www.webestilo.com/\n Manuales para desarrolladores web.\n","FROM: PMM <papay0@hotmail.com>\n")){
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<envio>1</envio>
				</datos>
				</xml>";
			}else{
				$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<envio>0</envio>
				</datos>
				</xml>";
			}
		}
		echo $xml;
?>
