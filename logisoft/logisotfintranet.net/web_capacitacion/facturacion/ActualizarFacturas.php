<?
	
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);
	
	function getXMLSing($xmlHon,$priv_key){
		//Carga Certificado
		$xml = new DomDocument();
		$xml->loadXML($xmlHon);
		//Carga prosedimiento de proceso de cadena original
		$xsl = new DomDocument;
		$xsl->load("ostring.xsl");
		$proc = new xsltprocessor();
		$proc->importStyleSheet($xsl);
		$original =$proc->transformToXML($xml);
		//firma la cadena original
		
		//$fp = $cert[0]['certificates']['key'];
		//$priv_key = $f['key'];
		//die($f['key']);
		//fclose($fp);
		$pkeyid = openssl_get_privatekey($priv_key);
		openssl_sign($original, $signature, $pkeyid,OPENSSL_ALGO_MD5);
		openssl_free_key($pkeyid);
		//coloca el sello en xml
		$esqueletonew=$xmlHon;
		$esqueletonew=str_replace("#1#",base64_encode($signature),$esqueletonew);
		$xmlReturn[1]=$esqueletonew;
		$xmlReturn[2]=$original;
		$xmlReturn[3]=base64_encode($signature);
		return $xmlReturn;
	}
	
	$s = "select * from certificates";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_array($r);
		$priv_key = $f['key'];
	
	$s = "SELECT LOCATE('sello=\"',xml),CONCAT(
	SUBSTRING(xml,1,LOCATE('sello=\"',xml)+6),
	'#1#',
	SUBSTRING(xml,LOCATE(' anoAprobacion=\"',xml)-1,(LENGTH(xml)+50)-(LOCATE(' anoAprobacion=\"',xml)))) xml,
	folio
	FROM facturacion
	where folio>0";
	$r = mysql_query($s,$l) or die($s);
	while($f = mysql_fetch_object($r)){
		$nuevoxml = getXMLSing($f->xml,$priv_key);
		$s = "update facturacion set
		xml='$nuevoxml[1]', cadenaoriginal='$nuevoxml[2]' where folio = '$f->folio'";
		mysql_query($s,$l) or die($s);
		
		echo $f->folio."<br>";
	}
?>