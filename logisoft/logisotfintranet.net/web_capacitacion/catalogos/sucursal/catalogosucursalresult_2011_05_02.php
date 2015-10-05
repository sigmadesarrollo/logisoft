<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	header('Content-type: text/xml');
	require_once('../../Conectar.php');	
	$link=Conectarse('webpmm');
	$tipo=$_GET['tipo']; $codigo=$_GET['codigo']; $cp=$_GET['cp']; $accion=$_GET['accion'];	
	if($accion==1){	
	$s ="SELECT cs.id, cs.prefijo, cs.idsucursal, cs.descripcion, cs.monitoreo,
	cs.concesion, cs.comision, cs.ventas, cs.recibido, cs.porcead, cs.porcrecoleccion,
	cs.lectores, cs.iva, cs.bascula, cs.cajachica, cs.horariolimiterecoleccion,cs.calle,
	cs.numero, cs.crucecalles, cs.cp, cs.colonia, cs.poblacion, cs.municipio, cs.estado,
	cs.pais, cs.telefono, cs.fax, cs.frontera, fleterecibido, fleteenviado,sobrepeso, 
	zonahoraria FROM catalogosucursal cs
	WHERE cs.id='$codigo'";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);
			if($f->fax==""){$f->fax=0;}	
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<id>$f->id</id>
			<prefijo>".cambio_texto($f->prefijo)."</prefijo>
			<idsucursal>".cambio_texto($f->idsucursal)."</idsucursal>
			<descripcion>".cambio_texto($f->descripcion)."</descripcion>
			<monitoreo>$f->monitoreo</monitoreo>
			<concesion>$f->concesion</concesion>
			<comision>$f->comision</comision>
			<ventas>$f->ventas</ventas>
			<recibido>$f->recibido</recibido>
			<porcead>$f->porcead</porcead>
			<recibido>$f->recibido</recibido>
			<porcrecoleccion>$f->porcrecoleccion</porcrecoleccion>
			<lectores>$f->lectores</lectores>
			<bascula>$f->bascula</bascula>
			<cajachica>$f->cajachica</cajachica>			
			<horariolimiterecoleccion>$f->horariolimiterecoleccion</horariolimiterecoleccion>
			<iva>$f->iva</iva>
			<calle>".cambio_texto($f->calle)."</calle>
			<numero>".cambio_texto($f->numero)."</numero>
			<entrecalles>".cambio_texto($f->crucecalles)."</entrecalles>
			<cp>$f->cp</cp>
			<colonia>".cambio_texto($f->colonia)."</colonia>
			<estado>".cambio_texto($f->estado)."</estado>
			<poblacion>".cambio_texto($f->poblacion)."</poblacion>
			<municipio>".cambio_texto($f->municipio)."</municipio>
			<pais>".cambio_texto($f->pais)."</pais>
			<telefono>".cambio_texto($f->telefono)."</telefono>
			<fax>".cambio_texto($f->fax)."</fax>
			<frontera>".cambio_texto($f->frontera)."</frontera>
			<fleterecibido>".cambio_texto($f->fleterecibido)."</fleterecibido>
			<fleteenviado>".cambio_texto($f->fleteenviado)."</fleteenviado>
			<sobrepeso>".cambio_texto($f->sobrepeso)."</sobrepeso>
			<encontro>$cant</encontro>
			<zonahoraria>".cambio_texto($f->zonahoraria)."</zonahoraria>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
		echo $xml;	
	}	
?>