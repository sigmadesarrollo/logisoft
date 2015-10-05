<?
	header('Content-type: text/xml');
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	
	if($_GET[accion] == 1){
		
		$_GET[valor] = strtoupper($_GET[valor]);
		
		if($_GET[campo]=="nick")
			$_GET[campo] = "cpn.nick";
		else
			$_GET[campo] = "cp.".$_GET[campo];
		
		$s = "SELECT cpn.nick, cp.rfc, cp.id, cp.nombre, cp.paterno, cp.materno 
		FROM catalogoprospecto AS cp
		LEFT JOIN catalogoprospectonick AS cpn ON cp.id = cpn.prospecto
		WHERE $_GET[campo] ".(($_GET[campo]=="cp.id")?" = '$_GET[valor]'":" like '$_GET[valor]%'")."
		".(($_GET[personamoral])?" and cp.personamoral = '$_GET[personamoral]' ":"")."
		group by cp.id";
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
				<materno>".cambio_texto(strtoupper($f->materno))."</materno>";
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
