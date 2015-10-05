<?
	header ("Content-Disposition: attachment; filename=reporte_".$_GET[mes]."_".$_GET[ano].".txt;" ); 
	header ("Content-Type: application/force-download;"); 


	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT folio,DATE_FORMAT(fecha, '%d/%m/%Y %H:%i:%s') fecha,
		DATE_FORMAT(fechacancelacion, '%d/%m/%Y %H:%i:%s') fechacancelacion, rfc,
		total+sobmontoafacturar+otrosmontofacturar AS total,
		otrosiva+sobiva+iva AS iva,
		if(facturaestado='CANCELADO',0,1) estado,
		xml
		FROM facturacion 
		WHERE folio > 0 and month(fecha)=$_GET[mes] and year(fecha)=$_GET[ano]";
	$r = mysql_query($s,$l) or die($s);
	while($f = mysql_fetch_object($r)){
		
		$f->rfc = str_replace(" ","",str_replace("-","",$f->rfc));
		
		$aproba = split(" noAprobacion=",$f->xml);
		$aproba = split('"',$aproba[1]);
		$serie = split(" serie=",$f->xml);
		$serie = split('"',$serie[1]);
		echo "|".$f->rfc."|".$serie[1]."|".$f->folio."|".$aproba[1]."|".$f->fecha."|".number_format($f->total,2,".","")."|".number_format($f->iva,2,".","")."|1|I||||\n";
		
		if($f->estado==0){
			echo "|".$f->rfc."|".$serie[1]."|".$f->folio."|".$aproba[1]."|".$f->fecha."|".number_format($f->total,2,".","")."|".number_format($f->iva,2,".","")."|0|I||||\n";
		}
	}
?>