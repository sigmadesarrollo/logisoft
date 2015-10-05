<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	require_once("../clases/ValidaConvenio.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		
		$s = "SELECT id from guiasempresariales where id = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			echo '({"encontro":"-2"})';
		}else{		
			$s = "SELECT sge.prepagada, gcn.folio, sge.id
			FROM solicitudguiasempresariales AS sge
			INNER JOIN generacionconvenio AS gcn ON sge.idconvenio = gcn.folio AND CURRENT_DATE < gcn.vigencia
			WHERE sge.status = 1 AND
			SUBSTRING('$_GET[folio]',4,9) 
			BETWEEN SUBSTRING(sge.desdefolio,4,9) AND SUBSTRING(sge.hastafolio,4,9)
			AND SUBSTR('$_GET[folio]',1,3) = SUBSTRING(sge.desdefolio,1,3) 
			AND SUBSTRING('$_GET[folio]',13,1) BETWEEN SUBSTRING(sge.desdefolio,13,1) AND SUBSTRING(sge.hastafolio,13,1)";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				echo '({"encontro":"1", "idconvenio":"'.$f->folio.'", "prepagadas":"'.$f->prepagada.'"})';
			}elseif($_GET[folio]!=""){
				echo '({"encontro":"0"})';
			}
		}
	}
?>