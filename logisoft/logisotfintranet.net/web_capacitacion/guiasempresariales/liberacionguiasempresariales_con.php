<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	
if($_GET['accion']==1){// BUSCAR No.venta liberacionguiasempresariales.php
		$s = "SELECT SGE.id,SGE.foliotipo,SGE.prepagada,SGE.desdefolio,SGE.hastafolio,SGE.usuario,GC.costo,GC.prepagadas 
FROM solicitudguiasempresariales AS SGE
INNER JOIN generacionconvenio AS GC ON GC.folio=SGE.idconvenio
WHERE SGE.foliotipo='".$_GET['id']."' AND SGE.prepagada='".$_GET['prepagada']."' AND SGE.STATUS=1 ";
		$r = mysql_query($s,$link);
		$f = mysql_fetch_object($r);
		$cant = mysql_num_rows($r);	
		if(mysql_num_rows($r)>0){
				
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">";
			
			$xml.="<datosX>";
			$xml.="<venta>".cambio_texto($f->foliotipo)."</venta>";
			$xml.="<r>".cambio_texto($f->prepagada)."</r>";
			$xml.="<vendedor>".cambio_texto($f->usuario)."</vendedor>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="</datosX>";

			$xml.="</xml>";				
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
}else if($_GET['accion']==2){
		$s = "SELECT sge.prepagada,sge.foliotipo, sge.id,gcn.costo,gcn.prepagadas
		FROM solicitudguiasempresariales AS sge
		INNER JOIN generacionconvenio AS gcn ON sge.idconvenio = gcn.folio
		WHERE sge.status = 1 AND
		SUBSTRING('".$_GET['folioi']."',4,9) 
		BETWEEN SUBSTRING(sge.desdefolio,4,9) AND SUBSTRING(sge.hastafolio,4,9)
		AND SUBSTR('".$_GET['folioi']."',1,3) = SUBSTRING(sge.desdefolio,1,3) 
		AND SUBSTRING('".$_GET['foliof']."',4,9) 
		BETWEEN SUBSTRING(sge.desdefolio,4,9) AND SUBSTRING(sge.hastafolio,4,9)
		AND SUBSTRING('".$_GET['foliof']."',13,1) 
		BETWEEN SUBSTRING(sge.desdefolio,13,1) AND SUBSTRING(sge.hastafolio,13,1) 
		AND sge.foliotipo='".$_GET['id']."' AND sge.prepagada='".$_GET['prepagada']."'";
		//die($s);
		
		$r = mysql_query($s,$link);
		$f = mysql_fetch_object($r);
		$costo=$f->costo;
		if(mysql_num_rows($r)>0){
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">";
			$xml.="<datosX>";
			$xml.="<encontro>1</encontro>";
			$folioi=(int)substr($_GET['folioi'],3,-1);
			$foliof=(int)substr($_GET['foliof'],3,-1);
			$i=(int)substr($_GET['folioi'],0,-10);
			$f=substr($_GET['folioi'],-1);
			$c=0;
			for($folioi;$folioi<=$foliof;$folioi++){
					$r=mysql_query("SELECT id FROM guiasempresariales where id='".$i.str_pad($folioi,9,"0",STR_PAD_LEFT).$f."'",$link);					
					$c++;			
					if(mysql_num_rows($r)<1){						
						$existe=0;
						break;
					}else{
						$existe=1;
					}
			}
			$xml.="<existe>".$existe."</existe>";
			if($f->prepagadas==1){
				$total=$c*$costo;
				$xml.="<total>".$total."</total>";
			}else{
				$xml.="<total>0</total>";
			}
			$xml.="</datosX>";
			$xml.="</xml>";	
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<existe>0</existe>
			<total>0</total>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
}

?>