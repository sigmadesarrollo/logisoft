<?
	header('Content-type: text/xml');
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion] == 1){
		/*if($_GET[campo]=="nick")
			$_GET[campo] = "ccn.nick";
		else
			$_GET[campo] = "cc.".$_GET[campo];*/
			
		if($_GET[nick]!="")
			$andnick 		= " and ccn.nick = '$_GET[nick]' ";
		if($_GET[rfc]!="")
			$andrfc 		= " and cc.rfc = '$_GET[rfc]' ";
		if($_GET[id]!="")
			$andid 			= " and cc.id= $_GET[id] ";
		if($_GET[nombre]!="")
			$andnombre 		= " and cc.nombre like '%$_GET[nombre]%' ";
		if($_GET[paterno]!="")
			$andpaterno 	= " and cc.paterno like '%$_GET[paterno]%' ";
		if($_GET[materno]!="")
			$andmaterno 	= " and cc.materno like '%$_GET[materno]%' ";
		if($_GET[ciudad]!="")
			$andciudad 		= " and d.poblacion like '$_GET[ciudad]%' ";
		
		$todosands = $andciudad.$andmaterno.$andpaterno.$andnombre.$andid.$andrfc.$andnick;
		
		if($_GET[tiposol]!=""){
			if($_GET[tiposol]=="PREPAGADA"){
				$inner = "INNER JOIN generacionconvenio AS gc ON cc.id = gc.idcliente
				AND gc.prepagadas = 1 AND CURRENT_DATE<gc.vigencia";
			}else{
				$inner = "INNER JOIN generacionconvenio AS gc ON cc.id = gc.idcliente
				AND (gc.consignacioncaja=1 OR gc.consignacionkg=1 OR gc.consignaciondescuento=1)
				AND CURRENT_DATE<gc.vigencia";
			}
		}
		
		$s = "select ccn.nick, cc.rfc, cc.id, cc.nombre, cc.paterno, cc.materno, ifnull(d.poblacion,'') as sucursal,
		IF(ISNULL(gc.folio),'NO','SI') AS convenio, IF(ISNULL(sc.folio),'NO','SI') AS credito
		from catalogocliente as cc
		INNER JOIN direccion d ON cc.id = d.codigo AND d.origen = 'cl'
		left join catalogosucursal as cs on cc.sucursal = cs.id
		left join catalogoclientenick as ccn on cc.id = ccn.cliente
		LEFT JOIN generacionconvenio gc ON cc.id = gc.idcliente AND gc.estadoconvenio = 'ACTIVADO'
		LEFT JOIN solicitudcredito sc ON cc.id = sc.cliente AND sc.estado = 'ACTIVADO'
		$inner
		where 1=1 $todosands 
		group by cc.id";
		
		//echo $s;
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
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
				<sucursal>".cambio_texto(strtoupper($f->sucursal))."</sucursal>
				<credito>".cambio_texto(strtoupper($f->credito))."</credito>
				<convenio>".cambio_texto(strtoupper($f->convenio))."</convenio>";
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
		}
	}
	
	if($_GET[accion] == 2){
		if($_GET[tiposol]!=""){
			if($_GET[tiposol]=="PREPAGADA"){
				$inner = "INNER JOIN generacionconvenio AS gc ON cc.id = gc.idcliente
				AND gc.prepagadas = 1 AND CURRENT_DATE<gc.vigencia";
			}else{
				$inner = "INNER JOIN generacionconvenio AS gc ON cc.id = gc.idcliente
				AND (gc.consignacioncaja=1 OR gc.consignacionkg=1 OR gc.consignaciondescuento=1)
				AND CURRENT_DATE<gc.vigencia";
			}
		}
		
		$todosands = "";
		
		if($_GET[nick]!="")
			$andnick 		= " and ccn.nick= '$_GET[nick]' ";
		if($_GET[rfc]!="")
			$andrfc 		= " and cc.rfc = '$_GET[rfc]' ";
		if($_GET[id]!="")
			$andid 			= " and cc.id= $_GET[id] ";
		if($_GET[nombre]!="")
			$andnombre 		= " and cc.nombre like '%$_GET[nombre]%' ";
		if($_GET[paterno]!="")
			$andpaterno 	= " and cc.paterno like '%$_GET[paterno]%' ";
		if($_GET[materno]!="")
			$andmaterno 	= " and cc.materno like '%$_GET[materno]%' ";
		if($_GET[ciudad]!="")
			$andciudad 		= " and d.poblacion like '$_GET[ciudad]%' ";
		
		$todosands = $andciudad.$andmaterno.$andpaterno.$andnombre.$andid.$andrfc.$andnick;
		
		$s = "select ccn.nick, cc.rfc, cc.id, cc.nombre, cc.paterno, cc.materno, ifnull(d.poblacion,'') as sucursal,
		IF(ISNULL(gc.folio),'NO','SI') AS convenio, IF(ISNULL(sc.folio),'NO','SI') AS credito 
		from catalogocliente as cc
		INNER JOIN direccion d ON cc.id = d.codigo AND d.origen = 'cl'
		left join catalogoclientenick as ccn on cc.id = ccn.cliente
		left join catalogosucursal as cs on cc.sucursal = cs.id
		LEFT JOIN generacionconvenio gc ON cc.id = gc.idcliente AND gc.estadoconvenio = 'ACTIVADO'
		LEFT JOIN solicitudcredito sc ON cc.id = sc.cliente AND sc.estado = 'ACTIVADO'
		$inner
		where 1=1 $todosands
		$andnick $andrfc $andid $andnombre $andpaterno $andmaterno $andciudad
		".(($_GET[personamoral]!="")?" and cc.personamoral = '$_GET[personamoral]'":"")."
		group by cc.id";
		
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0 && $todosands!=""){
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
		}
	}
	echo $xml;
	
?>
