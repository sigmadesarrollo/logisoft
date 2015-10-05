<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../../Conectar.php');
	$link = Conectarse('webpmm');

if($_GET['accion']==1){// BUSCAR CP ---- CatalogoSector.php
	$s = "SELECT cpo.codigopostal, cc.id AS idcolonia,cc.descripcion AS colonia
		FROM catalogocolonia cc
		INNER JOIN catalogocodigopostal cpo ON cc.cp=cpo.codigopostal
		WHERE cpo.codigopostal='".$_GET['cp']."' ";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<cp>".$f->codigopostal."</cp>";
		if($cant>1){
			$sql_r = mysql_query($s,$link) or die("error en linea ".__LINE__);
			while($row=mysql_fetch_object($sql_r)){
			$xml.="<idcolonia>".$row->idcolonia."</idcolonia>";
			$xml.="<colonia>".$row->colonia."</colonia>";
			}
		}else{
			$xml.="<idcolonia>".$f->idcolonia."</idcolonia>";
			$xml.="<colonia>".$f->colonia."</colonia>";
		}
		$xml.="<encontro>".$cant."</encontro>";
		$xml.="</datos>
				</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
}


if($_GET['accion']==2){// BUSCAR SECTOR ---- CatalogoSector.php
	$s ="SELECT CS.id,CS.descripcion,CS.idsucursal,CS.sucursal,CSD.cp,CSD.idcolonia,CSD.colonia 
FROM  catalogosector AS CS INNER JOIN catalogosectordetalle AS CSD 
ON CSD.idsector=CS.id
WHERE CS.id='".$_GET['sector']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> ";
			$xml .= "<datos>";
			$sql_r=mysql_query($s,$link) or die("error en linea ".__LINE__);
			while($row=mysql_fetch_object($sql_r)){
				$xml.="<cp>".$row->cp."</cp>";
				$xml.="<idcolonia>".$row->idcolonia."</idcolonia>";
				$xml.="<colonia>".$row->colonia."</colonia>";
			}
			$xml.="</datos>";
			$xml.= "<datos>
			<codigo>".$f->id."</codigo>
			<descripcion>".$f->descripcion."</descripcion>
			<idsucursal>".$f->idsucursal."</idsucursal>
			<sucursal>".$f->sucursal."</sucursal>
			<encontro>$cant</encontro>
			</datos>";
			$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
}else if($_GET['accion']==3){// BUSCAR Sucursal ---- CatalogoSector.php
	$s ="SELECT id,prefijo,descripcion FROM catalogosucursal WHERE id='".$_GET['sucursal']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> ";
			$xml.= "<datos>
			<idsucursal>".$f->id."</idsucursal>
			<prefijo>".cambio_texto($f->prefijo)."</prefijo>
			<sucursal>".cambio_texto($f->descripcion)."</sucursal>
			<encontro>$cant</encontro>
			</datos>";
			$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
}


?>