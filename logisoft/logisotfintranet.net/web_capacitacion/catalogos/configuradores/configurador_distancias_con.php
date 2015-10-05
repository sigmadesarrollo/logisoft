<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "select * from catalogodistancias where idorigen = $_GET[idorigen] and iddestino = $_GET[iddestino]";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>$cant</encontro>
			<idorigen>$f->idorigen</idorigen>
			<iddestino>$f->iddestino</iddestino>
			<distancia>$f->distancia</distancia>
			<desde>$_GET[desde]</desde>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			<idorigen>$_GET[idorigen]</idorigen>
			<iddestino>$_GET[iddestino]</iddestino>
			<desde>$_GET[desde]</desde>
			</datos>
			</xml>";
		}
		echo $xml;
	}
	
	if($_GET[accion]==2){
		
		$s = "select * from catalogodistancias where idorigen = $_GET[idorigen] and iddestino = $_GET[iddestino]";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$s = "update catalogodistancias set idorigen = $_GET[idorigen], iddestino = $_GET[iddestino], distancia = $_GET[distancia], 
			usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_date where idorigen = $_GET[idorigen] and iddestino = $_GET[iddestino]";
			mysql_query($s,$l) or die($s);
		}else{		
			$s = "insert into catalogodistancias set idorigen = $_GET[idorigen], iddestino = $_GET[iddestino], distancia = $_GET[distancia], 
			usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_date";
			mysql_query($s,$l) or die($s);
		}
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<insertado>1</insertado>
			<idorigen>$_GET[idorigen]</idorigen>
			<iddestino>$_GET[iddestino]</iddestino>
			<distancia>$_GET[distancia]</distancia>
			</datos>
			</xml>";
		echo $xml;
	}
?>
